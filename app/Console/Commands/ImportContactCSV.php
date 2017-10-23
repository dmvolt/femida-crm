<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Contact;
use Illuminate\Console\Command;
use Log;
use App\User;

class ImportContactCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ImportContactCSV';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import csv data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all()->pluck(null, 'name');
        foreach (['3.csv'] as $_file)
        {
            $data = array_map('str_getcsv', file(public_path($_file)));
            foreach ($data as $_contact)
            {
                $contact = Contact::firstOrNew(['name' => $_contact[0], 'phone' => $_contact[1]]);
                $contact->name = $_contact[0];
                $contact->phone = $_contact[1];
                $contact->email = $_contact[2];

                if ( $users->get($_contact[3]) == null )
                {
                    $user = new User();
                    $user->name = $_contact[3];
                    $user->email = $_contact[3];
                    $user->password = \Hash::make('12345');
                    $user->role_id = User::MANAGER_ID;
                    $user->department_id = 1;
                    $user->save();

                    $users->put($_contact[3], $user);
                }

                $contact->user_id = $users->get($_contact[3])->id;
                $contact->created_at = isset($_contact[4]) ? Carbon::parse($_contact[4]) : Carbon::now();
                $contact->save();
            }
        }
    }
}

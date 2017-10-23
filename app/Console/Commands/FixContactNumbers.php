<?php

namespace App\Console\Commands;

use App\Contact;
use Illuminate\Console\Command;

class FixContactNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:FixContactNumbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        foreach (Contact::all() as $_contact)
        {
            $_contact->phone = preg_replace('|[^0-9\\+]|','',$_contact->phone);
            $_contact->save();
        }
    }
}

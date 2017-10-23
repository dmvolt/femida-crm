<?php

namespace App\Console\Commands;

use App\Contact;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Mail;

class SendMessageNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SendMessageNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SendMessageNotification';

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
        $client = new \GuzzleHttp\Client();
        $notifications = DatabaseNotification::whereNotifiableType('App\Contact')->whereNull('read_at')->limit(15)->get();

        foreach ($notifications as $notification)
        {
            $contact = $notification->notifiable;
            $message = $notification->data;

            if ( $message['type'] == 'sms' )
            {
                if ($contact->phone)
                {
                    $phoneUrl = urlencode($contact->phone);
                    $messageUrl = urlencode(strip_tags($message['text']));

                    $url = 'http://smsc.ru/sys/send.php?charset=utf-8&login='.config('sms.login').'&psw='.config('sms.password').'&sender='.urlencode(config('sms.sender')).'&phones='.$phoneUrl.'&mes='.$messageUrl;
                    $res = $client->request('GET', $url);

                    echo $res->getBody();
                }
            }
            else
            {
                if ( $contact->email )
                {
                    Mail::send('emails.message', ['messageText' => $message['text']], function ($m) use ($contact, $message)
                    {
                        $m->subject($message['name']);
                        $m->to($contact->email, $contact->name);
                    });
                }
            }
            $notification->markAsRead();
        }
    }
}

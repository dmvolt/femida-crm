<?php

namespace App\Notifications;

use App\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\SmscRu\SmscRuMessage;
use NotificationChannels\SmscRu\SmscRuChannel;


class MessageNotification extends Notification
{
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmscRuChannel::class];
    }

    public function toSmscRu($notifiable)
    {
        return SmscRuMessage::create($this->message['text']);
    }
	

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->message;
    }
}

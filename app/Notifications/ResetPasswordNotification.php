<?php


namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{

    public function toMail($notifiable)
    {

        $message = (new MailMessage)
            ->line('Вы получили данное письмо, потому что кто-то сделал запрос на востановление пароля. Если письмо отправили не вы, просто проигнорируйте его.')
            ->action('Сброс пароля', url('password/reset', $this->token))
            ->subject('Востановление пароля');


        return $message;
    }

}
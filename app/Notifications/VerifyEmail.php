<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends BaseVerifyEmail implements ShouldQueue
{
    use Queueable;

    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('Подтверждение адреса электронной почты — YouConf')
            ->greeting('Добро пожаловать в YouConf!')
            ->line('Пожалуйста, нажмите на кнопку ниже, чтобы подтвердить ваш адрес электронной почты.')
            ->action('Подтвердить почту', $url)
            ->line('Ссылка действительна в течение 60 минут.')
            ->line('Если вы не регистрировались на YouConf, просто проигнорируйте это письмо.');
    }
}

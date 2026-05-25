<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpertMailing extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $subject,
        public string $body,
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.expert-mailing', [
                'body'      => $this->body,
                'firstName' => $notifiable->first_name ?? null,
            ]);
    }
}

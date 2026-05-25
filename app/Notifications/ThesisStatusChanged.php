<?php

namespace App\Notifications;

use App\Models\Thesis;
use App\Models\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ThesisStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Thesis $thesis,
        public Status $oldStatus,
        public Status $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {

        $data = [
            'thesis_id' => $this->thesis->id,
            'message' => "Статус заявки {$this->thesis->title} изменён",
            'custom_data' => [
                'old' => $this->oldStatus->name,
                'new' => $this->newStatus->name
            ]
        ];

        return $data;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Статус заявки изменён',
            'message' => sprintf(
                'Статус вашей работы «%s» изменён с «%s» на «%s»',
                $this->thesis->title,
                $this->oldStatus->name,
                $this->newStatus->name
            ),
            'thesis_id' => $this->thesis->id,
            'old_status' => $this->oldStatus->name,
            'new_status' => $this->newStatus->name,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Статус вашей работы изменён — YouConf')
            ->view('emails.thesis-status-changed', [
                'firstName'   => $notifiable->first_name,
                'thesisTitle' => $this->thesis->title,
                'oldStatus'   => $this->oldStatus->name,
                'newStatus'   => $this->newStatus->name,
                'url'         => url("/theses/{$this->thesis->id}"),
            ]);
    }
}

<?php

namespace App\Notifications;

use App\Models\ParticipantProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipantStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(private ParticipantProfile $participant)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = $this->statusLabel();

        $mail = (new MailMessage)
            ->subject('Actualizacion del estado de tu registro')
            ->greeting('Hola '.$notifiable->name)
            ->line('Tu registro fue '.$statusLabel.'.');

        if ($this->participant->status_notes) {
            $mail->line('Notas: '.$this->participant->status_notes);
        }

        return $mail->action('Ir a mi panel', route('participant.dashboard'))
            ->line('Gracias por participar en el Evento Deportivo y Cultural.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Estado de registro actualizado',
            'message' => 'Tu registro fue '.$this->statusLabel().'.',
            'status' => $this->participant->status,
            'notes' => $this->participant->status_notes,
            'link' => route('participant.dashboard'),
        ];
    }

    private function statusLabel(): string
    {
        return match ($this->participant->status) {
            'accepted' => 'aceptado',
            'rejected' => 'rechazado',
            default => 'actualizado',
        };
    }
}

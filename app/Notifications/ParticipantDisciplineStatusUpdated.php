<?php

namespace App\Notifications;

use App\Models\Discipline;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipantDisciplineStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        private Discipline $discipline,
        private string $status,
        private ?string $notes
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = $this->statusLabel();

        $mail = (new MailMessage)
            ->subject('Actualizacion de la disciplina: '.$this->discipline->name)
            ->greeting('Hola '.$notifiable->name)
            ->line('La disciplina "'.$this->discipline->name.'" fue '.$statusLabel.'.');

        if ($this->notes) {
            $mail->line('Notas: '.$this->notes);
        }

        return $mail->action('Ver mis disciplinas', route('participant.disciplines.index'))
            ->line('Puedes gestionar tus disciplinas desde el portal.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Actualizacion de disciplina',
            'message' => 'La disciplina "'.$this->discipline->name.'" fue '.$this->statusLabel().'.',
            'status' => $this->status,
            'notes' => $this->notes,
            'link' => route('participant.disciplines.index'),
        ];
    }

    private function statusLabel(): string
    {
        return match ($this->status) {
            'accepted' => 'aceptada',
            'rejected' => 'rechazada',
            default => 'actualizada',
        };
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class DataChangeEmailNotification extends Notification
{
    use Queueable;

    public function __construct($data)
    {
        $this->data = $data;
        $this->ticket = $data['ticket'];
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return $this->getMessage();
    }

    public function getMessage()
    {
        return (new MailMessage)
        ->subject('Notificación: :action', ['action' => $this->data['action']])
            ->greeting('Hola,')
            ->line('Acción: :action', ['action' => $this->data['action']])
            ->line("Solicitud de: ".$this->ticket->author_name) 
            ->line("Asusto del soporte: ".$this->ticket->title)
            ->line("Descripcion: ".Str::limit($this->ticket->content, 200))
            ->action('Observa tu Ticket', route('admin.tickets.show', $this->ticket->id))
            ->line('Favor de atenderlo, Muchas Gracias!')
            ->line(config('app.name') . ' TI')
            ->salutation(' ');
    }
}

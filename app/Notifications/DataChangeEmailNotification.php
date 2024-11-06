<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
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
            ->subject('Notificación: ' . $this->data['action']) // Interpolación de la variable correctamente en el subject
            ->greeting('Hola,')
            ->line('Acción: ' . $this->data['action']) // Interpolación de la variable correctamente en el contenido
            ->line('Solicitud de: ' . $this->ticket->author_name)
            ->line('Asunto del soporte: ' . $this->ticket->title) // Corregí "Asusto" a "Asunto"
            ->line('Descripción: ' . Str::limit($this->ticket->content, 200)) // Corregí "Descripcion" a "Descripción"
            ->action('Observa tu Ticket', route('admin.tickets.show', $this->ticket->id))
            ->line('Favor de atenderlo, Muchas Gracias!')
            ->line(config . 'LOAD' . ' TI')
            ->salutation(' ');
    }

}

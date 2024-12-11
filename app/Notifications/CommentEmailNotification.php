<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CommentEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {


        return (new MailMessage)
            ->subject('Nuevo comentario en el ticket: ' . $this->comment->ticket->title) // Título con el nombre del ticket
            ->greeting('Hola ' . $notifiable->name . ',') // Saludo al analista
            ->line('El usuario ' . $this->comment->author_name . ' ha agregado un comentario al ticket asignado.') // Notificación de comentario
            ->line('Comentario: ' . $this->comment->comment_text) // El contenido del comentario
            ->action('Ver ticket', route('tickets.show', $this->comment->ticket->id)) // Acción con el link al ticket
            ->line('Gracias por atender este ticket y por tu excelente trabajo.') // Mensaje final
            ->salutation('Saludos, Equipo de Soporte');
    }
}

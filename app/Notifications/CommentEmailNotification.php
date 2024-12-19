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
            ->subject('💬 Tienes un comentario en el ticket de soporte')
            ->greeting('Hola! 👋 ,' . $this->comment->ticket->author_name . '')
            ->line('Asunto del ticket: ' . $this->comment->ticket->title . '')
            ->line('')
            ->line('')
            ->line(Str::limit('Respuesta: ' . $this->comment->comment_text, 500))
            ->action('Ver ticket completo', route(optional($notifiable)->id ? 'admin.tickets.show' : 'tickets.show', $this->comment->ticket->id))
            ->line('')
            ->line('')
            ->line('Gracias por usar nuestro sistema de soporte.')
            ->salutation('LOAD TI');
    }
}

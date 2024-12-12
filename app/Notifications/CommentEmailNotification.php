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
            ->subject('Nuevo comentario en el ticket: ' . $this->comment->ticket->title)
            ->greeting('Hola,')
            ->line('comentarios en el ticket: ' . $this->comment->ticket->title . ':')
            ->line('')
            ->line(Str::limit($this->comment->comment_text, 500))
            ->action('Ver ticket completo', route(optional($notifiable)->id ? 'admin.tickets.show' : 'tickets.show', $this->comment->ticket->id))
            ->line('Gracias por usar nuestro sistema de soporte.')
            ->salutation('SALUDOS, LOAD TI');
    }
}

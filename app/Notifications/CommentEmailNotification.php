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
        // Definición de variables para mayor claridad
        $ticketTitle = $this->comment->ticket->title;
        $authorName = $this->comment->ticket->author_name;
        $commentText = Str::limit($this->comment->comment_text, 50);
        $ticketId = $this->comment->ticket->id;

        // Determinar la ruta del ticket según el tipo de usuario
        $route = $this->getTicketRoute($notifiable, $ticketId);

        return (new MailMessage)
            ->subject(__('💬 Tienes un comentario en el ticket de soporte'))
            ->greeting(__('Hola! 👋 :name', ['name' => $authorName]))
            ->line(__('Asunto del ticket: :title', ['title' => $ticketTitle]))
            ->line('')
            ->line(__('Respuesta: :response', ['response' => $commentText]))
            ->action(__('Ver ticket completo'), $route)
            ->line('')
            ->line(__('Gracias por usar nuestro sistema de soporte.'))
            ->salutation(__('Saludos, :company', ['company' => 'LOAD TI']));
    }

    /**
     * Obtener la ruta del ticket según el tipo de usuario.
     */
    protected function getTicketRoute($notifiable, $ticketId)
    {
        // Verificamos si el usuario tiene un ID asignado (posiblemente un administrador)
        return route(optional($notifiable)->id ? 'admin.tickets.show' : 'tickets.show', $ticketId);
    }
}

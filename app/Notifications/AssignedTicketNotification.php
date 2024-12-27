<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignedTicketNotification extends Notification
{
    use Queueable;

    protected $ticket;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $ticket
     * @return void
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
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
        // Extraemos la información necesaria del usuario y el ticket
        $userName = $notifiable->name;
        $ticketTitle = $this->ticket->title;
        $ticketId = $this->ticket->id;

        // Creamos la ruta al ticket
        $ticketRoute = route('admin.tickets.show', $ticketId);

        // Retornamos la notificación
        return (new MailMessage)
            ->subject(__('🎫 Se te ha asignado un nuevo ticket'))
            ->greeting(__('Hola, :name 👋', ['name' => $userName]))
            ->line(__('📌 Se te ha asignado un nuevo soporte: :title', ['title' => $ticketTitle]))
            ->action(__('Ver ticket'), $ticketRoute)
            ->line('')
            ->line(__('Gracias por el apoyo.'))
            ->salutation(__('Saludos, :company', ['company' => 'LOAD TI']));
    }
}

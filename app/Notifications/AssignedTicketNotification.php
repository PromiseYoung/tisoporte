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
        return (new MailMessage)
            ->subject('🎫 Se te ha asignado un nuevo ticket')
            ->greeting('Hola, 👋')
            ->line('📌 Se te ha asignado un nuevo soporte: ' . $this->ticket->title)
            ->action('Ver ticket', route('admin.tickets.show', $this->ticket->id))
            ->line('')
            ->line('Gracias por el apoyo')
            ->salutation('LOAD TI');
    }

}

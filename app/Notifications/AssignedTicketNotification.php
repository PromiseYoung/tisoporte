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
        // Aseguramos que la propiedad $ticket esté definida y tenga los valores esperados.
        $ticketTitle = $this->ticket->title ?? 'Título no disponible';

        // Personalizamos el saludo mostrando el nombre del usuario que recibe la notificación.
        $userName = $notifiable->name ?? 'Usuario';

        return (new MailMessage)
            ->subject('¡Se te ha asignado un nuevo Ticket!')
            ->greeting('Hola ' . $userName . ',')  // Usamos el nombre del usuario en el saludo
            ->line('Se ha solicitado un nuevo soporte:')
            ->line('')
            ->line('Título del ticket: ' . $ticketTitle)  // Mostramos el título del ticket
            ->action('Ver ticket', route('admin.tickets.show', $this->ticket->id))
            ->line('Gracias por usar nuestro sistema de soporte.')
            ->salutation('Saludos, LOAD TI');
    }
}

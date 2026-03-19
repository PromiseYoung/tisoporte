<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignedTicketNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $comment;
    protected $priority;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $ticket
     * @return void
     */

    // Asignar el ticket y obtener el último comentario y prioridad
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
        $this->comment = $ticket->comments()->latest()->first();
        $this->priority = $ticket->priority ? $ticket->priority->name : 'N/A';

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

    // Construir el correo de notificación
    public function toMail($notifiable)
    {
        // Extraemos la información necesaria del usuario y el ticket
        $userName = $notifiable->name;
        $ticketTitle = $this->ticket->title;
        $ticketId = $this->ticket->id;

        // Información adicional del ticket
        $ticketCategory = $this->ticket->category->name ?? 'N/A';
        $ticketPriority = $this->ticket->priority->name ?? 'N/A';
        $ticketStatus = $this->ticket->status->name ?? 'Pendiente';

        // Datos del autor del ticket
        $authorName = $this->ticket->author_name ?? 'Usuario no registrado';
        $authorEmail = $this->ticket->author_email ?? 'Sin correo';


        // Creamos la ruta al ticket
        $ticketRoute = route('admin.tickets.show', $ticketId);

        // Retornamos la notificación
        return (new MailMessage)
            ->subject(__('Nuevo ticket de soporte: :title', ['title' => $ticketTitle]))
            ->greeting(__('Hola, :name', ['name' => $userName]))
            ->line(__('Se ha creado un nuevo ticket en el sistema de soporte.'))
            ->line('---')
            ->line(__('📝 Asunto: :title', ['title' => $ticketTitle]))
            ->line(__('📂 Categoría: :category', ['category' => $ticketCategory]))
            ->line(__('⚡ Prioridad: :priority', ['priority' => $ticketPriority]))
            ->line(__('📌 Estado: :status', ['status' => $ticketStatus]))
            ->line(__('👤 Solicitante: :author (:email)', [
                'author' => $authorName,
                'email' => $authorEmail
            ]))
            ->line('---')
            ->action(__('Ver ticket completo'), $ticketRoute)
            ->salutation(__('Atentamente, :company', ['company' => 'LOAD TI | Mesa de Soporte']));

    }
}

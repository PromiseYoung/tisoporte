<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class DataChangeEmailNotification extends Notification
{
    use Queueable;

    protected $data;
    protected $ticket;

    /**
     * Create a new notification instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->ticket = $data['ticket'];  // Asegúrate de que $data tenga un índice 'ticket'
    }

    /**
     * Determine the channels the notification should be sent on.
     *
     * @param  mixed  $notifiable
     * @return array
     */

    // Definir los canales de notificación
    public function via($notifiable)
    {
        return ['mail'];  // Corregí el formato de retorno para que sea un array
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
        return $this->getMessage();  // Usamos el método para obtener el mensaje
    }

    /**
     * Crea el mensaje para el correo.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    // Generar el contenido del mensaje
    public function getMessage()
    {
        // Extraemos la información del ticket y el analista
        $analista = $this->ticket->assigned_to_user;
        // Definición de variables para mayor claridad
        $ticketTitle = $this->ticket->title;
        $authorName = $this->ticket->author_name;
        // Limitar el contenido del ticket para el correo
        $ticketContent = Str::limit($this->ticket->content, 200);
        $ticketId = $this->ticket->id;

        // Creamos la ruta al ticket
        $ticketRoute = route('admin.tickets.show', $ticketId);

        // Retornamos la notificación
        return (new MailMessage)
            ->subject($this->data['action'])
            ->greeting(__('Buen dia, :name 👋', ['name' => $analista->name]))
            ->line('')
            ->line(__('Nombre: :name', ['name' => $authorName]))
            ->line('')
            ->line(__('Asunto: :title', ['title' => $ticketTitle]))
            ->line('')
            ->line('')
            ->line(__('Descripción: :content', ['content' => $ticketContent]))
            ->action(__('Ver ticket completo'), $ticketRoute)
            ->line(__('Gracias por el apoyo.'))
            ->salutation(__('Éxito en tu soporte.'));
    }
}

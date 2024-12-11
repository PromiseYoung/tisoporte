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
    public function toMail($notifiable)
    {
        return $this->getMessage();  // Usamos el método para obtener el mensaje
    }

    /**
     * Crea el mensaje para el correo.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function getMessage()
    {
        // Verificamos si $this->ticket tiene los datos correctos
        $ticketTitle = $this->ticket->title ?? 'No disponible';
        $ticketContent = Str::limit($this->ticket->content, 200) ?? 'No hay descripción disponible';
        $ticketAuthor = $this->ticket->author_name ?? 'Desconocido';

        return (new MailMessage)
            ->subject('Notificación: ' . $this->data['action']) // Interpolación de la variable correctamente en el subject
            ->greeting('Solicito de tu apoyo,')
            ->line('Te ha solicitado: ' . $ticketAuthor)
            ->line('')
            ->line('Asunto del soporte: ' . $ticketTitle) // Corregí "Asusto" a "Asunto"
            ->line('')
            ->line('Descripción: ' . $ticketContent) // Corregí "Descripcion" a "Descripción"
            ->action('Consultar Ticket', route('admin.tickets.show', $this->ticket->id))
            ->line('Por favor de atenderlo, Muchas Gracias!')
            ->salutation('Saludos, LOAD TI');
    }
}

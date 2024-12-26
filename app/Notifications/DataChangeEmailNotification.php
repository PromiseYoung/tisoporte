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
        $analista = $this->ticket->assigned_to_user;

        return (new MailMessage)
            ->subject($this->data['action'])
            ->greeting('Hola,' . $analista->name . '👋')
            ->line('')
            ->line("Usuario: " . $this->ticket->author_name)
            ->line('')
            ->line('')
            ->line("Nombre del Soporte: " . $this->ticket->title)
            ->line('')
            ->line('')
            ->line("Descripción breve: " . Str::limit($this->ticket->content, 200))
            ->action('Ver ticket completo', route('admin.tickets.show', $this->ticket->id))
            ->line('Gracias por el apoyo ')
            ->salutation('Exito en tu soporte');
    }
}

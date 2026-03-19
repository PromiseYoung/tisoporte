<?php
namespace App\Observers;

use App\Notifications\AssignedTicketNotification;
use App\Notifications\DataChangeEmailNotification;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\Notification;

class TicketActionObserver
{
    /**
     * Handle the Ticket "created" event.
     *
     * @param  \App\Ticket  $model
     * @return void
     */
    public function created(Ticket $model)
    {
        // Define los datos de la notificación
        $data = [
            'action' => 'Han subido un nuevo ticket!',
            'model_name' => 'Ticket',
            'ticket' => $model,
        ];

        // Obtén todos los usuarios con el rol de Admin de manera eficiente
        // Utiliza el método whereHas para filtrar usuarios con el rol de Admin
        // y luego usa el método get() para obtener la colección de usuarios
        // Esto evita la carga de relaciones innecesarias y mejora el rendimiento
        // al reducir la cantidad de consultas a la base de datos.
        // Además, se asegura de que solo se envíe la notificación si hay usuarios
        // disponibles para recibirla.
        // Esto es útil para evitar enviar notificaciones vacías o innecesarias.

        $users = User::whereHas('roles', function ($q) {
            $q->where('title', 'ADMIN');
        })->get();

        // Verifica si hay usuarios antes de enviar la notificación
        if ($users->isNotEmpty()) {
            Notification::send($users, new DataChangeEmailNotification($data));
        }
    }

    /**
     * Handle the Ticket "updated" event.
     *
     * @param  \App\Ticket  $model
     * @return void
     */
    public function updated(Ticket $model)
    {
        // Verifica si el campo 'assigned_to_user_id' ha cambiado
        if ($model->getOriginal('assigned_to_user_id')) {
            $user = $model->assigned_to_user;

            // Verifica si el usuario asignado no es nulo
            if ($user) {
                // Enviar notificación al usuario asignado
                Notification::send([$user], new AssignedTicketNotification($model));
            }
        }
    }
}

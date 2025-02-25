<?php

namespace App;

use App\Localidad;
use App\Scopes\AgentScope;
use App\Traits\Auditable;
use App\Notifications\CommentEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ticket extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable;

    public $table = 'tickets';

    protected $appends = [
        'attachments',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'content',
        'status_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'priority_id',
        'category_id',
        'author_name',
        'author_email',
        'assigned_to_user_id',
        'localidad_id',  // Agregar este campo
    ];


    public static function boot()
    {
        parent::boot();

        Ticket::observe(new \App\Observers\TicketActionObserver);

        static::addGlobalScope(new AgentScope);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'ticket_id', 'id');
    }

    public function getAttachmentsAttribute()
    {
        return $this->getMedia('attachments');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function assigned_to_user()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }

    public function scopeFilterTickets($query)
    {
        $query->when(request()->input('priority'), function ($query) {
            $query->whereHas('priority', function ($query) {
                $query->whereId(request()->input('priority'));
            });
        })
            ->when(request()->input('category'), function ($query) {
                $query->whereHas('category', function ($query) {
                    $query->whereId(request()->input('category'));
                });
            })
            ->when(request()->input('status'), function ($query) {
                $query->whereHas('status', function ($query) {
                    $query->whereId(request()->input('status'));
                });
            });
    }

    public function sendCommentNotification($comment)
    {
        // Crear la consulta para obtener los usuarios que deben recibir la notificación
        $usersQuery = \App\User::query();

        // Filtrar usuarios con el rol 'Analista TI' relacionados al ticket
        $usersQuery->whereHas('roles', function ($q) {
            $q->where('title', 'Analista TI');
        })
            ->where(function ($q) {
                // Filtrar usuarios que tengan comentarios sobre este ticket
                $q->whereHas('comments', function ($q) {
                    $q->whereTicketId($this->id);
                })
                    // O usuarios asignados a este ticket
                    ->orWhereHas('tickets', function ($q) {
                    $q->whereId($this->id);
                });
            });

        // Incluir administradores si no hay comentarios o asignaciones
        if (!$comment->user_id && !$this->assigned_to_user_id) {
            $usersQuery->orWhereHas('roles', function ($q) {
                $q->where('title', 'Admin');
            });
        }

        // Excluir al usuario que hizo el comentario
        if ($comment->user_id) {
            $usersQuery->where('id', '!=', $comment->user_id);
        }

        // Obtener los usuarios
        $users = $usersQuery->get();

        // Crear la notificación
        $notification = new CommentEmailNotification($comment);

        // Enviar la notificación a los usuarios
        Notification::send($users, $notification);

        // Si el comentario tiene un autor y el ticket tiene un correo de autor, notificar al autor
        if ($comment->user_id && $this->author_email) {
            Notification::route('mail', $this->author_email)->notify($notification);
        }
    }
}

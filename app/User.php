<?php

namespace App;

use App\Notifications\CommentEmailNotification;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Hash;

class User extends Authenticatable implements CanResetPassword
{
    use Notifiable, HasApiTokens;

    public $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'author_email', // Added author_email to fillable
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'email_verified_at',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'email_verified_at',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to_user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y/m/d H:i:s A', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y/m/d H:i:s A') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function role()
    {
        return $this->roles()->first();
    }

    public function isAdmin()
    {
        return $this->roles->contains(1);
    }

    // relacion de llave foranea
    public function category()
    {
        return $this->belongsToMany(Category::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function sendCommentNotification($comment)
{
    // Si el autor es un usuario registrado
    $author = $this->author()->first();
    if ($author && method_exists($author, 'notify')) {
        $author->notify(new CommentEmailNotification($comment));
    }

    // Si solo se tiene el correo del autor (por ejemplo, usuarios externos)
    elseif (!empty($this->author_email)) {
        \Log::info("Enviando notificación por correo a autor externo: {$this->author_email}");
        Notification::route('mail', $this->author_email)
            ->notify(new CommentEmailNotification($comment));
    }
}
}

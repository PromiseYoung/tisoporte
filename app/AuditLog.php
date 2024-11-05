<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $table = 'audit_logs';

    protected $fillable = [
        'description',
        'subject_id',
        'subject_type',
        'user_id',
        'properties',
        'host',
    ];

    protected $casts = [
        'properties' => 'collection',
    ];

    public function user_name()
    {
        return $this->belongsTo(User::class);
    }
    public function asunto()
    {
        return $this->belongsTo(Ticket::class);
    }
    public function getFormattedPropertiesAttribute()
    {
        return collect(json_decode($this->properties, true)); // Para obtener propiedades como colección
    }
    // Método para obtener una propiedad específica
    public function getProperty($key, $default = null)
    {
        $properties = json_decode($this->properties, true);
        return $properties[$key] ?? $default;
    }
}

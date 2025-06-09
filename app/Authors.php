<?php

namespace App;

use App\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authors extends Model
{
    use HasFactory;

    protected $table = 'authors';
    protected $fillable = ['name', 'email'];
    /**
     * Relación de uno a muchos con el modelo Ticket.
     * Un autor puede tener muchos tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}

<?php

namespace App;  // Asegúrate de usar la ruta correcta de espacio de nombres

use App\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    use HasFactory;

    //  el nombre de la tabla
    protected $table = 'localidades';

    // No necesitas incluir 'id' ya que Laravel lo maneja automáticamente
    protected $fillable = ['nombre'];

    /**
     * Relación de uno a muchos con el modelo Ticket.
     * Una localidad puede tener muchos tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}

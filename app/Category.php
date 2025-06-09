<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{


    public $table = 'categories';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'color',
        'user_id',

    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }
    // RELACION con categoria
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

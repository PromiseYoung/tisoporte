<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

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
        'created_at',
        'updated_at',
        'deleted_at',
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

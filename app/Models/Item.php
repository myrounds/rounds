<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $table = 'items';

    protected $fillable = [
        'task_id',
        'name',
        'quantity',
        'notes',
        'completed_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [];

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $table = 'tasks';

    protected $fillable = [
        'group_id',
        'name',
        'quantity',
        'notes',
        'completed_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [];

    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }
}

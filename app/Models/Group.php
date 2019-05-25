<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $table = 'groups';

    protected $fillable = [
        'account_id',
        'assignee_id',
        'name',
        'day',
        'time',
        'address',
        'lat',
        'lon',
        'email',
        'phone',
        'notes',
        'completed_at',
        'active',
        'repeat'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [];

    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    public function assignee()
    {
        return $this->belongsTo('App\Models\Assignee');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $table = 'tasks';

    protected $fillable = [
        'account_id',
        'member_id',
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

    public function member()
    {
        return $this->belongsTo('App\Models\Member');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }
}

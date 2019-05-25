<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Helpers\Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Authenticatable
{
    use HasMultiAuthApiTokens, Notifiable, SoftDeletes;

    protected $table = 'accounts';

    protected $fillable = [
        'category_id',
        'name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    public function groups()
    {
        return $this->hasMany('App\Models\Group');
    }

    public function assignees()
    {
        return $this->hasMany('App\Models\Group');
    }
}

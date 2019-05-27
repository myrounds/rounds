<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Helpers\Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Authenticatable
{
    use HasMultiAuthApiTokens, Notifiable, SoftDeletes;

    protected $table = 'members';

    protected $fillable = [
        'account_id',
        'name',
        'email',
        'phone',
        'license_plate',
        'notes',
        'lat',
        'lon'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at'
    ];

    protected $casts = [];

    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [];

    public function users()
    {
        return $this->hasMany('App\Models\Account');
    }
}

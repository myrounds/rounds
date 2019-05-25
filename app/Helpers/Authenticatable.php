<?php

namespace App\Helpers;

use Illuminate\Foundation\Auth\User;

class Authenticatable extends User
{
    public function getType() {
        return strtolower(class_basename($this));
    }

    public function hasAccessToModel ($model) {
        $userType = $this->getType();
        $modelClassName = is_object($model) ? strtolower(class_basename($model)) : null;
        $userIdKey = $userType === $modelClassName ? 'id' : $userType.'_id';
        if (isset($model[$userIdKey]) && $model[$userIdKey] == $this->id) {
            return true;
        }
        return false;
    }
}

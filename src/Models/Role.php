<?php

namespace SimplePermission\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class,'user_has_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'role_has_permissions');
    }
}
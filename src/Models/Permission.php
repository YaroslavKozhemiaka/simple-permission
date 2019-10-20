<?php

namespace SimplePermission\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class,'user_has_permissions');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }

    public function scopeAssignRole($role)
    {
        return $this->roles()->attach($role);
    }
}
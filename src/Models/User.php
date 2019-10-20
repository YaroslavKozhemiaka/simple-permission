<?php

namespace SimplePermission\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    public function roles()
    {
        return $this->belongsToMany(Role::class,'user_has_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'user_has_permissions');
    }

    public function hasRole($roleName)
    {
        return $this->roles->contains(function ($role) use ($roleName) {
            return $role->name == $roleName;
        });
    }

    public function ableTo($permissionName)
    {
        $rolePermissions = $this->roles->map(function ($role) {
            return $role->permissions;
        })
            ->flatten();

        $permissions = $this->permissions->merge($rolePermissions);

        return $permissions->contains(function ($permission) use ($permissionName) {
            return $permission->name == $permissionName;
        });
    }

    public function allowTo($permission)
    {
        return $this->permissions()->attach($permission);
    }

    public function grantRole($role)
    {
        return $this->roles()->attach($role);
    }

    public function terminateToRole($role)
    {
        return $this->roles()->detach($role);
    }

    public function terminateToPermission($permission)
    {
        return $this->permissions()->detach($permission);
    }
}
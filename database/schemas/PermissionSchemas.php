<?php

namespace SimplePermission\Database\Schemas;

class PermissionSchemas extends Schema implements Schemable
{
    public function createTable()
    {
        $this->createPermissionsTable();
        $this->createRolesTable();
        $this->createUserPermissionTable();
        $this->createUserRoleTable();
        $this->createRolePermissionTable();

        return ['message' => 'The tables have created successful.'];
    }

    private function createPermissionsTable()
    {
        $this->capsule
            ->schema()
            ->create('permissions', function ($table) {

                $table->increments('id');
                $table->string('name')->unique();
                $table->timestamps();

            });
    }

    private function createRolesTable()
    {
        $this->capsule
            ->schema()
            ->create('roles', function ($table) {

                $table->increments('id');
                $table->string('name')->unique();
                $table->timestamps();

            });
    }

    private function createUserPermissionTable()
    {
        $this->capsule
            ->schema()
            ->create('user_has_permissions', function ($table) {

                $table->unsignedInteger('permission_id');
                $table->unsignedInteger('user_id');

                $table->foreign('permission_id')
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');

                $table->primary(['permission_id', 'user_id'],'user_has_permissions_permission');

            });
    }

    private function createUserRoleTable()
    {
        $this->capsule
            ->schema()
            ->create('user_has_roles', function ($table) {

                $table->unsignedInteger('role_id');
                $table->unsignedInteger('user_id');

                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');

                $table->primary(['role_id', 'user_id'],'user_has_roles_role');

            });
    }

    private function createRolePermissionTable()
    {
        $this->capsule
            ->schema()
            ->create('role_has_permissions', function ($table) {

                $table->unsignedInteger('role_id');
                $table->unsignedInteger('permission_id');

                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');

                $table->foreign('permission_id')
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');

                $table->primary(['role_id', 'permission_id'],'role_has_permissions_role_permission');

            });
    }
}
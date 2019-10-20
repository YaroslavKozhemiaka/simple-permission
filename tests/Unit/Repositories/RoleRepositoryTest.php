<?php

namespace Tests\Unit\Repositories;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;
use SimplePermission\Models\Permission;
use SimplePermission\Models\User;
use SimplePermission\Repositories\RoleRepository;

class RoleRepositoryTest extends TestCase
{
    /**
     * @var Capsule
     */
    protected $eloquentCapsule;
    /**
     * @var RoleRepository
     */
    protected $roleRepo;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->eloquentCapsule = $this->initEloquent();
        $this->roleRepo = new RoleRepository();
        $this->user = User::find(1);
    }
    /**
     * @test
     */
    public function a_repository_can_create_role()
    {
        $role = $this->roleRepo->create('User');

        $this->assertEquals('User', $role->name);
    }
    /**
     * @test
     */
    public function a_repository_can_update_role()
    {
        $role = $this->roleRepo->update('Moderator',1);

        $this->assertEquals('Moderator', $role->name);
    }
    /**
     * @test
     */
    public function a_role_should_be_unique()
    {
        $this->expectException('SimplePermission\Exceptions\AlreadyExistNameException');

        $this->roleRepo->create('User');
    }

    /**
     * @test
     */
    public function a_repository_can_delete_role_by_name()
    {
        $wasDeleted = $this->roleRepo->deleteByName('User');

        $this->assertTrue($wasDeleted);
    }
    /**
     * @test
     */
    public function a_repository_can_delete_role_by_id()
    {
        $wasDeleted = $this->roleRepo->deleteById(3);

        $this->assertTrue($wasDeleted);
    }
    /**
     * @test
     */
    public function a_repository_can_attach_permission_to_role()
    {
        $permission = Permission::find(2);

        $role = $this->roleRepo->whereId(1)->grant($permission);

        $this->assertTrue(
            $role->permissions->contains(function ($value) use ($permission) {
                return $value->id === $permission->id;
            })
        );
    }
    /**
     * @test
     */
    public function a_repository_can_assign_role_to_user()
    {
        $role = $this->roleRepo->whereId(1)->assignRoleTo($this->user);

        $this->assertTrue($this->user->hasRole($role->name));
    }
    /**
     * @test
     */
    public function a_repository_can_terminate_role_to_user()
    {
        $roleTerminate = $this->roleRepo->whereId(1)->terminateToUser($this->user);

        $this->assertFalse($this->user->roles->contains(function ($role) use ($roleTerminate) {
            return $role->id == $roleTerminate->id;
        }));
    }
    /**
     * @test
     */
    public function a_repository_can_terminate_permission_to_role()
    {
        $permissionRevoke = Permission::find(2);

        $role = $this->roleRepo->whereId(1)->terminateToPermission($permissionRevoke);

        $this->assertFalse($role->permissions->contains(function ($permission) use ($permissionRevoke) {
            return $permission->id == $permissionRevoke->id;
        }));
    }
    /**
     * @return Capsule
     */
    protected function initEloquent()
    {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'simple_permissions',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4'
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();

        return $capsule;
    }
}
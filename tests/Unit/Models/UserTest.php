<?php

namespace Tests\Unit\Models;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;
use SimplePermission\Models\Permission;
use SimplePermission\Models\Role;
use SimplePermission\Models\User;

class UserTest extends TestCase
{
    /**
     * @var Capsule
     */
    protected $eloquentCapsule;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->eloquentCapsule = $this->initEloquent();
        $this->user = User::find(1);
    }
    /**
     * @test
     */
    public function a_user_can_check_permission()
    {
        $ableTo = $this->user->ableTo('Edit posts');

        $this->assertTrue($ableTo);
    }
    /**
     * @test
     */
    public function a_user_can_check_role()
    {
        $hasRole = $this->user->hasRole('Admin');

        $this->assertTrue($hasRole);
    }
    /**
     * @test
     */
    public function a_user_can_allow_to_permission()
    {
        $permission = Permission::find(1);

        $this->user->allowTo($permission);

        $this->assertTrue($this->user->ableTo($permission->name));
    }
    /**
     * @test
     */
    public function a_user_can_assign_role()
    {
        $role = Role::find(1);

        $this->user->grantRole($role);

        $this->assertTrue($this->user->hasRole($role->name));
    }
    /**
     * @test
     */
    public function a_user_can_terminate_to_role()
    {
        $role = Role::find(1);

        $this->user->terminateToRole($role);

        $this->assertFalse($this->user->hasRole($role->name));
    }
    /**
     * @test
     */
    public function a_user_can_terminate_to_permission()
    {
        $permission = Permission::find(1);

        $this->user->terminateToPermission($permission);

        $this->assertFalse($this->user->ableTo($permission->name));
    }
    /**
     * @return Capsule
     */
    public function initEloquent()
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
<?php

namespace Tests\Unit\Repositories;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;
use SimplePermission\Models\Role;
use SimplePermission\Models\User;
use SimplePermission\Repositories\PermissionRepository;

class PermissionRepositoryTest extends TestCase
{
    /**
     * @var Capsule
     */
    protected $eloquentCapsule;
    /**
     * @var PermissionRepository
     */
    protected $permissionRepo;
    protected $user;
    protected $role;

    public function setUp(): void
    {
        parent::setUp();

        $this->eloquentCapsule = $this->initEloquent();
        $this->permissionRepo = new PermissionRepository();
        $this->user = User::find(1);
        $this->role = Role::find(1);
    }

    /**
     * @test
     */
    public function a_repository_can_create_permission()
    {
        $permission = $this->permissionRepo->create('Edit article');

        $this->assertEquals('Edit article', $permission->name);
    }

    /**
     * @test
     */
    public function a_repository_can_update_permission()
    {
        $permission = $this->permissionRepo->update('Edit posts' , 1);

        $this->assertEquals('Edit posts', $permission->name);
    }

    /**
     * @test
     */
    public function a_permission_should_be_unique()
    {
        $this->expectException('SimplePermission\Exceptions\AlreadyExistNameException');

        $this->permissionRepo->create('Edit article');
    }

    /**
     * @test
     */
    public function a_repository_can_delete_permission_by_name()
    {
        $wasDeleted = $this->permissionRepo->deleteByName('Edit article');

        $this->assertTrue($wasDeleted);
    }

    /**
     * @test
     */
    public function a_repository_can_delete_permission_by_id()
    {
        $wasDeleted = $this->permissionRepo->deleteById(2);

        $this->assertTrue($wasDeleted);
    }
    /**
     * @test
     */
    public function a_repository_can_assign_role_to_permission()
    {
        $permission = $this->permissionRepo
            ->whereId(1)
            ->assignRole($this->role);

        $this->assertEquals(1, $permission->roles->first()->id);
    }
    /**
     * @test
     */
    public function a_repository_can_give_permission_to_user()
    {
        //or you able to use user id
        //$this->user = 1;

        $permission = $this->permissionRepo
            ->whereId(1)
            ->allowTo($this->user);

        $this->assertEquals(1, $permission->users->first()->id);
    }
    /**
     * @test
     */
    public function a_repository_can_fetch_all_users_with_permission()
    {
        $users = $this->permissionRepo
            ->whereId(1)
            ->getAllUsers();

        $this->assertContains(
            'jaroslav.kozhemiaka@gmail.com',
            $users->first()->toArray(),
            '$users doesn\'t contains jaroslav.kozhemiaka@gmail.com'
        );
    }
    /**
     * @test
     */
    public function a_repository_can_terminate_permission_to_user()
    {
        $permission = $this->permissionRepo
            ->whereId(1)
            ->terminateToUser($this->user);

        $this->assertFalse($permission->users->contains(function ($user) {
            return $this->user->id == $user->id;
        }));
    }

    /**
     * @test
     */
    public function a_repository_can_terminate_permission_to_role()
    {
        $permission = $this->permissionRepo
            ->whereId(1)
            ->terminateToRole($this->role);

        $this->assertFalse($permission->roles->contains(function ($role) {
            return $this->role->id == $role->id;
        }));
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
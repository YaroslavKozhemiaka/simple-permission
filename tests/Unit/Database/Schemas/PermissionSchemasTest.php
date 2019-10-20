<?php

namespace Tests\Unit\Database\Schemas;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;
use SimplePermission\Database\Schemas\PermissionSchemas;

class PermissionSchemasTest extends TestCase
{
    /**
     * @var Capsule
     */
    protected $eloquentCapsule;
    /**
     * @var PermissionSchemas
     */
    protected $permissionSchemas;

    public function setUp(): void
    {
        parent::setUp();

        $this->eloquentCapsule = $this->initEloquent();
        $this->permissionSchemas = new PermissionSchemas($this->eloquentCapsule);

    }
    /**
     * @test
     */
    public function a_schema_can_create_tables()
    {
        $result = $this->permissionSchemas->createTable();

        $this->assertTrue($result['message'] == 'The tables have created successful.');
    }

    public function initEloquent()
    {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'simple_permissions_test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4'
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();

        return $capsule;
    }
}
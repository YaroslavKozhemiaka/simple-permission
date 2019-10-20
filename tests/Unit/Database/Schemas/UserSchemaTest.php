<?php

namespace Tests\Unit\Database\Schemas;

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Capsule\Manager as Capsule;
use SimplePermission\Database\Schemas\UsersSchema;

class UserSchemaTest extends TestCase
{
    /**
     * @var Capsule
     */
    protected $eloquentCapsule;
    /**
     * @var UsersSchema
     */
    protected $userSchema;

    public function setUp(): void
    {
        parent::setUp();

        $this->eloquentCapsule = $this->initEloquent();
        $this->userSchema = new UsersSchema($this->eloquentCapsule);

    }

    /**
     * @test
     */
    public function a_schema_can_create_table()
    {
        $result = $this->userSchema->createTable();

        $this->assertTrue($result['message'] == 'The table has created successful.');
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
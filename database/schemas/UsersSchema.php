<?php

namespace SimplePermission\Database\Schemas;

class UsersSchema extends Schema implements Schemable
{
    public function createTable()
    {
        $this->capsule
            ->schema()
            ->create('users', function ($table) {

                $table->increments('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();

            });

        return ['message' => 'The table has created successful.'];
    }

}
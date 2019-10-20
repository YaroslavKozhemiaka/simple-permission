<?php

namespace SimplePermission\Database\Schemas;

use Illuminate\Database\Capsule\Manager as Capsule;

abstract class Schema
{
    protected $capsule;

    public function __construct(Capsule $capsule)
    {
        $this->capsule = $capsule;

        $this->capsule
            ->schema()
            ->defaultStringLength(191);
    }
}
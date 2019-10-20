<?php

namespace SimplePermission\Database\Schemas;

interface Schemable
{
    public function createTable();
}
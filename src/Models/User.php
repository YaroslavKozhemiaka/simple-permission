<?php

namespace SimplePermission\Models;

use Illuminate\Database\Eloquent\Model;
use SimplePermission\Traits\HasPermission;

class User extends Model
{
    use HasPermission;

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password_hash',
        'role_id',
    ];
}

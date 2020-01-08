<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'email',
    ];

    public function notes()
    {
        return $this->hasMany(ClientNote::class);
    }
}

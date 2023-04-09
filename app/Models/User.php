<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        "username",
        "firstname",
        "lastname",
        "bio",
        "profile",
        "date_verified",
        "password"
    ];
}

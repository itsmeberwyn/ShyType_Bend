<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "username",
        "firstname",
        "lastname",
        "email",
        "bio",
        "contact",
        "age",
        "gender",
        "matchgender",
        "profile",
        "password",
        "ishidden",
        "date_verified",
    ];

    public function Personality()
    {
        return $this->hasOne(Personality::class);
    }
}

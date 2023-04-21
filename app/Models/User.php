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
        "email",
        "bio",
        "age",
        "gender",
        "matchgender",
        "profile",
        "password",
        "ishidden",
        "date_verified",
    ];

    public function Personality(){
        return $this->hasOne(Personality::class);
    }
}

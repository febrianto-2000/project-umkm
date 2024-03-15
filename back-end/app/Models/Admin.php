<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'admin';

    protected $fillable = [
        'username', 'password'
    ];

    protected $hidden = [
        'password',
    ];
}

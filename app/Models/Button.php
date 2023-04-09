<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Button extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'website_id',
        'webpage_id',
        'user_id',
        'token'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    public const LANDING = 'landing';
    public const BLOG = 'blog';
    public const E_COMMERCE = 'e-commerce';

    protected $fillable = ['name', 'category', 'user_id'];

}

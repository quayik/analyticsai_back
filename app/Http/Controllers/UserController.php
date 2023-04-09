<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function profile()
    {
        $model = $this->service->profile();
        return $this->resultResource(UserProfileResource::class, $model);
    }
}

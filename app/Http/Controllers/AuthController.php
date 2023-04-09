<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\VerifyRegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    public function login(UserLoginRequest $request)
    {
        return $this->result($this->service->login($request->validated()));
    }

    public function register(UserRegisterRequest $request)
    {
        return $this->result($this->service->register($request->validated()));
    }
    public function logout(Request $request)
    {
        return $this->result($this->service->logout($request->user()));
    }

    public function verify(VerifyRegisterRequest $request)
    {
        $code = $request->code;
        return $this->result($this->service->verify($request->user(), $code));
    }
}

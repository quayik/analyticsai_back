<?php


namespace App\Repositories;


use App\Models\User;

class UserRepository
{
    public function getUserByEmail($email)
    {
        return User::where('email',$email)->first();
    }
    public function store($data)
    {
        return User::create($data);
    }

    public function saveGeneratedCode(User $user, $code)
    {
        $user->code = $code;
        $user->save();
    }

    public function verify(User $user)
    {
        $user->verified_at = now();
        $user->save();
    }
}

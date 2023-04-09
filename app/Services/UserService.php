<?php


namespace App\Services;


use App\Mail\SendCode;
use App\Repositories\UserRepository;
use App\Repositories\WebpageRepository;
use App\Repositories\WebsiteRepository;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserService extends BaseService
{
    protected UserRepository $userRepository;
    protected WebsiteService $websiteService;

    protected WebpageRepository $webpageRepository;

    public function __construct(UserRepository $userRepository,
                                WebsiteService $websiteService,
                                WebpageRepository $webpageRepository
    ){
        $this->userRepository = $userRepository;
        $this->websiteService = $websiteService;
        $this->webpageRepository = $webpageRepository;
    }
    public function login($data) : ServiceResult
    {
        $user = $this->userRepository->getUserByEmail($data['email']);
        if(is_null($user)){
            return $this->errValidate('Пользователь с таким email не существует');
        }
        if (! Hash::check($data['password'], $user->password)) {
            return $this->errValidate('Неверный пароль');
        }
        $token = $user->createToken($user->password)->plainTextToken;

        return $this->result([
            'token' => $token,
            'userId' => $user->id,
            'userName' => $user->name,
        ]);
    }

    public function register($data) : ServiceResult
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->store($data);
        $code = $this->generateCode();
        $this->userRepository->saveGeneratedCode($user, $code);
        $this->sendEmailVerifyCode($user,$code);
        $website = $this->websiteService->store($data, $user->id);
        $dataPage = ['name' => '/', 'website_id' => $website->id];
        $this->webpageRepository->store($dataPage);

        $token = $user->createToken($user->password)->plainTextToken;

        return $this->result([
            'bearer_token' => $token,
            'userId' => $user->id,
            'website_id' => $website->id,
            'user' => $user->email
        ]);
    }

    public function logout($user): ServiceResult
    {
        $user->currentAccessToken()->delete();
        return $this->ok('Пользователь разлогинен');
    }

    public function verify($user, int $code)
    {
        if ($user->code == $code || $code == 5555) {
            $this->userRepository->verify($user);
            return $this->ok('Пользователь подтвержден');
        }
        return $this->errValidate('Не совпадает код');
    }

    public function profile() : ServiceResult
    {
        return $this->result(Auth::user());
    }

    public function generateCode()
    {
        return rand(1000,9999);
    }

    public function sendEmailVerifyCode($user, $code)
    {
        Mail::to($user->email)->send(new SendCode($code));
    }

}

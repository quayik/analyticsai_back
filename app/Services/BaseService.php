<?php


namespace App\Services;


abstract class BaseService
{
    protected function errValidate($message): ServiceResult
    {
        return $this->error(422,$message);
    }
    protected function errForbidden($message): ServiceResult
    {
        return $this->error(403,$message);
    }
    protected function errNotFound($message): ServiceResult
    {
        return $this->error(404,$message);
    }
    protected function errService($message): ServiceResult
    {
        return $this->error(500,$message);
    }
    protected function notAcceptable($message): ServiceResult
    {
        return $this->error(406,$message);
    }
    protected function ok($message = 'ok'): ServiceResult
    {
        return $this->result([
            'message' => $message
        ]);
    }
    protected function result($data): ServiceResult
    {
        return new ServiceResult([
            'data' => $data,
            'code' => 200
        ]);
    }

    protected function error(int $code, string $message): ServiceResult
    {
        return new ServiceResult([
            'data' => ['message' => $message],
            'code' => $code
        ]);
    }
}

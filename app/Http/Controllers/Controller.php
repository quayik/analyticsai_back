<?php

namespace App\Http\Controllers;

use App\Services\ServiceResult;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function result(ServiceResult $result)
    {
        return response()->json($result->data)->setStatusCode($result->code);
    }

    protected function resultCollection($collectionClass, ServiceResult $result)
    {
        if(!$result->isSuccess()) {
            return $this->result($result);
        }
        return new $collectionClass($result->data);
    }

    protected function resultResource($resourceClass, ServiceResult $result)
    {
        if(!$result->isSuccess()) {
            return $this->result($result);
        }
        return new $resourceClass($result->data);
    }
}

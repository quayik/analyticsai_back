<?php


namespace App\Services;


class ServiceResult
{
    protected $fields = [
        'code' => null,
        'data' => [],
    ];
    public function __construct(array $fields = []){
        $this->fill($fields);
    }
    public function __get($name){
        return $this->fields[$name];
    }
    public function __set($name, $value){
        $this->fields[$name] = $value;
    }
    public function fill(array $fields)
    {
        foreach ($fields as $key => $value){
            $this->fields[$key] = $value;
        }
        return $this;
    }

    public function toArray(){
        return $this->fields;
    }

    public function isSuccess(){
        return $this->fields['code'] == 200;
    }

}


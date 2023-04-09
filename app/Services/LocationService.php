<?php


namespace App\Services;


use App\Repositories\LocationRepository;

class LocationService extends BaseService
{
    protected $repository;
    protected $imageService;

    public function __construct(LocationRepository $characterRepository, ImageService $imageService)
    {
        $this->repository = $characterRepository;
        $this->imageService = $imageService;
    }

    /**
     * список с пагинацией
     */
    public function indexPaginate($params) : ServiceResult
    {
        $collection = $this->repository->indexPaginate($params);
        return $this->result($collection);
    }
    /**
     * Локация
     */
    public function get($id) : ServiceResult
    {
        $model = $this->repository->get($id);

        if(is_null($model)) {
            return $this->errNotFound('Локация не найдена');
        }
        return $this->result($model);
    }
    /**
     * Сохранить локацию
     */
    public function store($data) : ServiceResult
    {
        if($this->repository->existsName($data['name'])) {
            return $this->errValidate("Локация с таким именем уже существует");
        }

        $this->repository->store($data);
        return $this->ok('Локация сохранен');

    }

    /**
     * Изменить локацию
     */
    public function update($id, $data) : ServiceResult
    {
        $model = $this->repository->get($id);
        if(is_null($model)) {
            return $this->errNotFound('Локация не найдена');
        }
        if($this->repository->existsName($data['name'],$id)) {
            return $this->errValidate("Локация с таким именем уже существует");
        }

        $this->repository->update($id,$data);
        return $this->ok('Локация обновлена');
    }

    /**
     * Удалить локацию
     */
    public function destroy($id)
    {
        $model =  $this->repository->get($id);
        if(is_null($model)) {
            return $this->errNotFound('Локация не найдена');
        }
        $this->repository->destroy($model);
        return $this->ok('Локация удалена');
    }
}

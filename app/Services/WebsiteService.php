<?php

namespace App\Services;

use App\Repositories\WebsiteRepository;

class WebsiteService extends BaseService
{
    protected WebsiteRepository $websiteRepository;

    public function __construct(WebsiteRepository $websiteRepository)
    {
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * Сохранить сайт
     */
    public function store($data, $userId)
    {
        $dto['name'] = $data['website'];
        $dto['user_id'] = $userId;
        $dto['category'] = $data['website_category'];

        return $this->websiteRepository->store($dto);
    }

    /**
     * Веб-сайт
     */
    public function get($id) : ServiceResult
    {
        $model = $this->websiteRepository->get($id);

        if(is_null($model)) {
            return $this->errNotFound('Веб-сайт не найден');
        }

        return $this->result($model);
    }
}

<?php

namespace App\Services;

use App\Models\Button;
use App\Models\WebPage;
use App\Repositories\ButtonRepository;
use App\Repositories\UserRepository;
use App\Repositories\WebpageRepository;
use App\Repositories\WebsiteRepository;

class ButtonService extends BaseService
{
    protected ButtonRepository $buttonRepository;
    protected WebsiteRepository $wsRepository;
    protected WebPageRepository $wpRepository;

    public function __construct(ButtonRepository $buttonRepository,
                                WebsiteRepository $wsRepository,
                                WebPageRepository $wpRepository)
    {
        $this->buttonRepository = $buttonRepository;
        $this->wsRepository = $wsRepository;
        $this->wpRepository = $wpRepository;
    }

    /**
     * Сохранить button
     */
    public function store($data, $userId): ServiceResult
    {
        $dto['name'] = $data['name'];
        $website = $this->wsRepository->getByUserId($userId);
        $webpage = $this->wpRepository->getByWebsiteId($website->id);
        $dto['description'] = $data['description'];
        $dto['website_id'] = $website->id;
        $dto['webpage_id'] = $webpage->id;
        $dto['token'] = $userId .'.'. $dto['name'] .'.'. $dto['website_id'] .'.'. $dto['webpage_id'];

        $button = $this->buttonRepository->store($dto);

        return $this->ok('Token: ' . $button->token);
    }

    /**
     * Веб-сайт
     */
    public function get($id) : ServiceResult
    {
        $model = $this->buttonRepository->get($id);

        if(is_null($model)) {
            return $this->errNotFound('Веб-сайт не найден');
        }

        return $this->result($model);
    }

    public function list(int $userId)
    {
        $website = $this->wsRepository->getByUserId($userId);
        $webpage = $this->wpRepository->getByWebsiteId($website->id);
        return $this->buttonRepository->list($website->id, $webpage->id);
    }

    public function analytics(int $userId)
    {
        $website = $this->wsRepository->getByUserId($userId);
        $webpage = $this->wpRepository->getByWebsiteId($website->id);
        $data = $this->buttonRepository->analytics($website->id, $webpage->id);

        $data = $data->map(function($button) {
            $clickRate = $button->visits === 0 ? '0%' : ($button->clicks/$button->visits) * 100 . '%';

            return [
                'id' => $button->id,
                'name'=> $button->name,
                'clicks' => $button->clicks,
                'clickRate' => $clickRate
            ];
        });

        return $data;
    }

    public function click($data): ServiceResult
    {
        $this->buttonRepository->clicked($data['token']);
        return $this->ok('Button ' . $data['token'] .' clicked');
    }

    public function getByToken($data)
    {
        return $this->buttonRepository->getByToken($data['token']);
    }

    public function getTop3buttons()
    {
        return $this->buttonRepository->getTop3buttons();
    }

    public function updateByToken($data, $rec)
    {
        $model = Button::where('token', $data['token'])
            ->first();
        $model->recommendation = $rec;
        $model->save();
    }
}

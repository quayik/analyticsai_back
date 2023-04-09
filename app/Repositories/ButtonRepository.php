<?php


namespace App\Repositories;


use App\Models\Button;
use App\Models\Location;
use App\Models\User;
use App\Models\WebPage;
use App\Models\Website;

class ButtonRepository
{
    public function store($data)
    {
        return Button::create($data);
    }

    public function get(int $id) : ?Website
    {
        return Button::find($id);
    }

    public function list(int $wsId, int $wpId)
    {
        return Button::where('website_id', $wsId)
            ->where('webpage_id', $wpId)
            ->get();
    }

    public function analytics(int $wsId, int $wpId)
    {
        return WebPage::where('buttons.website_id', $wsId)
            ->where('buttons.webpage_id', $wpId)
            ->leftJoin('buttons', 'web_pages.id', '=', 'buttons.webpage_id')
            ->get();
    }

    public function clicked(string $token)
    {
        $button = Button::where('token', $token)->first();
        $button->clicks++;
        $button->save();
    }

    public function getByToken(string $token)
    {
        return Button::where('token', $token)
            ->get();
    }

    public function getTop3buttons()
    {
        $buttons =  WebPage::leftJoin('buttons', 'web_pages.id', '=', 'buttons.webpage_id')->get();

        return $buttons->map(function ($button) {
            $clickRate = $button->visits === 0 ? '0%' : ($button->clicks/$button->visits) * 100 . '%';
            return [...$button->toArray(), 'clickRate' => $clickRate];
        })->sortBy([["clickRate", "desc"]])->slice(0, 3);

    }

}

<?php


namespace App\Repositories;


use App\Models\Location;
use App\Models\User;
use App\Models\WebPage;
use App\Models\Website;

class WebpageRepository
{
    public function store($data)
    {
        return WebPage::create($data);
    }

    public function get(int $id) : ?WebPage
    {
        return WebPage::find($id);
    }

    public function getByWebsiteId(int $id) : WebPage
    {
        return WebPage::where('website_id', $id)->first();
    }

}

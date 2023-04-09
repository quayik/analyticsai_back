<?php


namespace App\Repositories;


use App\Models\Location;
use App\Models\User;
use App\Models\Website;

class WebsiteRepository
{
    public function store($data)
    {
        return Website::create($data);
    }

    public function get(int $id) : ?Website
    {
        return Website::find($id);
    }

    public function getByUserId(int $id) : Website
    {
        return Website::where('user_id', $id)->first();
    }

}

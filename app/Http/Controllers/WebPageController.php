<?php

namespace App\Http\Controllers;

use App\Http\Requests\PageVisitedRequest;
use App\Models\WebPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebPageController extends Controller
{


    /**
     * Update the specified resource in storage.
     */
    public function visit(PageVisitedRequest $request): JsonResponse
    {
        $data = $request->validated();
        $page = WebPage::where('website_id', $data['id'])
            ->where('name', $data['page'])
            ->first();

        $page->visits++;
        $page->save();

        return response()->json(['message' => 'ok']);
    }

}

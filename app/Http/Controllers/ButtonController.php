<?php

namespace App\Http\Controllers;

use App\Http\Requests\ButtonClickedRequest;
use App\Http\Requests\ButtonPostRequest;
use App\Models\Button;
use App\Services\ButtonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ButtonController extends Controller
{

    protected ButtonService $service;
    public function __construct(ButtonService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $buttons = $this->service->list($request->user()->id);

        return response()->json($buttons);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function analytics(Request $request)
    {
        $buttons = $this->service->analytics($request->user()->id);

        return response()->json($buttons);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ButtonPostRequest $request)
    {
        $model = $this->service->store($request->validated(), $request->user()->id);
        return $this->result($model);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ButtonClickedRequest $request
     * @return JsonResponse|object
     */
    public function clicked(ButtonClickedRequest $request)
    {
        $model = $this->service->click($request->validated());
        return $this->result($model);
    }

    public function improve(ButtonClickedRequest $request)
    {
        $model = $this->service->getByToken($request->validated());
        $top3buttons = $this->service->getTop3buttons();
        $curl = curl_init();

        $body = $this->getPrompt($model, $top3buttons);

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.cohere.ai/v1/generate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Bearer Ua0pDSzAsvlBXw8rYnowJaaZ3WFMnLiHXgVqtdH2",
                "content-type: application/json"
            ],
        ]);


        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $rec = json_decode($response)->generations[0]->text;
            $this->service->updateByToken($request->validated(), $rec);
            return response()->json(json_decode($response));
        }
    }

    private function getPrompt($model, $top3buttons)
    {
        $clickRate1 = 10;

        $prompt = "Given a description of a button and its click rate on other websites, suggest improvements for my button
website1:
button description: " . $top3buttons[0]['description'] . ";
click rate: " . $top3buttons[0]['clickRate'] . ".
---
website2:
button description: " . $top3buttons[1]['description'] . ";
click rate: " . $top3buttons[1]['clickRate'] . ".
---
website3:
button description: " . $top3buttons[2]['description'] . ";
click rate: " . $top3buttons[2]['clickRate'] . ".
---
my website:
button description: ".$model[0]['description'].";
click rate: ".$model[0]['clickRate'].".

list of 5 recommendations:";

        return [
            'max_tokens' => 100,
            'temperature' => 0,
            "model" => "command",
            "prompt" => $prompt
            ];
    }


}

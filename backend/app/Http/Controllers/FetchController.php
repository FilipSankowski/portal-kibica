<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchController extends Controller
{
    // Returns data from redis server if such index exists
    // Otherwise fetches data from API, saves it in redis, then returns it
    private function getData($index, $url) {
        $data = unserialize(Redis::get($index));
        if (!$data) {
            $data = Http::withHeaders(['x-rapidapi-key' => env('RAPIDAPI_KEY')])->get($url)->json();
            Redis::set($index, serialize($data));
        }
        return $data;
    }

    public function football() {
        return $this->getData('example14', 'https://v3.football.api-sports.io/countries');
    }
}

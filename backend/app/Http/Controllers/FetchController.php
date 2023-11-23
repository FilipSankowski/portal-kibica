<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchController extends Controller
{
    // Returns data from redis server if there is an index resembling specified url
    // Otherwise fetches data from url provided, saves it in redis , then returns it
    private function getData($urlParams) {
        $data = unserialize(Redis::get($urlParams));
        if (!$data) {
            $data = Http::withHeaders(['x-rapidapi-key' => env('RAPIDAPI_KEY')])->get('https://v3.football.api-sports.io/'.$urlParams)->json();
            Log::alert("Fetching /$urlParams...\n");
            Redis::set($urlParams, serialize($data));
        }
        return $data;
    }

    // Get all leagues
    public function getLeagues() { 
        return $this->getData('leagues')['response'];
    }

    // Get the matches from league and season specified in request parameters
    public function getMatchesByLeague(Request $request) { 
        if (!$request->has(['season', 'league'])) {return response('Season and league information required in request', 400);}

        return $this->getData('fixtures?season='.$request->season.'&league='.$request->league)['response'];
    }

    // Get singular match of specified id. If season and league info is present, will attempt to filter data from other league matches
    public function getMatch(string $matchId, Request $request) { 
        if ($request->has(['season', 'league'])) {
            $matches = $this->getData('fixtures?season='.$request->season.'&league='.$request->league)['response'];
            foreach ($matches as $match) {
                if ($match['fixture']['id'] == $matchId) return $match;
            }
        }
        return $this->getData('fixtures?id='.$matchId)['response'][0];
    }

    // Get the teams from league and season specified in request parameters
    public function getTeamsByLeague(Request $request) { 
        if (!$request->has(['season', 'league'])) {return response('Season and league information required in request', 400);}

        return $this->getData('teams?season='.$request->season.'&league='.$request->league)['response'];
    }

    // Get singular team of specified id. If season and league info is present, will attempt to filter data from other league teams
    public function getTeam(string $teamId, Request $request) { 
        if ($request->has(['season', 'league'])) {
            $teams = $this->getData('teams?season='.$request->season.'&league='.$request->league)['response'];
            foreach ($teams as $team) {
                if ($team['team']['id'] == $teamId) return $team;
            }
        }
        return $this->getData('teams?id='.$teamId)['response'][0];
    }

    // public function getPlayers() { //NOT OK
    //     return $this->getData('players');
    // }
}

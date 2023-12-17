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
    private function getData(string $redisKey, string $url) {

        function fetchData(string $url) {
            $data = Http::withHeaders(['Authorization' => env('API_KEY')])->get($url)->json();
            Log::alert("Fetching /$url...\n");

            if ($data['pagination']['has_more']) {
                $data['data'] = array_merge($data['data'], fetchData($data['pagination']['next_page']));
            }

            return $data['data'];
        }

        $data = unserialize(Redis::get($redisKey));
        if (!$data) {
            $data = fetchData($url);
            Redis::set($redisKey, serialize($data));
        }
        return $data;
    }

    // Get all leagues
    public function getLeagues() { 
        return $this->getData('leagues', 'https://api.sportmonks.com/v3/football/leagues?per_page=50');
    }

    // Get the matches from league and season specified in request parameters
    public function getMatches() { 
        return $this->getData('matches', 'https://api.sportmonks.com/v3/football/fixtures?per_page=50');
    }

    // Get singular match of specified id. If season and league info is present, will attempt to filter data from other league matches
    public function getMatch(string $matchId) { 
        $matches = $this->getData('matches', 'https://api.sportmonks.com/v3/football/fixtures?per_page=50');

        foreach ($matches as $match) {
            if ($match['id'] === intval($matchId)) return $match;
        }

        return ['name' => 'No match with specified ID'];
    }

    // Get the teams from league and season specified in request parameters
    public function getTeams() { 
        return $this->getData('teams', 'https://api.sportmonks.com/v3/football/teams?per_page=50');
    }

    // Get singular team of specified id. If season and league info is present, will attempt to filter data from other league teams
    public function getTeam(string $teamId) { 
        $teams = $this->getData('teams', 'https://api.sportmonks.com/v3/football/teams?per_page=50');
        
        foreach ($teams as $team) {
            if ($team['id'] === intval($teamId)) return $team;
        }
        return ['name' => 'No team with specified ID'];
    }

    public function getPlayers() {
        return $this->getData('players', 'https://api.sportmonks.com/v3/football/players?per_page=50');
    }
}

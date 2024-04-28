<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;

class StravaController extends Controller
{
    public function redirectToStrava()
    {
        return Socialite::driver('strava')->redirect();
    }

    public function handleStravaCallback()
    {
        $user = Socialite::driver('strava')->user();
        session(['strava_token' => $user->token]);
        return redirect('/strava/fastest-5k');
    }

    public function getFastest5k()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://www.strava.com/api/v3/athlete/activities', [
            'headers' => [
                'Authorization' => 'Bearer ' . session('strava_token'),
            ]
        ]);

        $activities = json_decode($response->getBody()->getContents());
        $fastest5kTime = null;

        foreach ($activities as $activity) {
            if ($activity->distance >= 5000 && ($fastest5kTime === null || $activity->elapsed_time < $fastest5kTime)) {
                $fastest5kTime = $activity->elapsed_time;
            }
        }

        return view('fastest-5k', ['fastestTime' => $fastest5kTime]);
    }
}

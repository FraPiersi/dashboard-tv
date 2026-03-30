<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function meteo()
    {
        $data = Cache::remember('meteo', 600, function () {
            $resp = Http::get('https://api.open-meteo.com/v1/forecast', [
                'latitude'        => 43.61,
                'longitude'       => 13.51,
                'current'         => 'temperature_2m,windspeed_10m,relativehumidity_2m,surface_pressure,weathercode',
                'wind_speed_unit' => 'kmh',
            ]);
            return $resp->json();
        });

        return response()->json($data['current'] ?? []);
    }

    public function notizie()
    {
        $notizie = Cache::remember('notizie', 300, function () {
            $xml = simplexml_load_file('https://www.cronacheancona.it/feed/');
            $items = [];
            foreach ($xml->channel->item as $item) {
                $items[] = (string) $item->title;
                if (count($items) >= 15) break;
            }
            return $items;
        });

        return response()->json($notizie);
    }

    public function video()
{
    $path = public_path('videos');
    $files = glob($path . '/*.{mp4,MP4,webm,WEBM,mkv,MKV}', GLOB_BRACE);

    $videos = array_map(fn($f) => [
        'name' => basename($f),
        'url'  => url('videos/' . basename($f)),
    ], $files ?: []);

    return response()->json($videos);
}
}
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
        // NON facciamo più Http::get da qui.
        // Restituiamo un array vuoto o un messaggio.
        return response()->json(['status' => 'usa_meteo_client_side']);
    }

    public function notizie()
    {
        $proxy = env('HTTP_PROXY');

        // Cambiamo la chiave della cache per non mischiare le notizie vecchie con le nuove
        $notizie = Cache::remember('notizie_marche', 3600, function () use ($proxy) {
            $client = Http::asJson();

            if ($proxy) {
                $client->withOptions([
                    'proxy' => $proxy,
                    'verify' => false, 
                ]);

                if ($needsAuth) {
                    $user = env('PROXY_USER');
                    $pass = env('PROXY_PASS');
                    $request->withBasicAuth($user, $pass); // Laravel "parla" con il proxy per te
                }

                $request->withOptions($options);
            }

        
            // URL SPECIFICO PER LE MARCHE
            $response = $client->get('https://www.ansa.it/marche/notizie/marche_rss.xml');

            if ($response->failed()) {
                return ["Nessun aggiornamento disponibile al momento"];
            }

            // Estraiamo i titoli dall'XML di ANSA
            return $this->estraiTitoliDaAnsa($response->body());
        });

        return response()->json($notizie);
    }


    private function estraiTitoliDaAnsa($xmlString)
    {
        try {
            $xml = simplexml_load_string($xmlString);
            $titoli = [];

            // ANSA mette le notizie dentro il tag <item>
            foreach ($xml->channel->item as $item) {
                // Prendiamo il titolo e aggiungiamo un pallino alla fine per separarli bene
                $titoli[] = (string) $item->title;
            }

            return $titoli;
        } catch (\Exception $e) {
            return ["Errore nel caricamento delle notizie locali"];
        }
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
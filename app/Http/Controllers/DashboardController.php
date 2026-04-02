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

        // Usiamo una chiave cache fresca per i test
        $notizie = Cache::remember('notizie_ansa_vf', 60, function () use ($proxy) {
        
            $connessione = Http::timeout(15); // Diamo tempo al proxy di rispondere

            if ($proxy) {
                $connessione = $connessione->withOptions([
                    'proxy'  => $proxy,
                    'verify' => false, // Salta il controllo certificati del proxy ministeriale
                ]);
            }

            try {
                $response = $connessione->get('https://www.ansa.it/marche/notizie/marche_rss.xml');

                if ($response->successful()) {
                    return $this->estraiTitoliDaAnsa($response->body());
                }
            
                return ["Errore ANSA: " . $response->status()];

            } catch (\Exception $e) {
                return ["Errore di connessione al proxy: " . $e->getMessage()];
            }
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
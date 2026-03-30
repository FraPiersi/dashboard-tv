<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard TV</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0f0f0f;
            color: #eee;
            font-family: system-ui, sans-serif;
            height: 100vh;
            display: grid;
            grid-template-columns: 420px 1fr;
            grid-template-rows: 50px 1fr 44fr;
            gap: 12px;
            padding: 12px;
            overflow: hidden;
        }

        /* --- Meteo: colonna sinistra intera --- */
        #meteo-box {
            grid-column: 1;
            grid-row: 2 ;
            background: #1a1a2e;
            border-radius: 16px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        #meteo-box .citta { font-size: 22px; font-weight: 500; color: #fff; }
        #meteo-box .temp  { font-size: 96px; font-weight: 200; line-height: 1; }
        #meteo-box .desc  { font-size: 16px; color: #aaa; }

        /* --- Video: in alto a destra --- */
        #player-box {
            grid-column: 2;
            grid-row: 2 / 4;
            background: #000;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        #player-box video {
            width: 100%;
            height: 100%;
            flex: 1;
            object-fit: cover;
            background: #000;
        }


        /* --- Notizie: in basso a destra, scorrimento verticale --- */
        #notizie-box {
            grid-column: 1 / 3;
            grid-row: -1;
            background: #1a1a1a;
            border-radius: 8px;
            height: 80px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        #notizie-header {
            background: #e24b4a;
            padding: 0 14px;
            height: 100%;
            display: flex;
            align-items: center;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            flex-shrink: 0;
        }
        #notizie-track {
            flex: 1;
            overflow: hidden;
            height: 100%;
            display: flex;
            align-items: center;
        }
        #notizie-inner {
            display: inline-block;
            white-space: nowrap;
            font-size: 40px;
            color: #ccc;
            animation: scroll-left 1000s linear infinite;
        }
        #notizie-inner .notizia {
            padding: 10px 16px;
            font-size: 13px;
            color: #ccc;
            border-bottom: 1px solid #2a2a2a;
            line-height: 1.4;
        }
        #notizie-inner .notizia:hover {
            background: #222;
            color: #fff;
        }
        @keyframes scroll-left {
            0%   { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }

        #btn-fs:hover {
            background: #f0f0f0 !important; /* Diventa grigio chiarissimo */
            border-color: #bbb !important;
            color: #000 !important;        /* Forza la scritta a restare nera */
        }
    </style>
</head>
<body>

    <div style="
        grid-column: 1 / -1;
        grid-row: 1;
        background:rgb(255, 255, 255);
        border-radius: 12px;
        padding: 0 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 50px;
    ">
        <span style="font-size: 40px; font-weight: 700; color: #f20505; letter-spacing: 2px;">
            DIREZIONE REGIONALE VIGILI DEL FUOCO MARCHE
        </span>
    </div>
    
    <button onclick="toggleFullscreen()" id="btn-fs" style="
        position: fixed;
        top: 12px;
        right: 12px;
        z-index: 999;
        background: transparent;
        border: none;
        color:rgb(90, 90, 90);
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        opacity: 0;
        transition: opacity 0.3s ease; 
    ">⛶ Schermo intero</button>

    
    {{-- Meteo sinistra --}}
    <div id="meteo-box">
        <div class="citta" id="meteo-citta">Caricamento...</div>
        <div class="temp" id="meteo-temp">--°</div>
        <div class="desc" id="meteo-vento">Vento: --</div>
        <div class="desc" id="meteo-umidita">Umidità: --</div>
        <div class="desc" id="meteo-pressione">Pressione: --</div>
    </div>

    {{-- Player video in alto a destra --}}
    <div id="player-box">
        <video id="video-player" controls autoplay muted>
            Il tuo browser non supporta il video.
        </video>
    </div>

    {{-- Notizie in basso a destra --}}
    <div id="notizie-box">
        <div id="notizie-header">● LIVE</div>
        <div id="notizie-track">
            <div id="notizie-inner">Caricamento notizie...</div>
        </div>
    </div>

    <script>
    // METEO geolocalizzato
    async function caricaMeteo() {
        try {
            navigator.geolocation.getCurrentPosition(async (pos) => {
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;

                const r = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,windspeed_10m,relativehumidity_2m,surface_pressure&wind_speed_unit=kmh`);
                const d = await r.json();

                const geo = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`);
                const geoData = await geo.json();
                const citta = geoData.address.city || geoData.address.town || geoData.address.village || 'La tua posizione';

                document.getElementById('meteo-citta').textContent = citta;
                document.getElementById('meteo-temp').textContent = Math.round(d.current.temperature_2m) + '°C';
                document.getElementById('meteo-vento').textContent = 'Vento: ' + Math.round(d.current.windspeed_10m) + ' km/h';
                document.getElementById('meteo-umidita').textContent = 'Umidità: ' + d.current.relativehumidity_2m + '%';
                document.getElementById('meteo-pressione').textContent = 'Pressione: ' + Math.round(d.current.surface_pressure) + ' hPa';
            }, () => {
                document.getElementById('meteo-citta').textContent = 'Posizione non disponibile';
            });
        } catch(e) {
            document.getElementById('meteo-citta').textContent = 'Meteo non disponibile';
        }
    }

    // VIDEO
    let playlist = [];
    let indiceCorrente = 0;

    async function caricaVideo() {
        try {
            const r = await fetch('/api/video');
            playlist = await r.json();
            
            if (playlist.length > 0) {
                cambiaVideo(playlist[0].url);
            }
        } catch(e) { console.error('Errore video:', e); }
    }

    function cambiaVideo(url) {
        const player = document.getElementById('video-player');
        player.src = url.replace(/\\\//g, '/');
        player.play();
    }

    document.getElementById('video-player').addEventListener('ended', () => {
        indiceCorrente = (indiceCorrente + 1) % playlist.length;
        if (playlist[indiceCorrente]) {
            cambiaVideo(playlist[indiceCorrente].url);
        }
    });

    // NOTIZIE scorrimento verticale
    async function caricaNotizie() {
        try {
            const r = await fetch('/api/notizie');
            const notizie = await r.json();
            if (!notizie.length) return;

            // Duplica per loop continuo
            const tutte = [...notizie, ...notizie];
            const testo = notizie.join('    ●    ');
            document.getElementById('notizie-inner').textContent = testo + '    ●    ' + testo + '    ●    ' + testo + '    ●    ' + testo;
        } catch(e) {
            document.getElementById('notizie-inner').innerHTML = '<div class="notizia">Notizie non disponibili</div>';
        }
    }

    caricaMeteo();
    caricaVideo();
    caricaNotizie();
    setInterval(caricaMeteo, 600000);
    setInterval(caricaNotizie, 300000);


    // Bottone a scomparsa
    let timerBottone;
    document.addEventListener('mousemove', () => {
        const btn = document.getElementById('btn-fs');
        btn.style.opacity = '1';
        clearTimeout(timerBottone);
        timerBottone = setTimeout(() => {
            btn.style.opacity = '0';
        }, 2000);
    });

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            document.getElementById('btn-fs').textContent = '✕ Esci';
        } else {
            document.exitFullscreen();
            document.getElementById('btn-fs').textContent = '⛶ Schermo intero';
        }
    }

    </script>
</body>
</html>
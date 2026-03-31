<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard TV</title>
    <style>
        /* Reset Totale */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background: #0f0f0f;
            color: #eee;
            font-family: system-ui, sans-serif;
            height: 100vh;
            width: 100vw;
            display: grid;
            padding: 12px;
            gap: 12px;
            overflow: hidden; /* Impedisce la comparsa di barre di scorrimento */

            /* DEFINIZIONE GRIGLIA */
            grid-template-columns: 420px 1fr; /* Colonna Meteo e Colonna Video */
            grid-template-rows: 60px 1fr 80px; /* Altezza fissa sopra e sotto, flessibile al centro */
        }

        /* --- 1. TESTATA (Sempre in alto, occupa tutto) --- */
        #header-banner {
            grid-column: 1 / 3; /* Da colonna 1 a 3 (tutta la larghezza) */
            grid-row: 1;        /* Prima riga */
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-vvf {
            height: 70px;    /* Regola questa altezza in base a quanto vuoi grandi i loghi */
            width: auto;     /* Mantiene le proporzioni */
            padding: 5px;    /* Un po' di respiro intorno al logo */
        }

        /* --- 2. METEO (Sinistra) --- */
        #meteo-box {
            grid-column: 1;     /* Prima colonna */
            grid-row: 2;        /* Seconda riga (centro) */
            background: #1a1a2e;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            min-height: 0; /* Importante per non far "esplodere" il box */
        }
        #meteo-box .citta { font-size: 22px; font-weight: 500; color: #fff; }
        #meteo-box .temp  { font-size: 90px; font-weight: 200; line-height: 1; }
        #meteo-box .desc  { font-size: 16px; color: #aaa; }

        /* --- 3. VIDEO (Destra) --- */
        #player-box {
            grid-column: 2;     /* Seconda colonna */
            grid-row: 2;        /* Seconda riga (centro) */
            background: #000;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            min-height: 0;
        }
        #player-box video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- 4. NOTIZIE (In fondo, occupa tutto) --- */
        #notizie-box {
            grid-column: 1 / 3; /* Da colonna 1 a 3 (tutta la larghezza) */
            grid-row: 3;        /* Terza riga (fondo) */
            background: #222;   /* Grigio scuro leggermente più chiaro del fondo */
            border-radius: 8px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        #notizie-header {
            background: #e24b4a;
            padding: 0 20px;
            height: 100%;
            display: flex;
            align-items: center;
            font-size: 20px;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
            color: white;
        }
        #notizie-track {
            flex: 1;
            overflow: hidden;
            display: flex;
            align-items: center;
        }
        #notizie-inner {
            display: inline-block;
            white-space: nowrap;
            font-size: 36px; /* Dimensione ideale per TV */
            color: #ccc;
            animation: scroll-left 900s linear infinite;
        }
        @keyframes scroll-left {
            0%   { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }

        /* Bottone Fullscreen */
        #btn-fs {
            position: fixed;
            top: 12px;
            right: 12px;
            z-index: 999;
            background: rgba(255,255,255,0.1);
            border: none;
            color: #666;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            opacity: 0;
            transition: 0.3s;
        }
        #btn-fs:hover { opacity: 1; background: #eee; color: #000; }

        /* Classe per nascondere il mouse */
        .hide-cursor {
            cursor: none !important;
        }
    </style>
</head>
<body>

    <div id="header-banner">
        <img src="{{ asset('images/logo-vvf.png') }}" class="logo-vvf">

        <span style="font-size: 40px; font-weight: 700; color: #f20505; letter-spacing: 2px; margin: 0 80px;">
            DIREZIONE REGIONALE VIGILI DEL FUOCO MARCHE
        </span>

        <img src="{{ asset('images/logo-vvf.png') }}" class="logo-vvf">
    </div>
    
    <button onclick="toggleFullscreen()" id="btn-fs">⛶ Schermo intero</button>

    <div id="meteo-box">
        <div class="citta" id="meteo-citta">Ancona</div>
        <div class="temp" id="meteo-temp">--°</div>
        <div class="desc" id="meteo-vento">Vento: --</div>
        <div class="desc" id="meteo-umidita">Umidità: --</div>
        <div class="desc" id="meteo-pressione">Pressione: --</div>
        
        <div style="width: 100%; flex: 1; margin-top: 15px; border-radius: 12px; overflow: hidden; border: 1px solid #333;">
            <iframe width="100%" height="100%" src="https://embed.windy.com/embed2.html?lat=43.61&lon=13.51&zoom=7&level=surface&overlay=clouds&product=ecmwf&metricWind=km%2Fh&metricTemp=%C2%B0C" frameborder="0"></iframe>
        </div>
    </div>

    <div id="player-box">
        <video id="video-player" autoplay muted playsinline>
            Il tuo browser non supporta il video.
        </video>
    </div>

    <div id="notizie-box">
        <div id="notizie-header">● LIVE</div>
        <div id="notizie-track">
            <div id="notizie-inner">Caricamento notizie...</div>
        </div>
    </div>

    <script>
        // Funzioni JavaScript (Meteo, Video, Notizie, Fullscreen)
        async function caricaMeteo() {
            try {
                // Proviamo a chiamare DIRETTAMENTE Open-Meteo dal browser, senza passare per Laravel
                const lat = 43.61;
                const lon = 13.51;
                const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,windspeed_10m,relativehumidity_2m,surface_pressure&wind_speed_unit=kmh`;
        
                const r = await fetch(url); 
                const d = await r.json();

                document.getElementById('meteo-citta').textContent = 'Ancona';
                document.getElementById('meteo-temp').textContent = Math.round(d.current.temperature_2m) + '°C';
                document.getElementById('meteo-vento').textContent = 'Vento: ' + Math.round(d.current.windspeed_10m) + ' km/h';
                document.getElementById('meteo-umidita').textContent = 'Umidità: ' + d.current.relativehumidity_2m + '%';
                document.getElementById('meteo-pressione').textContent = 'Pressione: ' + Math.round(d.current.surface_pressure) + ' hPa';
        
            } catch(e) {
                console.error('Il browser non riesce a scaricare il meteo:', e);
                // Se fallisce, forse serve davvero il proxy o Laravel
            }
        }

        let playlist = [];
        let indiceCorrente = 0;
        async function caricaVideo() {
            try {
                const r = await fetch('/api/video');
                playlist = await r.json();
                if (playlist.length > 0) cambiaVideo(playlist[0].url);
            } catch(e) { console.error(e); }
        }
        function cambiaVideo(url) {
            const player = document.getElementById('video-player');
            player.src = url.replace(/\\\//g, '/');
            player.play();
        }
        document.getElementById('video-player').addEventListener('ended', () => {
            indiceCorrente = (indiceCorrente + 1) % playlist.length;
            if (playlist[indiceCorrente]) cambiaVideo(playlist[indiceCorrente].url);
        });

        async function caricaNotizie() {
            try {
                const r = await fetch('/api/notizie');
                const notizie = await r.json();
                if (!notizie.length) return;
                const testo = notizie.join('    ●    ');
                document.getElementById('notizie-inner').textContent = testo + '    ●    ' + testo;
            } catch(e) { document.getElementById('notizie-inner').textContent = 'Notizie non disponibili'; }
        }

        caricaMeteo(); caricaVideo(); caricaNotizie();
        setInterval(caricaMeteo, 600000);
        setInterval(caricaNotizie, 300000);

        let timerBottone;
        document.addEventListener('mousemove', () => {
            const btn = document.getElementById('btn-fs');
            btn.style.opacity = '1';
            clearTimeout(timerBottone);
            timerBottone = setTimeout(() => btn.style.opacity = '0', 2000);
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


        let mouseTimer;

        document.addEventListener('mousemove', () => {
            // 1. Mostra il mouse (rimuove la classe)
            document.body.classList.remove('hide-cursor');
    
            // 2. Cancella il vecchio timer
            clearTimeout(mouseTimer);
    
            // 3. Fai partire un nuovo timer di 3 secondi (3000ms)
            mouseTimer = setTimeout(() => {
                document.body.classList.add('hide-cursor');
            }, 3000); 
        });
        
    </script>
</body>
</html>
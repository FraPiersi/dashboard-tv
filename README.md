# 📺 Dashboard TV Laravel

Una dashboard moderna, leggera e automatizzata, progettata per essere visualizzata su monitor o TV in modalità "kiosk". Integra un player video locale, informazioni meteo geolocalizzate e un news ticker in tempo reale.

## ✨ Funzionalità

- **🎥 Video Player Automizzato**: Riproduce in loop tutti i video presenti nella cartella locale. Passaggio automatico al video successivo.
- **🌤️ Meteo Smart**: Geolocalizzazione automatica tramite browser (Open-Meteo API) e mappa dei venti integrata (Windy).
- **📰 News Ticker**: Scorrimento verticale delle ultime notizie ANSA via RSS feed.
- **🖥️ Fullscreen Mode**: Pulsante a scomparsa per attivare la modalità a tutto schermo.
- **🛠️ Setup Automatizzato**: Script PowerShell inclusi per configurare l'intero ambiente di sviluppo in pochi clic.

---

## 🚀 Guida all'installazione (Windows)

Segui questi passaggi per far girare la dashboard sul tuo PC.

# 1.

Clona il repository o scarica lo ZIP:


# 2. 
Setup dell'Ambiente (Solo la prima volta)
Se il PC non ha i programmi necessari installati, apri PowerShell come Amministratore nella cartella del progetto ed esegui:

.\setup.ps1

Questo installerà XAMPP, Composer e Git tramite winget. Riavvia la PowerShell dopo questo passaggio.

# 3. 

Esegui lo script di configurazione per preparare il database e le chiavi di sistema:

.\installa.ps1

# 4. 

Inserisci i tuoi file video (MP4, WEBM o MKV) nella cartella locale:
public/videos/

Per avviare la dashboard, digita nel terminale:

php artisan serve
Apri il browser all'indirizzo: http://localhost:8000

Nota: Al primo avvio, clicca su "Consenti" quando il browser richiede l'accesso alla posizione per mostrare il meteo corretto.



📂 Struttura Tecnica del Progetto
app/Http/Controllers/DashboardController.php: Gestisce la logica backend, il recupero dei dati meteo, il parsing dei feed RSS e la scansione della cartella video.

resources/views/dashboard.blade.php: Contiene l'intera interfaccia utente, gli stili CSS Grid e la logica JavaScript per gli aggiornamenti dinamici.

routes/web.php: Definisce i punti di accesso (endpoint) per la pagina principale e le API interne.

public/videos/: Cartella di archiviazione per i contenuti multimediali.

setup.ps1: Script PowerShell per l'installazione dei software di base (XAMPP, Git, Composer).

installa.ps1: Script PowerShell per la configurazione rapida di Laravel (.env, key, database, link).



📡 Servizi e API Utilizzate
Framework: Laravel 11

Dati Meteo: Open-Meteo API

Geocoding: Nominatim OpenStreetMap

Mappa: Windy Embed

Notizie: ANSA RSS Feed


















<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

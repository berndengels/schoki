# Schokoladen Berlin-Mitte Page
GitHub: [https://github.com/berndengels/schoki](https://github.com/berndengels/schoki)  
URL: [https://www.schokoladen-mitte.de/](https://www.schokoladen-mitte.de/)

### Installations Voraussetzungen
- Webserver (apache2, nginx)
- Mysql-Datenbank-Server (mariadb, mysql)
- composer
- npm
- git
- Mac: Homebrew, Larel Valet
- Windows: XAMPP
- php 7.4

### Datenbank
- Mac: brew install mariadb (oder brew install mysql), brew install php 
- Datenbank "schokoladen" erstellen
- als User einloggen [https://www.schokoladen-mitte.de/admin](https://www.schokoladen-mitte.de/admin)
- falls Passwort nicht bekannt, dann über [Login](https://www.schokoladen-mitte.de/login) -> "Passwort vergessen" selbiges vergeben.  
- Datenbank-Datei von mir anfordern. Ich schicke sie dann per Mail zu.

### Installationen
virtuellen Host für die lokale Schoki-Page erstellen 
- (Mac) am besten mit [Laravel Valet](https://laravel.com/docs/10.x/valet), setzt installiertes [Homebrew](https://brew.sh/de/) voraus.
- (Windos) [XAMPP](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.12/xampp-windows-x64-8.2.12-0-VS16-installer.exe/download)

mit Terminal ins Projektverzeichnis wechseln und dort folgene Befehle ausführen
- .env.tpl kopieren zu .env (in Letzterer Konfiguratinsdaten setzen z.b: Passworte) 
- composer install
- npm install
- npm run dev (oder npm run watch, wenn man grad an Javascript oder Sass-Dateien arbeitet)
- falls valet: valet use php@8.2, valet secure schoki (für https)
- symbolischen Link von "public_html" zu "public" erstellen
- für Linux, Mac: Schreibrechte für alle setzen für folgende Pfade: storage, public_html/media, bootstrap/cache
- Über [https://www.schokoladen-mitte.de/admin/services/syncImages](Media -> sync Images) kannst Du alle Event-Grafiken lokal synchronisieren.
- jetzt sollte man die Seite gemäss der konfigurierten URL laden können (per valet z.B: schoki.test)

### Layout für public Page
- Grund-Layout (html): resources/views/layouts/public.blade.php
- CSS (über Sass): resources/sass/dark.css
- JS: resources/js/app.js 
- views (html): resources/views/public
- event view (html): resources/views/public/events-lazy.blade.php
- obere navigation: resources/views/public/templates/topNavigation.blade.php
- untere navigation: resources/views/public/templates/bottomNavigation.blade.php
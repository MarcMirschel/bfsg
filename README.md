# BFSG‑Scanner

CLI‑ und Web‑Tool zur automatisierten BFSG (Bundesförderungsstärkungsgesetz) / WCAG 2.1 AA‑Prüfung von Websites.

## Quick Start

```bash
git clone https://github.com/zoobrothers/bfsg-scanner.git
cd bfsg-scanner
composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Beispiel‑Scan

```bash
php artisan accessibility:scan https://beispiel.de --max-pages=100 --depth=3
```

## Cron‑Job

```cron
* * * * * www-data php /var/www/bfsg-scanner/artisan schedule:run >> /dev/null 2>&1
```

## Lizenz

MIT

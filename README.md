# tecnora-api
Tecnora — API backend (Laravel)

## Requisiti

- Docker con Docker Compose v2
- Solo per uso fuori da Docker: PHP 8.3 (estensioni `pdo_pgsql`, `zip`, `intl`, `bcmath`) e Composer 2

## Avvio con Docker

```bash
docker compose up --build
```

All'avvio il container `app`:

1. crea `.env` da `.env.example` se manca;
2. genera `APP_KEY` se assente;
3. attende che `db` (PostgreSQL 16) sia healthy ed esegue `php artisan migrate --force`;
4. avvia l'API su http://localhost:8000.

Verifica:

```bash
curl http://localhost:8000/api/health
# {"status":"ok","db":true}
```

`db` è `false` se il database non è raggiungibile (la risposta resta `200`).

> In Docker la configurazione viene letta dal file `.env` montato nel container (`php artisan serve` non inoltra le variabili d'ambiente del container al server).
> Se hai un `.env` locale con `DB_HOST` diverso da `db`, l'app nel container non raggiunge il database.

## Variabili principali

| Variabile | Default | Note |
| --- | --- | --- |
| `APP_URL` | `http://localhost:8000` | URL pubblico dell'API |
| `FRONTEND_URL` | `http://localhost:5173` | Origine consentita da CORS (`config/cors.php`) |
| `DB_CONNECTION` | `pgsql` | |
| `DB_HOST` | `db` | Nome del servizio nel compose |
| `DB_PORT` | `5432` | |
| `DB_DATABASE` | `tecnora` | |
| `DB_USERNAME` | `tecnora` | |
| `DB_PASSWORD` | `secret` | |

## Test

```bash
php artisan test
```

In locale i test usano SQLite in memoria (`phpunit.xml`, richiede `pdo_sqlite`); in CI girano su PostgreSQL 16 (`.github/workflows/ci.yml`).

> Le `<env>` di `phpunit.xml` non hanno `force="true"`: se `DB_CONNECTION`/`DB_*` sono già esportate nell'ambiente della shell, vincono loro (è così che la CI usa pgsql). Per forzare SQLite in locale: `DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test`.

## Stile

```bash
vendor/bin/pint          # corregge
vendor/bin/pint --test   # solo verifica (come in CI)
```

## Note

- `vendor/` nel container è un volume nominato: dopo modifiche a `composer.json`/`composer.lock` eseguire `docker compose build` e `docker compose down -v` (attenzione: `-v` rimuove anche il volume del database) oppure rimuovere solo il volume `vendor`.

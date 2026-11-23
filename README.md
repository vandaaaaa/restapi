# Zip API

Laravel REST API megyék és városok kezelésére. A lekérdezések nyilvánosak, az adatmódosításhoz tokenes (Laravel Sanctum) hitelesítés kell.

## Telepítés

1. `composer install`
2. `cp .env.example .env`, majd a `.env`-ben állítsd be az adatbázist (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
3. Hozz létre egy üres adatbázist `laravel_rest_api` néven
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `php artisan serve`

Az API ezen a címen érhető el: `http://localhost:8000/api`

Teszt felhasználó: `user@example.com` / `12345678`

## Végpontok

| Metódus | Útvonal | Leírás | Token |
|---|---|---|---|
| POST | `/api/login` | Bejelentkezés, a válaszban kapott token kell a védett végpontokhoz | nem |
| GET | `/api/counties` | Megyék listázása a városaikkal | nem |
| GET | `/api/cities` | Városok listázása | nem |
| GET | `/api/cities?search=vác` | Keresés a város nevében | nem |
| GET | `/api/cities?county_id=1` | Városok szűrése megye szerint | nem |
| GET | `/api/cities?search=a&county_id=1` | Keresés és szűrés együtt | nem |
| POST | `/api/counties` | Megye létrehozása | igen |
| PUT | `/api/counties/{id}` | Megye módosítása | igen |
| DELETE | `/api/counties/{id}` | Megye törlése | igen |
| POST | `/api/cities` | Város létrehozása | igen |
| PUT | `/api/cities/{id}` | Város módosítása | igen |
| DELETE | `/api/cities/{id}` | Város törlése | igen |

A védett végpontokhoz add meg a fejlécet: `Authorization: Bearer <token>`

## Dokumentáció

Az APIDOC dokumentáció a `public/docs/index.html` fájlban található. Újragenerálás:

```
apidoc -i app/Http/Controllers -o public/docs
```

## Tesztelés

A Bruno gyűjtemény a `bruno/` mappában található.
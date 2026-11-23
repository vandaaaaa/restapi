<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{   
/**
 * @api {get} /cities Városok listázása, keresés, szűrés
 * @apiVersion 1.0.0
 * @apiName GetCities
 * @apiGroup City
 *
 * @apiQuery {String} [search] Keresés a város nevében (részletre is keres)
 * @apiQuery {Number} [county_id] Szűrés a megye azonosítója szerint
 *
 * @apiSuccess {Object[]} cities A városok listája
 * @apiSuccess {Number} cities.id Város azonosítója
 * @apiSuccess {String} cities.name Város neve
 * @apiSuccess {Number} cities.county_id A megye azonosítója
 * @apiSuccess {Object} cities.county A város megyéje
 */
public function index(Request $request)
{
    $query = City::with('county');

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('county_id')) {
        $query->where('county_id', $request->county_id);
    }

    return response()->json([
        'cities' => $query->get(),
    ]);
}
/**
 * @api {post} /cities Város létrehozása
 * @apiVersion 1.0.0
 * @apiName CreateCity
 * @apiGroup City
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiBody {String} name A város neve (max. 255 karakter)
 * @apiBody {Number} county_id Létező megye azonosítója
 *
 * @apiSuccess {Object} city A létrehozott város
 *
 * @apiError (401) {String} message Nincs vagy érvénytelen token
 * @apiError (422) {Object} errors Hibás vagy hiányzó adat
 */
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'county_id' => 'required|exists:counties,id',
    ]);

    $city = City::create($request->all());

    return response()->json([
        'city' => $city,
    ]);
}
/**
 * @api {put} /cities/:id Város módosítása
 * @apiVersion 1.0.0
 * @apiName UpdateCity
 * @apiGroup City
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiParam {Number} id A város azonosítója (az URL-ben)
 * @apiBody {String} [name] A város új neve
 * @apiBody {Number} [county_id] Az új megye azonosítója
 *
 * @apiSuccess {Object} city A módosított város
 *
 * @apiError (401) {String} message Nincs vagy érvénytelen token
 * @apiError (404) {String} message A város nem található
 */
public function update(Request $request, $id)
{
    $city = City::findOrFail($id);
    $city->update($request->all());

    return response()->json([
        'city' => $city,
    ]);
}
/**
 * @api {delete} /cities/:id Város törlése
 * @apiVersion 1.0.0
 * @apiName DeleteCity
 * @apiGroup City
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiParam {Number} id A város azonosítója (az URL-ben)
 *
 * @apiSuccess {String} message "City deleted"
 *
 * @apiError (401) {String} message Nincs vagy érvénytelen token
 * @apiError (404) {String} message A város nem található
 */
public function destroy($id)
{
    $city = City::findOrFail($id);
    $city->delete();

    return response()->json([
        'message' => 'City deleted',
    ]);
}
}

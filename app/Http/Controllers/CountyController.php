<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\County;

class CountyController extends Controller
{
    /**
 * @api {get} /counties Megyék listázása
 * @apiVersion 1.0.0
 * @apiName GetCounties
 * @apiGroup County
 *
 * @apiSuccess {Object[]} counties A megyék listája, városaikkal
 * @apiSuccess {Number} counties.id Megye azonosítója
 * @apiSuccess {String} counties.name Megye neve
 * @apiSuccess {Object[]} counties.cities A megye városai
 */
    public function index()
{
    $counties = County::with('cities')->get();
    return response()->json([
        'counties' => $counties,
    ]);
}
/**
 * @api {post} /counties Megye létrehozása
 * @apiVersion 1.0.0
 * @apiName CreateCounty
 * @apiGroup County
 * @apiHeader {String} Authorization Bearer token (pl. "Bearer 1|abc...")
 *
 * @apiBody {String} name A megye neve (max. 255 karakter)
 *
 * @apiSuccess {Object} county A létrehozott megye
 *
 * @apiError (401) {String} message Nincs vagy érvénytelen token
 * @apiError (422) {Object} errors Hibás vagy hiányzó adat
 */
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $county = County::create($request->all());

    return response()->json([
        'county' => $county,
    ]);
}
/**
 * @api {put} /counties/:id Megye módosítása
 * @apiVersion 1.0.0
 * @apiName UpdateCounty
 * @apiGroup County
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiParam {Number} id A megye azonosítója (az URL-ben)
 * @apiBody {String} name A megye új neve
 *
 * @apiSuccess {Object} county A módosított megye
 *
 * @apiError (401) {String} message Nincs vagy érvénytelen token
 * @apiError (404) {String} message A megye nem található
 */
public function update(Request $request, $id)
{
    $county = County::findOrFail($id);
    $county->update($request->all());

    return response()->json([
        'county' => $county,
    ]);
}
/**
 * @api {delete} /counties/:id Megye törlése
 * @apiVersion 1.0.0
 * @apiName DeleteCounty
 * @apiGroup County
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiParam {Number} id A megye azonosítója (az URL-ben)
 *
 * @apiSuccess {String} message "County deleted"
 *
 * @apiError (401) {String} message Nincs vagy érvénytelen token
 * @apiError (404) {String} message A megye nem található
 */
public function destroy($id)
{
    $county = County::findOrFail($id);
    $county->delete();

    return response()->json([
        'message' => 'County deleted',
    ]);
}
}

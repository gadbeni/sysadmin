<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('places', function(){
    $limit = request('limit') ?? 20;
    return response()->json(['places' => App\Models\Place::selectRaw('id, title, subtitle, description, banner, gallery, '.DB::raw("(ST_AsGeoJSON(location)) AS location"))->where('status', 1)->limit($limit)->get()]);
});
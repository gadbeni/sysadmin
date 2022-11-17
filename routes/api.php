<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\DonationsController;

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

Route::get('cultures', function(){
    $limit = request('limit') ?? 20;
    return response()->json(['cultures' => App\Models\Culture::where('status', 1)->limit($limit)->get()]);
});

Route::get('posts', function(){
    $limit = request('limit') ?? 20;
    return response()->json(['posts' => App\Models\Post::where('status', 1)->limit($limit)->get()]);
});

// Donaciones
Route::get('donations_types', function(){
    $limit = request('limit') ?? 20;
    return response()->json(['donations_types' => App\Models\DonationsType::where('status', 1)->limit($limit)->get()]);
});
Route::post('donations/store', [DonationsController::class, 'store']);
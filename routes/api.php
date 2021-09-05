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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);


Route::group(
    [
        'middleware'=>['jwt.verify'],
        'prefix'=>'despesa'
    ],function () {

    Route::post('/', [\App\Http\Controllers\DespesaController::class, 'index']);

    Route::post('/novo', [\App\Http\Controllers\DespesaController::class, 'store']);

    Route::get('/editar/{id}', [\App\Http\Controllers\DespesaController::class, 'update']);

    Route::get('/apagar/{id}', [\App\Http\Controllers\DespesaController::class, 'destroy']);

});




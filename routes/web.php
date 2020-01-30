<?php

use App\Game;
use App\Http\Resources\GameResource;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/api/game/{id}', function($id) {
    return new GameResource(Game::where('id', $id)->first());
});
Route::post('/api/game/{id}/message', 'GameController@receiveMessage');
Route::post('/api/game/{id}', 'GameController@handleCommand');

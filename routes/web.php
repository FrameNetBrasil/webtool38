<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Index\MainController as IndexMainController;
use App\Modules\Auth\Controllers\LoginController as AuthLoginController;
use App\Http\Controllers\Structure\FrameController as StructureFrameController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


/*
Route::controller(IndexMainController::class)->group(function () {
    Route::get('/', 'main');
    Route::get('/login', 'login');
    Route::get('/logout', 'logout');
    Route::get('/main/auth0Callback', 'auth0Callback');
    Route::get('/changeLanguage/{lang}', 'changeLanguage');
});

Route::controller(StructureFrameController::class)->group(function () {
    Route::get('/frames', 'main');
    Route::post('/frames/grid', 'grid');
    Route::get('/frames/{id}/edit', 'edit');
    Route::get('/frames/{id}/entries', 'entries');
    Route::get('/frames/{id}/newFE', 'newFE');
    Route::get('/frames/{id}/newLU', 'newLU');
    Route::get('/frames/{id}/classification', 'classification');
    Route::get('/frames/{id}/relations', 'relations');
    Route::get('/frames/{id}/semanticTypes', 'semanticTypes');
    Route::get('/frames/{id}/feRelations', 'feRelations');

});

Route::controller(AuthLoginController::class)->group(function () {
    Route::post('/authenticate', 'authenticate');
});
*/
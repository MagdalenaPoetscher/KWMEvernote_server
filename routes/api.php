<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Models\Tag;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('user',[UserController::class, 'index']);
Route::get('user/{id}',[UserController::class, 'findByID']);


Route::get('catalogues',[CatalogueController::class, 'index']);
Route::get('catalogues/{id}', [CatalogueController::class,'findById']);
Route::get('catalogues/checkId/{id}', [CatalogueController::class,'checkId']);


Route::get('notes',[NoteController::class, 'index']);
Route::get('notes/{id}', [NoteController::class,'findById']);
Route::get('notes/checkId/{id}', [NoteController::class,'checkId']);


Route::get('tag',[TagController::class, 'index']);
Route::get('tag/{id}', [TagController::class,'findById']);
Route::get('tag/checkId/{id}', [TagController::class,'checkId']);
Route::get('tag/search/{searchTerm}',[TagController::class,'findBySearchTerm']);



Route::get('todo',[TodoController::class, 'index']);
Route::get('todo/{id}', [TodoController::class,'findById']);
Route::get('todo/checkId/{id}', [TodoController::class,'checkId']);



//Login
Route::post('auth/login', [AuthController::class, 'login']);
Route::group(['middleware'=> ['api', 'auth.jwt']], function (){
    //Catalogue Routen
    Route::post('catalogues', [CatalogueController::class, 'save']);
    Route::put('catalogues/{id}', [CatalogueController::class, 'update']);
    Route::delete('catalogues/{id}', [CatalogueController::class, 'delete']);
    //Notes Routen
    Route::post('notes', [NoteController::class, 'save']);
    Route::put('notes/{id}', [NoteController::class, 'update']);
    Route::delete('notes/{id}', [NoteController::class, 'delete']);
    //Tag Routen
    Route::post('tag', [TagController::class, 'save']);
    Route::put('tag/{id}', [TagController::class, 'update']);
    Route::delete('tag/{id}', [TagController::class, 'delete']);
    //To do Routen
    Route::post('todo', [TodoController::class, 'save']);
    Route::put('todo/{id}', [TodoController::class, 'update']);
    Route::delete('todo/{id}', [TodoController::class, 'delete']);

    //Logout
    Route::post('auth/logout', [AuthController::class, 'logout']);
});

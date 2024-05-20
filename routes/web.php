<?php

use App\Http\Controllers\CatalogueController;
use App\Models\Note;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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


/*Route::get('/', function () {
    $notes = Note::all();
    return view('notes.index', compact('notes'));
});*/

/*Route::get('/notes', function () {
    $notes = Note::all();
    return view('notes.index', compact('notes'));
});*/

/*Route::get('/notes/{id}', function ($id) {
    $note = Note::find($id);
    return view('notes.show', compact('note'));
});*/

Route::get('/catalogues', [CatalogueController::class, "index"]);

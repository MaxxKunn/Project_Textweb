<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [RecipeController::class, 'home']);
Route::get('/input', [RecipeController::class, 'input']);
Route::post('/result', [RecipeController::class, 'result']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDF;

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

Route::get("/", [PDF::class, "index"])->name("pdf.index");
Route::post("pdf/upload", [PDF::class, "upload"])->name("pdf.upload");
Route::get("pdf/merge", [PDF::class, "merge"])->name("pdf.merge");

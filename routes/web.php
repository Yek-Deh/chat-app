<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ChatController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('fetch-messages', [ChatController::class,'fetchMessages'])->middleware(['auth', 'verified'])->name('fetch-messages');
Route::post('send-message', [ChatController::class,'sendMessage'])->middleware(['auth', 'verified'])->name('send-message');

require __DIR__.'/auth.php';

Route::get('/dal',function (){
    DB::table('messages')->truncate();
    return back();
});

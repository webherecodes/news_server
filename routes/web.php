<?php

use App\Events\MessageNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/event', function () {
    broadcast(new MessageNotification('Hai'));
    return "Event broadcasted!";
});

Route::get('/broadcast', function () {
    return view('broadcast');
});


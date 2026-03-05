<?php

declare(strict_types=1);

use Awcodes\Botly\Http\Controllers\BotlyController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', BotlyController::class)->name('botly');

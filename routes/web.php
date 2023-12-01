<?php

use Illuminate\Support\Facades\Route;
use Karomap\LaravelAttachment\Http\Controllers\MediaController;

Route::get('media/{path}', MediaController::class)->where('path', '.+')->name('media');

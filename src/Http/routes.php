<?php

use Illuminate\Support\Facades\Route;

// Health URL
Route::get('/health', 'HealthController@health');
Route::get('/healthz', 'HealthController@healthz');

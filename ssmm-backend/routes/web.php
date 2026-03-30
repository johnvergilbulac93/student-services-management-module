<?php

use App\Events\Import\NotificationImportEvent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return  'Backend service is running' . ' - ' . date('Y-m-d H:i:s');
});
Route::get('{any}', function () {
    return redirect('/');
})->where('any', '.*');

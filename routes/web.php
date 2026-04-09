<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;

// بدل ما يفتح صفحة الـ welcome، هيروح علطول للـ index بتاعة الخدمات
Route::get('/', [ServiceController::class, 'index']);

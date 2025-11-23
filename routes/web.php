<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PedidosController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');
Route::get('/pedidos', [PedidosController::class, 'index'])->name('pedidos');

<?php

use App\Http\Controllers\Admin\CajaController;
use App\Http\Controllers\Admin\ItemInventarioController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\RegistroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PantallaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CocinaController;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tragos', [HomeController::class, 'tragos'])->name('tragos.index');

Route::get('/nosotros', [HomeController::class, 'nosotros'])->name('nosotros.index');

Route::get('/pantalla', [PantallaController::class, 'index'])->name('pantalla.index');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {

    Route::resource('productos', ProductoController::class);
    Route::resource('inventario', ItemInventarioController::class);
    Route::resource('pedidos', PedidoController::class);

    Route::get('cocina', [CocinaController::class, 'index'])->name('cocina.index');

    Route::patch('pedidos/{pedido}/listo', [PedidoController::class, 'marcarComoListo'])->name('pedidos.listo');
    Route::patch('pedidos/{pedido}/entregado', [App\Http\Controllers\Admin\PedidoController::class, 'marcarComoEntregado'])->name('pedidos.entregado');


    Route::post('caja', [CajaController::class, 'store'])->name('caja.store');
    Route::patch('caja/{caja}', [CajaController::class, 'update'])->name('caja.update');

    Route::get('registros', [RegistroController::class, 'index'])->name('registros.index');
    Route::get('registros/{caja}', [RegistroController::class, 'show'])->name('registros.show');
});

require __DIR__ . '/auth.php';
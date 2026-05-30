<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacturacionElectronicaController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['auth', 'role:Administrador|Admin|Administrador general'])->group(function () {
    Route::get('/admin', function () {
        return 'Bienvenido ADMIN de FarmaGo';
    });
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('consulta-precios', [ProductoController::class, 'consultarPrecio'])->name('productos.consultar-precio');
    Route::get('productos/buscar-por-codigo', [ProductoController::class, 'buscarPorCodigo'])->name('productos.buscar-por-codigo');
    Route::resource('productos', ProductoController::class);
    Route::resource('lotes', LoteController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('proveedores', ProveedorController::class)->parameters([
        'proveedores' => 'proveedor',
    ]);
    Route::resource('compras', CompraController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::resource('ventas', VentaController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::get('ventas/{venta}/comprobante', [VentaController::class, 'comprobante'])->name('ventas.comprobante');
    Route::post('ventas/{venta}/facturacion-electronica', [FacturacionElectronicaController::class, 'emitir'])->name('ventas.facturacion.emitir');
    Route::get('facturacion-electronica', [FacturacionElectronicaController::class, 'index'])->name('facturacion.index');
    Route::get('facturacion-electronica/{comprobante}', [FacturacionElectronicaController::class, 'show'])->name('facturacion.show');

    Route::get('kardex', [KardexController::class, 'index'])->name('kardex.index');
    Route::get('kardex/producto/{producto}', [KardexController::class, 'producto'])->name('kardex.producto');
    Route::get('kardex/lote/{lote}', [KardexController::class, 'lote'])->name('kardex.lote');

    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
    Route::get('reportes/stock-bajo', [ReporteController::class, 'stockBajo'])->name('reportes.stock-bajo');
    Route::get('reportes/vencimientos', [ReporteController::class, 'vencimientos'])->name('reportes.vencimientos');
    Route::get('reportes/productos-mas-vendidos', [ReporteController::class, 'productosMasVendidos'])->name('reportes.productos-mas-vendidos');
});

Route::middleware(['auth', 'role:Administrador|Admin|Administrador general'])->group(function () {
    Route::resource('usuarios', UsuarioController::class)->except(['show']);
    Route::patch('usuarios/{usuario}/bloqueo', [UsuarioController::class, 'toggleLock'])->name('usuarios.toggle-lock');
});

require __DIR__.'/auth.php';

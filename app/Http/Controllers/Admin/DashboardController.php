<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'daily');

        switch ($filter) {
            case 'weekly':
                $startDate = now()->subWeek();
                break;
            case 'monthly':
                $startDate = now()->subMonth();
                break;
            case 'annually':
                $startDate = now()->subYear();
                break;
            case 'daily':
            default:
                $startDate = now()->startOfDay();
                break;
        }

        $totalFacturado = Caja::where('estado', 'cerrada')
            ->where('fecha_cierre', '>=', $startDate)
            ->withSum('pedidos', 'precio_total')
            ->get()
            ->sum('pedidos_sum_precio_total');

        $productosMasVendidos = DB::table('pedido_producto')
            ->join('productos', 'pedido_producto.producto_id', '=', 'productos.id_producto')
            ->join('pedidos', 'pedido_producto.pedido_id', '=', 'pedidos.id_pedido')
            ->where('pedidos.created_at', '>=', $startDate)
            ->select('productos.nombre', DB::raw('SUM(pedido_producto.cantidad) as total_vendido'))
            ->groupBy('productos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get();

        $cajaAbierta = Caja::where('estado', 'abierta')->first();
        $pedidosPendientes = 0;
        $pedidosListos = 0;

        if($cajaAbierta){
            $pedidosPendientes = $cajaAbierta->pedidos()->where('status', 'pendiente')->count();
            $pedidosListos = $cajaAbierta->pedidos()->where('status', 'listo')->count();
        }


        return Inertia::render('Dashboard', [
            'totalFacturado' => $totalFacturado,
            'productosMasVendidos' => $productosMasVendidos,
            'pedidosPendientes' => $pedidosPendientes,
            'pedidosListos' => $pedidosListos,
            'currentFilter' => $filter,
        ]);
    }
}
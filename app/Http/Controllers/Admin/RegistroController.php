<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegistroController extends Controller
{
   public function index(Request $request)
    {
        $query = Caja::query()->where('estado', 'cerrada');

        $query->when($request->input('date'), function ($q, $date) {
            return $q->whereDate('fecha_apertura', $date);
        });

        $registros = $query->with(['openedBy', 'closedBy'])
            ->withSum(['pedidos as total_efectivo' => function ($query) {
                $query->where('tipo_pago', 'efectivo')
                      ->whereIn('status', ['listo', 'entregado']);
            }], 'precio_total')
            ->withSum(['pedidos as total_transferencia' => function ($query) {
                $query->where('tipo_pago', 'transferencia')
                      ->whereIn('status', ['listo', 'entregado']);
            }], 'precio_total')
            ->withSum(['pedidos as total_general' => function ($query) {
                $query->whereIn('status', ['listo', 'entregado']);
            }], 'precio_total')
            ->latest('fecha_cierre')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Registros/Index', [
            'registros' => $registros,
            'filters' => $request->only(['date']),
        ]);
    }

    public function show(Caja $caja)
    {
        $caja->load(['pedidos.productos', 'openedBy', 'closedBy']);

        return Inertia::render('Admin/Registros/Show', [
            'registro' => $caja
        ]);
    }
}

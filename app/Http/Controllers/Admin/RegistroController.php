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
            return $q->whereDate('fecha_apertura', '>=', $date);
        });

        $registros = $query->with(['openedBy', 'closedBy'])
            ->withSum('pedidos', 'precio_total')
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

<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PantallaController extends Controller
{
    public function index()
    {
        $cajaAbierta = Caja::where('estado', 'abierta')->first();
        $pedidosPendientes = [];
        $pedidosListos = [];

        if ($cajaAbierta) {
            $pedidos = $cajaAbierta->pedidos()
                                  ->whereIn('status', ['pendiente', 'listo'])
                                  ->orderBy('created_at', 'asc')
                                  ->get();

            $pedidosPendientes = $pedidos->where('status', 'pendiente')->values();
            $pedidosListos = $pedidos->where('status', 'listo')->values();
        }

        return Inertia::render('Pantalla/Index', [
            'pedidosPendientes' => $pedidosPendientes,
            'pedidosListos' => $pedidosListos,
        ]);
    }
}
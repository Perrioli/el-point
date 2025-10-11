<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CocinaController extends Controller
{
    public function index()
    {
        $cajaAbierta = Caja::where('estado', 'abierta')->first();
        $pedidosPendientes = [];

        if ($cajaAbierta) {
            $pedidosPendientes = $cajaAbierta->pedidos()
                ->where('status', 'pendiente')
                ->with('productos')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return Inertia::render('Admin/Cocina/Index', [
            'pedidosPendientes' => $pedidosPendientes,
        ]);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CajaController extends Controller
{
    public function store(Request $request)
    {
        $cajaAbierta = Caja::where('estado', 'abierta')->first();
        if ($cajaAbierta) {
            return redirect()->route('admin.pedidos.index')->with('error', 'Ya hay una caja abierta.');
        }

        Caja::create([
            'fecha_apertura' => Carbon::now(),
            'estado' => 'abierta',
        ]);

        return redirect()->route('admin.pedidos.index')->with('success', 'Caja abierta exitosamente.');
    }

    public function update(Caja $caja)
    {
        $caja->fecha_cierre = Carbon::now();
        $caja->estado = 'cerrada';

        $caja->save();

        return redirect()->route('admin.pedidos.index')->with('success', 'Caja cerrada exitosamente.');
    }
}

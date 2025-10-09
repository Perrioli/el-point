<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PedidoController extends Controller
{
    public function index()
    {
        $cajaAbierta = Caja::where('estado', 'abierta')->first();

        if (!$cajaAbierta) {
            return Inertia::render('Admin/Pedidos/CajaCerrada');
        }

        $pedidos = Pedido::where('caja_id', $cajaAbierta->id)
            ->with('productos')
            ->latest('id_pedido')
            ->get();

        return Inertia::render('Admin/Pedidos/Index', [
            'pedidos' => $pedidos,
            'caja' => $cajaAbierta,
        ]);
    }

    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        return Inertia::render('Admin/Pedidos/Create', [
            'productos' => $productos
        ]);
    }

    public function store(Request $request)
    {
        $cajaAbierta = Caja::where('estado', 'abierta')->firstOrFail();

        $validated = $request->validate([
            'persona' => 'required|string|max:255',
            'comentarios' => 'nullable|string',
            'precio_total' => 'required|numeric',
            'productos_pedido' => 'required|array|min:1',
            'productos_pedido.*.producto_id' => 'required|exists:productos,id_producto',
            'productos_pedido.*.cantidad' => 'required|integer|min:1',
            'productos_pedido.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $ultimoNumero = Pedido::where('caja_id', $cajaAbierta->id)->max('numero_caja');

        $nuevoNumeroCaja = ($ultimoNumero ?? 0) + 1;

        $pedido = Pedido::create([
            'caja_id' => $cajaAbierta->id,
            'persona' => $validated['persona'],
            'comentarios' => $validated['comentarios'],
            'precio_total' => $validated['precio_total'],
            'numero_pedido' => date('Ymd-His') . '-' . substr(uniqid(), -4),
            'numero_caja' => $nuevoNumeroCaja,
        ]);

        foreach ($validated['productos_pedido'] as $productoData) {
            $pedido->productos()->attach($productoData['producto_id'], [
                'cantidad' => $productoData['cantidad'],
                'precio_unitario' => $productoData['precio_unitario'],
            ]);
        }

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido registrado exitosamente.');
    }

    public function show(Pedido $pedido)
    {
        //
    }

    public function edit(Pedido $pedido)
    {
        //
    }

    // MÉTODO CORREGIDO
    public function update(Request $request, Pedido $pedido)
    {
        // Corregimos la lógica para que también actualice el estado
        $pedido->status = 'entregado';
        $pedido->hora_entrega = now();
        $pedido->save();

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido marcado como entregado.');
    }

    public function marcarComoListo(Pedido $pedido)
    {
        $pedido->status = 'listo';
        $pedido->save();

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido marcado como Listo para Retirar.');
    }

    public function destroy(Pedido $pedido)
    {
        //
    }
}

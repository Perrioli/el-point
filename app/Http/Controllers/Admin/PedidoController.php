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
            'tipo_pago' => 'required|string|in:efectivo,transferencia',
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

        $productosParaSincronizar = [];
        foreach ($validated['productos_pedido'] as $productoData) {
            $productoId = $productoData['producto_id'];
            if (isset($productosParaSincronizar[$productoId])) {
                $productosParaSincronizar[$productoId]['cantidad'] += $productoData['cantidad'];
            } else {
                $productosParaSincronizar[$productoId] = [
                    'cantidad' => $productoData['cantidad'],
                    'precio_unitario' => $productoData['precio_unitario'],
                ];
            }
        }
        $pedido->productos()->sync($productosParaSincronizar);

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido registrado exitosamente.');
    }

    public function show(Pedido $pedido)
    {
        //
    }

    public function edit(Pedido $pedido)
    {
        if ($pedido->status !== 'pendiente') {
            return redirect()->route('admin.pedidos.index')->with('error', 'Solo se pueden editar pedidos pendientes.');
        }

        $pedido->load('productos');

        $productos = Producto::orderBy('nombre')->get();

        return Inertia::render('Admin/Pedidos/Edit', [
            'pedido' => $pedido,
            'productos' => $productos
        ]);
    }

    public function update(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'persona' => 'required|string|max:255',
            'comentarios' => 'nullable|string',
            'precio_total' => 'required|numeric',
            'productos_pedido' => 'required|array|min:1',
            'productos_pedido.*.producto_id' => 'required|exists:productos,id_producto',
            'productos_pedido.*.cantidad' => 'required|integer|min:1',
            'productos_pedido.*.precio_unitario' => 'required|numeric|min:0',
            'tipo_pago' => 'required|string|in:efectivo,transferencia',

        ]);

        $pedido->update([
            'persona' => $validated['persona'],
            'comentarios' => $validated['comentarios'],
            'precio_total' => $validated['precio_total'],
            'tipo_pago' => $validated['tipo_pago'],
        ]);

        $productosParaSincronizar = [];
        foreach ($validated['productos_pedido'] as $productoData) {
            $productoId = $productoData['producto_id'];
            if (isset($productosParaSincronizar[$productoId])) {
                $productosParaSincronizar[$productoId]['cantidad'] += $productoData['cantidad'];
            } else {
                $productosParaSincronizar[$productoId] = [
                    'cantidad' => $productoData['cantidad'],
                    'precio_unitario' => $productoData['precio_unitario'],
                ];
            }
        }
        $pedido->productos()->sync($productosParaSincronizar);

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido actualizado exitosamente.');
    }

    public function marcarComoListo(Pedido $pedido)
    {
        $pedido->status = 'listo';
        $pedido->save();

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido marcado como Listo para Retirar.');
    }

    public function marcarComoEntregado(Pedido $pedido)
    {
        $pedido->status = 'entregado';
        $pedido->hora_entrega = now();
        $pedido->save();

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido marcado como entregado.');
    }

    public function destroy(Pedido $pedido)
    {
        if ($pedido->status !== 'pendiente') {
            return redirect()->route('admin.pedidos.index')->with('error', 'Solo se pueden cancelar pedidos pendientes.');
        }

        $pedido->delete();

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido cancelado exitosamente.');
    }
}

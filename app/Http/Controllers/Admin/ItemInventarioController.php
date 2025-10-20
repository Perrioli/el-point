<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemInventario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemInventarioController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Inventario/Index', [
            'items' => ItemInventario::latest('id_stock')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Inventario/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'producto_stock' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:0',
            'costo' => 'required|numeric|min:0',
        ]);

        ItemInventario::create($validated);

        return redirect()->route('admin.inventario.index')->with('success', 'Ítem añadido al inventario.');
    }

    public function edit(ItemInventario $inventario)
    {
        return Inertia::render('Admin/Inventario/Edit', [
            'item' => $inventario
        ]);
    }

    public function update(Request $request, ItemInventario $inventario)
    {
        $validated = $request->validate([
            'producto_stock' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:0',
            'costo' => 'required|numeric|min:0',
        ]);

        $inventario->update($validated);

        return redirect()->route('admin.inventario.index')->with('success', 'Ítem actualizado.');
    }

    public function destroy(ItemInventario $inventario)
    {
        $inventario->delete();
        return redirect()->route('admin.inventario.index')->with('success', 'Ítem eliminado del inventario.');
    }

    public function increment($id)
    {
        $item = ItemInventario::where('id_stock', $id)->firstOrFail();

        $item->cantidad = ($item->cantidad ?? 0) + 1;
        $item->save();

        return back()->with('success', 'Cantidad incrementada correctamente.');
    }

    /**
     * ➖ Decrementar cantidad (PATCH)
     */
    public function decrement($id)
    {
        $item = ItemInventario::where('id_stock', $id)->firstOrFail();

        $nuevaCantidad = ($item->cantidad ?? 0) - 1;
        if ($nuevaCantidad < 0) {
            $nuevaCantidad = 0;
        }

        $item->cantidad = $nuevaCantidad;
        $item->save();

        return back()->with('success', 'Cantidad reducida correctamente.');
    }
}

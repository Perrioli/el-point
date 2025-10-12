<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Producto;

class HomeController extends Controller
{
    public function index()
    {
        $productos = Producto::where('es_promocion', false)->latest()->get();

        return Inertia::render('Home', [
            'productos' => $productos
        ]);
    }

    public function tragos()
    {
        [$productosEnPromocion, $productosRegulares] = Producto::latest()->get()->partition(function ($producto) {
            return $producto->es_promocion;
        });

        return Inertia::render('Tragos', [
            'productosEnPromocion' => $productosEnPromocion->values(),
            'productosRegulares' => $productosRegulares->values(),
        ]);
    }

    public function nosotros()
    {
        return Inertia::render('Nosotros');
    }
}

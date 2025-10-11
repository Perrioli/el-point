<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Producto;

class HomeController extends Controller
{
    public function index()
{
    $productos = Producto::latest()->get();

    return Inertia::render('Home', [
        'productos' => $productos
    ]);
}

    public function tragos()
    {
        $productos = Producto::latest()->get();

        return Inertia::render('Tragos', [
            'productos' => $productos
        ]);
    }

    public function nosotros()
    {
        return Inertia::render('Nosotros');
    }
}

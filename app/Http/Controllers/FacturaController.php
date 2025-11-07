<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function index()
    {
        // Mostrar listado de facturas
        return view('facturas.index');
    }

    public function create()
    {
        // Mostrar formulario para crear una nueva factura
        return view('facturas.create');
    }

    public function store(Request $request)
    {
        // Guardar una nueva factura
    }
    //
    public function pendientes()
    {
        $facturas = \App\Models\Factura::where('estado', 'pendiente')->get();
        return view('facturas.pendientes', compact('facturas'));
    }

}

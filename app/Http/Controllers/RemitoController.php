<?php

namespace App\Http\Controllers;

use App\Models\Remito;
use App\Models\RemitoItem;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemitoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver remitos')->only(['index', 'show']);
        $this->middleware('permission:crear remitos')->only(['create', 'store']);
        $this->middleware('permission:editar remitos')->only(['edit', 'update']);
        $this->middleware('permission:eliminar remitos')->only(['destroy']);
        $this->middleware('permission:aprobar remitos')->only(['aprobar']);
    }

    public function index()
    {
        $remitos = Remito::with('cliente')->orderBy('id', 'desc')->get();
        return view('admin.remitos.index', compact('remitos'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        return view('admin.remitos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha'      => 'required|date',
            'items.*.descripcion' => 'required',
            'items.*.cantidad' => 'required|numeric|min:1',
        ]);

        $remito = Remito::create([
            'cliente_id' => $request->cliente_id,
            'creado_por' => Auth::id(),
            'fecha'      => $request->fecha,
            'estado'     => 'pendiente',
            'observaciones' => $request->observaciones,
        ]);

        foreach ($request->items as $item) {
            RemitoItem::create([
                'remito_id'   => $remito->id,
                'descripcion' => $item['descripcion'],
                'cantidad'    => $item['cantidad'],
            ]);
        }

        return redirect()->route('remitos.index')->with('success', 'Remito creado correctamente.');
    }

    public function show($id)
    {
        $remito = Remito::with('cliente', 'items')->findOrFail($id);
        return view('admin.remitos.show', compact('remito'));
    }

    public function edit($id)
    {
        $remito = Remito::with('items')->findOrFail($id);
        $clientes = Cliente::all();
        return view('admin.remitos.edit', compact('remito', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $remito = Remito::findOrFail($id);

        $request->validate([
            'cliente_id' => 'required',
            'fecha'      => 'required|date',
        ]);

        $remito->update([
            'cliente_id' => $request->cliente_id,
            'fecha'      => $request->fecha,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('remitos.index')->with('success', 'Remito actualizado correctamente.');
    }

    public function destroy($id)
    {
        Remito::destroy($id);
        return redirect()->route('remitos.index')->with('success', 'Remito eliminado.');
    }

    public function aprobar($id)
    {
        $remito = Remito::findOrFail($id);
        $remito->estado = 'aprobado';
        $remito->save();

        return back()->with('success', 'Remito aprobado.');
    }
}

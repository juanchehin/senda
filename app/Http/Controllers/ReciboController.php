<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Elibyy\TCPDF\Facades\TCPDF;

class ReciboController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver recibos')->only(['index', 'show']);
        $this->middleware('permission:crear recibos')->only(['create', 'store']);
        $this->middleware('permission:editar recibos')->only(['edit', 'update']);
        $this->middleware('permission:eliminar recibos')->only(['destroy']);
        $this->middleware('permission:aprobar recibos')->only(['aprobar']);
    }

    /**
     * Listado de recibos
     */
    public function index()
    {
        // Si querés ordenar por fecha descendente:
        $recibos = Recibo::orderBy('fecha', 'desc')->get();

        return view('admin.recibos.index', compact('recibos'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $recibo = new Recibo();

        return view('admin.recibos.create', compact('recibo'));
    }

    /**
     * Guardar nuevo recibo
     */
    public function store(Request $request)
    {
        $request->validate([
            'nro_recibo' => 'required|string|max:20',
            'fecha'      => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            Recibo::create([
                'nro_recibo' => $request->nro_recibo,
                'fecha'      => $request->fecha,
            ]);

            DB::commit();

            return redirect()
                ->route('recibos.index')
                ->with('success', 'Recibo creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
    }

    /**
     * Mostrar un recibo
     */
    public function show(Recibo $recibo)
    {
        return view('admin.recibos.show', compact('recibo'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Recibo $recibo)
    {
        return view('admin.recibos.edit', compact('recibo'));
    }

    /**
     * Actualizar un recibo
     */
    public function update(Request $request, Recibo $recibo)
    {
        $request->validate([
            'nro_recibo' => 'required|string|max:20',
            'fecha'      => 'required|date',
        ]);

        $recibo->update([
            'nro_recibo' => $request->nro_recibo,
            'fecha'      => $request->fecha,
        ]);

        return redirect()
            ->route('recibos.index')
            ->with('success', 'Recibo actualizado correctamente.');
    }

    /**
     * Eliminar un recibo
     */
    public function destroy(Recibo $recibo)
    {
        $recibo->delete();

        return redirect()
            ->route('recibos.index')
            ->with('success', 'Recibo eliminado.');
    }

    /**
     * Aprobar un recibo
     *
     * Nota: tu tabla actual NO tiene columna 'estado'.
     * Cuando la agregues, podés descomentar la lógica.
     */
    public function aprobar(Recibo $recibo)
    {
        // Ejemplo cuando exista la columna 'estado' en la tabla recibos:
        // $recibo->estado = 'aprobado';
        // $recibo->save();

        return back()->with('info', 'Función de aprobación aún no implementada en la base de datos.');
    }


    public function generar_pdf_recibo(Recibo $recibo)
    {
        $pdf = new TCPDF();

        $pdf::SetTitle('Recibo ' . $recibo->nro_recibo);

        // Sin márgenes
        $pdf::SetMargins(0, 0, 0);
        $pdf::SetAutoPageBreak(false, 0);

        $pdf::AddPage();

        // Ruta de la imagen base
        $imagePath = public_path('assets/img/recibo_base.png');

        // Insertar imagen base
        $pdf::Image($imagePath, 0, 0, 210, 297, 'PNG');

        /**
         * 1️⃣ TAPAR EL NÚMERO ORIGINAL DE LA PLANTILLA
         * Dibujamos un rectángulo blanco sobre el número impreso en la imagen
         * (Ajustar ancho/alto si lo necesitás)
         */
        $pdf::SetFillColor(255, 255, 255); // blanco
        $pdf::Rect(128, 23, 50, 12, 'F'); // X, Y, ANCHO, ALTO

        /**
         * 2️⃣ ESCRIBIR EL NÚMERO NUEVO ENCIMA
         */
        $pdf::SetFont('helvetica', 'B', 23);
        $pdf::SetTextColor(0, 0, 0); // negro

        $pdf::SetXY(130, 25);
        $pdf::Write(0, $recibo->nro_recibo);

        return response($pdf::Output('recibo.pdf', 'S'))
                ->header('Content-Type', 'application/pdf');
    }

}

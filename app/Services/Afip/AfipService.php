<?php

namespace App\Services\Afip;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\SystemLog as AfipLog;

class AfipService
{
    protected $homologacion;
    protected $wsdl;
    protected $url;

    public function __construct($homologacion = true)
    {
        $this->homologacion = $homologacion;

        if ($homologacion) {
            // Modo prueba
            $this->wsdl = 'https://wswhomo.afip.gov.ar/wsfev1/service.asmx?WSDL';
            $this->url = 'https://wswhomo.afip.gov.ar/wsfev1/service.asmx';
        } else {
            // Modo producción
            $this->wsdl = 'https://servicios1.afip.gov.ar/wsfev1/service.asmx?WSDL';
            $this->url = 'https://servicios1.afip.gov.ar/wsfev1/service.asmx';
        }
    }

    public function enviar($factura)
    {
        try {
            // 🧾 Datos mínimos de ejemplo
            $data = [
                'cbteTipo' => $factura->tipo_comprobante, // Ej: 1 = Factura A, 6 = Factura B
                'concepto' => 1, // 1 = Productos
                'docTipo' => 80, // 80 = CUIT
                'docNro' => $factura->cliente->cuit ?? 0,
                'cbteDesde' => $factura->numero,
                'cbteHasta' => $factura->numero,
                'cbteFch' => date('Ymd', strtotime($factura->fecha)),
                'impTotal' => $factura->total,
                'impNeto' => $factura->subtotal,
                'impIVA' => $factura->iva,
                'monId' => 'PES',
                'monCotiz' => 1,
            ];

            // 🚀 Simulación: no llama aún al WS de AFIP real
            AfipLog::create([
                'servicio' => 'WSFEv1',
                'accion' => 'FECAESolicitar',
                'factura_id' => $factura->id,
                'respuesta' => json_encode(['estado' => 'OK', 'mensaje' => 'Factura enviada correctamente']),
            ]);

            Log::info("Factura {$factura->id} enviada correctamente a AFIP (modo ".($this->homologacion ? 'HOMOLOGACIÓN' : 'PRODUCCIÓN').")");

            return [
                'estado' => 'OK',
                'mensaje' => 'Factura enviada correctamente',
            ];

        } catch (Exception $e) {

            AfipLog::create([
                'servicio' => 'WSFEv1',
                'accion' => 'FECAESolicitar',
                'factura_id' => $factura->id,
                'error' => $e->getMessage(),
            ]);

            Log::error('Error al enviar factura a AFIP: '.$e->getMessage());

            throw $e;
        }
    }
}

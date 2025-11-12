<?php

namespace App\Services\Afip;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class AfipService
{
    protected $cuit;
    protected $cert;
    protected $key;
    protected $taPath;
    protected $wsaaWsdl;
    protected $wsfeWsdl;

    public function __construct()
    {
        $this->cuit = env('AFIP_CUIT');
        $this->cert = base_path(env('AFIP_CERT_PATH'));
        $this->key = base_path(env('AFIP_KEY_PATH'));
        $this->taPath = storage_path('afip/homologacion/TA.xml'); // TA para homologación
        $this->wsaaWsdl = env('AFIP_WSDL_WSAA'); // WSAA homologación
        $this->wsfeWsdl = env('AFIP_WSDL_WSFE'); // WSFE homologación
    }

    /**
     * Genera el Token de Acceso (TA) para homologación.
     */
    public function obtenerToken()
    {
        Log::info("➡ Generando TRA para AFIP (homologación)");

        // Crear TRA
        $tra = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><loginTicketRequest version="1.0"></loginTicketRequest>');
        $header = $tra->addChild('header');
        $header->addChild('uniqueId', time());
        $header->addChild('generationTime', gmdate('Y-m-d\TH:i:s', time() - 60*10));
        $header->addChild('expirationTime', gmdate('Y-m-d\TH:i:s', time() + 60*10));
        $tra->addChild('service', 'wsfe');

        $traFile = storage_path('afip/homologacion/TRA.xml');
        $tra->asXML($traFile);

        // Firmar TRA con OpenSSL y generar Base64
        $signedFile = storage_path('afip/homologacion/TRA_signed.tmp');
        exec("openssl smime -sign -signer {$this->cert} -inkey {$this->key} -outform DER -nodetach -in {$traFile} -out {$signedFile} 2>&1", $output, $result);

        if ($result !== 0) {
            $err = implode("\n", $output);
            Log::error("❌ Error al firmar TRA: {$err}");
            throw new \Exception("Error al firmar TRA: {$err}");
        }

        $cms = base64_encode(file_get_contents($signedFile));

        // Llamada a WSAA
        $client = new \SoapClient($this->wsaaWsdl, ['soap_version' => SOAP_1_2, 'trace' => 1]);
        $loginCmsResponse = $client->loginCms(['in0' => $cms]);
        $taXml = $loginCmsResponse->loginCmsReturn;

        // Guardar TA
        file_put_contents($this->taPath, $taXml);
        Log::info("✅ TA generado correctamente y guardado en {$this->taPath}");

        return simplexml_load_string($taXml);
    }

    /**
     * Envia la factura al WSFE homologación.
     */
    public function enviarFactura($factura)
    {
        Log::info("➡ Enviando factura ID {$factura->id} a AFIP (homologación)");

        if (!file_exists($this->taPath)) {
            $this->obtenerToken();
        }

        $ta = simplexml_load_file($this->taPath);
        $token = (string)$ta->credentials->token;
        $sign = (string)$ta->credentials->sign;

        $client = new \SoapClient($this->wsfeWsdl, ['trace' => 1, 'exceptions' => 1]);

        $auth = [
            'Token' => $token,
            'Sign'  => $sign,
            'Cuit'  => $this->cuit,
        ];

        // Datos simplificados para prueba homologación ($1)
        $data = [
            'FeCAEReq' => [
                'FeCabReq' => [
                    'CantReg' => 1,
                    'PtoVta' => $factura->punto_venta,
                    'CbteTipo' => $factura->tipo_comprobante === 'A' ? 1 : 6,
                ],
                'FeDetReq' => [
                    'FECAEDetRequest' => [
                        'Concepto' => 1,
                        'DocTipo' => 80,
                        'DocNro'  => $factura->cliente->cuit ?? 0,
                        'CbteDesde' => $factura->numero,
                        'CbteHasta' => $factura->numero,
                        'CbteFch' => date('Ymd', strtotime($factura->fecha_emision)),
                        'ImpTotal' => $factura->importe_total,
                        'ImpTotConc' => 0,
                        'ImpNeto' => $factura->importe_total / 1.21,
                        'ImpIVA'  => $factura->importe_total - ($factura->importe_total / 1.21),
                        'ImpTrib' => 0,
                        'MonId' => 'PES',
                        'MonCotiz' => 1,
                        'Iva' => [
                            'AlicIva' => [
                                'Id' => 5,
                                'BaseImp' => $factura->importe_total / 1.21,
                                'Importe' => $factura->importe_total - ($factura->importe_total / 1.21),
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $res = $client->FECAESolicitar(['Auth' => $auth] + $data);

            Log::info("✅ Factura enviada correctamente", [
                'request'  => $client->__getLastRequest(),
                'response' => $client->__getLastResponse()
            ]);

            return $res;
        } catch (\Exception $e) {
            Log::error("❌ Error al enviar factura a AFIP: {$e->getMessage()}", [
                'request'  => $client->__getLastRequest(),
                'response' => $client->__getLastResponse()
            ]);
            throw $e;
        }
    }
}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .contenedor {
            padding: 20px;
        }

        .encabezado {
            text-align: center;
            margin-bottom: 20px;
        }

        .datos-factura, .datos-cliente {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 6px;
            text-align: left;
        }

        .total {
            text-align: right;
            margin-top: 10px;
            font-weight: bold;
        }

        .qr {
            margin-top: 20px;
            text-align: right;
        }

        .info-cae {
            font-size: 10px;
            margin-top: 10px;
        }

    </style>
</head>
<body>
    <div class="contenedor">
        <div class="encabezado">
            <h2>FACTURA {{ $factura->tipo_comprobante }}</h2>
            <p>Punto de Venta: {{ $factura->punto_venta }} - Comprobante N°: {{ $factura->numero_comprobante }}</p>
            <p>Fecha de Recibo: {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}</p>
            <p><strong>Estado:</strong> {{ $factura->estado ?? '-' }}</p>

        </div>

        <div class="datos-cliente">
            <strong>Cliente:</strong> {{ $factura->cliente->razon_social }}<br>
            <strong>CUIT:</strong> {{ $factura->cliente->cuit }}<br>
            <strong>Domicilio:</strong> {{ $factura->cliente->direccion }}
        </div>

        {{-- =============================
            DATOS DE SERVICIO (si aplica)
        ============================= --}}
        @if($factura->concepto == 2 || $factura->concepto == 3)
            <div class="datos-factura" style="margin-top: 10px; margin-bottom: 15px;">
                <strong>Período del Servicio:</strong><br>

                <strong>Desde:</strong>
                {{ $factura->fecha_desde ? \Carbon\Carbon::parse($factura->fecha_desde)->format('d/m/Y') : '-' }}
                &nbsp;&nbsp;

                <strong>Hasta:</strong>
                {{ $factura->fecha_hasta ? \Carbon\Carbon::parse($factura->fecha_hasta)->format('d/m/Y') : '-' }}
                <br>

                <strong>Vencimiento de Pago:</strong>
                {{ $factura->vencimiento_pago ? \Carbon\Carbon::parse($factura->vencimiento_pago)->format('d/m/Y') : '-' }}
            </div>
        @endif


        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Codigo</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $unidadTexto = [
                        ""   => "—",
                        "1"  => "kilogramos",
                        "2"  => "metros",
                        "3"  => "metros cuadrados",
                        "4"  => "metros cúbicos",
                        "5"  => "litros",
                        "6"  => "1000 kWh",
                        "7"  => "unidades",
                        "8"  => "pares",
                        "9"  => "docenas",
                        "10" => "quilates",
                        "11" => "millares",
                        "14" => "gramos",
                        "15" => "milímetros",
                        "16" => "mm cúbicos",
                        "17" => "kilómetros",
                        "18" => "hectolitros",
                        "20" => "centímetros",
                        "25" => "jgo. pqt. mazo naipes",
                        "27" => "cm cúbicos",
                        "29" => "toneladas",
                        "30" => "dam cúbicos",
                        "31" => "hm cúbicos",
                        "32" => "km cúbicos",
                        "33" => "microgramos",
                        "34" => "nanogramos",
                        "35" => "picogramos",
                        "41" => "miligramos",
                        "47" => "mililitros",
                        "48" => "curie",
                        "49" => "milicurie",
                        "50" => "microcurie",
                        "51" => "uiacthor",
                        "52" => "muiacthor",
                        "53" => "kg base",
                        "54" => "gruesa",
                        "61" => "kg bruto",
                        "62" => "uiactant",
                        "63" => "muiactant",
                        "64" => "uiactig",
                        "65" => "muiactig",
                        "66" => "kg activo",
                        "67" => "gramo activo",
                        "68" => "gramo base",
                        "96" => "packs",
                        "98" => "otras unidades",
                    ];
                    @endphp

                @foreach($factura->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->codigo }}</td>
                        <td>{{ $item->descripcion }}</td>
                        <td>{{ $unidadTexto[$item->unidad] ?? $item->unidad }}</td>
                        <td>{{ $item->cantidad }}</td>
                        <td>${{ number_format($item->precio_unitario, 2, ',', '.') }}</td>
                        <td>${{ number_format($item->cantidad * $item->precio_unitario, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- =============================
            REMITOS ASOCIADOS (si existen)
        ============================= --}}
        @if(isset($factura->remitos) && $factura->remitos->count() > 0)
            <h4 style="margin-top: 25px;">Remitos Asociados</h4>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pto. Venta</th>
                        <th>Comprobante</th>
                        <th>Fecha de Recibo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($factura->remitos as $i => $remito)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ str_pad($remito->pto_venta, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ str_pad($remito->comprobante, 8, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ \Carbon\Carbon::parse($remito->fecha_emision)->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif


        <p class="total">Importe Total: ${{ number_format($factura->importe_total, 2, ',', '.') }}</p>

        <p class="total" style="font-size: 12px;">
            Moneda: <strong>{{ $factura->moneda }}</strong>
        </p>

        <p class="total" style="font-size: 11px; font-weight: normal;">
            Tipo de Cambio Aplicado:
            <strong>${{ number_format($factura->valor_dolar, 2, ',', '.') }}</strong>
        </p>

        <div class="info-cae">
            <p><strong>CAE:</strong> {{ $factura->cae }}</p>
            <p><strong>Vto. CAE:</strong>
                {{ $factura->vto_cae ? \Carbon\Carbon::parse($factura->vto_cae)->format('d/m/Y') : '-' }}
            </p>
        </div>

        <div class="qr">
            <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($urlQr) }}&size=120x120" alt="QR AFIP">
        </div>
    </div>
</body>
</html>

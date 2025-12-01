<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Remito</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; }
    </style>
</head>
<body>

<h2>REMITO</h2>

<p><strong>Cliente:</strong> {{ $remito->cliente->razon_social }}</p>
<p><strong>Fecha:</strong> {{ $remito->fecha }}</p>
<p><strong>Estado:</strong> {{ $remito->estado }}</p>

<table>
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Cant.</th>
        </tr
    </thead>
    <tbody>
        @foreach($remito->items as $item)
        <tr>
            <td>{{ $item->descripcion }}</td>
            <td>{{ $item->cantidad }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

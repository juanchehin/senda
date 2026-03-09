@extends('adminlte::page')

@section('title', 'Editar Orden de compra')

@section('content_header')
<h2>Editar Orden de compra #{{ $orden->numero_oc }}</h2>
@stop

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<form action="{{ route('ordenes.update',$orden->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')


<div class="card">
<div class="card-body">

<div class="row">

<div class="col-md-3">
<label>Número de OC</label>
<input type="number" name="numero_oc" class="form-control"
value="{{ old('numero_oc',$orden->numero_oc) }}" required>
</div>


<div class="col-md-3">
<label>Fecha</label>
<input type="date"
name="fecha"
class="form-control"
value="{{ old('fecha',$orden->fecha) }}"
required>
</div>


<div class="col-md-3">
<label>Motivo</label>
<select name="motivo" class="form-control" required>

<option value="">Seleccionar</option>

<option value="cotizacion"
{{ old('motivo',$orden->motivo)=='cotizacion'?'selected':'' }}>
Cotización
</option>

<option value="stock"
{{ old('motivo',$orden->motivo)=='stock'?'selected':'' }}>
Stock
</option>

</select>
</div>


<div class="col-md-3">
<label>Condición compra</label>
<input type="text"
name="condicion_compra"
class="form-control"
value="{{ old('condicion_compra',$orden->condicion_compra) }}">
</div>


</div>


<hr>


<div class="row">


<div class="col-md-6">

<label>Razón Social</label>

<div class="position-relative">

<input type="text"
name="razon_social"
id="razon_social"
class="form-control"
autocomplete="off"
value="{{ old('razon_social', $orden->cliente->razon_social ?? '') }}"
required>

<input type="hidden"
name="id_cliente"
id="id_cliente"
value="{{ old('id_cliente',$orden->id_cliente) }}">

<div id="dropdown-clientes"
class="list-group position-absolute w-100 shadow"
style="z-index:9999; max-height:240px; overflow-y:auto; display:none;">
</div>

</div>

</div>


<div class="col-md-3">
<label>CUIT</label>

<input type="text"
id="cuit"
name="cuit"
class="form-control"
value="{{ old('cuit',$orden->cuit) }}">
</div>


<div class="col-md-3">
<label>Teléfono</label>

<input type="text"
id="telefono"
name="telefono"
class="form-control"
value="{{ old('telefono',$orden->telefono) }}">
</div>


</div>



<div class="row mt-3">

<div class="col-md-4">

<label>Dirección</label>

<input type="text"
id="direccion"
name="direccion"
class="form-control"
value="{{ old('direccion',$orden->direccion) }}">

</div>


<div class="col-md-4">

<label>Email</label>

<input type="email"
id="email"
name="email"
class="form-control"
value="{{ old('email',$orden->email) }}">

</div>


<div class="col-md-4">

<label>Archivo</label>

<input type="file"
name="archivo"
class="form-control">

@if($orden->archivo)

<small class="d-block mt-1">
<a href="{{ asset('storage/'.$orden->archivo) }}" target="_blank">
Ver archivo actual
</a>
</small>

@endif

</div>

</div>



<hr>


<h4>Ítems</h4>

<table class="table table-bordered">

<thead>
<tr>
<th>Código</th>
<th>Descripción</th>
<th>Cantidad</th>
<th>Unidad</th>
<th>Precio</th>
<th>IVA</th>
<th>Desc</th>
<th>Total</th>
<th></th>
</tr>
</thead>


<tbody id="items-table">

@php
$oldItems = old('items');
$items = $oldItems ?? $orden->items;
@endphp


@foreach($items as $i => $item)

<tr>

<td>
<input type="text"
name="items[{{$i}}][codigo]"
class="form-control"
value="{{ $item['codigo'] ?? $item->codigo }}">
</td>


<td>
<input type="text"
name="items[{{$i}}][descripcion]"
class="form-control"
value="{{ $item['descripcion'] ?? $item->descripcion }}">
</td>


<td>
<input type="number"
step="0.01"
name="items[{{$i}}][cantidad]"
class="form-control"
value="{{ $item['cantidad'] ?? $item->cantidad }}">
</td>


<td>

<select name="items[{{$i}}][unidad]" class="form-control">

<option value="7"
{{ ($item['unidad'] ?? $item->unidad)==7?'selected':'' }}>
unidades
</option>

<option value="1"
{{ ($item['unidad'] ?? $item->unidad)==1?'selected':'' }}>
kilogramos
</option>

<option value="2"
{{ ($item['unidad'] ?? $item->unidad)==2?'selected':'' }}>
metros
</option>

<option value="96"
{{ ($item['unidad'] ?? $item->unidad)==96?'selected':'' }}>
packs
</option>

</select>

</td>


<td>
<input type="number"
step="0.01"
name="items[{{$i}}][precio_unitario]"
class="form-control"
value="{{ $item['precio_unitario'] ?? $item->precio_unitario }}">
</td>


<td>
<input type="number"
step="0.01"
name="items[{{$i}}][iva]"
class="form-control"
value="{{ $item['iva'] ?? $item->iva }}">
</td>


<td>
<input type="number"
step="0.01"
name="items[{{$i}}][descuento]"
class="form-control"
value="{{ $item['descuento'] ?? $item->descuento }}">
</td>


<td>
<input type="number"
step="0.01"
name="items[{{$i}}][total]"
class="form-control"
value="{{ $item['total'] ?? $item->total }}"
readonly>
</td>


<td>
<button type="button"
class="btn btn-danger btn-sm remove-row">
X
</button>
</td>


</tr>

@endforeach

</tbody>

</table>


<button type="button" id="add-row" class="btn btn-primary btn-sm">
Agregar Ítem
</button>


<hr>


<div class="row">

<div class="col-md-4">
<label>Subtotal</label>
<input type="number" step="0.01"
name="subtotal"
class="form-control"
value="{{ old('subtotal',$orden->subtotal) }}" readonly>
</div>


<div class="col-md-4">
<label>Descuentos</label>
<input type="number" step="0.01"
name="descuentos_totales"
class="form-control"
value="{{ old('descuentos_totales',$orden->descuentos_totales) }}" readonly>
</div>


<div class="col-md-4">
<label>Total</label>
<input type="number" step="0.01"
name="total"
class="form-control"
value="{{ old('total',$orden->total) }}" readonly>
</div>

</div>


</div>


<div class="card-footer">

<a href="{{ route('ordenes.index') }}" class="btn btn-secondary">
Volver
</a>

<button class="btn btn-success">
Actualizar Orden
</button>

</div>

</div>

</form>

@stop

@extends('adminlte::page')

@section('title', 'Nuevo Pedido de Cotización')

@section('content_header')
    <h2>Nuevo Pedido de Cotización</h2>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">

        <form method="POST"
              action="{{ route('pedidos-cotizacion.store') }}"
              enctype="multipart/form-data">

            @csrf

            <div class="row">

                {{-- Cotización --}}
                <div class="col-md-6">
                    <label>Cotización</label>
                    <select name="id_cotizacion" class="form-control" required>
                        <option value="">Seleccione cotización</option>
                        @foreach($cotizaciones as $cotizacion)
                            <option value="{{ $cotizacion->id_cotizacion }}">
                                #{{ $cotizacion->id_cotizacion }}
                                - {{ $cotizacion->cliente->razon_social ?? '' }}
                                ({{ optional($cotizacion->fecha_cot)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Archivo --}}
                <div class="col-md-6">
                    <label>Archivo Adjunto (PDF / Imagen)</label>
                    <input type="file"
                           name="archivo"
                           class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png">
                </div>

            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <label>Observaciones</label>
                    <textarea name="observaciones"
                              class="form-control"
                              rows="4"
                              placeholder="Ingrese observaciones del pedido...">{{ old('observaciones') }}</textarea>
                </div>
            </div>

            <div class="mt-4 text-right">
                <button type="submit" class="btn btn-success">
                    Guardar Pedido
                </button>

                <a href="{{ route('pedidos-cotizacion.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>
            </div>

        </form>

    </div>
</div>

@stop

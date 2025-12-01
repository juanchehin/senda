@extends('adminlte::page')

@section('title', 'Nuevo Remito')

@section('content_header')
    <h1>Nuevo Remito</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('remitos.store') }}" method="POST">
            @csrf
            @include('admin.remitos.partials.form')
            <button type="submit" class="btn btn-success mt-3">Guardar Remito</button>
            <a href="{{ route('remitos.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
        </form>

    </div>
</div>

@stop

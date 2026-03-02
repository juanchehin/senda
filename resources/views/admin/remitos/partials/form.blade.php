@csrf

<div class="row">

    {{-- Número de Remito --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Número de Remito</label>
        <input type="text"
               name="numero_remito"
               class="form-control"
               value="{{ old('numero_remito', $remito->numero_remito ?? '') }}"
               required>
    </div>

    {{-- Cliente --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Cliente</label>
        <select name="id_cliente" class="form-control" required>
            <option value="">Seleccione cliente</option>
            @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}"
                    {{ old('id_cliente', $remito->id_cliente ?? '') == $cliente->id_cliente ? 'selected' : '' }}>
                    {{ $cliente->razon_social }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Fecha --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Fecha</label>
        <input type="date"
               name="fecha"
               class="form-control"
               value="{{ old('fecha', isset($remito->fecha) ? $remito->fecha->format('Y-m-d') : '') }}"
               required>
    </div>

    {{-- OC Asociada --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">OC Asociada</label>
        <input type="text"
               name="id_orden_compra"
               class="form-control"
               value="{{ old('id_orden_compra', $remito->id_orden_compra ?? '') }}">
    </div>

    {{-- Factura Relacionada --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Factura Relacionada</label>
        <input type="text"
               name="id_factura"
               class="form-control"
               value="{{ old('id_factura', $remito->id_factura ?? '') }}">
    </div>

    {{-- Estado --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-control" required>
            <option value="Emitido"
                {{ old('estado', $remito->estado ?? '') == 'Emitido' ? 'selected' : '' }}>
                Emitido
            </option>
            <option value="Confirmado"
                {{ old('estado', $remito->estado ?? '') == 'Confirmado' ? 'selected' : '' }}>
                Confirmado
            </option>
            <option value="Anulado"
                {{ old('estado', $remito->estado ?? '') == 'Anulado' ? 'selected' : '' }}>
                Anulado
            </option>
        </select>
    </div>

</div>

<div class="mt-3">
    <button type="submit" class="btn btn-primary">
        Guardar
    </button>
    <a href="{{ route('remitos.index') }}" class="btn btn-secondary">
        Cancelar
    </a>
</div>

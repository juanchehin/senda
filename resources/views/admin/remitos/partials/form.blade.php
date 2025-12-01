{{-- FORMULARIO DE REMITO --}}
<div class="row">

    {{-- Cliente --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="cliente_id">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-control" required>
                <option value="">Seleccione...</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}"
                        {{ old('cliente_id', $remito->cliente_id ?? '') == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->razon_social }} - {{ $cliente->cuit }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Fecha --}}
    <div class="col-md-3">
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control"
                   value="{{ old('fecha', $remito->fecha ?? date('Y-m-d')) }}" required>
        </div>
    </div>

</div>

{{-- ÍTEMS --}}
<h4 class="mt-4">Ítems del Remito</h4>

<table class="table table-bordered" id="tabla-items">
    <thead>
        <tr>
            <th>Descripción</th>
            <th style="width: 100px;">Cant.</th>
            <th style="width: 60px;"></th>
        </tr>
    </thead>

    <tbody>
        @php $i = 0; @endphp

        @if(isset($remito))
            @foreach($remito->items as $item)
                <tr>
                    <td>
                        <input type="text" name="items[{{ $i }}][descripcion]" class="form-control"
                               value="{{ $item->descripcion }}" required>
                    </td>
                    <td>
                        <input type="number" name="items[{{ $i }}][cantidad]" class="form-control" min="1"
                               value="{{ $item->cantidad }}" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm eliminar-item">&times;</button>
                    </td>
                </tr>
                @php $i++; @endphp
            @endforeach
        @else
            <tr>
                <td><input type="text" name="items[0][descripcion]" class="form-control" required></td>
                <td><input type="number" name="items[0][cantidad]" class="form-control" min="1" required></td>
                <td><button type="button" class="btn btn-danger btn-sm eliminar-item">&times;</button></td>
            </tr>
        @endif
    </tbody>
</table>

<button type="button" class="btn btn-primary btn-sm" id="agregar-item">Agregar Ítem</button>

<script>
let fila = {{ $i ?? 1 }};

document.getElementById('agregar-item').addEventListener('click', function() {
    let tabla = document.querySelector('#tabla-items tbody');

    let nuevaFila = `
        <tr>
            <td><input type="text" name="items[${fila}][descripcion]" class="form-control" required></td>
            <td><input type="number" name="items[${fila}][cantidad]" class="form-control" min="1" required></td>
            <td><button type="button" class="btn btn-danger btn-sm eliminar-item">&times;</button></td>
        </tr>
    `;

    tabla.insertAdjacentHTML('beforeend', nuevaFila);
    fila++;
});

document.addEventListener('click', function(e) {
    if (e.target.matches('.eliminar-item')) {
        e.target.closest('tr').remove();
    }
});
</script>

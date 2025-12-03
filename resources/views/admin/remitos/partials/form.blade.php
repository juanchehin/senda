{{-- FORMULARIO DE REMITO --}}

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- FORMULARIO DE REMITO --}}
<div class="row">

    {{-- Fecha --}}
    <div class="col-md-3">
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control"
                   value="{{ old('fecha', $remito->fecha ?? date('Y-m-d')) }}" required>
        </div>
    </div>

    {{-- Razón Social --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="razon_social">Razón Social</label>
            <input type="text" name="razon_social" id="razon_social" class="form-control"
                   value="{{ old('razon_social', $remito->razon_social ?? '') }}" required>
        </div>
    </div>

    {{-- CUIT --}}
    <div class="col-md-3">
        <div class="form-group">
            <label for="cuit">CUIT</label>
            <input type="text" name="cuit" id="cuit" maxlength="11" pattern="\d{11}"
                   class="form-control" value="{{ old('cuit', $remito->cuit ?? '') }}" required>
        </div>
    </div>

</div>

<div class="row">

    {{-- Domicilio --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="domicilio">Domicilio</label>
            <input type="text" name="domicilio" id="domicilio" class="form-control"
                   value="{{ old('domicilio', $remito->domicilio ?? '') }}" required>
        </div>
    </div>

    {{-- Localidad --}}
    <div class="col-md-3">
        <div class="form-group">
            <label for="localidad">Localidad</label>
            <input type="text" name="localidad" id="localidad" class="form-control"
                   value="{{ old('localidad', $remito->localidad ?? '') }}" required>
        </div>
    </div>

    {{-- Orden de Compra --}}
    <div class="col-md-3">
        <div class="form-group">
            <label for="orden_compra">Orden de Compra</label>
            <input type="text" name="orden_compra" id="orden_compra" class="form-control"
                   value="{{ old('orden_compra', $remito->orden_compra ?? '') }}">
        </div>
    </div>

</div>

{{-- ÍTEMS --}}
<h4 class="mt-4">Ítems del Remito</h4>

<table class="table table-bordered" id="tabla-items">
    <thead>
        <tr>
            <th style="width: 100px;">Artículo</th>
            <th>Descripción</th>
            <th style="width: 100px;">Cantidad</th>
            <th style="width: 60px;"></th>
        </tr>
    </thead>

    <tbody>
        @php $i = 0; @endphp

        @if(isset($remito))
            @foreach($remito->items as $item)
                <tr>
                    <td>
                        <input type="text" name="items[{{ $i }}][articulo]"
                               class="form-control"
                               value="{{ $item->articulo }}" required>
                    </td>
                    <td>
                        <input type="text" name="items[{{ $i }}][descripcion]"
                               class="form-control"
                               value="{{ $item->descripcion }}" required>
                    </td>
                    <td>
                        <input type="number" name="items[{{ $i }}][cantidad]"
                               class="form-control" value="{{ $item->cantidad }}" min="1" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm eliminar-item">&times;</button>
                    </td>
                </tr>
                @php $i++; @endphp
            @endforeach
        @else
            <tr>
                <td>
                    <input type="text" name="items[0][articulo]" class="form-control campo-articulo" required>
                </td>
                <td>
                    <input type="text" name="items[0][descripcion]" class="form-control" required>
                </td>
                <td>
                    <input type="number" name="items[0][cantidad]" class="form-control" min="1" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm eliminar-item">&times;</button>
                </td>
            </tr>

        @endif
    </tbody>
</table>

<button type="button" class="btn btn-primary btn-sm" id="agregar-item">Agregar Ítem</button>

<script>

function renumerarItems() {
    let filas = document.querySelectorAll('#tabla-items tbody tr');

    filas.forEach((tr, index) => {
        let numero = index + 1;

        // Artículo = 1.1, 1.2, 1.3 ...
        tr.querySelector('.campo-articulo').value = "1." + numero;

        // Reordenar names correctamente
        tr.querySelectorAll('input').forEach(input => {
            let name = input.getAttribute('name');
            let nuevoNombre = name.replace(/items\[\d+\]/, `items[${index}]`);
            input.setAttribute('name', nuevoNombre);
        });
    });
}

// AGREGAR ITEM
document.getElementById('agregar-item').addEventListener('click', function () {

    let nuevaFila = `
        <tr>
            <td>
                <input type="text" name="items[][articulo]" class="form-control campo-articulo" required>
            </td>
            <td>
                <input type="text" name="items[][descripcion]" class="form-control" required>
            </td>
            <td>
                <input type="number" name="items[][cantidad]" class="form-control" min="1" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm eliminar-item">&times;</button>
            </td>
        </tr>
    `;

    document.querySelector('#tabla-items tbody').insertAdjacentHTML('beforeend', nuevaFila);
    renumerarItems();
});

// ELIMINAR ITEM
document.addEventListener('click', function(e) {
    if (e.target.matches('.eliminar-item')) {
        e.target.closest('tr').remove();
        renumerarItems();
    }
});

// NUMERAR AL CARGAR
renumerarItems();

</script>


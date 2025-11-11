<?xml version="1.0" encoding="UTF-8"?>
<FeCAEReq>
  <FeCabReq>
    <CantReg>1</CantReg>
    <PtoVta>{{ $nota->punto_venta }}</PtoVta>
    <CbteTipo>7</CbteTipo> <!-- Nota de Débito B -->
  </FeCabReq>
  <FeDetReq>
    <FECAEDetRequest>
      <Concepto>{{ $nota->concepto }}</Concepto>
      <DocTipo>99</DocTipo>
      <DocNro>0</DocNro>
      <CbteDesde>{{ $nota->numero }}</CbteDesde>
      <CbteHasta>{{ $nota->numero }}</CbteHasta>
      <CbteFch>{{ \Carbon\Carbon::parse($nota->fecha_emision)->format('Ymd') }}</CbteFch>
      <ImpTotal>{{ number_format($nota->total, 2, '.', '') }}</ImpTotal>
      <ImpNeto>{{ number_format($nota->neto, 2, '.', '') }}</ImpNeto>
      <ImpIVA>{{ number_format($nota->iva, 2, '.', '') }}</ImpIVA>
      <MonId>PES</MonId>
      <MonCotiz>1.00</MonCotiz>

      <Iva>
        @foreach($nota->ivas as $iva)
          <AlicIva>
            <Id>{{ $iva['codigo'] }}</Id>
            <BaseImp>{{ number_format($iva['base'], 2, '.', '') }}</BaseImp>
            <Importe>{{ number_format($iva['importe'], 2, '.', '') }}</Importe>
          </AlicIva>
        @endforeach
      </Iva>
    </FECAEDetRequest>
  </FeDetReq>
</FeCAEReq>

<?xml version="1.0" encoding="UTF-8"?>
<FeCAEReq>
  <FeCabReq>
    <CantReg>1</CantReg>
    <PtoVta>{{ $factura->punto_venta }}</PtoVta>
    <CbteTipo>1</CbteTipo> <!-- Factura A -->
  </FeCabReq>
  <FeDetReq>
    <FECAEDetRequest>
      <Concepto>{{ $factura->concepto }}</Concepto>
      <DocTipo>80</DocTipo> <!-- 80 = CUIT -->
      <DocNro>{{ $factura->cliente->cuit }}</DocNro>
      <CbteDesde>{{ $factura->numero }}</CbteDesde>
      <CbteHasta>{{ $factura->numero }}</CbteHasta>
      <CbteFch>{{ \Carbon\Carbon::parse($factura->fecha_emision)->format('Ymd') }}</CbteFch>
      <ImpTotal>{{ number_format($factura->total, 2, '.', '') }}</ImpTotal>
      <ImpTotConc>0.00</ImpTotConc>
      <ImpNeto>{{ number_format($factura->neto, 2, '.', '') }}</ImpNeto>
      <ImpOpEx>0.00</ImpOpEx>
      <ImpIVA>{{ number_format($factura->iva, 2, '.', '') }}</ImpIVA>
      <ImpTrib>0.00</ImpTrib>
      <MonId>PES</MonId>
      <MonCotiz>1.00</MonCotiz>

      <Iva>
        @foreach($factura->ivas as $iva)
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

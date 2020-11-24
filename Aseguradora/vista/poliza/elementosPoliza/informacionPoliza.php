<div class="row">

  <div class="infoGeneral">
    <div class="datosPoliza">
      <span class="titulo">Poliza</span>
      <div class="infPol"> </div>
      
      <div class="infAdPol">
        <span class="subtitulo">Informacion</span>
        <div class="infEndSin"></div>
      
        <span class="subtitulo">Cobertura</span>
        <div class="infCo"> </div>

        <span class="subtitulo">Adicional</span>
        <div class="infCoA"> </div>
      </div>
    </div>

    <div class="datosTomador">
      <span class="titulo">Tomador</span>
      <div class="infTom"></div>
    </div>

    <div class="datosPago">
      <span class="titulo">Pagos</span>
      <div class="infPag"></div> 
      <div class="infCuo">
        <span class="subtitulo">Cuotas</span>
        <table>
          <thead>
            <tr>
              <th>Numero</th>
              <th>Identificador</th>
              <th>Prima</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody id="tbodyCuo"></tbody>
        </table>
      </div>
    </div>

    <div class="datosEstados">
      <span class="titulo">Estados</span>
      <p><span class="subtitulo">Actual</span></p>
      <p class="estAct"></p>
      <p><span class="subtitulo">Anteriores</span></p>
      <div class="estAnt"></div>
    </div>

    <div class="datosVehiculo">
      <span class="titulo">Vehiculo / Riesgo</span>
      <div class="infVe">
        <div class="Iz"></div>
        <div class="De"></div>
      </div>
    </div>
  </div>

</div>

<div class="row botonesVerPol">
  <button type="button" class="btn btn-flat blue btnVolverConPol"> <i class="fas fa-caret-left"></i> volver </button>
  <button type="button" class="btn btn-flat blue btnActConPol"> <i class="fas fa-sync-alt"></i> actualizar </button>
</div>

<!-- Tabla donde muestro la lista de polizas a a establecer metodos de pago -->
<div class="row" id="consultarPolRow">
  <div class="col s12">
    <table id="condicionesCuotasTabla" class="centered striped">
      <thead>
        <tr>
          <th scope="col">Poliza Nro</th>
          <th scope="col">Tomador</th>
          <th scope="col">Vehiculo</th>
          <th scope="col">Inicio Vig</th>
          <th scope="col">Fin Vig</th>
          <th scope="col">Registro</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- Modal emision condiciones de pago -->
<div id="condicionesCuotasModal" class="modal modal-fixed-footer">
   <form id="cuotasForm" class="formulario">
      <div class="modal-content">

          <div id="cuerpoModal">
            <?php include_once 'nuevaCuota.php'; ?>
          </div>

          <ul id="mensajePago" class="mensaje mensajeModal padding"></ul>
      </div>

      <div class="modal-footer">
          <button type="button" class="btn btn-flat blue modal-close" id="cancelarCondPago"> <i class="fas fa-times"></i> cerrar </button>
          <button type="submit" class="btn btn-flat green" id="grabarCondPago"> <i class="fas fa-save"></i> <span class="nombreBoton"> </span> </button>
      </div>

    </form>
</div>


<!-- Modal mostrar cuotas -->
<div id="mostrarCuotasModal" class="modal modal-fixed-footer">
    <div class="modal-content">
      <div class="tituloFormulario">Listado de cuotas</div>
      <div class="subtituloFormulario">Poliza: <span class="numeroPoliza"></span></div>

        <table class="tablaListadoCuotas centered">
          <thead>
              <tr>
                  <th>Cuota N°</th>
                  <th>Iden Cuota</th>
                  <th>Prima</th>
                  <th>Lapso</th>
                  <th>Factura</th>
                  <th>Vto Pago</th>
                  <th>Estado</th>
              </tr>
          </thead>
          <tbody id="tbodyTabla"></tbody>
        </table>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-flat blue modal-close" id="cancelarCondPago"> <i class="fas fa-times"></i> cerrar </button>
    </div>
</div>
<!-- Tabla donde muestro la lista de pagos -->
<div class="row" id="consultarPolRow">
  <div class="col s12">
    <table id="verPagoTabla" class="centered striped">
      <thead>
        <tr>
          <th scope="col">Numero</th>
          <th scope="col">Poliza Num</th>
          <th scope="col">Tomador</th>
          <th scope="col">N° Cuotas</th>
          <th scope="col">Estado</th>
          <th scope="col">Detalles</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- Modal para ver detalles de las cuotas y para realizar las facturas -->
<div id="detallesCuotasModal" class="modal modal-fixed-footer">
   <div class="modal-content">
      <div id="cuerpoModal">

        <div class="mostrarTabla">
          <div class="tituloFormulario">Consultar Facturas de Pago</div>
          <div class="textoInformacionCabecera">
            Poliza <span id="numPol"></span> - <span id="tomador"></span>
            <span class="divPrTo">Prima Total <span id="primaTotal"></span></span>
          </div>

          <table class="tablaListadoCuotas centered" id="tablaListadoCuotas">
            <thead>
              <tr>
                <th>Iden cuota</th>
                <th>N° cuota</th>
                <th>Prima</th>
                <th>Lapso</th>
                <th>Factura</th>
                <th>Vto Pago</th>
                <th>Estado</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="detallesCuotasTbody"></tbody>
          </table>

        </div>

        <div class="mostrarRecibo">
          <div class="tituloFormulario">Recibos - Emitir factura #<span class="nroCuota">.................</span> </div>
   
            <div class="contRecibo"> 
              <?php include 'reciboPago.php'?>
            </div>

            <div class="btns-estados">
              <div class="efectuar">
                <a type="button" class="btn-flat efectuarPago" id="efectuarPago">Cobrar</a>
                <span> <i class="fas fa-long-arrow-alt-right"></i> <span class="aCobrar"></span> </span>
              </div>
              <div class="efectuado">
                <i class="fas fa-check-circle icCheckPago"></i><div class="conInfAcc">Pago efectuado <span class="fechaEmisRec"></span> </div>
              </div>
            </div>
        </div>

      </div>
    </div>
    <div class="modal-footer">
      
        <a class="btn-flat envRecibo"><i class="fas fa-share"></i> enviar</a>
        <a class="btn-flat impRecibo"><i class="fas fa-print"></i> imprimir</a>

        <button type="button" class="btn btn-flat grey" id="actualizarListCuotas"> <i class="fas fa-sync-alt"></i> actualizar </button>
        <button type="button" class="btn btn-flat blue atrasDetallesCuotas" id="atrasDetallesCuotas"> <i class="fas fa-caret-left"></i> volver </button>
        <button type="button" class="btn btn-flat blue modal-close" id="cerrarDetallesCuotas"> <i class="fas fa-times"></i> cerrar </button>
    </div>
</div>


<!-- Modal para enviar el recibo por email -->
<div class="modalEnviarCorreo modal">
  <form class="formEnviarCorreo formulario" id="formCorreo">

    <div class="modal-content">
      <div class="tituloFormulario">Enviar recibo</div>
      <label for="correo" class="correoLab">Correo electronico</label>
      <input type="email" class="correo" name="correo">
      <div class="mjeCorr"></div>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-flat blue modal-close" id="cerrarModalEnviarRec"> <i class="fas fa-times"></i> cerrar </button>
      <button type="submit" class="btn btn-flat green" id="btnEnviarRecibo"> enviar <i class="fas fa-paper-plane"> </i> </button>
    </div>

  </form>  
</div>

<script type="text/javascript" src="js/pago.js?v=3"></script>
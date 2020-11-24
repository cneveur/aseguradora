<div class="row" id="consultarPolRow">
  <div class="col s12">
    <table id="listadoEndososTabla" class="centered striped">
      <thead>
			<tr>
				<th scope="col">Nro de poliza</th>
				<th scope="col">Tomador</th>
				<th scope="col">Estado</th>
				<th scope="col">Ver</th>
			</tr>
		</thead>
		<tbody></tbody>
    </table>
  </div>
</div>

<!-- Modal de listado de endosos por poliza -->
<div id="modalListadoDeEndososPorPoliza" class="modal modal-fixed-footer">
   <div class="modal-content">
      <div class="subtituloFormulario">Endosos <span id="numeroPolizaEn">Poliza: <span id="numeroPolListado"></span> </span></div>
      <div id="cuerpoModalListadoEndosoPorPoliza">
        <table id="mostrarEndososPorPoliza" class="mostrarEndososPorPoliza centered striped">
          <thead>
            <tr>
              <th scope="col">Num</th>
              <th scope="col">Fecha</th>
              <th scope="col">Hora</th>
              <th scope="col">Tipo</th>
              <th scope="col">Descripcion</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody id="contenidoTablaEn"></tbody>
        </table>
      </div>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-flat blue" id="cerrarEndososPorPoliza"> <i class="fas fa-times"></i> cerrar </button>
   </div>
 </div>

<!-- Modal donde mostramos la descripcion a modificar del endoso seleccionado -->
 <div id="modalDescripcionEndoso" class="modal modal-fixed-footer">
   <div class="modal-content">
      <div class="subtituloFormulario" id="tituloModalDescripcion">Descripcion <span id="numeroPolizaEn">Endoso: <span id="numeroEndDesc"></span> </span> </div>
      <label for="desc"></label>
      <textarea class="form-control" id="desc" rows="5"></textarea>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-flat blue modal-close" id="cancelarDescEnd"> <i class="fas fa-times"></i> cancelar </button>
      <button type="button" class="btn btn-flat green" id="aceptarDescEnd"> <i class="fas fa-save"></i> guardar </button>
   </div>
 </div>

<script type="text/javascript" src="js/endosos.js?v=3"></script>
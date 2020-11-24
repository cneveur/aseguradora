<?php
  include_once('../../../controlador/siniestrosController.php');
  include_once('../../../config/db.php');
  $objSin = new SiniestrosController();
?>
<!-- Tabla donde muestro la lista de siniestros registrados -->
<div class="row" id="consultarPolRow">
  <div class="col s12">
    <table id="administrarSiniestroTabla" class="centered striped">
      <thead>
        <tr>
          <th scope="col">Nro Siniestro</th>
          <th scope="col">Nro Poliza</th>
          <th scope="col">Tomador</th>
          <th scope="col">Tipo</th>
          <th scope="col">Denuncia</th>
          <th scope="col">Estado</th>
          <th scope="col">Administrar</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- Modal Cambiar Estado Siniestro -->
 <div id="modalCambiarEstado" class="modal modal-fixed-footer">
   <div class="modal-content formulario">
      <div class="tituloFormulario">Cambiar Estado de Siniestro</div>
      <select id="selectEstadoSiniestro" ><?= $objSin->listadoEstadosSiniestro() ?></select>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-flat blue modal-close" id="cancelarCambiarEstadoSin"><i class="fas fa-times"></i> cerrar</button>
      <button type="button" class="btn btn-flat green" id="aceptarCambiarEstadoSin"> <i class="fas fa-save"></i> guardar </button>
   </div>
 </div>

<!-- Modal administrar imagenes del siniestro -->
<div id="modalAdminImgSin" class="modal modal-fixed-footer">
  <div class="modal-content formulario">
    <div class="tituloFormulario">Administrar imagenes del siniestro</div>
    <!--Formulario para seleccionar y cargar imagenes-->
    <form enctype="multipart/form-data">
      <div class="divBtnsImagenSiniestro row">

        <button type="button" class="btn btn-flat btn-floating blue" id="nuevaImagenBtn"> <i class="fas fa-plus"></i> </button>
        <label for="imagenSin">Nueva imagen</label>
        <input type="file" name="imagenSin" id="imagenSin">

        <div class="btnImg">
          <div id="estadoSubida"> </div>
          <div id="divBot"> <button type="button" class="btn btn-flat green" id="cargarImagen"> <i class="fas fa-upload"></i> cargar </button> </div>
        </div>
      </div>    
    </form>
    <!--Galeria donde se visualizan las imagenes-->
    <div class="galeriaImg">
      <div class="gallery-container"></div>
    </div>
  </div>
  <div class="modal-footer">
    <span class="subtituloFormulario" id="infoPieImgSin">
      Siniestro <span id="nroSiniestroPie"> </span>
      <span id="marcaModeloPie"></span>
    </span>
    <button type="button" class="btn btn-flat blue modal-close" id="cerrarModalAdminImgSin"> <i class="fas fa-times"></i> cerrar </button>
  </div>
</div>

<script type="text/javascript" src="js/siniestros.js"></script>
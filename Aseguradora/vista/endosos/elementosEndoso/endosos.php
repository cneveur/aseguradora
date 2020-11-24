<?php 
   include_once('../../../controlador/endososController.php');
   include_once('../../../config/db.php');
   $objEnd = new EndososController();
?>

<!-- Tabla donde muestro la lista de polizas registradas -->
<div class="row" id="consultarPolRow">
  <div class="col s12">
    <table id="mostrarPolizasTabla" class="centered striped">
      <thead>
			<tr>
				<th scope="col">Nro</th>
				<th scope="col">Tomador</th>
				<th scope="col">Riesgo</th>
				<th scope="col">Inicio Vig</th>
				<th scope="col">Fin Vig</th>
				<th scope="col">Estado</th>
				<th scope="col">Mod</th>
			</tr>
		</thead>
		<tbody id=""></tbody>
    </table>
  </div>
</div>

<!-- Modal Seleccionar tipo de endoso -->

<div id="modalSeleccionarEnd" class="modal modal-fixed-footer">
   <div class="modal-content formulario">
      <h5 class="tituloFormulario">Tipo de Endoso a registrar</h5>
      <select id="selectTipoEndoso" > <?= $objEnd->listadoTipoEndosos() ?> </select>
   </div>
   <div class="modal-footer">
     <button type="button" class="btn btn-flat blue modal-close" id="cancelarModalTipoEnd"> <i class="fas fa-times"></i> cancelar </button>
     <button type="button" class="btn btn-flat green" id="continuarModalTipoEnd"> continuar <i class="fas fa-caret-right"></i> </button>
   </div>
 </div>

<!--VENTANA MODAL DE ENDOSO VEHICULO-->
<div id="modalEndosoVehiculo" class="modal modal-fixed-footer">
  <form id="formularioEndosoV" class="formulario">
     <div class="modal-content">
     <div class="tituloFormulario">Endosos poliza: <span class="numeroPol"></span></div>
        <div id="cuerpoModalEndoso" class="modalEndoso">
            <?php include_once '../../endosos/divsEndoso/vehiculoModificar.php'?>
        </div>
        <ul id="mensajeEndoso" class="mensajeModal"></ul>
     </div>
     <div class="row modal-footer">
        <button type="button" class="btn btn-flat blue" id="cancelarCambiosEndosoV"> <i class="fas fa-times"></i> cancelar </button>
        <button type="submit" class="btn btn-flat green" id="guardarCambiosEndosoV"> <i class="fas fa-save"></i> guardar </button>
     </div>
  </form>
 </div>

 <!--VENTANA MODAL DE ENDOSO TOMADOR-->
<div id="modalEndosoTomador" class="modal modal-fixed-footer">
  <form id="formularioEndosoT" class="formulario">
     <div class="modal-content">
     <div class="tituloFormulario">Endosos poliza: <span class="numeroPol"></span></div>
        <div id="cuerpoModalEndoso" class="modalEndoso">
            <?php include_once '../../endosos/divsEndoso/tomadorModificar.php'?>
        </div>
        <ul id="mensajeEndoso" class="mensajeModal"></ul>
     </div>
     <div class="row modal-footer">
        <button type="button" class="btn btn-flat blue" id="cancelarCambiosEndosoT"> <i class="fas fa-times"></i> cancelar </button>
        <button type="submit" class="btn btn-flat green" id="guardarCambiosEndosoT"> <i class="fas fa-save"></i> guardar </button>
     </div>
  </form>
 </div>   

 <!--VENTANA MODAL DE ENDOSO COBERTURA-->
<div id="modalEndosoCobertura" class="modal modal-fixed-footer">
  <form id="formularioEndosoC" class="formulario">
     <div class="modal-content">
        <div class="tituloFormulario">Endosos poliza: <span class="numeroPol"></span></div>
        <div id="cuerpoModalEndoso" class="modalEndoso">
            <?php include_once '../../endosos/divsEndoso/coberturaModificar.php'?>
        </div>
     </div>
     <div class="row modal-footer">
        <button type="button" class="btn btn-flat blue" id="cancelarCambiosEndosoC"> <i class="fas fa-times"></i> cancelar </button>
        <button type="submit" class="btn btn-flat green" id="guardarCambiosEndosoC"> <i class="fas fa-save"></i> guardar </button>
     </div>
  </form>
 </div>   

<script type="text/javascript" src="js/cliente.js"></script>
<script type="text/javascript" src="js/endosos.js"></script>
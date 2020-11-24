<?php 
  if(file_exists('../../controlador/siniestrosController.php') && file_exists('../../config/db.php')){
		include_once('../../controlador/siniestrosController.php');
		include_once('../../config/db.php');
	}else if(file_exists('../../../controlador/siniestrosController.php') && file_exists('../../../config/db.php')){
		include_once('../../../controlador/siniestrosController.php');
		include_once('../../../config/db.php');
	} 
  $objSin = new SiniestrosController();
?>
 
<fieldset id="fieldsetSiniestro">

  <h2 class="tituloFormulario">siniestro</h2>

  <div class="cabeceraInfoSin">
    <div class="textoCabeceraNuevoSin"> Se asignara el numero <span id="nroSin" class="textoCabNuevoSinAzul"></span> al siniestro a registrar </div>
  </div>

  <div class="row">
    <div class="col s6">
      <label for="fechaOcurrencia">Fecha de Ocurrencia</label>
      <input type="text" id="fechaOcurrencia" name="fechaOcurrencia" placeholder="Seleccione" class="validarSin">
    </div>

    <div class="col s3">
      <label for="horaOcurrencia">Hora de Ocurrencia</label>
      <input type="time" id="horaOcurrencia" name="horaOcurrencia" placeholder="Ingrese" class="validarSin">
    </div>

    <div class="col s3">
      <label for="LesMuer">Hubo Lesion/Muerte</label>
      <select id="LesMuer" name="LesMuer" class="validarSin">
        <option value="0" selected disabled>Seleccione</option>
        <option value="NO">No</option>
        <option value="SI">Si</option>
      </select>  
    </div>
  
    <div class="col s8">
      <label for="selectPolizas">Poliza</label>
      <select class="select2 validarSin" id="selectPolizas" name="selectPolizas"></select>          
    </div>

    <div class="col s4">
      <label for="TipoSin">Tipo de Siniestro</label>
      <select id="TipoSin" name="TipoSin" class="validarSin">
        <?= $objSin->listadoTipoSiniestro() ?>
      </select>          
    </div>
  </div>  

  <span class="btnSigSin">
      <button type="submit" class="btn btn-flat green" id="siguienteSiniestro"> siguiente <i class="fas fa-caret-right"></i> </button>
  </span>
  
</fieldset>
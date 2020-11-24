<?php
  if(file_exists('../../controlador/funcionesGlobalesController.php') && file_exists('../../config/db.php')){
		include_once('../../controlador/funcionesGlobalesController.php');
		include_once('../../config/db.php');
	}else if(file_exists('../../../controlador/funcionesGlobalesController.php') && file_exists('../../../config/db.php')){
		include_once('../../../controlador/funcionesGlobalesController.php');
		include_once('../../../config/db.php');
	} 
  $objFG = new FuncionesGlobalesController();
?>

<fieldset id="fieldsetAccidente">

  <h2 class="tituloFormulario">accidente</h2>

  <div class="row">
    <div class="col s12">
      <label for="decripAcc">Descripcion</label>
      <textarea id="decripAcc" name="decripAcc" class="validarAcc"></textarea>
    </div>
  </div>

  <div class="row">
    <div class="col s6">
      <label for="provinciaAcc">Provincia</label>
      <select id="provinciaAcc" class="select2 validarAcc" name="provinciaAcc">
        <?= $objFG->listadoProvincias() ?>
      </select>
    </div>
    <div class="col s6">
      <label for="localidadAcc">Localidad</label>
      <select id="localidadAcc" class="select2 validarAcc" name="localidadAcc">
        <option value="0">Seleccione una Provincia</option>
      </select>
    </div>
  </div>  
    
  <div class="row">
      <div class="col s6">
        <label for="calleAcc">Calle</label>
        <input type="text" id="calleAcc" name="calleAcc" class="validarAcc">
      </div>
      <div class="col s6">
        <label for="alturaAcc">Altura</label>
        <input type="text" id="alturaAcc" name="alturaAcc" class="validarAcc">
      </div>          
  </div>
    
  <button type="button" class="btn btn-flat blue" id="atrasAccidente"><i class="fas fa-caret-left"></i> atras</button>
  <span class="btnAcc"></span>
  
</fieldset>
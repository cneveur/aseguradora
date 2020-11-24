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

<!--<form id="nuevoCliente" class="formulario">-->
  <fieldset>

    <div class="tituloFormulario tomador">datos del nuevo tomador</div>
	
	<div class="row">
		<div class="col s4">
	    	<label for="nombre">Nombre/Razon Social</label>
			<input type="text" class="campo" name="nombre" id="nombre" placeholder="Nombre Apellido">
	    </div>

	    <div class="col s4">
	    	<label for="documento">Documento/CUIT</label>
			<input type="text" class="campo" name="documento" id="documento" maxlength="8" placeholder="00000000000 (Sin espacios)">
	    </div>

	   	 <div class="col s4">
	    	<label for="persona">Persona</label>
			<select class="campo" name="persona" id="persona">
			  <option value="0" selected disabled>Seleccione</option>
			  <option value="fisica">Física</option>
			  <option value="juridica">Juridica</option>
			</select>
	    </div>
	    
	</div>

	<div class="row">
		<div class="col s4">
	    	<label for="nacionalidad">Nacionalidad/Pais</label>
			<input type="text" class="campo" name="nacionalidad" id="nacionalidad" placeholder="Nombre Pais">
	    </div>

	    <div class="col s4">
	    	<label for="fech_nac">Nacimiento/Orig</label>
			<input type="text" class="campo" name="fech_nac" id="fech_nac" placeholder="00/00/0000 (Seleccione)">
	    </div>

	    <div class="col s4">
	    	<label for="provincia" >Provincia/Distrito</label>
			<select class="campo select2" name="provincia" id="provincia">
				<?= $objFG->listadoProvincias() ?>
			</select>
	    </div>
	</div>

	<div class="row">

	    <div class="col s4">
	    	<label for="cp">Codigo Postal</label>
			<select class="campo select2" name="cp" id="cp"></select>
	    </div>

		<div class="col s4">
	    	<label for="localidad">Localidad</label>
		    <select class="campo select2" name="localidad" id="localidad"></select>
	    </div>

	    <div class="col s4">
	    	<label for="telefono">Teléfono</label>
			<input type="text" class="campo" name="telefono" id="telefono" placeholder="00000000000 (Sin espacios)">
	    </div>
	</div>

	<div class="row">
		<div class="col s4">
	    	<label for="calle">Calle</label>
			<input type="text" class="campo" name="calle" id="calle" placeholder="Nombre/Numero Calle">
	    </div>

	    <div class="col s4">
	    	<label for="genero">Género</label>
			<select class="campo" name="genero" id="genero">
			  <option value="0" selected disabled>Seleccione</option>
			  <option value="masculino">Masculino</option>
			  <option value="femenino">Femenino</option>
			  <option value="indeterminado">Indeterminado</option>
			</select>
	    </div>

	    <div class="col s4">
	    	<label for="correo">Correo</label>
			<input type="email" class="campo" name="correo" id="correo" placeholder="correo@ejemplo.com (Opcional)">
	    </div>
	</div>
    
  </fieldset>
<!--</form>-->
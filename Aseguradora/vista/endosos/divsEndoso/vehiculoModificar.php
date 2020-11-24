<?php
  include_once('../../../controlador/polizasController.php');
  include_once('../../../controlador/funcionesGlobalesController.php');
	include_once('../../../config/db.php');
  $objSelect = new PolizasController();
  $objFg = new FuncionesGlobalesController();
?>

<!--<form id="divVehiculoEndoso" class="formulario">-->
  <fieldset id="vehiculoField">

    <div class="subtituloFormulario" id="tituloDatosVehiculo"></div>

    <div class="row">
      <div class="col s2">
        <label for="suma_asegurada">Suma Asegurada</label>
        <input type="text" id="suma_asegurada" class="campo" placeholder="560.000" name="suma_asegurada">
      </div>

      <div class="col s3">
        <label for="select_marca">Marca</label>
        <select class="campo select2" id="select_marca" name="select_marca">
          <?= $objSelect->listadoMarcas() ?>
        </select>
      </div>

      <div class="col s3">
        <label for="select_modelo1">Modelo</label>
        <select class="campo select2" id="select_modelo1" name="select_modelo1">
          <option value="0" disabled selected>Seleccionar Marca</option>
        </select>
      </div>

      <div class="col s2">
        <label for="patente">Patente</label>
        <input type="text" id="patente" class="campo" placeholder="ABC 123" maxlength="9" name="patente">
      </div>

      <div class="col s2">
        <label for="motor">Motor</label>
        <input type="text" id="motor" class="campo" placeholder="2.0 TSI 20V 230CV" name="motor">
      </div>
    </div>

    <div class="row">
      <div class="col s3">
        <label for="nroChasis">Nro Chasis</label>
        <input type="text" id="nroChasis" class="campo" placeholder="B2N5R6S9E9R6VJ5T8" name="nroChasis">
      </div>

      <div class="col s2">
          <label for="nroMotor">Nro Motor</label>
          <input type="text" id="nroMotor" class="campo" placeholder="GH323JK" name="nroMotor">
      </div>

      <div class="col s3">
        <label for="select_clase">Clase</label>
        <select class="campo" id="select_clase" name="select_clase">
          <?= $objSelect->listadoClases() ?>
        </select>
      </div>

      <div class="col s4">
        <label for="selectTipoUso">Uso</label>
        <select class="campo" id="selectTipoUso" name="selectTipoUso">
          <?= $objSelect->listadoUsos() ?>
        </select>
      </div>
    </div>

    <div class="row">

       <div class="col s1">
          <label for="GPS">GPS</label>
          <div class="switch">
            <label> <input type="checkbox" id="GPS" class="filled-in"> <span class="filled-in"></span> </label>
          </div>
       </div>

       <div class="col s1">
          <label for="ceroKm">0 Km</label>
          <div class="switch">
            <label> <input type="checkbox" id="ceroKm" class="filled-in"> <span class="filled-in"></span> </label>
          </div>
       </div>

      <div class="col s2">
        <label for="cant_kilometro">Kilometros</label>
        <input type="text" id="cant_kilometro" class="campo" placeholder="124.000" name="cant_kilometro">
      </div>

      <div class="col s2">
        <label for="codigoPostal">Codigo Postal</label>
        <select class="campo select2" id="codigoPostal" name="codigoPostal">
         <?= $objFg->listadoCp() ?>
        </select>
      </div>

      <div class="col s4">
        <label for="vehiculoLocalidad">Localidad</label>
        <select class="campo select2" id="vehiculoLocalidad" name="vehiculoLocalidad">
          <option value="0" selected disabled>Seleccionar Codigo Postal</option>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col s3">
        <label for="coberturaAd">Coberturas Adicionales</label>
        <select class="campo" id="coberturaAd" name="coberturaAd">
          <?= $objSelect->listadoCoberturasAd() ?>
        </select>
      </div>

      <div class="col s2">
        <label for="combustible">Combustible</label>
         <select class="campo" id="combustible" name="combustible">
          <option value="0" selected disabled>Seleccione</option>
          <option value="Nafta">Nafta</option>
          <option value="GasOil">Gas Oil</option>
          <option value="Energia Electrica">Energia Electrica</option>
        </select>
      </div>

      <div class="col s1">
        <label for="eGas">Gas</label>
        <div class="switch">
          <label> <input type="checkbox" id="eGas" class="filled-in"> <span class="filled-in"></span> </label>
        </div>
      </div>

      <div class="col s2">
        <label for="anio">Año</label>
        <input type="number" id="anio" class="campo" placeholder="2017" name="anio">
      </div>

      <div class="col s1">
        <label for="pasajeros">Pasajeros</label>
        <input type="number" id="pasajeros" class="campo" placeholder="5" name="pasajeros">
      </div>

      <div class="col s1">
        <label for="asientos">Asientos</label>
        <input type="number" id="asientos" class="campo" placeholder="5" name="asientos">
      </div>

      <div class="col s2">
        <label for="color">Color</label>
        <input type="text" id="color" class="campo" placeholder="Blanco" name="color">
      </div>
     
    </div>  

  </fieldset>
<!--</form>-->

<script type="text/javascript" src="js/poliza.js?v=3"></script>
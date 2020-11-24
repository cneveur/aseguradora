<fieldset class="border p-2" id="polizaField">
  <legend class="w-auto">Endosos Datos de Poliza</legend>

  <form>
    <div class="form-row">

      <div class="form-group col-md-3">
      	<fieldset class="border p-2">
      	<label for="fechaPol">Emision de poliza</label>
  		  <input type="text" readonly class="form-control-plaintext" name="fechaPol" id="fechaPol" value="">
  		  </fieldset>
      </div>

      <div class="form-group col-md-3">
      	<fieldset class="border p-2">
          <label for="horaEmPo">Hora</label>
      		<input type="text" readonly class="form-control-plaintext" name="horaEmPo" id="horaEmPo" value="">
  		</fieldset>
      </div>

      <div class="form-group col-md-3">
        <fieldset class="border p-2">
        <label for="fechaEm">Emision de endoso</label>
        <?php date_default_timezone_set("America/Argentina/Buenos_Aires")?>
        <input type="text" readonly class="form-control-plaintext" name="fechaEm" id="fechaEm" value="<?=date("d/m/Y")?>">
        </fieldset>
      </div>

      <div class="form-group col-md-3">
        <fieldset class="border p-2">
          <label for="horaEm">Hora</label>
          <?php date_default_timezone_set("America/Argentina/Buenos_Aires")?>
          <input type="text" readonly class="form-control-plaintext" name="horaEm" id="horaEm" value="<?= date("H:i a")?>">
      </fieldset>
      </div>
    </div>

    <div class="form-row">

    	<div class="form-group col-md-2">
        	<label for="vig_ini">Inicio vigencia</label>
  		<input type="text" class="form-control form-control-sm" id="vig_ini" name="vig_ini" placeholder="Seleccione">
  		<div id="fechInVigR"></div>
      </div>

      <div class="form-group col-md-2">
          <label for="vig_finEndoso">Fin de vigencia</label>
      <input type="text" class="form-control form-control-sm" id="vig_finEndoso" name="vig_finEndoso" placeholder="Seleccione">
      <div id="fechFinVigR"></div>
      </div>

      <div class="form-group col-md-2">
        	<label for="horaInVig">Hora</label>
  		<input type="text" class="form-control form-control-sm" id="horaInVig" name="horaInVig" placeholder="Seleccione">
  		<div id="horaInVigR"></div>
      </div>

      <div class="form-group col-md-3">
        <label for="selectEmisorPoliza">Emisor</label>
        <select class="custom-select custom-select-sm" id="selectEmisorPoliza" name="selectEmisorPoliza">
          <option value="0" selected disabled >Seleccione</option>
          <?php include "../../poliza/elementosPoliza/mostrarPersonal.php"; ?>
        </select>
      <div id="emisorPolizaR"></div>
      </div>

      <div class="form-group col-md-3">
        <label for="select_emisorEndoso">Emisor de Endoso</label>
        <select class="custom-select custom-select-sm" id="select_emisorEndoso" name="select_emisorEndoso">
          <option value="0" selected disabled >Seleccione</option>
          <?php include "../../poliza/elementosPoliza/mostrarPersonal.php"; ?>
        </select>
      <div id="emisorR"></div>
      </div>

    </div>
  </form>
</fieldset>
<h2 class="tituloFormulario titForPol">datos de poliza</h2>

<div class="infoCabeceraEmisPoliza">
	<span class="textoCabecer">Se asignara el numero <span id="nroPoliza"></span> a la poliza a registrar</span>
</div>

<div class="row">

	<div class="col s5">
		<label for="vig_ini" class="vig_ini">Inicio de vigencia</label>
		<input type="text" id="vig_ini" placeholder="Seleccione" name="vig_ini">
	</div>

	<div class="col s2">
		<label for="tipVig" class="tipVig">Fin de vigencia</label>
		<select name="tipVig" id="tipVig" class="tipVig">
			<option value="0" selected disabled>Seleccione vigencia</option>
			<option value="4">4 Meses</option>
			<option value="5">5 Meses</option>
			<option value="6">6 Meses</option>
			<option value="7">7 Meses</option>
			<option value="8">8 Meses</option>
			<option value="9">9 Meses</option>
			<option value="10">10 Meses</option>
			<option value="11">11 Meses</option>
			<option value="12">12 Meses</option>
		</select>
	</div>

	<div class="col s5">
		<label for="vig_fin" class="vig_fin">Fecha de finalizacion</label>
		<input type="text" id="vig_fin" placeholder="Seleccione" name="vig_fin">
	</div>

</div>

<div class="divResumenVigPoliza">
	<!-- <div class="resumen1">Complete la informacion</div> -->
	<div class="resumen">
		<input type="text" id="vigenciaInicioInput">
		<input type="text" id="vigenciaFinInput">
		La vigencia establecida sera desde el <span id="vigenciaInicioSpan"></span> hasta el <span id="vigenciaFinSpan"></span> a las 11:59 AM
	</div>
</div>
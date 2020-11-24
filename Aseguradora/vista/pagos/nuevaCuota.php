<div class="tituloFormulario" id="tituloModalPago"></div>

<!--<fieldset class="fieldsetCuotas">-->

	<div class="datosPol">
		<div class="infoPag-1">
			<div class="infoPag-2">
				<div class="infoPag-3">
					<div class="infoPag-4-titulo">Poliza</div> 
					<div class="infoPag-4-conteniido">
						<div> Numero <span id="numPoliza"></span> </div> 
						<div> Emitida el <span id="emision"></span> a las <span id="horaEmision"></span> </div>
					</div>
				</div>
				<div class="infoPag-3">
					<div class="infoPag-4-titulo">Vigencia</div> 
					<div class="infoPag-4-conteniido">
						<div> Desde <span id="inicioVig"></span> <span id="inicioHora"></span> hasta <span id="finVig"></span> <span id="finHora"></span> (<span id="mesesVig"></span> mes/es) </div> 
						<input type="text" class="mesesVigMuestra" id="tipVig">
					</div>
				</div>
				<div class="infoPag-3">
					<div class="infoPag-4-titulo">Vehiculo</div> 
					<div class="infoPag-4-conteniido">
						<div> <span id="marcaP"></span> <span id="modeloP"></span> <span id="motorP"></span> - <span id="localidadP"></span> <span id="provinciaP"></span> - <span id="cpP"></span> </div>
						<div> Año: <span id="anio"></span> </div>
						<div> Es cero km: <span id="ceroKm"></span> </div> 
						<div> Kilometros: <span id="kms"></span> </div> 
						<div> Equipo de gas <span id="eGas"></span> </div> 
					</div>    
				</div>
				<div class="infoPag-3">
					<div class="infoPag-4-titulo">Coberturas</div> 
					<div class="infoPag-4-conteniido">
						<div> Principal <span id="coberturaDesc"></span> <span id="coberturaNombre"></span> </div Principal>
						<div> Adicional <span id="coberturaAd"></span> </div> 
						<div> Suma Asegurada $<span id="sumaAs"></span> </div> 
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="row">

		<div class="col s2">
			<label for="primaTotal">Prima total $</label>
			<input type="number" id="primaTotal" name="primaTotal">
		</div>

		<div class="col s2">
			<label for="cantCuotas">Cant cuotas</label>
			<select id="cantCuotas" name="cantCuotas"></select>
		</div>

		<div class="col s2">
			<label for="primaMensual">Prima mensual $</label>
			<input type="number" id="primaMensual" name="primaMensual">
		</div>

		<div class="col s2" id="diaCob">
			<label for="diaCobrar">Dia a facturar</label>
			<select id="diaCobrar" name="diaCobrar"></select>
		</div>

		<div class="col s3 tipoPag">
			<label for="tipoPag">Metodo de pago</label>
		    <select id="tipoPag" name="tipoPag">
		    	<option value="0" selected disabled>Seleccione</option>
		      <option value="1">Efectivo</option>
		      <option value="2" disabled>Asociar Tarjeta de Credito/Debito</option>
		    </select>
		</div>

		<div class="col s2 divVerCuotas">
			<label for="verCuotas">Ver cuotas</label>
			<a type="button" id="verCuotas" class="btn btn-flat"> <i class="fas fa-eye"></i> </a >
		</div>

		<input type="text" id="idPago">
		
	</div>

	<script type="text/javascript" src="js/pago.js?v=3"></script>
	
	<!--
	<div class="row datosTarjeta">
		<hr class="hrPago">
		<div class="col s4">
			<label for="tarjetaNum">Numero de tarjeta</label>
			<input type="text" class="browser-default" placeholder="0000 0000 0000 0000" id="tarjetaNum" name="tarjetaNum">
		</div>

		<div class="col s4">
			<label for="tarjetaNombre">Nombre</label>
			<input type="text" class="browser-default" placeholder="Tal cual figura en la tarjeta" id="tarjetaNombre" name="tarjetaNombre">
		</div>

		<div class="col s1">
			<label for="tarjetaMesCad">Mes caduc</label>
			<input type="text" class="browser-default" placeholder="MM" id="tarjetaMesCad" name="tarjetaMesCad">
		</div>

		<div class="col s1">
			<label for="tarjetaAnCad">Año caduc</label>
			<input type="text" class="browser-default" placeholder="AAAA" id="tarjetaAnCad" name="tarjetaAnCad">
		</div>
    </div>

    <button type="button" id="comprobarTarjeta" class="waves-light btn-small blue">Comprobar</button>-->



<!--</fieldset>-->
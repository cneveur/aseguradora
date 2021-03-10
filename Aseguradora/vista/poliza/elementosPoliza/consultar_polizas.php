<!-- Tabla donde muestro todas las polizas y su informacion -->
<div class="row" id="consultarPolRow">
	<div class="col s12">
		<table id="consultarPolizasTabla" class="centered">
			<thead>
				<tr>
					<th scope="col">Nro</th>
					<th scope="col">Tomador</th>
					<th scope="col">Vehiculo</th>
					<th scope="col">Emision</th>
					<th scope="col">Vigencia</th>
					<th scope="col">Anulacion</th>
					<th scope="col">Estado</th>
					<th scope="col">Info</th>
					<th scope="col">Ver</th>
				</tr>
			</thead>
			<tbody id="info"></tbody>
		</table>
		<div class="btnActPolPadre">
			<button type="button" class="btn btn-flat blue btnActualizarListPolizas" id="actListPol"> <i class="fas fa-sync-alt"></i> actualizar </button>
		</div>
	</div>
</div>
 
<script type="text/javascript" src="js/poliza.js?v=3"></script>
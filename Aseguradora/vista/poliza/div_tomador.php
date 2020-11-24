<form id="divTomador" class="formulario">
	<fieldset id="fieldsetTomador">
		<?php require_once 'formularios/formularioTomador.php' ?>
		<div id="cliR"></div>
		<div id="botonesTomador">
			<button type="button" class="btn btn-flat blue" id="atrasTomador"> <i class="fas fa-caret-left"></i> atras </button>
			<span class="btnDivTomador"></span>
		</div>
	</fieldset>
	<ul id="mensajeTomador" class="mensaje ul2"></ul>
</form>

<!--VENTANA MODAL DE NUEVO CLIENTE-->
<div id="modalcliente" class="modal modal-fixed-footer" >
	<form id="nuevoCliente" class="formulario">
		<div class="modal-content">
			<div id="cuerpoModal">
				<?php include_once '../../tomador/nuevoTomador.php'; ?>
			</div>
			<ul id="mensajeTomador" class="mensaje mensajeModal"></ul>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-flat blue modal-close" id="cancelarCliente"> <i class="fas fa-times"></i> cerrar </button>
			<button type="submit" class="btn btn-flat green" id="grabarCliente"> <i class="fas fa-save"></i> grabar </button>
		</div>

	</form>
</div>

<script type="text/javascript" src="js/cliente.js?v=3"></script>
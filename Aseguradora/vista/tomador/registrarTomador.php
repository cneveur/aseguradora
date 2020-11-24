<form id="nuevoCliente" class="formulario nuevoCliente">
    <?php include_once 'nuevoTomador.php'; ?>  

    <div class="btnsNuevoTomador">
        <div class="btnsNuevoTomadorHijo">
            <button type="button" class="btn btn-flat red cancelarClienteSec" id="cancelarClienteSec"> <i class="fas fa-times"></i> cancelar </button>
            <button type="submit" class="btn btn-flat green grabarCliente" id="grabarClienteSec"> <i class="fas fa-save"></i> grabar </button>
        </div>
    </div>

    <ul id="mensajeTomador" class="mensaje ul2 mensajeTomador"></ul>

</form>

<script type="text/javascript" src="js/cliente.js?v=3"></script>
<?php  
	include_once('../../controlador/funcionesGlobalesController.php');
	include_once('../../config/db.php');
	$objFG = new FuncionesGlobalesController();
?>


<form id="registroSucursal" class="formulario registroSucursal">
    <fieldset>
        <div class="tituloFormulario"> <i class="fas fa-building"></i> agregar nueva sucursal</div>

        <div class="row">
            <div class="col s4">
                <label for="nombreSucursal">Nombre</label>
                <input type="text" name="nombreSucursal" id="nombreSucursal" placeholder="Sucursal de X cuidad">
            </div>

            <div class="col s4">
                <label for="cuitSucursal">Cuit</label>
                <input type="text" name="cuitSucursal" id="cuitSucursal" placeholder="00-00000000-0">
            </div>

            <div class="col s4">
                <label for="direccionSucursal">Direccion</label>
                <input type="text" name="direccionSucursal" id="direccionSucursal" placeholder="Calle x Numero x">
            </div>
        </div>

        <div class="row">

            <div class="col s4">
                <label for="provinciaSucursal">Provincia</label>
                <select class="select2" name="provinciaSucursal" id="provinciaSucursal">
                    <?= $objFG->listadoProvincias() ?>
                </select>
            </div>

            <div class="col s4">
                <label for="cpSucursal" >Codigo Postal</label>
                <select class="select2" name="cpSucursal" id="cpSucursal">
                    <option selected disabled>Seleccione Provincia</option>
                </select>
            </div>
            
            <div class="col s4">
                <label for="localidadSucursal">Localidad</label>
                <select class="select2" name="localidadSucursal" id="localidadSucursal">
                    <option selected disabled>Seleccione Codigo Postal</option>
                </select>
            </div>

        </div>

        <div class="col s12 btnsNavSucursal">
            <button type="button" class="volverSucursal btn-flat MAI blue"> <i class="fas fa-angle-left"></i> volver</button>
            <button type="submit" class="btnSubmitSucursal btn-flat MAI green" id="submitSucursal"> <i class="fas fa-save"></i> registrar</button>
        </div>
    </fieldset>

    <ul id="mensajeSucursal" class="col s12 mensaje"></ul>
</form>

<script type="text/javascript" src="js/sucursales.js?v=14"></script>
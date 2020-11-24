<?php  
	include_once('../../controlador/funcionesGlobalesController.php');
	include_once('../../config/db.php');
	$objFG = new FuncionesGlobalesController();
?>

<form id="registroUser" class="formulario registroUser">
    <fieldset>
        <div class="tituloFormulario"> <i class="fas fa-user"></i> datos del nuevo usuario</div>

        <div class="row">
            <div class="col s4">
                <label for="nombreUser">Nombre completo</label>
                <input type="text" name="nombreUser" id="nombreUser" placeholder="Nombre Apellido">
            </div>

            <div class="col s4">
                <label for="documentoUser">Documento</label>
                <input type="text" name="documentoUser" id="documentoUser" maxlength="8" placeholder="00000000000 (Sin espacios)">
            </div>

            <div class="col s4">
                <label for="nacionalidadUser">Nacionalidad</label>
                <input type="text" name="nacionalidadUser" id="nacionalidadUser" placeholder="Argentina">
            </div>
        </div>

        <div class="row">
            <div class="col s4">
                <label for="provinciaUser" >Provincia/Distrito</label>
                <select class="select2" name="provinciaUser" id="provinciaUser">
                    <?= $objFG->listadoProvincias() ?>
                </select>
            </div>

             <div class="col s4">
                <label for="localidadUser">Localidad</label>
                <select class="select2" name="localidadUser" id="localidadUser"></select>
            </div>

            <div class="col s4">
                <label for="direccionUser">Direccion</label>
                <input type="text" name="direccionUser" id="direccionUser" placeholder="Calle - Numero">
            </div>
        </div>

        <div class="row">
           <div class="col s4">
                <label for="nacUser">Fecha nacimiento</label>
                <input type="text" class="datepicker" name="nacUser" id="nacUser" placeholder="00/00/0000">
            </div>

            <div class="col s4">
                <label for="telefonoUser">Teléfono</label>
                <input type="text" name="telefonoUser" id="telefonoUser" placeholder="00000000000 (Sin espacios)">
            </div>

            <div class="col s4">
                <label for="correoUser">E-Mail</label>
                <input type="email" name="correoUser" id="correoUser" placeholder="correo@ejemplo.com">
            </div>
        </div>

        <div class="row">
            <div class="col s4">
                <label for="generoUser">Género</label>
                <select name="generoUser" id="generoUser">
                    <option value="0" selected disabled>Seleccione</option>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                    <option value="otro">Otro</option>
                </select>
            </div>

            <div class="UP"></div>

            <div class="col s4 rol">
                <label for="rolUser">Rol</label>
                <select name="rolUser" id="rolUser">
                    <option value="0" selected disabled>Seleccione</option>
                    <option value="usuario">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
        </div>  

        <div class="col s12 btnsRegistroUser">
            <button type="button" class="volverUser btn-flat MAI blue"> <i class="fas fa-angle-left"></i> volver</button>
            <button type="submit" class="registrarUser btn-flat MAI green" id="submitUser"> <i class="fas fa-save"></i> registrar</button>
        </div>
    </fieldset>

    <ul id="mensajeUser" class="mensaje"></ul>
</form>

<script type="text/javascript" src="js/users.js?v=3"></script>
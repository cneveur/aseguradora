jQuery(document).ready(function(){

    validarUser();
    getFotoPerfilUser();

    $('.modal').modal({
        dismissible: false
    });

    $('input, select').addClass('browser-default');

    $("#provinciaUser, #localidadUser").select2({width: "100%"})
    $("#nacUser").prop('readonly', true).datepicker({
        dayNames: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
	    dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
	    monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
	    monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        dateFormat: 'dd/mm/yy',
        
        changeMonth: true,
        changeYear: true,
        // yearRange: "c-100:c-16",
        minDate: '-100Y',
        maxDate: '-16Y'
    });

    $('.ui-datepicker-title select').removeAttr('style');

    $("#provinciaUser").on('change', function(){
        idProv = $("#provinciaUser").val();
        mostrarLocalidades(idProv);
    })

    $(document).off('click', '.btnNuevoUser');
    $(document).on('click', '.btnNuevoUser', function(){
        $("#contenidoC").load('vista/users/nuevoUser.php')
    });

    $(document).off('click', '.btnListadoBajas');
    $(document).on('click', '.btnListadoBajas', function(){
       listadoUserBaja()
    })

    $(".volverUser").off('click');
    $(".volverUser").on('click', function() {
        $('#contenidoC').load('vista/users/administrarUsers.php', function(){
            tablaListadoUsers();
        });
    });

    $(".volverMisDatos, .volverGrabarFotoUser").off('click');
    $(".volverMisDatos, .volverGrabarFotoUser").on('click', function() {
        $("#inicio").trigger('click');
    });

    $(document).off('click', '.reactivarUser')
    $(document).on('click', '.reactivarUser', function(){
        idUser = $(this).attr('value')
        reactivarUser(idUser)
    });

    //Configurar datos del usuario
    $(".misDatos").off('click');
    $(".misDatos").on('click', function(){
        getMisDatosUser();
    });

    $(".modFotoUser").off('click');
    $(".modFotoUser").on('click', function(){ 
        $("#contenidoC").load('vista/users/modFotoUser.php');
        $("#divTit").css('display', 'block');
        $("#tituloCont").html('mi foto de perfil');
        $("#divSubTit").html('');
        //getFotoPerfilUser();
    });

    $(".grabarNuevaFotoUser").off('click');
    $(".grabarNuevaFotoUser").on('click', function(){
        modFotoUser();
    });
})

function validarUser()
{
    var valNombre = /^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/
    var valFecha = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/
    var email = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i

    $.validator.addMethod("validarNombre", function (value, element) {
        return this.optional(element) || valNombre.test(value)
    })

    $.validator.addMethod("validarFecha", function (value, element) {
        return this.optional(element) || valFecha.test(value)
    })

    $.validator.addMethod("validarGenero", function (value, element) {
        return this.optional(element) || value == 'masculino' || value == 'femenino' || value == 'otro'
    })

    $.validator.addMethod("validarRol", function (value, element) {
        return this.optional(element) || value == 'usuario' || value == 'admin'
    })

    $.validator.addMethod('email', function(value, element){
		return this.optional(element) || email.test(value);
	});


    $("#registroUser").validate({
        rules: {
            "nombreUser":       {required: true, validarNombre: true},
            "documentoUser":    {required: true, digits: true, rangelength: [6, 8]},
            "nacionalidadUser": {required: true, lettersonly: true},
            "provinciaUser":    {required: true, rangelength: [1, 24]},
            "localidadUser":    {required: true, rangelength: [1, 22963]},
            "direccionUser":    {required: true},
            "nacUser":          {required: true, validarFecha: true},
            "telefonoUser":     {required: true, digits: true, min: 6},
            "correoUser":       {required: true, email: true},
            "generoUser":       {required: true, validarGenero: true},
            "rolUser":          {required: true, validarRol: true},
            //Modificar usuario y contraseña
            "usuarioUser":      {required: true, minlength: 4, maxlength: 35},
            "pass1":            {required: true, minlength: 5},
            "pass2":            {required: true, minlength: 5, equalTo: "#pass1" }
        },
        messages: {
            "nombreUser":       {required: "Debe ingresar el Nombre",                  validarNombre: "El Nombre ingresado es incorrecto"},
            "documentoUser":    {required: "Debe ingresar el Documento",               digits: "El Documento ingresado es incorrecto", rangelength: "El Documento ingresado esta incompleto"},
            "nacionalidadUser": {required: "Debe ingresar la Nacionalidad",            lettersonly: "El Pais ingresado es incorrecto"},
            "provinciaUser":    {required: "Debe seleccionar una Provincia",           rangelength: "La Provincia es incorrecta"},
            "localidadUser":    {required: "Debe seleccionar una Localidad",           rangelength: "La Localidad es incorrecta"},
            "direccionUser":    {required: "Debe ingresar una Direccion"},
            "nacUser":          {required: "Debe seleccionar una Fecha de Nacimiento", validarFecha: "La Fecha ingresada es incorrecta"},
            "telefonoUser":     {required: "Debe ingresar un Telefono",                digits: "El Telefono ingresado es incorrecto", min: "El Telefono ingresado es incorrecto"},
            "correoUser":       {required: "Debe ingresar una Direccion de E-Mail",    email: "El E-Mail ingresado es incorrecto"},
            "generoUser":       {required: "Debe seleccionar el Genero",               validarGenero: "El genero seleccionado es incorrecto"},
            "rolUser":          {required: "Debe seleccionar el Rol",                  validarRol: "El tipo de rol seleccionado es incorrecto"},

            "usuarioUser":      {required: "Debe ingresar un nombre de usuario",       minlength: "El usuario debe tener al menos 4 caracteres", maxlength: "El usuario es demasiado largo"},
            "pass1":            {required: "Debe ingresar una contraseña",             minlength: "La contraseña debe tener al menos 5 caracteres"},
            "pass2":            {required: "Debe ingresar nuevamente la contraseña",   minlength: "La contraseña debe tener al menos 5 caracteres", equalTo: "Las contraseñas no son iguales"}
        },

        highlight: function (element) {
            if ($(element).hasClass('select2')) {
                $(element).siblings('span').find('.selection').find('.select2-selection').addClass('claseError');
            } else {
                $(element).addClass('claseError');
            }

        },
        unhighlight: function (element){
            if ($(element).hasClass('select2')) {
                $(element).siblings('span').find('.selection').find('.select2-selection').removeClass('claseError');
            } else {
                $(element).removeClass('claseError');
            }
        },

        errorElement: 'li',
        errorLabelContainer: '#mensajeUser',

        submitHandler: function(){
            if($("#submitUser").hasClass("registrarUser")){
                grabarUser();
            }else if($("#submitUser").hasClass("modificarUser")){
                setMisDatosUser();
            }
        }
    })
}

function mostrarLocalidades(idProv, selectLoc){

    $("#localidadUser").empty();

    $.ajax({
        url: 'scripts/aseguradora.php',
        type: 'post',
        dataType: 'json',
        data: {param:80001, id:idProv},
        success: function(r){
            if(r!=null){
                $("#localidadUser").append('<option value="0" selected disabled>Seleccione</option>')
                $.each(r.data, function(key, val){
                    if(selectLoc==val.localidadId){
                        $("#localidadUser").append('<option value="'+val.localidadId+'" selected>'+val.localidadNombre+'</option>')
                    }else{
                        $("#localidadUser").append('<option value="'+val.localidadId+'">'+val.localidadNombre+'</option>')
                    }
                })
            }else{
                alertify.error('Ocurrio un error al mostrar las localidades')
            }
        }
    })
}

function grabarUser(){

    formUser = [
        $("#nombreUser").val(),
        $("#documentoUser").val(),
        $("#nacionalidadUser").val(),
        $("#provinciaUser").val(),
        $("#localidadUser").val(),
        $("#direccionUser").val(),
        $("#nacUser").val(),
        $("#telefonoUser").val(),
        $("#correoUser").val(),
        $("#generoUser").val(),
        $("#rolUser").val()
    ]

    $.ajax({
        url: 'scripts/aseguradora.php',
        type: 'post',
        dataType: 'json',
        data: {param:60000, formUser:formUser},
        
        success: function(r){
            if(r.success){
                email = r.email
                idUser = r.idUser
                enviarEmailRegistroUser(email, idUser, 'registro')
            }else{
                alertify.error('Ocurrio un problema al registrar el nuevo usuario')
            }
        }
    })
}

function enviarEmailRegistroUser(email, idUser, parametro)
{

    if(parametro=='registro'){
        nombreBoton = '.registrarUser';
        contenidoBoton = '<i class="fas fa-check-circle"></i> registrado';
        mensaje = 'Usuario registrado exitosamente';
    }else if(parametro=='reenviar'){
        nombreBoton = '#btnEnviarInfoAcceso';
        contenidoBoton = '<i class="fas fa-check-circle"></i> enviado';
        mensaje = 'Datos de acceso enviados correctamente.';
    }

    const html = 
    `La informacion para el acceso al sistema fueron enviados por e-mail al usuario. </br>
     En caso de no haber recibido la informacion, debe de ponerse en contacto con el administrador.`;

    var info = [email, idUser]

    $.ajax({
        url: 'scripts/emails/enviarEmailRegistroUser.php',
        type: 'post',
        dataType: 'json',
        data: {info:info},
        beforeSend(){
            $(nombreBoton).html('<img src="img/ImgAlt/preload.gif">').show().attr('disabled', 'disabled');
            $(".volverUser").attr('disabled', 'disabled');
		},
        success: function(r){
            if(r.envCorr){
                alertify.success(mensaje);
                $(nombreBoton).html(contenidoBoton).show().attr('disabled', 'disabled');
                $(".volverUser").attr('disabled', false);

                if(parametro=='reenviar'){
                    $(".modalReenviarInfoAcceso").modal('close');
                }

                alertify.alert('Registro de usuarios', html, function(){
                    $('#contenidoC').load('vista/users/administrarUsers.php', function(){
                        tablaListadoUsers();
                    })
                }).set(configuracionAlert('ok'))
            }else{
                alertify.error('Ocurrio un problema al enviar la informacion de acceso al usuario')
            }
        }
    })
}

function bajaUser(idUser)
{
    alertify.confirm('Anular usuario', '¿Estas seguro? El usuario ya no podra acceder al sistema hasta que se revierta esta opcion.',
    function(){

    $.ajax({
        url: 'scripts/aseguradora.php',
        dataType: 'json',
        type: 'post',
        data: {param: 60001, idUser:idUser},
        success: function(r){
            if(r.success){
                if(r.mismoEst){
                    alertify.alert('El usuario ya se encuentra dado de baja en el sistema');
                }else{
                    alertify.success('Usuario dado de baja correctamente');
                }
            }else
            {
                alertify.error('Ocurrio un problema al dar de baja el usuario');
            }
            tablaListadoUsers.ajax.reload()
            $(".btnBajaUser, .btnModRolUser, .btnInfoUser, .btnReenviarInfoAccesoUser").css('display', 'none')
            $(".btnNuevoUser, .btnListadoBajas").css('display', 'inline')

        }
    });

    },
    function(){}
    ).set(configuracionAlert('okCancel','Dar de baja'));
}

function reactivarUser(idUser)
{   
    alertify.confirm('Reactivar user', '¿Estas seguro? El usuario podra ingresar nuevamente al sistema.',
    function(){

    $.ajax({
        url: 'scripts/aseguradora.php',
        dataType: 'json',
        type: 'post',
        data: {param: 60002, idUser:idUser},
        success: function(r){
            if(r.success){
                if(r.mismoEst){
                    alertify.alert('El usuario ya se encuentra activo en el sistema');
                }else{
                    alertify.success('Usuario reactivado correctamente');
                }
            }else
            {
                alertify.error('Ocurrio un problema al reactivar el usuario');
            }
            tablaListadoUsers.ajax.reload();
            tablaListadoUserBaja.ajax.reload();
        }
    });
    },
    function(){}
    ).set(configuracionAlert('okCancel','Reactivar'));
}

function traerInfoUser(idUser)
{
    $(".checkUs, .checkAdm, .enviarModRol").prop({'checked': false, 'disabled': false})

    $("#modalModificarRolUser").modal('open');
    $.ajax({
        url: 'scripts/aseguradora.php',
        type: 'post',
        dataType: 'json',
        data: {param:60003, idUser:idUser},
        success: function(r){
            if(r.success){
                //asignamos la informacion al los campos
                $(".num").html('#'+r.nro);
                $(".nombre").html(r.nombre);
                $(".usuario").html('@'+r.usuario);
            
                //Chequeamos la opcion que corresponde, pero si queremos modificar el administrador y solo existe uno, no podremos
                if(r.rol=='Usuario'){
                    $(".checkUs").prop({'checked': true, 'disabled': true});
                    $(".checkAdm").prop('checked', false);
                }else if(r.rol=='Administrador'){
                    if(r.cantAdm==1){
                        $(".checkAdm").prop({'checked': true, 'disabled': true});

                        $(".checkUs").off('click');
                        $(".checkUs").on('click', function(){
                            $(".checkAdm").prop('checked', true);
                            alertify.alert('Modificar rol usuario', 'No es posible modificar el rol de '+r.nombre+' en el sistema, ya que es el unico usuario administrador.').set(configuracionAlert('ok'));
                        });

                    }else if (r.cantAdm>1){
                        $(".checkAdm").prop({'checked': true, 'disabled': true});

                        $(".checkUs").off('click');
                        $(".checkUs").on('click', function(){
                            $(".checkUs").prop('checked', true);
                        });
                    }
                }

                //Enviamos la peticion ajax para cambiar el rol al presionar en enviar
                $(".enviarModRol").off('click')
                $(".enviarModRol").on('click', function(){
                    modRolUser(idUser, r.nombre, r.rol)
                })

            }else{
                alertify.error('Ocurrio un error al realizar la peticion al servidor')
            }
        }
    })
}

function modRolUser(idUser, nombre, rol)
{
    if($(".checkAdm").is(':checked')){
        var nuevoRol = 'Administrador';
        texto = '¿Desea convertir a '+nombre+' en administrador del sistema?';
    }else if($(".checkUs").is(':checked')){
        var nuevoRol = 'Usuario';
        texto = '¿Desea convertir a '+nombre+' solo en usuario del sistema?';
    }

    info = [idUser, nuevoRol];

    if(nuevoRol==rol){
        alertify.alert('Modificar rol usuario', ''+nombre+ ' ya posee el rol de '+rol+'.').set(configuracionAlert('ok'));
    }else{
        alertify.confirm('Modificar rol usuario', texto, 
        function(){
            $.ajax({
                url: 'scripts/aseguradora.php',
                type: 'post',
                dataType: 'json',
                data: {param:60004, info:info},
                success: function(re)
                {
                    $(".btnBajaUser, .btnModRolUser, .btnInfoUser, .btnReenviarInfoAccesoUser").css('display', 'none')
                    $(".btnNuevoUser, .btnListadoBajas").css('display', 'inline')
                    tablaListadoUsers.ajax.reload()
                    $("#modalModificarRolUser").modal('close');

                    if(re.success){
                        texto = ''+nombre+' ahora posee los permisos que asi le adjudica el rol de '+nuevoRol+'.'
                        alertify.alert('Rol modificado', texto).set(configuracionAlert('ok'))
                        alertify.success('Rol modificado correctamente')
                    }else{
                        alertify.error('Ocurrio un problema al modificar el rol del usuario')
                    }
                }
            })
        }, 
        function(){}
        ).set(configuracionAlert('okCancel','Modificar rol'));
    }
}

function infoUser(idUser)
{
    $("#modalVerInfoUser").modal('open');

    $.ajax({
        url: 'scripts/aseguradora.php',
        type: 'post',
        dataType: 'json',
        data: {param:60003, idUser:idUser},
        success: function(r){
            if(r.success){
                $(".nume").html('#'+r.nro);
                $(".usu").html('@'+r.usuario);
                $(".estado").html(r.estado);
                $(".rol").html(r.rol);
                $(".nomb").html(r.nombre);
                $(".doc").html(r.documento);
                $(".naci").html(r.fecha_nac);
                $(".gen").html(r.genero);
                $(".correo").html(r.correo);
                $(".tel").html(r.telefono);
                $(".domi").html(r.direccion+' - '+r.localidad);
                $(".locPais").html(r.provincia+' - '+r.nacionalidad);
            }else{
                alertify.error('Ocurrio un error al realizar la peticion al servidor');
            }
        }
    }) 
}

function generarReenvioDatosUser(idUser){

    texto = `¿Estas seguro? Al realizar esta accion, se generara una nueva contraseña 
           y sera enviada junto al nombre de usuario actual a la direccion de correo asignada.`;

    alertify.confirm('Reenviar datos de acceso', texto,
    function(){

        $(".modalReenviarInfoAcceso").modal('open');

        $.ajax({
            url: 'scripts/aseguradora.php',
            type: 'post',
            dataType: 'json',
            data: {param:60003, idUser:idUser},
            success: function(r){
                if(r.success){
                    validarEmail(0, idUser);
                    $('.correoUs').val(r.correo);
                    
                }else{
                    alertify.error('Ocurrio un error al realizar la peticion al servidor');
                }
            }
        }) 
    },
    function(){}
    ).set(configuracionAlert('okCancel','Aceptar', 'Cancelar'));
}

function validarEmail(param, idUser)
{    
    idUsuario = idUser;

	$("#formCorreo").validate({
		rules: {
			"correo": { required: true, email: true }
		},
		messages: {
			"correo": { required: "Debe ingresar una direccion de correo electronico", email: "Debe ingresar una direccion de correo electronico valida" }
		},
		highlight: function (element) {
			$(element).css({ 'border': '1px solid #ff00005c', 'background': '#ff00000a' });
		},
		unhighlight: function (element) {
			$(element).css({ 'border': '1px solid #ccc', 'background': 'transparent' });
		},

		errorElement: 'label',
		errorLabelContainer: '.mjeCorr',

		submitHandler: function(form){
			if(param==0){
                reenviarInfoAccesoUser(idUsuario);
			}
		}
	})
}

function reenviarInfoAccesoUser(idUser){
    idUsuario = idUser;
    $.ajax({
        url: 'scripts/aseguradora.php',
        type: 'post',
        dataType: 'json',
        data: {param:60009, idUser:idUsuario},
        success: function(r){
            if(r.modPassUser){

                email = $('.correoUs').val();
                nombreUser = r.nombreUser;
                enviarEmailRegistroUser(email, idUser, 'reenviar');

            }else{
                alertify.error('Ocurrio un error al realizar la peticion al servidor');
            }
        }
    }) 
}

function getMisDatosUser()
{
    $("#divTit").css('display', 'block');
    $("#tituloCont").html('mis datos');
    $("#divSubTit").html('');

    $('#contenidoC').load('vista/users/nuevoUser.php', function(){

        $('.registroUser .tituloFormulario').html('<i class="fas fa-user-edit"></i> Modificar mi informacion');
        $('.registrarUser').html('<i class="fas fa-save"></i> modificar');
        $('.col.s4.rol').css('display', 'none'); //Ocultamos el campo de rol
        $('.volverUser').addClass('volverMisDatos').removeClass('volverUser');//modificamos la clase del boton volver para poder llamarlo correctamente
        $('.registrarUser').addClass('modificarUser').removeClass('registrarUser'); //modificamos la clase del boton submit a modificarUser

        //Agregamos los campos de usuario y contraseña al formulario
        htmlUserPass = `
        <div class="col s2">
            <div class="switch">
                <label>Modificar Contraseña</label><br>
                <label>
                    <input type="checkbox" class="checkModPass">
                    <span class="lever"></span>
                </label>   
            </div>
        </div>

        <div class="col s2">
            <label for="usuarioUser">Usuario</label>
            <input type="text" class="browser-default" name="usuarioUser" id="usuarioUser">
        </div>

        <div class="col s2">
            <label for="pass1">Contraseña nueva</label>
            <input type="password" class="browser-default" name="pass1" id="pass1"">
        </div>  
        
        <div class="col s2">
            <label for="pass2">Repetir</label>
            <input type="password" class="browser-default" name="pass2" id="pass2"">
        </div>  `;

        $('.UP').html(htmlUserPass);

        $('#pass1, #pass2').prop('disabled', 'disabled').css('background','#eee');
        $(".checkModPass").change(function() {
            if($(this).is(":checked")){
                $('#pass1, #pass2').prop('disabled', false).css('background','transparent').val('');
            }
            else{
                $('#pass1, #pass2').prop('disabled', 'disabled').css('background','#eee').val('');
            }
        })

        $.ajax({
            url: 'scripts/aseguradora.php',
            type: 'post',
            dataType: 'json',
            data: {param:60005},
            success: function(r){
                if(r.success){

                    var genero = r.genero.toLowerCase();

                    $("#nombreUser").val(r.nombre);
                    $("#documentoUser").val(r.documento);
                    $("#nacionalidadUser").val(r.nacionalidad);
                    $('#provinciaUser').val(r.provincia).trigger('change.select2');
                    mostrarLocalidades(r.provincia, r.localidad);
                    //$("#provinciaUser").val(r.provincia);
                    //$("#localidadUser").val(r.localidad);
                    $("#direccionUser").val(r.direccion);
                    $("#nacUser").val(r.fecha_nac);
                    $("#telefonoUser").val(r.telefono);
                    $("#correoUser").val(r.correo);
                    $("#generoUser").val(genero);
                    $("#usuarioUser").val(r.usuario);
                    
                }else{
                    alertify.error('Ocurrio un error al realizar la peticion al servidor');
                }
            }
        }) 
    });
}

function setMisDatosUser()
{
    alertify.prompt('Modificar mis datos', 'Ingrese su contraseña', '',
    function(evt, value){

        if($(".checkModPass").is(":checked")){
            var checkeado = 'si';
        }
        else{
            var checkeado = 'no';
        }
    
        info = [
            checkeado,
            $("#nombreUser").val(),
            $("#documentoUser").val(),
            $("#nacionalidadUser").val(),
            $("#provinciaUser").val(),
            $("#localidadUser").val(),
            $("#direccionUser").val(),
            $("#nacUser").val(),
            $("#telefonoUser").val(),
            $("#correoUser").val(),
            $("#generoUser").val(),
            $("#usuarioUser").val(),
            $("#pass1").val(),
            $("#pass2").val(),
            value
        ]    
    
        $.ajax({
            url: 'scripts/aseguradora.php',
            type: 'post',
            dataType: 'json',
            data: {param:60006, info:info},
            beforeSend: function(){
                $("#submitUser").html('<img src="img/ImgAlt/preload.gif">').show().attr('disabled', 'disabled');
                $(".volverUser").attr('disabled', 'disabled');
            },
            success: function(r){
                if(r.segPass){
                    if(r.diferenteInfo){
                        if(r.userDisp){
                            if(r.update){
                                alertify.success('Informacion modificada correctamente');
                                getMisDatosUser();
                            }else{
                                //Ocurrio un problema al modificar la informacion
                                alertify.error('Ocurrio un problema al modificar la informacion');
                                getMisDatosUser();
                            }
                        }else{
                            //El usuario ya se encuentra en uso
                            alertify.alert('Modificar mis datos', 'El usuario ya se encuentra en uso, ingrese uno diferente.').set(configuracionAlert('ok'));
                            $("#submitUser").html('<i class="fas fa-save"></i> reintentar').show().attr('disabled', false);
                        }
                    }else{
                        //No se realizaron modificaciones
                        alertify.notify('No se realizaron modificaciones');
                        getMisDatosUser();
                    }
                }else{
                    //la contraseña es incorrecta
                    alertify.alert('Modificar mis datos', 'La contraseña es incorrecta, reintente.').set(configuracionAlert('ok'));
                    $("#submitUser").html('<i class="fas fa-save"></i> reintentar').show().attr('disabled', false);
                }
            }
        })

    },
    function(){}).set(configuracionAlert('password','Aceptar'));
}

function getFotoPerfilUser()
{
    $.ajax({
        url: 'scripts/aseguradora.php',
        type:'post',
        dataType:'json',
        data: {param:60007},
        success: function(r){
            if(r.existeImgPerfil=='si'){
                contenido = `<div class="divActualFotoHijo">
                                <img src="`+r.ruta+`" alt="foto de perfil del usuario" class="fotoActualUser" title="Eliminar foto de perfil">
                                <a class="eliFotoUser"> <i class="fas fa-trash" title="Eliminar foto de perfil"></i></a>
                             </div>
                            `;
                $(".divActualFoto").html(contenido);
                $(".configuracionUser").html('<a href="#"> <img src="'+r.ruta+'" alt="foto de perfil del usuario"> </a> ');
                funcionesImgUser();
            }else if(r.existeImgPerfil=='no'){
                $(".divActualFoto").html('<a href="#"> <i class="fas fa-user-circle"></i> </a>');
                $(".configuracionUser").html('<a href="#"> <i class="fas fa-user"></i> </a>');
            }
        }
    })
}

function funcionesImgUser(){
    $(".eliFotoUser").addClass('oculto');

    $(".fotoActualUser").on('mouseover', function(){
        $(".eliFotoUser").removeClass('oculto');
    })
    $(".fotoActualUser").on('mouseout', function(){
        $(".eliFotoUser").addClass('oculto');
        $(".fotoActualUser").removeClass('claseOpaco');
    })
    $(".eliFotoUser").on('mouseover', function(){
        $(".fotoActualUser").addClass('claseOpaco');
        $(this).removeClass('oculto');
    })

    $(".fotoActualUser, .eliFotoUser").on('click', function(){
      eliminarFotoPerfilUser();
    })
}

function eliminarFotoPerfilUser(){  
    alertify.confirm('Eliminar foto de perfil', '¿Dese eliminar su foto de perfil?', 
    function(){
        $.ajax({
            url: 'scripts/aseguradora.php',
            type:'post',
            dataType:'json',
            data: {param:60008},
            success: function(r){
                if(r.eliminarFotoPerfil!=false){
                    alertify.success('Foto de perfil eliminada correctamente');
                    getFotoPerfilUser();
                }else{
                    alertify.error('Ocurrio un problema al eliminar la foto de perfil');
                }
            }
        });
    }, 
    function(){}
    ).set(configuracionAlert('okCancel','Eliminar'));

}

function modFotoUser()
{
    var fotoPerfil = $(".nuevaFoto");

    var infoImagen = fotoPerfil.prop('files')[0];

    var formData = new FormData();
    formData.append('imagen', infoImagen);

    $.ajax({
        url: 'scripts/imagenes/procesarImgPerfil.php',
        type: 'post',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(r){

            if(r==1){
                alertify.success('Foto de perfil actualizada');
                getFotoPerfilUser();
                $('.nuevaFoto').val('');
            }else if(r==10){
                alertify.alert('Cargar imagen', 'Debe seleccionar una imagen').set(configuracionAlert('ok'));
            }else if(r==20){
                alertify.alert('Cargar imagen', 'El formato de la imagen no coincide con los admitidos (.JPG / .PNG / .JPEG)').set(configuracionAlert('ok'));
            }else if(r==30){
                alertify.alert('Cargar imagen', 'El tamaño de la imagen es superior al maximo permitido (5 Mb)').set(configuracionAlert('ok'));
            }else if(r==40){
                alertify.error('No se ha podido registrar la imagen solicitada');
            }else if(r==50){
                alertify.error('Ha ocurrido un problema al realizar la peticion al servidor');
            }else if(r==60){
                alertify.error('Ocurrio un problema interno. Reintente mas tarde.');
                $("#inicio").trigger('click');
            }

        }
    })
}

function tablaListadoUsers()
{
    tablaListadoUsers = $('#tablaListadoUsers').DataTable({

        "language": {
            "zeroRecords": "No se encontraron usuarios registrados",
            "sSearch": "Buscar usuario",
        },

        "ajax": "scripts/tablas/tablaListadoUsuarios.php",
        "scrollY": 370,
        "bPaginate": false,
        "order": [[3, "desc"]],
        "columns": [
            { data: "boton", width: "" },
            { data: "nro", title: "Numero", width: "" },
            { data: "tomador", title: "Nombre", width: "" },
            { data: "rol", title: "Rol", width: "" },
            { data: "estado", title: "Estado", width: "" }
        ],
        //"dom": 'Bfrtilp',
        "dom": 'Bft',

        //Iniciamos la funcion select para las filas
        select: true,
        "select": {
            style: 'single',
            selector: 'td:first-child',
            "value": '4'
        },
         //Agregamos el checkbox en el td vacio de la tabla
        "aoColumnDefs": [{
            orderable: false,
            className: 'selectUser select-checkbox',
            targets: 0
        }],

        //Agregamos los botones de funcionalidad de usuarios
        "buttons":
        [
            {
                text: '<i class="fas fa-user-plus"></i> Nuevo',
                className: 'green btnNuevoUser'
            },
            {
                text: '<i class="fas fa-users-slash"></i> Bajas',
                className: 'blue btnListadoBajas'
            },
            {
                text: '<i class="fas fa-user-slash"></i> Baja',
                className: 'red btnBajaUser'
            },
            {
                text: '<i class="fas fa-user-shield"></i> Rol',
                className: 'yellow btnModRolUser'
            },
            {
                text: '<i class="fas fa-question-circle"></i> Info',
                className: 'blue btnInfoUser'
            },
            {
                text: '<a> Reenviar datos de acceso </a> ',
                className: 'btnReenviarInfoAccesoUser'
            }
        ]
    });

    //Cada vez que seleccionamos una fila, mostramos los botones
    tablaListadoUsers.on('select', function(){

        $(".btnNuevoUser, .btnListadoBajas").css('display', 'none')

        $(document).off('click', '.selectUser')
        $(document).on('click', '.selectUser', function(){
            idUser = $(this).children().attr('value')
        })

        $(".btnBajaUser, .btnModRolUser, .btnInfoUser, .btnReenviarInfoAccesoUser").css('display', 'initial');

        $(".btnBajaUser").off('click');
        $(".btnBajaUser").on('click', function () {
            bajaUser(idUser)
        });

        $(".btnModRolUser").off('click');
        $(".btnModRolUser").on('click', function () {
            traerInfoUser(idUser)
        });

        $(".btnInfoUser").off('click');
        $(".btnInfoUser").on('click', function () {
            infoUser(idUser)
        });

        $(".btnReenviarInfoAccesoUser").off('click');
        $(".btnReenviarInfoAccesoUser").on('click', function(){
            generarReenvioDatosUser(idUser);
        });

    })

    //Cada vez que deseleccionamos una fila, ocultamos los botones
    tablaListadoUsers.on('deselect', function () {
        $(".btnBajaUser, .btnModRolUser, .btnInfoUser, .btnReenviarInfoAccesoUser").css('display', 'none')
        $(".btnNuevoUser, .btnListadoBajas").css('display', 'inline')
    })

    return tablaListadoUsers
}

function listadoUserBaja()
{
    $("#modalListadoUserBaja").modal('open')

    tablaListadoUserBaja = $('#listadoBajaUser').DataTable({

        "language": {
            "zeroRecords": "No se encontraron usuarios dados de baja",
            "sSearch": "Buscar usuario",
        },

        "ajax": "scripts/tablas/tablaListadoUserBaja.php",
        "scrollY": 370,
        "scrollCollapse": true,
        "dom": 'ft',
        "order": [[3, "desc"]],
        "columns": [
            { data: "nro", title: "Numero", width: "" },
            { data: "tomador", title: "Nombre", width: "" },
            { data: "rol", title: "Rol", width: "" },
            { data: "estado", title: "Estado", width: "" },
            { data: "boton", title: "Reactivar", width: "" }
        ]
    });

    $(".cerrarModalListadoUsBa").off('click');
    $(".cerrarModalListadoUsBa").on('click', function(){
        $("#modalListadoUserBaja").modal('close')
        $('#listadoBajaUser').DataTable().destroy();
    })

    return tablaListadoUserBaja
}
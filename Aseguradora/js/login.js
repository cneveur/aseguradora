jQuery(document).ready(function(){

    listadoSucursalesSelect();

    $('input, select').addClass('browser-default');

    $("select").on('change', function(){
       $(this).css('color', '#fff');
    });

    $("input, select").on('click', function(){
        $(this).siblings('i').css('color', '#f5f5f5');
    });
    
    $("input, select").on('focusout', function(){
        $(this).siblings('i').css('color', '#757575');
    });

    validarLogin()

     //Salimos del sistema
    $(".logout").off('click');
    $(".logout").on('click', function(){
        logout();
    })

})

function validarLogin()
{    
    $("#formLogin").validate({
        rules: {
            "u": {required: true},
            "p": {required: true},
            "s": {required: true}
        },
        messages: {
            "u": {required: "Debe ingresar un Usuario"},
            "p": {required: "Debe ingresar una Contraseña"},
            "s": {required: "Debe seleccionar una Sucursal"}
        },
        highlight: function (element) {
            $(element).addClass('claseError');
            $(element).siblings('i').addClass('claseErrorIcono');
        },
        unhighlight: function (element){
            $(element).removeClass('claseError');
            $(element).siblings('i').removeClass('claseErrorIcono');
        },

        errorElement: 'li',
        errorLabelContainer: '#errorLogin',

        submitHandler: function(){
            ingresarSistema()
        }
    })
}

function ingresarSistema()
{
    var info = [$("#u").val(), $("#p").val(), $("#selectSuc").val()];

    $(".mjeLogin").html('La informacion ingresada no coincide con nuestros registros').slideUp(200);

    $.ajax({
        url: 'scripts/aseguradora.php',
		type: 'POST',
		dataType: 'json',
        data: {param:90000, info:info},
        beforeSend: function(){
            $("#envFormLogin").html('<img src="img/ImgAlt/preloadAzul.gif">').show().attr('disabled', 'disabled')
            $(".btn-large").css('padding', '3px')
        },
        success: function(r){
            function accion()
            {
                $(".btn-large").css('padding', '9px')
                if(r.existe){

                    if(r.passCorr=='si'){

                        if(r.sucDisp){

                            if(r.activo=='si'){
    
                                $("#envFormLogin").html('Logueado').show().attr('disabled', 'disabled')

                                location.href = 'indexMaqueta.php'
                                // if(r.rol == 'admin'){
                                //     location.href = 'maquetaAdmin.php'
                                // }else if(r.rol == 'user'){
                                //     location.href = 'maquetaUser.php'
                                // }
        
                            }else if(r.activo=='no'){
        
                                $(".mjeLogin").html('Usted no tiene permiso para acceder al sistema permanentemente').slideDown(200)
                                $("#envFormLogin").html('<span> Reintentar <i class="fas fa-redo-alt"></i> </span>').show().attr('disabled', false)
                                setTimeout(() => { $(".mjeLogin").slideUp(200) }, 3000);
                            }
                        }else if(r.sucDisp==false){
                            $(".mjeLogin").html('La Sucursal no se encuentra registrada en el sistema').slideDown(200)
                            $("#envFormLogin").html('<span> Reintentar <i class="fas fa-redo-alt"></i> </span>').show().attr('disabled', false)
                            setTimeout(() => { $(".mjeLogin").slideUp(200) }, 3000);
                        }

                    }else if(r.passCorr=='no'){
                        $(".mjeLogin").html('La contraseña ingresada es incorrecta').slideDown(200)
                        $("#envFormLogin").html('<span> Reintentar <i class="fas fa-redo-alt"></i> </span>').show().attr('disabled', false)
                        setTimeout(() => { $(".mjeLogin").slideUp(200) }, 3000);
                    }
                }else if(r.existe==false){
                    $(".mjeLogin").html('El Usuario o Correo ingresado no coincide con nuestros registros').slideDown(200)
                    $("#envFormLogin").html('<span> Reintentar <i class="fas fa-redo-alt"></i> </span>').show().attr('disabled', false)
                    setTimeout(() => { $(".mjeLogin").slideUp(200) }, 3000);
                }
            }
            setTimeout(() => { accion() }, 2000);
        }
    })
}

function logout(){

    var nombre = $(".nombreUser").html();

    texto = nombre+', ¿Deseas salir del sistema?'

    alertify.confirm('Cerrar sesion', texto,
	 function(){

        $.ajax({
            url: 'scripts/aseguradora.php',
            type: 'POST',
            dataType: 'json',
            data: {param:90001},
            success: function(r){
                if(r.success){
                    location.href = 'index.php';
                }
            }
               
        });
	 },
	 function(){}
	 ).set(configuracionAlert('okCancel','Si'));
}

function listadoSucursalesSelect(){
    $("#selectSuc").empty();
    $("#selectSuc").append('<option value="0" selected disabled>Sucursal</option>');
    $.ajax({
        url: 'scripts/aseguradora.php',
		type: 'POST',
		dataType: 'json',
        data: {param:70004},
        success: function(r){
            if(r.success){
                $.each(r.data, function(key, val){
                    $("#selectSuc").append('<option value="'+val.id+'">'+val.nombre+' - '+val.iden+'</option>');
                });
            }
        }
    })
}
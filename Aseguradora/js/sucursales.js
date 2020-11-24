jQuery(document).ready(function($){

    $('input, select').addClass('browser-default');

    $(".btnAgregarSucursal").off('click');
    $(".btnAgregarSucursal").on('click', function(){
        $("#contenidoC").load('vista/sucursales/nuevaSucursal.php', function(){
            validarSucursal();
        });
    });

    $(".volverSucursal").off('click');
    $(".volverSucursal").on('click', function(){
        $('#contenidoC').load('vista/sucursales/administrarSucursales.php', function(){
            tablaAdminSucursales();
        });
    });

    $("#cpSucursal, #localidadSucursal, #provinciaSucursal").select2();

    $("#provinciaSucursal").on('change', function(){
        idProv = $("#provinciaSucursal").val();
        mostrarCp(idProv);
    })

    $("#cpSucursal").on('change', function(){
        cp = $("#cpSucursal").val();
        mostrarLocalidades(cp);
    });

    $(document).off('click', '#verSucursal');
    $(document).on('click', '#verSucursal', function(){
        idSuc = $(this).attr('value');
        verInfoSucursal(idSuc);
    });

    $(document).off('click', '#eliSucursal');
    $(document).on('click', '#eliSucursal', function(){
        idSuc = $(this).attr('value');
        eliminarSucursal(idSuc);
    });


    $(document).off('click', '#modSucursal');
    $(document).on('click', '#modSucursal', function(){

        idSuc = $(this).attr('value');

        var info = traerInfoSucursal(idSuc);

        $('#contenidoC').load('vista/sucursales/modificarSucursal.php', function(){

            validarSucursal(idSuc);

            $('.sucursalNro').html(info['nombre']+' - '+info['iden']);

            $('#nombreSucursal').val(info['nombre']);
            $('#cuitSucursal').val(info['cuit']);
            $('#direccionSucursal').val(info['direccion']);
            $('#provinciaSucursal').val(info['provinciaId']);
            mostrarCp(info['provinciaId'], info['cp']);
            mostrarLocalidades(info['cp'], info['localidadId']);
        });
    });
    
});


function mostrarCp(idProv, cpSelect){

    $("#cpSucursal").empty();

     $.ajax({
        url: 'scripts/aseguradora.php',
        type: 'post',
        dataType: 'json',
        data: {param:80002, id:idProv},
        success: function(r){
            if(r!=null){
                $("#cpSucursal").append('<option value="0" selected disabled>Seleccione</option>');
                $.each(r.data, function(key, val){
                    if(cpSelect==val.cpNum){
                        $("#cpSucursal").append('<option value="'+val.cpNum+'" selected>'+val.cpNum+'</option>')
                    }else{
                        $("#cpSucursal").append('<option value="'+val.cpNum+'">'+val.cpNum+'</option>')
                    }
                })
            }else{
                alertify.error('Ocurrio un error al mostrar las localidades')
            }
        }
    })
}

function mostrarLocalidades(cp, selectLoc){

    $("#localidadSucursal").empty();

    $.ajax({
        url: 'scripts/aseguradora.php',
        type: 'post',
        dataType: 'json',
        data: {param:80000, cp:cp},
        success: function(r){
            if(r!=null){
                $("#localidadSucursal").append('<option value="0" selected disabled>Seleccione</option>')
                $.each(r.data, function(key, val){
                    if(selectLoc==val.localidadId){
                        $("#localidadSucursal").append('<option value="'+val.localidadId+'" selected>'+val.localidadNombre+'</option>')
                    }else{
                        $("#localidadSucursal").append('<option value="'+val.localidadId+'">'+val.localidadNombre+'</option>')
                    }
                })
            }else{
                alertify.error('Ocurrio un error al mostrar las localidades')
            }
        }
    })
}

function validarSucursal(data){

    var valNombre = /^[a-zA-Z ]{5,30}$/;

    $.validator.addMethod("validarNombre", function (value, element) {
        return this.optional(element) || valNombre.test(value)
    })

    $("#registroSucursal").validate({
        rules: {
            "nombreSucursal":    {required: true, minlength: [5], validarNombre: true},
            "cuitSucursal":      {required: true, rangelength: [10, 15]},
            "direccionSucursal": {required: true, minlength: [5]},
            "provinciaSucursal": {required: true, rangelength: [1, 24]},
            "cpSucursal":        {required: true, rangelength: [1, 22963]},
            "localidadSucursal": {required: true, rangelength: [1, 22963]}
        },
        messages: {
            "nombreSucursal":    {required: "Debe ingresar el Nombre",           minlength: "El Nombre no puede ser tan corto", validarNombre: "El Nombre no puede contener numeros"},
            "cuitSucursal":      {required: "Debe ingresar el Cuit",             rangelength: "El Cuit ingresado no es valido"},
            "direccionSucursal": {required: "Debe ingresar la Direccion",        minlength: "La informacion en la Direccion es muy limitada"},
            "provinciaSucursal": {required: "Debe seleccionar una Provincia",    rangelength: "La Provincia es incorrecta"},
            "cpSucursal":        {required: "Debe seleccionar un Codigo Postal", rangelength: "El Codigo Postal es incorrecto"},
            "localidadSucursal": {required: "Debe seleccionar una Localidad",    rangelength: "La Localidad es incorrecta"}
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
        errorLabelContainer: '#mensajeSucursal',

        submitHandler: function(){

            var id = $(".btnSubmitSucursal").attr('id');

            if(id=='submitSucursal'){
                registrarSucursal();
            }else if(id=='submitModificarSucursal'){
                modificarSucursal(data);
            }
           
        }
    })
}

function registrarSucursal(){
    info = [
        $("#nombreSucursal").val(),
        $("#cuitSucursal").val(),
        $("#direccionSucursal").val(),
        $("#provinciaSucursal").val(),
        $("#cpSucursal").val(),
        $("#localidadSucursal").val()
    ]

    $.ajax({
        url: 'scripts/aseguradora.php',
        type: 'post',
        dataType: 'json',
        data: {param:70000, data:info},
        success: function(r){
            if(r.success){
                $("#nombreSucursal").val(''),
                $("#cuitSucursal").val(''),
                $("#direccionSucursal").val(''),
                $("#provinciaSucursal").val('n'),
                mostrarCp(null, 0);
                mostrarLocalidades(null, 0);
                $('#contenidoC').load('vista/sucursales/administrarSucursales.php', function(){
                    tablaAdminSucursales();
                });
                alertify.success('Sucursal registrada con exito');
            }else{
                alertify.error('Ocurrio un error al mostrar las localidades');
            }
        }
    });
}

function eliminarSucursal(idSuc)
{
    alertify.confirm('Eliminar sucursal', '¿Esta seguro que desea eliminar esta sucursal? <br> Ya no estara disponible para el inicio de sesion',
    function(){
        $.ajax({
            url: 'scripts/aseguradora.php',
            type: 'post',
            dataType: 'json',
            data: {param:70001, idSuc:idSuc},
            success: function(r){
                if(r.success){
                    tablaAdminSucursales.ajax.reload();
                    alertify.success('Sucursal eliminada del sistema');
                }else{
                    alertify.error('Ocurrio un error al realizar la peticion');
                }
            }
        })
    },
    function(){}
    ).set(configuracionAlert('okCancel','Dar de baja'));
}

function traerInfoSucursal(idSuc){

    $.ajax({
        url: 'scripts/aseguradora.php',
        type: 'post',
        dataType: 'json',
        async: false, //Usamos para generar una peticion no asincrona
        data: {param:70002, idSuc:idSuc},
        success: function(r){
            if(r.success){
                info = r.info;
            }else{
                alertify.error('Ocurrio un error al realizar la peticion');
            }
        }
    });

    return info;
}

function modificarSucursal(idSuc){

    info = [
        $("#nombreSucursal").val(),
        $("#cuitSucursal").val(),
        $("#direccionSucursal").val(),
        $("#provinciaSucursal").val(),
        $("#cpSucursal").val(),
        $("#localidadSucursal").val(),
        idSuc
    ]

    $.ajax({
        url: 'scripts/aseguradora.php',
        type: 'post',
        dataType: 'json',
        data: {param:70003, data:info},
        success: function(r){
            if(r.repetido){
                alertify.notify('No se realizaron modificaciones');
            }else{
                if(r.success){
                    alertify.success('Informacion actualizada con exito');
                }else{
                    alertify.error('Ocurrio un error al realizar la peticion');
                }
            }

            /*Limpio los campos*/
            $("#nombreSucursal").val(''),
            $("#cuitSucursal").val(''),
            $("#direccionSucursal").val(''),
            $("#provinciaSucursal").val('n'),
            mostrarCp(null, 0);
            mostrarLocalidades(null, 0);

            $('#contenidoC').load('vista/sucursales/administrarSucursales.php', function(){
                tablaAdminSucursales();
            });
            
        }
    });
}

function verInfoSucursal(idSuc){

    info = traerInfoSucursal(idSuc);
    
    $('#contenidoC').load('vista/sucursales/verInfoSucursal.php', function(){

        $(".sucursalNro").html(info['nombre']);

        $(".nomb").html(info['nombre']);
        $(".iden").html(info['iden']);
        $(".cuit").html(info['cuit']);
        $(".dir").html(info['direccion']);
        $(".cp").html(info['cp']);
        $(".pro").html(info['provincia']);
        $(".loc").html(info['localidad']);

    });
}

function tablaAdminSucursales(){

    tablaAdminSucursales = $('#admSucursalesTabla').DataTable({
		"language": {
			"zeroRecords": "No se encontraron Sucursales registradas",
        	"sSearch": "Buscar",
        },
        "ajax": "scripts/tablas/tablaAdministrarSucursales.php",    
      
        "scrollY": 370,
        "bPaginate": false,
        "dom": 'Bft',
        "order": [],

        "columns": [                    
          {data:"iden", title: "Identificador" ,width:""},
          {data:"nom",  title: "Nombre"        ,width:""},
          {data:"dir",  title: "Direccion"     ,width:""},
          {data:"cp",   title: "Codigo Postal" ,width:""},
          {data:"ubic", title: "Ubicacion"     ,width:""},
          {data:"acc",  title: "Acciones"      ,width:"20%"}
        ],

         //Agregamos los botones de funcionalidad de usuarios
         "buttons":
         [
             {
                 text: '<i class="fas fa-plus-circle"></i> Agregar',
                 className: 'green btnAgregarSucursal'
             }
         ]
	});

	return tablaAdminSucursales;
}
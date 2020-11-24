/* Fichero del menu con las funcionalidades de javascript, que la unica funcion que cumple, es al apretar los 
botones del menú cargar con el evento load, un fichero de php que hace referencia a las vistas de ese apartado*/

jQuery(document).ready(function($){

  $('input, select').addClass('browser-default');

  //Funcionalidad al presionar el boton de configuracion de usuario
  $(".configuracionUser").on('click', function(){
    $(".menuUser").slideToggle(200);    
  })

  $(".contenido, .sidebar").click(function() {
    $(".menuUser").slideUp(200);
  });

  //Funcionamiento del menu
  $('.opMen').off('click');
  $('.opMen').on('click', function(){

    $('.sub-ul-li-a').removeClass('sub-activado');//desactivamos el submenu

    //Cada vez que abrimos y cerramos el menu, se agrega o se quita una flecha
    f = $(this).children('a').children('.flecha').children();

    $(this).children('.sub').slideToggle(200, function(){
      if(f.hasClass('fa-angle-right')){
        f.removeClass('fas fa-angle-right');
        f.addClass('fas fa-angle-down');
      }else{
        f.removeClass('fas fa-angle-down');
        f.addClass('fas fa-angle-right');
      }
    });

    $(this).children('a').addClass('active');//activamos la opcion del menu

    //Cuando seleccionamos otra opcion de menu, cerramos el ya abierto
    var Grupo = $(this).parent();
    var posicion = $(this).index();
    Grupo.children().each(function(index){
       if (index != posicion){
          $(this).children(".sub").slideUp(200);
          $(this).children('a').removeClass('active');
          $(this).children('a').children('.flecha').children().removeClass('fas fa-angle-right');
          $(this).children('a').children('.flecha').children().addClass('fas fa-angle-down');
       }
    });

  })
  $('.sub-ul').click(function(e){
    e.stopPropagation();
  })


  //Si presionamos el boton de inicio
  $('#inicio').on('click', function(){
    $("#divTit").css('display', 'block');
    $("#tituloCont").html('Inicio');
    var nombre = $(".nombreUser").html();
    $("#divSubTit").html('Bienvenido '+nombre);
    $('#contenidoC').html('');
  })


  //Funcionamiento del submenu
  $('.sub-ul-li-a').off('click');
  $('.sub-ul-li-a').on('click', function(){

    $(this).addClass('sub-activado');

    //recorremos los elementos para aplicar y remover las clases de activo
    var grup = $(this).parent().parent();
    var pos = $(this).parent().index();
    grup.children().each(function(index){
       if (index != pos){
          $(this).children().removeClass('sub-activado');
       }
    });

    var nombreId = $(this).attr('id');
    switch(nombreId)
    {
      case 'nuevaPoliza':
        var titulo = 'emision de poliza';
        var subtitulo = '';
        $("#contenidoC").load('vista/poliza/elementosPoliza/formulario_poliza.php', function(){
          
          //Creamos aca el change de marca y cp para usarlo solo una vez y evitar anidacion de funciones
          $('#select_marca').on('change', function(){
            llenarModelo($('#select_marca').val(), 0);
          });
          $("#codigoPostal").on('change', function(){
            mostrarLocalidades($("#codigoPostal").val(), 0);
          });

        });
        
        break;

      case 'verPoliza':
        var titulo = 'consultar polizas';
        var subtitulo = ''; 
        $('#contenidoC').load('vista/poliza/elementosPoliza/consultar_polizas.php', function(){
          tablaConsultarPoliza();
        });      
        break;

      case 'renovarPoliza':
        var titulo = 'renovacion de polizas';
        var subtitulo = 'Listado de polizas vencidas con su respectiva vigencia finalizada';
        $('#contenidoC').load('vista/poliza/elementosPoliza/renovar_poliza.php', function(){
          tablaRenovarPoliza();
        });
        break;

      case 'emitirEndoso':
        var titulo = 'generar endoso';
        var subtitulo = '';
        $('#contenidoC').load('vista/endosos/elementosEndoso/endosos.php', function(){
          tablaGenerarEndoso();
        });
        break;

      case 'verEndoso':
        var titulo = 'consultar endosos';
        var subtitulo = '';
        $('#contenidoC').load('vista/endosos/elementosEndoso/listadoEndosos.php', function(){
          tablaListadoEndosos();
        });
        break;

      case 'nuevoSiniestro':
        var titulo = 'emitir nuevo siniestro';
        var subtitulo = '';
        $('#contenidoC').load('vista/siniestro/cargarSiniestro.php', function(){
          listarPolizas();
          //Creamos aca el change de provincia con localidades para usarlo solo una vez y evitar anidacion
          $("#provinciaAcc").on('change', function(){
            mostrarLocalidadesSin($("#provinciaAcc").val(), 0);
          });
        });
        break;

      case 'adminSiniestro':
        var titulo = 'administrar siniestros';
        var subtitulo = '';
        $('#contenidoC').load('vista/siniestro/elementosSiniestro/administrarSiniestro.php', function(){
          tablaAdministrarSiniestro();
        });
        break;

      case 'verSiniestro':
        var titulo = 'consultar siniestros';
        var subtitulo = '';
        $('#contenidoC').load('vista/siniestro/elementosSiniestro/consultarSiniestro.php', function(){
          tablaConsultarSiniestro();
        });
        break;

      case 'nuevoTomador1':
        var titulo = 'registrar nuevo tomador';
        var subtitulo = '';
        $('#contenidoC').load('vista/tomador/registrarTomador.php');
        break;

      case 'adminTomador':
        var titulo = 'administrar tomadores';
        var subtitulo = '';
        $('#contenidoC').load('vista/tomador/adminTomador.php', function(){
          tablaModificarDatosTomadores();
        });
        break;

      case 'nuevaCuota':
        var titulo = 'establecer cuotas';
        var subtitulo = 'Listado de polizas las cuales se han configurado o no los parametros de pago.';
        $('#contenidoC').load('vista/pagos/emisionPago.php', function(){
          tablaCondicionesCuotas();
        });
        break;

      case 'facturarPago':
        var titulo = 'facturas de cuotas';
        var subtitulo = 'Listado de pagos registrados';
        $('#contenidoC').load('vista/pagos/facturarPago.php', function(){
          tablaFacturarPago();
        });
        break;

      case 'AdmUsers':
        var titulo = 'Administrar usuarios';
        var subtitulo = '';
        $('#contenidoC').load('vista/users/administrarUsers.php', function(){
          tablaListadoUsers();
        });
        break;


      case 'AdmSucursales':
        var titulo = 'Administrar sucursales';
        var subtitulo = '';
        $('#contenidoC').load('vista/sucursales/administrarSucursales.php', function(){
          tablaAdminSucursales();
        });
        break;
    }

    $("#divTit").css('display', 'block');
    $("#tituloCont").html(titulo);
    $("#divSubTit").html(subtitulo);

  })

});
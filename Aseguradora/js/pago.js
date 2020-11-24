jQuery(document).ready(function($) {

	funcionalidadesPagos();
	actualizarEstadoCuota();

	$(document).off('click', '.btnAdminPago');
	$(document).on('click', '.btnAdminPago', function(){
		var idPol = $(this).attr('value');
		adminPago(idPol);
	});

	$(document).off('click', '#verCuotas');
	$(document).on('click', '#verCuotas', function(){
		mostrarCuotas();
	});

	$("#cancelarCondPago").off('click');
	$("#cancelarCondPago").on('click', function(){
		$("#primaTotal, #primaMensual, #diaCobrar, #fechaCobrar").val('');
		$("#cantCuotas").val(1);
		condicionesCuotasTabla.ajax.reload();
	});

	$(document).off('click', '.facturarPago');
	$(document).on('click', '.facturarPago', function(){
		var pagoId = $(this).attr('value');
		facturarPago(pagoId);
	});

	$(document).off('click', '.verFactPago');
	$(document).on('click', '.verFactPago', function(){
		var pagoId = $(this).attr('value');
		mostrarListadoRecibos(pagoId);
	});

	$(".btnAtrasListCu").off('click');
	$(".btnAtrasListCu").on('click', function(){
		$('#contenidoC').load('vista/pagos/facturarPago.php', function(){
			tablaFacturarPago();
		});
	});

	$(".btnEnviarListCu").off('click');
	$(".btnEnviarListCu").on('click', function(){
		validarEmail(1, null);
	});

	$(".btnDescargarListCu").off('click');
	$(".btnDescargarListCu").on('click', function(){
		envDescListRecibos('descargar');
	});

	$("#actualizarListCuotas").off('click');
	$("#actualizarListCuotas").on('click', function(){
		$(this).html('<img src="img/ImgAlt/preloadArrowBlue.gif">'+'actualizar').attr('disabled', 'disabled');
		setTimeout(() => {
			$(this).html('<i class="fas fa-sync-alt"></i> actualizar').attr('disabled', false);
			actualizarEstadoCuota();
			listadoCuotasFacturaTabla.ajax.reload();
		}, 1000);
	});



});

function generarFormularioPago(param, infoRen)
{
	$('.tabs').tabs('select','test5');
	boton = '<button type="submit" class="btn btn-flat green" id="cargar_datos"> grabar poliza <i class="fas fa-save"></i> </button>';
	$(".btnDivPago").append(boton);

	//Si el parametro es 1, generamos la informacion con los datos pasados por parametro
	if(param==1){

		var mesesVigPol = $("#tipVig").val();
		var inVigPol = $("#vig_ini").val();
		var sumAs = infoRen[1].replace(/\./g,'');

		$("#divPoliza").css('display', 'none');
		$("#cuotasForm").css('display', 'block');

		$('.atrRenPa').off('click');
		$('.atrRenPa').on('click', function(e){
			e.preventDefault();
			$("#divPoliza").css('display', 'block');
			$("#cuotasForm").css('display', 'none');

			$("#primaTotal, #cantCuotas, #primaMensual").val(''); 
			$("#diaCobrar, #tipoPag").val(0);

			$("#mensajePago").css('display', 'none');

			$("#primaTotal, #cantCuotas, #primaMensual, #diaCobrar, #tipoPag").css({
				border: '1px solid #ccc',
				background: 'transparent'
			});
		})

		var idPoliza = infoRen[0];

		validarPago(3, idPoliza);

	}else if(param==2){
		var mesesVigPol = $("#tipVig").val();
		var inVigPol 	= $("#vig_ini").val();
		var sumAs 	    = $("#suma_asegurada").val().replace(/\./g,'');

		validarPago(1);
	}

	/*Operamos con la informacion obtenida */

	var cantCuotas   = $("#cantCuotas").val();
	var primaMensual = $("#primaMensual").val();
	var diaCobrar    = $("#diaCobrar").val();
	var tipoPag      = $("#tipoPag").val();

	var primaTotal = ((2*sumAs)/100); //Calculamos la prima total (3% de la sumaAs)
    var primaTotal = (primaTotal/12)*mesesVigPol; //(Asignamos el costo en relacion a la duracion de la vigencia)
	$("#primaTotal").val(Math.trunc(primaTotal)); //Asignamos en el campo el valor de la prima total calculada por el sistema
	$("#cantCuotas").empty();
	$("#cantCuotas").append('<option value="0" selected disabled>Seleccione</option>');
	for (var i=2; i<=mesesVigPol; i++){ //El maximo de cuotas disponibles sera el mismo valor de meses de vig.
		var numero = mesesVigPol % i; //Calculamos el resto de cantidad de meses de vig dividido cantCuotas.
		if(numero==0){
			$("#cantCuotas").append('<option value="'+i+'">' + i + '</option>'); //Asignamos el valor a las opciones si el resto es 0
		}
	}

	//Al modificar las cuotas se asigna el valor de las primas mensuales
	$("#cantCuotas").off('change');
	$("#cantCuotas").on('change', function(){
		var cantCuotas = $("#cantCuotas").val();
		var primaMensual = Math.trunc(primaTotal/cantCuotas);

		$("#primaMensual").val(primaMensual);
	});

	//Al modificar la prima por defecto, al modificar las cuotas se asigna el valor de las primas mens.
	$("#primaTotal").on('keyup');
	$("#primaTotal").on('keyup', function(){ //Cada vez que se modifique la prima, obtendremos el valor

		var primaTotal = $("#primaTotal").val();
		primaTotal = primaTotal.replace(/\./g,''); 
		$("#cantCuotas").val(1);
		$("#primaMensual").val('');

		$("#cantCuotas").off('change');
		$("#cantCuotas").on('change', function(){
			var cantCuotas = $("#cantCuotas").val();
			var primaMensual = Math.trunc(primaTotal/cantCuotas);
			$("#primaMensual").val(primaMensual);
		});
	});

	//Llenamos el select de dia a cobrar con 28 opciones
	$("#diaCobrar").empty().append('<option value="0" selected disabled>Seleccione</option>');
	for (var i=1; i<=28; i++) {
		$("#diaCobrar").append('<option value="'+ i +'">'+ i +'</option>');
	}
}

function adminPago(idPol)
{
	$("#condicionesCuotasModal").modal('open');

	$(".datosPol").css('display', 'block');
	$(".tipoPag").remove();

	texto = "Con esta operacion se modificara el pago ya registrado con anterioridad, dependiendo la fecha en que realize este tipo de operaciones puede verse afectado cualquier transaccion en el sistema, como cuotas en deuda o fechas obsoletas, se recomienda desde un principio establecer las condiciones de pago ni bien se registre la poliza y no volver a modificar esta informacion, y en caso de llevarlo a cabo, verificar detenidamente la informacion de pago luego.";

	alertify.alert('Modificar datos de pago', texto).set(configuracionAlert('ok'));

	$("#tituloModalPago").html("modificar condiciones de pago");
	$(".nombreBoton").html('modificar');
	$(".divVerCuotas").css('display', 'block');
	$("#primaTotal, #primaMensual, #diaCobrar, #fechaCobrar").val('');
	$("#cantCuotas").val(1);

	$("#cantCuotas").empty();
	
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'post',
		dataType: 'json',
		data: {param:50000, idpoliza:idPol},
		success: function(r){
			if(r.data['success'])
			{
				$("#primaMensual").prop('disabled', true);
				$("#idPago").val(r.data['pagoId']);

				$("#numPoliza").html 	  (r.data['polizaNum']);
				$("#emision").html 		  (r.data['polizaEmis']);
				$("#horaEmision").html 	  (r.data['polizaEmisHora']);
				$("#inicioVig").html 	  (r.data['polizaInVig']);
				$("#inicioHora").html 	  (r.data['polizaHorInVig']);
				$("#finVig").html 		  (r.data['polizaFinVig']);
				$("#finHora").html 		  (r.data['polizaHorInVig']);
				$("#mesesVig").html 	  (r.data['polizaMesesVig']);
				$("#tipVig").val 	  	  (r.data['polizaMesesVig']);
				$("#marcaP").html    	  (r.data['marca']);
				$("#modeloP").html    	  (r.data['modelo']);
				$("#motorP").html    	  (r.data['motor']);
				$("#localidadP").html 	  (r.data['vehiculoLocalidad']);
				$("#provinciaP").html 	  (r.data['vehiculoProvincia']);
				$("#cpP").html 			  (r.data['cpVehiculo']);
				$("#anio").html 		  (r.data['anio']);
				$("#ceroKm").html 		  (r.data['ceroKm']);
				$("#kms").html 			  (r.data['kilometros']);
				$("#eGas").html 		  (r.data['eGas']);
				$("#coberturaDesc").html  (r.data['coberturaDesc']);
				$("#coberturaNombre").html(r.data['cobertura']);
				$("#coberturaAd").html    (r.data['coberturaAd']);
				$("#sumaAs").html 		  (r.data['sumaAsegurada']);

				$("#primaTotal").val(r.data['primaTotal'].replace(/\./g,'')); //Asignamos el valor de la prima calculada por el sistema en el campo

				var prima = $("#primaTotal").val(); //Obtenemos el valor de esa prima
				prima = prima.replace(/\./g,''); 

				$("#primaMensual").val(prima); //Asignamos por defecto el valor de una cuota.
				$("#cantCuotas").append('<option value="0" disabled>Seleccione</option>');
				for (var i = 2; i <= r.data['polizaMesesVig']; i++){ //El maximo de cuotas disponibles sera el mismo valor de meses de vig.
					var numero = r.data['polizaMesesVig'] % i; //Calculamos el resto de cantidad de meses de vig dividido cantCuotas.
					if(numero==0){
						$("#cantCuotas").append('<option value="'+i+'">' + i + '</option>'); //Asignamos el valor a las opciones si el resto es 0
					}
				}

				//Al modificar las cuotas se asigna el valor de las primas mensuales
				$("#cantCuotas").off('change');
				$("#cantCuotas").on('change', function(){
					var cantCuotas = $("#cantCuotas").val();
					var primaMensual = Math.trunc(prima/cantCuotas);
					$("#primaMensual").val(primaMensual);
				});

				//Al modificar la prima por defecto, al modificar las cuotas se asigna el valor de las primas mens.
				$("#primaTotal").on('keyup');
				$("#primaTotal").on('keyup', function(){ //Cada vez que se modifique la prima, obtendremos el valor

					var prima = $("#primaTotal").val();
					prima = prima.replace(/\./g,''); 
					$("#cantCuotas").val(1);
					$("#primaMensual").val(prima);


					$("#cantCuotas").off('change');
					$("#cantCuotas").on('change', function(){
						var cantCuotas = $("#cantCuotas").val();
						var primaMensual = Math.trunc(prima/cantCuotas);
						$("#primaMensual").val(primaMensual);

						if(cantCuotas==1){
							$("#diaCob").css("display", "none");
							$("#fechaCob").css("display", "block");
						}else{
							$("#diaCob").css('display', 'block');
							$("#fechaCob").css("display", "none");
						}
					});
				});

				//Completamos el select
				var diaSelect = r.data['polizaInVig'].substr(0,2);

				$("#diaCobrar").empty();
				for (var i = 1; i <= 28; i++) {
					$("#diaCobrar").append('<option value="'+ i +'">'+ i +'</option>');
				}

				//Hacemos otra peticion AJAX para completar los datos
				mostrarInfoPago(idPol);
				validarPago(2, null);
			}
		}
	});
}

function mostrarInfoPago(idPol)
{
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'POST',
		dataType: 'json',
		data: {param: 50002, idPol:idPol},
		success: function(r){
			if(r.success){
				var fecha = r.diaCobrar.substr(0,2);
				if(fecha.substr(0,1) == 0){
					fecha = fecha.substr(1);
				}
				$("#primaTotal").val(r.primaTotal);
				$("#primaMensual").val(r.primaMensual);
				$("#cantCuotas").val(r.cantCuotas).trigger('change');
				$("#diaCobrar").val(fecha).trigger('change');
			}else{
				alertify.error('Ocurrio un problema al mostrar la informacion de pago');
			}
		}
	})
}

function validarPago(param, datos)
{

	var valFecha = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
	$.validator.addMethod("validarFecha", function(value, element) {
			return this.optional(element) || valFecha.test(value) ;
	});

	numeroCorr = /^[1-9]{1}[0-9]{3,5}$|[1-9]{1}[0-9]{1}\.[0-9]{3}$|[1-6]{1}[0-9]{2}\.[0-9]{3}$|[1-9]{1}\.[0-9]{3}\.[0-9]{3}$/;
	$.validator.addMethod('numeroCorr', function(value, element){
		return this.optional(element) || numeroCorr.test(value);
	});

	$.validator.addMethod('cantCuotasCorr', function(value, element){
		return this.optional(element) || cantCuotasCorrecta(value)==true;
	});

	$.validator.addMethod('primaTot', function(value, element){
		value = value.replace(/\./g,'');
		return this.optional(element) || value>3000&&value<600000;
	});

	$.validator.addMethod('primaMen', function(value, element){
		value = value.replace(/\./g,'');
		return this.optional(element) || value>100&&value<60000;
	});

	$.validator.addMethod('diaCobrar', function(value, element){
		return this.optional(element) || value>0&&value<29;
	});

	function cantCuotasCorrecta(valorIngresado){

		//El numero de cuotas seleccionado es valido si es divisible por la cantidad de meses de duracion de la vigencia
		mesesVig = $("#tipVig").val();
		
		for (let i=2; i<=mesesVig; i++){
			numero = mesesVig % i;
			if(numero==0){
				if(valorIngresado==i){
					return true;
				}
			}
		}
	}

	$("#cuotasForm").validate({

       rules: {
           "primaTotal": {required:true, primaTot: true, numeroCorr: true, number: true},
           "cantCuotas": {required:true, rangelength:[1,12], cantCuotasCorr:true},
           "primaMensual": {required:true, primaMen: true, number: true},
           "diaCobrar": {required:true, diaCobrar:[1,28]},
           "tipoPag":{required: true}
       },
       messages: {
           "primaTotal": {required:"Debe ingresar una Prima Total", primaTot: "La Prima Total ingresada es incorrecta (Debe ser mayor a 3.000 y menor a 600.000)", numeroCorr:"Ingrese un numero de Prima Total correcto", number:"Ingrese un numero correcto"},
           "cantCuotas": {required:"Debe seleccionar una cantidad de Cuotas", rangelength:"La cantidad de Cuotas ingresada no es valida", cantCuotasCorr: "El numero de Cantidad de Cuotas no es permitido."},
           "primaMensual": {required:"Debe de existir una Prima Mensual", primaMen: "La Prima Mensual ingresada es incorrecta", number:"Ingrese un numero correcto"},
           "diaCobrar": {required:"Debe seleccionar un Dia a Facturar", diaCobrar:"El Dia a Facturar ingresado no es permitido."},
           "tipoPag":{required: "Debe seleccionar un metodo de pago"}
       },

       highlight: function(element) {
	        $(element).addClass('claseError');
	   },
	   unhighlight: function(element) {
	   		$(element).removeClass('claseError');
	   },

	   errorElement : 'li',
       errorLabelContainer: '#mensajePago',

       submitHandler: function(form){

       	if(param == 1){
       		grabarPoliza();
       	}else if(param == 2){
       		modificarCuotas(datos);
       	}else if(param == 3){
       		var idPoliza = datos;
       		renovarPoliza(idPoliza);
       	}

       }
    });
}

function modificarCuotas(datos)
{
	var primaTotal   = $("#primaTotal").val();
	var cantCuotas   = $("#cantCuotas").val();
	var primaMensual = $("#primaMensual").val();
	var diaCobrar    = $("#diaCobrar").val();

	var mesesVig  = $("#mesesVig").html();
	var inicioVig = $("#inicioVig").html();
	var finVig    = $("#finVig").html();

	var idPago = $("#idPago").val();
	var lapso = mesesVig/cantCuotas;

	//Asignamos el valor del lapso de meses
	for (let index = 1; index < 12; index++) {
		if(lapso==index){
			var lapso = index;	
		}
	}

	texto = 'Valor de la prima total de $'+primaTotal+' que se realizara en '+cantCuotas+' pagos de $'+primaMensual+'.<br/> El pago de la prima mensual se facturara cada '+lapso+' mes/es, en el dia '+diaCobrar+' del mes que asi corresponda, durante un plazo de '+mesesVig+' mes/es de vigencia.';

	var datos = [idPago, primaTotal, cantCuotas, primaMensual, diaCobrar, inicioVig, finVig, lapso];

	alertify.confirm('Modificar informacion de pago', texto, 
		function(){

			$.ajax({
				url: 'scripts/aseguradora.php',
				type: 'POST',
				dataType: 'json',
				data: {param: 50001, datos:datos},
				success: function(r){

					if(r){
						alertify.success('Pago modificado correctamente');
					}else{
						alertify.error('Hubo un error al modificar el pago');
					}

					condicionesCuotasTabla.ajax.reload();
					$("#primaTotal, #primaMensual, #diaCobrar, #fechaCobrar").val('');
					$("#cantCuotas").val(1);
					$("#condicionesCuotasModal").modal('close');
				}
			})
			
		}, 
		function(){}
	).set(configuracionAlert('okCancel','Registrar'));
}

function mostrarCuotas()
{
	$('#mostrarCuotasModal').modal({
		dismissible: true
	});

	$("#mostrarCuotasModal").modal('open');

	var idPago = $("#idPago").val();

	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'POST',
		dataType: 'json',
		data: {param: 50003, idPago:idPago},
		success: function(r){
			if(r.data!==null){
				$(".numeroPoliza").html(r.data['numPol']);
				$("#tbodyTabla").html(r.tbody);
			}else{
				alertify.error('Ocurrio un error al mostrar las cuotas de esta poliza');
			}
		}
	})
}

function facturarPago(pagoId)
{
	$("#detallesCuotasModal").modal('open');
	$(".mostrarTabla").css('display', 'block');
	$(".mostrarRecibo, .atrasDetallesCuotas, .envRecibo, .impRecibo").css('display', 'none');
	$("#cuerpoModal").css('opacity','1');

	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'POST',
		dataType: 'json',
		data: {param: 50004, pagoId:pagoId},
		success: function(r){
			if(r.success){

				$("#numPol").html(r.numPol);
				$("#tomador").html(r.tomador);
				$("#primaTotal").html('$'+r.primaTot);
			
				//Contenido de la tabla
				$('#tablaListadoCuotas').DataTable().destroy();
				listadoCuotasFacturaTabla = $('#tablaListadoCuotas').DataTable({
					"language": {
						"zeroRecords": "No se encontraron polizas sin cuotas establecidas",
				    	"sSearch": "Buscar",
				    },

				    "ajax":{  
				        "url": "scripts/tablas/listadoCuotasFacturaTabla.php", 
				        "method": 'POST',
				        "data":{param: 50004, pagoId:pagoId},
				        "dataSrc":""
				    },

				    //data: r.tbody,

				    "scrollY": 370,
				    "scrollCollapse": true,
				    "bPaginate": false,
				    "bInfo": false,
					"order": [[1, "asc"]],
			
					"aoColumnDefs": [
				      { 'bSortable': false, 'aTargets': [0] },
				      { 'bSortable': false, 'aTargets': [1] },
				      { 'bSortable': false, 'aTargets': [2] },
				      { 'bSortable': false, 'aTargets': [3] },
				      { 'bSortable': false, 'aTargets': [4] },
				      { 'bSortable': false, 'aTargets': [5] },
				      { 'bSortable': false, 'aTargets': [6] },
				      { 'bSortable': false, 'aTargets': [7] }
				    ],

				    "columns": [                    
				      {data:"idenCuota", title: "Ident",      width:"12%"},
				      {data:"numCuota",  title: "N°",   	  width:"4%"},
				      {data:"priMen",    title: "Prima",      width:"8%"},
				      {data:"lapso",     title: "Lapso",      width:"28%"},
				      {data:"diaCobrar", title: "Factura",    width:"7%"},
				      {data:"vtoPago",   title: "Vto",   width:"10%"},
				      {data:"estado",    title: "Estado",     width:"26%"},
				      {data:"boton",     title: "Factura",    width:"5%"}
				    ],

				    //Depende el estado del pago mostramos el texto de un color
			        "createdRow": function(row,data,index){
			        	estado = data['estado'];

			        	if(estado == 'A facturar'){
			        		$('td', row).eq(6).css('color','#000000de');
			        	}else if(estado == 'Facturada (Sin pagar)'){
							$('td', row).eq(6).css({'color':'#d2c300de', 'font-weight':'600'});
			        	}else if(estado == 'Pago aprobado'){
			        		$('td', row).eq(6).css({'color':'#4caf50', 'font-weight':'600'});
			        	}else if(estado == 'Pago rechazado'){
			        		$('td', row).eq(6).css({'color':'#ec9815', 'font-weight':'600'});
			        	}else if (estado == 'Deuda'){
			        		$('td', row).eq(6).css({'color':'#f44336', 'font-weight':'600'});
			        	}
			        }

				});
						
				$(document).off('click', '.generarFactura');
				$(document).on('click', '.generarFactura', function(){
					var cuotaId = $(this).attr('value');
					traerDatosReciboCuota(cuotaId);
					$(".efectuar").css('display', 'grid');
					$(".efectuado, .envRecibo, .impRecibo, #actualizarListCuotas").css('display','none');
				});

				$(document).off('click', '.verFactura');
				$(document).on('click', '.verFactura', function () {
					cuotaId = $(this).attr('value');
					traerDatosReciboCuota(cuotaId);		
					$(".efectuar, #actualizarListCuotas").css('display', 'none');
					$(".efectuado").css('display', 'block');		
					$(".envRecibo, .impRecibo").css('display','unset');
				});

			}else{
				alertify.error('Ocurrio un problema al mostrar la informacion');
			}
		}
	})
}

function traerDatosReciboCuota(cuotaId)
{
	$(".atrasDetallesCuotas").css('display', 'block');
	$(".atrasDetallesCuotas").off('click');
	$(".atrasDetallesCuotas").on('click', function(){
		$(".mostrarTabla, #actualizarListCuotas").css('display', 'block');
		$(".mostrarRecibo, .atrasDetallesCuotas").css('display', 'none');
		$("#cuerpoModal").css('opacity','1');
		$(".envRecibo, .impRecibo").css('display','none');
	})

	$("#emitirFacturaModal").modal('open');

	$(".mostrarTabla, .estadoAccionRecibo").css('display', 'none');
	$(".mostrarRecibo").css('display', 'block');

	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'POST',
		dataType: 'json',
		data: {param:50005, idCuota:cuotaId},
		success: function(r){
			if(r.success){

				$(".recibo .nombreComp").html('<img src="img/ImgAlt/asegLogo.png" class="logoAs" alt="Logo Aseguradora">');
				
				$(".nroPol").html(r.numPol);
				$(".nroCuota").html(r.nroCu);
				$(".aCobrar").html('$'+r.priMen);
				$(".aseg").html(r.tom);
				$(".domic").html(r.dom);
				$(".emision").html(r.fact);
				$(".laps").html(r.lapCu);
				$(".vigencia").html(r.vig);
				$(".vtoPago").html(r.vtoPa);
				$(".importe").html('$'+r.priMen);
				$(".vehiculo").html(r.veh);
				$(".anio").html(r.anioVe);
				$(".patente").html(r.pat);
				$(".nroMot").html(r.nroMot);
				$(".sAseg").html('$'+r.sumAs);
				$(".cob").html(r.cob);
				$(".cobAd").html(r.cobAd);
				$(".numPoliza").html(r.numPol);
				$(".fechaEmision").html(r.fact);

				$(".fechaEmisRec").html('el '+r.fechCob);

				//EFECTUAMOS EL COBRO EN EFECTIVO DE LA CUOTA 
				$(document).off('click', '.efectuarPago');
				$(document).on('click', '.efectuarPago', function(){
					efectuarPago(cuotaId);
				});

				//IMPRIMIMOS EL RECIBO DE LA CUOTA 
				$(document).off('click', '.impRecibo');
				$(document).on('click', '.impRecibo', function(){
					imprimirRecibo(cuotaId);
				});

				//ENVIAMOS POR CORREO EL RECIBO DE LA CUOTA
				$(document).off('click', '.envRecibo');
				$(document).on('click', '.envRecibo', function(){
					enviarRecibo(cuotaId);
				});

			}else{
				alertify.error('Ocurrio un problema al mostrar la informacion');
				$("#efectuarPago").css('display', 'none');
				$("#cuerpoModal").css('opacity','0.5');

				$(".nroPol").html('************ **********');
				$(".aseg").html('************ **********');
				$(".domic").html('************ **********');
				$(".emision").html('************ **********');
				$(".laps").html('************ **********');
				$(".vigencia").html('************ **********');
				$(".vtoPago").html('************');
				$(".importe").html('************');
				$(".vehiculo").html('************');
				$(".anio").html('****');
				$(".patente").html('******');
				$(".nroMot").html('**********');
				$(".sAseg").html('************');
				$(".cob").html('****** **********');
				$(".cobAd").html('**********');
				$(".numPoliza").html('**********');
				$(".fechaEmision").html('************');

				$(".nroCuota").html('************ **********');
				$(".lap").html('************ **********');
				$(".imp").html('************ **********');
				$(".vto").html('************ **********');
				$(".tom").html('************ **********');
				$(".nroPag").html('************ **********');
				$(".estCuota").html('************ **********');
			}
		}
	})
}

function efectuarPago(cuotaId)
{
	alertify.confirm('Efectuar Pago', 'Esta opcion no puede deshacerse ¿Seguro?',
	 function(){
	 	
	 	$.ajax({
			url: 'scripts/aseguradora.php',
			type: 'POST',
			dataType: 'json',
			data: {param: 50006, cuotaId:cuotaId},
			beforeSend: function(){
				$("#efectuarPago").html('<img src="img/ImgAlt/preload.gif">').show().attr('disabled', 'disabled');
			},
			success: function(r){
				if(r.permitido){

					if(r.success){
						setTimeout(function(){

							$(".efectuar").css('display', 'none');
							$(".efectuado").css('display', 'block');
							$(".fechaEmisRec").html('el '+r.fechaCobro);
							$(".envRecibo, .impRecibo").css('display','unset');
							
							//$("#efectuarPago").html('Efectuado').show().attr('disabled', 'disabled');
							alertify.success('Pago efectuado');
							listadoCuotasFacturaTabla.ajax.reload();
							traerDatosReciboCuota(cuotaId);
						},2000);
	
					}else{
						$(".efectuar").css('display', 'block');
						$(".efectuado").css('display', 'none');
						$(".envRecibo, .impRecibo").css('display','none');
						//$("#efectuarPago").html('Error').show().attr('disabled', 'disabled');
						alertify.error('Ocurrio un error al procesar la peticion');
						$(".btnAcc").css('display', 'block');
					}
				}else{
					alertify.error('No se permite efectuar esta cuota.');
					$("#efectuarPago").html('Efectuado').show().attr('disabled', 'disabled');
				}
				
			}
		})
	 },
	 function(){}
	 ).set(configuracionAlert('okCancel','Si'));
}

function actualizarEstadoCuota()
{
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'POST',
		dataType: 'json',
		data: {param:50007},
		success: function(r){
			if(r){
				//listadoCuotasFacturaTabla.ajax.reload();
			}
		}
	})
}

function imprimirRecibo()
{
	var ticket = document.querySelector(".contRecibo");

	var ventana = window.open('', 'PRINT', 'height=600,width=1000');
	//ventana.document.write('<link rel="stylesheet" href="css/estilos.css">');
	ventana.document.write(ticket.innerHTML);
	ventana.document.close();
	ventana.focus();
	ventana.onload = function () {
		ventana.print();
		ventana.close();
	};

	return true;
}

function enviarRecibo(cuotaId)
{
	$(".modalEnviarCorreo").modal('open');

	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'POST',
		dataType: 'json',
		data: {param:50009, cuotaId:cuotaId},
		success: function(r) {

			if(r.success) {

				info = [r.tomador, r.nroPago, r.nroCuota];
				validarEmail(0, info);
				if(r.existeCorreo=='si'){
					$(".estadoAccionRecibo").css('display', 'block');
					$(".correoLab").html('Correo electronico');
					$(".correo").val(r.correo);			
				}else if (r.existeCorreo == 'no'){
					$(".correoLab").html('Ingrese un correo electronico');
				}
			}else {
				alertify.error('Ha ocurrido un problema al realizar la peticion al servidor');
			}
		}
	})
}

function validarEmail(param, data)
{
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
				enviarCorreo(data);
			}else if(param==1){
				envDescListRecibos('enviar');
			}
			
		}
	})
}

function enviarCorreo(data)
{
	var correo = $(".correo").val();

	info = [correo, data[0], data[1], data[2]];

	$.ajax({
		url: 'scripts/emails/enviarEmailRecibo.php',
		type: 'POST',
		dataType: 'json',
		data: {info: info},
		beforeSend(){			
			$("#btnEnviarRecibo").html('<img src="img/ImgAlt/preload.gif">').show();
			$("#btnEnviarRecibo, #cerrarModalEnviarRec").attr('disabled', 'disabled');
		},
		success: function(r) {
			if(r.envCorr){
				alertify.success('Comprobante enviado por correo con exito');
				$("#btnEnviarRecibo").html('Enviar');
				$(".modalEnviarCorreo").modal('close');
				$("#btnEnviarRecibo, #cerrarModalEnviarRec").attr('disabled', false);
			}else{
				$("#btnEnviarRecibo").html('Reintentar');
				$("#btnEnviarRecibo, #cerrarModalEnviarRec").attr('disabled', false);
				alertify.error('Error al enviar el comprobante por correo');
			}
			
		}
	})
	
}

function mostrarListadoRecibos(pagoId)
{

	$("#divTit").css('display', 'block');
    $("#tituloCont").html('Ver recibos de cuotas');
    $("#divSubTit").html('Listado de recibos de todas las cuotas de la poliza');
	
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'POST',
		dataType: 'json',
		data: {param:50010, pagoId:pagoId},
		success: function(r) {
			if(r.resp['success']){
				$('#contenidoC').load('vista/pagos/mostrarListadoRecibos.php');

			}else{
				alertify.error('Ha ocurrido un problema al realizar la peticion al servidor');
			}
		}
	})
}

function envDescListRecibos(accion)
{	

	if(accion=='descargar'){
		location.href = 'scripts/PDFs/listadoRecibosPDF.php';

	}else if(accion=='enviar'){

		var info = [
			$("#email").val(),  //E-mail
			$(".aseg").html(),	//tomador
			$(".nroPol").html()	//nroPoliza
		];

		$.ajax({
			url: 'scripts/emails/enviarListadoRecibos.php',
			type: 'POST',
			dataType: 'json',
			data: {info:info},
			beforeSend(){
				$(".btnEnviarListCu").html('<img src="img/ImgAlt/preload.gif">').show().attr('disabled', 'disabled');
			},
			success: function(r){

				if(r.envCorr){
					alertify.success('Comprobante enviado por correo con exito');
					$(".btnEnviarListCu").html('<i class="fas fa-share"></i> Reenviar').attr('disabled', false);
				}else{
					alertify.error('Error al enviar el comprobante por correo');
					$(".btnEnviarListCu").html('<i class="fas fa-share"></i> Reintentar').attr('disabled', false);
				}
			}
		})
		
	}
	
}

function tablaCondicionesCuotas()
{
	condicionesCuotasTabla = $('#condicionesCuotasTabla').DataTable({
		"language": {
			"zeroRecords": "No se encontraron polizas sin cuotas establecidas",
	    	"sSearch": "Buscar",
	    },
	    "ajax": "scripts/tablas/tablaModificarCuotas.php",
	    "scrollY": 370,
	    "scrollCollapse": true,
	    "bPaginate": false,
	    "bInfo": false,
		"order": [[5, "desc"]],
		"aoColumnDefs": [
	      { 'bSortable': false, 'aTargets': [6] }

	    ],
	    "columns": [                    
	      {data:"nro", title: "Nro",width:""},
	      {data:"tomador", title: "Tomador",width:""},
	      {data:"vehiculo", title: "Vehiculo",width:""},
	      {data:"vigini", title: "Inicio Vig",width:""},
	      {data:"vigfin", title: "Fin Vig",width:""},
	      {data:"reg", title: "Registro",width:""},
	      {data:"boton", title: "Cuotas",width:""}
	    ]     
	});

	return condicionesCuotasTabla;
}

function tablaFacturarPago()
{
	tablaFacturarPago = $("#verPagoTabla").DataTable({
		"language": {
			"zeroRecords": "No se encontraron pagos registrados en la base de datos",
	    	"sSearch": "Buscar Pagos",
	    },

	    "ajax": "scripts/tablas/tablaFacturarPago.php",

	    "scrollY": 370,

	    "scrollCollapse": true,

	    "bPaginate": false,

	    "bInfo": false,

		"order": [[4, "desc"]],

		"aoColumnDefs": [
	      { 'bSortable': false, 'aTargets': [5] }
	    ],
		
	    "columns": [                    
	      {data:"nro"      , title: "Pago num",width:""},
	      {data:"polizaNum", title: "Poliza num",width:""},
	      {data:"tomador"  , title: "Tomador",width:""},
	      {data:"nroCuotas", title: "N° Cuotas",width:""},
	      {data:"estado"   , title: "Estado",width:""},
	      {data:"boton"    , title: "",width:""}
	    ]     

	});
}

function funcionalidadesPagos()
{
	$('input, select').addClass('browser-default');
	
	$(".divVerCuotas").css('display', 'none');
	$("#primaMensual").prop('disabled', true);

	$('.modal').modal({
		dismissible: false
	});

	$(".modalEnviarCorreo").modal({
		onCloseEnd: function(){
			$("#formCorreo").validate().destroy();
		},
		dismissible: false,
		endingTop: '20%'
	})
}
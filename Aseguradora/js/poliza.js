jQuery(document).ready(function(){

	funcionalidadesPoliza();
	actualizarEstadosPoliza();
	listadoCoberturas();

	$("#siguientePoliza").on('click', function(){
		validarPoliza(1);
	});

	$(document).off('click', '.renovarPoliza');
	$(document).on('click', '.renovarPoliza', function(){
		var idPoliza = $(this).attr('value');
		var nroPoliza = $(this).attr("data-id");
		var sumAs = $(this).attr('data-sumAs');

		var info = [idPoliza, nroPoliza, sumAs];
		mostrarInfoRenovacion(info);
	});

	$(document).off('click', '.verConPol')
	$(document).on('click', '.verConPol', function(){
		var idPoliza = $(this).attr('value');

		$(document).off('click', '.btnVolverConPol')
		$(document).on('click', '.btnVolverConPol', function () {
			$(this).css('background', '#e0e0e0');
			$('#contenidoC').load('vista/poliza/elementosPoliza/consultar_polizas.php', function () {
				tablaConsultarPoliza();
			});
		});
		mostrarInformacion(idPoliza);

		$(document).off('click', '.btnActConPol');
		$(document).on('click', '.btnActConPol', function(){
			$(this).html('<img src="img/ImgAlt/preloadArrowBlue.gif">'+'actualizar').attr('disabled', 'disabled');
			setTimeout(() => {
				mostrarInformacion(idPoliza);
			}, 3000);
		})
	});

	$("#actListPol").off('click');
	$("#actListPol").on('click', function(){
		$(this).html('<img src="img/ImgAlt/preloadArrowBlue.gif">'+'actualizar').attr('disabled', 'disabled');
		setTimeout(() => {
			$(this).html('<i class="fas fa-sync-alt"></i> actualizar').attr('disabled', false);
			actualizarEstadosPoliza();
			tablaConsultarPoliza.ajax.reload();
		}, 1000);
	});

});

function validarPoliza(param, info)
{

	var valFecha = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
	$.validator.addMethod("validarFecha", function(value, element) {
			return this.optional(element) || valFecha.test(value) ;
	});

	$.validator.addMethod("validarTF", function(value, element) {
			return this.optional(element) || validarTipoFecha(value)==true;
	});

	$.validator.addMethod("validarTipVig", function(value, element) {
			return this.optional(element) || valTipoVig(value)==true;
	});

	$.validator.addMethod("validarTipFinVig", function(value, element) {
		return this.optional(element) || validarTipoFechaInVig(value)==true;
	});	

	function validarTipoFecha(fecha){

		horaActual = moment(new Date()).format('HH:mm'); //hora actual
		horaComparar = moment('12:00', 'HH:mm').format('HH:mm'); //12 del mediodia
		fechaIngresada = moment($("#vig_ini").val(), 'DD/MM/YYYY').format('MM/DD/YYYY'); //fecha ingresada a comparar

		if(horaActual>horaComparar){
			var fechaComparar = moment(new Date()).add(1, 'days').format('MM/DD/YYYY'); //un dia despues de hoy
		}else if(horaActual<horaComparar){
			var fechaComparar = moment(new Date()).format('MM/DD/YYYY'); //fecha actual
		}

		if(fechaIngresada>fechaComparar){
			return true;
		}else if(fechaIngresada<fechaComparar){
			return false;
		}else if(fechaIngresada==fechaComparar){
			return true;
		}
	}

	function valTipoVig(valor){
		for (let i=4; i<=12; i++) {
			if(valor==i){
				return true;
			}			
		}
		
	}

	function validarTipoFechaInVig(inVig){

	
		inicioVigencia = moment(inVig, 'DD/MM/YYYY'); //Inicio de vigencia ingresado
		finVigencia = moment($("#vig_fin").val(), 'DD/MM/YYYY');  //Fin de vigencia del input
		cantMeses = $("#tipVig").val();
		diferencia = finVigencia.diff(inicioVigencia, 'months'); //Obtenemos la diferencia en meses

		//si la diferencia en meses entre el inicio de vigencia y el fin de vigencia es igual a la cantidad de meses, devuelve true
		if(cantMeses==diferencia){
			return true;
		}else if(cantMeses!=diferencia){
			return false;
		}
	}

	$("#divPoliza").validate({
		rules: {
			"vig_ini": {required:true, validarFecha: true, validarTF: true, validarTipFinVig: true},
			"vig_fin": {required:true, validarFecha: true},
			"tipVig": {required:true, validarTipVig: true}
		},
		messages: {
			"vig_ini": {required:"Seleccione una fecha de Inicio de Vigencia", validarFecha: "El tipo de fecha ingresado no es valido", validarTF: "La Fecha de inico de vigencia no es permitida", validarTipFinVig: "La Fecha de Fin de Vigencia ingresada no es permitida"},
			"vig_fin": {required:"Seleccione una fecha de Fin de Vigencia", validarFecha: "El tipo de fecha ingresado no es valido"},
			"tipVig": {required:"Seleccione una opcion par Fin de vigencia", validarTipVig: "La opcion de Fin de Vigencia ingresada no es valida."}
		},

		highlight: function(element) {
				$(element).closest('.hasDatepicker').addClass('claseError');
				$(element).closest('.tipVig').addClass('claseError');
		},
		unhighlight: function(element) {
				$(element).closest('.hasDatepicker').removeClass('claseError'); 
				$(element).closest('.tipVig').removeClass('claseError');
		},

		errorElement : 'li',
		errorLabelContainer: '#mensajePoliza',

		submitHandler: function(form){
			if(param == 1){
				validarTomador();
			}else if(param == 2){
				generarFormularioPago(1, info);
			}
			
		}
    });
}

function validarTomador()
{
	$('.tabs').tabs('select','test2');
	boton = '<button type="submit" class="btn btn-flat green" id="siguienteTomador" value="Enviar"> siguiente <i class="fas fa-caret-right"></i> </button>';
	$(".btnDivTomador").html(boton);

	$("#siguienteTomador").attr('disabled', false);

	$('#select_cliente').change(function(){
	    if ($(this).val()!="")
	    {
	        $(this).valid();
	    }
	});

	//$("#siguienteTomador").on('click', function(){

		$("#divTomador").validate({
			rules:{
				"select_cliente":{required:true}
			},
			messages:{
				"select_cliente":{required: "Debe seleccionar un Tomador"}
			},

			highlight: function(element){
		        $(element).siblings('span').find('.selection').find('.select2-selection').addClass('claseError');
		    },
		    unhighlight: function(element) {
		         $(element).siblings('span').find('.selection').find('.select2-selection').removeClass('claseError'); 
		    },
 
		    errorElement : 'li',
	        errorLabelContainer: '#mensajeTomador',
 
	        submitHandler: function(form){
	            validarVehiculo();
	        }

		});
	//});
}

function validarVehiculo()
{
	$('.tabs').tabs('select','test3');
	boton = '<button type="submit" class="btn btn-flat green" id="siguienteVehiculo" value="Enviar"> siguiente <i class="fas fa-caret-right"></i> </button>';
	$(".btnDivVehiculo").html(boton);

	$("#siguienteVehiculo").attr('disabled', false);

	$('#select_marca, #select_modelo1, #codigoPostal, #vehiculoLocalidad').change(function(){
	    if ($(this).val()!="")
	    {
	        $(this).valid();
	    }
	});

	sumaAsegurada = /^[1-9]{1}[0-9]{4,7}$|[1-9]{1}[0-9]{1}\.[0-9]{3}$|[1-9]{1}[0-9]{2}\.[0-9]{3}$|[1-9]{1}\.[0-9]{3}\.[0-9]{3}$|[1-9]{1}[0-9]{1}\.[0-9]{3}\.[0-9]{3}$/;
	$.validator.addMethod('sumaAsegurada', function(value, element){
		return this.optional(element) || sumaAsegurada.test(value);
	});

	patente = /^[a-zA-Z]{3}[0-9]{3}$|[a-zA-Z]{2}[0-9]{3}[a-zA-Z]{2}$/;
	$.validator.addMethod("patente", function(value, element) {
			value.replace(/ /g, "");
			return this.optional(element) || patente.test(value);
	});

	nroChasis = /^[a-zA-Z0-9]{18}$/;
	$.validator.addMethod("nroChasis", function(value, element) {
			return this.optional(element) || nroChasis.test(value);
	});

	nroMotor = /^[a-zA-Z]{2}[0-9]{3}[a-zA-Z]{2}$/;
	$.validator.addMethod("nroMotor", function(value, element) {
			return this.optional(element) || nroMotor.test(value);
	});

	cantKm = /^[1-9]{1}[0-9]{1,6}$|[1-9]{1}[0-9]{1}\.[0-9]{3}$|[1-9]{1}[0-9]{2}\.[0-9]{3}$|[1-9]{1}\.[0-9]{3}\.[0-9]{3}$/;
	//^[1-9]{1,7}$|[1-9]{1}\.[1-9]{3}$|[1-9]{1}[0-9]{1}\.[1-9]{3}$|[1-9]{1}[0-9]{2}\.[1-9]{3}$|[1-9]{1}\.[0-9]{3}\.[0-9]{3}$/;
	$.validator.addMethod('cantKm', function(value, element){
		return this.optional(element) || cantKm.test(value);
	});

	anio = /^[1]{1}[9]{1}[5-9]{1}[0-9]{1}$|[2]{1}[0]{1}[0]{1}[0-9]{1}$|[2]{1}[0]{1}[1]{1}[0-9]{1}$|[2]{1}[0]{1}[2]{1}[0-1]{1}$/;
	$.validator.addMethod('anio', function(value, element){
		return this.optional(element) || anio.test(value);
	});

	$.validator.addMethod("validarComb", function(value, element) {
			return this.optional(element) || value=="Nafta" || value=="GasOil" || value=="Energia Electrica";
	});

	//$("#siguienteVehiculo").on('click', function(){ 

		$("#divVehiculo").validate({
			rules:{
				"suma_asegurada": {required: true, sumaAsegurada: true},
 				"selec_marca": {required: true, rangelength:[1,79]},
 				"select_modelo1": {required: true, rangelength:[1,1005]},
 				"patente": {required: true, patente: true},
 				"motor": {required: true},
 				"nroChasis": {required: true, nroChasis: true},
 				"nroMotor": {required: true, nroMotor: true},
 				"select_clase": {required: true, rangelength:[1,14], number: true},
 				"selectTipoUso": {required: true, rangelength:[1,25], number: true},
 				"cant_kilometro": {required: true, cantKm:true},
 				"codigoPostal": {required: true, rangelength:[1,22963], number: true},
 				"vehiculoLocalidad": {required: true, rangelength:[1,22963], number: true},
 				"coberturaAd": {required: true, rangelength:[1,6], number: true},
 				"combustible": {required: true, validarComb:true},
 				"anio": {required: true, anio: true},
 				"pasajeros": {number: true},
 				"asientos": {number: true},
 				"color": {lettersonly: true}
			},

			messages:{
				"suma_asegurada": {required: "Debe ingresar la Suma Asegurada", sumaAsegurada: "La Suma Asegurada es incorrecta"},
 				"selec_marca": {required: "Debe seleccionar una Marca", rangelength:"La Marca seleccionada es incorrecta"},
 				"select_modelo1": {required: "Debe seleccionar un Modelo", rangelength:"El Modelo seleccionado es incorrecto"},
 				"patente": {required: "Debe ingresar la Patente", patente: "La Patente ingresada es incorrecta"},
 				"motor": {required: "Debe ingresar el Motor"},
 				"nroChasis": {required: "Debe ingresar el Numero de Chasis", nroChasis: "El numero de Chasis es incorrecto"},
 				"nroMotor": {required: "Debe ingresar el Numero de Motor", nroMotor: "El Numero de Motor es incorrecto"},
 				"select_clase": {required: "Debe seleccionar la Clase", rangelength:"La Clase seleccionada es incorrecta", number:"La Clase seleccionada es incorrecta 2"},
 				"selectTipoUso": {required: "Debe seleccionar el Uso", rangelength:"El Uso seleccionado es incorrecto", number:"El Uso seleccionado es incorrecto 2"},
 				"cant_kilometro": {required: "Debe ingresar la Cantidad de Kilometros", cantKm:"El valor de Cantidad de Kilometros es incorrecto"},
 				"codigoPostal": {required: "Debe seleccionar un Codigo Postal", rangelength:"El Codigo Postal seleccionado es incorrecto", number:"El Codigo Postal seleccionado es incorrecto 2"},
 				"vehiculoLocalidad": {required: "Debe seleccionar la Localidad", rangelength:"La Localidad seleccionada es incorrecta", number:"La Localidad seleccionada es incorrecta 2"},
 				"coberturaAd": {required: "Debe seleccionar una Cobertura Adicional", rangelength:"La Cobertura Adicional seleccionada es incorrecta", number:"La Cobertura Adicional seleccionada es incorrecta 2"},
 				"combustible": {required: "Debe seleccionar un tipo de Combustible", validarComb:"El tipo de Combustible seleccionado es incorrecto"},
 				"anio": {required: "Debe ingresar el Año", anio:"El Año ingresado es incorrecto"},
 				"pasajeros": {number:"El valor ingresado de Pasajeros es incorrecto"},
 				"asientos": {number:"El valor ingresado de Asientos es incorrecto"},
 				"color": {lettersonly:"El valor ingresado de Color es incorrecto"}
			},

			highlight: function(element){
		        if($(element).closest('.campo').hasClass('select2')){
					$(element).siblings('span').find('.selection').find('.select2-selection').addClass('claseError');
				}else{
					$(element).closest('.campo').addClass('claseError');
				}
		    },
		    unhighlight: function(element) {
		    	if($(element).closest('.campo').hasClass('select2')){
					$(element).siblings('span').find('.selection').find('.select2-selection').removeClass('claseError'); 
				}else{
					$(element).closest('.campo').removeClass('claseError'); 
				}
		    },
 
		    errorElement : 'li',
	        errorLabelContainer: '#mensajeVehiculo',
 
	        submitHandler: function(form){
	            validarCobertura();
	        }
		});

	//});
}

function validarCobertura()
{
	$('.tabs').tabs('select','test4');
	boton = '<button type="submit" class="btn btn-flat green" id="siguienteCobertura"> siguiente <i class="fas fa-caret-right"></i> </button>';
	$(".btnDivCobertura").html(boton);

	$("#siguienteCobertura").attr('disabled', false);

	$('#select_cobertura').change(function(){
	    if ($(this).val()!="")
	    {
	        $(this).valid();
	    }
	});

	//$("#cargar_datos").on('click', function(){
		$("#divCobertura").validate({
			rules:{"select_cobertura": {required: true} },
			messages:{"select_cobertura": {required: "Debe seleccionar un Tipo de Cobertura"} },
			highlight: function(element){
				$(element).closest('.campo').addClass('claseError');
			},
			unhighlight: function(element){
				$(element).closest('.campo').removeClass('claseError'); 
			},
			errorElement : 'li',
	        errorLabelContainer: '#mensajeCobertura',
			submitHandler: function(form){
				generarFormularioPago(2, null);
			}
		});
	//});	
}

function llenarModelo(marca, modelomodificar)
{	
	jQuery('#select_modelo1').empty();
	jQuery('#select_modelo1').append('<option value="" disabled selected>Seleccione un modelo</option>');
	jQuery.ajax({
		type:"post",
		data:{param:10000, marca:marca},
		url: "scripts/aseguradora.php",
		dataType:'json',
		success: function(r){
			if(r!=null){
				//cuando traigo datos de la bbdd
				//jQuery('#nombre').val(r[0].['nombre']);
				//llenarmodelo(val(r[0].['marca'],r[0].['modelo']);
				jQuery.each(r.data, function(k,v){
					if(modelomodificar==v.id){
						jQuery('#select_modelo1').append('<option value="' + v.id + '" selected>' + v.nombre + '</option>');
					}else{
						jQuery('#select_modelo1').append('<option value="' + v.id + '">' + v.nombre + '</option>');
					}
				});
			}
		}
	});
}

function mostrarInformacion(idpoliza)
{
	$("#contenidoC").load('vista/poliza/elementosPoliza/informacionPoliza.php');

	jQuery.ajax({
		url: "scripts/aseguradora.php",
		type: "post",
		data: {param:10003, idpoliza:idpoliza},
		dataType: 'json',
		success: function (r) {
			if (r.success) {

				$(".btnActConPol").html('<i class="fas fa-sync-alt"></i> Actualizar').attr('disabled', false)

				infPol = '<p>Numero <span>'+r.info[0]+ '</span></p>'+ 
				'<p>Emitida el <span>' + r.info[1] + '</span> (' + r.info[2] + ') por <span>'+r.info['ne']+'</span> ('+r.info['nroe']+') en '+r.info['ns']+' ('+r.info['is']+')</p>'+
				'<p>Vigente desde el <span>' + r.info[3] + '</span> hasta el <span>' + r.info[4] + '</span> 12:00hs (<span>'+r.info[5]+' meses</span>)</p>'+
				'<p> Fecha de anulacion <span>'+r.info[6]+'</span></p>';
				$(".infPol").html(infPol);

				infEndSin = '<p>Numero de endosos realizados <span>'+ r.info[7]+'</span></p>'+
				'<p> Numero de siniestros involucrados <span>'+ r.info[8]+'</span></p>';
				$(".infEndSin").html(infEndSin);
				infCo = '<p><span>'+r.info[9]+'</span></p>';
				$(".infCo").html(infCo);
				infCoA = '<p><span>'+r.info[10]+'</span></p>';
				$(".infCoA").html(infCoA);

				infTom = '<p>Numero <span>' + r.info[12]+'</span> </p>'+
						'<p> Nombre completo <span>'+r.info[13]+'</span> </p>'+
						'<p> Nro documento <span>'+r.info[14]+'</span> </p>'+
						'<p> Tipo de persona <span>' + r.info[15] +'</span> </p>'+
						'<p> Nacionalidad <span>' + r.info[16] +'</span> </p>'+
						'<p> Domicilio <span>' + r.info[11] +'</span> </p>'+
						'<p> Codigo postal <span>' + r.info[17] +'</span> </p>'+
						'<p> Fecha nacimiento <span>' + r.info[18] +'</span> </p>'+
						'<p> Genero <span>' + r.info[19] +'</span> </p>'+
						'<p> Telefono <span>' + r.info[20] +'</span> </p>'+
						'<p> Correo <span>' + r.info[21] +'</span> </p>'
				$(".infTom").html(infTom);
				
				if (r.info[25] == 1){
					var metodo = 'Pago en efectivo';
				}else if (r.info[25] == 2){
					var metodo = 'Tarjeta de Credito Asociada';
				}
				infPag = '<span class="subtitulo">Metodo de pago</span>'+
						'<p> Numero de pago <span> ' + r.info[22] +'</span> </p>'+
						'<p> Fecha de registro <span>' + r.info[23] +'</span> </p>'+
						'<p> Prima total <span>' + '$'+r.info[24] +'</span> </p>'+
					'<p> Metodo de pago <span>' + metodo +'</span> </p>'+
						'<p> Estado actual <span>' + r.info[26] +'</span> </p>';
				$(".infPag").html(infPag);

				//Mostramos el listado de cuotas
				$.each(r.cuotas, function(k,v){
					cuotas = '<tr>'+
								'<td>'+v.numero_cuota+'</td>'+
								'<td>'+v.iden_cuota+'</td>'+
								'<td>'+'$'+v.prima_mensual+'</td>'+
								'<td>'+v.estadoCuota+'</td>'+
							'</tr>';
					$("#tbodyCuo").append(cuotas);	
				})

				$.each(r.estados, function(k, v){
					if(k == 0){
						$(".estAct").append('<span>' + v.estado + '</span>');
					}else{
						$(".estAnt").append('<p> <span>'+v.estado+'</span> </p>');
					}
				})


				Iz ='<p> Suma asegurada <span>'+'$'+ r.info[32] +'</span> </p>'+
					'<p> Marca <span> ' + r.info[27] +'</span> </p>'+
					'<p> Modelo <span>' + r.info[28] +'</span> </p>'+
					'<p> Motor <span>' + r.info[33] +'</span> </p>'+
					'<p> Patente <span>' + r.info[34] +'</span> </p>'+
					'<p> Nro chasis <span>' + r.info[35] +'</span> </p>'+
					'<p> Nro motor <span>' + r.info[36] +'</span> </p>'+
					'<p> Clase <span>' + r.info[29] +'</span> </p>'+
					'<p> Uso <span>' + r.info[30] +'</span> </p>'+
					'<p> GPS <span>' + r.info[37] +'</span> </p>';

				De ='<p> Cero Km <span>' + r.info[38] +'</span> </p>'+
					'<p> Cantidad de Kilometros <span> ' + r.info[39] +' Kms'+'</span> </p>'+
					'<p> Localidad <span>' + r.info[31] +' </span> </p>'+
					'<p> Codigo postal <span>' + r.info[40] +'</span> </p>'+
					'<p> Combustible <span>' + r.info[41] +'</span> </p>'+
					'<p> Equipo de gas <span>' + r.info[42] +'</span> </p>'+
					'<p> Año <span>' + r.info[43] +'</span> </p>'+
					'<p> Color <span>' + r.info[44] +'</span> </p>'+
					'<p> Asientos <span>' + r.info[45] +'</span> </p>'+
					'<p> Pasajeros <span>' + r.info[46] +'</span> </p>';

				$(".Iz").html(Iz);
				$(".De").html(De);

			} else {
				alertify.error('Ocurrio un problema al mostrar la informacion.');
			}
		}
	});
}

function grabarPoliza()
{

	if($("#GPS").is(':checked')){
			var gps = 'Si';
	}else{
		var gps = 'No';
	}
	if($("#ceroKm").is(':checked')){
		var ceroKm = 'Si';
	}else{
		var ceroKm = 'No';
	}
	if($("#eGas").is(':checked')){
		var eGas = 'Si';
	}else{
		var eGas = 'No';
	}

	//DATOS DEL VEHICULO
	var sumAs  = $('#suma_asegurada').val();
	var mar    = $('#select_marca').val();
	var mod    = $('#select_modelo1').val();
	var pat    = $('#patente').val();
	var mo 	   = $('#motor').val();
	var nroCha = $('#nroChasis').val();
	var nroMot = $('#nroMotor').val();
	var cla    = $('#select_clase').val();
	var us     = $('#selectTipoUso').val();
	var kms    = $('#cant_kilometro').val();
	var cp     = $('#codigoPostal').val();
	var loc    = $('#vehiculoLocalidad').val();
	var cobAd  = $('#coberturaAd').val();
	var com    = $('#combustible').val();
	var an     = $('#anio').val();
	var pa     = $('#pasajeros').val();
	var as     = $('#asientos').val();
	var co     = $('#color').val();
	
	var datosVehiculo = [sumAs, mar, mod, pat, mo, nroCha, nroMot, cla, us, gps, ceroKm, kms, cp, loc, cobAd, com, eGas, an, pa, as, co];

	//DATOS DE LA POLIZA
	var nroPol    = $('#nroPoliza').html();
	var vig_ini   = $('#vig_ini').val();	
	var vig_fin   = $('#vig_fin').val();
	var tipVig 	  = $('#tipVig').val();

	var cli = $('#select_cliente').val();

	var cob = $('#select_cobertura').val();

	var datosPoliza = [nroPol, vig_ini, vig_fin, cli, cob, tipVig];

	//DATOS DEL PAGO
	var primaTotal   = $("#primaTotal").val();
	var cantCuotas   = $("#cantCuotas").val();
	var primaMensual = $("#primaMensual").val();
	var diaCobrar    = $("#diaCobrar").val();

	var lapso = (tipVig/cantCuotas).toString();

	var metodoPago = $("#tipoPag").val();
	
	var texto = 'Valor de la prima total de $'+primaTotal+' que se realizara en '+cantCuotas+' pagos de $'+primaMensual+'.<br/> El pago de la prima mensual se facturara cada '+lapso+' mes/es, en el dia '+diaCobrar+' del mes que asi corresponda, durante un plazo de '+tipVig+' mes/es de vigencia.';

	var datosPago = [primaTotal, cantCuotas, primaMensual, diaCobrar, vig_ini, vig_fin, lapso, metodoPago];

	alertify.confirm('Registrar Pago', texto, 
		function(){
			$.ajax({
				type:"post",
				data:{param:10002, datosPoliza:datosPoliza, datosVehiculo:datosVehiculo, datosPago:datosPago},
				url: "scripts/aseguradora.php",
				dataType:'json',
				success: function(r){
					if(r.success){

						$('#contenidoC').load('vista/poliza/elementosPoliza/formulario_poliza.php');

						alertify.success('Poliza cargada con exito.');
						
					}else{
						alertify.error('Error al cargar la poliza.');
					}
				}
			});
		}, 
		function(){}
	).set(configuracionAlert('okCancel','Registrar'));
}

function mostrarLocalidades(cp, localidadSelect)
{
	jQuery("#vehiculoLocalidad").empty();
	jQuery("#vehiculoLocalidad").append('<option value="" disabled selected>Seleccione Localidad</option>');
	$.ajax({
		type:"post",
		data:{param:80000, cp:cp},
		url: "scripts/aseguradora.php",
		dataType:'json',
		success: function(r){
			if(r!=null){
				jQuery.each(r.data, function(i,val){
					if(localidadSelect==val.localidadId){
						jQuery('#vehiculoLocalidad').append('<option value="' + val.localidadId + '" selected>' + val.localidadNombre + '</option>');
					}else{
						jQuery('#vehiculoLocalidad').append('<option value="' + val.localidadId + '">' + val.localidadNombre + '</option>');
					}
				});
			}
		}
	});
}

function mostrarInfoRenovacion(info)
{	
	var idPoliza  = parseInt(info[0]);
	var nroPoliza = info[1];
	var sumAs     = info[2];
	var idPoliza  = [idPoliza];
	//Comprobamos si la poliza se encuentra en deuda, y de ahi permitimos renovarla o no
	$.ajax({
		url: "scripts/aseguradora.php",
		type: 'POST',
		dataType: 'json',
		data: {param: 50008, data:idPoliza},
		success: function(r){
			if(r.success){
				var fact = r.fact;
				var apro = r.apro;
				var rech = r.rech;
				var deud = r.deud;
				var caCu = r.cantCuotas;
				var nroP = r.nroPago;

				//Si la cantidad de cuotas es igual a la cantidad de cuotas con pago aprobado
				if(caCu==apro){

					$('#contenidoC').load('vista/poliza/formularios/formularioRenovarPoliza.php', function(){
						funcionalidadesPoliza();

						$('#divSubTit').html(''); //Eliminamos el subtitulo
						$('.titForPol').html('datos de poliza '+nroPoliza);
						$('.infoCabeceraEmisPoliza, .divResumenVigPoliza').remove(); 

						//Obtenemos los datos por parametro y los enviamos a la funcion de validacion
						var infoRen = [idPoliza, sumAs];
						validarPoliza(2, infoRen);


						$('.atrRenPol').off('click');
						$('.atrRenPol').on('click', function(e){
							e.preventDefault();
							$('#contenidoC').load('vista/poliza/elementosPoliza/renovar_poliza.php', function(){
								tablaRenovarPoliza();
							});	
						})
					})
					
				}else{
					texto = `
						<div class="alertRenPol">
							<div class="ARP1"> ¡ Importante ! </div>
							<div class="ARP2"> Para llevar a cabo una renovacion, todas las cuotas de la poliza deben estar con su correspondiente pago aprobado sin excepción. </div>
							<div class="ARP3"> Verifique el pago nro: <span class="ARP3-1"> `+nroP+` </span></div>
							
							<div class="ARP4"> 
								<div class="ARP4-1"> Detalles </div>
								<div class="ARP4-2"> 
									Cuotas con pagos aprobados: <span class="ARP4-2-1"> `+apro+` </span>
								</div>
								<div class="ARP4-3">
									Cuotas con pagos sin efectuar: <span class="ARP4-3-1"> `+(fact+rech+deud)+` </span>
									
									<div class="ARP4-3-2"> Facturados: <span class="ARP4-3-2-1"> `+fact+` </span> </div>
									<div class="ARP4-3-2"> Rechazados: <span class="ARP4-3-2-2"> `+rech+` </span> </div>
									<div class="ARP4-3-2"> En deuda: <span class="ARP4-3-2-3"> `+deud+` </span> </div>
								</div>
							</div>
						</div>	
					`;

					alertify.alert('Renovar Poliza', texto).set(configuracionAlert('ok'));

					$('.alertRenPol').parent('.ajs-content').parent('.ajs-body').parent('.ajs-dialog').css('top', '0px');
				}
			}else{
				alertify.error('Ocurrio un error al procesar la peticion');
			}
			
		}
	})
}

function renovarPoliza(idPoliza)
{
	var infoRenPol = 
	[
		idPoliza,
		$("#vig_ini").val(),
		$("#tipVig").val(),
		$("#vig_fin").val(),
		$("#primaTotal").val(),
		$("#cantCuotas").val(),
		$("#primaMensual").val(),
		$("#diaCobrar").val(),
		$("#tipoPag").val()
	];

	$.ajax({
		url: "scripts/aseguradora.php",
		type: "post",
		dataType: "json",
		data: {param:10005, data:infoRenPol},
		success: function(r){
			if (r.renPoliza){
				alertify.success('Poliza renovada con exito');
				$('#contenidoC').load('vista/poliza/elementosPoliza/renovar_poliza.php', function(){
		          tablaRenovarPoliza();
		        });
			}else{
				alertify.error('Ocurrio un problema al renovar la poliza.');
			}
		}	
	})
}

function actualizarEstadosPoliza() {
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'POST',
		dataType: 'json',
		data: { param: 10006},
		success: function (r) {
			if (r.success) {
				//alertify.success('Estados de poliza actualizado!!!');
			}
		}
	})
}

function listadoCoberturas()
{
	$("#infoCobertura")
	$("#select_cobertura").empty().append('<option value="0" selected disabled>Seleccione</option>');

	$.ajax({
		url: "scripts/aseguradora.php",
		type: "post",
		dataType: "json",
		data: {param:10001},
		success: function(r){
			if(r!=null){
				
				$.each(r, function(k, val){
					$("#select_cobertura").append('<option value="'+val.id+'">'+val.nombre+': '+val.descripcion+'</option>');
				})

				//Al seleccionar la cobertura se mostrara la informacion de la misma
				$('#select_cobertura').on('change', function(){

					var idCob = $('#select_cobertura').val();

					for (let i=0; i<=5; i++) {
						if(idCob==r[i]['id']){
							$('#infoCobertura').html(r[i]['informacion']);
						}
					}
				
				});
			}
		}
	});
}

function tablaConsultarPoliza()
{
	
	tablaConsultarPoliza = $('#consultarPolizasTabla').DataTable({
		"language": {
			"zeroRecords": "No se encontraron polizas",
        	"sSearch": "Buscar",
        },
        "ajax": "scripts/tablas/tablaConsultarPoliza.php",      
        "scrollY": 370,
        "bPaginate": false,
        "dom": 'Bft',
		"order": [[5, "desc"]],
		"lengthMenu": [[5, 10], [5, 10]],
		"aoColumnDefs": [
		  { 'bSortable': false, 'aTargets': [0] },
		  { 'bSortable': false, 'aTargets': [3] },
		  { 'bSortable': false, 'aTargets': [6] },
		  { 'bSortable': false, 'aTargets': [7] }
        ],

        "columns": [                    
          {data:"nro", title: "Nro",           width:"8%"},
          {data:"tomador", title: "Tomador",   width:"18%"},
		  {data:"vehiculo", title: "Vehiculo", width:"12%"},
		  {data:"vigPol", title: "Vigencia",   width:"16%"},
		  {data:"anulac", title: "Anulacion",  width:"4%"},
		  {data:"emision", title: "Emision",   width:"12%"},
          {data:"estado", title: "Estado",	   width:"8%"},
          {data:"info", title: "Info",		   width:"19%"},
          {data:"boton", title: "Ver",		   width:"2%"}
		],

		"buttons":[
			{
				text: `<select id="selectVistaListPoliza"> 
							<option value="0">Todas</option>
							<option value="1">Espera</option>
							<option value="2">Vigentes</option>
							<option value="3">Vencidas</option>
							<option value="4">Renovadas</option>
							<option value="5">Anuladas</option>
					   </select>`,
                className: 'browser-default'
            }
		],

		// 0 - Vigente
		// 1 - Vencida
		// 2 - Anulada
		// 3 - Vigente-deuda
		// 4 - Renovada
		// 5 - Espera
		
		"createdRow": function(row,data,index){
        	estado = data['estado'];

        	if(estado=='Vigente'){
        		$('td', row).eq(6).css({'color':'#333', 'font-weight':'600'});
        	}else if(estado=='Vencida'){
				$('td', row).eq(6).css({'color':'orange', 'font-weight':'600'});
        	}else if(estado=='Anulada'){
				$('td', row).eq(6).css({'color':'red', 'font-weight':'600'});
        	}else if(estado=='Vigente-deuda'){
				$('td', row).eq(6).css({'color':'#d7d700', 'font-weight':'600'});
			}else if(estado=='Renovada'){
				$('td', row).eq(6).css({'color':'#2e4eadb8', 'font-weight':'600'});
			}else if(estado=='Espera'){
				$('td', row).eq(6).css('color','#333');
			}

			//Mostramos la informacion segun la opcion elegida
			$("#selectVistaListPoliza").on('change', function(){
				val = $(this).val();

				function mostrar(est){
					$('td', row).css('display', 'revert');
					if(data['estado']!=est){
						$('td', row).css('display', 'none');
					}
				}
				
				if(val==0){
					$('td', row).css('display', 'revert');
				}else if(val==1){
					mostrar('Espera')
				}else if(val==2){
					mostrar('Vigente')
				}else if(val==3){
					mostrar('Vencida')
				}else if(val==4){
					mostrar('Renovada')
				}else if(val==5){
					mostrar('Anulada')
				}else if(val==6){
					
				}
			})
		}
		
	});

	return tablaConsultarPoliza;
}

function tablaRenovarPoliza()
{
	tablaRenovarPoliza = $('#renovarPolizaTabla').DataTable({
		"language": {
			"zeroRecords": "No hay polizas vencidas registradas",
        	"sSearch": "Buscar",
        },
        "ajax": "scripts/tablas/tablaRenovarPoliza.php",    
        "scrollY": 370,
        "scrollCollapse": true,
        "dom": 'ft',
        "order": [[3, "desc"]],
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [0] },
          { 'bSortable': false, 'aTargets': [1] },
          { 'bSortable': false, 'aTargets': [2] },
          { 'bSortable': false, 'aTargets': [3] },
          { 'bSortable': false, 'aTargets': [4] }

        ],
        "columns": [                    
          {data:"nro",     title: "Nro",       width:"20%%"},
          {data:"tomador", title: "Tomador",   width:"20%%"},
		  {data:"vigPol",  title: "Vigencia",  width:"20%%"},
		  {data:"emision",  title: "Emision",  width:"15%"},
          {data:"anulac",  title: "Anulacion", width:"14%"},
          {data:"renovar", title: "Renovar",   width:"11%"}
        ]
	});

	return tablaRenovarPoliza;
}

function funcionalidadesPoliza()
{
	$('input, select').addClass('browser-default');
	
  	//Establecemos los tabs a todos los campos con la clase tabs (requerido por materialize para funcionar)
	$('.tabs').tabs();

	//Declaramos como modal todo campo con la clase .modal
	$('.modal').modal({
		dismissible: false
	});

	//Desabilitamos los campos donde se muestra la informacion del tomador en emitir poliza.
	$("#nombreDatos,#docDatos,#tipDatos,#nacDatos,#proDatos,#locDatos,#cpDatos,#calleDatos,#genDatos,#telDatos,#corrDatos,#feNacDatos").attr('disabled', 'disabled').removeClass('browser-default');
  	
	//Si la hora en la que se registra la poliza es superior a las 12 (mediodia), el dia de inicio de vigencia MINIMO a seleccionar es el siguiente al presente
	var dt = new Date();
    var hor = dt.getHours();
	if(hor >= 12){
		var diaMinimo = 1; 
	}else{
		var diaMinimo = 0;
	}

	//Generamos el numero de poliza
	var letras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	var l = (letras[Math.round(Math.random()*26)]+letras[Math.round(Math.random()*26)]);
	var num = Math.floor(1e9 + (Math.random() * 9e14));
	var numPol = l+num;
	$('#nroPoliza').html(numPol);

  	//Usamos datepicker en los campos "vig_ini" y "vig_fin" estableciendo el rango minimo de 4 meses entre fecha y fecha.
	$("#vig_fin").prop({'disabled': true, 'placeholder': 'Seleccione una opcion anterior'});
	$("#tipVig").prop('disabled', true);
	$("#vig_ini, #vig_fin").prop('readonly', true).css('background', 'transparent');

	$("#vig_ini, #tipVig, #vig_fin").on('mouseover', function(){
		$("#vig_ini").prop('readonly', true);
		$("#vig_fin").prop('disabled', true);
	});

    $(function(){
		$("#vig_ini").datepicker({
			dayNames: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
	        dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
	        monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
	        monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
			dateFormat: 'dd/mm/yy',
			minDate: diaMinimo,

			altField: "#vigenciaInicioInput",
			altFormat: "DD d MM yy",
			
			onSelect: function (fecha) {
				//$("#vig_fin").prop({'disabled': false, 'placeholder': 'Seleccione'});
				$("#tipVig").val(0);
				$("#tipVig").prop('disabled', false);

				$("#vig_fin").val('');
				
				//Segun la cantidad de meses que elijamos se establecen las fechas con el rango automaticamente.
				$("#tipVig").on('change', function(){

					var opcion = $("#tipVig").val();

					fecha = $("#vig_ini").datepicker('getDate');

					/*Cada vez que seleccionemos la cant de meses que dura la vigencia,
					seteamos el input de fin de vigencia con la cantidad de meses*/
					for(let i=1; i<=12; i++){
						if(opcion==i){
							fecha.setMonth(fecha.getMonth() + i);
						}
					}
					$("#vig_fin").datepicker("setDate", fecha);

					/*Al modificar el input oculto, mostramos el cuadro de informacion de vigencia y asignamos la info a los span */
					$(".divResumenVigPoliza").css('display', 'block');
					var inicioVigInput = $("#vigenciaInicioInput").val();
					var finVigInput = $("#vigenciaFinInput").val();
					$("#vigenciaInicioSpan").html(inicioVigInput);
					$("#vigenciaFinSpan").html(finVigInput);
				});
			
			}
		});

		$("#vig_fin").datepicker({
			dayNames: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
	        dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
	        monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
	        monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
			dateFormat: 'dd/mm/yy',
		
			altField: "#vigenciaFinInput",
			altFormat: 'DD d MM yy',

			onClose: function (fecha) {
				$("#vig_ini").datepicker("option", "maxDate", fecha);
			}
		});
	});

    //Se aplica la libreria select2 a los inputs requeridos en emision de poliza. 
	$('#select_cliente, #select_marca, #select_modelo1, #codigoPostal, #vehiculoLocalidad').select2({width: "100%"});

	/*Condicion para ingresar cantidad de kms correctamente*/
	$("#ceroKm").on('click', function(){
		if($("#ceroKm").is(':checked')){
			$("#cant_kilometro").prop('disabled', true).css('background', '#eee');
			$("#cant_kilometro").val('0');
		}else{
			$("#cant_kilometro").prop('disabled', false).css('background', 'transparent');;
			$("#cant_kilometro").val('');
			$("#cant_kilometro").focus();
		}
	});

	//Mostrar campos correctamente de cantidad de pasajeros
	$("#pasajeros, #asientos").prop('min', 1);
	$("#selectTipoUso").on('change', function(){
		if ($("#selectTipoUso").val() == 14){
			$("#pasajeros, #asientos").val('');
			$("#pasajeros, #asientos").prop('max', 15);
		}else{
			$("#pasajeros").prop('max', 60);
		}
	});

	$("#atrasTomador").off('click');
	$("#atrasTomador").on('click', function(){
		$('.tabs').tabs('select','test1');
		$(".btnDivTomador").html('');
	});

	$("#atrasVehiculo").on('click');
	$("#atrasVehiculo").on('click', function(e){
		$('.tabs').tabs('select','test2');
		$(".btnDivVehiculo").html('');
	});
	$("#atrasCobertura").on('click');
	$("#atrasCobertura").on('click', function(e){
		$('.tabs').tabs('select','test3');
		$(".btnDivCobertura").html('');
	});

	$("#atrasPago").on('click');
	$("#atrasPago").on('click', function(e){
		$('.tabs').tabs('select','test4');
		$(".btnDivPago").html('');
	});

	$('#cancelar_datos').on('click', function(e){
		e.preventDefault();
		alertify.confirm('Emision de Poliza', '¿Cancelar proceso de emision de poliza?',
		 function(){$('#contenidoC').load('vista/poliza/elementosPoliza/formulario_poliza.php');}, 
		 function(){}
		 ).set(configuracionAlert('okCancel','Si'));
		
	});

	//Aplicamos estilos al select buscador de consultar polizas.
	estilos = {
		'margin': '7px 0px 0px',
		'height': '26px',
		'padding': '7px 0px 0px',
		'border-radius': '4px',
		'width': '9rem',
		'font-size': '14px',
		'border': '1px solid #ccc'
	}
	
	$("#selectVistaListPoliza").parent().parent().css(estilos);
}
$(document).ready(function() {

	funcionalidadesEndoso();

	$('#cancelarCambiosEndoso, #cancelarCambiosEndosoV, #cancelarCambiosEndosoT, #cancelarCambiosEndosoC').on('click', function(){
		alertify.confirm('Proceso de Endoso', '¿Cancelar proceso de emision de endoso?',
		 function(){
		 	$('#modalSeleccionarEnd, #modalEndosoVehiculo, #modalEndosoTomador, #modalEndosoCobertura').modal('close'); 
		 	$("#formularioEndosoV, #formularioEndosoT, formularioEndosoC").validate().destroy();
		 	tablaGenerarEndoso.ajax.reload();
		 }, 
		 function(){}
		 ).set(configuracionAlert('okCancel','Si'));
	});

	$(document).off('click', '.editarPol');
	$(document).on('click', '.editarPol', function(){

		$("#continuarModalTipoEnd").attr('disabled', 'disabled');
		$('#selectTipoEndoso').val(0);
		$('#modalSeleccionarEnd').modal('open');

		var id = $(this).attr('value');

		$("#continuarModalTipoEnd, #cancelarModalTipoEnd").off('click');
		$("#continuarModalTipoEnd").on('click', function(){

			if($('#selectTipoEndoso').val()==1){
				traerDatosPoliza(1, id);
				$('#modalEndosoVehiculo').modal('open');
			}else if($('#selectTipoEndoso').val()==2){
				traerDatosPoliza(2, id);
				$('#modalEndosoVehiculo').modal('open');
			}else if($('#selectTipoEndoso').val()==3){
				traerDatosPoliza(3, id);
				$('#modalEndosoVehiculo').modal('open');
			}else if($('#selectTipoEndoso').val()==4){
				traerDatosPoliza(4, id);
				$('#modalEndosoTomador').modal('open');
			}else if($('#selectTipoEndoso').val()==5){
				traerDatosPoliza(5, id);
				$('#modalEndosoCobertura').modal('open');
			}
		});
	
		$("#cancelarModalTipoEnd").on('click', function(){
			$('#modalSeleccionarEnd').modal('close');
		});
	});

	$(document).off('click', '.verEnd');
	$(document).on('click', '.verEnd', function(){
		$('#modalListadoDeEndososPorPoliza').modal('open');
		var id = $(this).attr('value');
		mostrarEndososPorPoliza(id);
	});

	$(document).off('click', '.editarDescripcion');
	$(document).on('click', '.editarDescripcion', function(){
		var idEnd = $(this).attr('value');
		modificarDescripcion(idEnd);
	});
})

function traerDatosPoliza(paramValid, idPoliza)
{
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'post',
		dataType: 'json',
		data: {param:10007, id:idPoliza},
		success: function(r){
			if(r.data['success']){

				$(".numeroPol").html(r.data['nro']);
				
				if(r.data['gps']=='Si'){
					$('#GPS').prop('checked', true);
				}else{
					$('#GPS').prop('checked', false);
				}

				if(r.data['0km']=='Si'){
					$('#ceroKm').prop('checked', true);
				}else{
					$('#ceroKm').prop('checked', false);
				}

				if(r.data['eGas']=='Si'){
					$('#eGas').prop('checked', true);
				}else{
					$('#eGas').prop('checked', false);
				}

				if(r.data['pasajeros']==''){
					pasajeros = '';
				}else if(r.data['pasajeros']!=''){
					pasajeros = r.data['pasajeros'];
				}
				if(r.data['asientos']==''){
					asientos = '';
				}else if(r.data['asientos']!=''){
					asientos = r.data['asientos'];
				}

		
				$('#suma_asegurada').val(r.data['suma_asegurada']);
				$('#select_marca').val(r.data['marca_id']).trigger('change');
				llenarModelo(r.data['marca_id'], r.data['modelo_id']);
				$('#select_marca').on('change', function(){
					llenarModelo($('#select_marca').val(), 0);
				});
				$('#patente').val(r.data['patente']);
				$('#motor').val(r.data['motor']);
				$('#nroChasis').val(r.data['nroChasis']);
				$('#nroMotor').val(r.data['nroMotor']);
				$('#select_clase').val(r.data['clase']);
				$('#selectTipoUso').val(r.data['uso']);
				$('#ceroKm').val();
				$('#cant_kilometro').val(r.data['Kilometros']);
				$('#codigoPostal').val(r.data['codigoPostal']).trigger('change');
				mostrarLocalidades(r.data['codigoPostal'], r.data['localidad']);
				$("#codigoPostal").on('change', function(){
					mostrarLocalidades($("#codigoPostal").val(), 0);
				});
				$('#coberturaAd').val(r.data['coberturaAd']).trigger('change');
				$('#combustible').val(r.data['combustible']);
				$('#eGas').val();
				$('#anio').val(r.data['anio']);
				$('#pasajeros').val(pasajeros);
				$('#asientos').val(asientos);
				$('#color').val(r.data['color']);

				$('#select_cliente').val(r.data['clienteid']).trigger('change');
				$('#select_cobertura').val(r.data['coberturaid']).trigger('change');

				r.data['idPoliza'] = idPoliza;
				var datos = r.data;

				validarEndoso(paramValid, datos);

			}else{
				alertify.error('Ha ocurrido un problema al mostrar la informacion');
			}
		}
	});
}

function validarEndoso(parametro, info)
{
	if(parametro == 1 || parametro == 2 || parametro ==3){

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

		$("#formularioEndosoV").validate({
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
	        errorLabelContainer: '#mensajeEndoso',
 
	        submitHandler: function(form){
	            procesarEndoso(info)
	        }
		});
		
	}else if(parametro == 4){

		$('#select_cliente').change(function(){
		    if ($(this).val()!="")
		    {
		        $(this).valid();
		    }
		});

		$("#formularioEndosoT").validate({
			rules:{
				"select_cliente":{required:true}
			},
			messages:{
				"select_cliente":{required: "Debe seleccionar un Tomador"}
			},
			highlight: function(element){
				$(element).siblings('span').find('.selection').find('.select2-selection').addClass('claseError');
			},
			unhighlight: function(element){
				$(element).siblings('span').find('.selection').find('.select2-selection').removeClass('claseError');
			},
			errorElement: 'li',
			errorLabelContainer: '#mensajeEndoso',

			submitHandler: function(form){
				procesarEndoso(info);
			}
		});
		
	}else if(parametro == 5){

		$('#select_cobertura').change(function(){
		    if ($(this).val()!="")
		    {
		        $(this).valid();
		    }
		});

		$("#formularioEndosoC").validate({
			rules:{"select_cobertura": {required: true} },
			messages:{"select_cobertura": {required: "Debe seleccionar un Tipo de Cobertura"} },
			highlight: function(element){
				$(element).closest('.campo').addClass('claseError');
			},
			unhighlight: function(element){
				$(element).closest('.campo').removeClass('claseError');
			},
			errorElement : 'li',
	        errorLabelContainer: '#mensajeEndoso',
			submitHandler: function(form){
				procesarEndoso(info);
			}
		});
	}
}

function procesarEndoso(datos)
{	
	/*var fechaEmisEnd 	  = $('#fechaEm').val();
	var horaEmisEnd 	  = $('#horaEm').val();*/
	
	var fechEmPoliza 	  = datos['P.fecha_emision'];
	var horEmisPol        = datos['P.horaEmis'];
	var iniVigPol 		  = datos['P.vigencia_inicio'];
	var finVigPol 		  = datos['P.vigencia_fin'];
	var horaInVig 		  = datos['P.horaInVig'];

	var sumaAs 			  = $('#suma_asegurada').val();
	var marca 			  = $('#select_marca').val();
	var modelo 		      = $('#select_modelo1').val()
	var patente 		  = $('#patente').val();
	var motor 			  = $('#motor').val();
	var nroChasis 		  = $('#nroChasis').val();
	var nroMotor 		  = $('#nroMotor').val();
	var clase 			  = $('#select_clase').val();
	var uso               = $('#selectTipoUso').val();
	var cantKms 		  = $('#cant_kilometro').val();
	var cp 				  = $('#codigoPostal').val();
	var vehiculoLocalidad = $('#vehiculoLocalidad').val();
	var cobAd 			  = $('#coberturaAd').val();
	var combustible 	  = $('#combustible').val();
	var anio 			  = $('#anio').val();
	var pasajeros 		  = $('#pasajeros').val();
	var asientos          = $('#asientos').val();
	var color 			  = $('#color').val();

	var cliente           = $('#select_cliente').val();
	var cobertura         = $('#select_cobertura').val();

	var idPoliza 		  = datos['idPoliza']; 

	if($("#eGas").is(':checked')){
		var eGas = 'Si';
	}else{
		var eGas = 'No';
	}

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

	var nuevosDatEnd = [ fechEmPoliza,
						 horEmisPol,
						 iniVigPol,
						 finVigPol,
						 horaInVig,
						 sumaAs,
						 marca,
						 modelo,
						 patente,
						 motor,
						 nroChasis,
						 nroMotor,
						 clase,
						 uso,
						 gps,
						 ceroKm,
						 cantKms,
						 cp,
						 vehiculoLocalidad,
						 cobAd,
						 combustible,
						 eGas,
						 anio,
						 pasajeros,
						 asientos,
						 color,
						 cliente,
						 cobertura,
						 idPoliza ]; // 29 elementos

	//var informacionEndoso = [idPoliza, datos['P.nro'], fechaEmisEnd, horaEmisEnd];
	modificarDatosPoliza(nuevosDatEnd, idPoliza);
}

function modificarDatosPoliza(nuevosDatos, idPoliza)
{
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'post',
		dataType: 'json',
		data: {param:10008, datos:nuevosDatos},
		success: function(r){
			if(r.success){
				
				if(r.accion == 1){

					if($('#selectTipoEndoso').val()==1){
						paramTipoEnd = 1;
					}else if($('#selectTipoEndoso').val()==2){
						paramTipoEnd = 2;
					}else if($('#selectTipoEndoso').val()==3){
						paramTipoEnd = 3;
					}else if($('#selectTipoEndoso').val()==4){
						paramTipoEnd = 4;
					}else if($('#selectTipoEndoso').val()==5){
						paramTipoEnd = 5;
					}

					registrarEndoso(idPoliza, paramTipoEnd);

				}else if(r.accion==2){
					alertify.notify('No se realizaron modificaciones en la poliza');
					$('#modalSeleccionarEnd, #modalEndosoVehiculo, #modalEndosoTomador, #modalEndosoCobertura').modal('close');

				}else{
					alertify.error('Ocurrio un problema al procesar el endoso');
					$('#modalSeleccionarEnd, #modalEndosoVehiculo, #modalEndosoTomador, #modalEndosoCobertura').modal('close');
				}

				tablaGenerarEndoso.ajax.reload();

			}else{
				alertify.error('Ocurrio un problema al procesar el endoso');
				$('#modalSeleccionarEnd, #modalEndosoVehiculo, #modalEndosoTomador, #modalEndosoCobertura').modal('close');
			}
		}
	});	
}

function registrarEndoso(idPoliza, paramTipoEnd)
{

	var tipoEnd = paramTipoEnd;

	//Usamos la variable tipoEnd para saber el tipo de endoso que vamos a registrar. El valor lo obtenemos por el select o lo pasamos por parametro.
	
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'post',
		dataType: 'json',
		data: {param:20000, tipoEnd:tipoEnd, idPoliza:idPoliza},
		success: function(r){
			if(r.success){

				$('#modalCambiarEstadoPoliza, #modalSeleccionarEnd, #modalEndosoVehiculo, #modalEndosoTomador, #modalEndosoCobertura').modal('close');

				tablaGenerarEndoso.ajax.reload();

				if(tipoEnd == 1){
					alertify.success('Endoso "Modificacion de Riesgo" registrado con exito');
				}else if(tipoEnd == 2){
					alertify.success('Endoso "Modificacion de Datos Nominales" registrado con exito');
				}else if(tipoEnd == 3){
					alertify.success('Endoso "Modificacion de Datos del Vehiculo" registrado con exito');
				}else if(tipoEnd == 4){
					alertify.success('Endoso "Modificacion de Asegurado" registrado con exito');
				}else if(tipoEnd == 5){
					alertify.success('Endoso "Cambio de Cobertura" registrado con exito');
				}else if(tipoEnd == 6){
					alertify.success('Endoso "Cambio de Estado de Poliza" registrado con exito');
				}

			}else{
				alertify.error('Ha ocurrido un problema al registrar el Endoso');
			}
		}
	});
}

function modificarDescripcion(idEndoso)
{
	$('#modalDescripcionEndoso').modal('open');
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'post',
		dataType: 'json',
		data: {param:20002, idEndoso:idEndoso},
		success: function(r){
			if(r.data['success']){

				$('#desc').val(r.data['descripcion']);
				$('#numeroEndDesc').html(r.data['endosoNum']);
				actualizarDescripcion(r.data['descripcion'], r.data['idEndoso']);

			}else{
				alertify.error('Ha ocurrido un problema al mostrar la informacion solicitada');
			}
		}
	});
}

function actualizarDescripcion(desAct, idEndoso)
{
	$('#aceptarDescEnd').off('click');
	$('#aceptarDescEnd').on('click', function(){
		var nuevaDesc = $('#desc').val();
		if(desAct == nuevaDesc){
			alertify.notify('No se realizaron cambios en la descripcion del endoso');
			$('#modalDescripcionEndoso').modal('close');
		}else{
			$.ajax({
				url: 'scripts/aseguradora.php',
				type: 'post',
				dataType: 'json',
				data: {param:20003, nuevaDesc:nuevaDesc, idEndoso:idEndoso},
				success: function(r){
					if(r.success){
						$('#modalDescripcionEndoso').modal('close');
						alertify.success('Descripcion modificada');
						tablaListadoEndososPorPoliza.ajax.reload();
					}else{
						$('#modalDescripcionEndoso').modal('close');
						alertify.error('Ha ocurrido un problema al modificar la descripcion del endoso');
					}
				}
			});
			
		}
	});
}

function sumarDias(fecha, dias)
{
  fecha.setDate(fecha.getDate() + dias);

  var di = fecha.getDate();
  var me = fecha.getMonth() + 1;
  var anio = fecha.getFullYear();

  if(di < 10){
    dia = "0"+di;
  }else{
    dia = di;
  }
  
  if(me < 10){
    mes = "0"+me;
  }else{
    mes = me;
  }

  fecha = dia+'/'+mes+'/'+anio;

  return fecha;
}

function mostrarEndososPorPoliza(idPoliza)
{	
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'post',
		dataType: 'json',
		data: {param:20001, id:idPoliza},
		success: function(r){
			if(r.success){

				$('#numeroPolListado').html(r.numPol);
				$('#nombreTomador').html(r.tom);

				tablaListadoEndososPorPoliza = $('#mostrarEndososPorPoliza').DataTable({
					"destroy": true,
					"language": {
						"zeroRecords": "No se encontraron resultados",
				    	"sSearch": "Buscar Endoso",
				    },      
					"scrollY": 370,
					"bPaginate": false,
					"dom": 'ft',
					"order": [[1, "desc"],[2, "desc"]],
					"lengthMenu": [[5, 10], [5, 10]],
					"aoColumnDefs": [
				      { 'bSortable': false, 'aTargets': [5] }
				    ],
				    "ajax":{  
				        "url": "scripts/tablas/tablaMostrarEndososPorPoliza.php", 
				        "method": 'POST',
				        "data":{idPol:idPoliza},
				        "dataSrc":""
				    },
				    "columns": [                    
			          {data:"nro",   	   title: "Nro end.",    width:"10%"},
			          {data:"fecha", 	   title: "Registro",    width:"10%"},
			          {data:"hora",  	   title: "Hora",        width:"10%"},
			          {data:"tipo",  	   title: "Tipo",        width:"32%"},
			          {data:"descripcion", title: "Descripcion", width:"35%"},
			          {data:"btn", 		   title: "",            width:"3%"},
			          
			        ]

				});
				
			}else{
				alertify.error('Ha ocurrido un problema al mostrar la informacion solicitada');
			}
		}
	});
}

function tablaGenerarEndoso()
{
	tablaGenerarEndoso = $('#mostrarPolizasTabla').DataTable({
		"language": {
			"zeroRecords": "No se encontraron polizas registradas en la base de datos.",
        	"sSearch": "Buscar poliza",
        },
        "ajax": "scripts/tablas/tablaGenerarEndoso.php",
        "scrollY": 370,
        "scrollCollapse": true,
		"dom": 'ft',
		"order": [[5, "desc"]],
		"lengthMenu": [[5, 10], [5, 10]],
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [6] }
        ],
        "columns": [                    
          {data:"nro", title: "Poliza Nro",width:""},
          {data:"tomador", title: "Tomador",width:""},
          {data:"vehiculo", title: "Vehiculo",width:""},
          {data:"vigini", title: "Inicio Vig",width:""},
          {data:"vigfin", title: "Fin Vig",width:""},
          {data:"estado", title: "Estado",width:""},
          {data:"boton", title: "",width:""}
        ]
	});

	return tablaGenerarEndoso;
}

function tablaListadoEndosos()
{
	tablaListadoEndosos = $('#listadoEndososTabla').DataTable({
		"language": {
			"zeroRecords": "No se encontraron endosos registrados",
        	"sSearch": "Buscar poliza",
        },
        "ajax": "scripts/tablas/tablaListadoEndosos.php",        
        "scrollY": 370,
        "scrollCollapse": true,
        "dom": 'ft',
		"order": [[2, "asc"]],
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [3] }
        ],
        "columns": [                    
          {data:"nro", title: "Poliza Nro",width:""},
          {data:"tomador", title: "Tomador",width:""},
          {data:"estado", title: "Estado",width:""},
          {data:"boton", title: "",width:""}
        ]
	});

	return tablaListadoEndosos;
}

function funcionalidadesEndoso()
{

	$('.tabs').tabs();
	$('#selectTipoEndoso').formSelect();

	//Declaramos como modal todo campo con la clase .modal
	$('.modal').modal({
		dismissible: false
	});

	$('input, select').addClass('browser-default');
	
	$('#tomadorField .tituloFormulario').remove();
	$('#info input').removeClass('browser-default');

	//Funciones para mostrar botones y formularios correspondientes segun el tipo de endoso seleccionado

	function actDesCampos(accion){
		todosCampos = `#suma_asegurada, #select_marca, #select_modelo1, #patente, #motor, #nroChasis,
		#nroMotor, #select_clase, #selectTipoUso, #GPS, #ceroKm, #cant_kilometro, #codigoPostal,
		#vehiculoLocalidad, #coberturaAd, #combustible, #eGas, #anio, #pasajeros, #asientos, #color`;
		if(accion=='d'){
			$(todosCampos).prop('disabled', true).css('background', '#eee');
		}else if(accion=='a'){
			$(todosCampos).prop('disabled', false).css('background', 'transparent');
		}

	}
	function limpiarSelects(){
		$("#select_clase, #selectTipoUso, #codigoPostal, #vehiculoLocalidad, #coberturaAd, #combustible").val(0).trigger('change');
		$("#codigoPostal").val('n').trigger('change');
	}

	$('#selectTipoEndoso').off('change');
	$('#selectTipoEndoso').on('change', function(){

		$("#continuarModalTipoEnd").attr('disabled', false);
		actDesCampos('d'); //Por cada change, desactivamos todos los campos

		if($('#selectTipoEndoso').val()==1){

			$("#tituloDatosVehiculo").html("Modificacion de Riesgo");
			camposActivar = "#select_marca, #select_modelo1";
			$(camposActivar).prop('disabled', false).css('background', 'transparent');
		
			$("#select_modelo1").on('change', function(){
				if(
					$("#suma_asegurada").val()!='' || $("#patente").val()!='' || $("#motor").val()!='' || $("#nroChasis").val()!=''
					|| $("#nroMotor").val()!='' || $("#cant_kilometro").val()!='' || $("#anio").val()!=''
				){
					alertify.confirm('Modificacion de riesgo', '¿Desea conservar toda la informacion del vehiculo tal cual?',
					function(){}, 
					function(){
						camposDesactivar = `#suma_asegurada, #patente, #motor, #nroChasis,
						#nroMotor, #select_clase, #selectTipoUso, #GPS, #ceroKm, #cant_kilometro, #codigoPostal,
						#vehiculoLocalidad, #coberturaAd, #combustible, #eGas, #anio, #pasajeros, #asientos, #color`;
						$(camposDesactivar).prop('disabled', false).css('background', 'transparent').val('');
						limpiarSelects();
						$("#GPS, #ceroKm, #eGas").prop('checked', false);
					}).set(configuracionAlert('okCancel','Conservar','Ingresar nueva'));
				}
			});

		}else if($('#selectTipoEndoso').val()==2){

			$("#tituloDatosVehiculo").html("Modificacion de Datos Nominales");
			camposActivar = `#suma_asegurada, #patente, #nroChasis, #nroMotor`;
			$(camposActivar).prop('disabled', false).css('background', 'transparent');

		}else if($('#selectTipoEndoso').val()==3){

			$("#tituloDatosVehiculo").html("Modificacion datos del vehiculo");
			camposActivar = `#motor, #select_clase, #selectTipoUso, #GPS, #ceroKm, #cant_kilometro, #codigoPostal,
							 #vehiculoLocalidad, #coberturaAd, #combustible, #eGas, #anio, #pasajeros, #asientos, #color`;
			$(camposActivar).prop('disabled', false).css('background', 'transparent');

		}else if($('#selectTipoEndoso').val()==4){
			$("#tituloTomadorEndoso").html("Modificacion de Asegurado");
			$("#nuevoTomador").remove();

		}else if($('#selectTipoEndoso').val()==5){

			$("#tituloCoberturaEndoso").html("Cambio de cobertura");
		}
	});
	
	//Cerramos el modal de listado de endosos al presionar aceptar
	$('#cerrarEndososPorPoliza').on('click', function(){
		$('#modalListadoDeEndososPorPoliza').modal('close');
	});
    
	$('#cancelarDescEnd').on('click', function(){
		$('#modalDescripcionEndoso').modal('close');
	});


	//Abilitamos el boton de guardar cambios al realizar una modificacion en el campo de descripcion de endoso
	$('#aceptarDescEnd').prop('disabled', true);

	$('#desc').on('change', function(){
		if($('#desc').val() == ''){
			$('#aceptarDescEnd').prop('disabled', true);
		}else{
			$('#aceptarDescEnd').prop('disabled', false);
		}
	});
}
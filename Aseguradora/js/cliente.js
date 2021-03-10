jQuery(document).ready(function($) {

	funcionalidadesTomador();
	listadoTomadores();
	$("#provincia").on('change', function(){
		mostrarCpC($("#provincia").val(), 0);
	});	

	$("#cp").on('change', function(){
		mostrarLocalidadesC($("#cp").val())
	});		

	$('#select_cliente').on('change',function(){
		mostrarDatosCliente($('#select_cliente').val());
	});

	$('#grabarCliente').on('click', function(){
		validarCliente(1);
	});

	$('#grabarClienteSec').on('click', function(){
		validarCliente(2);
	});

	$(document).off('click', '.editarDatosTom');
	$(document).on('click', '.editarDatosTom', function(){
		$('#modificarDatosTomadorModal').modal('open');
		var id = $(this).attr('value');
		mostrarDatosTomador(id);
	});

	$(document).off('click', '.bajaTom');
	$(document).on('click', '.bajaTom', function(){
		var id = $(this).attr('value');
		eliminarTomador(id);
	});

	$(document).off('click', '.verTom');
	$(document).on('click', '.verTom', function(){
		var id = $(this).attr('value');
		consultarTomador(id);
	});
});

function validarCliente(parametro, datos)
{
	var valNombre = /^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/;
	var valFecha = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
	var email = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;

	$.validator.addMethod("validarNombre", function(value, element) {
		return this.optional(element) || valNombre.test(value) ;
	});

	$.validator.addMethod("validarTipoPer", function(value, element) {
		return this.optional(element) || value == 'fisica' || value == 'juridica';
	});

	$.validator.addMethod("validarFecha", function(value, element) {
		return this.optional(element) || valFecha.test(value) ;
	});

	$.validator.addMethod("validarGenero", function(value, element) {
		return this.optional(element) || value == 'masculino' || value == 'femenino' || value == 'indeterminado';
	});

	$.validator.addMethod('email', function(value, element){
		return this.optional(element) || email.test(value);
	});


	if(parametro==1){
		claseUl = '.mensaje.mensajeModal';
	}else if(parametro==2){
		claseUl = '.mensaje.ul2';
	}else if(parametro==3){
		claseUl = '.mensaje.mensajeModal';
	}


	$("#nuevoCliente").validate({
		rules: {
			"nombre": {required:true, validarNombre:true},
			"documento": {required:true, digits:true, rangelength: [6, 8]},
			"persona": {required:true, validarTipoPer:true},
			"nacionalidad": {required:true, lettersonly:true},
			"fech_nac": {required:true, validarFecha:true},
			"provincia": {required:true, rangelength: [1, 24]},
			"cp": {required:true, rangelength: [0, 9421]},
			"localidad": {required:true, rangelength: [1, 22963]},
			"telefono": {required:true, digits:true, min:6},
			"calle": {required:true},
			"genero": {required:true, validarGenero:true},
			"correo": {email:true}
		},
		messages: {
			"nombre": {required:"Debe ingresar el Nombre", validarNombre:"El Nombre ingresado es incorrecto"},
			"documento": {required:"Debe ingresar el Documento", rangelength:"El Documento ingresado está incompleto", digits:"El Documento ingresado es incorrecto"},
			"persona": {required:"Debe ingresar el tipo de Persona", validarTipoPer:"El tipo de persona seleccionado es incorrecto"},
			"nacionalidad": {required:"Debe ingresar la Nacionalidad", lettersonly:"El Pais ingresado es incorrecto"},
			"fech_nac": {required:"Debe ingresar la fecha de Nacimiento", validarFecha:"La Fecha de Nacimiento/Orig ingresada es incorrecta"},
			"provincia": {required:"Debe seleccionar una Provincia", rangelength:"La Provincia es incorrecta"},
			"cp": {required:"Debe seleccionar un Codigo Postal", rangelength:"El Codigo Postal es incorrecto"},
			"localidad": {required:"Debe seleccionar una Localidad", rangelength:"La Localidad es incorrecta"},
			"telefono": {required:"Debe ingresar un numero de Telefono", digits:"El Telefono ingresado es incorrecto", min:"El Telefono ingresado es incorrecto"},
			"calle": {required:"Debe ingresar una calle"},
			"genero": {required:"Debe seleccionar el Genero", validarGenero:"El Genero seleccionado es incorrecto"},
			"correo": {email:"El E-mail ingresado es incorrecto"}
		},

		highlight: function(element) {
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
    	errorLabelContainer: claseUl,

    	submitHandler: function(form){

    		switch (parametro)
    		{
			    case 1:
			    	//Le pasamos el parametro 'seleccionar' para que se seleccione en la funcion
			        grabarCliente('seleccionar');
			        break;

			    case 2:
			        grabarCliente();
			        break;

			    case 3:
			        modificarDatosTomador(datos);
			        break;
			}
        }
	});
}

function mostrarDatosCliente(idtomador)
{
	jQuery.ajax({
		type:"post",
		data:{param:40000, idtomador:idtomador},
		url: "scripts/aseguradora.php",
		dataType:'json',
		success: function(r){
			if(r.data['success']==true){

				$('#modalcliente').modal('close');

				$("#info").css('display', 'block');
				
				$("#nombreDatos").val(r.data['nombre']);
				$("#docDatos").val(r.data['documento']);
				$("#tipDatos").val(r.data['persona']);
				$("#nacDatos").val(r.data['nacionalidad']);
				$("#proDatos").val(r.data['provincia']);
				$("#locDatos").val(r.data['localidad']);
				$("#cpDatos").val(r.data['cp']);
				$("#calleDatos").val(r.data['calle']);
				$("#genDatos").val(r.data['genero']);
				$("#telDatos").val(r.data['telefono']);
				$("#corrDatos").val(r.data['correo']);
				$("#feNacDatos").val(r.data['fecha_nac']);
			}else{
				alertify.error('Ocurrio un problema al mostrar la informacion');
				//$("#info").css('display', 'none');
				$('#modalcliente').modal('close');
			}
		}
	});
}

function grabarCliente(param)
{
	var nom = $('#nombre').val();
	var docu = $('#documento').val();
	var per = $('#persona').val();
	var nac = $('#nacionalidad').val();
	var pro = $('#provincia').val();
	var loc = $('#localidad').val();
	var cp = $('#cp').val();
	var call = $('#calle').val();
	var gen = $('#genero').val();
	var tel = $('#telefono').val();
	var corr = $('#correo').val();
	var fechNac = $('#fech_nac').val();

	var datos = [nom, docu, per, nac, pro, loc, cp, call, gen, tel, corr, fechNac];

	jQuery.ajax({
		url: "scripts/aseguradora.php",
		type: 'post',
		dataType: 'json',
		data: {param:40001, d:datos},
		success: function(r){
			if(r.success){

				$("#info").css('display', 'block');

				$("#nombre, #documento, #nacionalidad, #calle, #telefono, #correo, #fech_nac").val('');
				$("#persona").val('0');
				$("#provincia").val('nulo').trigger('change');
				$("#cp, #localidad").empty();
				$("#cp").append('<option value="can" disabled selected>Seleccione Provincia</option>');
				$("#localidad").append('<option value="nulo" disabled selected>Seleccione Codigo Postal</option>');
				$("#genero").val('0');
				
				$("#mensaje").html("");
				alertify.success('Tomador Registrado con Exito');
				
			}else{

				$("#nombre, #documento, #nacionalidad, #calle, #telefono, #correo, #fech_nac").val('');
				$("#persona").val('0');
				$("#provincia").val('nulo').trigger('change');
				$("#cp, #localidad").empty();
				$("#cp").append('<option value="can" disabled selected>Seleccione Provincia</option>');
				$("#localidad").append('<option value="nulo" disabled selected>Seleccione Codigo Postal</option>');
				$("#genero").val('0');

				$("#mensaje").html("");
				alertify.error('Ocurrio un problema al registrar el tomador');
			}

			if(param=='seleccionar'){
				listadoTomadores(r.idTomador);
				mostrarDatosCliente(r.idTomador);
			}

			$('#modalcliente').modal('close');
		}
	});
}

function mostrarLocalidadesC(cp, loc)
{
	jQuery("#localidad").empty();
	jQuery("#localidad").append('<option value="" disabled selected>Seleccione</option>');
	$.ajax({
		type:"post",
		data:{param:80000, cp:cp},
		url: "scripts/aseguradora.php",
		dataType:'json',
		success: function(r){
			if(r!=null){
				$.each(r.data, function(i,val){
					if(loc==val.localidadId){
						$("#localidad").append('<option value="' + val.localidadId + '" selected>' + val.localidadNombre + '</option>');
					}else{
						$("#localidad").append('<option value="' + val.localidadId + '">' + val.localidadNombre + '</option>');
					}
				});
			}else{
				alertify.error('A ocurrido un error al mostrar las localidades');
			}
		}
	});
}

function mostrarCpC(idPro, cp)
{
	jQuery("#cp").empty();
	jQuery("#cp").append('<option value="nulo" disabled selected>Seleccione</option>');
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {param:80002, id:idPro},
		url: 'scripts/aseguradora.php',
		success: function(r){
			if(r!=null){
				$.each(r.data, function(i, val){
					if(cp==val.cpNum){
						$("#cp").append('<option value="' + val.cpNum + '" selected>' + val.cpNum + '</option>');
					}else{
						$("#cp").append('<option value="' + val.cpNum + '">' + val.cpNum + '</option>');
					}
				});
			}else{
				alertify.error('Ha ocurrido un error al mostrar los codigos postales');
			}
		}
	});
}

function mostrarDatosTomador(id)
{
	$.ajax({
		url: 'scripts/aseguradora.php',
		dataType: 'json',
		type: 'post',
		data: {param:40002, id:id},
		success: function(r){
			if(r.success){

				$(".tituloFormulario.tomador").html('datos del tomador '+' '+r.nroTom);

				$("#nroTom").html(r.nroTom);

				persona = r.persona.toLowerCase();
				genero = r.genero.toLowerCase();

				$("#nombre").val(r.nombre);
				$("#documento").val(r.documento);
				$("#persona").val(persona);
				$("#nacionalidad").val(r.nacionalidad);
				$("#provincia").val(r.provinciaId).trigger('change');
				mostrarCpC(r.provinciaId, r.cp);
				mostrarLocalidadesC(r.cp, r.localidadId);
				$("#calle").val(r.calle);
				$("#genero").val(genero);
				$("#telefono").val(r.telefono);
				$("#correo").val(r.correo);
				$("#fech_nac").val(r.fecha_nac);

				$(document).off('click', '#grabarClienteAdmin');
				$(document).on('click', '#grabarClienteAdmin', function(){
					validarCliente(3, id);
				});
				
			}else{
				alertify.error('Hubo un error al mostrar la informacion del tomador');
			}
		}
	});
}

function modificarDatosTomador(id)
{	
	no = $("#nombre").val();
	dd = $("#documento").val(); 
	pe = $("#persona").val(); 
	na = $("#nacionalidad").val(); 
	fe = $("#fech_nac").val(); 
	pr = $("#provincia").val(); 
	cp = $("#cp").val(); 
	lo = $("#localidad").val();
	te = $("#telefono").val(); 
	ca = $("#calle").val(); 
	ge = $("#genero").val(); 
	co = $("#correo").val();

	nuevosDatos = [no, dd, pe, na, fe, pr, cp, lo, te, ca, ge, co, id];

	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'post',
		dataType: 'json',
		data: {param:40003, datos:nuevosDatos},
		success: function(r){
			if(r.success){
				if(r.accion == 1){

					alertify.success('Datos del tomador modificados con exito');
					$("#modificarDatosTomadorModal").modal('close');
					tablaModificarDatosTomadores.ajax.reload();

				}else if(r.accion == 2){

					alertify.notify('No se realizaron modificaciones');
					$("#modificarDatosTomadorModal").modal('close');
					tablaModificarDatosTomadores.ajax.reload();

				}else{

					alertify.error('Ocurrio un problema al modificar los datos del tomador');
					$("#modificarDatosTomadorModal").modal('close');
					tablaModificarDatosTomadores.ajax.reload();

				}
			}else{
				alertify.error('Ocurrio un problema al realizar la peticion');
				$("#modificarDatosTomadorModal").modal('close');
				tablaModificarDatosTomadores.ajax.reload();
			}
		}
	});
}

function eliminarTomador(id)
{
	alertify.confirm('Eliminar cliente', '¿Dar de baja el cliente? Se eliminara por completo del sistema, y sus datos no podran recuperarse.',
	 function(){

		$.ajax({
			url: 'scripts/aseguradora.php',
			dataType: 'json',
			type: 'post',
			data: {param: 40004, id:id},
			success: function(r){
				if(r.success){
					if(r.accion == 1)
					{
						alertify.success('Cliente dado de baja con exito');
						tablaModificarDatosTomadores.ajax.reload();
					}else if(r.accion == 2)
					{
					  if(r.cantPol == 1){
					  	texto = 'El cliente no puede darse de baja ya que es tomador de una poliza';
					  }else{
					  	texto = 'El cliente no puede darse de baja ya que es tomador de '+r.cantPol+' polizas';
					  }
					  alertify.alert('Eliminar cliente', texto).set(configuracionAlert('ok'));
					  tablaModificarDatosTomadores.ajax.reload();

					}else
					{
						alertify.error('Ocurrio un problema al dar de baja el cliente');
						tablaModificarDatosTomadores.ajax.reload();
					}
				}else
				{
					alertify.error('Ocurrio un problema al dar de baja el cliente');
					tablaModificarDatosTomadores.ajax.reload();
				}
			}
		});

	 },
	 function(){}
	 ).set(configuracionAlert('okCancel','Si'));

}

function consultarTomador(id)
{
	$("#consultarTomModal").modal('open');
	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'post',
		dataType: 'json',
		data: {param: 40002, id:id},
		success: function(r){
			if(r.success){

				$("#nroTom").html('#'+r.nroTom);

				$(".infoTom").html(r.nombre+' '+r.documento);
				$(".nomT").html(r.nombre);
				$(".docT").html(r.documento);
				$(".perT").html(r.persona);
				$(".nacioT").html(r.nacionalidad);
				$(".proT").html(r.provinciaNombre);
				$(".locT").html(r.localidadNombre);
				$(".cpT").html(r.cp);
				$(".callT").html(r.calle);
				$(".genT").html(r.genero);
				$(".telT").html(r.telefono);
				$(".corrT").html(r.correo);
				$(".nacimT").html(r.fecha_nac);

			}else{
				alertify.erorr('Ocurrio un problema al mostrar la informacion del tomador');
			}
		}
	});
}

function listadoTomadores(tomadorSelect){
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {param:40005},
		url: 'scripts/aseguradora.php',
		success: function(r){
			if(r!=null){
				jQuery("#select_cliente").empty();
				jQuery("#select_cliente").append('<option value="0" disabled selected>Seleccione</option>');
				$.each(r, function(i, val){
					if(tomadorSelect==val.id){
						$("#select_cliente").append('<option value="'+val.id+'" selected>'+val.nombre+' - '+val.doc+'</option>');
					}else{
						$("#select_cliente").append('<option value="'+val.id+'">'+val.nombre+' - '+val.doc+'</option>');
					}
				});
			}else{
				alertify.error('Ha ocurrido un error al mostrar el listado de tomadores');
			}
		}
	});
}

function tablaModificarDatosTomadores()
{
	tablaModificarDatosTomadores = $('#modificarDatosTomadorTabla').DataTable({
		"language": {
			"zeroRecords": "No se encontraron tomadores",
        	"sSearch": "Buscar",
        },
        "ajax": "scripts/tablas/tablaModificarDatosTomadores.php",    
        "scrollY": 370,
        "scrollCollapse": true,
		"dom": 'ft',
		"order": [],
		"lengthMenu": [[5, 10], [5, 10]],
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [4] }
        ],

        "columns": [                    
			{data:"num_tom", title: "Nro Tom",width:""},
			{data:"nombre", title: "Nombre",width:""},
			{data:"provincia", title: "Provincia",width:""},
			{data:"localidad", title: "Localidad",width:""},
			{data:"acciones", title: "Acciones",width:"20%"}
        ]     
	});

	return tablaModificarDatosTomadores;
}

function funcionalidadesTomador()
{
	$('input, select').addClass('browser-default');

	//Establecemos los tabs a todos los campos con la clase tabs (requerido por materialize para funcionar)
	$('.tabs').tabs();

	//Declaramos como modal todo campo con la clase .modal
	$('.modal').modal({
		dismissible: false
	});

	//Aplicamos la libreria select2 a los campos requeridos
	$('#provincia, #localidad, #cp').select2({width: "100%"});

	//Agregamos el texto a los campos cada vez que recargamos las funciones
	$("#cp").append('<option value="can" disabled selected>Seleccione Provincia</option>');
	$("#localidad").append('<option value="nulo" disabled selected>Seleccione Codigo Postal</option>');

	//Desabilitamos los campos donde se muestra la informacion del tomador en emitir poliza.
	$("#nombreDatos,#docDatos,#tipDatos,#nacDatos,#proDatos,#locDatos,#cpDatos,#calleDatos,#genDatos,#telDatos,#corrDatos,#feNacDatos").attr('disabled', 'disabled');

	//Se aplica el complemento Datepicker al input llamado "fech_nac" en registro de tomador.
	$("#fech_nac").datepicker({
		dayNames: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
	    dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
	    monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
	    monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
		dateFormat: 'dd/mm/yy',

		changeMonth: true,
        changeYear: true
	}).prop('readonly', true);

	/*Desabilitar campos segun el tipo de persona que se elija al cargar cliente/tomador.
	En caso de seleccionar el tipo de persona Juridica, el campo para seleccionar genero 
		no estara disponible por lo que se deshabilitara.*/
	$('#genero option[value="'+"indeterminado"+'"]').attr("disabled", true);
	$("#persona").on('change', function(){
		if($("#persona").val() == 'juridica'){
		 	$("#genero").prop('disabled', true);
		 	$("#genero").val("indeterminado").trigger('change');
		 	$('#genero option[value="'+"indeterminado"+'"]').attr("disabled", false);
		 }else{
		 	$('#genero option[value="'+"indeterminado"+'"]').attr("disabled", true);
		 	$("#genero").prop('disabled', false);
		 	$("#genero").val('0');
		 }
	});

	// Limpiamos los campos del formulario de cliente al presionar las opciones de cancelar.
	$('#cancelarCliente, #cancelarClienteSec, #cancelarClienteAdmin').on('click',function(){

		$("#nuevoCliente").validate().destroy();

		$("#nombre, #documento, #nacionalidad ,#calle ,#telefono ,#correo ,#fech_nac").val('');
		$("#persona, #genero").val('0');
		$("#provincia").val('n').trigger('change');
		$("#cp").empty().append('<option value="can" disabled selected>Seleccione Provincia</option>');
		$("#localidad").empty().append('<option value="nulo" disabled selected>Seleccione Codigo Postal</option>');

		$("#mensaje").html("");
	});
}
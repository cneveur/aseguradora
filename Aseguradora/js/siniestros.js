jQuery(document).ready(function($) {
	
	funcionalidadesSiniestro();
	validarSiniestro();
	
	$("#cancelarCambiarEstadoSin").on('click', function(){
		$("#modalCambiarEstado").modal('close');
	});
	
	$("#btnVolverModificarSin").on('click', function(){
		$('#contenidoC').load('vista/siniestro/elementosSiniestro/administrarSiniestro.php', function(){
			tablaAdministrarSiniestro();
		});
	});

	$(document).off('click', '.ModRestoDatos');
	$(document).on('click', '.ModRestoDatos', function(){
		var id = $(this).attr('value');
		traerDatosSin(id);
	});

	$(document).off('click', '#verSiniestro');
	$(document).on('click', '#verSiniestro', function(){
		var id = $(this).attr('value');
		mostrarDatosSiniestro(id);
	});

	$(document).off('click', '.modImagenSin');
	$(document).on('click', '.modImagenSin', function(){
		var idSin = $(this).attr('value');
		$("#modalAdminImgSin").modal('open');
		mostrarImgSiniestro(idSin);
	});

	$(document).off('click', '.eliminarImg');
	$(document).on('click', '.eliminarImg', function(){
		var idImg = $(this).attr('value');
		eliminarImgSin(idImg);
	});

});

function validarSiniestro()
{
	var valFecha = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
	$.validator.addMethod("validarFecha", function(value, element) {
		return this.optional(element) || valFecha.test(value) ;
	});
	$.validator.addMethod("valFechaCorrecta", function(value, element) {
		return this.optional(element) || valFechaCorrecta(value)==true
	});

	var valHora = /^([0-2][0-3])|([0-1][0-9]):[0-5][0-9]$/;
	$.validator.addMethod("validarHora", function(value, element) {
		return this.optional(element) || valHora.test(value);
	});
	$.validator.addMethod("valHoraCorrecta", function(value, element) {
		return this.optional(element) || validarHoraCorrecta(value)==true
	});

	function valFechaCorrecta(fecha){
		var fechaIngresada = moment($("#fechaOcurrencia").val(), 'DD/MM/YYYY').format('MM/DD/YYYY')
		var fechaActual = moment(new Date()).format("MM/DD/YYYY");
		if(fechaIngresada>fechaActual){
			return false;
		}else if(fechaIngresada<=fechaActual){
			return true;
		}
	}

	function validarHoraCorrecta(hora){
	
		var horaUsuario = moment(hora, 'HH:mm').format('HH:mm');
		var horaActualMenosDiez = moment(new Date()).subtract(10, 'minutes').format("HH:mm"); //Horario actual menos 10 minutos
		
		//Obtenemos la fecha ingresada y la fecha actual
		var fechaIngresada = moment($("#fechaOcurrencia").val(), 'DD/MM/YYYY').format('MM/DD/YYYY')
		var fechaActual = moment(new Date()).format("MM/DD/YYYY");

		console.log('')

		//Si el dia en que ocurrio el siniestro es el presente, la hora maxima a seleccionar es 10 minutos antes a la actual
		if(fechaIngresada==fechaActual){
			if(horaUsuario>horaActualMenosDiez){
				return false;
			}else if(horaUsuario<horaActualMenosDiez){
				return true;
			}else if(horaUsuario==horaActualMenosDiez){
				return true;
			}
		}else if(fechaIngresada>fechaActual){
			return false;
		}else if(fechaIngresada<fechaActual){
			return true;
		}
	}
	
	$("#divSiniestro").validate({
		rules:{
		 	"fechaOcurrencia": {required:true, validarFecha:true, valFechaCorrecta:true},
			"horaOcurrencia":  {required:true, validarHora:true, valHoraCorrecta:true},
			"LesMuer": 		   {required:true},
			"selectPolizas":   {required:true},
			"TipoSin":         {required:true}
		},
		messages: {
           	"fechaOcurrencia": {required:"Debe seleccionar la Fecha de Ocurrencia", validarFecha:"El formato de Fecha de Ocurrencia es incorrecto", valFechaCorrecta: "La Fecha de Ocurrencia ingresada no esta permitida"},
			"horaOcurrencia":  {required:"Debe seleccionar la Hora de Ocurrencia", validarHora:"El formato de Hora de Ocurrencia es incorrecto" , valHoraCorrecta: "La hora de Ocurrencia ingresada no esta permitida"},
			"LesMuer":         {required:"Debe seleccionar si Hubo Lesion/Muerte"},
			"selectPolizas":   {required:"Debe seleccionar una Poliza"},
			"TipoSin":         {required:"Debe seleccionar un Tipo de Siniestro"}
       },
       highlight: function(element) {
       		if($(element).hasClass('select2')){
       			$(element).siblings('span').find('.selection').find('.select2-selection').addClass('claseError');
       		}else{
       			$(element).addClass('claseError');
       		}
	   },
	   unhighlight: function(element) {
	   		if($(element).hasClass('select2')){
       			$(element).siblings('span').find('.selection').find('.select2-selection').removeClass('claseError'); 
       		}else{
       			$(element).removeClass('claseError'); 
       		}
	   },

	   errorElement : 'li',
       errorLabelContainer: '#mensajeSiniestro',

       submitHandler: function(form){
	   	validarDenunciante();
       }
	});
}

function validarDenunciante()
{
	$('.tabs').tabs('select','test2');
	boton = '<button type="submit" class="btn btn-flat green" id="siguienteDenun"> siguiente <i class="fas fa-caret-right"></i> </button>';
	$(".btnSigDen").html(boton);


	$.validator.addMethod("validarSelectDenAs", function(value, element) {
		return this.optional(element) || value==1 || value==2;
	});

	var valNombre = /^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/;
	$.validator.addMethod("validarNombre", function(value, element) {
		return this.optional(element) || valNombre.test(value) ;
	});

	var valDNI = /^\d{8}(?:[-\s]\d{4})?$/;
	$.validator.addMethod("valDNI", function(value, element) {
		return this.optional(element) || valDNI.test(value) ;
	});

	var email = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
	$.validator.addMethod('email', function(value, element){
		return this.optional(element) || email.test(value);
	});

	$("#divDenunciante").validate({
		rules:{
			"selectDenunAseg": 	   {required:true, validarSelectDenAs:true},
			"nombreDenunciante":   {required:true, validarNombre:true},
			"dniDenunciante":      {required:true, valDNI:true},
			"domicDenunciante":    {required:true},
			"telefonoDenunciante": {required:true, rangelength:[10,15]},
			"emailDenunciante":    {email: true}
		},
		messages: {
		   "selectDenunAseg": 	  {required:"Debe seleccionar si el Denunciante es la misma persona que el Asegurado", validarSelectDenAs:"La opcion seleccionada no es permitida"},
           "nombreDenunciante":   {required:"Debe ingresar el Nombre del Deunuciante", validarNombre:"El Nombre del Denunciante es incorrecto"},
		   "dniDenunciante":      {required:"Debe ingresar el DNI del Deunuciante", valDNI:"El DNI del Denunciante es incorrecto"},
		   "domicDenunciante":    {required:"Debe ingresar el Domicilio del Deunuciante"},
		   "telefonoDenunciante": {required:"Debe ingresar un Telefono del Deunuciante", rangelength:"El Telefono del Denunciante ingresado no es valido"},
		   "emailDenunciante":    {email: "Debe ingresar un E-Mail correcto"}
       },
       highlight: function(element) {
       		$(element).addClass('claseError');
	   },
	   unhighlight: function(element) {
	   		$(element).removeClass('claseError');
	   },

	   errorElement : 'li',
       errorLabelContainer: '#mensajeDenunciante',

       submitHandler: function(form){
           validarAccidente();
       }
	});
}

function validarAccidente()
{
	$('.tabs').tabs('select','test3');
	valor = 0;
	if($('#TipoSin').val()==5){
		valor = 5;
		boton = '<button type="submit" class="btn btn-flat green" id="siguienteAcc"> siguiente <i class="fas fa-caret-right"></i> </button>';
	}else{
		valor = 10;
		boton = `<button type="submit" class="btn btn-flat green" id="cargarSiniestro"> grabar siniestro <i class="fas fa-save"></i> </button>
			 	<button type="button" class="btn btn-flat red" id="cancelarSin" onclick="cancelarProceso()"> cancelar <i class="fas fa-times"></i> </button>`;
	}
	$(".btnAcc").html(boton);

	$('#provinciaAcc, #localidadAcc').change(function(){
	    if ($(this).val()!="")
	    {
	        $(this).valid();
	    }
	});

	$("#divAccidente").validate({
		rules:{
		   "decripAcc":    {required:true, minlength:[25]},
		   "provinciaAcc": {required:true},
		   "localidadAcc": {required:true},
		   "calleAcc":     {minlength:[2]},
		   "alturaAcc":    {minlength:[2]}
		},
		messages: {
           "decripAcc":    {required:"Debe ingresar la Descripcion", minlength:"La Descripcion del Accidente es muy corta"},
		   "provinciaAcc": {required:"Debe seleccionar una Provincia"},
		   "localidadAcc": {required:"Debe seleccionar una Localidad"},
		   "calleAcc": 	   {minlength:"La Calle ingresada es muy corta"},
		   "alturaAcc":    {minlength:"La Altura ingresada es muy corta"}
        },
        highlight: function(element) {
        	if($(element).hasClass('select2')){
       			$(element).siblings('span').find('.selection').find('.select2-selection').addClass('claseError');
       		}else{
       			$(element).addClass('claseError');
       		}
	    },
	    unhighlight: function(element) {
	    	if($(element).hasClass('select2')){
       			$(element).siblings('span').find('.selection').find('.select2-selection').removeClass('claseError');
       		}else{
       			$(element).removeClass('claseError');
       		}
	    },
 
	    errorElement : 'li',
        errorLabelContainer: '#mensajeAccidente',
 
        submitHandler: function(form){
			if(valor==5){
				validarReclamante();
			}else if(valor==10){
				grabarSiniestro();
			}
            
        }
	});
}

function validarReclamante()
{
	$('.tabs').tabs('select','test4');
	boton = '<button type="submit" class="btn btn-flat green" id="siguienteRec"> siguiente <i class="fas fa-caret-right"></i> </button>';
	$(".btnSigRec").html(boton);

	var valNombre = /^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/;
	$.validator.addMethod("validarNombre", function(value, element) {
			return this.optional(element) || valNombre.test(value) ;
	});

	var valDNI = /^\d{8}(?:[-\s]\d{4})?$/;
	$.validator.addMethod("valDNI", function(value, element) {
			return this.optional(element) || valDNI.test(value) ;
	});

	$("#divReclamante").validate({
		rules:{
		   "nombreReclamante":   {required:true, validarNombre:true},
		   "domicReclamante":    {required:true},
		   "telefonoReclamante": {required:true},
		   "dniReclamante":      {required:true, valDNI:true}
		},
		messages: {
           "nombreReclamante":   {required:"Debe ingresar el Nombre del Reclamante", validarNombre:"El Nombre del Reclamante es incorrecto"},
		   "domicReclamante":    {required:"Debe ingresar el Domicilio del Reclamante"},
		   "telefonoReclamante": {required:"Debe ingresar el Telefono del Reclamante"},
		   "dniReclamante":      {required:"Debe ingresar el DNI del Reclamante", valDNI:"El DNI del Reclamante es incorrecto"}
        },
        highlight: function(element) {
        	$(element).addClass('claseError');
	    },
	    unhighlight: function(element) {
	    	$(element).removeClass('claseError');
	    },

	    errorElement : 'li',
        errorLabelContainer: '#mensajeReclamante',
 
        submitHandler: function(form){
           validarConductor();
        }
	});
}

function validarConductor()
{
	$('.tabs').tabs('select','test5');
	boton = `<button type="submit" class="btn btn-flat green" id="cargarSiniestro"> grabar siniestro <i class="fas fa-save"></i> </button>
			 <button type="button" class="btn btn-flat red" id="cancelarSin" onclick="cancelarProceso()"> cancelar <i class="fas fa-times"></i> </button>`;
	$(".btnSinConductor").html(boton);

	$.validator.addMethod("validarSelectCondRec", function(value, element) {
		return this.optional(element) || value==1 || value==2;
	});

	var valNombre = /^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/;
	$.validator.addMethod("validarNombre", function(value, element) {
			return this.optional(element) || valNombre.test(value) ;
	});

	var valDNI = /^\d{8}(?:[-\s]\d{4})?$/;
	$.validator.addMethod("valDNI", function(value, element) {
			return this.optional(element) || valDNI.test(value) ;
	});

	$("#divConductor").validate({
		rules:{
		   "selectCondRec": 	{required:true, validarSelectCondRec:true},
		   "nombreConductor":   {required:true, validarNombre:true},
		   "domicConductor":    {required:true},
		   "telefonoConductor": {required:true},
		   "dniConductor":      {required:true, valDNI:true}
		},
		messages: {
		   "selectCondRec": 	{required:"Debe seleccionar si el Conductor es la misma persona que el Reclamante", validarSelectCondRec:"La opcion seleccionada no es permitida"},
           "nombreConductor":   {required:"Debe ingresar el Nombre del Conductor", validarNombre:"El Nombre del Conductor ingresado es incorrecto"},
		   "domicConductor":    {required:"Debe ingresar el Domicilio del Conductor"},
		   "telefonoConductor": {required:"Debe ingresar el Telefono del Conductor"},
		   "dniConductor":      {required:"Debe ingresar el DNI del Conductor", valDNI:"El DNI del Conductor ingresado es incorrecto"}
        },
        highlight: function(element) {
        	$(element).addClass('claseError');
	    },
	    unhighlight: function(element) {
	    	$(element).removeClass('claseError');
	    },
	    
	    errorElement : 'li',
        errorLabelContainer: '#mensajeConductor',
 
        submitHandler: function(form){
          grabarSiniestro();
        }
	});
}

function validarModSiniestro(idSiniestro, fechaHoraMaxima)
{
	$.validator.setDefaults({
       ignore: []
	});

	//siniestro
	var valFecha = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
	$.validator.addMethod("validarFecha", function(value, element) {
		return this.optional(element) || valFecha.test(value) ;
	});
	$.validator.addMethod("valFechaCorrecta", function(value, element) {
		return this.optional(element) || valFechaCorrecta(value)==true
	});
	var valHora = /^([0-2][0-3])|([0-1][0-9]):[0-5][0-9]$/;
	$.validator.addMethod("validarHora", function(value, element) {
		return this.optional(element) || valHora.test(value);
	});
	$.validator.addMethod("valHoraCorrecta", function(value, element) {
		return this.optional(element) || validarHoraCorrecta(value)==true
	});
	function valFechaCorrecta(fecha){
		var fechaIngresada = moment($("#fechaOcurrencia").val(), 'DD/MM/YYYY').format('MM/DD/YYYY')
		var fechaMaxPermitida = moment(fechaHoraMaxima, 'DD/MM/YYYY HH:mm').format('MM/DD/YYYY'); //Fecha de registro
		if(fechaIngresada>fechaMaxPermitida){
			return false;
		}else if(fechaIngresada<=fechaMaxPermitida){
			return true;
		}
	}
	function validarHoraCorrecta(hora){

		var fechaMaxPermitida = moment(fechaHoraMaxima, 'DD/MM/YYYY HH:mm').format('MM/DD/YYYY'); //Fecha de registro
		var horaMaxPermitida = moment(fechaHoraMaxima, 'DD/MM/YYYY HH:mm').subtract(10, 'minutes').format("HH:mm");//Hora de registro menos 10 minutos

		var horaIngresada = moment(hora, 'HH:mm').format('HH:mm');
		
		//Obtenemos la fecha ingresada y la fecha actual
		var fechaIngresada = moment($("#fechaOcurrencia").val(), 'DD/MM/YYYY').format('MM/DD/YYYY')
		var fechaActual = moment(new Date()).format("MM/DD/YYYY");

		//Si el dia en que ocurrio el siniestro es el presente, la hora maxima a seleccionar es 10 minutos antes a la actual
		if(fechaIngresada>=fechaMaxPermitida){
			if(horaIngresada>horaMaxPermitida){
				return false;
			}else if(horaIngresada<horaMaxPermitida){
				return true;
			}else if(horaIngresada==horaMaxPermitida){
				return true;
			}
		}else if(fechaIngresada<fechaMaxPermitida){
			return true;
		}
	}

	//denunciante
	$.validator.addMethod("validarSelectDenAs", function(value, element) {
		return this.optional(element) || value==1 || value==2;
	});

	var valNombre = /^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/;
	$.validator.addMethod("validarNombre", function(value, element) {
		return this.optional(element) || valNombre.test(value) ;
	});

	var valDNI = /^\d{8}(?:[-\s]\d{4})?$/;
	$.validator.addMethod("valDNI", function(value, element) {
		return this.optional(element) || valDNI.test(value) ;
	});

	var email = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
	$.validator.addMethod('email', function(value, element){
		return this.optional(element) || email.test(value);
	});

	$.validator.addMethod("validarSelectCondRec", function(value, element) {
		return this.optional(element) || value==1 || value==2;
	});

	$("#modificarSiniestro").validate({
		rules:{
			"fechaOcurrencia": {required:true, validarFecha:true, valFechaCorrecta:true},
			"horaOcurrencia":  {required:true, validarHora:true, valHoraCorrecta:true},
			"LesMuer": 		   {required:true},
			"selectPolizas":   {required:true},
			"TipoSin":         {required:true},

			"selectDenunAseg": 	   {required:true, validarSelectDenAs:true},
			"nombreDenunciante":   {required:true, validarNombre:true},
			"dniDenunciante":      {required:true, valDNI:true},
			"domicDenunciante":    {required:true},
			"telefonoDenunciante": {required:true, rangelength:[10,15]},
			"emailDenunciante":    {email: true},

   			"decripAcc":    {required:true, minlength:[25]},
			"provinciaAcc": {required:true},
			"localidadAcc": {required:true},
			"calleAcc":     {minlength:[2]},
			"alturaAcc":    {minlength:[2]},

			"nombreReclamante":   {required:true, validarNombre:true},
			"domicReclamante":    {required:true},
			"telefonoReclamante": {required:true},
			"dniReclamante":      {required:true, valDNI:true},

			"selectCondRec": 	{required:true, validarSelectCondRec:true},
			"nombreConductor":   {required:true, validarNombre:true},
			"domicConductor":    {required:true},
			"telefonoConductor": {required:true},
			"dniConductor":      {required:true, valDNI:true}
		},
		messages: {
			"fechaOcurrencia": {required:"Debe seleccionar la Fecha de Ocurrencia", validarFecha:"El formato de Fecha de Ocurrencia es incorrecto", valFechaCorrecta: "La Fecha de Ocurrencia ingresada no esta permitida"},
			"horaOcurrencia":  {required:"Debe seleccionar la Hora de Ocurrencia", validarHora:"El formato de Hora de Ocurrencia es incorrecto" , valHoraCorrecta: "La hora de Ocurrencia ingresada no esta permitida"},
			"LesMuer":         {required:"Debe seleccionar si Hubo Lesion/Muerte"},
			"selectPolizas":   {required:"Debe seleccionar una Poliza"},
			"TipoSin":         {required:"Debe seleccionar un Tipo de Siniestro"},

 			"selectDenunAseg": 	   {required:"Debe seleccionar si el Denunciante es la misma persona que el Asegurado", validarSelectDenAs:"La opcion seleccionada no es permitida"},
			"nombreDenunciante":   {required:"Debe ingresar el Nombre del Deunuciante", validarNombre:"El Nombre del Denunciante es incorrecto"},
			"dniDenunciante":      {required:"Debe ingresar el DNI del Deunuciante", valDNI:"El DNI del Denunciante es incorrecto"},
			"domicDenunciante":    {required:"Debe ingresar el Domicilio del Deunuciante"},
			"telefonoDenunciante": {required:"Debe ingresar un Telefono del Deunuciante", rangelength:"El Telefono del Denunciante ingresado no es valido"},
			"emailDenunciante":    {email: "Debe ingresar un E-Mail correcto"},

 			"decripAcc":    {required:"Debe ingresar la Descripcion", minlength:"La Descripcion del Accidente es muy corta"},
			"provinciaAcc": {required:"Debe seleccionar una Provincia"},
			"localidadAcc": {required:"Debe seleccionar una Localidad"},
			"calleAcc": 	   {minlength:"La Calle ingresada es muy corta"},
			"alturaAcc":    {minlength:"La Altura ingresada es muy corta"},

			"nombreReclamante":   {required:"Debe ingresar el Nombre del Reclamante", validarNombre:"El Nombre del Reclamante es incorrecto"},
			"domicReclamante":    {required:"Debe ingresar el Domicilio del Reclamante"},
			"telefonoReclamante": {required:"Debe ingresar el Telefono del Reclamante"},
			"dniReclamante":      {required:"Debe ingresar el DNI del Reclamante", valDNI:"El DNI del Reclamante es incorrecto"},

			"selectCondRec": 	{required:"Debe seleccionar si el Conductor es la misma persona que el Reclamante", validarSelectCondRec:"La opcion seleccionada no es permitida"},
			"nombreConductor":   {required:"Debe ingresar el Nombre del Conductor", validarNombre:"El Nombre del Conductor ingresado es incorrecto"},
			"domicConductor":    {required:"Debe ingresar el Domicilio del Conductor"},
			"telefonoConductor": {required:"Debe ingresar el Telefono del Conductor"},
			"dniConductor":      {required:"Debe ingresar el DNI del Conductor", valDNI:"El DNI del Conductor ingresado es incorrecto"}
        },
        highlight: function(element){
			
        	if($(element).hasClass('validarSin')){
				$('.tabs').tabs('select','test1');
    		}else if($(element).hasClass('validarDen')){
				$('.tabs').tabs('select','test2');
    		}else if($(element).hasClass('validarAcc')){
				$('.tabs').tabs('select','test3');
    		}else if($(element).hasClass('validarRec')){
				$('.tabs').tabs('select','test4');
    		}else if($(element).hasClass('validarCond')){
				$('.tabs').tabs('select','test5');    			
			}

        	if($(element).hasClass('select2')){
        		$(element).siblings('span').find('.selection').find('.select2-selection').addClass('claseError');
        	}else{
        		$(element).addClass('claseError');
        	}
	    },
	    unhighlight: function(element) {
	    	if($(element).hasClass('select2')){
        		$(element).siblings('span').find('.selection').find('.select2-selection').removeClass('claseError'); 
        	}else{
        		$(element).removeClass('claseError'); 
        	}
	    },
	    
	    errorElement : 'li',
        errorLabelContainer: '#mensajeModSin',
 
        submitHandler: function(form){
			modificarDatosSin(idSiniestro);
        }
	});
}

function traerDatosDenunciante(id)
{
	var idPol = id;
	$.ajax({
		type:"post",
		data:{param:30000, data:idPol},
		url: "scripts/aseguradora.php",
		dataType:'json',
		success: function(r){
			if(r.data['success']==true)
			{
				$('#nombreDenunciante').val(r.data['nombre']);
				$("#telefonoDenunciante").val(r.data['telefono']);
				$("#dniDenunciante").val(r.data['documento']);
				$("#domicDenunciante").val(r.data['domicilio']);
				$("#emailDenunciante").val(r.data['correo']);
			}
		}
	});
}

function grabarSiniestro()
{
	if($("#selectDenunAseg").val()==1){
		var tomadDenunciante = 'SI';
	}else if($("#selectDenunAseg").val()==2){
		var tomadDenunciante = 'NO';
	}

	if($("#selectCondRec").val()==1){
		var condReclamante = 'SI';
	}else if($("#selectCondRec").val()==2){
		var condReclamante = 'NO';
	}

	if($('#TipoSin').val() != 5){
		var tomadReclamante = 'SI';
	}else{
		var tomadReclamante = 'NO';
	}

	var datosSiniestro = 
	[
		$('#nroSin').html(),
		$('#fechaOcurrencia').val(),
		$('#horaOcurrencia').val(),
		$('#selectPolizas').val(),
		$('#LesMuer').val(),
		$('#TipoSin').val(),

		$('#nombreDenunciante').val(),
		$('#telefonoDenunciante').val(),
		$('#dniDenunciante').val(),
		$('#emailDenunciante').val(),
		$('#domicDenunciante').val(),

		$('#decripAcc').val(),
		$('#calleAcc').val(),
		$('#alturaAcc').val(),
		$('#localidadAcc').val(),
		$('#provinciaAcc').val(),

		$('#nombreReclamante').val(),
		$('#domicReclamante').val(),
		$('#telefonoReclamante').val(),
		$('#dniReclamante').val(),

		$('#nombreConductor').val(),
		$('#domicConductor').val(),
		$('#telefonoConductor').val(),
		$('#dniConductor').val(),

		tomadDenunciante,
		condReclamante,
		tomadReclamante
	];


	$.ajax({
		url: "scripts/aseguradora.php",
		type: 'post',
		dataType: 'json',
		data: {param: 30001, data: datosSiniestro},
		success: function(r)
		{
			if(r.success)
			{
				alertify.success('Siniestro cargado con exito');
				$('#contenidoC').load('vista/siniestro/cargarSiniestro.php');
				
			}else
			{
				alertify.error('Error al cargar el siniestro');
				$('#contenidoC').load('vista/siniestro/cargarSiniestro.php');
			}
		}
	});
}

function cancelarProceso(){
	alertify.confirm('Emision de siniestro', '¿Cancelar proceso de emision de siniestro?',
	function(){ $('#contenidoC').load('vista/siniestro/cargarSiniestro.php');}, 
	function(){}).set(configuracionAlert('okCancel','Si'));
}

function cambiarEstadoSin(idSiniestro, idEstadoActual)
{
	$('#modalCambiarEstado').modal('open');

	$("#selectEstadoSiniestro").val(idEstadoActual);
	var idSiniestro = idSiniestro;
	var idEstadoActual = idEstadoActual;

	$("#aceptarCambiarEstadoSin").attr('disabled', true);
	$("#selectEstadoSiniestro").on('change', function(){
		$("#aceptarCambiarEstadoSin").attr('disabled', false);
	});

	$("#aceptarCambiarEstadoSin").off('click');
	$("#aceptarCambiarEstadoSin").on('click', function(){

    	var idNuevoEstSin = $("#selectEstadoSiniestro").val();

    	if(idNuevoEstSin != null && idNuevoEstSin != idEstadoActual){
    		var datos = [idSiniestro, idNuevoEstSin];    	
    		$.ajax({
    			url: 'scripts/aseguradora.php',
    			type: 'post',
    			dataType: 'json',
    			data: {param: 30002, data: datos},
    			success: function(r){
    				if(r.success){
    					
    					$('#modalCambiarEstado').modal('close');
						tablaAdministrarSiniestro.ajax.reload();
    					alertify.success('Estado modificado correctamente');
    				}else{
    					$('#modalCambiarEstado').modal('close');
    					alertify.error('Ocurrio un error al modificar el estado');
    				}
    			}
    		});
    		
    	}else{
    		$('#modalCambiarEstado').modal('close');
    		alertify.notify('No se realizaron cambios');
    		tablaAdministrarSiniestro.ajax.reload();
    	}
    });
}

function traerDatosSin(idSiniestro)
{		
	$('#contenidoC').load('vista/siniestro/elementosSiniestro/modificarSiniestro.php', function(){
		funcionalidadesSiniestro();
		$(".textoCabeceraNuevoSin").html('');
		//Quitamos del DOM elementos que no usamos
		elementos = `#siguienteSiniestro, #atrasDenunciante, #atrasAccidente, #atrasReclamante, #atrasConductor,
				   #mensajeSiniestro, #mensajeDenunciante, #mensajeAccidente, #mensajeReclamante, #mensajeConductor`;
		$(elementos).remove();

		$.ajax({
			url: 'scripts/aseguradora.php',
			type: 'post',
			dataType: 'json',
			data: {param: 30003, data: idSiniestro},
			success: function(r){
				if(r.success){

					texto = `Poliza <span class="textoCabNuevoSinAzul">`+r.nroPol+`</span>.
							 Siniestro <span class="textoCabNuevoSinAzul">`+r.nroSiniestro+`</span>
							 denunciado el <span class="textoCabNuevoSinAzul">`+r.fechaDen+`</span>`;
					$(".textoCabeceraNuevoSin").html(texto);

					//Convierto la fecha a formato valido y se lo asigno a datepicker para asignar un limite maximos
					fechaMax = moment(r.fechaDen, 'DD/MM/YYYY HH:mm').format('DD/MM/YYYY');
					$("#fechaOcurrencia" ).datepicker( "option", "maxDate", fechaMax);

					$("#nroSin").val(r.nroSiniestro);
					$("#fechaDenuncia").val(r.fechaDen);
					$("#fechaOcurrencia").val(r.fechaOc);
					$("#horaOcurrencia").val(r.horaOc);
					$("#LesMuer").val(r.lesionMuerte);
					listarPolizas(r.idPoliza, 1);
					$("#TipoSin").val(r.tipoSiniestro).trigger('change', function(){ funcionalidadesSiniestro() });

					if(r.tomadDenunciante=='SI'){
						$("#selectDenunAseg").val(1).trigger('change');
					}else if(r.tomadDenunciante=='NO'){
						$("#selectDenunAseg").val(2).trigger('change');
						$("#nombreDenunciante").val(r.nomDen);
						$("#telefonoDenunciante").val(r.telDen);
						$("#dniDenunciante").val(r.dniDen);
						$("#domicDenunciante").val(r.domDen);
						$("#emailDenunciante").val(r.emailDen);
					}

					$("#decripAcc").val(r.descripcionAcc);

					$('#provinciaAcc').val(r.provinciaAcc).trigger('change');
					mostrarLocalidadesSin(r.provinciaAcc, r.localidadAcc);
					$("#provinciaAcc").on('change', function(){
						mostrarLocalidadesSin($("#provinciaAcc").val(), 0);
					});
					$("#calleAcc").val(r.calleAcc);
					$("#alturaAcc").val(r.alturaAcc);

					$("#nombreReclamante").val(r.nombreTercero);
					$("#domicReclamante").val(r.domTercero);
					$("#telefonoReclamante").val(r.telTercero);
					$("#dniReclamante").val(r.docTercero);

					if(r.condReclamante=='SI'){
						$("#selectCondRec").val(1).trigger('change');
					}else if(r.condReclamante=='NO'){
						$("#selectCondRec").val(2).trigger('change');
						$("#nombreConductor").val(r.nombreConductor);
						$("#telefonoConductor").val(r.telConductor);
						$("#domicConductor").val(r.domConductor);
						$("#dniConductor").val( r.dniConductor);
					}else if(r.condReclamante==''){
						$("#selectCondRec").val(0);
					}
				
					//Al modificar la opcion, limpiamos o volvemos a mostrar los datos
					camposDenunciante = "#nombreDenunciante, #telefonoDenunciante, #dniDenunciante, #domicDenunciante, #emailDenunciante";
					$("#selectDenunAseg").on('change', function(){
						if($(this).val()==1){
							$(camposDenunciante).val('').prop('disabled', true);
						}else if($(this).val()==2){
							$('#nombreDenunciante').val(r.nomDen);
							$('#telefonoDenunciante').val(r.telDen);
							$('#dniDenunciante').val(r.dniDen);
							$('#domicDenunciante').val(r.domDen);
							$('#emailDenunciante').val(r.emailDen);
						}
					})
					camposConductor = "#nombreConductor, #telefonoConductor, #domicConductor, #dniConductor";
					$("#selectCondRec").on('change', function(){
						if($(this).val()==1){
							$(camposConductor).val('').prop('disabled', true);
						}else if($(this).val()==2){
							$('#nombreConductor').val(r.nombreConductor);
							$('#telefonoConductor').val(r.telConductor);
							$('#domicConductor').val(r.domConductor);
							$('#dniConductor').val( r.dniConductor);
						}
					})

					//Pasamos el horario maximo y la fecha maxima permitida
					fechaHoraMaxima = r.fechaDen //Formato fecha y hora juntos;
					validarModSiniestro(idSiniestro, fechaHoraMaxima);
	
				}else{
					alertify.error('Ha ocurrido un problema al mostrar la informacion del siniestro solicitado');
				}
			}
		});
	});
}

function modificarDatosSin(idSiniestro)
{
   	if($("#TipoSin").val()==5){
		tomadorReclama = 'NO';
	}else if($("#TipoSin").val()!=5){
		tomadorReclama = 'SI';
	}

	if($("#selectDenunAseg").val()==1){
		tomadorDenuncia = 'SI';
	}else if($("#selectDenunAseg").val()==2){
		tomadorDenuncia = 'NO';
	}

	conductorReclamante = '';
	if($("#selectCondRec").val()==1){
		conductorReclamante = 'SI';
	}else if($("#selectCondRec").val()==2){
		conductorReclamante = 'NO';
	}
	
	datosSin = [$("#fechaOcurrencia").val(), $("#horaOcurrencia").val(), $("#LesMuer").val(), $("#TipoSin").val(),
				$("#nombreDenunciante").val(), $("#dniDenunciante").val(), $("#domicDenunciante").val(), $("#telefonoDenunciante").val(),$("#emailDenunciante").val(),
 				$("#decripAcc").val(), $("#provinciaAcc").val(), $("#localidadAcc").val(), $("#calleAcc").val(), $("#alturaAcc").val(),         
				$("#nombreReclamante").val(), $("#domicReclamante").val(), $("#telefonoReclamante").val(), $("#dniReclamante").val(), 
				$("#nombreConductor").val(), $("#domicConductor").val(), $("#telefonoConductor").val(), $("#dniConductor").val(),
				idSiniestro, tomadorReclama, tomadorDenuncia, conductorReclamante];

	$.ajax({
		url: 'scripts/aseguradora.php',
		type: 'post',
		dataType: 'json',
		data: {param: 30004, datosSin:datosSin},
		success: function(r){
			if(r.success){

				if(r.accion == 1){
					alertify.success('Siniestro modificado correctamente');
					$("#modalModRestoDatos").modal('close');

				}else if(r.accion == 2){
					alertify.notify('No se realizaron modificaciones');
					$("#modalModRestoDatos").modal('close');

				}else{
					alertify.error('Ocurrio un problema al modificar los datos del siniestro');
					$("#modalModRestoDatos").modal('close');
				}

			}else{
				alertify.error('Ocurrio un problema al realizar la peticion de modificacion de datos del siniestro');
				$("#modalModRestoDatos").modal('close');
			}

			$('#contenidoC').load('vista/siniestro/elementosSiniestro/administrarSiniestro.php', function(){
				tablaAdministrarSiniestro();
				tablaAdministrarSiniestro.ajax.reload();
			});
		}
	});
}

function mostrarLocalidadesSin(id, locSelect){
	jQuery('#localidadAcc').empty();
	jQuery("#localidadAcc").append('<option value="" disabled selected>Seleccione</option>');
	$.ajax({
		type:"post",
		data:{param:80001, id:id},
		url: "scripts/aseguradora.php",
		dataType:'json',
		success: function(r){
			if(r!=null){
				$.each(r.data, function(i,val){
					if(locSelect==val.localidadId){
						$("#localidadAcc").append('<option value="' + val.localidadId + '" selected>' + val.localidadNombre + '</option>');
					}else{
						$("#localidadAcc").append('<option value="' + val.localidadId + '">' + val.localidadNombre + '</option>');
					}
				});
			}
		}
	});
}

function mostrarDatosSiniestro(idSiniestro)
{
	var idSiniestro = idSiniestro;
	$.ajax({
		type:"post",
		data:{param:30005, id:idSiniestro},
		url: "scripts/aseguradora.php",
		dataType:'json',
		success: function(r){
			if(r.success){
				$('#contenidoC').load('vista/siniestro/elementosSiniestro/verDatosSiniestro.php', function(){

					$(".nroSin").html('#'+r.nroSiniestro);
					$(".fechaDen").html(r.fechaOc+' - '+r.horaOc);
					$(".fechaOcu").html(r.fechaDen);
					$(".tipoSin").html(r.tipoSiniestro);
					$(".lesionMuerte").html(r.lesionMuerte);
					$(".estadoSin").html(r.estadoSiniestro);

					$(".numPol").html('#'+r.nroPol);
					$(".tomador").html(r.tomador);
					$(".vigencia").html(r.vigencia_inicio+' al '+r.vigencia_fin+' a las 11:59');
					$(".estadoPol").html(r.estadoPoliza);

					$(".denunTom").html(r.tomadDenunciante);
					$(".nombreDen").html(r.nomDen);
					$(".dniDen").html(r.dniDen);
					$(".domiDen").html(r.domDen);
					$(".telDen").html(r.telDen);
					$(".emailDen").html(r.emailDen);

					$(".ubiAcc").html(r.localidadAcc+', '+r.provinciaAcc+', '+r.calleAcc+' '+r.alturaAcc);
					$(".descAcc").html(r.descripcionAcc);

					$(".tomRec").html(r.tomadReclamante);
					$(".nombreRec").html(r.nombreTercero);
					$(".domRec").html(r.domTercero);
					$(".telRec").html(r.telTercero);
					$(".docRec").html(r.docTercero);

					$(".condRec").html(r.condReclamante);
					$(".nombreCond").html(r.nombreConductor);
					$(".domCond").html(r.domConductor);
					$(".telCond").html(r.telConductor);
					$(".docCond").html(r.dniConductor);
					
					/*En caso de que sea la misma persona, no mostramos los campos innecesarios (Vacios)*/
					if(r.tomadDenunciante=='SI'){	
						$(".nombreDen, .dniDen, .domiDen, .telDen, .emailDen").closest('.infoSin-3').css('display', 'none');
					}					
					if(r.tomadReclamante=='SI'){	
						$(".nombreRec, .domRec, .telRec, .docRec").closest('.infoSin-3').css('display', 'none');
					}					
					if(r.condReclamante=='SI'){	
						$(".nombreCond, .domCond, .telCond, .docCond").closest('.infoSin-3').css('display', 'none');
					}

				});
			}else{
				alertify.error('Ocurrio un problema al mostrar la informacion.');
			}
		}
	});
}

function mostrarImgSiniestro(idSin)
{
	$("#nroSiniestroPie").html('sin imagenes registradas');
	$("#marcaModeloPie").html('');

	$("#nuevaImagenBtn").off('click');
	$("#nuevaImagenBtn").on('click', function(){
		$("#imagenSin").trigger('click');
	});

	$.ajax({
		type:"post",
		data:{param:30006, idSin:idSin},
		url: "scripts/aseguradora.php",
		dataType:'json',
		success: function(r){
			$(".gallery-container").html(r.data);
			$("#nroSiniestroPie").html(r.info['nroSin']);
			$("#marcaModeloPie").html(r.info['marcaModVe']);
		}
	});

	grabarImgSiniestro(idSin);
}

function grabarImgSiniestro(idSin)
{
	var idSin = idSin;

	$("#cargarImagen").off('click');
	$("#cargarImagen").on('click', function(){

		var estadoOut = setTimeout(function(){
			$("#estadoSubida").fadeOut(1000);
		},2000);

		var imagenes = document.getElementsByClassName("gallery-card").length;

		if(imagenes<12){

			var imagen = $('#imagenSin');

			var image_data = imagen.prop('files')[0];

			var formData = new FormData();
			formData.append('imagen', image_data);
			formData.append('idSin', idSin);

			$.ajax({
				url: "scripts/imagenes/procesarImgSin.php",
				type: "POST",
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				beforeSend: function(d){
					$("#cargarImagen").prop('disabled', true);
					$("#estadoSubida").html('<img src="img/ImgAlt/preload.gif">').show();
				},
				success: function(r){
					$("#cargarImagen").prop('disabled', false);
					if(r == 1){
						//alertify.success('Imagen cargada');
						$("#imagenSin").val('');
						$("#estadoSubida").html('<img src="img/ImgAlt/success.png">').show();
						estadoOut;
						mostrarImgSiniestro(idSin);

					}else if(r == 10){
						$("#estadoSubida").html('');
						alertify.alert('Cargar imagen', 'Debe seleccionar una imagen').set(configuracionAlert('ok'));
					}else if(r == 20){
						$("#estadoSubida").html('<img src="img/ImgAlt/error.png">').show();
						estadoOut;
						alertify.alert('Cargar imagen', 'El formato de la imagen no coincide con los admitidos (.JPG / .PNG / .JPEG)').set(configuracionAlert('ok'));
					}else if(r == 30){
						$("#estadoSubida").html('<img src="img/ImgAlt/error.png">').show();
						alertify.alert('Cargar imagen', 'El tamaño de la imagen es superior al maximo permitido (5 Mb)').set(configuracionAlert('ok'));
						estadoOut;
					}else if(r == 40){
						$("#estadoSubida").html('<img src="img/ImgAlt/error.png">').show();
						alertify.error('No se ha podido grabar esa imagen');
						estadoOut;
					}else if(r == 50){
						$("#estadoSubida").html('<img src="img/ImgAlt/error.png">').show();
						alertify.error('Ha ocurrido un problema al realizar la peticion al servidor');
						estadoOut;
					}
				}
			});
		}else{
			alertify.alert('Cargar imagen', 'Solo se permiten cargar hasta 12 imagenes').set(configuracionAlert('ok'));
		}

	});
}

function eliminarImgSin(idImg)
{
	var idImg = idImg;

	alertify.confirm('Eliminar imagen', '¿Eliminar esta imagen?',
	 function(){
	 	$.ajax({
			url: 'scripts/aseguradora.php',
			type: 'POST',
			dataType: 'json',
			data: {param:30007, idImg:idImg},
			success: function(r){
				if(r.success){
					mostrarImgSiniestro(r.idSin);
				}else{
					alertify.error('Ocurrio un error al eliminar la imagen');
				}
			}
		});
	 },
	 function(){}
	 ).set(configuracionAlert('okCancel','Si'));
}

function listarPolizas(polizaSelect, getUnica)
{
	jQuery('#selectPolizas').empty();
	jQuery('#selectPolizas').append('<option value="0" disabled selected>Seleccione una Poliza</option>');
	jQuery.ajax({
		type:"post",
		data:{param:10004},
		url: "scripts/aseguradora.php",
		dataType:'json',
		success: function(r){
			if(r!=null){
				if(getUnica==null){
					jQuery.each(r.data, function(k,v){
						if(polizaSelect==v.id){
							jQuery('#selectPolizas').append('<option value="'+v.id+'" selected>'+v.nro+' - '+v.tomador+' - '+v.doc+'</option>');
						}else{
							if(v.estado==0){ //Deshabilitamos las polizas que estan vencidas o con deuda
								jQuery('#selectPolizas').append('<option value="'+v.id+'">'+v.nro+' - '+v.tomador+' - '+v.doc+'</option>');
							}else if(v.estado==1){
								jQuery('#selectPolizas').append('<option value="'+v.id+'" disabled>'+v.nro+' - '+v.tomador+' - '+v.doc+' - '+' (Poliza Vencida)' + '</option>');
							}else if(v.estado==3){
								jQuery('#selectPolizas').append('<option value="'+v.id+'" disabled>'+v.nro+' - '+v.tomador+' - '+ v.doc +' - '+' (Poliza con deudas)' + '</option>');
							}
						}
					});
				}else if(getUnica==1){
					jQuery.each(r.data, function(k,v){
						if(polizaSelect==v.id){
							jQuery('#selectPolizas').append('<option value="'+v.id+'" selected>'+v.nro+' - '+v.tomador+' - '+v.doc+'</option>');
						}
					});
				}
			}
		}
	});
}

function tablaAdministrarSiniestro()
{
	tablaAdministrarSiniestro = $('#administrarSiniestroTabla').DataTable({
		"ajax": "scripts/tablas/tablaAdministrarSiniestro.php",
		"columns": [                    
          {data:"nroSin",  title: "Nro Siniestro", width:""},
          {data:"nroPol",  title: "Nro Poliza",    width:""},
          {data:"tomador", title: "Tomador",       width:""},
		  {data:"tipo",    title: "Tipo",          width:""},
		  {data:"fechDen", title: "Denuncia",      width:""},
          {data:"estado",  title: "Estado",        width:""},
          {data:"botones", title: "Administrar",   width:""}
        ],
		"language": {
			"zeroRecords": "No se encontraron siniestros registrados en la base de datos.",
        	"sSearch": "Buscar",
        },      
        "scrollY": 370,
        "scrollCollapse": true,
        "dom": 'ft',
		"order": [[4, "desc"]],
		"lengthMenu": [[5, 10], [5, 10]],
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [5] }
        ]
     });

	return tablaAdministrarSiniestro;
}

function tablaConsultarSiniestro()
{
	$('#consultarSiniestroTabla').DataTable({
		"ajax": "scripts/tablas/tablaConsultarSiniestro.php",
		"columns": [
			{data:"nroSin", title:"Nro Siniestro"},
			{data:"nroPol", title:"Nro Poliza"},
			{data:"tomador", title:"Tomador"},
			{data:"fechDen", title: "Fecha Denuncia"},
			{data:"estado", title: "Estado"},
			{data:"boton", title: ""}
		],

		"language": {
			"zeroRecords": "No se encontraron siniestros registrados en la base de datos.",
        	"sSearch": "Buscar",
		},
		"dom": 'ft',
        "scrollY": 370,
        "scrollCollapse": true,
		"order": [[4, "desc"]],
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [5] }
        ]

     });
}

function funcionalidadesSiniestro()
{
	$('.tabs').tabs();

	$('.collapsible').collapsible();

    $('.modal').modal({
		dismissible: false
	});

	$('input, select').addClass('browser-default');
   
	//Se aplica la libreria select2 a los inputs requeridos en emision de siniestro. 
	$('#selectPolizas, #provinciaAcc, #localidadAcc, #provinciaAccMod, #localidadAccMod').select2({width: "100%"});

	//Generamos el numero de siniestro y lo
	var letrasSin = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	var lSin = (letrasSin[Math.round(Math.random()*26)]+letrasSin[Math.round(Math.random()*26)]);
	var numSin = Math.floor(1e9 + (Math.random() * 9e14));
	var numSiniestro = numSin+lSin;
	$('#nroSin, #fechaDenuncia, #fechaOcurrencia, #fechaOcurrenciaMod').prop('readonly', true);
	$('#nroSin').html(numSiniestro);

	/*Se aplica el complemento Datepicker al input llamado "fechaOcurrencia" en emision de siniestro, pero se aplica una opcion para 
	que solo se pueda seleccionar una fecha anterior a la actual.*/
  	$(function(){
	    $("#fechaOcurrencia").datepicker({
	    	dayNames: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
	        dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
	        monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
	        monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
	      	dateFormat: "dd/mm/yy",
			maxDate: 0
		});
	  });
	

	/*Mantenemos la integridad de los inputs*/
	$('#fechaOcurrencia').on('mouseover', function(){
		$(this).prop('readonly', true);
	});
	$('#horaOcurrencia').on('mouseover', function(){
		$(this).prop('type', 'time');
	});

	//Segun la opcion del select denunciante-asegurado que elijamos se habilitaran los campos o no
	inputsDenunciante = '#nombreDenunciante, #telefonoDenunciante, #dniDenunciante, #domicDenunciante, #emailDenunciante';
	$(inputsDenunciante).val('').prop('disabled', true);

	$("#selectDenunAseg").on('change', function(){
		if($(this).val()==1){
			$(inputsDenunciante).val('').prop('disabled', true);
			$(inputsDenunciante).on('mouseover', function(){
				$(inputsDenunciante).prop('disabled', true)
			})
		}else if($(this).val()==2){
			$(inputsDenunciante).val('').prop('disabled', false);
			$(inputsDenunciante).on('mouseover', function(){
				$(inputsDenunciante).prop('disabled', false)
			})
		}else{
			$(inputsDenunciante).val('').prop('disabled', true);
			$(inputsDenunciante).on('mouseover', function(){
				$(inputsDenunciante).prop('disabled', true)
			})
		}
	})


	//Si requerimos de agregar la informacion de terceras personas, segun el tipo de siniestro elegido estaran disponible los campos o no
	inputsReclamante = '#nombreReclamante, #domicReclamante, #telefonoReclamante, #dniReclamante';
	inputsConductor = '#selectCondRec, #nombreConductor, #domicConductor, #telefonoConductor, #dniConductor';

	$("#TipoSin").on('change', function(){
		if($("#TipoSin").val()==5){

			$("#tab4, #tab5").parent('.tab').removeClass('disabled');
			$("#tab3").children('i').css('display','inline-block');
			$("#test4").css('display', 'none').mouseover(()=> $("#test4").css('display','block'));
			$("#test5").css('display', 'none').mouseover(()=> $("#test5").css('display','block'));
			$("#tab4, #tab5").mouseover(()=> $("#tab4, #tab5").parent('.tab').removeClass('disabled'));

			$("#selectCondRec").val(0);
			$(inputsReclamante).prop('disabled', false);
			$(inputsConductor).prop('disabled', false);

		}else{

			$("#tab4, #tab5").parent('.tab').addClass('disabled');
			$("#tab3").children('i').css('display','none');
			$("#test4, #test5").css('display', 'none').mouseover(()=> $("#test4, #test5").css('display','none'));
			$("#tab4, #tab5").mouseover(()=> $("#tab4, #tab5").parent('.tab').addClass('disabled'));

			$("#selectCondRec").val(0);
			$(inputsReclamante).prop('disabled', true);
			$(inputsConductor).prop('disabled', true);
		}

		//Quitamos el boton para que no quede el actual en caso de modificar el tipo de siniestro.
		$(".btnAcc").html('');
	});

	camposConductor = '#nombreConductor, #domicConductor, #telefonoConductor, #dniConductor';

	$("#selectCondRec").on('change', function(){
		if($(this).val()==1){
			$(camposConductor).val('').prop('disabled', true);
			$(camposConductor).on('mouseover', function(){
				$(camposConductor).prop('disabled', true)
			})
		}else if($(this).val()==2){
			$(camposConductor).val('').prop('disabled', false);
			$(camposConductor).off('mouseover')
		}else{
			$(camposConductor).val('').prop('disabled', true);
			$(camposConductor).on('mouseover', function(){
				$(camposConductor).prop('disabled', true)
			})
		}
	})

	//Establecemos limites maximos y minimos en los campos de anio
	$("#anio").prop('min', 1950);
	$("#anio").prop('max', 2021);

	//Al presionar el boton atras en cada seccion, se muestra la pantalla anterior.
	$("#atrasDenunciante").off('click');
	$("#atrasDenunciante").on('click', function(e){
		$('.tabs').tabs('select','test1');
		$('.btnSigDen').html('');
	});
	$("#atrasAccidente").off('click');
	$("#atrasAccidente").on('click', function(e){
		$('.tabs').tabs('select','test2');
		$('.btnAcc').html('');
	});
	$("#atrasReclamante").off('click');
	$("#atrasReclamante").on('click', function(e){
		$('.tabs').tabs('select','test3');
		$('.btnSigRec').html('');
	});
	$("#atrasConductor").off('click');
	$("#atrasConductor").on('click', function(e){
		$('.tabs').tabs('select','test4');
		$('.btnSinConductor').html('');
	});

	$("#volverConsultarSin").on('click', function(){
		$('#contenidoC').load('vista/siniestro/elementosSiniestro/consultarSiniestro.php', function(){
			tablaConsultarSiniestro();
		  });
	});

	/*Parametros para lightbox js*/
	lightbox.option({
		'resizeDuration': 0,
		'fadeDuration': 0,
		'wrapAround': true,
		'albumLabel': "%1/%2",
		'imageFadeDuration': 0,
		'wrapAround': true
	});
}
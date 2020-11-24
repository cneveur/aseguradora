$(document).ready(function() {

	//					-------------------FORMULARIO EJEMPLO------------------- 

	/*<form name="form1" id="form1" class="formulario">
        <div class="row">
          <div class="col s4">
            <label for="txtNombre">Nombre</label>
            <input class="browser-default" type="text" name="txtNombre" />

            <label for="txtApellidos">Apellidos</label>
            <input class="browser-default" type="text" name="txtApellidos" />

            <label for="txtFNac">F/nacimiento</label>
            <input class="browser-default" type="text" name="txtFNac" />

            <label for="txtNIF">N.I.F.</label>
            <input class="browser-default" type="text" name="txtNIF" />

            <label for="txtCPostal">Código postal</label>
            <input class="browser-default" type="text" name="txtCPostal" />

            <label for="txtMail">E-Mail</label>
            <input class="browser-default" type="text" name="txtMail" />

            <label for="txtBlog">Blog personal</label>
            <input class="browser-default" type="text" name="txtBlog" />

            <input class="browser-default" type="submit" name="btnEnviar"  value="Enviar"/>
            
          </div>
          
        </div>
    </form>*/

     //					-------------------UTILIZACION PARA EL FORMULARIO ANTERIOR------------------- 

     $("#form1").validate({

       rules: {
           "txtNombre": { required:true, lettersonly:true },
           "txtApellidos": { required:true, lettersonly:true },
           "txtFNac": { dateITA:true },
           "txtNIF": { rangelength:[9,9] },   // Formato
           "txtCPostal": { required:true, digits:true, rangelength:[5,5] },
           "txtMail": { required:true, email:true },
           "txtBlog": { url:true }
       },
       messages: {
           "txtNombre": { required:"Introduce el nombre", lettersonly:"Sólo se admiten letras en el nombre"},
           "txtApellidos": { required:"Introduce los apellidos", lettersonly:"Sólo se admiten letras en los apellidos"},
           "txtFNac": { dateITA:"<img src='error.gif' alt='' /> Formato de fecha no válido" },
           "txtNIF": "Formato de NIF incorrecto",
           "txtCPostal": { required:"Escribe un código postal válido", digits:"Sólo pueden haber números en el código postal", rangelength:"El código postal debe contener cinco dígitos" },
           "txtMail": { required:"Introduce tu E-Mail", email:"La dirección E-Mail no tiene formato correcto" },
           "txtBlog": "URL no válida"
       },
       submitHandler: function(form){
           alertify.success("Los datos son correctos");
       }
    });

//  -------------------METODOS------------------- 


required 	  - Hace el elemento requerido.
remote		  - Solicita un recurso para verificar la validez del elemento.
minlength 	- Hace que el elemento requiera una longitud mínima dada.
maxlength 	- Hace que el elemento requiera una longitud máxima dada.
rangelength - Hace que el elemento requiera un rango de valores dado.
min 		    - Hace que el elemento requiera un mínimo dado.
max 		    - Hace que el elemento requiera un máximo dado.
range 		  - Hace que el elemento requiera un rango de valores dado.
step 		    - Hace que el elemento requiera un paso dado.
email 		  - Hace que el elemento requiera un correo electrónico válido
url 		    - Hace que el elemento requiera una URL válida
date 		    - Hace que el elemento requiera una fecha.
dateISO 	  - Hace que el elemento requiera una fecha ISO.
number 		  - Hace que el elemento requiera un número decimal.
digits 		  - Hace que el elemento requiera solo dígitos.
equalTo 	  - Requiere que el elemento sea el mismo que otro
lettersonly - Solo letras


// -------------------DEFINIR MI METODO PERSONALIZADO------------------- 

$.validator.addMethod('nombreMetodo', function(value, element){
	return this.optional(element) ||  

	/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i.test(value);
},
'Debe ingresar valor x')

// -------------------AGREGAR CLASE ERROR------------------- 

$.validator.setDefault({
	highlight: function(element) {
        $(element).parents('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).parents('.form-group').removeClass('has-error');
    },
})






});

// ALGUNAS EXPRESIONES REGULARES

^     -  Inicio de la cadena
$     -  Fin de la cadena
[]    -  Cualquier caracter del conjunto, por ejemplo [xyz] representa el conjunto formado por las letras x,y,z y encontrará cualquiera de esos caracteres.
[^]   -  Cualquier caracter no incluido en el conjunto, por ejemplo[^xyz] representa cualquier caracter no incluido en el conjunto formado por las letras x,y,z
?     -  Cero o una ocurrencia de lo que precede al símbolo, por ejemplo para encontrar cero o una ocurrencia de www. utilizaremos el patrón (www\.)?
+     -  El caracter que le precede debe aparecer al menos una vez, por ejemplo Google, Gooogle, Gooooooogle se representa con la siguiente expresión regular: Goo+gle
*     -  El caracter que le precede debe aparecer cero, una o más veces, utilizando el ejemplo anterior, Gooo*gle representa Google, Goooogle, Goooooogle.
{x}   -  x ocurrencias del caracter que lo precede, por ejemplo www. podría ser representado con el patrón w{3}\.
{x,z} -  Entre x y z ocurrencias del caracter que lo precede, con el ejemplo de Google, si quisiéramos que hubieran mínimo 2 letras o y máximo 5, utilizaríamos el patrón Go{2,5}gle
{x,}  -  x o más ocurrencias de lo que lo precede, con el ejemplo de Google, para tener 2 o más letras o usaríamos la expresión regular Go{2,}gle
\.    -  Un punto dentro del patrón, como definimos en uno de los ejemplos anteriores, la expresión w{3}\. define la cadena www.
\s    -  Representa un espacio en blanco
\d    -  Un dígito numérico
\w    -  Un caracter alfanumérico
\n    -  Un salto de línea
\r    -  Representa el caracter de retorno de carro
\t    -  Tabulador
\S    -  Cualquier caracter excepto un espacio en blanco
\D    -  Cualquier caracter excepto un dígito numérico
\W    -  Representa cualquier caracter no alfanumérico
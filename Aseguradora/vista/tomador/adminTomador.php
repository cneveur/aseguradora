<!-- Tabla donde muestro la lista de tomadores registrados -->
<div class="row" id="consultarPolRow">
  <div class="col s12">
    <table id="modificarDatosTomadorTabla" class="centered striped">
      <thead>
        <tr>
          <th scope="col">Nombre</th>
          <th scope="col">Nro Tom</th>
          <th scope="col">Provincia</th>
          <th scope="col">Localidad</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- Modal Modificar Datos del tomador -->
<div id="modificarDatosTomadorModal" class="modal modal-fixed-footer">
   <form id="nuevoCliente" class="formulario">
      <div class="modal-content">

          <div id="cuerpoModal">
            <?php include_once 'nuevoTomador.php'; ?>
          </div>

          <ul class="mensaje mensajeModal"></ul>

      </div>

      <div class="modal-footer">
          <button type="button" class="btn btn-flat blue modal-close" id="cancelarClienteAdmin"> <i class="fas fa-times"></i> cerrar </button>
          <button type="submit" class="btn btn-flat green" id="grabarClienteAdmin"> <i class="fas fa-save"></i> grabar </button>
      </div>

    </form>
</div>


<!-- Modal Consultar Datos del Tomador -->
<div id="consultarTomModal" class="modal modal-fixed-footer">
  <div class="modal-content">
    <div class="tituloFormulario">Consultar datos tomador <span id="nroTom"></span></div>

    <div class="infoTom-1">
      <div class="infoTom-2">
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Nombre Completo</div> 
            <div class="infoTom-4-conteniido nomT"></div>
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Documento</div> 
            <div class="infoTom-4-conteniido docT"></div>
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Tipo de Persona</div> 
            <div class="infoTom-4-conteniido perT"></div>    
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Nacionalidad</div> 
            <div class="infoTom-4-conteniido nacioT"></div>
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Provincia</div> 
            <div class="infoTom-4-conteniido proT"></div>
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Localidad</div> 
            <div class="infoTom-4-conteniido locT"></div>
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Codigo Postal</div> 
            <div class="infoTom-4-conteniido cpT"></div>
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Calle</div> 
            <div class="infoTom-4-conteniido callT"></div>
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Genero</div> 
            <div class="infoTom-4-conteniido genT"></div>    
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Telefono</div> 
            <div class="infoTom-4-conteniido telT"></div>
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Correo</div> 
            <div class="infoTom-4-conteniido corrT"></div>
        </div>
        <div class="infoTom-3">
            <div class="infoTom-4-titulo">Fecha de Nacimiento</div> 
            <div class="infoTom-4-conteniido nacimT"></div>
        </div>

      </div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-flat blue modal-close" id="grabarClienteAdmin"> <i class="fas fa-times"></i> cerrar </button>
  </div>
</div>

<script type="text/javascript" src="js/cliente.js?v=3"></script>

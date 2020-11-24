<form id="cuotasForm" class="formulario">
  <fieldset id="fieldsetPago">

    <h2 class="tituloFormulario">Datos de pago</h2>

    <?php require_once '../../pagos/nuevaCuota.php' ?>

    <div id="botonesPago">
      <button type="button" class="btn btn-flat blue" id="atrasPago"> <i class="fas fa-caret-left"></i> atras </button>
      <span class="btnDivPago"></span>
      <button type="button" class="btn btn-flat red" id="cancelar_datos" value="Enviar"> cancelar <i class="fas fa-times"></i> </button>
      
    </div>

  </fieldset>
</form>

<ul id="mensajePago" class="mensaje"></ul>
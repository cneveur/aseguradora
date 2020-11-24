<!--Div para renovar poliza-->
<div id="formRenPol">
	<!--Formulario donde se muestra datos de la poliza-->
    <div>
        <form class="formulario formRenovacionPol" id="divPoliza">
            <fieldset>

            <div class="row" id="formPolRen">
                <?php require_once '../formularios/formularioPoliza.php' ?>
            </div>

            <div class="botRenPol">	
                <button type="button" class="btn btn-flat blue atrRenPol"> <i class="fas fa-caret-left"></i> atras </button>
                <button type="submit" class="btn btn-flat green sigRenPol"> siguiente <i class="fas fa-caret-right"></i> </button>
            </div>

            </fieldset>
        </form>

        <ul id="mensajePoliza" class="mensaje"></ul>
    </div>
    
    <!--Formulario donde se muestra datos del pago-->
    <div>
        <form class="formulario cuotasForm" id="cuotasForm">
            <fieldset>
                <div class="tituloFormulario titRenPago">Datos del pago</div>

                <div>
                    <?php require_once '../../pagos/nuevaCuota.php' ?>
                </div>

                <div class="botRenPa">
                    <button type="submit" class="btn btn-flat blue atrRenPa"> <i class="fas fa-caret-left"></i> atras </button>
                    <button type="submit" class="btn btn-flat green sigRenPa"> <i class="fas fa-save"></i> renovar </button>
                </div>
            </fieldset>
        </form>
        <ul id="mensajePago" class="mensaje"></ul>
    </div>
</div>
<form id="modificarSiniestro" class="formulario">

    <ul id="tabFormularioSiniestro" class="tabs tabs-fixed-width tab-demo z-depth-1 ">
        <li class="tab"><a id="tab1" href="#test1" class="active">Informacion General <i class="fas fa-chevron-circle-right"></i> </a> </li>
        <li class="tab"><a id="tab2" href="#test2">Denunciante <i class="fas fa-chevron-circle-right"></i> </a></li>
        <li class="tab"><a id="tab3" href="#test3">Accidente <i class="fas fa-chevron-circle-right"></i> </a></li>
        <li class="tab"><a id="tab4" href="#test4">Reclamante <i class="fas fa-chevron-circle-right"></i> </a></li>
        <li class="tab"><a id="tab5" href="#test5">Conductor</a></li>
    </ul>
    <div id="test1" class="col s12"> <?php include_once "../formulariosSiniestro/formSiniestro.php"; ?>    </div>
    <div id="test2" class="col s12"> <?php include_once "../formulariosSiniestro/formDenunciante.php"; ?>   </div>
    <div id="test3" class="col s12"> <?php include_once "../formulariosSiniestro/formAccidente.php"; ?>  </div>
    <div id="test4" class="col s12"> <?php include_once "../formulariosSiniestro/formReclamante.php"; ?> </div>
    <div id="test5" class="col s12"> <?php include_once "../formulariosSiniestro/formConductor.php"; ?> </div>

    <div class="btnsModificarSiniestro">
        <button type="button" class="btn btn-flat blue" id="btnVolverModificarSin"> <i class="fas fa-caret-left"></i> volver </button>
        <div class="btnsModSinHijo">
            <button type="submit" class="btn btn-flat green"> confirmar <i class="fas fa-save"></i> </button>
        </div>
    </div>

</form> 

<ul id="mensajeModSin" class="mensaje"></ul>    

<script type="text/javascript" src="js/siniestros.js"></script>
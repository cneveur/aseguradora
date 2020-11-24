<ul id="tabFormularioSiniestro" class="tabs tabs-fixed-width tab-demo z-depth-1 ">
  <li class="tab"><a id="tab1" href="#test1" class="active">Informacion General <i class="fas fa-chevron-circle-right"></i> </a> </li>
  <li class="tab"><a id="tab2" href="#test2">Denunciante <i class="fas fa-chevron-circle-right"></i> </a></li>
  <li class="tab"><a id="tab3" href="#test3">Accidente <i class="fas fa-chevron-circle-right"></i> </a></li>
  <li class="tab"><a id="tab4" href="#test4">Reclamante <i class="fas fa-chevron-circle-right"></i> </a></li>
  <li class="tab"><a id="tab5" href="#test5">Conductor</a></li>
</ul>
<div id="test1" class="col s12"> <?php include_once "divSiniestro/divSiniestro.php"; ?>    </div>
<div id="test2" class="col s12"> <?php include_once "divSiniestro/divDenunciante.php"; ?>   </div>
<div id="test3" class="col s12"> <?php include_once "divSiniestro/divAccidente.php"; ?>  </div>
<div id="test4" class="col s12"> <?php include_once "divSiniestro/divReclamante.php"; ?> </div>
<div id="test5" class="col s12"> <?php include_once "divSiniestro/divConductor.php"; ?> </div>

<script type="text/javascript" src="js/siniestros.js"></script>
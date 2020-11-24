<fieldset id="fielsetDenunciante">

  <h2 class="tituloFormulario">denunciante</h2>

  <div class="selectDenunAsegPadre">
    <label for="selectDenunAseg">El Denunciante es la misma persona que el Asegurado</label>
    <select name="selectDenunAseg" id="selectDenunAseg" class="validarDen">
      <option value="0" selected disabled>Seleccione</option>
      <option value="1">Si</option>
      <option value="2">No</option>
    </select>
  </div>
    
  <div class="row">
    <div class="col s4">
      <label for="nombreDenunciante">Nombre Completo</label>
      <input type="text" id="nombreDenunciante" name="nombreDenunciante" class="validarDen">
    </div>

    <div class="col s4">
      <label for="dniDenunciante">DNI</label>
      <input type="text" id="dniDenunciante" name="dniDenunciante" class="validarDen">
    </div>  

    <div class="col s4">
      <label for="domicDenunciante">Domicilio</label>
      <input type="text" id="domicDenunciante" name="domicDenunciante" class="validarDen">
    </div>                 
  </div>  

  <div class="row">
    <div class="col s4">
      <label for="telefonoDenunciante">Telefono</label>
      <input type="text" id="telefonoDenunciante" name="telefonoDenunciante" class="validarDen">
    </div>

    <div class="col s4">
      <label for="emailDenunciante">E-mail</label>
      <input type="text" id="emailDenunciante" name="emailDenunciante" class="validarDen">
    </div>
  </div> 

  <button type="button" class="btn btn-flat blue" id="atrasDenunciante"><i class="fas fa-caret-left"></i> atras</button>

  <span class="btnSigDen"></span>
  
</fieldset>
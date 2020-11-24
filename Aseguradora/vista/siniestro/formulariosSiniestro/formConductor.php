<fieldset id="fieldsetConductor">

  <h2 class="tituloFormulario">conductor (tercero)</h2>

  <div class="selectCondRecPadre">
    <label for="selectCondRec">El Conductor es la misma persona que el Reclamante</label>
    <div>
      <select name="selectCondRec" id="selectCondRec" class="validarCond">
        <option value="0" selected disabled>Seleccione</option>
        <option value="1">Si</option>
        <option value="2">No</option>
      </select>
    </div>
  </div>
    
  <div class="row">
      <div class="col s6">
        <label for="nombreConductor">Nombre Completo</label>
        <input type="text" id="nombreConductor" name="nombreConductor" class="validarCond">
      </div>

      <div class="col s6">
        <label for="domicConductor">Domicilio</label>
        <input type="text" id="domicConductor" name="domicConductor" class="validarCond">
    </div>             
  </div>  

  <div class="row">
    <div class="col s6">
        <label for="telefonoConductor">Telefono</label>
        <input type="text" id="telefonoConductor" name="telefonoConductor" class="validarCond">
      </div>

    <div class="col s6">
        <label for="dniConductor">DNI</label>
        <input type="text" id="dniConductor" name="dniConductor" class="validarCond">
    </div>
  </div> 

  <button type="button" class="btn btn-flat blue" id="atrasConductor"><i class="fas fa-caret-left"></i> atras</button>
  <span class="btnSinConductor"></span>
  
</fieldset>
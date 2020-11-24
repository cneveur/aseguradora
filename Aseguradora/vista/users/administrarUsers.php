<!-- Tabla donde muestro la lista de usuarios del sistema -->

<div class="row rowListadoUsers">
  <table id="tablaListadoUsers" class="centered">
    <thead>
      <tr>
        <th scope="col"><i class="fas fa-check-circle"></i></th>
        <th scope="col">Numero</th>
        <th scope="col">Nombre</th>
        <th scope="col">Rol</th>
        <th scope="col">Estado</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<!--Modal listado de usuarios dados de baja-->
<div id="modalListadoUserBaja" class="modal modal-fixed-footer">
  <div class="modal-content">
    <div class="tituloFormulario">Listado de usuarios dados de baja</div>
    <div class="tablaUsrBaja">
      <table id="listadoBajaUser" class="centered">
        <thead>
          <tr>
            <th scope="col">Numero</th>
            <th scope="col">Nombre</th>
            <th scope="col">Rol</th>
            <th scope="col">Estado</th>
            <th scope="col">Activar</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
  <div class="row modal-footer">
    <button type="button" class="btn btn-flat blue cerrarModalListadoUsBa"> <i class="fas fa-times"></i> cerrar </button>
  </div>
</div>

<!--Modal para modificar el rol de un usuario-->
<div id="modalModificarRolUser" class="modal modal-fixed-footer">
  <div class="modal-content">
    <div class="tituloFormulario">Modificar rol del usuario</div>
    <div class="contentMR">
        <!--Info de usuario-->
        <div class="div1MR">
          <div class="tituloMR">Informacion del usuario</div>
          <div class="div2MR">
            <div class="subtituloMR">Numero</div> 
            <div class="infoMR num"></div>
            <div class="subtituloMR">Nombre</div> 
            <div class="infoMR nombre">Erasmo Jesus Jordan</div>
            <div class="subtituloMR">Usuario</div> 
            <div class="infoMR usuario">Jordanerasmo7</div>
          </div>
        </div>
        <!--Seleccionar rol-->
        <div class="div1MR">
          <div class="tituloMR">Rol</div>
          <div class="div2MR">
            <div class="div3MR">
              <p class="div3MR-p1">
                <label>
                  <input class="with-gap checkUs" name="grupoCheck" type="radio"/>
                  <span>Usuario</span>
                </label>
                <a class="btn-flat"><i class="fas fa-user"></i></a>
              </p>
              <p>
                <label>
                  <input class="with-gap checkAdm" name="grupoCheck" type="radio"/>
                  <span>Administrador</span>
                </label>
                 <a class="btn-flat"><i class="fas fa-user-shield"></i></a>
              </p>
            </div>
          </div>
        </div>
    </div>
  </div>
  <div class="row modal-footer">
    <button type="button" class="btn btn-flat blue modal-close"> <i class="fas fa-times"></i> cerrar </button>
    <button type="button" class="btn btn-flat green enviarModRol"> <i class="fas fa-save"></i> confirmar </button>
  </div>
</div>

<!--Modal para visualizar los datos del usuario-->
<div id="modalVerInfoUser" class="modal modal-fixed-footer">
  <div class="modal-content">
    <div class="tituloFormulario">Informacion del usuario</div>
    <div> <?php require_once 'informacionUser.php' ?> </div>
  </div>
  <div class="row modal-footer">
    <button type="button" class="btn btn-flat blue modal-close"> <i class="fas fa-times"></i> cerrar </button>
  </div>
</div>


<!--Modal para reenviar la informacion de acceso-->
<div class="modalReenviarInfoAcceso modal">
  <form class="formReenviarInfoAcceso formulario" id="formCorreo">
    <div class="modal-content">
      <div class="tituloFormulario">Reenviar informacion de acceso</div>
      <label for="correo" class="correoLab">Correo electronico</label>
      <input type="email" class="correoUs" name="correo">
      <div class="mjeCorr"></div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-flat blue modal-close" id="cerrarModalReenviarInfoAcceso"> <i class="fas fa-times"></i> cerrar </button>
      <button type="submit" class="btn btn-flat green" id="btnEnviarInfoAcceso"> enviar <i class="fas fa-paper-plane"> </i> </button>
    </div>
  </form>  
</div>

<script type="text/javascript" src="js/users.js?v=3"></script>
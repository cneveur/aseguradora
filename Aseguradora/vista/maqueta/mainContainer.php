
<div class="main_container">
    <div class="contenido" id="contenido">
        <div class="menuUser">
            <ul>
                <div class="nombreUser"><?= $_SESSION['user']['nombre'] ?></div>
                <hr>
                <li class="modFotoUser"> Foto de perfil <span class="icoMenUs"> <i class="fas fa-user-circle"></i> </span> </li>
                <li class="misDatos"> Mis datos <span class="icoMenUs"> <i class="fas fa-user-edit"></i> </span> </li>
                <li class="logout"> Salir del sistema <span class="icoMenUs"> <i class="fas fa-sign-out-alt"></i> </span> </li>
            </ul>
        </div>
        <div id="divTit"><h2 class="linea"><span id="tituloCont"></span></h2></div>
        <div id="divSubTit"><span id="subTituloCont"></span></div>
        <div id="contenidoC"></div>
    </div>
</div>
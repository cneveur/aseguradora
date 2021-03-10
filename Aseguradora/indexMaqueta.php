<?php
    session_start();
    if(isset($_SESSION['user'])){

        if($_SESSION['user']['rol']=='Administrador'){
            ob_start();
                require_once 'vista/maqueta/sidebarAdmin.php';
            $sidebar = ob_get_clean();

        }else if($_SESSION['user']['rol']=='Usuario'){

            ob_start();
                require_once 'vista/maqueta/sidebarUser.php';
            $sidebar = ob_get_clean();
        }
    }else{
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Aseguradora</title>
        <?php require_once 'vista/maqueta/linksHead.php' ?>
    </head>

    <body onload="">
        <div class="wrapper">
            <?php require_once 'vista/maqueta/topNavbar.php' ?>
            <div class="sidebar"> <?= $sidebar ?> </div>
            <?php require_once 'vista/maqueta/mainContainer.php' ?>
        </div>

        <?php require_once 'vista/maqueta/footerScripts.php' ?>
    </body>
</html>
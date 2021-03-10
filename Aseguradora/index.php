<!-- Redireccion de sesiones -->
<?php
    session_start();
    if(isset($_SESSION['user'])){
        if($_SESSION['user']['rol']=='Administrador' || $_SESSION['user']['rol']=='Usuario'){
            header('Location: indexMaqueta.php');
        }
    }
?>

<!-- Formulario para iniciar sesion -->
<html>
    <head>
        <title>Aseguradora - Login</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="css/estilosLogin.css">
    </head>
    <body>
        <div class="mjeLogin"></div>

        <div class="login">
            <div class="logoImgLogin">
                <img src="img/imgAlt/logoAsegLetra.png" alt="logo">
            </div>
            <h1>Acceso al sistema</h1>
            <form method="post" id="formLogin">
                <div class="divInput">
                    <i class="fas fa-user"></i>
                    <input type="text" name="u" id="u" placeholder="Usuario"/>
                </div>
                <div class="divInput">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="p" id="p" placeholder="Contraseña"/>
                </div>
                <div class="divInput">
                    <i class="fas fa-building"></i>
                    <select class="suc" name="s" id="selectSuc"></select>
                </div>
                
                <button type="submit" id="envFormLogin" class="btn">
                    <span>Entrar <i class="fas fa-sign-in-alt"></i></span>    
                </button>
                <ul id="errorLogin"></ul>
            </form>
        </div>

        <script type="text/javascript" src="js/plugins/jQuery/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="js/login.js?v=1"></script>
        <!-- jQuery Validation Plugin-->
        <script type="text/javascript" src="js/plugins/jQuery Validation Plugin/dist/jquery.validate.min.js"></script>
        <script type="text/javascript" src="js/plugins/jQuery Validation Plugin/dist/additional-methods.min.js"></script>

        <!--Font Awesome-->
        <script type="text/javascript" src="js/plugins/FontAwesome/fontAwesome.js"></script>

    </body>
</html>
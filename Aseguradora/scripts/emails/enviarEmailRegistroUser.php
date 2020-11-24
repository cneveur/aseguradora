<?php 

    /*-------------------ENVIAMOS EL CORREO ELECTRONICO---------------------*/

    include_once('../../Agregados/phpmailer/Exception.php');
    include_once('../../Agregados/phpmailer/PHPMailer.php');
    include_once('../../Agregados/phpmailer/SMTP.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['info'])) {
        $info       = $_POST['info'];
        $correo     = $info[0];
        $idUser    = $info[1];
    }

    session_start();
    if(isset($_SESSION['pass'])){
        $pass = $_SESSION['pass'];
    }
    unset($_SESSION['pass']);

    //Validamos la informacion que llega
    $error = false;
    $correo = filter_var($correo, FILTER_VALIDATE_EMAIL);
    if($correo===FALSE || is_null($correo)) {$error = true;}
    $idUser = filter_var($idUser, FILTER_VALIDATE_INT);
    if($idUser===FALSE || is_null($idUser)) {$error = true;}
  
    
    /*-------------------CONSULTAMOS A LA BBDD LA INFORMACION PARA ENVIAR---------------------*/
    include_once('../../config/db.php');
    $conexion = Database::connect();    
    $mysqli = $conexion;
    $mysqli->set_charset("utf8");
    $sql = "SELECT nombre, usuario, pass FROM usuarios WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if($stmt->bind_param('s', $idUser)){
        $stmt->execute();
        $r = $stmt->get_result();
        $datos = $r->fetch_assoc();
        $nombreUser = $datos['nombre'];
        $user = $datos['usuario'];
    }

    
    $arr['envCorr'] = false;
    $arr['mje'] = '';
    $mensaje = '<p>Estimado/a '.$nombreUser.', a continuacion se detallara la informacion necesaria para que pueda acceder al sistema de Aseguradora SA </p>
                <p> <b> USUARIO: '.$user.' <br>CONTRASEÑA: '.$pass.' </b> </p> 
                <p>Recomendamos:
                <br>1) Bajo ninguna circunstancia comparta con nadie la anterior información.
                <br>2) Una vez complete el inicio de sesión, modifique la mencionada información para así preservar la seguridad en el sistema.</p>  
                <p>Atte, Aseguradora SA.</p>';

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug  = 0; //SMTP::DEBUG_SERVER;             
        $mail->isSMTP();                                    
        $mail->Host       = 'smtp.gmail.com';               
        $mail->SMTPAuth   = true;                          
        $mail->Username   = 'aseguradora2021@gmail.com';    
        $mail->Password   = 'aseguradoraSociedadAnonima2021';           
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587;                            

        $mail->setFrom('aseguradora2021@gmail.com', 'Aseguradora SA');   
        $mail->addAddress($correo);
        
        $mail->isHTML(true);                   
        $mail->Subject = 'Informacion de acceso';        
        $mail->Body    = $mensaje;  

        if(!$mail->send()){
            $arr['envCorr'] = false;
            $arr['mje'] = 'Error al enviar la info por email';
        }else{
            $arr['envCorr'] = true;
            $arr['mje'] = 'Info enviada por email correctamente';
        }

    } catch (Exception $e) {
        $arr['envCorr'] = false;
        $arr['mje'] = "Error al enviar la info por email. Mailer Error: {$mail->ErrorInfo}";
    }
    echo json_encode($arr);
?>
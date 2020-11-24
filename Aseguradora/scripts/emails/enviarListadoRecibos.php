<?php 

    if(!isset($_SESSION)) 
    { 
        session_start(); 
	} 
    
    
    /*Creamos un PDF*/

    ob_start();
        require '../../vista/pagos/listadoRecibosParaPDF.php';
    $html = ob_get_clean();

    require_once '../../Agregados/mpdf/vendor/autoload.php';

    /*Css*/
    $css = file_get_contents('../../css/recibos/recibosPDF.css');

    $mpdf = new \Mpdf\Mpdf([
        "format" => "A4"
    ]);

    $mpdf->writeHtml($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->writeHtml($html, \Mpdf\HTMLParserMode::HTML_BODY);

    //$listadoRecibos = $mpdf->output();

    $nombreListadoRecibos = md5(rand().'.pdf');

    $listadoRecibos = $mpdf->output($nombreListadoRecibos, \Mpdf\Output\Destination::STRING_RETURN);
  
    $cont = file_put_contents($nombreListadoRecibos, $listadoRecibos);

    include_once '../../Agregados/phpmailer/Exception.php';
    include_once '../../Agregados/phpmailer/PHPMailer.php';
    include_once '../../Agregados/phpmailer/SMTP.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['info'])) {
        $info = $_POST['info'];
        $correo    = $info[0];
        $tomador   = $info[1];
        $nroPoliza = $info[2];
    }
    
    $arr['envCorr'] = false;
    $arr['mje'] = '';
    $mensaje = 'Estimado/a '.$tomador.', adjuntamos los comprobantes de pago ya emitidos correspondientes a la poliza #'.$nroPoliza.'. Atte Aseguradora SA.';

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug  = false;
        $mail->isSMTP();                                    
        $mail->Host       = 'smtp.gmail.com';               
        $mail->SMTPAuth   = true;                          
        $mail->Username   = 'aseguradora2021@gmail.com';    
        $mail->Password   = 'aseguradoraSociedadAnonima2021';           
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587;                            

        $mail->setFrom('aseguradora2021@gmail.com', 'Aseguradora SA');   
        $mail->addAddress($correo);
        $mail->addAttachment($nombreListadoRecibos, 'recibos.pdf');

        $mail->isHTML(true);                   
        $mail->Subject = 'Listado de comprobantes de pago';              
        $mail->Body    = $mensaje;  

        if(!$mail->send()){
            $arr['envCorr'] = false;
            $arr['mje'] = 'Error al enviar el mensaje';
        }else{
            $arr['envCorr'] = true;
            $arr['mje'] = 'Correo enviado correctamente';
        }

    } catch (Exception $e) {
        $arr['envCorr'] = false;
        $arr['mje'] = "Error al enviar el mensaje. Mailer Error: {$mail->ErrorInfo}";
    }
    
    unlink($nombreListadoRecibos);
    echo json_encode($arr);
?>
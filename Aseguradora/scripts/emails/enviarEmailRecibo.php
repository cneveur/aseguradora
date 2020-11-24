<?php 

    ob_start();
        require '../../vista/pagos/reciboPago.php';
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

    $nombreComprobante = md5(rand().'.pdf');

    $listadoRecibos = $mpdf->output($nombreComprobante, \Mpdf\Output\Destination::STRING_RETURN);

    $cont = file_put_contents($nombreComprobante, $listadoRecibos);

    /*ENVIAMOS EL CORREO ELECTRONICO*/
    include_once('../../Agregados/phpmailer/Exception.php');
    include_once('../../Agregados/phpmailer/PHPMailer.php');
    include_once('../../Agregados/phpmailer/SMTP.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['info'])) {
        $info = $_POST['info'];
        $correo   = $info[0];
        $tomador  = $info[1];
        $nroPago  = $info[2];
        $nroCuota = $info[3]; 
    }
    
    $arr['envCorr'] = false;
    $arr['mje'] = '';
    $mensaje = 'Estimado/a '.$tomador.', adjuntamos su factura #'.$nroCuota.', correspondiente al pago #'.$nroPago.'. Atte Aseguradora SA.';

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
        $mail->addAttachment($nombreComprobante, 'comprobante.pdf');

        $mail->isHTML(true);                   
        $mail->Subject = 'Factura';              
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

    unlink($nombreComprobante);
    echo json_encode($arr);
?>
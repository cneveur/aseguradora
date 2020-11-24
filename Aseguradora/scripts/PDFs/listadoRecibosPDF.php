<?php
    
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

    $mpdf->Output('Recibo-'.md5(rand()).'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
?>
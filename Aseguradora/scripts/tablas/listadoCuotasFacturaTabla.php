<?php
    include_once('../../config/db.php');

    if(isset($_POST['pagoId'])){
        $idPago = $_POST['pagoId'];
    }

    $conexion = Database::connect();
    $mysqli = $conexion;
    $mysqli->set_charset("utf8");
    
    $tbody  = array();
    
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    $d = date("d");
    $m = date("m");
    $a = date("Y");

    $fechaActual =  $d.'/'.$m.'/'.$a;

    $sql = "SELECT C.iden_cuota, C.numero_cuota, C.prima_mensual, C.lapso, C.dia_cobrar, C.vto_pago, EC.nombre, C.id, EC.id
            FROM pagos P
            INNER JOIN cuotas C ON C.pago_id = P.id
            INNER JOIN poliza PO ON PO.id = P.poliza_id
            INNER JOIN estado_cuota EC ON EC.id = C.estado
            INNER JOIN tomador T ON T.id = PO.clienteid
            WHERE P.id = ?";

    $stmt = $mysqli->prepare($sql);
    if($stmt!==FALSE){

        $stmt->bind_param('i', $idPago);
        $stmt->execute();
        $re = $stmt->get_result();

        if($re->num_rows > 0){
            while ($cuota = $re->fetch_array()){

                $aFacturar = 0;
                $facturada = 1;
                $aprobado  = 2;
                $rechazado = 3;
                $deuda     = 4;

                $estadoCuota = $cuota[8];
  
                //if( $cuota[4] != $fechaActual )
                if($estadoCuota == $facturada){ //Si el estado es "Facturado (Sin pagar)"

                    $boton = '<a class="btn btn-flat generarFactura" value="'.$cuota[7].'">
                                <span>Emitir</span>
                             </a>';
                }else if($estadoCuota == $aprobado){
                    $boton = '<a class="btn btn-flat verFactura" value="'.$cuota[7].'">
                                <i class="fas fa-search"></i>
                             </a>';
                }else if($estadoCuota == $rechazado){
                    $boton = '<a class="btn btn-flat generarFactura" value="'.$cuota[7].'">
                                <span>Aprobar</span>
                             </a>';
                }else if($estadoCuota == $deuda){
                    $boton = '<a class="btn btn-flat generarFactura" value="'.$cuota[7].'">
                                <span>Saldar</span>
                             </a>';
                }else{
                    $boton = '';
                }

                $primaMensual = '$'.$cuota[2].'';

                $arr              = array();
                $arr['idenCuota'] = $cuota[0];
                $arr['numCuota']  = $cuota[1];
                $arr['priMen']    = $primaMensual;
                $arr['lapso']     = $cuota[3];
                $arr['diaCobrar'] = $cuota[4];
                $arr['vtoPago']   = $cuota[5];
                $arr['estado']    = $cuota[6];
                $arr['boton']     = $boton;

                $tbody[] = $arr;
            }
        }
        $stmt->close();
    }

    echo json_encode($tbody) 

    ?>
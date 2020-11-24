<?php
        include_once '../../config/db.php';

        $conexion = Database::connect();

        $mysqli = $conexion;

        //$tabla  = '';

        $tabla = array();

        $sql  = "SELECT PAGO.id, PAGO.num, POL.nro, CONCAT(TOM.nombre,' - ',TOM.num_tom), (SELECT COUNT(*) FROM cuotas C WHERE C.pago_id = PAGO.id), EP.nombre
                 FROM pagos PAGO
                 INNER JOIN poliza POL ON POL.id = PAGO.poliza_id
                 INNER JOIN tomador TOM ON TOM.id = POL.clienteid
                 INNER JOIN estado_pago EP ON EP.id = PAGO.estado";

        $stmt = $mysqli->prepare($sql);

        if ($stmt!==FALSE) {
            $stmt->execute();
            $rs = $stmt->get_result();

            if ($rs->num_rows > 0)
            {
                while ($mostrar = mysqli_fetch_row($rs)){

                    if($mostrar[5] == 'Caducado'){
                        $boton = '<a class="btn-flat verFactPago" value="'.$mostrar[0].'"><i class="fas fa-search"></i></a>';
                    }else{
                        $boton = '<a class="btn-flat facturarPago" value="'.$mostrar[0].'"><i class="fas fa-pen"></i></a>';
                    }

                    $arr              = array();
                    $arr['nro']       = $mostrar[1];
                    $arr['polizaNum'] = $mostrar[2];
                    $arr['tomador']   = $mostrar[3];
                    $arr['nroCuotas'] = $mostrar[4];
                    $arr['estado']    = $mostrar[5];
                    $arr['boton']     = $boton;
    
                    $tabla[]        = $arr;
                }
            }
        }

        $stmt->close();
        echo json_encode(array('data' => $tabla));
<?php
    include_once '../../config/db.php';

    $conexion = Database::connect();
    $mysqli = $conexion;
    $tabla = array();

    $sql  = "SELECT
        S.id,                       
        S.nroSiniestro AS nroSin,
        P.nro AS nroPol,         
        T.nombre AS tomador,     
        S.fechaDen AS fechDen,
        ES.nombre AS estado           
        FROM siniestro S
        INNER JOIN poliza P ON P.id = S.idPoliza
        INNER JOIN tomador T ON T.id = P.clienteid
        INNER JOIN tiposiniestro TS ON TS.id = S.tipoSiniestro
        INNER JOIN estadosiniestro ES ON ES.id = S.estado";

    $stmt = $mysqli->prepare($sql);

    if ($stmt!==FALSE) {
        $stmt->execute();
        $rs = $stmt->get_result();

        if ($rs->num_rows > 0)
        {
            while ($mostrar = mysqli_fetch_row($rs)){

                //Agregamos un span con la fecha en formato yyyy/mm/dd para poder ordenarlo en datatables
                $f = str_replace('/', '-', $mostrar[4]);
                $fechaOrdenar = date("Y/m/d", strtotime($f));

                $arr            = array();
                $arr['nroSin']  = $mostrar[1];
                $arr['nroPol']  = $mostrar[2];
                $arr['tomador'] = $mostrar[3];
                $arr['fechDen'] = '<span style="display: none">'.$fechaOrdenar.'</span>'.$mostrar[4];
                $arr['estado']  = $mostrar[5];
                $arr['boton']   = '<a class="btn btn-flat verSiniestro" id="verSiniestro" value="'.$mostrar[0].'" > <i class="fas fa-search"></i></a>';
                
                $tabla[]        = $arr;
            }
        }
    }

    $stmt->close();
    echo json_encode(array('data' => $tabla));

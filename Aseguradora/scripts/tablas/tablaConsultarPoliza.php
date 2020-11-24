<?php
        include_once '../../config/db.php';

        $conexion = Database::connect();

        $mysqli = $conexion;

        //$tabla  = '';

        $tabla = array();

        $sql  = "SELECT
        P.id,
        P.nro,
        CONCAT (T.nombre,' - ',T.num_tom),
        CONCAT(MA.nombre,' ', M.nombre),
        P.fecha_emision,
        P.horaEmis,
        CONCAT (P.vigencia_inicio,' - ',P.vigencia_fin),
        P.anulacion,
        EP.nombre,
        EP.descripcion
        FROM poliza P
        INNER JOIN tomador T ON T.id = P.clienteid
        INNER JOIN vehiculo V ON V.id = P.vehiculoid
        INNER JOIN marca MA ON MA.id = V.marca_id
        INNER JOIN modelo M ON M.id = V.modelo_id
        INNER JOIN estadopoliza EP ON EP.id = P.estado";

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

                    $arr             = array();
                    $arr['nro']      = $mostrar[1];
                    $arr['tomador']  = $mostrar[2];
                    $arr['vehiculo'] = $mostrar[3];
                    $arr['emision']  = '<span style="display: none">'.$fechaOrdenar.'</span>'.$mostrar[4].' - '.$mostrar[5];
                    $arr['vigPol']   = $mostrar[6];
                    $arr['anulac']   = $mostrar[7];
                    $arr['estado']   = $mostrar[8];
                    $arr['info']     = $mostrar[9];
                    $arr['boton']    = '<a class="btn btn-flat verConPol" value="'.$mostrar[0].'" id="verConPol"> <i class="fas fa-search"></i></a>';
                    
                    $tabla[]        = $arr;
                }
            }
        }

        $stmt->close();
        echo json_encode(array('data' => $tabla));

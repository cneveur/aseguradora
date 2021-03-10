<?php
    include_once '../../config/db.php';

    $conexion = Database::connect();
    $mysqli = $conexion;
    $tabla = array();

    $sql  = "SELECT
    P.id,
    P.nro,
    CONCAT (T.nombre,' - ',T.num_tom),
    CONCAT (P.vigencia_inicio,' - ',P.vigencia_fin),
    P.fecha_emision,
    P.horaEmis,
    P.anulacion,
    EP.descripcion,
    V.suma_asegurada
    FROM poliza P
    INNER JOIN tomador T ON T.id = P.clienteid
    INNER JOIN vehiculo V ON V.id = P.vehiculoid
    INNER JOIN marca MA ON MA.id = V.marca_id
    INNER JOIN modelo M ON M.id = V.modelo_id
    INNER JOIN estadopoliza EP ON EP.id = P.estado
    WHERE P.estado = 1";

    $stmt = $mysqli->prepare($sql);

    if ($stmt!==FALSE) {
        $stmt->execute();
        $rs = $stmt->get_result();

        if ($rs->num_rows > 0)
        {
            while ($mostrar = mysqli_fetch_row($rs)){

                // Agregamos un span con la fecha en formato yyyy/mm/dd para poder ordenarlo en datatables.
                $f = str_replace('/', '-', $mostrar[4]);
                $fechaOrdenar = date("Y/m/d", strtotime($f));

                $arr            = array();
                $arr['nro']     = $mostrar[1];
                $arr['tomador'] = $mostrar[2];
                $arr['vigPol']  = $mostrar[3];
                $arr['emision']  = '<span style="display: none">'.$fechaOrdenar.'</span>'.$mostrar[4].' - '.$mostrar[5];
                $arr['anulac']  = $mostrar[6];
                $arr['renovar'] = '<a class="btn btn-flat renovarPoliza" value="'.$mostrar[0].'"  data-id="'.$mostrar[1].'" data-sumAs="'.$mostrar[8].'"> <i class="fas fa-arrow-right"></i></a>';
       
                $tabla[]        = $arr;
            }
        }
    }

    $stmt->close();
    echo json_encode(array('data' => $tabla));
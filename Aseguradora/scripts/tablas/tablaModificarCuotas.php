<?php 
    include_once '../../config/db.php';

    $conexion = Database::connect();
    $mysqli = $conexion;
    $tabla = array();

    $sql  = "SELECT
                P.id,
                P.nro,
                CONCAT(T.nombre,' - ',T.num_tom) AS Tomador,
                CONCAT(MA.nombre, ' ', M.nombre) AS Vehiculo,
                P.vigencia_inicio,
                P.vigencia_fin,
                P.fecha_emision,
                EP.nombre AS estadoPa
            FROM poliza P
            INNER JOIN tomador T      ON T.id = P.clienteid
            INNER JOIN vehiculo V     ON V.id = P.vehiculoid
            INNER JOIN marca MA       ON MA.id = V.marca_id
            INNER JOIN modelo M       ON M.id = V.modelo_id
            INNER JOIN pagos PA       ON PA.poliza_id = P.id
            INNER JOIN estado_pago EP ON EP.id = PA.estado
            WHERE P.estado = 0 || P.estado = 5";

    $stmt = $mysqli->prepare($sql);
    if ($stmt!==FALSE) {
        $stmt->execute();
        $rs = $stmt->get_result();
        if ($rs->num_rows > 0)
        {
            while ($mostrar = mysqli_fetch_row($rs)){

                $arr             = array();
                $arr['nro']      = $mostrar[1];
                $arr['tomador']  = $mostrar[2];
                $arr['vehiculo'] = $mostrar[3];
                $arr['vigini']   = $mostrar[4];
                $arr['vigfin']   = $mostrar[5];
                $arr['reg']      = $mostrar[6];
                
                $arr['boton']    = '<a class="btn-flat btnAdminPago" value="'.$mostrar[0].'"><i class="fas fa-pen"></i></a>';
                $tabla[] = $arr;
            }
        }
    }
    $stmt->close();
    echo json_encode(array('data' => $tabla));
 ?>
<?php 
    include_once '../../config/db.php';

    $conexion = Database::connect();
    $mysqli = $conexion;
    $tabla = array();

    $sql  = "SELECT
            P.id,
            P.nro,
            T.nombre AS Tomador,
            CONCAT(MA.nombre, ' ', M.nombre) AS Vehiculo,
            C.nombre AS Cobertura,
            P.fecha_emision,
            P.emisor,
            P.vigencia_inicio,
            P.vigencia_fin,
            EP.nombre AS estado,
            P.estado
            FROM poliza P
            INNER JOIN tomador T ON T.id = P.clienteid
            INNER JOIN vehiculo V ON V.id = P.vehiculoid
            INNER JOIN marca MA ON MA.id = V.marca_id
            INNER JOIN modelo M ON M.id = V.modelo_id
            INNER JOIN cobertura C ON C.id = P.coberturaid
            INNER JOIN estadopoliza EP ON EP.id = P.estado
            WHERE P.estado = 0 OR P.estado = 3";
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
                $arr['vigini']   = $mostrar[7];
                $arr['vigfin']   = $mostrar[8];
                $arr['estado']   = $mostrar[9];
                $arr['boton']    = ' <a class="btn btn-flat editarPol" value="'.$mostrar[0].'"> <i class="fas fa-plus"></i></a>';

               

                $tabla[]         = $arr;
            }
        }
    }
    $stmt->close();
    
    echo json_encode(array('data' => $tabla));
 ?>
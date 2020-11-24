<?php 
    include_once '../../config/db.php';

    $conexion = Database::connect();

    $mysqli = $conexion;

    $tabla = array();

    $sql  = "SELECT
            P.id AS idPoliza,
            E.id,
            E.polizaNum,
            E.descripcion,
            E.polizaId,
            E.fechaRegistroEnd,
            E.horaRegistroEnd,
            TE.descripcion,
            T.nombre AS Tomador,
            EP.nombre AS Estado
            FROM endoso E
            INNER JOIN poliza P ON E.polizaId = P.id
            INNER JOIN tomador T ON P.clienteId = T.id
            INNER JOIN tipo_endoso TE ON TE.id = E.tipo
            INNER JOIN estadopoliza EP ON EP.id = P.estado
            GROUP BY P.id";
    $stmt = $mysqli->prepare($sql);
    if ($stmt!==FALSE) {
        $stmt->execute();
        $rs = $stmt->get_result();
        if ($rs->num_rows > 0)
        {
            while ($mostrar = mysqli_fetch_row($rs)){

                $arr             = array();
                $arr['nro']      = $mostrar[2];
                $arr['tomador']  = $mostrar[8];
                $arr['estado']   = $mostrar[9];
                $arr['boton']    = '<a class="btn btn-flat verEnd" value="'.$mostrar[0].'"><i class="fas fa-search"></i></a>';

                $tabla[]         = $arr;
            }
        }
    }
    $stmt->close();
    echo json_encode(array('data' => $tabla));

    /*onclick="procesarDatosEndoso('.$mostrar[0].')"*/
 ?>
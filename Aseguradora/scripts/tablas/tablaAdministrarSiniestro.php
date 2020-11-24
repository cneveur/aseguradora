<?php 
    include_once '../../config/db.php';

    $conexion = Database::connect();

    $mysqli = $conexion;

    $tabla = array();

    $sql  = "SELECT
             S.id,
             S.estado,
             S.nroSiniestro,
             P.nro AS NroPol,
             T.nombre AS Tomador,
             TS.nombre AS Tipo,
             S.fechaDen AS fechDen,
             ES.nombre AS Estado
             FROM siniestro S
             INNER JOIN poliza P ON P.id = S.idPoliza
             INNER JOIN tomador T ON T.id = P.clienteid
             INNER JOIN tiposiniestro TS ON TS.id = S.tipoSiniestro
             INNER JOIN estadosiniestro ES ON ES.id = S.estado";
    $stmt = $mysqli->prepare($sql);
    if ($stmt!==FALSE) {
        $stmt->execute();
        $rs = $stmt->get_result();
        if ($rs->num_rows > 0){

            while ($mostrar = mysqli_fetch_row($rs)){

                $btnModificarEstado = '<a class="btn btn-flat modificarEstado" value="'.$mostrar[0].'" onclick="cambiarEstadoSin('.$mostrar[0].','.$mostrar[1].')"> <i class="fas fa-pencil-alt"></i></a>';
                $btnAdmin = '<a class="btn btn-flat ModRestoDatos" value="'.$mostrar[0].'"> <i class="fas fa-pen"></i></a>';
                $btnImgs = '<a class="btn btn-flat modImagenSin" value="'.$mostrar[0].'"> <i class="fas fa-images"></i></a>';
                
                $arr            = array();
                $arr['nroSin']  = $mostrar[2];
                $arr['nroPol']  = $mostrar[3];
                $arr['tomador'] = $mostrar[4];
                $arr['tipo']    = $mostrar[5];
                $arr['fechDen'] = $mostrar[6];
                $arr['estado']  = $mostrar[7].' '.$btnModificarEstado;
                $arr['botones'] = $btnAdmin.' '.$btnImgs;

                $tabla[]        = $arr;
            }
        }
    }
    $stmt->close();
    echo json_encode(array('data' => $tabla));

    /*onclick="traerDatosSin('.$mostrar[0].')" */
 ?>
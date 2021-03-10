<?php 
    include_once '../../config/db.php';

    $conexion = Database::connect();
    $mysqli = $conexion;
    $tabla = array();

    $sql  = "SELECT T.id, T.nombre, T.num_tom, P.nombre AS provincia, proper(L.nombre) AS localidad
             FROM tomador T
             INNER JOIN provincia P ON P.id = T.provincia
             INNER JOIN localidad L ON L.id = T.localidad ";

    $stmt = $mysqli->prepare($sql);
    if ($stmt!==FALSE) {
        $stmt->execute();
        $rs = $stmt->get_result();
        if ($rs->num_rows > 0)
        {
            while ($mostrar = mysqli_fetch_row($rs)){

                $btnEditar = '<a class="btn btn-flat editarDatosTom" value="'.$mostrar[0].'"><i class="fas fa-pen"></i></a>';
                $btnVer = '<a class="btn btn-flat verTom" value="'.$mostrar[0].'"><i class="fas fa-search"></i></a>';
                $btnEliminar = '<a class="btn btn-flat bajaTom" value="'.$mostrar[0].'"><i class="fas fa-trash-alt"></i></a>';

                $arr              = array();
                $arr['nombre']    = $mostrar[1];
                $arr['num_tom']   = $mostrar[2];
                $arr['provincia'] = $mostrar[3];
                $arr['localidad'] = $mostrar[4];
                $arr['acciones']  = $btnEditar.' '.$btnVer.' '.$btnEliminar;

                $tabla[]          = $arr;
            }
        }
    }
    $stmt->close();
    echo json_encode(array('data' => $tabla));

 ?>
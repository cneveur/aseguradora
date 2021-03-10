<?php
    include_once '../../config/db.php';

    session_start();
    if(isset($_SESSION['user'])){
        $idSesion = $_SESSION['user']['id'];
    }

    $conexion = Database::connect();
    $mysqli = $conexion;
    $tabla = array();

    $sql  = "SELECT id, nro, nombre, rol, estado FROM usuarios WHERE estado = 'Activo' AND id <> ?";

    $stmt = $mysqli->prepare($sql);

    if ($stmt!==FALSE) {
        $stmt->bind_param('i', $idSesion);
        $stmt->execute();
        $rs = $stmt->get_result();

        if ($rs->num_rows > 0)
        {
            while ($mostrar = mysqli_fetch_row($rs)){

                $arr            = array();
                $arr['nro']     = $mostrar[1];
                $arr['tomador'] = $mostrar[2];
                $arr['rol']     = $mostrar[3];
                $arr['estado']  = $mostrar[4];
                $arr['boton']   = '<a value="'.$mostrar[0].'"></a>';

                $tabla[]        = $arr;
            }
        }
    }

    $stmt->close();
    echo json_encode(array('data' => $tabla));
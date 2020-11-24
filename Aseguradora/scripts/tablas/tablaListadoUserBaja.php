<?php
    include_once '../../config/db.php';

    $conexion = Database::connect();

    $mysqli = $conexion;

    $tabla = array();

    $sql  = "SELECT id, nro, nombre, rol, estado FROM usuarios WHERE estado = 'Baja'";

    $stmt = $mysqli->prepare($sql);

    if ($stmt!==FALSE) {
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
                $arr['boton']   = '<a class="btn-flat reactivarUser" value="'.$mostrar[0].'"> <i class="fas fa-user-check"></i></a>';

                $tabla[]        = $arr;
            }
        }
    }

    $stmt->close();
    echo json_encode(array('data' => $tabla));
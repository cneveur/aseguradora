<?php
    include_once '../../config/db.php';

    $conexion = Database::connect();

    $mysqli = $conexion;

    $tabla = array();

    $sql  = "SELECT S.id,
                    S.iden_sucursal,
                    S.nombre,
                    S.direccion,
                    L.codigopostal,
                    CONCAT(proper(L.nombre),' - ',P.nombre)
            FROM sucursales S
            INNER JOIN provincia P ON P.id = S.provincia
            INNER JOIN localidad L ON L.id = S.localidad";

    $stmt = $mysqli->prepare($sql);

    if ($stmt!==FALSE) {
        $stmt->execute();
        $rs = $stmt->get_result();

        if ($rs->num_rows > 0)
        {
            while ($mostrar = mysqli_fetch_row($rs)){

                $verSuc = '<a class="btn btn-flat" value="'.$mostrar[0].'" id="verSucursal"> <i class="fas fa-search"></i></a>';
                $modSuc = '<a class="btn btn-flat" value="'.$mostrar[0].'" id="modSucursal"> <i class="fas fa-pen"></i></a>';
                $eliSuc = '<a class="btn btn-flat" value="'.$mostrar[0].'" id="eliSucursal"> <i class="fas fa-trash"></i></a>';

                $arr          = array();
                $arr['iden']  = $mostrar[1];
                $arr['nom']   = $mostrar[2];
                $arr['dir']   = $mostrar[3];
                $arr['cp']    = $mostrar[4];
                $arr['ubic']  = $mostrar[5];
                $arr['acc']   = $verSuc.' '.$modSuc.' '.$eliSuc;
                
                $tabla[]        = $arr;
            }
        }
    }

    $stmt->close();
    echo json_encode(array('data' => $tabla));

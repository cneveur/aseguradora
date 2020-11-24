<?php
    include_once('../../config/db.php');

    if(isset($_POST['idPol'])){
        $id = $_POST['idPol'];
    }

    $conexion = Database::connect();
    $mysqli = $conexion;
    $mysqli->set_charset("utf8");
    $sql  = "SELECT E.polizaNum AS polizaNum, T.nombre AS tomador, E.fechaRegistroEnd, E.horaRegistroEnd, 
             TE.descripcion AS tipo, SUBSTRING(E.descripcion, 1, 37) AS descripcion, E.id, E.endosoNum
             FROM endoso E
             INNER JOIN tipo_endoso TE ON TE.id = E.tipo
             INNER JOIN poliza P ON E.polizaId = P.id
             INNER JOIN tomador T ON T.id = P.clienteid 
             WHERE E.polizaId = ?
             ORDER BY E.fechaRegistroEnd DESC";
    $stmt = $mysqli->prepare($sql);
    if ($stmt!==FALSE) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $rs = $stmt->get_result();
        if ($rs->num_rows > 0)
        {
            while ($mostrar = mysqli_fetch_row($rs)){
             
                $arr = array();
                $arr['nro']         =  $mostrar[7];
                $arr['fecha']       =  $mostrar[2];
                $arr['hora']        =  $mostrar[3];
                $arr['tipo']        =  $mostrar[4];
                $arr['descripcion'] =  $mostrar[5].'...';
                $arr['btn']         = '<a class="btn btn-flat editarDescripcion" value="'.$mostrar[6].'"><i class="fas fa-pencil-alt"></i></a>';
                                
                $arr1[]             = $arr;
            }
        }
        echo json_encode($arr1);
    }
    $stmt->close();

    /*onclick="editarDescripcion('.$mostrar[0].')"*/
 ?>
<?php 

class TomadoresController{

    private $conexion;

    public function __construct()
    {
        $this->conexion = Database::connect();
        date_default_timezone_set("America/Argentina/Buenos_Aires");
    }

    public function mostrarDatosCliente($post)
    {
        $conexion = mysqli_connect('localhost', 'root', '', 'aseguradora');

        $idtomador = $post['idtomador'];

        $arr = array('success'=>false);

        $query = "SELECT T.id, T.nombre, T.documento, T.persona, T.nacionalidad, P.nombre AS provincia, proper(L.nombre) AS localidad, T.cp, T.calle, T.genero, T.telefono, T.correo, T.fecha_nac
                FROM tomador T
                INNER JOIN provincia P ON P.id = T.provincia
                INNER JOIN localidad L ON L.id = T.localidad
                WHERE T.id = '$idtomador'";

        $resultado = $this->conexion->query($query);

        while ($tomador = mysqli_fetch_row($resultado)) {
            $_SESSION['T.id']    = $tomador[0];
            $arr['nombre']       = $tomador[1];
            $arr['documento']    = $tomador[2];
            $arr['persona']      = $tomador[3];
            $arr['nacionalidad'] = $tomador[4];
            $arr['provincia']    = $tomador[5];
            $arr['localidad']    = $tomador[6];
            $arr['cp']           = $tomador[7];
            $arr['calle']        = $tomador[8];
            $arr['genero']       = $tomador[9];
            $arr['telefono']     = $tomador[10];
            $arr['correo']       = $tomador[11];
            $arr['fecha_nac']    = $tomador[12];
            $arr['success']      = true;
            //cuando vayas a hacer lo que tengas que hacer con la variable de session['id']
            //hacer unset($_SESSION['id'])
        }

        echo json_encode(array('data' => $arr));
    }

    public function grabarCliente($post)
    {
        try{

            $mysqli = $this->conexion;

            $error = false;

            $arr = array('success'=>false);

            $datos = $post['d'];
            
            $nom =  filter_var($datos[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($nom===FALSE || is_null($nom)) {$error = true;}

            $docu = filter_var($datos[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($docu===FALSE || is_null($docu)) {$error = true;}

            $per = filter_var($datos[2], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            if($per===FALSE || is_null($per)) {$error = true;}

            $nac = filter_var($datos[3], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($nac===FALSE || is_null($nac)) {$error = true;}

            $pro = filter_var($datos[4], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            if($pro===FALSE || is_null($pro)) {$error = true;}

            $loc = filter_var($datos[5], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            if($loc===FALSE || is_null($loc)) {$error = true;}

            $cp = filter_var($datos[6], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            if($cp===FALSE || is_null($cp)) {$error = true;}

            $call = filter_var($datos[7], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            if($call===FALSE || is_null($call)) {$error = true;}

            $gen = filter_var($datos[8], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            if($gen===FALSE || is_null($gen)) {$error = true;}

            $tel = filter_var($datos[9], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            if($tel===FALSE || is_null($tel)) {$error = true;}

            if($datos[10] == ''){
                $corr = '';
            }else{
                $corr = filter_var($datos[10], FILTER_VALIDATE_EMAIL); 
                if($corr===FALSE) {$error = true;}
            }
            
            $fechNac = filter_var($datos[11], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            if($fechNac===FALSE || is_null($fechNac)) {$error = true;}

            if(!$error){

                $nom1  = ucwords($nom); 
                $per1  = ucwords($per); 
                $nac1  = ucwords($nac); 
                $pro1  = ucwords($pro);
                $loc1  = ucwords($loc); 
                $call1 = ucwords($call); 
                $gen1  = ucwords($gen);

                $num_tom = rand(9999999, 99999999);

                $mysqli->set_charset("utf8");
                $sql  = "INSERT INTO tomador (num_tom, nombre, documento, persona, nacionalidad, provincia, localidad, cp, calle, genero, telefono, correo, fecha_nac) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $stmt = $mysqli->prepare($sql);
                if ($stmt!==FALSE) {
                    $stmt->bind_param('sssssssssssss', $num_tom, $nom1, $docu, $per1, $nac1, $pro1, $loc1, $cp, $call1, $gen1, $tel, $corr, $fechNac);
                    $stmt->execute();
                    

                    //Devolvemos el id del tomador registrado
                    $sql = "SELECT MAX(id) AS id FROM tomador";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->execute();
                    $rs = $stmt->get_result();
                    if ($rs->num_rows > 0) {
                        while ($fila = $rs->fetch_array()) {
                            $idTomador = $fila[0];
                        }
                    }

                    $stmt->close();
                    $arr = array('success'=>true, 'idTomador'=>$idTomador);
                }
            }

            return json_encode($arr);
        
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function mostrarDatosTomador($post)
    {
        $id = $post['id'];
        $arr = array();
        $mysqli = $this->conexion;
        $sql = "SELECT T.id, T.num_tom, T.nombre, T.documento, T.persona, T.nacionalidad, P.nombre as provinciaNombre, proper(L.nombre) as localidadNombre, T.cp, T.calle, T.genero, T.telefono, T.correo, T.fecha_nac, T.localidad as localidadId, T.provincia as provinciaId
                FROM tomador T
                INNER JOIN provincia P ON P.id = T.provincia
                INNER JOIN localidad L ON L.id = T.localidad
                WHERE T.id = ?";
        $stmt = $mysqli->prepare($sql);

        if($stmt!==FALSE){
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if($resultado->num_rows > 0){

                while($dato = $resultado->fetch_array()){
                    $arr['id']              = $dato[0];
                    $arr['nroTom']          = $dato[1];
                    $arr['nombre']          = $dato[2];
                    $arr['documento']       = $dato[3];
                    $arr['persona']         = $dato[4];
                    $arr['nacionalidad']    = $dato[5];
                    $arr['provinciaNombre'] = $dato[6];
                    $arr['localidadNombre'] = $dato[7];
                    $arr['cp']              = $dato[8];
                    $arr['calle']           = $dato[9];
                    $arr['genero']          = $dato[10];
                    $arr['telefono']        = $dato[11];
                    $arr['correo']          = $dato[12];
                    $arr['fecha_nac']       = $dato[13];
                    $arr['localidadId']     = $dato[14];
                    $arr['provinciaId']     = $dato[15];

                    $arr['success']         = true;
                }
            }
        }

        $stmt->close();
        echo json_encode($arr);
    }

    public function modificarDatosTomador($post)
    {
        $datos = $post['datos'];

        $arr = array('success'=>false, 'accion'=>0);

        $error = false;

        $nom =  filter_var($datos[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nom===FALSE || is_null($nom)) {$error = true;}

        $docu = filter_var($datos[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($docu===FALSE || is_null($docu)) {$error = true;}

        $per = filter_var($datos[2], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        if($per===FALSE || is_null($per)) {$error = true;}

        $nac = filter_var($datos[3], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nac===FALSE || is_null($nac)) {$error = true;}

        $fechNac = filter_var($datos[4], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        if($fechNac===FALSE || is_null($fechNac)) {$error = true;}

        $pro = filter_var($datos[5], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        if($pro===FALSE || is_null($pro)) {$error = true;}

        $cp = filter_var($datos[6], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        if($cp===FALSE || is_null($cp)) {$error = true;}

        $loc = filter_var($datos[7], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        if($loc===FALSE || is_null($loc)) {$error = true;}

        $tel = filter_var($datos[8], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        if($tel===FALSE || is_null($tel)) {$error = true;}

        $call = filter_var($datos[9], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        if($call===FALSE || is_null($call)) {$error = true;}

        $gen = filter_var($datos[10], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        if($gen===FALSE || is_null($gen)) {$error = true;}

        if($datos[11] == ''){
            $corr = '';
        }else{
            $corr = filter_var($datos[11], FILTER_VALIDATE_EMAIL); 
            if($corr===FALSE) {$error = true;}
        }

        $id = $datos[12];

        if(!$error){

        $nom1  = ucwords($nom); 
        $per1  = ucwords($per); 
        $nac1  = ucwords($nac); 
        $pro1  = ucwords($pro);
        $loc1  = ucwords($loc); 
        $call1 = ucwords($call); 
        $gen1  = ucwords($gen);

        $sql  = 
        "SELECT id, nombre, documento, persona, nacionalidad, provincia, localidad, cp, calle, genero, telefono, correo, fecha_nac
            FROM tomador
            WHERE nombre       = ? AND
                documento    = ? AND
                persona      = ? AND
                nacionalidad = ? AND
                provincia    = ? AND
                localidad    = ? AND
                cp           = ? AND
                calle        = ? AND
                genero       = ? AND
                telefono     = ? AND
                correo       = ? AND
                fecha_nac    = ? AND
                id           = ?   ";

        $mysqli = $this->conexion;
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ssssssssssssi', $nom1, $docu, $per1, $nac1, $pro1, $loc1, $cp, $call1, $gen1, $tel, $corr, $fechNac, $id);
        $stmt->execute();
        $rs = $stmt->get_result();

        if($rs->num_rows == 0){

            $sql = "UPDATE tomador
                    SET nombre       = ?,
                        documento    = ?,
                        persona      = ?,
                        nacionalidad = ?,
                        provincia    = ?,
                        localidad    = ?,
                        cp           = ?,
                        calle        = ?,
                        genero       = ?,
                        telefono     = ?,
                        correo       = ?,
                        fecha_nac    = ?
                    WHERE id         = ? ";

                $stmt = $mysqli->prepare($sql);

                if ($stmt!==FALSE) {
                    $stmt->bind_param('ssssssssssssi', $nom1, $docu, $per1, $nac1, $pro1, $loc1, $cp, $call1, $gen1, $tel, $corr, $fechNac, $id);
                    $stmt->execute();
                    $arr = array('success'=>true, 'accion'=>1);
                }  

        }else{
            $arr = array('success'=>true, 'accion'=>2);
        }
        }
        $stmt->close();
        echo json_encode($arr);
    }

    public function eliminarTomador($id)
    {

        //Accion 1 El tomador fue eliminado correctamente
        //Accion 2 No se ejecuto la consulta, no fue posible eliminar el tomador

        $id = $id['id'];
        $arr = array('success'=>false, 'accion'=>0);
        $mysqli = $this->conexion;

        $esTomador = "SELECT P.clienteid
                FROM poliza P 
                WHERE P.clienteid = ?";

        $stmt = $mysqli->prepare($esTomador);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 0){
            $sql = "DELETE FROM tomador WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('i', $id);
            if($stmt->execute()){
                $arr = array('success'=>true , 'accion'=>1);
            }else{
                $arr = array('success'=>true, 'accion'=>2);
            }
        }else{
            $arr = array('success'=>true, 'accion'=>2, 'cantPol' => $result->num_rows);
        }
        
        $stmt->close();

        echo json_encode($arr);
    }

    public function listarTomadores()
    {
        $tomadores = '';
        $mysqli = $this->conexion;
        $tomadores = array();

        $sql = "SELECT id, nombre, documento FROM tomador";

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){
            $stmt->execute();

            $rs = $stmt->get_result();
            if ($rs->num_rows > 0) {
                // $tomadores .= '<option value="0" selected disabled>Seleccione</option>';
                // foreach($rs as $tom){
                //     $tomadores .= '<option value="'.$tom['id'].'">'.$tom['nombre'].' - '.$tom['documento'].'</option>';
                // }   
                foreach($rs as $tom){
                    $arr           = array();
                    $arr['id']     = $tom['id'];
                    $arr['nombre'] = $tom['nombre'];
                    $arr['doc']    = $tom['documento'];
                    $tomadores[]   = $arr;
                }   
            }

            $stmt->close();
            // return $tomadores;
            echo json_encode($tomadores);
        }
    }
}
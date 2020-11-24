<?php

    //include_once('../config/db.php');

    class EndososController{

        private $conexion;

        public function __construct()
        {
            $this->conexion = Database::connect();
            date_default_timezone_set("America/Argentina/Buenos_Aires");
        }

        public function grabarEndoso($info)
        {
            $mysqli = $this->conexion;
    
            $arr = array('success'=>false);
    
            $error = false;
    
            $idPoliza = $info['idPoliza'];
    
            date_default_timezone_set("America/Argentina/Buenos_Aires");      
    
            $tipoEnd = filter_var($info['tipoEnd'], FILTER_VALIDATE_INT);
            if($tipoEnd===FALSE || is_null($tipoEnd)) {$error = true;}
    
            $idPoliza = filter_var($idPoliza, FILTER_VALIDATE_INT);
            if($idPoliza===FALSE || is_null($idPoliza)) {$error = true;}
    
            $fechaEmisEnd = date("d/m/Y");
    
            $horaEmisEnd = date("H:i");

            if(!isset($_SESSION)){
                session_start();
            }
            if(isset($_SESSION['user'])){
                $emis = $_SESSION['user']['id']; 
            }

    
            //OBTENEMOS EL NUMERO DE POLIZA PARA GUARDARLO EN EL ENDOSO!
            $nroPoliza  = 
            "SELECT nro FROM poliza
             WHERE id = ?";
    
            $stmt = $mysqli->prepare($nroPoliza);
            if($stmt!==FALSE){
                $stmt->bind_param('i', $idPoliza);
                $stmt->execute();
    
                $rs = $stmt->get_result();
                if ($rs->num_rows > 0) {
                    while ($fila = $rs->fetch_array()) {
                        $nroPoliza = $fila[0];
                    }
                }
            }
           
            if($tipoEnd==1){
                $descripcion = "Modificacion de riesgo poliza $nroPoliza.";
            }else if($tipoEnd==2){
                $descripcion = "Modificacion de datos nominales poliza $nroPoliza.";
            }else if($tipoEnd==3){
                $descripcion = "Modificacion de datos del vehiculo poliza $nroPoliza.";
            }else if($tipoEnd==4){
                $descripcion = "Modificacion de asegurado poliza $nroPoliza.";
            }else if($tipoEnd==5){
                $descripcion = "Cambio de cobertura poliza $nroPoliza.";
            }else if($tipoEnd==6){
                $descripcion = "Modificacion de Estado de Poliza $nroPoliza.";
            }
    
            //generamos un numero aleatorio para asignarle al endoso
            $numeroEnd = rand(999999999,9999999999);
    
            if(!$error){
    
                $mysqli->set_charset("utf8");
                $sql  = "INSERT INTO endoso (polizaId, endosoNum, polizaNum, fechaRegistroEnd, horaRegistroEnd, emisor, tipo, descripcion) VALUES (?,?,?,?,?,?,?,?)";
                $stmt = $mysqli->prepare($sql);
                if ($stmt!==FALSE) {
                    $stmt->bind_param('issssiis', $idPoliza, $numeroEnd, $nroPoliza, $fechaEmisEnd, $horaEmisEnd, $emis, $tipoEnd, $descripcion);
                    $stmt->execute();
                    $stmt->close();
    
                    $arr = array('success'=>true);
                }
            }
    
            return json_encode($arr);
        }

        public function mostrarEndososPorPoliza($id)
        {
            $mysqli = $this->conexion;
            $arr = array('success'=>false);
            $id = $id['id'];
            $mysqli->set_charset("utf8");
            $sql  = "SELECT E.polizaNum AS polizaNum, T.nombre AS tomador, E.fechaRegistroEnd, E.horaRegistroEnd, TE.descripcion AS tipo, E.descripcion AS descripcion, E.id, E.endosoNum, P.id
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
    
                        $arr = array
                        (
                            $mostrar[7],
                            $mostrar[2],
                            $mostrar[3],
                            $mostrar[4],
                            $mostrar[5],
                           '<a class="editarDescripcion" value="'.$mostrar[6].'"><i class="fas fa-pencil-alt"></i></a>'
                        );
                        $numPol = $mostrar[0];
                        $tom    = $mostrar[1];
                        $idPol  = $mostrar[8];
                        //$idEnd =array($mostrar[6]);
                        //$arr['success'] = true;
    
                        $arr1[] = $arr;
                        //$idEnd[] = $idEnd;
                    }
                }
                echo json_encode(array(/*'data' => $arr1,*/ 'success'=>true, 'numPol'=>$numPol, 'tom'=>$tom, /*'idPol'=>$idPol*/));
            }
            $stmt->close();
        }
    
        public function traerDescripcionEndoso($idEndoso)
        {
            $mysqli = $this->conexion;
    
            $arr = array('success'=>false);
    
            $idEndoso = $idEndoso['idEndoso'];
    
            $mysqli->set_charset("utf8");
    
            $sql  = "SELECT descripcion, endosoNum
                    FROM endoso
                    WHERE id = ? ";
    
            $stmt = $mysqli->prepare($sql);
    
            if ($stmt!==FALSE) {
    
                $stmt->bind_param('i', $idEndoso);
                $stmt->execute();
                $rs = $stmt->get_result();
    
                if ($rs->num_rows > 0) {
                    while ($endoso = $rs->fetch_array()) {
    
                        $arr['descripcion'] = $endoso[0];
                        $arr['endosoNum']   = $endoso[1];
                        $arr['idEndoso']    = $idEndoso;
                        $arr['success']     = true;
                    }
                }
            }
    
            $stmt->close();
            echo json_encode(array('data' => $arr));
        }
    
        public function actualizarDescripcion($post)
        {
            $arr = array('success'=>false);
    
            $error = false;
    
            $idEndoso = $post['idEndoso'];
    
            $nuevaDesc = filter_var($post['nuevaDesc'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($nuevaDesc===FALSE || is_null($nuevaDesc)) {$error = true;}
    
            if(!$error){
    
                $mysqli = $this->conexion;
    
                $mysqli->set_charset("utf8");
    
                $sql = "UPDATE endoso
                        SET descripcion = ?
                        WHERE id        = ?";
    
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('si', $nuevaDesc, $idEndoso);
                $stmt->execute();
                $rs = $stmt->get_result();
    
                if ($stmt!==FALSE){
                    $arr = array('success'=>true);
                }
                $stmt->close();
            }
    
            echo json_encode($arr);
        }

        public function listadoTipoEndosos()
        {
            $mysqli = $this->conexion;
            $tiposEnd = '';
    
            $sql = "SELECT id, descripcion FROM tipo_endoso";
            $stmt = $mysqli->prepare($sql);
            if($stmt!=FALSE){
                $stmt->execute();
                $rs = $stmt->get_result();
                if($rs->num_rows > 0){
                    $tiposEnd .= '<option value="0" selected disabled>Seleccione</option>';
                    foreach($rs as $end){
                        $tiposEnd .= '<option value="'.$end['id'].'">'.$end['descripcion'].'</option>';
                    }   
                }
            }
            $stmt->close();
    
            return $tiposEnd;
        }
    }
<?php 
include_once('../config/db.php');

class SucursalesController{

    private $conexion;

    function __construct(){
        $this->conexion = Database::connect();
    }

    public function registrarSucursal($post)
    {
    
        $mysqli = $this->conexion;

        $error = false;

        $arr['success'] = false;

        $info = $post['data'];

        $nombre = filter_var($info[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nombre===FALSE || is_null($nombre)) {$error = true;}

        $cuit = filter_var($info[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($cuit===FALSE || is_null($cuit)) {$error = true;}

        $direccion = filter_var($info[2], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($direccion===FALSE || is_null($direccion)) {$error = true;}

        $provincia = filter_var($info[3], FILTER_VALIDATE_INT);
        if($provincia===FALSE || is_null($provincia)) {$error = true;}

        $cp = filter_var($info[4], FILTER_VALIDATE_INT);
        if($cp===FALSE || is_null($cp)) {$error = true;}

        $localidad = filter_var($info[5], FILTER_VALIDATE_INT);
        if($localidad===FALSE || is_null($localidad)) {$error = true;}

        if(!$error){

            $ident = chr(rand(ord('A'), ord('Z'))).chr(rand(ord('A'), ord('Z'))).'-'.rand(111111, 999999);

            $nombre = ucwords($nombre);
            $ident = ucwords($ident);
            $direccion = ucwords($direccion);

            $sql = "INSERT INTO sucursales (nombre, iden_sucursal, cuit, direccion, cp, localidad, provincia)
                    VALUES (?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            if($stmt!=FALSE){
                $stmt->bind_param('ssssiii', $nombre, $ident, $cuit, $direccion, $cp, $localidad, $provincia);
                if($stmt->execute()){
                    $arr['success'] = true;
                }
            }

            $stmt->close();
        }

        return json_encode($arr);
    }

    public function eliminarSucursal($post)
    {
        $idSuc = $post['idSuc'];
        $arr['success'] = false;
        $mysqli = $this->conexion;
        
        $sql = "DELETE FROM sucursales WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        if($stmt!=FALSE){
            $stmt->bind_param('i', $idSuc);
            if($stmt->execute()){
                $arr['success'] = true;
            }
        }
        $stmt->close();

        return json_encode($arr);
    }

    public function traerInfoSucursal($post)
    {
        $arr['success'] = false;
        $mysqli = $this->conexion;
        $idSuc = $post['idSuc'];
        $info = '';

        $sql = "SELECT S.nombre         AS nombre,
                        S.iden_sucursal  AS iden, 
                        S.cuit           AS cuit, 
                        S.direccion      AS direccion, 
                        S.cp             AS cp, 
                        S.localidad      AS localidadId,
                        S.provincia      AS provinciaId,
                        proper(L.nombre) AS localidad,
                        P.nombre         AS provincia
                FROM sucursales S
                INNER JOIN localidad L ON L.id = S.localidad
                INNER JOIN provincia P ON P.id = S.provincia
                WHERE S.id = ?";

        $stmt = $mysqli->prepare($sql);
        if($stmt){
            $stmt->bind_param('i', $idSuc);
            if($stmt->execute()){
                $r = $stmt->get_result();
                $info = $r->fetch_assoc();
                $arr['info'] = $info;
                $arr['success'] = true;
            }
        }
        $stmt->close();

        return json_encode($arr);
    }

    public function modificarSucursal($post)
    {
        $info = $post['data'];
        $arr['success']  = false;
        $arr['repetido'] = false;
        $mysqli = $this->conexion;

        $nombre = filter_var($info[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nombre===FALSE || is_null($nombre)) {$error = true;}

        $cuit = filter_var($info[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($cuit===FALSE || is_null($cuit)) {$error = true;}

        $direccion = filter_var($info[2], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($direccion===FALSE || is_null($direccion)) {$error = true;}

        $provincia = filter_var($info[3], FILTER_VALIDATE_INT);
        if($provincia===FALSE || is_null($provincia)) {$error = true;}

        $cp = filter_var($info[4], FILTER_VALIDATE_INT);
        if($cp===FALSE || is_null($cp)) {$error = true;}

        $localidad = filter_var($info[5], FILTER_VALIDATE_INT);
        if($localidad===FALSE || is_null($localidad)) {$error = true;}

        $idSuc = $info[6];

        $sql = "SELECT nombre, cuit, direccion, cp, localidad, provincia
                FROM sucursales
                WHERE nombre        = ? AND
                        cuit          = ? AND
                        direccion     = ? AND
                        cp            = ? AND
                        localidad     = ? AND
                        provincia     = ?  ";

        $stmt = $mysqli->prepare($sql);
        if($stmt){
            $stmt->bind_param('sssiii', $nombre, $cuit, $direccion, $cp, $localidad, $provincia);
            if($stmt->execute()){
                $r = $stmt->get_result();
                if($r->num_rows==0){
                    $arr['repetido'] = false;

                    if($arr['repetido']==false){
                        
                        $sql1 = "UPDATE sucursales 
                                    SET nombre    = ?,
                                        cuit      = ?,
                                        direccion = ?,
                                        cp        = ?,
                                        localidad = ?,
                                        provincia = ? 
                                    WHERE id      = ? ";
                        $stmt1 = $mysqli->prepare($sql1);
                        if($stmt1){
                            $stmt1->bind_param('sssiiii', $nombre, $cuit, $direccion, $cp, $localidad, $provincia, $idSuc);
                            if($stmt1->execute()){
                                $arr['success'] = true;
                            }

                            $stmt1->close();
                        }
                    }
                }else{
                    $arr['repetido'] = true; 
                }
            }
        }

        $stmt->close();

        return json_encode($arr);
    }

    public function listadoSucursalesSelect()
    {
        $mysqli = $this->conexion;
        $arr['success'] = false;
        $data = array();
        
        $sql = "SELECT id, nombre, iden_sucursal FROM sucursales";
        $stmt = $mysqli->prepare($sql);
        if($stmt->execute()){
            $r = $stmt->get_result();
        
            foreach($r as $suc){
                $info['id']     = $suc['id'];
                $info['nombre'] = $suc['nombre'];
                $info['iden']   = $suc['iden_sucursal'];
                $data[]         = $info;
            }
            if(isset($data)){
                $arr['success'] = true;
                $arr['data'] = $data;
            }
        }
        $stmt->close();
        return json_encode($arr);
    }
}
<?php

include_once('../config/db.php');

class SistemaController{

    private $conexion;

    public function __construct()
    { 
        $this->conexion = Database::connect();
        date_default_timezone_set("America/Argentina/Buenos_Aires");
    }

    public function loginSistema($post)
    {
        $info = $_POST['info'];
        $user = $info[0];
        $pass = $info[1];
        $idSu = $info[2];

        $arr['existe'] = false;
        $arr['rol'] = false;
        $arr['passCorr'] = '';
        $arr['sucDisp'] = false;
        $arr['activo'] = '';
        

        $mysqli = $this->conexion;

        session_start();

        $sql = "SELECT id, nombre, usuario, correo, pass, rol, estado                                                   
                FROM usuarios
                WHERE usuario = ? OR correo = ? ";

        $stmt = $mysqli->prepare($sql);
        if($stmt){
            $stmt->bind_param('ss', $user, $user);
            $stmt->execute();
            $r = $stmt->get_result();

            if($r->num_rows == 1){

                $arr['existe'] = true;
                $datos = $r->fetch_assoc();

                if(password_verify($pass, $datos['pass'])){

                    $arr['passCorr'] = 'si';

                    //Consultamos si la sucursal se encuentra disponible
                    $sqlSuc = "SELECT id FROM sucursales WHERE id = ?";
                    $stmtSuc = $mysqli->prepare($sqlSuc);
                    if($sqlSuc){
                        $stmtSuc->bind_param('i', $idSu);
                        $stmtSuc->execute();
                        $rSuc = $stmtSuc->get_result();
                    
                        if($rSuc->num_rows > 0){
                            $arr['sucDisp'] = true;
                            
                            $datos['sucursal'] = $idSu;
                            $_SESSION['user'] = $datos;
    
                            if($datos['estado'] == 'Activo'){
    
                                $arr['activo'] = 'si';
    
                                if($datos['rol'] == 'Administrador'){
                                    $arr['rol'] = 'admin';
    
                                }else if($datos['rol'] == 'Usuario'){
                                    $arr['rol'] = 'user';
                                }
                                
                            }else if($datos['estado'] == 'Baja'){
                                $arr['activo'] = 'no';
                            }
                        }else{//La sucursal no se encuentra registrada en el sistema
                            $arr['sucDisp'] = false;
                        }
                    }

                    if($stmtSuc){
                        $stmtSuc->close();
                    }

                }else{//La contraseña es incorrecta
                    $arr['passCorr'] = 'no';
                }
                
            }else{
                $arr['existe'] = false;
            }
        }

        $stmt->close();
        return json_encode($arr);

    }

    public function logoutSistema()
    {
        session_start();
        session_destroy();
        //header('Location: ../index.php');
        
        $arr['success'] = true;

        return json_encode($arr);
    }
}
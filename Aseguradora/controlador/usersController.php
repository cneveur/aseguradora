<?php   

include_once('../config/db.php');
    
class UsersController{

    private $conexion;

    public function __construct()
    { 
        $this->conexion = Database::connect();
        date_default_timezone_set("America/Argentina/Buenos_Aires");
    }

    public function grabarUser($post)
    {
        $formUser = $post['formUser'];
        $arr['success'] = false;
        $error = false;
        $mysqli = $this->conexion;

        $nombreUser = filter_var($formUser[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nombreUser===FALSE || is_null($nombreUser)) {$error = true;}

        $documentoUser = filter_var($formUser[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($documentoUser===FALSE || is_null($documentoUser)){$error = true;}

        $nacionalidadUser = filter_var($formUser[2], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nacionalidadUser===FALSE || is_null($nacionalidadUser)) {$error = true;}

        $provinciaUser = filter_var($formUser[3], FILTER_VALIDATE_INT);
        if($provinciaUser===FALSE || is_null($provinciaUser)) {$error = true;}

        $localidadUser = filter_var($formUser[4], FILTER_VALIDATE_INT);
        if($localidadUser===FALSE || is_null($localidadUser)) {$error = true;}

        $direccionUser = filter_var($formUser[5], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($direccionUser===FALSE || is_null($direccionUser)) {$error = true;}

        $nacUser = filter_var($formUser[6], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nacUser===FALSE || is_null($nacUser)) {$error = true;}

        $telefonoUser = filter_var($formUser[7], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($telefonoUser===FALSE || is_null($telefonoUser)) {$error = true;}

        $correoUser = filter_var($formUser[8], FILTER_VALIDATE_EMAIL);
        if($correoUser===FALSE || is_null($correoUser)) {$error = true;}

        $generoUser = filter_var($formUser[9], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($generoUser===FALSE || is_null($generoUser)) {$error = true;}

        $rolUser = filter_var($formUser[10], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($rolUser===FALSE || is_null($rolUser)) {$error = true;}

        if(!$error){
            
            $numUser = rand(999999999,9999999999);
            $usuario = rand(10000,99999).'-'.$documentoUser;
            $pass = rand(999999999, 9999999999);
            $hashPass = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 10]);

            //Guardamos la contraseña en una variable de session para despues enviarla limpia por correo
            session_start();
            $_SESSION['pass'] = $pass;
 
            if($rolUser=='usuario'){
                $rol = 'Usuario';
            }else if($rolUser=='admin'){
                $rol = 'Administrador';
            }

            $nombreUser  = ucwords($nombreUser);
            $nacionalidadUser  = ucwords($nacionalidadUser);
            $direccionUser  = ucwords($direccionUser);
            $generoUser  = ucwords($generoUser);

            $estado = 'Activo';

            $sql = "INSERT INTO usuarios (nro, nombre, documento, nacionalidad, provincia, localidad, direccion, fecha_nac, telefono, correo, genero, usuario, pass, rol, estado)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            if($stmt){
                $stmt->bind_param('ssssiisssssssss', $numUser, $nombreUser, $documentoUser, $nacionalidadUser, $provinciaUser, $localidadUser, $direccionUser, $nacUser, $telefonoUser, $correoUser, $generoUser, $usuario, $hashPass, $rol, $estado);
                if($stmt->execute()){
                
                    //Obtenemos el id del usuario ingresado
                    $conIdUs = "SELECT max(id) AS id FROM usuarios";
                    $stmt2 = $mysqli->prepare($conIdUs);
                    if($stmt2->execute()){
                        $r2 = $stmt2->get_result();
                        $infoUs = $r2->fetch_assoc();
                        $idUser = $infoUs['id'];
                        
                        $arr['success'] = true;
                        $arr['idUser']  = $idUser;
                        $arr['email']   = $correoUser;
                    }
                }else{
                    $arr['success'] = 'error';
                }
            }
            
        }else{
            $arr['success'] = false;
        }  

        $stmt->close();
        echo json_encode($arr);
    }

    public function bajaUser($post)
    {
        $idUser = $post['idUser'];
        $mysqli = $this->conexion;
        $arr['success'] = false;
        $arr['mismoEst'] = false;

        $sql1 = "SELECT estado FROM usuarios WHERE id = ?";
        $stmt = $mysqli->prepare($sql1);
        if($stmt->bind_param('i', $idUser)){

            $stmt->execute();
            $r = $stmt->get_result();
            $dat = $r->fetch_assoc();

            if($dat['estado'] != 'Baja'){

                $sql = "UPDATE usuarios SET estado = 'Baja' WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                if($stmt->bind_param('i', $idUser)){
                    $stmt->execute();
                    $arr['success'] = true;
                    $arr['mismoEst'] = false;
                }
            }else{
                $arr['mismoEst'] = true;
            }
        }

        $stmt->close();
        echo json_encode($arr);
    }

    public function reactivarUser($post)
    {
        $idUser = $post['idUser'];
        $mysqli = $this->conexion;
        $arr['success'] = false;
        $arr['mismoEst'] = false;

        $sql1 = "SELECT estado FROM usuarios WHERE id = ?";
        $stmt = $mysqli->prepare($sql1);
        if($stmt->bind_param('i', $idUser)){

            $stmt->execute();
            $r = $stmt->get_result();
            $dat = $r->fetch_assoc();

            if($dat['estado'] != 'Activo'){

                $sql = "UPDATE usuarios SET estado = 'Activo' WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                if($stmt->bind_param('i', $idUser)){
                    $stmt->execute();
                    $arr['success'] = true;
                    $arr['mismoEst'] = false;
                }
            }else{
                $arr['mismoEst'] = true;
            }
        }

        $stmt->close();
        echo json_encode($arr);
    }

    public function traerInfoUser($post)
    {
        $idUser = $post['idUser'];
        $mysqli = $this->conexion;
        $arr['success'] = false;

        $sql = "SELECT U.nro, U.nombre, U.usuario, U.rol, (SELECT COUNT(rol) FROM usuarios WHERE rol='Administrador') AS cantAdm,
                       U.estado, U.documento, U.fecha_nac, U.genero, U.correo, U.telefono, U.nacionalidad, U.direccion,
                       proper(LOC.nombre) AS loc, PRO.nombre AS pro
                FROM usuarios U
                INNER JOIN localidad LOC ON LOC.id = U.localidad
                INNER JOIN provincia PRO ON PRO.id = U.provincia
                WHERE U.estado = 'Activo' AND U.id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $idUser);
        if($stmt->execute()){
            $r = $stmt->get_result();
            if($r->num_rows > 0){
                $info = $r->fetch_assoc();
                $arr['nro']     = $info['nro'];
                $arr['nombre']  = $info['nombre'];
                $arr['usuario'] = $info['usuario'];
                $arr['rol']     = $info['rol'];
                $arr['cantAdm'] = $info['cantAdm'];

                $arr['estado']       = $info['estado'];
                $arr['documento']    = $info['documento'];
                $arr['fecha_nac']    = $info['fecha_nac'];
                $arr['genero']       = $info['genero'];
                $arr['correo']       = $info['correo'];
                $arr['telefono']     = $info['telefono'];
                $arr['nacionalidad'] = $info['nacionalidad'];
                $arr['direccion']    = $info['direccion'];
                $arr['localidad']    = $info['loc'];
                $arr['provincia']    = $info['pro'];


                $arr['success'] = true;
            }
        }
        $stmt->close();
        echo json_encode($arr);
    }

    public function modRolUser($post)
    {
        $info = $post['info'];
        $idUser = $info[0];
        $nuevoRol = $info[1];

        $mysqli = $this->conexion;
        $arr['success'] = false;

        $sql = "UPDATE usuarios SET rol = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('si', $nuevoRol, $idUser);
        if($stmt->execute()){
            $arr['success'] = true;
        }
        
        $stmt->close();
        echo json_encode($arr);
    }

    public function getMisDatosUser()
    {
        session_start();
        if(isset($_SESSION['user'])){
            $idUser = $_SESSION['user']['id'];
        }

        $mysqli = $this->conexion;
        $arr['success'] = false;

        $sql = "SELECT nro, nombre, documento, nacionalidad, provincia, localidad, direccion, fecha_nac, telefono, correo, genero, usuario
                FROM usuarios 
                WHERE estado = 'Activo' AND id = ?";


        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $idUser);
        if($stmt->execute()){
            $r = $stmt->get_result();
            if($r->num_rows > 0){

                $info = $r->fetch_assoc();

                $arr['nro']          = $info['nro'];
                $arr['nombre']       = $info['nombre'];
                $arr['documento']    = $info['documento'];
                $arr['nacionalidad'] = $info['nacionalidad'];
                $arr['provincia']    = $info['provincia'];
                $arr['localidad']    = $info['localidad'];
                $arr['direccion']    = $info['direccion'];
                $arr['fecha_nac']    = $info['fecha_nac'];
                $arr['telefono']     = $info['telefono'];
                $arr['correo']       = $info['correo'];
                $arr['genero']       = $info['genero'];
                $arr['usuario']      = $info['usuario'];
        
                $arr['success'] = true;
            }
        }
        $stmt->close();
        echo json_encode($arr);
    }

    public function setMisDatosUser($post)
    {
        $info = $post['info'];
        $arr['segPass'] = null;
        $arr['diferenteInfo'] = null;
        $arr['userDisp'] = null;
        $arr['update'] = null;
        $error = false;
        $mysqli = $this->conexion;

        session_start();
        if(isset($_SESSION['user'])){
            $idUser = $_SESSION['user']['id'];
        }

        $nombreUser = filter_var($info[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nombreUser===FALSE || is_null($nombreUser)) {$error = true;}

        $documentoUser = filter_var($info[2], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($documentoUser===FALSE || is_null($documentoUser)){$error = true;}

        $nacionalidadUser = filter_var($info[3], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nacionalidadUser===FALSE || is_null($nacionalidadUser)) {$error = true;}

        $provinciaUser = filter_var($info[4], FILTER_VALIDATE_INT);
        if($provinciaUser===FALSE || is_null($provinciaUser)) {$error = true;}

        $localidadUser = filter_var($info[5], FILTER_VALIDATE_INT);
        if($localidadUser===FALSE || is_null($localidadUser)) {$error = true;}

        $direccionUser = filter_var($info[6], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($direccionUser===FALSE || is_null($direccionUser)) {$error = true;}

        $nacUser = filter_var($info[7], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nacUser===FALSE || is_null($nacUser)) {$error = true;}

        $telefonoUser = filter_var($info[8], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($telefonoUser===FALSE || is_null($telefonoUser)) {$error = true;}

        $correoUser = filter_var($info[9], FILTER_VALIDATE_EMAIL);
        if($correoUser===FALSE || is_null($correoUser)) {$error = true;}

        $generoUser = filter_var($info[10], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($generoUser===FALSE || is_null($generoUser)) {$error = true;}

        $usuarioUser = filter_var($info[11], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($usuarioUser===FALSE || is_null($usuarioUser)) {$error = true;}

        $pass1 = filter_var($info[12], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($pass1===FALSE || is_null($pass1)) {$error = true;}

        $pass2 = filter_var($info[13], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($pass2===FALSE || is_null($pass2)) {$error = true;}

        if(!$error){

            $seguridadPass = $info[14];

            $nombreUser  = ucwords($nombreUser);
            $nacionalidadUser  = ucwords($nacionalidadUser);
            $direccionUser  = ucwords($direccionUser);
            $generoUser  = ucwords($generoUser);

            //obtenemos la contraseña actual
            $stmt1 = $mysqli->prepare("SELECT pass FROM usuarios WHERE id = $idUser");
            $stmt1->execute();
            $r1 = $stmt1->get_result();
            $data1 = $r1->fetch_assoc();
            if(password_verify($seguridadPass, $data1['pass'])){
                $arr['segPass'] = true;

                //Comprobamos que no se ingresen los mismos datos
                $sql2 = "SELECT nombre, documento, nacionalidad, provincia, localidad, direccion, fecha_nac, telefono, correo, genero, usuario
                        FROM usuarios
                        WHERE id = $idUser";
                $stmt2 = $mysqli->prepare($sql2);
                $stmt2->execute();
                $r2 = $stmt2->get_result();
                $data2 = $r2->fetch_assoc();
                //Si toda la info modificada es igual a la registrada, no se realizara la modificacion
                if(    $data2['nombre']       != $nombreUser
                    || $data2['documento']    != $documentoUser
                    || $data2['nacionalidad'] != $nacionalidadUser
                    || $data2['provincia']    != $provinciaUser
                    || $data2['localidad']    != $localidadUser
                    || $data2['direccion']    != $direccionUser
                    || $data2['fecha_nac']    != $nacUser
                    || $data2['telefono']     != $telefonoUser
                    || $data2['correo']       != $correoUser
                    || $data2['genero']       != $generoUser
                    || $data2['usuario']      != $usuarioUser
                    || $pass1                 != '' 
                    && $pass2                 != ''              )
                    {
                        $arr['diferenteInfo'] = true;

                        //Verificamos que el nuevo nombre de usuario sea unico
                        $stmt3 = $mysqli->prepare("SELECT usuario FROM usuarios WHERE id <> $idUser");
                        $stmt3->execute();
                        $contExisteUser = 0;
                        $contNoExisteUser = 0;
                        foreach ($stmt3->get_result() as $usuario){

                            $userRegistrado = $usuario['usuario'];

                            if($userRegistrado == $usuarioUser){
                                $contExisteUser++;
                            }else{
                                $contNoExisteUser++;
                            }
                        }

                        //Si el usuario no se encuentra ocupado, seguimos
                        if($contExisteUser == 0){

                            $arr['userDisp'] = true;

                            //Si la opcion de modificar contraseña fue chequeada (= si), pasamos los valores de pass1 y pass2 al bind_param
                            if($info[0]=='si'){
                                $sql4 = "UPDATE usuarios 
                                        SET nombre       = ?,
                                            documento    = ?,
                                            nacionalidad = ?,
                                            provincia    = ?,
                                            localidad    = ?, 
                                            direccion    = ?, 
                                            fecha_nac    = ?, 
                                            telefono     = ?, 
                                            correo       = ?, 
                                            genero       = ?, 
                                            usuario      = ?, 
                                            pass         = ?
                                        WHERE id         = ?";

                                $hashPass = password_hash($pass1, PASSWORD_DEFAULT, ['cost' => 10]);  //encriptamos la nueva contraseña   
                                $stmt4 = $mysqli->prepare($sql4);
                                $stmt4->bind_param('sssiisssssssi', $nombreUser, $documentoUser,$nacionalidadUser, $provinciaUser, $localidadUser, $direccionUser, $nacUser, $telefonoUser, $correoUser, $generoUser, $usuarioUser, $hashPass, $idUser);

                            }else if($info[0]=='no'){

                                $sql4 = "UPDATE usuarios 
                                        SET nombre       = ?,
                                            documento    = ?,
                                            nacionalidad = ?,
                                            provincia    = ?,
                                            localidad    = ?, 
                                            direccion    = ?, 
                                            fecha_nac    = ?, 
                                            telefono     = ?, 
                                            correo       = ?, 
                                            genero       = ?, 
                                            usuario      = ?
                                        WHERE id         = ?";
                                $stmt4 = $mysqli->prepare($sql4);
                                $stmt4->bind_param('sssiissssssi', $nombreUser, $documentoUser,$nacionalidadUser, $provinciaUser, $localidadUser, $direccionUser, $nacUser, $telefonoUser, $correoUser, $generoUser, $usuarioUser, $idUser);
                            }

                            if($stmt4->execute()){
                                $arr['update'] = true;
                                $info = [$nombreUser, null, null, null, null, null, null, null, null, null, null, null];
                                $this->actualizarSesionUser($info);
                            }else{
                                $arr['update'] = false;
                            }
                            $stmt4->close();

                        }else{
                            $arr['userDisp'] = false;
                        }

                        $stmt3->close();

                    }else{
                        $arr['diferenteInfo'] = false;
                    }

                    $stmt2->close();

            }else{
                $arr['segPass'] = false; 
            }

            $stmt1->close();

        }else{
            $arr['update'] = false;
        }

        echo json_encode($arr);
    }

    public function getFotoPerfilUser()
    {
        if(!isset($_SESSION)){
            session_start();
            if(isset($_SESSION['user']['id'])){
                $idUser = $_SESSION['user']['id'];
            }
        }

        $arr['existeImgPerfil'] = false;
        
        $mysqli = $this->conexion;
        $sql = "SELECT id, ruta FROM imgperfilusers WHERE idUser = ? ORDER BY id DESC";
        $stmt = $mysqli->prepare($sql);
        if($stmt->bind_param('i', $idUser)){
            $stmt->execute();
            $r = $stmt->get_result();
            $data = $r->fetch_assoc();
            
            if($r->num_rows==0){
                $arr['existeImgPerfil'] = 'no';
            }else{
                $arr['existeImgPerfil'] = 'si';
                $arr['ruta'] = $data['ruta'];
            }

            $stmt->close();
        }
        echo json_encode($arr);
    }

    public function actualizarSesionUser($info)
    {
        //nombre, documento, nacionalidad, provincia, localidad, direccion, fecha_nac, telefono, correo, genero, usuario, pass, rol, estado

       // $info[0] = //nombre      
       // $info[1] = //documento   
       // $info[2] = //nacionalidad
       // $info[3] = //provincia   
       // $info[4] = //localidad   
       // $info[5] = //direccion   
       // $info[6] = //fecha_nac   
       // $info[7] = //telefono    
       // $info[8] = //correo      
       // $info[9] = //genero      
       // $info[10 = //usuario   
       
       $info = $info;

        if(!isset($_SESSION)){
            session_start();
        }

        if($info[0]!=NULL){
            $_SESSION['user']['nombre'] = $info[0];
        }
        if($info[1]!=NULL){
            $_SESSION['user']['documento'] = $info[1];
        }
        if($info[2]!=NULL){
            $_SESSION['user']['nacionalidad'] = $info[2];
        }
        if($info[3]!=NULL){
            $_SESSION['user']['provincia'] = $info[3];
        }
        if($info[4]!=NULL){
            $_SESSION['user']['localidad'] = $info[4];
        }
        if($info[5]!=NULL){
            $_SESSION['user']['direccion'] = $info[5];
        }
        if($info[6]!=NULL){
            $_SESSION['user']['fecha_nac'] = $info[6];
        }
        if($info[7]!=NULL){
            $_SESSION['user']['telefono'] = $info[7];
        }
        if($info[8]!=NULL){
            $_SESSION['user']['correo'] = $info[8];
        }
        if($info[9]!=NULL){
            $_SESSION['user']['genero'] = $info[9];
        }
        if($info[10]!=NULL){
            $_SESSION['user']['usuario'] = $info[10];
        }
    }

    public function eliminarFotoPerfilUser()
    {
        $arr['eliminarFotoPerfil'] = false;
        
        $mysqli = $this->conexion;

        if(!isset($_SESSION)){
            session_start();
            if(isset($_SESSION['user'])){
                $idUser = $_SESSION['user']['id'];
            }
        }

        //Si ya existe una foto, la eliminamos
        $stmt0 = $mysqli->prepare("SELECT ruta FROM imgperfilusers WHERE idUser = ?");
        $existeImg = $stmt0->bind_param('i', $idUser);

        if($existeImg!=FALSE){
            $stmt0->execute();
            $re = $stmt0->get_result();
            $in = $re->fetch_assoc();
            
            //Si existe un registro, lo borramos de bbdd y el archivo de la carpeta
            if($re->num_rows>=1 && file_exists('../'.$in['ruta']) ){

                $stmt01 = $mysqli->prepare("DELETE FROM imgperfilusers WHERE idUser = $idUser");
                if($stmt01!=FALSE){
                    $eliminarRegistro = $stmt01->execute();
                    $eliminarRuta = unlink('../'.$in['ruta']);

                    if($eliminarRegistro!=FALSE && $eliminarRuta!=FALSE){
                        $arr['eliminarFotoPerfil'] = true;
                    }

                    if(isset($stmt01)){
                        $stmt01->close();
                    }
                }
            }
        }
        if(isset($stmt0)){
            $stmt0->close();
        }

        echo json_encode($arr);
    }

    public function reenviarInfoAccesoUser($idUser)
    {
        $idUser = $idUser['idUser'];
        $arr['modPassUser'] = false;
        $mysqli = $this->conexion;


        //Obtenemos el nombre de usuario
        $sql = "SELECT usuario FROM usuarios WHERE id = ?";
        $stmt = $mysqli->prepare($sql);

        if($stmt!=false){
            $stmt->bind_param('i', $idUser);
            if($stmt->execute()!=false){
                $r = $stmt->get_result();
                foreach($r as $info){
                    $nomUser = $info['usuario'];
                }
            }
        }

        //Generamos una nueva contraseña y reemplazamos la original en la bbdd
        $pass = rand(999999999, 9999999999);
        $hashPass = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 10]);

        //Guardamos la contraseña en una variable de session para despues enviarla limpia por correo
        session_start();
        $_SESSION['pass'] = $pass;

        $sql2 = "UPDATE usuarios SET pass = ? WHERE id = ?";
        $stmt2 = $mysqli->prepare($sql2);
        if($stmt2!=false){
            $stmt2->bind_param('si', $hashPass, $idUser);
            if($stmt2->execute()!=false){
                $arr['modPassUser'] = true;
                $arr['nombreUser'] = $nomUser;
            }
        }

        if($stmt){
            $stmt->close();
        }
        if($stmt2){
            $stmt2->close();
        }

        return json_encode($arr);
    }
}
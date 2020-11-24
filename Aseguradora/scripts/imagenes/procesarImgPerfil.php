<?php
    include_once('../../config/db.php');
    $conexion = Database::connect();

    $respuesta = 50;

    if(!isset($_SESSION)){
        session_start();
        if(isset($_SESSION['user'])){
            $idUser = $_SESSION['user']['id'];
        }
    }

    if(!empty($_FILES['imagen'])){

        $nombreImagen = $_FILES['imagen']['name'];
        $nomImgTemp = $_FILES['imagen']['tmp_name'];
        $tamañoImagen = $_FILES['imagen']['size'];

        $exp = explode(".", $nombreImagen);
        $ext = end($exp);
        $extPermitida = array('jpg', 'jpeg', 'png');

        //Si ya existe una foto, la eliminamos
        $stmt0 = $conexion->prepare("SELECT ruta FROM imgperfilusers WHERE idUser = ?");
        $existeImg = $stmt0->bind_param('i', $idUser);

        if($existeImg!==FALSE){
            $stmt0->execute();
            $re = $stmt0->get_result();
            $in = $re->fetch_assoc();
            
            //Si existe un registro, lo borramos de bbdd y el archivo de la carpeta
            if($re->num_rows>=1 && file_exists('../../'.$in['ruta']) ){

                $stmt01 = $conexion->prepare("DELETE FROM imgperfilusers WHERE idUser = $idUser");
                if($stmt01!==FALSE){
                    $stmt01->execute();
                    unlink('../../'.$in['ruta']);
                    $stmt01->close();
                }
            }

            //grabamos la nueva imagen
            if(in_array($ext, $extPermitida)){
                $imagen = time().'.'.$ext;
                $ubicacion = "../../img/imgPerfilUser/".$imagen;

                if($tamañoImagen < 5242880){
                    $subida = move_uploaded_file($nomImgTemp, $ubicacion);

                    if($subida!==FALSE){
                        $sql = "INSERT INTO imgperfilusers (nombre, idUser, ruta) VALUES (?,?,?)";
                        $ubicacion2 = "img/imgPerfilUser/".$imagen;
                        $stmt = $conexion->prepare($sql);

                        if ($stmt!==FALSE) {
                            $stmt->bind_param('sis', $imagen, $idUser, $ubicacion2);
                            $stmt->execute();
                            $respuesta = true;
                            $stmt->close();

                        }else{
                            $respuesta = 60;
                        }
                    }else{
                        $respuesta = 40;
                    }
                }else{
                    $respuesta =  30;
                }
            }else{
                $respuesta =  20;
            }

            $stmt0->execute();
        }
    }else{
    $respuesta = 10;
    }

    echo $respuesta;
?>
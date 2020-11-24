<?php 

	include_once('../../config/db.php');
	$conexion = Database::connect();

	$respuesta = 50;

    if(!empty($_FILES['imagen'])){

    	$idSin = $_POST['idSin'];
    
        $nombreImagen = $_FILES['imagen']['name'];
        $nomImgTemp = $_FILES['imagen']['tmp_name'];
        $tamañoImagen = $_FILES['imagen']['size'];
    
        $exp = explode(".", $nombreImagen);
        $ext = end($exp);
        $extPermitida = array('jpg', 'jpeg', 'png');
            
        if(in_array($ext, $extPermitida)){
            $imagen = time().'.'.$ext;
            $ubicacion = "../../img/ImgSin/".$imagen;
            if($tamañoImagen < 5242880){
            	//ERROR SUBE IMAGENES HASTA 2045KB
                $subida = move_uploaded_file($nomImgTemp, $ubicacion);

                if($subida){
	                $sql = "INSERT INTO img_siniestro (nombre, idSiniestro, ubicacion) VALUES(?,?,?)";
	                $ubicacion2 = "img/ImgSin/".$imagen;
	                $stmt = $conexion->prepare($sql);
	                if ($stmt!==FALSE) {
	                    $stmt->bind_param('sis', $imagen, $idSin, $ubicacion2);
	                    $stmt->execute();
	                    $stmt->close();

	                   $respuesta = true;
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
    }else{
       $respuesta = 10;
    }


    echo $respuesta;
?>
<?php 

    //include_once('../config/db.php');

    class SiniestrosController
    {
        private $conexion;

        public function __construct()
        {
            $this->conexion = Database::connect();
            date_default_timezone_set("America/Argentina/Buenos_Aires");
        }

        public function traerDatosDenunciante($post)
        {
        
            $idpoliza = $post['data'];

            $arr = array('success'=>false);

            $query = "SELECT T.nombre, T.documento, T.telefono, T.correo,
                    CONCAT(T.calle, ', ', T.localidad, ', ', T.provincia) AS domicilio
                    FROM poliza P
                    INNER JOIN tomador T ON T.id = P.clienteid
                    WHERE P.id = '$idpoliza'";

            $resultado = $this->conexion->query($query);

            while ($dato = mysqli_fetch_row($resultado)) {
                $arr['nombre']    = $dato[0];
                $arr['documento'] = $dato[1];
                $arr['telefono']  = $dato[2];
                $arr['correo']    = $dato[3];
                $arr['domicilio'] = $dato[4];
                $arr['success']   = true;
            }
            
            echo json_encode(array('data' => $arr));
        }

        public function grabarSiniestro($post)
        {
            try{

                $mysqli = $this->conexion;
                $arr = array('success'=>false);
                $datosPoliza = $post['data'];
                $error = false;

                $nroSin = filter_var($datosPoliza[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($nroSin===FALSE || is_null($nroSin)) {$error = true;}

                $fechOcu = filter_var($datosPoliza[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($fechOcu===FALSE || is_null($fechOcu)) {$error = true;}

                $hOcu = filter_var($datosPoliza[2], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($hOcu===FALSE || is_null($hOcu)) {$error = true;}

                $IdPoliza = filter_var($datosPoliza[3], FILTER_VALIDATE_INT);
                if($IdPoliza===FALSE || is_null($IdPoliza)) {$error = true;}

                $lesMu = filter_var($datosPoliza[4], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($lesMu===FALSE || is_null($lesMu)) {$error = true;}

                $tipSin = filter_var($datosPoliza[5], FILTER_VALIDATE_INT);
                if($tipSin===FALSE || is_null($tipSin)) {$error = true;}

                $nomDen = filter_var($datosPoliza[6], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($nomDen===FALSE || is_null($nomDen)) {$error = true;}

                $telDen = filter_var($datosPoliza[7], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($telDen===FALSE || is_null($telDen)) {$error = true;}

                $dniDen = filter_var($datosPoliza[8], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($dniDen===FALSE || is_null($dniDen)) {$error = true;}

                $emailDen = filter_var($datosPoliza[9], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($emailDen===FALSE) {$error = true;}

                $domicDen = filter_var($datosPoliza[10], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($domicDen===FALSE || is_null($domicDen)) {$error = true;}

                $descAcc = filter_var($datosPoliza[11], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($descAcc===FALSE || is_null($descAcc)) {$error = true;}

                $callAcc = filter_var($datosPoliza[12], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($callAcc===FALSE){$error = true;}

                $altAcc = filter_var($datosPoliza[13], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($altAcc===FALSE){$error = true;}

                $locAcc = filter_var($datosPoliza[14], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($locAcc===FALSE || is_null($locAcc)) {$error = true;}

                $proAcc = filter_var($datosPoliza[15], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($proAcc===FALSE || is_null($proAcc)) {$error = true;}

                $nomRec = filter_var($datosPoliza[16], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($nomRec===FALSE) {$error = true;}

                $domRec = filter_var($datosPoliza[17], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($domRec===FALSE) {$error = true;}

                $telRec = filter_var($datosPoliza[18], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($telRec===FALSE) {$error = true;}

                $dniRec = filter_var($datosPoliza[19], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($dniRec===FALSE) {$error = true;}

                $nomCon = filter_var($datosPoliza[20], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($nomCon===FALSE) {$error = true;}

                $domCon = filter_var($datosPoliza[21], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($domCon===FALSE) {$error = true;}

                $telCon = filter_var($datosPoliza[22], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($telCon===FALSE) {$error = true;}

                $dniCon = filter_var($datosPoliza[23], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if($dniCon===FALSE) {$error = true;}

                $tomadDenunciante = $datosPoliza[24];
                $condReclamante = $datosPoliza[25];
                $tomadReclamante = $datosPoliza[26];

                 //Si el tomador es el denunciante, se asigna el valor NULL, si no es, guardamos los valores NO NULOS ingresados.
                if($tomadDenunciante=='NO'){
                    if( is_null($nomDenNuev) ||
                        is_null($dniDenNuev) ||
                        is_null($domDenNuev) ||
                        is_null($telDenNuev)
                    ){
                        $error = true;
                    }else{
                        $nomDenNuev = $nomDenNuev;
                        $dniDenNuev = $dniDenNuev;
                        $domDenNuev = $domDenNuev;
                        $telDenNuev = $telDenNuev;
                        $emailDenNuev = $emailDenNuev;
                    }
                }else if($tomadDenunciante=='SI'){
                    $nomDenNuev = '';
                    $dniDenNuev = '';
                    $domDenNuev = '';
                    $telDenNuev = '';
                    $emailDenNuev = '';
                }

                /*Si el siniestro fue un choque y se involucra una tercera persona, asignamos los daatos NO NULOS ingresados,
                de lo contrario se asigna por defecto NULL*/
                if($tipSin==5){
                    if(is_null($nomRec) || 
                    is_null($domRec) ||
                    is_null($telRec) ||
                    is_null($dniRec)
                    ){
                        $error = true;
                    }else{
                        $nomRec = $nomRec;
                        $domRec = $domRec;
                        $telRec = $telRec;
                        $dniRec = $dniRec;
                    }
                }else{
                    $nomRec = '';
                    $domRec = '';
                    $telRec = '';
                    $dniRec = '';
                    $nomCon = '';
                    $domCon = '';
                    $telCon = '';
                    $dniCon = '';
                    $condReclamante = null;
                }

                /*Si el conductor es la misma persona que la tercera persona (reclamante), se asigna NULL 
                de lo contrario se asignas los valores NO NULOS que ingrese el usuario*/
                if($condReclamante=='NO'){
                    if( is_null($nomCon) ||
                        is_null($domCon) ||
                        is_null($telCon) ||
                        is_null($dniCon)
                    ){
                        $error = true;
                    }else{
                        $nomCon = $nomCon;
                        $domCon = $domCon;
                        $telCon = $telCon;
                        $dniCon = $dniCon;
                    }
                }else if($condReclamante=='SI'){
                    $nomCon = '';
                    $domCon = '';
                    $telCon = '';
                    $dniCon = '';
                }else if($condReclamante==''){
                    $condReclamante = '';
                }

                if(!isset($_SESSION)){
                    session_start();
                }
                if(isset($_SESSION['user'])){
                    $emis = $_SESSION['user']['id']; 
                }


                if(!$error){

                    $fechDen = date('d/m/Y').' - '.date("H:i");

                    $lesMu            = ucwords($lesMu);
                    $nomDen           = ucwords($nomDen);
                    $domicDen         = ucwords($domicDen);
                    $callAcc          = ucwords($callAcc);
                    $proAcc           = ucwords($proAcc);
                    $locAcc           = ucwords($locAcc);
                    $nomRec           = ucwords($nomRec);
                    $domRec           = ucwords($domRec);
                    $nomCon           = ucwords($nomCon);
                    $domCon           = ucwords($domCon);
                    $estado           = '1';
                    $tomadDenunciante = ucwords($tomadDenunciante);
                    $condReclamante   = ucwords($condReclamante);
                    $tomadReclamante  = ucwords($tomadReclamante);

                    $sql  = "INSERT INTO siniestro (nroSiniestro, fechaDen, emisor, fechaOc, horaOc, idPoliza, lesionMuerte, tipoSiniestro, nomDen, telDen, domDen
                    ,dniDen, emailDen, estado, descripcionAcc, calleAcc, alturaAcc, provinciaAcc, localidadAcc, nombreTercero, domTercero, telTercero, docTercero, nombreConductor, telConductor, domConductor, dniConductor, tomadDenunciante, condReclamante, tomadReclamante) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    
                    $stmt = $mysqli->prepare($sql);
                    
                    if($stmt!==FALSE) {
                        $stmt->bind_param('ssissisisssssissssssssssssssss', $nroSin, $fechDen, $emis, $fechOcu, $hOcu, $IdPoliza, $lesMu, $tipSin, $nomDen, $telDen, $domicDen, $dniDen, $emailDen, $estado, $descAcc, $callAcc, $altAcc, $proAcc, $locAcc, $nomRec, $domRec, $telRec, $dniRec, $nomCon, $telCon, $domCon, $dniCon, $tomadDenunciante, $condReclamante, $tomadReclamante);
                        if($stmt->execute()){
                            $arr = array('success'=>true);
                            $stmt->close();
                        }
                    }
                }

                return json_encode($arr);

            }catch(Exception $e)
            {
                return $e->getMessage();
            }
        }
    
        public function modificarEstadoSiniestro($post)
        {
            try{

                $mysqli = $this->conexion;

                $arr = array('success'=>false);

                $datos         = $post['data'];
                $idSiniestro   = $datos[0];
                $idNuevoEstSin = $datos[1];

                $sql  = "UPDATE siniestro
                        SET estado = ?
                        WHERE id = '$idSiniestro'";
                $stmt = $mysqli->prepare($sql);
                if ($stmt!==FALSE) {
                    $stmt->bind_param('i', $idNuevoEstSin);
                    $stmt->execute();
                    $stmt->close();

                    $arr = array('success'=>true);
                }

                return json_encode($arr);

            }catch(Exception $e){
                return $e->getMessage();
            }
        }

        public function traerDatosSiniestro($post)
        {
            $arr['success'] = false;
            $idSin = $post['data'];
            $mysqli = $this->conexion;

            $sql = "SELECT S.idPoliza, P.nro, S.nroSiniestro, S.fechaDen, S.fechaOc, S.horaOc, S.lesionMuerte, S.tipoSiniestro, S.nomDen, S.telDen, S.dniDen,
                    S.domDen, S.emailDen, S.descripcionAcc, S.calleAcc, S.alturaAcc, S.provinciaAcc, 
                    S.localidadAcc, S.nombreTercero, S.domTercero, S.telTercero, S.docTercero, S.nombreConductor, 
                    S.telConductor, S.domConductor, S.dniConductor, S.imagen, S.tomadDenunciante, 
                    S.condReclamante, S.tomadReclamante
                    FROM siniestro S
                    INNER JOIN poliza P ON P.id = S.idPoliza
                    WHERE S.id = ?";

            $stmt = $mysqli->prepare($sql);
            if($stmt!=FALSE){
                $stmt->bind_param('i', $idSin);
                if($stmt->execute()){
                    $r = $stmt->get_result();

                    foreach($r as $dato){

                        $arr['idPoliza']         = $dato['idPoliza'];
                        $arr['nroPol']           = $dato['nro'];
                        $arr['nroSiniestro']     = $dato['nroSiniestro'];
                        $arr['fechaDen']         = $dato['fechaDen'];
                        $arr['fechaOc']          = $dato['fechaOc'];
                        $arr['horaOc']           = $dato['horaOc'];
                        $arr['lesionMuerte']     = $dato['lesionMuerte'];
                        $arr['tipoSiniestro']    = $dato['tipoSiniestro'];
                        $arr['nomDen']           = $dato['nomDen'];
                        $arr['telDen']           = $dato['telDen'];
                        $arr['dniDen']           = $dato['dniDen'];
                        $arr['domDen']           = $dato['domDen'];
                        $arr['emailDen']         = $dato['emailDen'];
                        $arr['descripcionAcc']   = $dato['descripcionAcc'];
                        $arr['calleAcc']         = $dato['calleAcc'];
                        $arr['alturaAcc']        = $dato['alturaAcc'];
                        $arr['provinciaAcc']     = $dato['provinciaAcc'];
                        $arr['localidadAcc']     = $dato['localidadAcc'];
                        $arr['nombreTercero']    = $dato['nombreTercero'];
                        $arr['domTercero']       = $dato['domTercero'];
                        $arr['telTercero']       = $dato['telTercero'];
                        $arr['docTercero']       = $dato['docTercero'];
                        $arr['nombreConductor']  = $dato['nombreConductor'];
                        $arr['telConductor']     = $dato['telConductor'];
                        $arr['domConductor']     = $dato['domConductor'];
                        $arr['dniConductor']     = $dato['dniConductor'];
                        $arr['imagen']           = $dato['imagen'];
                        $arr['tomadDenunciante'] = $dato['tomadDenunciante'];
                        $arr['condReclamante']   = $dato['condReclamante'];
                        $arr['tomadReclamante']  = $dato['tomadReclamante'];
        
                        $arr['success']         = true;
                    }
                }
            }

            if($stmt){
                $stmt->close();
            }

            echo json_encode($arr);
        }

        public function actualizarDatosSin($post)
        {
            $datosActSin = $post['datosSin'];

            $arr = array('success'=>false, 'accion'=>0);

            $error = false;

            $fechaOcNuev = filter_var($datosActSin[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($fechaOcNuev===FALSE || is_null($fechaOcNuev)) {$error = true;}

            $horaOcNuev = filter_var($datosActSin[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($horaOcNuev===FALSE || is_null($horaOcNuev)) {$error = true;}

            $lesionMuerteNuev = filter_var($datosActSin[2], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($lesionMuerteNuev===FALSE || is_null($lesionMuerteNuev)) {$error = true;}

            $tipoSiniestroNuev = filter_var($datosActSin[3], FILTER_VALIDATE_INT);
            if($tipoSiniestroNuev===FALSE || is_null($tipoSiniestroNuev)) {$error = true;}

            $nomDenNuev = filter_var($datosActSin[4], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($nomDenNuev===FALSE) {$error = true;}
            
            $dniDenNuev = filter_var($datosActSin[5], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($dniDenNuev===FALSE) {$error = true;}
            
            $domDenNuev = filter_var($datosActSin[6], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($domDenNuev===FALSE) {$error = true;}
            
            $telDenNuev = filter_var($datosActSin[7], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($telDenNuev===FALSE) {$error = true;}

            $emailDenNuev = filter_var($datosActSin[8], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($emailDenNuev===FALSE) {$error = true;}

            $descripcionAccNuev = filter_var($datosActSin[9], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($descripcionAccNuev===FALSE || is_null($descripcionAccNuev)) {$error = true;}

            $provinciaAccNuev = filter_var($datosActSin[10], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($provinciaAccNuev===FALSE || is_null($provinciaAccNuev)) {$error = true;}
            
            $localidadAccNuev = filter_var($datosActSin[11], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($localidadAccNuev===FALSE || is_null($localidadAccNuev)) {$error = true;}
           
            $calleAccNuev = filter_var($datosActSin[12], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($calleAccNuev===FALSE || is_null($calleAccNuev)) {$error = true;}

            $alturaAccNuev = filter_var($datosActSin[13], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($alturaAccNuev===FALSE || is_null($alturaAccNuev)) {$error = true;}

            $nombreTerceroNuev = filter_var($datosActSin[14], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($nombreTerceroNuev===FALSE) {$error = true;}

            $domTerceroNuev = filter_var($datosActSin[15], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($domTerceroNuev===FALSE) {$error = true;}

            $telTerceroNuev = filter_var($datosActSin[16], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($telTerceroNuev===FALSE) {$error = true;}

            $docTerceroNuev = filter_var($datosActSin[17], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($docTerceroNuev===FALSE) {$error = true;}

            $nombreConductorNuev = filter_var($datosActSin[18], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($nombreConductorNuev===FALSE) {$error = true;}
            
            $domConductorNuev = filter_var($datosActSin[19], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($domConductorNuev===FALSE) {$error = true;}
            
            $telConductorNuev = filter_var($datosActSin[20], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($telConductorNuev===FALSE) {$error = true;}

            $dniConductorNuev = filter_var($datosActSin[21], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($dniConductorNuev===FALSE) {$error = true;}

            $idSiniestro = $datosActSin[22];
            
            $tomadReclamante = filter_var($datosActSin[23], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($tomadReclamante===FALSE || is_null($tomadReclamante)) {$error = true;}
            
            $tomadDenunciante = filter_var($datosActSin[24], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($tomadDenunciante===FALSE || is_null($tomadDenunciante)) {$error = true;}

            $condReclamante = filter_var($datosActSin[25], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($condReclamante===FALSE || is_null($condReclamante)) {$error = true;}


            //Si el tomador es el den, se asigna el valor NULL, si no es, guardamos los valores NO NULOS ingresados.
            if($tomadDenunciante=='NO'){
                if( is_null($nomDenNuev) || is_null($dniDenNuev) || is_null($domDenNuev) || is_null($telDenNuev)){
                    $error = true;
                }else{
                    $nomDenNuev = $nomDenNuev;
                    $dniDenNuev = $dniDenNuev;
                    $domDenNuev = $domDenNuev;
                    $telDenNuev = $telDenNuev;
                    $emailDenNuev = $emailDenNuev;
                }
            }else if($tomadDenunciante=='SI'){
                $nomDenNuev = '';
                $dniDenNuev = '';
                $domDenNuev = '';
                $telDenNuev = '';
                $emailDenNuev = '';
            }

            /*Si el siniestro fue un choque y se involucra una tercera persona, asignamos los daatos NO NULOS ingresados,
             de lo contrario se asigna por defecto NULL*/
            if($tipoSiniestroNuev==5){
                if(is_null($nombreTerceroNuev) || is_null($domTerceroNuev) ||is_null($telTerceroNuev) ||is_null($docTerceroNuev)){
                    $error = true;
                }else{
                    $nombreTerceroNuev = $nombreTerceroNuev;
                    $domTerceroNuev = $domTerceroNuev;
                    $telTerceroNuev = $telTerceroNuev;
                    $docTerceroNuev = $docTerceroNuev;
                }
            }else{
                $nombreTerceroNuev = '';
                $domTerceroNuev = '';
                $telTerceroNuev = '';
                $docTerceroNuev = '';
                $nombreConductorNuev = '';
                $domConductorNuev = '';
                $telConductorNuev = '';
                $dniConductorNuev = '';
                $condReclamante = '';
            }

            /*Si el conductor es la misma persona que la tercera persona (reclamante), se asigna NULL, 
            de lo contrario se asignas los valores NO NULOS que ingrese el usuario*/
            if($condReclamante=='NO'){
                if( is_null($nombreConductorNuev) || is_null($domConductorNuev) || is_null($telConductorNuev) || is_null($dniConductorNuev)){
                    $error = true;
                }else{
                    $nombreConductorNuev = $nombreConductorNuev;
                    $domConductorNuev = $domConductorNuev;
                    $telConductorNuev = $telConductorNuev;
                    $dniConductorNuev = $dniConductorNuev;
                }
            }else if($condReclamante=='SI'){
                $nombreConductorNuev = '';
                $domConductorNuev = '';
                $telConductorNuev = '';
                $dniConductorNuev = '';
            }else if($condReclamante==NULL){
                $condReclamante = '';
            }

            if(!$error){

                $lesionMuerteNuev = ucwords($lesionMuerteNuev);
                $nomDenNuev = ucwords($nomDenNuev);
                $domDenNuev = ucwords($domDenNuev);
                $calleAccNuev = ucwords($calleAccNuev);
                $provinciaAccNuev = ucwords($provinciaAccNuev);
                $localidadAccNuev = ucwords($localidadAccNuev);
                $nombreTerceroNuev = ucwords($nombreTerceroNuev);
                $domTerceroNuev = ucwords($domTerceroNuev);
                $nombreConductorNuev = ucwords($nombreConductorNuev);
                $domConductorNuev = ucwords($domConductorNuev);

                $sql = 
                "SELECT id, fechaOc, horaOc, lesionMuerte, tipoSiniestro, nomDen, telDen, dniDen, domDen, emailDen, descripcionAcc, calleAcc, alturaAcc, provinciaAcc, localidadAcc, nombreTercero, domTercero, telTercero, docTercero, nombreConductor, telConductor, domConductor, dniConductor, tomadDenunciante, condReclamante, tomadReclamante
                FROM siniestro 
                WHERE
                fechaOc          = ? AND
                horaOc           = ? AND
                lesionMuerte     = ? AND
                tipoSiniestro    = ? AND
                nomDen           = ? AND
                telDen           = ? AND
                dniDen           = ? AND
                domDen           = ? AND
                emailDen         = ? AND
                descripcionAcc   = ? AND
                calleAcc         = ? AND
                alturaAcc        = ? AND
                provinciaAcc     = ? AND
                localidadAcc     = ? AND
                nombreTercero    = ? AND
                domTercero       = ? AND
                telTercero       = ? AND
                docTercero       = ? AND
                nombreConductor  = ? AND
                telConductor     = ? AND
                domConductor     = ? AND
                dniConductor     = ? AND
                tomadDenunciante = ? AND
                condReclamante   = ? AND
                tomadReclamante  = ? AND
                id               = ?  ";

                $mysqli = $this->conexion;
        
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('sssisssssssssssssssssssssi', $fechaOcNuev, $horaOcNuev, $lesionMuerteNuev, $tipoSiniestroNuev, $nomDenNuev, $telDenNuev, $dniDenNuev, $domDenNuev, $emailDenNuev, $descripcionAccNuev, $calleAccNuev, $alturaAccNuev, $provinciaAccNuev, $localidadAccNuev, $nombreTerceroNuev, $domTerceroNuev, $telTerceroNuev, $docTerceroNuev, $nombreConductorNuev, $telConductorNuev, $domConductorNuev, $dniConductorNuev, $tomadDenunciante, $condReclamante, $tomadReclamante, $idSiniestro);
                $stmt->execute();
                $rs = $stmt->get_result();

                if($rs->num_rows == 0){
                    $sql = "UPDATE siniestro
                            SET fechaOc          = ?,
                                horaOc           = ?,
                                lesionMuerte     = ?,
                                tipoSiniestro    = ?,
                                nomDen           = ?,
                                telDen           = ?,
                                dniDen           = ?,
                                domDen           = ?,
                                emailDen         = ?,
                                descripcionAcc   = ?,
                                calleAcc         = ?,
                                alturaAcc        = ?,
                                provinciaAcc     = ?,
                                localidadAcc     = ?,
                                nombreTercero    = ?,
                                domTercero       = ?,
                                telTercero       = ?,
                                docTercero       = ?,
                                nombreConductor  = ?,
                                telConductor     = ?,
                                domConductor     = ?,
                                dniConductor     = ?,
                                tomadDenunciante = ?,
                                condReclamante   = ?,
                                tomadReclamante  = ?
                            WHERE id             = ?";

                    $stmt = $mysqli->prepare($sql);
                    if ($stmt!==FALSE) {
                        $stmt->bind_param('sssisssssssssssssssssssssi', $fechaOcNuev, $horaOcNuev, $lesionMuerteNuev, $tipoSiniestroNuev, $nomDenNuev, $telDenNuev, $dniDenNuev, $domDenNuev, $emailDenNuev, $descripcionAccNuev, $calleAccNuev, $alturaAccNuev, $provinciaAccNuev, $localidadAccNuev, $nombreTerceroNuev, $domTerceroNuev, $telTerceroNuev, $docTerceroNuev, $nombreConductorNuev, $telConductorNuev, $domConductorNuev, $dniConductorNuev, $tomadDenunciante, $condReclamante, $tomadReclamante, $idSiniestro);
                        $stmt->execute();
                        $arr = array('success'=>true, 'accion'=>1);
                        $stmt->close();
                    }

                }else{
                    $arr = array('success'=>true, 'accion'=>2);
                    $stmt->close();
                }
                echo json_encode($arr);
            }    
        }

        public function verSiniestro($post)
        {
            $arr = array('success'=>false);

            $idSiniestro = $post['id'];

            $query = 
            "SELECT S.nroSiniestro, S.fechaDen, S.fechaOc, TS.nombre as tipoSiniestro, S.horaOc, S.lesionMuerte, S.tomadDenunciante, S.condReclamante, S.tomadReclamante, ES.nombre as estadoSiniestro,
                    P.nro, CONCAT(T.nombre,' - #',T.num_tom) as tomador, P.vigencia_inicio, P.vigencia_fin, EP.nombre as estadoPoliza,
                    S.nomDen, S.telDen, S.domDen, S.dniDen, S.emailDen,
                    S.descripcionAcc, S.calleAcc, S.alturaAcc, PRO.nombre as provinciaAcc, proper(LOC.nombre) as localidadAcc,
                    S.nombreTercero, S.domTercero, S.telTercero, S.docTercero,
                    S.nombreConductor, S.telConductor, S.domConductor, S.dniConductor
            FROM siniestro S
            INNER JOIN tiposiniestro TS ON TS.id = S.tipoSiniestro
            INNER JOIN estadoSiniestro ES ON ES.id = S.estado
            INNER JOIN poliza P ON P.id = S.idPoliza
            INNER JOIN tomador T ON T.id = P.clienteId
            INNER JOIN estadopoliza EP ON EP.id = P.estado
            INNER JOIN provincia PRO ON PRO.id = S.provinciaAcc
            INNER JOIN localidad LOC ON LOC.id = S.localidadAcc

            WHERE S.id = '$idSiniestro'";

            $resultado = $this->conexion->query($query);

            while ($sin = mysqli_fetch_row($resultado)) {

                $arr['nroSiniestro']     = $sin[0];
                $arr['fechaDen']         = $sin[1];
                $arr['fechaOc']          = $sin[2];
                $arr['tipoSiniestro']    = $sin[3];
                $arr['horaOc']           = $sin[4];
                $arr['lesionMuerte']     = $sin[5];
                $arr['tomadDenunciante'] = $sin[6];
                $arr['condReclamante']   = $sin[7];
                $arr['tomadReclamante']  = $sin[8];
                $arr['estadoSiniestro']  = $sin[9];
                $arr['nroPol']           = $sin[10];
                $arr['tomador']          = $sin[11];
                $arr['vigencia_inicio']  = $sin[12];
                $arr['vigencia_fin']     = $sin[13];
                $arr['estadoPoliza']     = $sin[14];
                $arr['nomDen']           = $sin[15];
                $arr['telDen']           = $sin[16];
                $arr['domDen']           = $sin[17];
                $arr['dniDen']           = $sin[18];
                $arr['emailDen']         = $sin[19];
                $arr['descripcionAcc']   = $sin[20];
                $arr['calleAcc']         = $sin[21];
                $arr['alturaAcc']        = $sin[22];
                $arr['provinciaAcc']     = $sin[23];
                $arr['localidadAcc']     = $sin[24];
                $arr['nombreTercero']    = $sin[25];
                $arr['domTercero']       = $sin[26];
                $arr['telTercero']       = $sin[27];
                $arr['docTercero']       = $sin[28];
                $arr['nombreConductor']  = $sin[29];
                $arr['telConductor']     = $sin[30];
                $arr['domConductor']     = $sin[31];
                $arr['dniConductor']     = $sin[32];

                $arr['success']            = true;
            }

            echo json_encode($arr);
        }

        public function mostrarImgSin($post)
        {
            $idSin = $post['idSin'];
            $imagenes = array();
            $info = array();
            $mysqli = $this->conexion;

            $sql = "SELECT IMG.id, IMG.nombre, IMG.idSiniestro, IMG.ubicacion, 
                        SIN.nroSiniestro nroSin,
                        CONCAT(MAR.nombre,' ', MO.nombre) AS marModVehiculo

                    FROM img_siniestro IMG
                    INNER JOIN siniestro SIN ON SIN.id = IMG.idSiniestro
                    INNER JOIN poliza POL ON POL.id = SIN.idPoliza
                    INNER JOIN vehiculo VE ON VE.id = POL.vehiculoid
                    INNER JOIN marca MAR ON MAR.id = VE.marca_id
                    INNER JOIN modelo MO ON MO.id = VE.modelo_id
                    WHERE idSiniestro = ?";

            $stmt = $mysqli->prepare($sql);

            if($stmt!==FALSE){
                $stmt->bind_param('i', $idSin);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if($resultado->num_rows > 0){

                    while($dato = $resultado->fetch_array()){
                        $arr['id']          = $dato[0];
                        $arr['nombre']      = $dato[1];
                        $arr['idSin']       = $dato[2];
                        $arr['ubicacion']   = $dato[3];
                        $info['nroSin']     = $dato[4];
                        $info['marcaModVe'] = $dato[5];
                    
                        $arr['success']      = true;

                        
                    $arr1 = '<div class="gallery-card">
                                    <a href="'.$dato[3].'" data-lightbox="roadtrip"><img src="'.$dato[3].'" alt="Imagen"></a>
                                    <button type="button" title="Eliminar imagen" class="eliminarImg" value="'.$dato[0].'"> <i class="fas fa-trash-alt"></i> </button>    
                                </div>';

                        $imagenes[] = $arr1;
                    }
                }
            }

            $stmt->close();
            echo json_encode(array('data' => $imagenes, 'info' => $info));
        }

        public function eliminarImgSin($post)
        {
            $idImg = $post['idImg'];
            $arr = array('success'=>false, 'accion'=>0);
            $mysqli = $this->conexion;

            //obtenemos la informacion de la imagen
            $sqlInfo = "SELECT idSiniestro, nombre
                            FROM img_siniestro
                            WHERE id = ?";
            $stmt = $mysqli->prepare($sqlInfo);
            if($stmt!==FALSE){
                $stmt->bind_param('i', $idImg);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if($resultado->num_rows > 0){
                    while($dato = $resultado->fetch_array()){
                        $idSiniestro = $dato[0];
                        $nombre   = $dato[1];
                    }


                    //consultas para eliminar los datos
                    $sqlEliminar = "DELETE FROM img_siniestro WHERE id = ?";
                    $stmt = $mysqli->prepare($sqlEliminar);
                    
                    if($stmt !== FALSE)
                    {
                        $stmt->bind_param('i', $idImg);
                        $ejec = $stmt->execute();
                        $ubicacion = '../img/ImgSin/'.$nombre;
                        unlink($ubicacion); //eliminamos el archivo de la ruta

                        if($ejec){
                            $arr = array('success'=>true, 'idSin'=>$idSiniestro);
                        }
                    }
                }
            }
            $stmt->close();

            echo json_encode($arr);
        }

        public function listadoTipoSiniestro()
        {
            $mysqli = $this->conexion;
            $tiposSin = '';
    
            $sql = "SELECT id, nombre FROM tiposiniestro";
            $stmt = $mysqli->prepare($sql);
            if($stmt!=FALSE){
                $stmt->execute();
                $rs = $stmt->get_result();
                if($rs->num_rows > 0){
                    $tiposSin .= '<option value="0" selected disabled>Seleccione</option>';
                    foreach($rs as $TS){
                        $tiposSin .= '<option value="'.$TS['id'].'">'.$TS['nombre'].'</option>';
                    }   
                }
            }
            $stmt->close();
    
            return $tiposSin;
        }

        public function listadoEstadosSiniestro()
        {
            $mysqli = $this->conexion;
            $estadosSin = '';
    
            $sql = "SELECT id, nombre FROM estadosiniestro";
            $stmt = $mysqli->prepare($sql);
            if($stmt!=FALSE){
                $stmt->execute();
                $rs = $stmt->get_result();
                if($rs->num_rows > 0){
                    $estadosSin .= '<option value="0" selected disabled>Seleccione</option>';
                    foreach($rs as $estado){
                        $estadosSin .= '<option value="'.$estado['id'].'">'.$estado['nombre'].'</option>';
                    }   
                }
            }
            $stmt->close();
    
            return $estadosSin;
        }
    }
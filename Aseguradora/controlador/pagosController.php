<?php   
    
class PagosController{

    private $conexion;

    public function __construct()
    { 
        $this->conexion = Database::connect();
        date_default_timezone_set("America/Argentina/Buenos_Aires");
    }

    public function grabarPago($idPol, $cob, $sumAs, $mesesVig, $datosPago)
    {
        $mysqli = $this->conexion;
        $respuesta = false;

        //Extraemos los datos del pago
        $datosPago = $datosPago;

        $sumAs = str_replace('.', '', $sumAs); //Eliminamos el punto (.)

        // Generamos un numero aleatorio para el pago
        $iden_pago = mt_rand(999999999, 9999999999);

        $idPol = $idPol;

        $cob = $cob;

        $prima = $datosPago[0];
        $prima = number_format($prima, '0', ',', '.'); //No colocamos ningun decimal (cero)
        $prima = str_replace('.', '', $prima); //Eliminamos el punto (.)

        $d = date("d");
        $m = date("m");
        $a = date("Y");

        $fecha_registro =  $d.'/'.$m.'/'.$a;

        $metodoPago = $datosPago[7];

        $estado = '1';

        $sql = "INSERT INTO pagos (num, poliza_id, cobertura_id, prima_total, fecha_registro, metodoPago, estado) VALUES (?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('siisssi', $iden_pago, $idPol, $cob, $prima, $fecha_registro, $metodoPago, $estado);
        $r = $stmt->execute();

        if($r){

            $conIdPago = "SELECT MAX(id) AS id FROM pagos";
            $stmt = $mysqli->prepare($conIdPago);
            $stmt->execute();
            $rs = $stmt->get_result();
            if ($rs->num_rows > 0) {
                
                //obtenemos el id del pago ingresado
                while ($fila = $rs->fetch_array()) {
                    $pagoId = $fila[0];
                }

                $idPago        = $pagoId;
                $primaTotal    = $prima;
                $cantCuotas    = $datosPago[1];
                $primaMensual  = $datosPago[2];
                $diaCobrar     = $datosPago[3];
                $inicioVig     = $datosPago[4];
                $finVig        = $datosPago[5];
                $tiempoAcobrar = $datosPago[6];

                $datosPago = [$pagoId, $primaTotal, $cantCuotas, $primaMensual, $diaCobrar, $inicioVig, $finVig, $tiempoAcobrar]; 

                $cuotas = $this->grabarCuotas($datosPago);

                if($cuotas){
                    $respuesta = true;
                }else{
                    $respuesta = false;
                }
            }

        }else{
            $respuesta = false;
        }

        $stmt->close();

        return $respuesta;
    }

    public function generarFormularioPago($post)
    {
        $mysqli = $this->conexion;

        $idpoliza = $post['idpoliza'];

        $arr = array('success'=>false);

        $sql  = 
        " SELECT
            P.id,
            P.nro,
            P.fecha_emision, 
            P.horaEmis, 
            P.horaInVig, 
            P.vigencia_inicio, 
            P.vigencia_fin, 
            EP.nombre AS estado,
            P.meses_vig,

            V.suma_asegurada, 
            MA.nombre AS marca, 
            M.nombre AS modelo, 
            V.motor,
            V.0km, 
            V.kilometros, 
            V.codigoPostal, proper(L.nombre) AS localidad,
            PR.nombre AS provincia,
            V.combustible, 
            V.eGas, 
            V.anio,

            C.nombre, 
            C.Descripcion,
            CA.nombre AS coberturaAd,

            PA.prima_total,

            PA.id,
            PA.estado

            FROM poliza P
            INNER JOIN vehiculo V ON V.id = P.vehiculoid 
            INNER JOIN marca MA ON MA.id = V.marca_id 
            INNER JOIN modelo M ON M.id = V.modelo_id 
            INNER JOIN localidad L ON V.localidad = L.id
            INNER JOIN provincia PR ON PR.id = L.provincia_id
            INNER JOIN cobertura_adicional CA ON V.coberturaAd = CA.id
            INNER JOIN cobertura C ON C.id = P.coberturaid
            INNER JOIN clase_vehiculo CV ON CV.id = V.clase
            INNER JOIN uso_vehiculo UV ON UV.id = V.uso
            INNER JOIN estadopoliza EP ON EP.id = P.estado
            INNER JOIN pagos PA ON PA.poliza_id = P.id

            WHERE P.id = ? ";

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){
            $stmt->bind_param('i', $idpoliza);
            $stmt->execute();

            $rs = $stmt->get_result();
            if ($rs->num_rows > 0) {
                while ($fila = $rs->fetch_array()) 
                {
                    $arr                      = array();
                    $arr['polizaId']          = $fila[0];
                    $arr['polizaNum']         = $fila[1];
                    $arr['polizaEmis']        = $fila[2];
                    $arr['polizaEmisHora']    = $fila[3];
                    $arr['polizaHorInVig']    = $fila[4];
                    $arr['polizaInVig']       = $fila[5];
                    $arr['polizaFinVig']      = $fila[6];
                    $arr['polizaEstado']      = $fila[7];
                    $arr['polizaMesesVig']    = $fila[8];
 
                    $arr['sumaAsegurada']     = $fila[9];
                    $arr['marca']             = $fila[10];
                    $arr['modelo']            = $fila[11];
                    $arr['motor']             = $fila[12];
                    $arr['ceroKm']            = $fila[13];
                    $arr['kilometros']        = $fila[14];
                    $arr['cpVehiculo']        = $fila[15];
                    $arr['vehiculoLocalidad'] = $fila[16];
                    $arr['vehiculoProvincia'] = $fila[17];
                    $arr['combustible']       = $fila[18];
                    $arr['eGas']              = $fila[19];
                    $arr['anio']              = $fila[20];

                    $arr['cobertura']         = $fila[21];
                    $arr['coberturaDesc']     = $fila[22];
                    $arr['coberturaAd']       = $fila[23];

                    $arr['primaTotal']        = $fila[24];

                    $arr['pagoId']            = $fila[25];
                    $arr['pagoEstado']        = $fila[26];
 
                    $arr['success']           = true;
                }
            }
        }

        $stmt->close();
        echo json_encode(array('data' => $arr));
    }

    public function modificarCuotas($post)
    {

        $datos = $post['datos'];
        $respuesta = false;
        $error = false;
        $mysqli = $this->conexion;

        $idPago = filter_var($datos[0], FILTER_VALIDATE_INT);
        if($idPago===FALSE || is_null($idPago)) {$error = true; }

        $primaTotal = filter_var($datos[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($primaTotal===FALSE || is_null($primaTotal)) {$error = true;}

        if(!$error){

            //Modificamos la informacion del pago
            $sql = "UPDATE pagos
                    SET prima_total = ?,
                        estado      = ? 
                    WHERE id        = ?";

            $estado = 3;

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('sii', $primaTotal, $estado, $idPago);

            if($stmt->execute()){ //Si se ejecuta la consulta, pasamos a eliminar las cuotas

                $elCuota = "DELETE FROM cuotas WHERE pago_id = ?";
                $stmt = $mysqli->prepare($elCuota);
                $stmt->bind_param('i', $idPago);

                if($stmt->execute()){ //Si se ejecuta la consulta, pasamos a grabar las nuevas cuotas   

                    $modCuotas = $this->grabarCuotas($datos);

                    if($modCuotas){
                        $respuesta = true;
                    }
                }
            }
        }

        $stmt->close();
        return $respuesta;
    }

    public function grabarCuotas($datos)
    {
        //Funcion destinada solo a generar y grabar las cuotas en la BBDD

        $respuesta = false;

        $error = false;

        $mysqli = $this->conexion;

        $datos = $datos;

        $idPago = filter_var($datos[0], FILTER_VALIDATE_INT);
        if($idPago===FALSE || is_null($idPago)) {$error = true; }

        $primaTotal = filter_var($datos[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($primaTotal===FALSE || is_null($primaTotal)) {$error = true;}

        $cantCuotas = filter_var($datos[2], FILTER_VALIDATE_INT);
        if($cantCuotas===FALSE || is_null($cantCuotas)) {$error = true;}

        $primaMensual = filter_var($datos[3], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($primaMensual===FALSE || is_null($primaMensual)) {$error = true;}

        $diaCobrar = filter_var($datos[4], FILTER_VALIDATE_INT);
        if($diaCobrar===FALSE || is_null($diaCobrar)) {$error = true;}

        $inicioVig = filter_var($datos[5], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($inicioVig===FALSE || is_null($inicioVig)) {$error = true;}

        $finVig = filter_var($datos[6], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($finVig===FALSE || is_null($finVig)) {$error = true;}

        $tiempoAcobrar = $datos[7];


        //obtenemos el dia, mes y año de inicio de vigencia para poder sumarlos en los lapsos despues
        $diaDeVigencia  = substr($inicioVig, 0, -8);
        $mesDeVigencia  = substr($inicioVig, 3, -5);
        $anioDeVigencia = substr($inicioVig, 6);

        $mesCobro  = substr($inicioVig, 3, -5);
        $anioCobro = substr($inicioVig, 6);


        if($diaCobrar<10){
            $diaCobrar = "0$diaCobrar";
        }

        //si el dia a cobrar es menor al de inicio de vigencia, se suma un mes mas para la facturacion, y se agrega un cero por delante para seguir con la forma de escritura.
        if($diaCobrar<=$diaDeVigencia){
            $mesCobro = $mesCobro + 1;
            if($mesCobro<10){
                $mesCobro = "0$mesCobro";
            }
            
        }

        $insCuotas = "INSERT INTO cuotas (numero_cuota, iden_cuota, pago_id, prima_mensual, lapso, dia_cobrar, vto_pago, estado) VALUES (?,?,?,?,?,?,?,?)";

        for ($i=1; $i<=$cantCuotas ; $i++) { 

            //--------LAPSOS DE COBRO--------

            //Primera fecha del lapso, es la original
            $lapso1 = "$diaDeVigencia/$mesDeVigencia/$anioDeVigencia";

            //Sumamos un mes a la fecha por cada ciclo para hacer los lapsos de tiempo
            $mesDeVigencia = $mesDeVigencia + $tiempoAcobrar;

            //Si colocamos el numero de mes correcto
            if($mesDeVigencia>12){

                if($mesDeVigencia==13){
                $mesDeVigencia = '1';
                }else if($mesDeVigencia==14){
                    $mesDeVigencia = '2';                            
                }else if($mesDeVigencia==15){
                    $mesDeVigencia = '3';
                }else if($mesDeVigencia==16){
                    $mesDeVigencia = '4';
                }else if($mesDeVigencia==17){
                    $mesDeVigencia = '5';
                }else if($mesDeVigencia==18){
                    $mesDeVigencia = '6';
                }else if($mesDeVigencia==19){
                    $mesDeVigencia = '7';
                }else if($mesDeVigencia==20){
                    $mesDeVigencia = '8';
                }else if($mesDeVigencia==21){
                    $mesDeVigencia = '9';
                }else if($mesDeVigencia==22){
                    $mesDeVigencia = '10';
                }else if($mesDeVigencia==23){
                    $mesDeVigencia = '11';
                }
                
                $anioDeVigencia = $anioDeVigencia + 1;
            }

            //Agregamos el cero cuando el mes es menor a 10
            if($mesDeVigencia<10){
                $mesDeVigencia = "0$mesDeVigencia";
            }

            //Segunda fecha del lapso, donde ya se sumo un mes
            $lapso2 = "$diaDeVigencia/$mesDeVigencia/$anioDeVigencia";

            $lapso = "$lapso1 - $lapso2";


            //---------FECHA DE VENCIMIENTO DEL PAGO (1 mes despues de la facturacion)-----------

            $mesVto = ($mesCobro+1);
            $anioVto = $anioCobro;

            if($mesVto>12){

                if($mesVto==13){
                $mesVto = '1';
                }else if($mesVto==14){
                    $mesVto = '2';                            
                }else if($mesVto==15){
                    $mesVto = '3';
                }else if($mesVto==16){
                    $mesVto = '4';
                }else if($mesVto==17){
                    $mesVto = '5';
                }else if($mesVto==18){
                    $mesVto = '6';
                }else if($mesVto==19){
                    $mesVto = '7';
                }else if($mesVto==20){
                    $mesVto = '8';
                }else if($mesVto==21){
                    $mesVto = '9';
                }else if($mesVto==22){
                    $mesVto = '10';
                }else if($mesVto==23){
                    $mesVto = '11';
                }
                
                $anioVto = $anioVto + 1;
            }

            if($mesVto<10){
                $mesVto = "0$mesVto";
            }

            $vto_pago = "$diaCobrar/$mesVto/$anioVto";


            //-------FECHA DE FACTURACION DE CADA LAPSO---------

            //Armamos la fecha separada en partes
            $fechaCobrarCuota = "$diaCobrar/$mesCobro/$anioCobro";

            //Sumamos un mes a la fecha de facturacion por cada lapso
            $mesCobro = $mesCobro + $tiempoAcobrar;

            //Si colocamos el numero de mes correcto
            if($mesCobro>12){

                if($mesCobro==13){
                $mesCobro = '1';
                }else if($mesCobro==14){
                    $mesCobro = '2';                            
                }else if($mesCobro==15){
                    $mesCobro = '3';
                }else if($mesCobro==16){
                    $mesCobro = '4';
                }else if($mesCobro==17){
                    $mesCobro = '5';
                }else if($mesCobro==18){
                    $mesCobro = '6';
                }else if($mesCobro==19){
                    $mesCobro = '7';
                }else if($mesCobro==20){
                    $mesCobro = '8';
                }else if($mesCobro==21){
                    $mesCobro = '9';
                }else if($mesCobro==22){
                    $mesCobro = '10';
                }else if($mesCobro==23){
                    $mesCobro = '11';
                }
                
                $anioCobro = $anioCobro + 1;
            }

            //Agregamos el cero cuando el mes es menor a 10
            if($mesCobro<10){
                $mesCobro = "0$mesCobro";
            }


            //-----RESTO DE INFORMACION-----

            //Generamos el numero de cuota
            $iden_cuota = mt_rand(999999999,9999999999);

            //Guardamos en una variable el dia a cobrar las cuotas o la prima total
            if($cantCuotas==1){
                $fecha = $fechaCobrar;
            }else{
                $fecha = $fechaCobrarCuota;
            }

            $estado = 0;

            //-----INSERSION BBDD-----

            $stmt = $mysqli->prepare($insCuotas);
            $exe = $stmt->bind_param('ssissssi', $i, $iden_cuota, $idPago, $primaMensual, $lapso, $fecha, $vto_pago, $estado);
    
            if($stmt->execute()){
                $respuesta = true;
            }else{
               echo $stmt->error;
            }
        }
        $stmt->close();

        return $respuesta;
    }

    public function mostrarInfoPago($post)
    {
        $arr = array();
        $idPol = $post['idPol'];
        $mysqli = $this->conexion;

        $sql = "SELECT P.id, P.prima_total, C.prima_mensual, C.dia_cobrar
                FROM pagos P
                INNER JOIN cuotas C ON C.pago_id = P.id
                WHERE P.poliza_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $idPol);

        if($stmt->execute()){
            $resultado = $stmt->get_result();
            if($resultado->num_rows > 0){

                $cantCuotas = $resultado->num_rows;

                while($dato = $resultado->fetch_array()){
                    $arr                 = array();
                    $arr['id']           = $dato[0];
                    $arr['primaTotal']   = $dato[1];
                    $arr['primaMensual'] = $dato[2];
                    $arr['diaCobrar']    = $dato[3];

                    $arr['cantCuotas']   = $cantCuotas;
                    $arr['success']      = true;
                }
            }
        }

        $stmt->close();
        echo json_encode($arr);
    }

    public function mostrarCuotas($post)
    {
        $idPago = $post['idPago'];
        $arr    = array();
        $tbody  = array();
        $mysqli = $this->conexion;

        $sql = "SELECT C.numero_cuota, C.iden_cuota, C.prima_mensual, C.lapso, C.dia_cobrar, C.vto_pago, EC.nombre, PO.nro
                FROM pagos P
                INNER JOIN cuotas C ON C.pago_id = P.id
                INNER JOIN poliza PO ON PO.id = P.poliza_id
                INNER JOIN estado_cuota EC ON EC.id = C.estado
                WHERE P.id = ?";

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){

            $stmt->bind_param('i', $idPago);
            $stmt->execute();
            $re = $stmt->get_result();

            if($re->num_rows > 0){
                while ($cuota = $re->fetch_array() ) {
                    $arr['numCuota']  = $cuota[0];
                    $arr['idenCuota'] = $cuota[1];
                    $arr['priMen']    = $cuota[2];
                    $arr['lapso']     = $cuota[3];
                    $arr['diaCobrar'] = $cuota[4];
                    $arr['vtoPago']   = $cuota[5];
                    $arr['estado']    = $cuota[6];
                    $arr['numPol']    = $cuota[7];

                    $arr['success']    = true;

                    $infoTabla = 
                    '<tr>
                        <td>'    . $arr['numCuota']   .' / '.$re->num_rows.'</td>
                        <td>'    . $arr['idenCuota']  .'</td>
                        <td>'.'$'. $arr['priMen']     .'</td>
                        <td>'    . $arr['lapso']      .'</td>
                        <td>'    . $arr['diaCobrar']  .'</td>
                        <td>'    . $arr['vtoPago']    .'</td>
                        <td>'    . $arr['estado']     .'</td>
                    </tr>';

                    $tbody[] = $infoTabla;
                }
            }
        }
        $stmt->close();
        return json_encode(array('data' => $arr, 'tbody' => $tbody));   
    }

    public function facturarPago($post)
    {
        $idPago = $post['pagoId'];
        $arr['success'] = false;
        $mysqli = $this->conexion;

        $sql = "SELECT PO.nro, CONCAT(T.nombre,' (',T.num_tom,')'), P.prima_total
                FROM pagos P
                INNER JOIN poliza PO ON PO.id = P.poliza_id
                INNER JOIN tomador T ON T.id = PO.clienteid
                WHERE P.id = ?";

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){

            $stmt->bind_param('i', $idPago);
            $stmt->execute();
            $re = $stmt->get_result();

            if($re->num_rows > 0){
                while ($cuota = $re->fetch_array() ) {
                    $arr['numPol']    = $cuota[0];
                    $arr['tomador']   = $cuota[1];
                    $arr['primaTot']  = $cuota[2];

                    $arr['success']    = true;

                }
            }
        }
        $stmt->close();
        return json_encode($arr);
    }

    public function traerDatosReciboCuota($post)
    {
        $idCuota = $post['idCuota'];
        $arr['success'] = false;
        $mysqli = $this->conexion;

        session_start();

        if(isset($_SESSION['recibo'])){
            unset($_SESSION["recibo"]);
        }

        $sql = "SELECT CU.iden_cuota, 
                       POL.nro,
                       CONCAT(CLI.nombre,'(',CLI.documento,') / ',CLI.num_tom),
                       CONCAT(CLI.calle,', ',proper(LOC.nombre),' - ',PRO.nombre,' (',LOC.codigopostal,')'),
                       CU.dia_cobrar,
                       CONCAT(POL.vigencia_inicio,' - ',POL.vigencia_fin),
                       CU.vto_pago,
                       CU.prima_mensual,
                       CONCAT(MAR.nombre,' ',MO.nombre,' ',VE.motor),
                       VE.anio,
                       VE.patente,
                       VE.nroMotor,
                       VE.suma_asegurada,
                       COB.descripcion,
                       COBAD.nombre,
                       PA.num,
                       ESCU.nombre,
                       CU.lapso,
                       CU.numero_cuota,
                       (SELECT COUNT(*) FROM cuotas CUOT WHERE CUOT.pago_id = (SELECT pago_id FROM cuotas WHERE cuotas.id = $idCuota)),
                       CU.fecha_cobro
                FROM poliza POL
                INNER JOIN vehiculo VE ON VE.id = POL.vehiculoid
                INNER JOIN tomador CLI ON CLI.id = POL.clienteid
                INNER JOIN provincia PRO ON PRO.id = CLI.provincia
                INNER JOIN localidad LOC ON LOC.id = CLI.localidad AND LOC.codigopostal = CLI.cp
                INNER JOIN pagos PA ON PA.poliza_id = POL.id
                INNER JOIN cuotas CU ON CU.pago_id = PA.id
                INNER JOIN marca MAR ON MAR.id = VE.marca_id
                INNER JOIN modelo MO ON MO.id = VE.modelo_id
                INNER JOIN cobertura COB ON COB.id = POL.coberturaid
                INNER JOIN cobertura_adicional COBAD ON COBAD.id = VE.coberturaAd
                INNER JOIN estado_cuota ESCU ON ESCU.id = CU.estado
                WHERE CU.id = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $idCuota);
        $eje = $stmt->execute();

        if($eje){
            $r = $stmt->get_result();

            if($r->num_rows > 0){
                while($dato = $r->fetch_array()){
                    $arr['nroCu']   = $dato[0];
                    $arr['numPol']  = $dato[1];
                    $arr['tom']     = $dato[2];
                    $arr['dom']     = $dato[3];
                    $arr['fact']    = $dato[4];
                    $arr['vig']     = $dato[5];
                    $arr['vtoPa']   = $dato[6];
                    $arr['priMen']  = $dato[7];
                    $arr['veh']     = $dato[8];
                    $arr['anioVe']  = $dato[9];
                    $arr['pat']     = $dato[10];
                    $arr['nroMot']  = $dato[11];
                    $arr['sumAs']   = $dato[12];
                    $arr['cob']     = $dato[13];
                    $arr['cobAd']   = $dato[14];
                    $arr['nroPa']   = $dato[15];
                    $arr['estCu']   = $dato[16];
                    $arr['lapCu']   = $dato[17];
                    $arr['cuotNro'] = $dato[18];
                    $arr['cantCuo'] = $dato[19];
                    $arr['fechCob'] = $dato[20];
                    $arr['success'] = true;
                }
            }
        }

        $stmt->close();

        $_SESSION['recibo'] = $arr;
        return json_encode($arr);
    }

    public function efectuarPago($post)
    {
        $cuotaId = $post['cuotaId'];
        $arr['success'] = false;
        $arr['permitido'] = false;
        $mysqli=$this->conexion;
        $estado = 2;

        $comprobar = "SELECT estado FROM cuotas WHERE id = ?";
        $stmtC = $mysqli->prepare($comprobar);
        $stmtC->bind_param('i', $cuotaId);
        if($stmtC->execute()){
            $r = $stmtC->get_result();
            $data = $r->fetch_assoc();
            if($data['estado']==0 || $data['estado']==2){
                $arr['permitido'] = false;
            }else{
                $arr['permitido'] = true;
            }
        }
        $stmtC->close();

        if($arr['permitido']){

            $fechaActualCobro = Date("d/m/Y").' - '.Date("H:i"); //Obtenemos la fecha actual

            $sql = "UPDATE cuotas 
                    SET estado      = ?,
                        fecha_cobro = ? 
                    WHERE id        = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('isi', $estado, $fechaActualCobro, $cuotaId);
            
            if($stmt->execute()){
                $arr['success'] = true;
                $arr['fechaCobro'] = $fechaActualCobro;
            }else{
                echo $stmt->error;
            }

            $stmt->close();
        }

        return json_encode($arr);
    }

    public function actualizarEstadoCuota()
    {
        $arr['actCuota'] = false;
        $mysqli = $this->conexion;
        $fechaActual = new DateTime( str_replace("/", "-", Date("d/m/Y")) );

        $sql = "SELECT C.id as cuotaId, C.dia_cobrar as dia_cobrar, C.vto_pago as vto_pago, 
                       P.fecha_registro as fecha_registro, C.estado as estado, P.estado as estadoPago
                FROM cuotas C
                INNER JOIN pagos P ON P.id = C.pago_id";
        $stmt = $mysqli->prepare($sql);
        if($stmt){
            $stmt->execute();
            $r = $stmt->get_result();

            foreach($r as $var){
                $diaCobrar = new DateTime(str_replace("/", "-", $var['dia_cobrar']));
                $vtoPago = new DateTime(str_replace("/", "-", $var['vto_pago']));
                $fechaRegistro = new DateTime(str_replace("/", "-", $var['fecha_registro']));

                //Si el estado de la cuota es diferente de aprobado y rechazado, y el pago es distinto a caducado.
                if($var['estado']!=2 && $var['estado']!=3 && $var['estadoPago']!=2){
                    
                    if($fechaActual>=$fechaRegistro && $fechaActual<$diaCobrar){
                        //A facturar
                        $estadoCuota = 0;
                        $idCuota = $var['cuotaId'];
                        var_dump($idCuota.'= A facturar');

                        $uCuota = $mysqli->prepare("UPDATE cuotas SET estado = $estadoCuota WHERE id = $idCuota");
                    
                    }else if($fechaActual>=$diaCobrar && $fechaActual<$vtoPago){
                        //Facturada (Sin pagar)
                        $estadoCuota = 1;
                        $idCuota = $var['cuotaId'];
                        var_dump($idCuota.'=Facturada sin pagar');

                        $uCuota = $mysqli->prepare("UPDATE cuotas SET estado = $estadoCuota WHERE id = $idCuota");

                    }else if($fechaActual>=$vtoPago){
                        //Deuda
                        $estadoCuota = 4;
                        $idCuota = $var['cuotaId'];
                        var_dump($idCuota.'= Deuda');

                        $uCuota = $mysqli->prepare("UPDATE cuotas SET estado = $estadoCuota WHERE id = $idCuota");

                    }else{
                        $estadoCuota = NULL;
                    }

                    if($uCuota){
                        $uCuota->execute();
                        $uCuota->close();
                        $arr['actCuota'] = true;
                    }else{
                        echo $uCuota->error;
                    }
                }

            }
            $stmt->close();
        }

        return json_encode($arr);
    }
    
    public function comprobarDeuda($post)
    {
        $mysqli = $this->conexion;
        $info = $post['data'];
        $idPoliza = intVal($info[0]);
        
        //$idPoliza = intVal($post['idPoliza']);
        $arr['success'] = false;
        $r = false;
        $fact = 0;
        $apro = 0;
        $rech = 0;
        $deud = 0;
    
        //Comprobamos si la poliza a renovar se encuentra en deuda
        $sql = "SELECT C.iden_cuota as ideCuo, C.estado as estado, EC.nombre as estadoCuota, P.num as nroPago
                FROM cuotas C
                INNER JOIN estado_cuota EC ON EC.id = C.estado
                INNER JOIN pagos P ON P.id = C.pago_id
                INNER JOIN poliza PO ON PO.id = P.poliza_id
                WHERE PO.id = ?";


        $stmt10 = $mysqli->prepare($sql);
        if($stmt10->bind_param('i', $idPoliza)){
            $stmt10->execute();
            $re = $stmt10->get_result();

            foreach($re as $cuota){
                if($cuota['estado'] == 1){
                    $fact = $fact + 1;

                }else if($cuota['estado'] == 2){
                    $apro = $apro + 1;
                    
                }else if($cuota['estado'] == 3){
                    $rech = $rech + 1;
                    
                }else if($cuota['estado'] == 4){
                    $deud = $deud + 1;
                    
                }

                $nroPago = $cuota['nroPago'];
            }

            $arr['fact']       = $fact;
            $arr['apro']       = $apro;
            $arr['rech']       = $rech;
            $arr['deud']       = $deud;
            $arr['cantCuotas'] = $re->num_rows;
            $arr['nroPago']    = $nroPago;
            $arr['success']    = true;
        }

        $stmt10->close();
        return json_encode($arr);
    }

    public function enviarRecibo($post)
    {   
        if(!isset($_SESSION)) 
        { 
            session_start();
        }

        if(isset($_SESSION['urlImg'])){
            unset($_SESSION['urlImg']);
        }

        $cuotaId = $post['cuotaId'];
        $arr['success'] = false;

        $sql = "SELECT T.correo AS correo, T.nombre AS tomador, PA.num AS nroPago, CU.iden_cuota AS nroCuota
                FROM tomador T
                INNER JOIN poliza P ON P.clienteid = T.id
                INNER JOIN pagos PA ON PA.poliza_id = P.id
                INNER JOIN cuotas CU ON CU.pago_id = PA.id AND CU.id = ?";

        $mysqli = $this->conexion;
        $stmt = $mysqli->prepare($sql);
        if($stmt != FALSE){
            $stmt->bind_param('i', $cuotaId);
            $stmt->execute();
            $r = $stmt->get_result();

            if($r->num_rows > 0){
                foreach($r as $var){
                    $correo = $var['correo'];
                    if($correo != '' || $correo != NULL){
                        $arr['existeCorreo'] = 'si';
                    }else{
                        $arr['existeCorreo'] = 'no';
                    }
                    $arr['correo']   = $correo;
                    $arr['tomador']  = $var['tomador'];
                    $arr['nroPago']  = $var['nroPago'];
                    $arr['nroCuota'] = $var['nroCuota'];
                    $arr['success']  = true;

                    $_SESSION['urlImg'] = '../../';
                }
            }
        }
        $stmt->close();
        return json_encode($arr);
    }

    public function mostrarListadoRecibos($post)
    {
        /*Truncamos la sesion de recibo para evitar cargar esa informacion*/
        session_start();
        if(isset($_SESSION['recibo'])){
            unset($_SESSION["recibo"]);
        }
        /*Truncamos la sesion de info para poder modificar la informacion solicitada*/
        if(isset($_SESSION['infoListadoRecibos'])){
            unset($_SESSION["infoListadoRecibos"]);
        }

        $pagoId = $post['pagoId'];
        $mysqli = $this->conexion;
        $r['success'] = false;

        $sql = "SELECT CU.iden_cuota, 
                       POL.nro,
                       CONCAT(CLI.nombre,'(',CLI.documento,') / ',CLI.num_tom),
                       CONCAT(CLI.calle,', ',proper(LOC.nombre),' - ',PRO.nombre,' (',LOC.codigopostal,')'),
                       CU.dia_cobrar,
                       CONCAT(POL.vigencia_inicio,' - ',POL.vigencia_fin),
                       CU.vto_pago,
                       CU.prima_mensual,
                       CONCAT(MAR.nombre,' ',MO.nombre,' ',VE.motor),
                       VE.anio,
                       VE.patente,
                       VE.nroMotor,
                       VE.suma_asegurada,
                       COB.descripcion,
                       COBAD.nombre,
                       CU.lapso,
                       CLI.correo
                FROM poliza POL
                INNER JOIN vehiculo VE ON VE.id = POL.vehiculoid
                INNER JOIN tomador CLI ON CLI.id = POL.clienteid
                INNER JOIN provincia PRO ON PRO.id = CLI.provincia
                INNER JOIN localidad LOC ON LOC.id = CLI.localidad AND LOC.codigopostal = CLI.cp
                INNER JOIN pagos PA ON PA.poliza_id = POL.id
                INNER JOIN cuotas CU ON CU.pago_id = PA.id
                INNER JOIN marca MAR ON MAR.id = VE.marca_id
                INNER JOIN modelo MO ON MO.id = VE.modelo_id
                INNER JOIN cobertura COB ON COB.id = POL.coberturaid
                INNER JOIN cobertura_adicional COBAD ON COBAD.id = VE.coberturaAd

                WHERE PA.id = ?";

        $stmt = $mysqli->prepare($sql);
        
        if($stmt->bind_param('i', $pagoId)){
            $stmt->execute();
            $rs    = $stmt->get_result();
            $filas = $rs->num_rows;
            if ($filas > 0){
                while ($info = $rs->fetch_array()) {
                    $arr              = array();
                    $arr['nroCu']   = $info[0];
                    $arr['numPol']  = $info[1];
                    $arr['tom']     = $info[2];
                    $arr['dom']     = $info[3];
                    $arr['fact']    = $info[4];
                    $arr['vig']     = $info[5];
                    $arr['vtoPa']   = $info[6];
                    $arr['priMen']  = $info[7];
                    $arr['veh']     = $info[8];
                    $arr['anioVe']  = $info[9];
                    $arr['pat']     = $info[10];
                    $arr['nroMot']  = $info[11];
                    $arr['sumAs']   = $info[12];
                    $arr['cob']     = $info[13];
                    $arr['cobAd']   = $info[14];
                    $arr['lapCu']   = $info[15];
                    $arr['email']   = $info[16];
      
                    $r['success'] = true;

                    $arr1[]           = $arr;
                }

                $arr1['filas'] = $filas;
                $arr1['urlImg'] = '../../';

                $_SESSION['infoListadoRecibos'] = $arr1;
            }
        }

        return json_encode(array('resp'=>$r, 'info'=>$arr1));
    }
}
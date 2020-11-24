<?php

//include_once('../config/db.php');

class PolizasController
{
    private $conexion;

    public function __construct()
    { 
        $this->conexion = Database::connect();
        date_default_timezone_set("America/Argentina/Buenos_Aires");
    }

    public function listadoMarcas()
    {
        $marcas = '';
        $mysqli = $this->conexion;

        $sql = "SELECT id, nombre FROM marca";

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){
            $stmt->execute();

            $rs = $stmt->get_result();
            if ($rs->num_rows > 0) {
                $marcas .= '<option value="0" selected disabled>Seleccione</option>';
                foreach($rs as $marca){
                    $marcas .= '<option value="'.$marca['id'].'">'.$marca['nombre'].'</option>';
                }     
            }

            $stmt->close();

            return $marcas;
        }
    }

    public function buscarmodelos($post)
    {
        $idMarca = $post['marca'];
        $modelos = array();
        $mysqli = $this->conexion;

        $sql = "SELECT id, nombre FROM modelo WHERE id_marca = ?";

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){
            $stmt->bind_param('i', $idMarca);
            $stmt->execute();

            $rs = $stmt->get_result();
            if ($rs->num_rows > 0) {
                while ($modelo = $rs->fetch_array()) {
                    $arr           = array();
                    $arr['id']     = $modelo[0];
                    $arr['nombre'] = $modelo[1];
                    $modelos[]     = $arr;
                }
            }

            $stmt->close();

            echo json_encode(array('data' => $modelos));
        }
    }

    public function listadoClases()
    {
        $mysqli = $this->conexion;
        $clases = '';

        $sql = "SELECT id, nombre FROM clase_vehiculo";
        $stmt = $mysqli->prepare($sql);
        if($stmt!=FALSE){
            $stmt->execute();
            $rs = $stmt->get_result();
            if($rs->num_rows > 0){
                $clases .= '<option value="0" selected disabled>Seleccione</option>';
                foreach($rs as $clase){
                    $clases .= '<option value="'.$clase['id'].'">'.$clase['nombre'].'</option>';
                }   
            }
        }
        $stmt->close();

        return $clases;
    }

    public function listadoUsos()
    {
        $mysqli = $this->conexion;
        $usos = '';

        $sql = "SELECT id, nombre FROM uso_vehiculo";
        $stmt = $mysqli->prepare($sql);
        if($stmt!=FALSE){
            $stmt->execute();
            $rs = $stmt->get_result();
            if($rs->num_rows > 0){
                $usos .= '<option value="0" selected disabled>Seleccione</option>';
                foreach($rs as $uso){
                    $usos .= '<option value="'.$uso['id'].'">'.$uso['nombre'].'</option>';
                }   
            }
        }
        $stmt->close();

        return $usos;
    }

    public function listadoCoberturasAd()
    {
        $mysqli = $this->conexion;
        $cobAd = '';

        $sql = "SELECT id, nombre FROM cobertura_adicional";
        $stmt = $mysqli->prepare($sql);
        if($stmt!=FALSE){
            $stmt->execute();
            $rs = $stmt->get_result();
            if($rs->num_rows > 0){
                $cobAd .= '<option value="0" selected disabled>Seleccione</option>';
                foreach($rs as $coA){
                    $cobAd .= '<option value="'.$coA['id'].'">'.$coA['nombre'].'</option>';
                }   
            }
        }
        $stmt->close();

        return $cobAd;
    }

    public function listadoCoberturas()
    {
        $info = array();
        $mysqli = $this->conexion;

        $sql = "SELECT id, nombre, descripcion, informacion FROM cobertura";

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){
            $stmt->execute();
            $rs = $stmt->get_result();

            if ($rs->num_rows>0){
                foreach($rs as $cob){
                    $arr                = array();
                    $arr['id']          = $cob['id'];
                    $arr['nombre']      = $cob['nombre'];
                    $arr['descripcion'] = $cob['descripcion'];
                    $arr['informacion'] = $cob['informacion'];
                    $info[]             = $arr;
                }
            }

            $stmt->close();

            return json_encode($info);
        }
    }

    public function grabarVehiculo($post)
    {

        try{

            $mysqli = $this->conexion;

            $error = false;

            $arr = array('success'=>false);

            $datos = $post['datosVehiculo'];

            $datosPoliza = $post['datosPoliza'];

            $sumAs =  filter_var($datos[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($sumAs===FALSE || is_null($sumAs)) {$error = true;}

            $mar = filter_var($datos[1], FILTER_VALIDATE_INT);
            if($mar===FALSE || is_null($mar)) {$error = true;}

            $mod = filter_var($datos[2], FILTER_VALIDATE_INT);
            if($mod===FALSE || is_null($mod)) {$error = true;}

            $patente = filter_var($datos[3], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($patente===FALSE || is_null($patente)) {$error = true;}

            $motor = filter_var($datos[4], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($motor===FALSE || is_null($motor)) {$error = true;}

            $nroChasis = filter_var($datos[5], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($nroChasis===FALSE || is_null($nroChasis)) {$error = true;}

            $nroMotor = filter_var($datos[6], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($nroMotor===FALSE || is_null($nroMotor)) {$error = true;}

            $clase = filter_var($datos[7], FILTER_VALIDATE_INT);
            if($clase===FALSE || is_null($clase)) {$error = true;}
            
            $uso = filter_var($datos[8], FILTER_VALIDATE_INT);
            if($uso===FALSE || is_null($uso)) {$error = true;}

            $gps = filter_var($datos[9], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($gps===FALSE || is_null($gps)) {$error = true;}

            $ceroKm = filter_var($datos[10], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($ceroKm===FALSE || is_null($ceroKm)) {$error = true;}

            $kms = filter_var($datos[11], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($kms===FALSE || is_null($kms)) {$error = true;}

            $codigoPostal = filter_var($datos[12], FILTER_VALIDATE_INT);
            if($codigoPostal===FALSE || is_null($codigoPostal)) {$error = true;}

            $localidad = filter_var($datos[13], FILTER_VALIDATE_INT);
            if($localidad===FALSE || is_null($localidad)) {$error = true;}

            $coberturaAd = filter_var($datos[14], FILTER_VALIDATE_INT);
            if($coberturaAd===FALSE || is_null($coberturaAd)) {$error = true;}

            $combustible = filter_var($datos[15], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($combustible===FALSE || is_null($combustible)) {$error = true;}

            $eGas = filter_var($datos[16], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($eGas===FALSE || is_null($eGas)) {$error = true;}

            $anio = filter_var($datos[17], FILTER_VALIDATE_INT);
            if($anio===FALSE || is_null($anio)) {$error = true;}

            $pasajeros = filter_var($datos[18], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($pasajeros===FALSE) {$error = true;}

            $asientos = filter_var($datos[19], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($asientos===FALSE) {$error = true;}

            $color = filter_var($datos[20], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($color===FALSE) {$error = true;}

            if(!$error){

                $patente     = strtoupper($patente);
                $motor       = strtoupper($motor); 
                $color       = ucwords($color); 
                $pasajeros   = strtoupper($pasajeros);
                $gps         = ucwords($gps); 
                $combustible = ucwords($combustible); 
                $nroChasis   = strtoupper($nroChasis);
                $nroMotor    = strtoupper($nroMotor);

                $sumAs       = str_replace(',', '', $sumAs);
                $sumAs       = str_replace('.', '', $sumAs);
                $sumAs       = number_format($sumAs, '0', ',', '.');

                $kms         = str_replace(',', '', $kms);
                $kms         = str_replace('.', '', $kms);
                $kms         = number_format($kms, '0', ',', '.');


                $mysqli->set_charset("utf8");
                $sql  = "INSERT INTO vehiculo (suma_asegurada, marca_id, modelo_id, patente, motor, nroChasis, nroMotor, clase, uso, gps, 0km, Kilometros, codigoPostal, localidad, coberturaAd, combustible, eGas, anio, color, asientos, pasajeros) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $stmt = $mysqli->prepare($sql);
                if ($stmt!==FALSE) {
                    $stmt->bind_param('siissssiisssiiississs', $sumAs, $mar, $mod, $patente, $motor, $nroChasis, $nroMotor, $clase, $uso, $gps, $ceroKm, $kms, $codigoPostal, $localidad, $coberturaAd, $combustible, $eGas, $anio, $color, $asientos, $pasajeros);

                    $ex = $stmt->execute();

                    if($ex){
                        $sql = "SELECT MAX(id) AS id FROM vehiculo";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->execute();
                        $rs = $stmt->get_result();
                        if ($rs->num_rows > 0) {
                            while ($fila = $rs->fetch_array()) {
                                $idVehiculo = $fila[0];
                            }

                            $datosPago = $post['datosPago'];
                            $r = $this->grabarPoliza($datosPoliza, $idVehiculo, $sumAs, $datosPago);
                            if($r===true){
                                $arr = array('success'=>true);
                            }
                        }   
                    }

                    $stmt->close();
                }
            }

            return json_encode($arr);

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function mostrarInformacion($post)
    {
        $mysqli = $this->conexion;

        $idpoliza = intval($post['idpoliza']);

        $arr = array('success'=>false);

        $sql  = "SELECT PO.nro, PO.fecha_emision, PO.horaEmis, PO.vigencia_inicio, PO.vigencia_fin, PO.meses_vig, PO.anulacion,
                /*INFO POLIZA*/
                (SELECT COUNT(id) FROM endoso WHERE polizaId = ?)    AS num_endosos, 
                (SELECT COUNT(id) FROM siniestro WHERE idPoliza = ?) AS num_siniestros,
                CONCAT(CO.nombre,' - ',CO.descripcion)               AS cobertura,
                COAD.nombre                                          AS cobAdicionalM,
                USU.nombre AS emisor,
                USU.nro AS nroEmisor,
                SUC.nombre AS nombreSuc,
                SUC.iden_sucursal AS idenSuc,

                /*INFO TOMADOR*/
                CONCAT(TOM.calle,', ',(SELECT proper(nombre) FROM localidad WHERE id = TOM.localidad),' ',(SELECT nombre FROM provincia WHERE id = TOM.provincia)) AS domicilio,
                TOM.num_tom, TOM.nombre, TOM.documento, TOM.persona, TOM.nacionalidad, TOM.cp, TOM.fecha_nac, TOM.genero, TOM.telefono, TOM.correo,

                /*INFO PAGO*/
                PA.num, PA.fecha_registro, PA.prima_total, PA.metodoPago, (SELECT nombre from estado_pago WHERE id = PA.estado) AS estadoPago,
                
                /*INFO VEHICULO*/
                (SELECT nombre FROM marca WHERE id = VE.marca_id)              AS marca, 
                (SELECT nombre FROM modelo WHERE id = VE.modelo_id)            AS modelo,
                (SELECT nombre FROM clase_vehiculo WHERE id = VE.clase)        AS clase, 
                (SELECT nombre FROM uso_vehiculo WHERE id = VE.uso)            AS uso,
                (SELECT proper(nombre) FROM localidad WHERE id = VE.localidad) AS veLocalidad,
                VE.suma_asegurada, VE.motor, VE.patente, VE.nroChasis, VE.nroMotor, VE.gps, VE.0Km, VE.Kilometros, VE.codigoPostal, VE.combustible, VE.eGas, VE.anio, VE.color, VE.asientos, VE.pasajeros
                FROM poliza PO
                INNER JOIN cobertura CO             ON CO.id = PO.coberturaid
                INNER JOIN cobertura_adicional COAD ON COAD.id = (SELECT coberturaAd FROM vehiculo WHERE id = PO.vehiculoid)
                INNER JOIN tomador TOM              ON TOM.id = PO.clienteid
                INNER JOIN pagos PA                 ON PA.poliza_id = PO.id
                INNER JOIN vehiculo VE              ON VE.id = PO.vehiculoid
                INNER JOIN sucursales SUC           ON SUC.id = PO.sucursal
                INNER JOIN usuarios USU             ON USU.id = PO.emisor
                WHERE PO.id = ?";

        $sqlPago = "SELECT numero_cuota, iden_cuota, prima_mensual, (SELECT nombre FROM estado_cuota WHERE id = CU.estado) AS estadoCuota
                    FROM cuotas CU
                    WHERE pago_id = (SELECT id FROM pagos WHERE poliza_id = ?)";

        $sqlEstados = "SELECT CONCAT (EP.nombre,': Desde ',LT.inicio_lapso,' hasta ',LT.fin_lapso) as estadoPoliza
                       FROM ltestpol LT
                       INNER JOIN estadopoliza EP ON EP.id = LT.estado
                       WHERE polizaid = ?
                       ORDER BY nrohilo DESC";

                       
        $stmt1 = $mysqli->prepare($sql);

        $stmt2 = $mysqli->prepare($sqlPago);

        $stmt3 = $mysqli->prepare($sqlEstados);

        if($stmt1&&$stmt2&&$stmt3){

            $stmt1->bind_param('iii', $idpoliza, $idpoliza, $idpoliza);

            if($stmt1->execute()){
                $r1 = $stmt1->get_result();
                foreach($r1 as $v1){
                    $info[] = '';
                    //poliza
                    $info[0] = $v1['nro'];
                    $info[1] = $v1['fecha_emision'];
                    $info[2] = $v1['horaEmis'];
                    $info[3] = $v1['vigencia_inicio'];
                    $info[4] = $v1['vigencia_fin'];
                    $info[5] = $v1['meses_vig'];
                    $info[6] = $v1['anulacion'];
                    $info[7] = $v1['num_endosos'];
                    $info[8] = $v1['num_siniestros'];
                    $info[9] = $v1['cobertura'];
                    $info[10] = $v1['cobAdicionalM'];
                    $info['ne'] = $v1['emisor'];
                    $info['nroe'] = $v1['nroEmisor'];
                    $info['ns'] = $v1['nombreSuc'];
                    $info['is'] = $v1['idenSuc'];
                    //tomador
                    $info[11] = $v1['domicilio'];
                    $info[12] = $v1['num_tom'];
                    $info[13] = $v1['nombre'];
                    $info[14] = $v1['documento'];
                    $info[15] = $v1['persona'];
                    $info[16] = $v1['nacionalidad'];
                    $info[17] = $v1['cp'];
                    $info[18] = $v1['fecha_nac'];
                    $info[19] = $v1['genero'];
                    $info[20] = $v1['telefono'];
                    $info[21] = $v1['correo'];
                    //pago
                    $info[22] = $v1['num'];
                    $info[23] = $v1['fecha_registro'];
                    $info[24] = $v1['prima_total'];
                    $info[25] = $v1['metodoPago'];
                    $info[26] = $v1['estadoPago'];
                    //vehiculo
                    $info[27] = $v1['marca'];
                    $info[28] = $v1['modelo'];
                    $info[29] = $v1['clase'];
                    $info[30] = $v1['uso'];
                    $info[31] = $v1['veLocalidad'];
                    $info[32] = $v1['suma_asegurada'];
                    $info[33] = $v1['motor'];
                    $info[34] = $v1['patente'];
                    $info[35] = $v1['nroChasis'];
                    $info[36] = $v1['nroMotor'];
                    $info[37] = $v1['gps'];
                    $info[38] = $v1['0Km'];
                    $info[39] = $v1['Kilometros'];
                    $info[40] = $v1['codigoPostal'];
                    $info[41] = $v1['combustible'];
                    $info[42] = $v1['eGas'];
                    $info[43] = $v1['anio'];
                    $info[44] = $v1['color'];
                    $info[45] = $v1['asientos'];
                    $info[46] = $v1['pasajeros'];

                    $arr['info'] = $info;

                    $stmt2->bind_param('i', $idpoliza);

                    //Listado de cuotas
                    if($stmt2->execute()){
                        $r2 = $stmt2->get_result();
                        if($r2->num_rows > 0){
                            while($v2 = $r2->fetch_array()){
                                $cuota['numero_cuota']  = $v2[0];
                                $cuota['iden_cuota']    = $v2[1];
                                $cuota['prima_mensual'] = $v2[2];
                                $cuota['estadoCuota']   = $v2[3];

                                $cuotas[] = $cuota;
                            }
                        }

                        $arr['cuotas'] = $cuotas;                        

                        //Listado de Estados de la poliza
                        $stmt3->bind_param('i', $idpoliza);
                        if($stmt3->execute()){
                            $r3 = $stmt3->get_result();
                            if($r3->num_rows > 0){
                                while($v3 = $r3->fetch_array()){
                                    $estado['estado'] = $v3[0];
                                    $estados[] = $estado;
                                }
                            }

                            $arr['estados'] = $estados;
                            $arr['success'] = true;
                        }
                    }
                }
            }
            $stmt1->close();
            $stmt2->close();
            $stmt3->close();
        }
    
        return json_encode(array('info'=>$arr['info'], 'cuotas'=>$arr['cuotas'], 'estados'=>$arr['estados'], 'success'=>$arr['success']));
    }

    public function grabarPoliza($datosPoliza, $idVehiculo, $sumAs, $datosPago)
    {
        try{     

            $mysqli = $this->conexion;

            $error = false;

            $res = false;

            $datosPoliza = $datosPoliza;

            $idVehiculo = $idVehiculo;

            $nroPol = filter_var($datosPoliza[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($nroPol===FALSE || is_null($nroPol)) {$error = true;}

            $vig_ini = filter_var($datosPoliza[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($vig_ini===FALSE || is_null($vig_ini)) {$error = true;}

            $vig_fin = filter_var($datosPoliza[2], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($vig_fin===FALSE || is_null($vig_fin)) {$error = true;}

            $cli = filter_var($datosPoliza[3], FILTER_VALIDATE_INT);
            if($cli===FALSE || is_null($cli)) {$error = true;}

            $cob = filter_var($datosPoliza[4], FILTER_VALIDATE_INT);
            if($cob===FALSE || is_null($cob)) {$error = true;}
         
            $mesesVig = filter_var($datosPoliza[5], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($mesesVig===FALSE || is_null($mesesVig)) {$error = true;}
            
            if(!isset($_SESSION)){
                session_start();
            }
            if(isset($_SESSION['user'])){
                $emis = $_SESSION['user']['id']; 
                $sucursal = $_SESSION['user']['sucursal']; 
            }

            //Reemplazamos los "/" por "-"" en la fecha para poder sumarle un mes.
            $finVig = str_replace("/", "-", $vig_fin);
            $f = new DateTime($finVig);
            $f->modify('next month');
            $anulacion = $f->format('d/m/Y');

            if(!$error){

                $estado = 5;
                $fechaRegistro = Date("d/m/Y");
                $horaRegistro = Date("H:i");
                $horaInVig = '11:59';

                $mysqli->set_charset("utf8");
                $sql  = "INSERT INTO poliza (nro, clienteid, vehiculoid, coberturaid, fecha_emision, horaEmis, emisor, sucursal, vigencia_inicio, horaInVig, vigencia_fin, meses_vig, anulacion, estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $stmt = $mysqli->prepare($sql);
                if ($stmt!==FALSE) {
                    $stmt->bind_param('siiissiisssssi', $nroPol, $cli, $idVehiculo, $cob, $fechaRegistro, $horaRegistro, $emis, $sucursal, $vig_ini, $horaInVig, $vig_fin, $mesesVig, $anulacion, $estado);
                    $ej = $stmt->execute();

                    if($ej){

                        $conIdPol = "SELECT MAX(id) AS id FROM poliza";
                        $stmt = $mysqli->prepare($conIdPol);
                        $stmt->execute();
                        $rs = $stmt->get_result();
                        if ($rs->num_rows > 0) {
                            while ($fila = $rs->fetch_array()) {
                                $polizaId = $fila[0];
                            }
                            
                            include_once('pagosController.php');
                            $pagosController = new PagosController;
                            $pago = $pagosController->grabarPago($polizaId, $cob, $sumAs, $mesesVig, $datosPago);
                            $fechaRegistroLapso = $fechaRegistro.' - '.$horaRegistro;
                            $lineaTiempo = $mysqli->prepare("INSERT INTO ltestpol (polizaid, nrohilo, estado, inicio_lapso, fin_lapso) VALUES ('$polizaId', '1', '$estado', '$fechaRegistroLapso', 'Actualidad')");
                            if($pago){
                                if($lineaTiempo->execute()){
                                    $lineaTiempo->close();
                                    $res=true;
                                }
                            }
                        }
                    }
                    $stmt->close();
                }
            }

            return $res;

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function traerDatosPoliza($id)
    {
        $mysqli = $this->conexion;

        $arr = array('success'=>false);

        $id = $id['id'];

        $mysqli->set_charset("utf8");

        $sql  = "SELECT 
                P.nro, P.fecha_emision, P.horaEmis, P.vigencia_inicio, P.vigencia_fin, P.horaInVig, P.clienteid, P.coberturaid,
                V.suma_asegurada, V.marca_id, V.modelo_id, V.patente, V.motor, V.nroChasis, V.nroMotor,
                V.clase, V.uso, V.gps, V.0km, V.Kilometros, V.codigoPostal, V.localidad, V.coberturaAd, 
                V.combustible, V.eGas, V.anio, V.color, V.asientos, V.pasajeros            
                FROM poliza P
                INNER JOIN vehiculo V ON P.vehiculoid = V.id
                WHERE P.id = ? ";

        $stmt = $mysqli->prepare($sql);

        if ($stmt!==FALSE) {

            $stmt->bind_param('i', $id);
            $stmt->execute();
            $rs = $stmt->get_result();

            if ($rs->num_rows > 0) {
                while ($po = $rs->fetch_array()) {

                    $arr['nro']             = $po[0];
                    $arr['fecha_emision']   = $po[1];
                    $arr['horaEmis']        = $po[2];
                    $arr['vigencia_inicio'] = $po[3];
                    $arr['vigencia_fin']    = $po[4];
                    $arr['horaInVig']       = $po[5];
                    $arr['clienteid']       = $po[6];
                    $arr['coberturaid']     = $po[7];
                    $arr['suma_asegurada']  = $po[8];
                    $arr['marca_id']        = $po[9];
                    $arr['modelo_id']       = $po[10];
                    $arr['patente']         = $po[11];
                    $arr['motor']           = $po[12];
                    $arr['nroChasis']       = $po[13];
                    $arr['nroMotor']        = $po[14];
                    $arr['clase']           = $po[15];
                    $arr['uso']             = $po[16];
                    $arr['gps']             = $po[17];
                    $arr['0km']             = $po[18];
                    $arr['Kilometros']      = $po[19];
                    $arr['codigoPostal']    = $po[20];
                    $arr['localidad']       = $po[21];
                    $arr['coberturaAd']     = $po[22];
                    $arr['combustible']     = $po[23];
                    $arr['eGas']            = $po[24];
                    $arr['anio']            = $po[25];
                    $arr['color']           = $po[26];
                    $arr['asientos']        = $po[27];
                    $arr['pasajeros']       = $po[28];

                    $arr['success']           = true;
                }
            }
        }

        $stmt->close();
        echo json_encode(array('data' => $arr));
    }

    public function modificarDatosPoliza($nuevosDatos)
    {
        $datosActEnd = $nuevosDatos['datos'];

        $arr = array('success'=>false, 'accion'=>0);

        $error = false;

        $fechEmPoliza = filter_var($datosActEnd[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($fechEmPoliza===FALSE || is_null($fechEmPoliza)) {$error = true;}

        $horEmisPol = filter_var($datosActEnd[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($horEmisPol===FALSE || is_null($horEmisPol)) {$error = true;}

        $iniVigPol = filter_var($datosActEnd[2], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($iniVigPol===FALSE || is_null($iniVigPol)) {$error = true;}

        $finVigPol = filter_var($datosActEnd[3], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($finVigPol===FALSE || is_null($finVigPol)) {$error = true;}

        $horaInVig = filter_var($datosActEnd[4], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($horaInVig===FALSE || is_null($horaInVig)) {$error = true;}

        $sumaAs = filter_var($datosActEnd[5], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($sumaAs===FALSE || is_null($sumaAs)) {$error = true;}

        $marca = filter_var($datosActEnd[6], FILTER_VALIDATE_INT);
        if($marca===FALSE || is_null($marca)) {$error = true;}

        $modelo = filter_var($datosActEnd[7], FILTER_VALIDATE_INT);
        if($modelo===FALSE || is_null($modelo)) {$error = true;}

        $patente = filter_var($datosActEnd[8], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($patente===FALSE || is_null($patente)) {$error = true;}

        $motor = filter_var($datosActEnd[9], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($motor===FALSE || is_null($motor)) {$error = true;}

        $nroChasis = filter_var($datosActEnd[10], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nroChasis===FALSE || is_null($nroChasis)) {$error = true;}

        $nroMotor = filter_var($datosActEnd[11], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nroMotor===FALSE || is_null($nroMotor)) {$error = true;}

        $clase = filter_var($datosActEnd[12], FILTER_VALIDATE_INT);
        if($clase===FALSE || is_null($clase)) {$error = true;}

        $uso = filter_var($datosActEnd[13], FILTER_VALIDATE_INT);
        if($uso===FALSE || is_null($uso)) {$error = true;}

        $gps = filter_var($datosActEnd[14], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($gps===FALSE || is_null($gps)) {$error = true;}

        $ceroKm = filter_var($datosActEnd[15], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($ceroKm===FALSE || is_null($ceroKm)) {$error = true;}

        $cantKms = filter_var($datosActEnd[16], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($cantKms===FALSE || is_null($cantKms)) {$error = true;}

        $cp = filter_var($datosActEnd[17], FILTER_VALIDATE_INT);
        if($cp===FALSE || is_null($cp)) {$error = true;}

        $vehiculoLocalidad = filter_var($datosActEnd[18], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($vehiculoLocalidad===FALSE || is_null($vehiculoLocalidad)) {$error = true;}

        $cobAd = filter_var($datosActEnd[19], FILTER_VALIDATE_INT);
        if($cobAd===FALSE || is_null($cobAd)) {$error = true;}

        $combustible = filter_var($datosActEnd[20], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($combustible===FALSE || is_null($combustible)) {$error = true;}

        $eGas = filter_var($datosActEnd[21], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($eGas===FALSE || is_null($eGas)) {$error = true;}

        $anio = filter_var($datosActEnd[22], FILTER_VALIDATE_INT);
        if($anio===FALSE || is_null($anio)) {$error = true;}

        $pasajeros = filter_var($datosActEnd[23], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($pasajeros===FALSE) {$error = true;}

        $asientos = filter_var($datosActEnd[24], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($asientos===FALSE) {$error = true;}

        $color = filter_var($datosActEnd[25], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($color===FALSE) {$error = true;}

        $cliente = filter_var($datosActEnd[26], FILTER_VALIDATE_INT);
        if($cliente===FALSE || is_null($cliente)) {$error = true;}

        $cobertura = filter_var($datosActEnd[27], FILTER_VALIDATE_INT);
        if($cobertura===FALSE || is_null($cobertura)) {$error = true;}

        $idPoliza = $datosActEnd[28];

        if(!$error){

            $patente = strtoupper($patente);
            $nroChasis = strtoupper($nroChasis);
            $nroMotor = strtoupper($nroMotor);
            $motor = strtoupper($motor);
            $color = ucwords($color);

            $sig[]='.';
            $sig[]=',';
            $sumaAs = str_replace($sig,'', $sumaAs);
            $cantKms = str_replace($sig,'', $cantKms);

            $sumaAs = number_format($sumaAs, 0, ',', '.');
            $cantKms = number_format($cantKms, 0, ',', '.');
            
            $sql = 
            "SELECT 
            P.clienteid, P.vehiculoid, P.coberturaid, P.vigencia_inicio, P.vigencia_fin,
            V.suma_asegurada, V.marca_id, V.modelo_id, V.patente, V.motor, V.nroChasis, V.nroMotor, V.clase, V.uso,
            V.gps, V.0km, V.Kilometros, V.codigoPostal, V.localidad, V.coberturaAd, V.combustible, V.eGas, V.anio, 
            V.pasajeros, V.asientos, V.color
            FROM poliza P
            INNER JOIN vehiculo V
            WHERE
            P.clienteid       = ? AND 
            P.coberturaid     = ? AND
            P.vigencia_inicio = ? AND
            P.vigencia_fin    = ? AND
            V.suma_asegurada  = ? AND
            V.marca_id        = ? AND
            V.modelo_id       = ? AND
            V.patente         = ? AND
            V.motor           = ? AND
            V.nroChasis       = ? AND
            V.nroMotor        = ? AND
            V.clase           = ? AND
            V.uso             = ? AND
            V.gps             = ? AND
            V.0km             = ? AND
            V.Kilometros      = ? AND
            V.codigoPostal    = ? AND
            V.localidad       = ? AND
            V.coberturaAd     = ? AND
            V.combustible     = ? AND
            V.eGas            = ? AND
            V.anio            = ? AND
            V.pasajeros       = ? AND
            V.asientos        = ? AND 
            V.color           = ? ";

            $mysqli = $this->conexion;
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('iissiiissssiissiiiissiiis', $cliente, $cobertura, $iniVigPol, $finVigPol, $sumaAs, $marca, $modelo, $patente, $motor, $nroChasis, $nroMotor, $clase, $uso, $gps, $ceroKm, $cantKms, $cp, $vehiculoLocalidad, $cobAd, $combustible, $eGas, $anio, $pasajeros, $asientos, $color);
            $stmt->execute();
            $rs = $stmt->get_result();

            if($rs->num_rows==0){

                $sql = "UPDATE poliza P
                        INNER JOIN vehiculo V ON P.vehiculoid = V.id
                        SET 
                            P.clienteid       = ?,
                            P.coberturaid     = ?,
                            P.vigencia_inicio = ?,
                            P.vigencia_fin    = ?,
                            V.suma_asegurada  = ?,
                            V.marca_id        = ?,
                            V.modelo_id       = ?,
                            V.patente         = ?,
                            V.motor           = ?,
                            V.nroChasis       = ?,
                            V.nroMotor        = ?,
                            V.clase           = ?,
                            V.uso             = ?,
                            V.gps             = ?,
                            V.0km             = ?,
                            V.Kilometros      = ?,
                            V.codigoPostal    = ?,
                            V.localidad       = ?,
                            V.coberturaAd     = ?,
                            V.combustible     = ?,
                            V.eGas            = ?,
                            V.anio            = ?,
                            V.pasajeros       = ?,
                            V.asientos        = ?,
                            V.color           = ?
                        WHERE
                            P.id              = ?  ";
                
                $stmt = $mysqli->prepare($sql);
                if ($stmt!==FALSE) {
                    $stmt->bind_param('iisssiissssiisssiiissssssi', $cliente, $cobertura, $iniVigPol, $finVigPol, $sumaAs, $marca, $modelo, $patente, $motor, $nroChasis, $nroMotor, $clase, $uso, $gps, $ceroKm, $cantKms, $cp, $vehiculoLocalidad, $cobAd, $combustible, $eGas, $anio, $pasajeros, $asientos, $color, $idPoliza);
                    $stmt->execute();
                    $arr = array('success'=>true, 'accion'=>1);
                    $stmt->close();
                }

            }else
            {
                $arr = array('success'=>true, 'accion'=>2);
                $stmt->close();
            }
        }

        echo json_encode($arr);
    }

    public function listarPolizas()
    {
        $mysqli = $this->conexion;
        $arr1 = array();
        $sql = "SELECT P.id as id, P.nro as nro, T.nombre as tomador, T.documento as doc, P.estado as estado
              FROM poliza P
              INNER JOIN tomador T ON T.id = P.clienteid";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        $r = $stmt->get_result();

        if($r->num_rows > 0){
            while($pol = $r->fetch_array()){
                $arr = array();
                $arr['id']      = $pol[0];
                $arr['nro']     = $pol[1];
                $arr['tomador'] = $pol[2];
                $arr['doc']     = $pol[3];
                $arr['estado']  = $pol[4];
                $arr1[]         = $arr;
            }
        }

        $stmt->close();

        echo json_encode(array('data' => $arr1));
    }

    public function renovarPoliza($post)
    {
        $mysqli = $this->conexion;
        $arr['renPoliza'] = false;

        $info = $post['data'];

        $idPoliza     = $info[0];
        $idPoliza     = $idPoliza[0];
        $vig_ini      = $info[1];
        $tipVig       = $info[2];
        $vig_fin      = $info[3];
        $primaTotal   = $info[4];
        $cantCuotas   = $info[5];
        $primaMensual = $info[6];
        $diaCobrar    = $info[7];
        $tipoPag      = $info[8];

        $lapso = strval($tipVig/$cantCuotas);

        $fechaRegistro = Date("d/m/Y");
        $horaRegistro = Date("H:i");
        $fechaRegLT = $fechaRegistro.' - '.$horaRegistro;
    
        $finVig = str_replace("/", "-", $vig_fin);
        $f = new DateTime($finVig);
        $f->modify('next month');
        $anulacion = $f->format('d/m/Y');

        $estadoEsp = 5;
        $estadoRen = 4;
        $estPagoAn = 2;

        // 1) Insertamos en poliza un clon de la poliza
        $insertarClon = "INSERT INTO poliza (nro, clienteid, vehiculoid, coberturaid, fecha_emision, horaEmis, emisor,
                                     vigencia_inicio, horaInVig, vigencia_fin, meses_vig, anulacion, estado)

                         SELECT nro, clienteid, vehiculoid, coberturaid, fecha_emision, horaEmis, emisor,
                                    vigencia_inicio, horaInVig, vigencia_fin, meses_vig, anulacion, estado 
                         FROM poliza 
                         WHERE id = ?";
        $stmt1 = $mysqli->prepare($insertarClon);


        // 2) Modificamos ese clon con la informacion que corresponde (lo buscamos con el maximo id ingresado)
        $modificarClon = "UPDATE poliza 
                          SET fecha_emision   = ?,
                              horaEmis        = ?,
                              vigencia_inicio = ?,
                              vigencia_fin    = ?,
                              meses_vig       = ?,
                              anulacion       = ?,
                              estado          = ? 
                          WHERE id            = ?";
        $stmt2 = $mysqli->prepare($modificarClon);

    
        // 3) Modificamos el estado de la poliza original a 'renovada'
        $modificarEstPoliza = "UPDATE poliza SET estado = ? WHERE id = ?";
        $stmt3 = $mysqli->prepare($modificarEstPoliza);


        // 4) Modificamos el estado del pago original a 'anulado'
        $modificarEstadoPago = "UPDATE pagos SET estado = ? WHERE id = (SELECT DISTINCT id
                                                                        FROM pagos PA
                                                                        WHERE PA.poliza_id = ?)";
        $stmt4 = $mysqli->prepare($modificarEstadoPago);


        //Si todas las consultas existen, pasamos todos los parametros y realizamos las ejecuciones en cadena
        if($stmt1 && $stmt2 && $stmt3 && $stmt4){

            //Clonacion
            $stmt1->bind_param('i', $idPoliza);
            $stmt1->execute();
            $stmt1->close();

            //Obtenemos el id de la poliza renovada
            $stmtId= $mysqli->prepare("SELECT MAX(id) AS id FROM poliza");
            $stmtId->execute();
            $r = $stmtId->get_result();
            foreach($r as $id){
                $idNuevaPoliza = $id['id'];
            }
            $stmtId->close();

            //Modificacion poliza renovada
            $stmt2->bind_param('ssssssii', $fechaRegistro, $horaRegistro, $vig_ini, $vig_fin, $tipVig, $anulacion, $estadoEsp, $idNuevaPoliza);
            $stmt2->execute();
            $stmt2->close();

            //Estado de poliza original a 'renovado'
            $stmt3->bind_param('ii', $estadoRen, $idPoliza);
            $stmt3->execute();
            $stmt3->close();
            //Cuando modificamos el estado de la poliza original a 'renovado', insertamos ese cambio de estado en la linea del tiempo de estados de la poliza
            $this->guardarLineaTiempoEstadoPoliza($idPoliza, $estadoRen);

            //Estado de pago original a 'anulado'
            $stmt4->bind_param('ii', $estPagoAn, $idPoliza);
            $stmt4->execute();
            $stmt4->close();

            //Traemos la informacion de la poliza renovada para generar el pago
            $con = $mysqli->prepare("SELECT P.coberturaid as cob, V.suma_asegurada as sumAs, P.meses_vig as mVig 
                                     FROM poliza P
                                     INNER JOIN vehiculo V ON V.id = P.vehiculoid
                                     WHERE P.id = ?");

            if($con->bind_param('i', $idNuevaPoliza)){
                $con->execute();
                foreach($con->get_result() as $info){
                    $cob      = $info['cob'];
                    $sumAs    = $info['sumAs'];
                    $mesesVig = $info['mVig'];
                }

                $datosPago = [$primaTotal, $cantCuotas, $primaMensual, $diaCobrar, $vig_ini, $vig_fin, $lapso, $tipoPag];
                $con->close();
            }
            //$grabarPago = $this->grabarPago($idNuevaPoliza, $cob, $sumAs, $mesesVig, $datosPago);

            include_once('pagosController.php');
            $pagosController = new PagosController;
            $grabarPago = $pagosController->grabarPago($idNuevaPoliza, $cob, $sumAs, $mesesVig, $datosPago);
    
            if($grabarPago){
                //Insercion de una nueva linea del tiempo de estados
            
                $ins = "INSERT INTO ltestpol (polizaid, nrohilo, estado, inicio_lapso, fin_lapso) VALUES (?,?,?,?,?)";
                $lineaTiempo = $mysqli->prepare($ins);
                $numeroHilo = '1';
                $fecFinLap = 'Actualidad';
                $lineaTiempo->bind_param('isiss', $idNuevaPoliza, $numeroHilo, $estadoEsp, $fechaRegLT, $fecFinLap);
                if($lineaTiempo!=FALSE){
                    $lineaTiempo->execute(); 
                    $lineaTiempo->close();
                }
               
                $arr['renPoliza'] = true;
            } 
        }
        return json_encode($arr);          
    }

    public function actualizarEstadosPoliza()
    {
        //1) corroboramos las fechas en relacion a la fecha actual para asi declarar la poliza en un estado determinado
        //2) si la poliza esta vigente, corroboramos si posee deuda o no, en caso de que alguna cuota se encuentre en deuda, modificamos el estado a vigenteConDeuda
        //En caso de que la poliza se encuentre vencida o anulada, NO SE CORROBORA SI POSEE DEUDA
        $mysqli = $this->conexion;
        $arr['actEstPol'] = false;
        $fecha_actual = new DateTime( str_replace("/", "-", Date("d/m/Y")) );
        $sql = "SELECT id, estado, fecha_emision, vigencia_inicio, vigencia_fin, anulacion FROM poliza";

        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        $r = $stmt->get_result();

        foreach($r as $resp){

            $idPol     = $resp['id'];
            $estado    = $resp['estado'];
            $emision   = $resp['fecha_emision'];
            $inicioVig = $resp['vigencia_inicio'];
            $finVigenc = $resp['vigencia_fin'];
            $anulacion = $resp['anulacion'];

            $emision   = new DateTime( str_replace("/", "-", $resp['fecha_emision']) );
            $inicioVig = new DateTime( str_replace("/", "-", $resp['vigencia_inicio']) );
            $finVigenc = new DateTime( str_replace("/", "-", $resp['vigencia_fin']) );
            $anulacion = new DateTime( str_replace("/", "-", $resp['anulacion']) );
                
            //Se aplica la logica a toda poliza que no este renovada
            if($estado != 4){

                //Si la fecha actual es mayor o igual a la emison y menor al inicio de vigencia = Espera
                if($fecha_actual>=$emision && $fecha_actual<$inicioVig){
                    $estActual = 5;
                //Si la fecha actual es mayor o igual al inicio de vigencia y menor a fin de vigencia = Vigente
                }else if($fecha_actual>=$inicioVig && $fecha_actual<$finVigenc){
                   $estActual = 0;
                //Si la fecha actual es mayor a fin de vigencia y menor a anulacion = Vencida
                }else if($fecha_actual>=$finVigenc && $fecha_actual<$anulacion){
                   $estActual = 1;
                //Si la fecha actual es mayor o igual a la fecha de anulacion = Anulada
                }else if($fecha_actual >= $anulacion){
                    $estActual = 2;
                }
                

                if($estActual != $estado){

                    $stmt = $mysqli->prepare("UPDATE poliza SET estado = $estActual WHERE id = $idPol");
                    if($stmt->execute()){
                        
                        //Insertamos el cambio de estado en la linea del tiempo de estados de esa poliza
                        $lineaTiempo = $this->guardarLineaTiempoEstadoPoliza($idPol, $estActual);

                        //Comprobamos la deuda en todas las polizas vigentes, si existen, sumamos un contador, en caso de que este sea mayor a 0, cambiamos el estado de la poliza a deuda, o viceversa
                        if($estado == 0){
                            $con = "SELECT C.estado as estadoCuota, P.estado as estadoPoliza
                                    FROM cuotas C
                                    INNER JOIN poliza P ON P.id = $idPol
                                    WHERE pago_id = (SELECT id FROM pagos WHERE poliza_id = $idPol)";
    
                            $comp = $mysqli->prepare($con);
    
                            if($comp->execute()){
                                $contDeud = 0;
    
                                foreach($comp->get_result() as $v){
                                    if($v['estadoCuota'] == 4){
                                        $contDeud ++;
                                    }
                                }
    
                                if($contDeud >= 1){ //Si existe una o mas deudas, cambiamos el estado y agregamos la linea del tiempo
                                    $estPol = 3;
                                    $arr['accion'] = 'deuda';
                                    $stmt2 = $mysqli->prepare("UPDATE poliza SET estado = $estPol WHERE id = $idPol");
                                    if($stmt2->execute()){
                                        $this->guardarLineaTiempoEstadoPoliza($idPol, $estPol); //Cuando se cambia el estado a 'deuda', agregamos a la linea del tiempo
                                    }
                                    $stmt2->close();
                                }else{
                                    $arr['accion'] = 'sin deuda';
                                }
                                $arr['actEstPol'] = true;
                            }                   
                        }else{
                            $arr['accion'] = 'sin polizas vig';
                        }
                    }else{
                        echo $stmt->error;
                    }
    
                    $stmt->close();
                }else{
                    $arr['accion'] = 'Mismo estado';
                }
            }
        }

        return json_encode($arr);
    }
    
    public function guardarLineaTiempoEstadoPoliza($polizaId, $nuevoEstado)
    {
        $mysqli = $this->conexion;
        $arr['LtEstPoliza'] = false;
        $fechaActual = Date("d/m/Y - H:i");
        $polizaId = $polizaId;
        $nuevoEstado = $nuevoEstado;

        //Obtenemos el numero de hilo mayor de la linea del tiempo de estados de esa poliza
        $con = "SELECT MAX(nrohilo) as nroHilo FROM ltestpol WHERE polizaid = '$polizaId'";
        $stmtNroHilo = $mysqli->prepare($con);

        if($stmtNroHilo){

            $stmtNroHilo->execute();
            $r = $stmtNroHilo->get_result();
            foreach($r as $var){
                $nroHilo = $var['nroHilo'];
            }
        }
        $stmtNroHilo->close();
        
        //Asignamos una fecha de finalizacion del lapso al ultimo hilo de la linea del tiempo de estados de la poliza
        $stmtAsFech = $mysqli->prepare("UPDATE ltestpol SET fin_lapso = '$fechaActual' WHERE polizaid = '$polizaId' AND nrohilo = '$nroHilo'");
        if($stmtAsFech){
            $stmtAsFech->execute();
        }
        $stmtAsFech->close();

        //Insertamos un nuevo hilo a la linea del tiempo de estados de la poliza (Sumando el numero de hilo)
        $nroHiloNuevo = strval($nroHilo+1);
        $finLapso = "Actualidad";
        $sql = "INSERT INTO ltestpol (polizaid, nrohilo, estado, inicio_lapso, fin_lapso) VALUES (?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        if($stmt){
            $stmt->bind_param('isiss', $polizaId, $nroHiloNuevo, $nuevoEstado, $fechaActual, $finLapso);
            $stmt->execute();
            $arr['LtEstPoliza'] = true;
        }
        $stmt->close();

        return json_encode($arr);
    }
}
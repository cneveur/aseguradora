<?php   

// include_once('../config/db.php');
    
class FuncionesGlobalesController{

    private $conexion;

    public function __construct()
    { 
        $this->conexion = Database::connect();
        date_default_timezone_set("America/Argentina/Buenos_Aires");
    }

    
    public function listadoProvincias()
    {
        $provincias = '';
        $mysqli = $this->conexion;

        $sql = "SELECT id, nombre FROM provincia";

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){
            $stmt->execute();

            $rs = $stmt->get_result();
            if ($rs->num_rows > 0) {
                $provincias .= '<option value="n" selected disabled>Seleccione</option>';
                foreach($rs as $pro){
                    $provincias .= '<option value="'.$pro['id'].'">'.$pro['nombre'].'</option>';
                }     
            }
        }
        $stmt->close();
        return $provincias;
    }

    public function listadoLocalidadesPorCp($cp)
    {
        $cp = $cp['cp'];
        $arr1 = array();

        $sql  = 
        " SELECT id, proper(nombre) FROM localidad
          WHERE codigopostal = ? ";

        $mysqli = $this->conexion;

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){
            $stmt->bind_param('s', $cp);
            $stmt->execute();

            $rs = $stmt->get_result();
            if ($rs->num_rows > 0) {
                while ($fila = $rs->fetch_array()) {
                    $arr                    = array();
                    $arr['localidadId']     = $fila[0];
                    $arr['localidadNombre'] = $fila[1];
                    $arr1[]                 = $arr;
                }
            }

            $stmt->close();

            echo json_encode(array('data' => $arr1));
        }
    }

    public function listadoLocalidadesPorProvincia($post)
    {
        $id = $post['id'];
        $arr1 = array();

        $mysqli = $this->conexion;

        $sql  = 
        "SELECT id, proper(nombre) FROM localidad
         WHERE provincia_id = ?";

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $rs = $stmt->get_result();
            if ($rs->num_rows > 0) {
                while ($fila = $rs->fetch_array()) {
                    $arr                    = array();
                    $arr['localidadId']     = $fila[0];
                    $arr['localidadNombre'] = $fila[1];
                    $arr1[]                 = $arr;
                }
            }

            $stmt->close();
            echo json_encode(array('data' => $arr1));
        }
    }

    public function listadoCpPorProvincia($id)
    {
        $id = $id['id'];

        $arr1 = array();

        $mysqli = $this->conexion;

        $sql  = 
        "SELECT id, codigopostal FROM localidad
         WHERE provincia_id = ?
         GROUP BY codigopostal";

        $stmt = $mysqli->prepare($sql);
        if($stmt!==FALSE){
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $rs = $stmt->get_result();
            if ($rs->num_rows > 0) {
                while ($fila = $rs->fetch_array()) {
                    $arr           = array();
                    $arr['cpId']   = $fila[0];
                    $arr['cpNum']  = $fila[1];
                    $arr1[]        = $arr;
                }
            }

            $stmt->close();
            echo json_encode(array('data' => $arr1));
        }
    }

    public function listadoCp()
    {

        $mysqli = $this->conexion;
        $cps = '';

        $sql = "SELECT DISTINCT codigopostal FROM localidad";
        $stmt = $mysqli->prepare($sql);
        if($stmt!=FALSE){
            $stmt->execute();
            $rs = $stmt->get_result();
            if($rs->num_rows > 0){
                $cps .= '<option value="n" selected disabled>Seleccione</option>';
                foreach($rs as $cp){
                    $cps .= '<option value="'.$cp['codigopostal'].'">'.$cp['codigopostal'].'</option>';
                }   
            }
        }
        
        $stmt->close();
        return $cps;
    }

}
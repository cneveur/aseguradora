<?php

$error = true;

if (isset($_POST['param'])) {
    $opcion = $_POST['param'];

    $error = false;
    include_once('../config/db.php');
}

if(!$error){

    switch ($opcion) {

        //--------------------POLIZAS---------------------------
        case 10000:
            include_once '../controlador/polizasController.php';
            $polizac = new PolizasController();
            $retorno = $polizac->buscarmodelos($_POST);
            break;

        case 10001:
            include_once '../controlador/polizasController.php';
            $polizac = new PolizasController();
            $retorno = $polizac->listadoCoberturas();
            break;

        case 10002:
            include_once '../controlador/polizasController.php';
            $polizac = new PolizasController();
            $retorno = $polizac->grabarVehiculo($_POST);
            break;

        case 10003:
            include_once '../controlador/polizasController.php';
            $polizac = new PolizasController();
            $retorno = $polizac->mostrarInformacion($_POST);
            break;

        case 10004:
            include_once '../controlador/polizasController.php';
            $polizac = new PolizasController();
            $retorno = $polizac->listarPolizas();
            break;

        case 10005:
            include_once '../controlador/polizasController.php';
            $polizac = new PolizasController();
            $retorno = $polizac->renovarPoliza($_POST);
            break;

        case 10006:
            include_once '../controlador/polizasController.php';
            $polizac = new PolizasController();
            $retorno = $polizac->actualizarEstadosPoliza();
            break;

        case 10007:
            include_once '../controlador/polizasController.php';
            $polizac = new PolizasController();
            $retorno = $polizac->traerDatosPoliza($_POST);
            break;
        
        case 10008:
            include_once '../controlador/polizasController.php';
            $polizac = new PolizasController();
            $retorno = $polizac->modificarDatosPoliza($_POST);
            break;

        //--------------------ENDOSOS---------------------------
        case 20000:
            include_once '../controlador/endososController.php';
            $endosoc = new EndososController;
            $retorno = $endosoc->grabarEndoso($_POST);
            break;

        case 20001:
            include_once '../controlador/endososController.php';
            $endosoc = new EndososController;
            $retorno = $endosoc->mostrarEndososPorPoliza($_POST);
            break;

        case 20002:
            include_once '../controlador/endososController.php';
            $endosoc = new EndososController;
            $retorno = $endosoc->traerDescripcionEndoso($_POST);
            break;

        case 20003:
            include_once '../controlador/endososController.php';
            $endosoc = new EndososController;
            $retorno = $endosoc->actualizarDescripcion($_POST);
            break;

        //--------------------SINIESTROS---------------------------
        case 30000:
            include_once '../controlador/siniestrosController.php';
            $siniestrosc = new SiniestrosController();
            $retorno = $siniestrosc->traerDatosDenunciante($_POST);
            break;

        case 30001:
            include_once '../controlador/siniestrosController.php';
            $siniestrosc = new SiniestrosController();
            $retorno = $siniestrosc->grabarSiniestro($_POST);
            break;

        case 30002:
            include_once '../controlador/siniestrosController.php';
            $siniestrosc = new SiniestrosController();
            $retorno = $siniestrosc->modificarEstadoSiniestro($_POST);
            break;

        case 30003:
            include_once '../controlador/siniestrosController.php';
            $siniestrosc = new SiniestrosController();
            $retorno = $siniestrosc->traerDatosSiniestro($_POST);
            break;

        case 30004:
            include_once '../controlador/siniestrosController.php';
            $siniestrosc = new SiniestrosController();
            $retorno = $siniestrosc->actualizarDatosSin($_POST);
            break;

        case 30005:
            include_once '../controlador/siniestrosController.php';
            $siniestrosc = new SiniestrosController();
            $retorno = $siniestrosc->verSiniestro($_POST);
            break;

        case 30006:
            include_once '../controlador/siniestrosController.php';
            $siniestrosc = new SiniestrosController();
            $retorno = $siniestrosc->mostrarImgSin($_POST);
            break;

        case 30007:
            include_once '../controlador/siniestrosController.php';
            $siniestrosc = new SiniestrosController();
            $retorno = $siniestrosc->eliminarImgSin($_POST);
            break;

        //--------------------CLIENTE/TOMADOR---------------------------
        case 40000:
            include_once '../controlador/tomadoresController.php';
            $tomadorc = new TomadoresController();
            $retorno = $tomadorc->mostrarDatosCliente($_POST);
            break;

        case 40001:
            include_once '../controlador/tomadoresController.php';
            $tomadorc = new TomadoresController();
            $retorno = $tomadorc->grabarCliente($_POST);
            break;

        case 40002:
            include_once '../controlador/tomadoresController.php';
            $tomadorc = new TomadoresController();
            $retorno = $tomadorc->mostrarDatosTomador($_POST);
            break;

        case 40003:
            include_once '../controlador/tomadoresController.php';
            $tomadorc = new TomadoresController();
            $retorno = $tomadorc->modificarDatosTomador($_POST);
            break;

        case 40004:
            include_once '../controlador/tomadoresController.php';
            $tomadorc = new TomadoresController();
            $retorno = $tomadorc->eliminarTomador($_POST);
            break;

        case 40005:
            include_once '../controlador/tomadoresController.php';
            $tomadorc = new TomadoresController();
            $retorno = $tomadorc->listarTomadores();
            break;

        //--------------------PAGOS---------------------------
        case 50000:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->generarFormularioPago($_POST);
            break;

        case 50001:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->modificarCuotas($_POST);
            break;

        case 50002:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->mostrarInfoPago($_POST);
            break;

        case 50003:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->mostrarCuotas($_POST);
            break;

        case 50004:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->facturarPago($_POST);
            break;

        case 50005:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->traerDatosReciboCuota($_POST);
            break;

        case 50006:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->efectuarPago($_POST);
            break;

        case 50007:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->actualizarEstadoCuota();
            break;
        
        case 50008:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->comprobarDeuda($_POST);
            break;

        case 50009:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->enviarRecibo($_POST);
            break;

        case 50010:
            include_once '../controlador/pagosController.php';
            $pagoc = new PagosController();
            $retorno = $pagoc->mostrarListadoRecibos($_POST);
            break;

        //--------------------USERS---------------------------  
        case 60000:
            include_once '../controlador/usersController.php';
            $user = new UsersController();
            $retorno = $user->grabarUser($_POST);
            break;

        case 60001:
            include_once '../controlador/usersController.php';
            $user = new UsersController();
            $retorno = $user->bajaUser($_POST);
            break;

        case 60002:
            include_once '../controlador/usersController.php';
            $user = new UsersController();
            $retorno = $user->reactivarUser($_POST);
            break;

        case 60003:
            include_once '../controlador/usersController.php';
            $user = new UsersController();
            $retorno = $user->traerInfoUser($_POST);
            break;

        case 60004:
            include_once '../controlador/usersController.php';
            $user = new UsersController();
            $retorno = $user->modRolUser($_POST);
            break;

        case 60005:
            include_once '../controlador/usersController.php';
            $user = new UsersController();
            $retorno = $user->getMisDatosUser();
            break;

        case 60006:
            include_once '../controlador/usersController.php';
            $user = new UsersController();
            $retorno = $user->setMisDatosUser($_POST);
            break;

        case 60007:
            include_once '../controlador/usersController.php';
            $user = new UsersController();
            $retorno = $user->getFotoPerfilUser($_POST);
            break;

        case 60008:
            include_once '../controlador/usersController.php';
            $user = new UsersController();
            $retorno = $user->eliminarFotoPerfilUser($_POST);
            break;

        case 60009:
            include_once '../controlador/usersController.php';
            $user = new UsersController();
            $retorno = $user->reenviarInfoAccesoUser($_POST);
            break;

        //--------------------SUCURSALES---------------------------  
        case 70000:
            include_once '../controlador/sucursalesController.php';
            $suc = new SucursalesController();
            $retorno = $suc->registrarSucursal($_POST);
            break;

        case 70001:
            include_once '../controlador/sucursalesController.php';
            $suc = new SucursalesController();
            $retorno = $suc->eliminarSucursal($_POST);
            break;
        
        case 70002:
            include_once '../controlador/sucursalesController.php';
            $suc = new SucursalesController();
            $retorno = $suc->traerInfoSucursal($_POST);
            break;

        case 70003:
            include_once '../controlador/sucursalesController.php';
            $suc = new SucursalesController();
            $retorno = $suc->modificarSucursal($_POST);
            break;

        case 70004:
            include_once '../controlador/sucursalesController.php';
            $suc = new SucursalesController();
            $retorno = $suc->listadoSucursalesSelect($_POST);
            break;

        //--------------------FUNCIONES GLOBALES DEL SISTEMA---------------------------     
        case 80000:
            include_once '../controlador/funcionesGlobalesController.php';
            $globalc = new FuncionesGlobalesController();
            $retorno = $globalc->listadoLocalidadesPorCp($_POST);
            break;

        case 80001:
            include_once '../controlador/funcionesGlobalesController.php';
            $globalc = new FuncionesGlobalesController();
            $retorno = $globalc->listadoLocalidadesPorProvincia($_POST);
            break;

        case 80002:
            include_once '../controlador/funcionesGlobalesController.php';
            $globalc = new FuncionesGlobalesController();
            $retorno = $globalc->listadoCpPorProvincia($_POST);
            break;

        //--------------------SISTEMA---------------------------  
        case 90000:
            include_once '../controlador/sistemaController.php';
            $login = new SistemaController();
            $retorno = $login->loginSistema($_POST);
            break;

        case 90001:
            include_once '../controlador/sistemaController.php';
            $login = new SistemaController();
            $retorno = $login->logoutSistema();
            break;

    }

    echo $retorno;

}else{
    echo 'Error aseguradora.php';
}
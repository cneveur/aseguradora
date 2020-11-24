<?php

	$srcImg = 'img/ImgAlt/asegLogo.png'; //Definimos la url de la imagen

	if(!isset($_SESSION)) 
    { 
        session_start(); 
	} 
	
	if(isset($_SESSION['recibo'])){
		$info = $_SESSION['recibo'];

		if(isset($_SESSION['urlImg'])){
			//Como llamamos al fichero desde diferentes lugares, la url de la imagen debe de modificarse
			$srcImg = $_SESSION['urlImg'].$srcImg;
		}

	}else{
		$info['numPol'] = '****************';
		$info['nroCu'] = '*********';
		$info['tom'] = '********* ********* / ************';
		$info['dom'] = '************ ****** ******';
		$info['fact'] = '********';
		$info['lapCu'] = '********  ********';
		$info['vig'] = '******** ********';
		$info['vtoPa'] = '********';
		$info['priMen'] = '********';
		$info['veh'] = '******** *********** ********* ***';
		$info['anioVe'] = '****';
		$info['pat'] = '******';
		$info['nroMot'] = '***********';
		$info['sumAs'] = '*******';
		$info['cob'] = '**** ** *********';
		$info['cobAd'] = '******** ******** *****';
		$info['numPol'] = '**************';
		$info['fact'] = '***************';
	}

?>

<html class="htmlRecibo">

	<link rel="stylesheet" href="css/recibos/recibosPDF.css">

	<div class="recibo">

		<div class="nombreComp"><img src="<?= $srcImg ?>" class="logoAs" alt="Logo Aseguradora"></div>     
		<div class="infoComp">
		<div> <span class="neg">Cupon oficial Asegurado</span> </div>
		<div> <span class="domicEmpr"></span>Av. España 789 Santa Rosa La Pampa</div>
		<div> <span class="neg">C.U.I.T:</span> <span class="cuitEmpr"></span>30-70003691-1</div>
		</div>         

		<div class="datosRecibo">
		<div> <span class="neg">Poliza/Cuota:</span> 
			<span class="nroPol"> <?=$info['numPol']?> </span> / <span class="nroCuota"> <?=$info['nroCu']?> </span>
		</div>
		<div> <span class="neg">Tom/Num:</span> <span class="aseg"> <?= $info['tom'] ?> </span> </div>
		<div> <span class="neg">Domicilio:</span> <span class="domic"> <?= $info['dom'] ?> </span> </div>
		<div> 
			<span class="neg">Fecha factuacion:</span> <span class="emision"> <?= $info['fact'] ?> </span>
			<span class="neg">Lapso:</span> <span class="laps"> <?= $info['lapCu'] ?> </span>
		</div>
		<div> <span class="neg">Vigencia de poliza:</span> <span class="vigencia"> <?= $info['vig'] ?> </span> </div>
		<div>
			<div class="lineaVenImp">
			<div class="cont">
				<span class="neg">Vencimiento del pago:</span> <span class="vtoPago"> <?= $info['vtoPa'] ?> </span>
				<span class="neg">Importe:</span> <span class="importe"> <?='$'.$info['priMen'] ?> </span>
			</div>
			</div> 
		</div>
		</div>

		<div class="infoVehic">
		<div> <span class="neg">Vehiculo:</span> <span class="vehiculo"> <?= $info['veh'] ?> </span> </div>
		<div> 
			<span class="neg">Año:</span> <span class="anio"> <?= $info['anioVe'] ?> </span>
			<span class="neg">Patente:</span> <span class="patente"> <?= $info['pat'] ?> </span>
			<span class="neg">Motor:</span> <span class="nroMot"> <?= $info['nroMot'] ?> </span>
			<span class="neg">S.Aseg:</span> <span class="sAseg"> <?='$'.$info['sumAs'] ?> </span>
		</div>
		<div> 
			<span class="neg">Cob:</span> <span class="cob"> <?= $info['cob'] ?> </span> 
			<span class="neg">Ad:</span> <span class="cobAd"> <?= $info['cobAd'] ?> </span> 
		</div>
		</div>

		<div class="pieRecibo">
		<div class="textoPieRec">El presente plan de pago para la poliza <span class="numPoliza"> <?= $info['numPol'] ?> </span> reemplaza cualquier documento emitido con anterioridad a la fecha <span class="fechaEmision"> <?= $info['fact'] ?> </span>.</div>
		</div>
		
	</div>
</html>
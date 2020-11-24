<?php
	if(!isset($_SESSION)) { 
        session_start(); 
	} 	
	if(isset($_SESSION['infoListadoRecibos'])){
        $infoRecibos = $_SESSION['infoListadoRecibos'];
    }
?>
<html>  
    <head>
        <link rel="stylesheet" href="css/estilos.css">  
    </head>
    <body>
        <!--Listado de recibos de una poliza-->
        <div class="LR1">
            <div class="LR2">
                
                <?php for($i=0; $i < $infoRecibos['filas']; $i++): ?>
                    <div class="LR3">
                        <div class="LR4">
                            <div class="recibo">
                                <div class="nombreComp"><img src="img/ImgAlt/asegLogo.png" class="logoAs" alt="Logo Aseguradora"></div>     
                                <div class="infoComp">
                                    <div> <span class="neg">Cupon oficial Asegurado</span> </div>
                                    <div> <span class="domicEmpr"></span>Av. España 789 Santa Rosa La Pampa</div>
                                    <div> <span class="neg">C.U.I.T:</span> <span class="cuitEmpr"></span>30-70003691-1</div>
                                </div>         
                                <div class="datosRecibo">
                                    <div> <span class="neg">Poliza/Cuota:</span> 
                                        <span class="nroPol"> <?=$infoRecibos[$i]['numPol']?> </span> / <span class="nroCuota"> <?=$infoRecibos[$i]['nroCu']?> </span>
                                    </div>
                                    <div> <span class="neg">Tom/Num:</span> <span class="aseg"> <?= $infoRecibos[$i]['tom'] ?> </span> </div>
                                    <div> <span class="neg">Domicilio:</span> <span class="domic"> <?= $infoRecibos[$i]['dom'] ?> </span> </div>
                                    <div> 
                                        <span class="neg">Fecha factuacion:</span> <span class="emision"> <?= $infoRecibos[$i]['fact'] ?> </span>
                                        <span class="neg">Lapso:</span> <span class="laps"> <?= $infoRecibos[$i]['lapCu'] ?> </span>
                                    </div>
                                    <div> <span class="neg">Vigencia de poliza:</span> <span class="vigencia"> <?= $infoRecibos[$i]['vig'] ?> </span> </div>
                                    <div>
                                        <div class="lineaVenImp">
                                            <div class="cont">
                                                <span class="neg">Vencimiento del pago:</span> <span class="vtoPago"> <?= $infoRecibos[$i]['vtoPa'] ?> </span>
                                                <span class="neg">Importe:</span> <span class="importe"> <?='$'.$infoRecibos[$i]['priMen'] ?> </span>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="infoVehic">
                                    <div> <span class="neg">Vehiculo:</span> <span class="vehiculo"> <?= $infoRecibos[$i]['veh'] ?> </span> </div>
                                    <div> 
                                        <span class="neg">Año:</span> <span class="anio"> <?= $infoRecibos[$i]['anioVe'] ?> </span>
                                        <span class="neg">Patente:</span> <span class="patente"> <?= $infoRecibos[$i]['pat'] ?> </span>
                                        <span class="neg">Motor:</span> <span class="nroMot"> <?= $infoRecibos[$i]['nroMot'] ?> </span>
                                        <span class="neg">S.Aseg:</span> <span class="sAseg"> <?='$'.$infoRecibos[$i]['sumAs'] ?> </span>
                                    </div>
                                    <div> 
                                        <span class="neg">Cob:</span> <span class="cob"> <?= $infoRecibos[$i]['cob'] ?> </span> 
                                        <span class="neg">Ad:</span> <span class="cobAd"> <?= $infoRecibos[$i]['cobAd'] ?> </span> 
                                    </div>
                                </div>
                                <div class="pieRecibo">
                                <div class="textoPieRec">El presente plan de pago para la poliza <span class="numPoliza"> <?= $infoRecibos[$i]['numPol'] ?> </span> reemplaza cualquier documento emitido con anterioridad a la fecha <span class="fechaEmision"> <?= $infoRecibos[$i]['fact'] ?> </span>.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor ?>
            </div>
        </div>
        <div class="btnsVerListCu">
            <button type="button" class="btn btn-flat blue btnAtrasListCu"> <i class="fas fa-caret-left"></i> Volver</button>
            <a type="button" class="btn btn-flat btnDescargarListCu"> <i class="fas fa-file-download"></i> PDF</a>
            <form id="formCorreo" class="formEnvCorrListadoCuo">
                <button type="submit" class="btn btn-flat grey btnEnviarListCu"> Enviar <i class="fas fa-share"></i></button>
                <input type="email" class="envEmail browser-default" id="email" placeholder="E-mail" name="correo" value=" <?= $infoRecibos[0]['email'] ?> ">
            </form>
        </div>
        <script type="text/javascript" src="js/pago.js?v=3"></script>
    </body>
</html>


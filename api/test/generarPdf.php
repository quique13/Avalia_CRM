<?php
//error_reporting(0);
include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";
require_once '../class/dompdfPHP7/src/Autoloader.php';
require_once '../class/dompdfPHP7/lib/php-font-lib/src/FontLib/Autoloader.php';
date_default_timezone_set('America/Guatemala');

Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;

$conn = new dbClassMysql();
$func = new Functions();

if(isset($_GET['reciboPdf'])){
    $idPago=$_GET['idPago'];
    $strQueryPdf = "SELECT ped.idUsuarioPago, e.idVendedor,e.idCliente, idDetalle,fechaPagoRealizado as fechaPago,montoPagado,noDeposito,bancoDeposito,noPago,e.apartamento,e.proyecto,noRecibo,
    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),
    IFNULL(CONCAT(primerApellido,' '),''),IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as nombreCliente  
    FROM  prograEngancheDetalle ped
    INNER JOIN  enganche e ON ped.idEnganche = e.idEnganche
    INNER JOIN  agregarCliente ag ON e.idCliente = ag.idCliente   
    where idDetalle ={$idPago}";

    //echo $strQueryPdf;
    $qTmp = $conn ->db_query($strQueryPdf);
    $rTmp = $conn->db_fetch_object($qTmp);    
    $montoCocina=0;
    if($rTmp->proyecto=='Marabi'){
        $logo = '../img/logo Marabi.png';
       
    }else if($rTmp->proyecto=='Naos'){
        $logo = '../img/logo naos.png';
    }
    if($rTmp->cocina=='Sin cocina' || $rTmp->cocina==''){
        $cocina = 'No';
    }else{
        $cocina = 'Si';
    }
    $proyecto = strtoupper($rTmp->proyecto);
    $intIdOcaCliente=$rTmp->idCliente;
    $meses = array(1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $montoLetras=monto_letras($rTmp->montoPagado);
    //$montoLetras=monto_letras(1100);
  
        $fecha= date('d',strtotime($rTmp->fechaPago)).' De '.$meses[date('n',strtotime($rTmp->fechaPago))].' De '.date('Y',strtotime($rTmp->fechaPago));
        //$fecha='31 De '.$meses[3].' De 2,022';
        $noRecibo=$rTmp->noRecibo==''?$rTmp->idDetalle:$rTmp->noRecibo;
        //$noRecibo=$rTmp->noRecibo==''?'1'.$rTmp->idDetalle+1:$rTmp->noRecibo;
        $nombreCompleto=$rTmp->nombreCliente;
        $monto='Q.'.number_format($rTmp->montoPagado,2,".",",");
        //$monto='Q.'.number_format(1100,2,".",",");
        $montoLetras=$montoLetras;
        $concepto='Pago enganche No.'.$rTmp->noPago;
        //$concepto='Gabinetes de cocina y 2 closets hab. Principal y 1 hab. Secundaria';
        $cocina='no';
        //$cocina='Si';
        $montoCocina='';
        $saldoCocina='';
        $tipoPago='enganche';
        //$tipoPago='';
        $apartamento=$rTmp->apartamento;
        $noDeposito=$rTmp->noDeposito;
        //$noDeposito='31217774';
        $banco=$rTmp->bancoDeposito;
        $moneda='quetzales';
        $fechaActual='Recibo generado con fecha '.date('d/m/Y H:i:s');
        $codigoRecibo=$rTmp->idUsuarioPago.'-'.$rTmp->idVendedor.'-'.date('Ymd',strtotime($rTmp->fechaPago)).'-'.$rTmp->idDetalle;
        $nombre = normalizarNombre($nombreCompleto)."_".$codigoRecibo.".pdf";
    // instantiate and use the dompdf class
  
    $dompdf = new Dompdf();
    $font = \FontLib\Font::load('Effra_Std_Rg.ttf');
    $font->parse();  // for getFontWeight() to work this call must be done first!
    echo $font->getFontName() .'<br>';
    echo $font->getFontSubfamily() .'<br>';
    echo $font->getFontSubfamilyID() .'<br>';
    echo $font->getFontFullName() .'<br>';
    echo $font->getFontVersion() .'<br>';
    echo $font->getFontWeight() .'<br>';
    echo $font->getFontPostscriptName() .'<br>';
    $font->close();
    $texto_r='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style>
                    @font-face {
                        font-family: effrarg;
                        src: url("Effra_Std_Lt.ttf");
                    }
                    @font-face {
                        font-family: effrargB;
                        src: url("Effra_Std_Rg.ttf");
                        font-weight: bold;
                    }
            </style>
        </head>
        <body>
        <img style="position:absolute;top:5.47in;left:6.30in;width:4.60in;height:0.70in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_1.png').'" />
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">El </span></SPAN><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.14in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">pago </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.45in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.65in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">medio </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.02in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.19in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cheque </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.62in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">est&aacute; </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.88in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">sujeto </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.24in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.34in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.47in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">condici&oacute;n </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.01in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.18in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">que </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.42in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.55in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">mismo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.94in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">sea</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.37in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">efectivo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.82in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.92in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">su </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.08in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">presentaci&oacute;n, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.84in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">si </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.96in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.09in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cheque </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.52in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">es </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.68in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">rechazado </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.28in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.49in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.62in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">falta </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.88in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">fondos </span></SPAN><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.40in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">o </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.71in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cualquier </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.23in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">otra </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.48in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">causa, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.00in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">empresa </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">se </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.66in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">reserva </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.10in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.23in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">derecho </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.70in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">proceder</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">acuerdo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.64in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.74in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">lo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">establecido </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.52in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.73in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.85in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">ley </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.04in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">(C&oacute;digo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.67in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">Comercio, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.26in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">C&oacute;digo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.67in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">permitido).</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:6.19in;left:1.00in;width:4.42in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_2.png').'" />
        <div style="position:absolute;top:6.21in;left:1.11in;width:4.14in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:8pt;font-family:effrarg;color:#000000">ORGINAL; Cliente - DUPLICADO; Contabilidad - TRIPLICADO - Archivo</span></SPAN><br/></div>
        <div style="position:absolute;top:6.21in;left:1.11in;width:4.14in;line-height:0.17in;"><DIV style="position:relative; left:0.63in;"><span style="font-style:normal;font-weight:bold;font-size:8pt;font-family:effrarg;color:#000000"></span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;width:3.41in;line-height:0.27in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">SPV </span></SPAN><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;line-height:0.27in;"><DIV style="position:relative; left:0.48in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$proyecto.', SOCIEDAD AN&Oacute;NIMA </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;width:3.41in;line-height:0.27in;"><DIV style="position:relative; left:1.20in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;width:3.41in;line-height:0.27in;"><DIV style="position:relative; left:2.36in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"></span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">4TA. </span></SPAN><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:0.33in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Avenida </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:0.86in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">23</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">-</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">80, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.28in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">zona </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.62in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">14 </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Guatemala</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:1.25in;left:8.07in;width:0.85in;line-height:0.23in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:effrarg;color:#000000">RECIBO DE CAJA</span></SPAN><br/></div>
        <img style="position:absolute;top:1.19in;left:8.95in;width:0.78in;height:0.47in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_3.png').'" />
        <div style="position:absolute;top:1.19in;left:9.06in;width:2.5in;line-height:0.39in;"><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Times New Roman;color:#ff0000">No</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Times New Roman;color:#ff0000">. '.$noRecibo.'</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Times New Roman;color:#ff0000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.24in;left:0.49in;width:10.94in;height:0.32in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_1.png').'" />
        <img style="position:absolute;top:2.24in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_2.png').'" />
        <div style="position:absolute;top:2.31in;left:0.62in;width:1.06in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">RECIBIMOS </span></SPAN><br/></div>
        <div style="position:absolute;top:2.31in;left:0.62in;width:10.66in;line-height:0.17in;"><DIV style="position:relative; left:0.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$nombreCompleto.' </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:2.27in;left:1.56in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_1.png').'" />
        <img style="position:absolute;top:1.82in;left:0.52in;width:7.07in;height:0.34in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_3.png').'" />
        <div style="position:absolute;top:1.90in;left:0.66in;width:6.8in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">FECHA: </span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$fecha.'</span><br/></SPAN></div>
        <img style="position:absolute;top:1.85in;left:1.07in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_2.png').'" />
        <img style="position:absolute;top:1.81in;left:7.59in;width:3.84in;height:0.34in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_4.png').'" />
        <div style="position:absolute;top:1.86in;left:7.73in;width:3.58in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MONTO:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$monto.' </span><br/></SPAN></div>
        <img style="position:absolute;top:1.84in;left:8.18in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_3.png').'" />
        <img style="position:absolute;top:2.56in;left:0.49in;width:10.93in;height:0.32in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_4.png').'" />
        <img style="position:absolute;top:2.56in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_5.png').'" />
        <div style="position:absolute;top:2.62in;left:0.61in;width:1.20in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">LA </span></SPAN><br/></div>
        <div style="position:absolute;top:2.62in;left:0.61in;width:1.20in;line-height:0.17in;"><DIV style="position:relative; left:0.20in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CANTIDAD </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:2.62in;left:0.61in;width:10.60in;line-height:0.17in;"><DIV style="position:relative; left:0.94in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$montoLetras.'</span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:2.57in;left:1.68in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_4.png').'" />
        <img style="position:absolute;top:2.88in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_5.png').'" />
        <img style="position:absolute;top:2.95in;left:0.51in;width:10.90in;height:0.19in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_5.png').'" />
        <img style="position:absolute;top:2.88in;left:11.13in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_6.png').'" />
        <img style="position:absolute;top:3.32in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_6.png').'" />
        <div style="position:absolute;top:3.39in;left:0.62in;width:1.40in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">POR </span></SPAN><br/></div>
        <div style="position:absolute;top:3.39in;left:0.62in;width:1.40in;line-height:0.17in;"><DIV style="position:relative; left:0.33in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CONCEPTO </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:3.39in;left:0.62in;width:10.60in;line-height:0.17in;"><DIV style="position:relative; left:1.14in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$concepto.'</span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:3.35in;left:1.90in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_7.png').'" />
        <img style="position:absolute;top:3.64in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_6.png').'" />
        ';
        if($rTmp->proyecto=='Naos'){
            $texto_r .= '<div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">PAGO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:0.43in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">POR </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:0.76in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CONCEPTO </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:1.57in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:1.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MUEBLES </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:2.48in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:2.71in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">COCINA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        <div style="position:absolute;top:3.72in;left:5.97in;width:2.21in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">ABONO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.72in;left:5.97in;width:2.21in;line-height:0.17in;"><DIV style="position:relative; left:0.53in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Q.____________________</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        <div style="position:absolute;top:3.72in;left:8.65in;width:2.22in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">SALDO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.72in;left:8.65in;width:2.22in;line-height:0.17in;"><DIV style="position:relative; left:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Q. </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:8.65in;width:2.22in;line-height:0.17in;"><DIV style="position:relative; left:0.68in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">____________________</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        ';
        }
        
        $texto_r .= '<img style="position:absolute;top:3.70in;left:10.75in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_8.png').'" />
        <img style="position:absolute;top:3.96in;left:0.49in;width:3.68in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_7.png').'" />
        <div style="position:absolute;top:4.04in;left:0.61in;width:0.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">RESERVA:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.03in;left:1.25in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_9.png').'" />
        <img style="position:absolute;top:3.96in;left:4.16in;width:3.68in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_8.png').'" />
        <div style="position:absolute;top:4.04in;left:4.28in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">ENGANCHE:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.99in;left:5.04in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <div style="position:absolute;top:4.04in;left:5.10in;width:0.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:effrarg;color:#000000">X</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.96in;left:7.83in;width:3.61in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_9.png').'" />
        <div style="position:absolute;top:4.04in;left:7.95in;width:1.06in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CANCELACI&Oacute;N:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.99in;left:8.92in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:4.28in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_10.png').'" />
        <div style="position:absolute;top:4.37in;left:0.61in;width:10.11in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">APARTAMENTO:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$apartamento.' </span><br/></SPAN></div>
        
        <img style="position:absolute;top:4.61in;left:0.49in;width:4.01in;height:0.65in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_11.png').'" />
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">N</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">O</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">.</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN><br/></div>
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.16in;"><DIV style="position:relative; left:0.25in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.17in;"><DIV style="position:relative; left:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CHEQUE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.68in;left:0.61in;width:3.60in;line-height:0.50in;"><DIV style="position:relative; left:0in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$noDeposito.'</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.70in;left:2.22in;width:1.65in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">N</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">O</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">.</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN><br/></div>
        <div style="position:absolute;top:4.70in;left:2.22in;width:1.65in;line-height:0.16in;"><DIV style="position:relative; left:0.25in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.70in;left:2.22in;width:1.65in;line-height:0.17in;"><DIV style="position:relative; left:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">TRANSFERENCIA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:4.60in;left:4.49in;width:4.03in;height:0.66in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_12.png').'" />
        <div style="position:absolute;top:4.70in;left:4.61in;width:0.56in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">BANCO:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.68in;left:4.61in;width:3.70in;line-height:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$banco.'</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.61in;left:8.52in;width:2.92in;height:0.65in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_13.png').'" />
        <div style="position:absolute;top:4.70in;left:8.64in;width:0.67in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MONEDA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.94in;left:8.64in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">QUETZALES</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.91in;left:9.55in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <div style="position:absolute;top:4.96in;left:9.60in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:effrarg;color:#000000">X</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.94in;left:10.25in;width:0.69in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DOLARES</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.44in;left:6.28in;width:4.62in;height:0.74in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_7.png').'" />
        <img style="position:absolute;top:5.44in;left:0.91in;width:4.62in;height:0.74in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_8.png').'" />
        <div style="position:absolute;top:5.68in;left:1in;width:4.50in;line-height:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$codigoRecibo.' </span></SPAN><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">FIRMA </span></SPAN><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.13in;"><DIV style="position:relative; left:0.36in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">DEL </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.13in;"><DIV style="position:relative; left:0.59in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">VENDEDOR </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.90in;left:2.27in;width:1.94in;line-height:0.16in;"><DIV style="position:relative; left:1.22in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">/</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.14in;"><DIV style="position:relative; left:1.29in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">COBRADOR</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:0.25in;left:0.54in;width:2.33in;height:0.95in" src="'.fcnBase64($logo).'" />
        <img style="position:absolute;top:2.57in;left:1.77in;width:9.65in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_1.png').'" />
        <img style="position:absolute;top:3.65in;left:6.64in;width:1.51in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_2.png').'" />
        <img style="position:absolute;top:3.65in;left:9.33in;width:1.50in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_3.png').'" />
        <img style="position:absolute;top:4.86in;left:0.51in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_4.png').'" />
        <img style="position:absolute;top:4.86in;left:4.53in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_5.png').'" />
        <img style="position:absolute;top:1.83in;left:8.27in;width:3.11in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_6.png').'" />
        <img style="position:absolute;top:1.83in;left:1.15in;width:6.40in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_7.png').'" />

        <img style="position:absolute;top:3.32in;left:1.99in;width:9.42in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_9.png').'" />
        ';
        if($rTmp->proyecto=='Naos'){
            $texto_r .= '<img style="position:absolute;top:3.67in;left:3.89in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />';
        }
        
        $texto_r .= '
        <img style="position:absolute;top:3.99in;left:1.46in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:1.29in;left:9.49in;width:1.87in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_14.png').'" />
        <img style="position:absolute;top:4.29in;left:1.69in;width:9.72in;height:0.31in" src="" />
        
        <img style="position:absolute;top:4.91in;left:10.98in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_17.png').'" />
        <div style="position:absolute;top:7.28in;left:7.88in;width:5.87in;height:0.31in"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#ff0000">'.$fechaActual.'</span><br/></SPAN></div>
        
        <img style="position:absolute;top:5.56in;left:1.22in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_19.png').'" />
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">hecho </span></SPAN><br/></div>
        </body>
    </html>';
    $dompdf->load_html(($texto_r));

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('legal', 'landscape');

    // Render the HTML as PDF
    $dompdf->render();

     //Guardar PDF en servidor
     $_path = "../public/";
     $pdfD = $dompdf->output();
     file_put_contents($_path."recibo_".$idPago.".pdf", $pdfD);
     $codigo = "recibo_".$idPago.".pdf";
     $strQuery = "   SELECT *
                     FROM  archivo 
                     WHERE id_cliente = {$intIdOcaCliente}
                     AND estado = 1
                     AND nombre = 'recibo_{$idPago}.pdf'   ";
     $qTmp = $conn->db_query($strQuery);
     if ($conn->db_num_rows($qTmp) <= 0) {
         $strQuery = "   INSERT INTO  archivo (id_cliente, id_tipo_documento, codigo, tipo, nombre)
                         VALUES ({$intIdOcaCliente}, 9, '{$codigo}', 'pdf', '{$codigo}')";
         $conn->db_query($strQuery);
     }
     $filename = $_path."recibo_".$idPago.".pdf"; // el nombre con el que se descargará, puede ser diferente al original 
     $nombre = "recibo_".$idPago.".pdf";
     header("Content-type: application/octet-stream"); 
     header("Content-Type: application/force-download"); 
     header("Content-Disposition: attachment; filename=\"$nombre\"\n"); 
     readfile($filename);
    

    // Output the generated PDF to Browser
    // Enviamos el fichero PDF al navegador.
    //$dompdf->stream($nombre);
    
}

if(isset($_GET['reciboExtraPdf'])){
    $idPago=$_GET['idPago'];
    $strQueryPdf = "SELECT ce.idEnganche, ce.idUsuarioPago, e.idVendedor,e.idCliente, idCobro,fechaPagoRealizado as fechaPago,montoPagado,noDeposito,bancoDeposito,noPago,e.apartamento,e.proyecto,noRecibo,
    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),
    IFNULL(CONCAT(primerApellido,' '),''),IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as nombreCliente  
    FROM  cobrosExtras ce
    INNER JOIN  enganche e ON ce.idEnganche = e.idEnganche
    INNER JOIN  agregarCliente ag ON e.idCliente = ag.idCliente   
    where idCobro ={$idPago}";

    //echo $strQueryPdf;
    $qTmp = $conn ->db_query($strQueryPdf);
    $rTmp = $conn->db_fetch_object($qTmp);    
    $montoCocina=0;
    if($rTmp->proyecto=='Marabi'){
        $logo = '../img/logo Marabi.png';
       
    }else if($rTmp->proyecto=='Naos'){
        $logo = '../img/logo naos.png';
    }
    if($rTmp->cocina=='Sin cocina' || $rTmp->cocina==''){
        $cocina = 'No';
    }else{
        $cocina = 'Si';
    }
    $proyecto = strtoupper($rTmp->proyecto);
    $intIdOcaCliente=$rTmp->idCliente;
    $meses = array(1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $montoLetras=monto_letras($rTmp->montoPagado);
  
        $fecha= date('d',strtotime($rTmp->fechaPago)).' De '.$meses[date('n',strtotime($rTmp->fechaPago))].' De '.date('Y',strtotime($rTmp->fechaPago));
        $noRecibo=$rTmp->noRecibo==''?$rTmp->idCobro.$rTmp->idCobro.$rTmp->noPago:$rTmp->noRecibo;
        $nombreCompleto=$rTmp->nombreCliente;
        $monto='Q.'.number_format($rTmp->montoPagado,2,".",",");
        
        $montoLetras=$montoLetras;
        $concepto='Pago Gabinetes de cocina y 2 closets hab. Principal y 1 hab. Secundaria No.'.$rTmp->noPago;
        $cocina='no';
        //$cocina='Si';
        $montoCocina='';
        $saldoCocina='';
        //$tipoPago='enganche';
        $tipoPago='';
        $apartamento=$rTmp->apartamento;
        $noDeposito=$rTmp->noDeposito;
        //$noDeposito='31217774';
        $banco=$rTmp->bancoDeposito;
        $moneda='quetzales';
        $fechaActual='Recibo generado con fecha '.date('d/m/Y H:i:s');
        $codigoRecibo=$rTmp->idUsuarioPago.'-'.$rTmp->idVendedor.'-'.date('Ymd',strtotime($rTmp->fechaPago)).'-'.$rTmp->idCobro;
        $nombre = normalizarNombre($nombreCompleto)."_".$codigoRecibo.".pdf";
    // instantiate and use the dompdf class
  
    $dompdf = new Dompdf();
    $font = \FontLib\Font::load('Effra_Std_Rg.ttf');
    $font->parse();  // for getFontWeight() to work this call must be done first!
    echo $font->getFontName() .'<br>';
    echo $font->getFontSubfamily() .'<br>';
    echo $font->getFontSubfamilyID() .'<br>';
    echo $font->getFontFullName() .'<br>';
    echo $font->getFontVersion() .'<br>';
    echo $font->getFontWeight() .'<br>';
    echo $font->getFontPostscriptName() .'<br>';
    $font->close();
    $texto_r='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style>
                    @font-face {
                        font-family: effrarg;
                        src: url("Effra_Std_Lt.ttf");
                    }
                    @font-face {
                        font-family: effrargB;
                        src: url("Effra_Std_Rg.ttf");
                        font-weight: bold;
                    }
            </style>
        </head>
        <body>
        <img style="position:absolute;top:5.47in;left:6.30in;width:4.60in;height:0.70in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_1.png').'" />
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">El </span></SPAN><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.14in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">pago </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.45in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.65in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">medio </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.02in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.19in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cheque </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.62in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">est&aacute; </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.88in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">sujeto </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.24in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.34in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.47in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">condici&oacute;n </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.01in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.18in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">que </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.42in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.55in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">mismo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.94in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">sea</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.37in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">efectivo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.82in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.92in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">su </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.08in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">presentaci&oacute;n, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.84in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">si </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.96in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.09in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cheque </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.52in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">es </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.68in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">rechazado </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.28in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.49in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.62in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">falta </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.88in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">fondos </span></SPAN><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.40in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">o </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.71in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cualquier </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.23in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">otra </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.48in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">causa, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.00in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">empresa </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">se </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.66in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">reserva </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.10in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.23in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">derecho </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.70in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">proceder</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">acuerdo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.64in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.74in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">lo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">establecido </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.52in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.73in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.85in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">ley </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.04in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">(C&oacute;digo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.67in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">Comercio, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.26in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">C&oacute;digo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.67in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">permitido).</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:6.19in;left:1.00in;width:4.42in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_2.png').'" />
        <div style="position:absolute;top:6.21in;left:1.11in;width:4.14in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:8pt;font-family:effrarg;color:#000000">ORGINAL; Cliente - DUPLICADO; Contabilidad - TRIPLICADO - Archivo</span></SPAN><br/></div>
        <div style="position:absolute;top:6.21in;left:1.11in;width:4.14in;line-height:0.17in;"><DIV style="position:relative; left:0.63in;"><span style="font-style:normal;font-weight:bold;font-size:8pt;font-family:effrarg;color:#000000"></span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;width:3.41in;line-height:0.27in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">SPV </span></SPAN><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;line-height:0.27in;"><DIV style="position:relative; left:0.48in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$proyecto.', SOCIEDAD AN&Oacute;NIMA </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;width:3.41in;line-height:0.27in;"><DIV style="position:relative; left:1.20in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;width:3.41in;line-height:0.27in;"><DIV style="position:relative; left:2.36in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"></span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">4TA. </span></SPAN><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:0.33in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Avenida </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:0.86in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">23</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">-</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">80, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.28in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">zona </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.62in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">14 </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Guatemala</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:1.25in;left:8.07in;width:0.85in;line-height:0.23in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:effrarg;color:#000000">RECIBO DE CAJA</span></SPAN><br/></div>
        <img style="position:absolute;top:1.19in;left:8.95in;width:0.78in;height:0.47in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_3.png').'" />
        <div style="position:absolute;top:1.19in;left:9.06in;width:2.5in;line-height:0.39in;"><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Times New Roman;color:#ff0000">No</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Times New Roman;color:#ff0000">. '.$noRecibo.'</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Times New Roman;color:#ff0000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.24in;left:0.49in;width:10.94in;height:0.32in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_1.png').'" />
        <img style="position:absolute;top:2.24in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_2.png').'" />
        <div style="position:absolute;top:2.31in;left:0.62in;width:1.06in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">RECIBIMOS </span></SPAN><br/></div>
        <div style="position:absolute;top:2.31in;left:0.62in;width:10.66in;line-height:0.17in;"><DIV style="position:relative; left:0.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$nombreCompleto.' </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:2.27in;left:1.56in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_1.png').'" />
        <img style="position:absolute;top:1.82in;left:0.52in;width:7.07in;height:0.34in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_3.png').'" />
        <div style="position:absolute;top:1.90in;left:0.66in;width:6.8in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">FECHA: </span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$fecha.'</span><br/></SPAN></div>
        <img style="position:absolute;top:1.85in;left:1.07in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_2.png').'" />
        <img style="position:absolute;top:1.81in;left:7.59in;width:3.84in;height:0.34in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_4.png').'" />
        <div style="position:absolute;top:1.86in;left:7.73in;width:3.58in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MONTO:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$monto.' </span><br/></SPAN></div>
        <img style="position:absolute;top:1.84in;left:8.18in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_3.png').'" />
        <img style="position:absolute;top:2.56in;left:0.49in;width:10.93in;height:0.32in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_4.png').'" />
        <img style="position:absolute;top:2.56in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_5.png').'" />
        <div style="position:absolute;top:2.62in;left:0.61in;width:1.20in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">LA </span></SPAN><br/></div>
        <div style="position:absolute;top:2.62in;left:0.61in;width:1.20in;line-height:0.17in;"><DIV style="position:relative; left:0.20in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CANTIDAD </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:2.62in;left:0.61in;width:10.60in;line-height:0.17in;"><DIV style="position:relative; left:0.94in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$montoLetras.'</span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:2.57in;left:1.68in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_4.png').'" />
        <img style="position:absolute;top:2.88in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_5.png').'" />
        <img style="position:absolute;top:2.95in;left:0.51in;width:10.90in;height:0.19in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_5.png').'" />
        <img style="position:absolute;top:2.88in;left:11.13in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_6.png').'" />
        <img style="position:absolute;top:3.32in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_6.png').'" />
        <div style="position:absolute;top:3.39in;left:0.62in;width:1.40in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">POR </span></SPAN><br/></div>
        <div style="position:absolute;top:3.39in;left:0.62in;width:1.40in;line-height:0.17in;"><DIV style="position:relative; left:0.33in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CONCEPTO </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:3.39in;left:0.62in;width:10.60in;line-height:0.17in;"><DIV style="position:relative; left:1.14in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$concepto.'</span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:3.35in;left:1.90in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_7.png').'" />
        <img style="position:absolute;top:3.64in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_6.png').'" />
        ';
        if($rTmp->proyecto=='Naos'){
            $texto_r .= '<div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">PAGO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:0.43in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">POR </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:0.76in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CONCEPTO </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:1.57in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:1.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MUEBLES </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:2.48in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:2.71in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">COCINA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        <div style="position:absolute;top:3.72in;left:5.97in;width:2.21in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">ABONO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.72in;left:5.97in;width:2.21in;line-height:0.17in;"><DIV style="position:relative; left:0.53in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Q.____________________</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        <div style="position:absolute;top:3.72in;left:8.65in;width:2.22in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">SALDO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.72in;left:8.65in;width:2.22in;line-height:0.17in;"><DIV style="position:relative; left:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Q. </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:8.65in;width:2.22in;line-height:0.17in;"><DIV style="position:relative; left:0.68in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">____________________</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        ';
        }
        
        $texto_r .= '<img style="position:absolute;top:3.70in;left:10.75in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_8.png').'" />
        <img style="position:absolute;top:3.96in;left:0.49in;width:3.68in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_7.png').'" />
        <div style="position:absolute;top:4.04in;left:0.61in;width:0.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">RESERVA:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.03in;left:1.25in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_9.png').'" />
        <img style="position:absolute;top:3.96in;left:4.16in;width:3.68in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_8.png').'" />
        <div style="position:absolute;top:4.04in;left:4.28in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">ENGANCHE:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.99in;left:5.04in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <div style="position:absolute;top:4.04in;left:5.10in;width:0.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:effrarg;color:#000000">X</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.96in;left:7.83in;width:3.61in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_9.png').'" />
        <div style="position:absolute;top:4.04in;left:7.95in;width:1.06in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CANCELACI&Oacute;N:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.99in;left:8.92in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:4.28in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_10.png').'" />
        <div style="position:absolute;top:4.37in;left:0.61in;width:10.11in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">APARTAMENTO:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$apartamento.' </span><br/></SPAN></div>
        
        <img style="position:absolute;top:4.61in;left:0.49in;width:4.01in;height:0.65in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_11.png').'" />
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">N</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">O</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">.</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN><br/></div>
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.16in;"><DIV style="position:relative; left:0.25in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.17in;"><DIV style="position:relative; left:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CHEQUE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.68in;left:0.61in;width:3.60in;line-height:0.50in;"><DIV style="position:relative; left:0in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$noDeposito.'</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.70in;left:2.22in;width:1.65in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">N</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">O</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">.</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN><br/></div>
        <div style="position:absolute;top:4.70in;left:2.22in;width:1.65in;line-height:0.16in;"><DIV style="position:relative; left:0.25in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.70in;left:2.22in;width:1.65in;line-height:0.17in;"><DIV style="position:relative; left:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">TRANSFERENCIA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:4.60in;left:4.49in;width:4.03in;height:0.66in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_12.png').'" />
        <div style="position:absolute;top:4.70in;left:4.61in;width:0.56in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">BANCO:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.68in;left:4.61in;width:3.70in;line-height:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$banco.'</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.61in;left:8.52in;width:2.92in;height:0.65in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_13.png').'" />
        <div style="position:absolute;top:4.70in;left:8.64in;width:0.67in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MONEDA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.94in;left:8.64in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">QUETZALES</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.91in;left:9.55in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <div style="position:absolute;top:4.96in;left:9.60in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:effrarg;color:#000000">X</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.94in;left:10.25in;width:0.69in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DOLARES</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.44in;left:6.28in;width:4.62in;height:0.74in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_7.png').'" />
        <img style="position:absolute;top:5.44in;left:0.91in;width:4.62in;height:0.74in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_8.png').'" />
        <div style="position:absolute;top:5.68in;left:1in;width:4.50in;line-height:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$codigoRecibo.' </span></SPAN><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">FIRMA </span></SPAN><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.13in;"><DIV style="position:relative; left:0.36in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">DEL </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.13in;"><DIV style="position:relative; left:0.59in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">VENDEDOR </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.90in;left:2.27in;width:1.94in;line-height:0.16in;"><DIV style="position:relative; left:1.22in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">/</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.14in;"><DIV style="position:relative; left:1.29in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">COBRADOR</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:0.25in;left:0.54in;width:2.33in;height:0.95in" src="'.fcnBase64($logo).'" />
        <img style="position:absolute;top:2.57in;left:1.77in;width:9.65in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_1.png').'" />
        <img style="position:absolute;top:3.65in;left:6.64in;width:1.51in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_2.png').'" />
        <img style="position:absolute;top:3.65in;left:9.33in;width:1.50in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_3.png').'" />
        <img style="position:absolute;top:4.86in;left:0.51in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_4.png').'" />
        <img style="position:absolute;top:4.86in;left:4.53in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_5.png').'" />
        <img style="position:absolute;top:1.83in;left:8.27in;width:3.11in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_6.png').'" />
        <img style="position:absolute;top:1.83in;left:1.15in;width:6.40in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_7.png').'" />

        <img style="position:absolute;top:3.32in;left:1.99in;width:9.42in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_9.png').'" />
        ';
        if($rTmp->proyecto=='Naos'){
            $texto_r .= '<img style="position:absolute;top:3.67in;left:3.89in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />';
        }
        
        $texto_r .= '
        <img style="position:absolute;top:3.99in;left:1.46in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:1.29in;left:9.49in;width:1.87in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_14.png').'" />
        <img style="position:absolute;top:4.29in;left:1.69in;width:9.72in;height:0.31in" src="" />
        
        <img style="position:absolute;top:4.91in;left:10.98in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_17.png').'" />
        <div style="position:absolute;top:7.28in;left:7.88in;width:5.87in;height:0.31in"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#ff0000">'.$fechaActual.'</span><br/></SPAN></div>
        
        <img style="position:absolute;top:5.56in;left:1.22in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_19.png').'" />
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">hecho </span></SPAN><br/></div>
        </body>
    </html>';
    $dompdf->load_html(($texto_r));

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('legal', 'landscape');

    // Render the HTML as PDF
    $dompdf->render();

     //Guardar PDF en servidor
     $_path = "../public/";
     $pdfD = $dompdf->output();
     file_put_contents($_path."recibo_extra_".$idPago.".pdf", $pdfD);
     $codigo = "recibo_extra_".$idPago.".pdf";
     $strQuery = "   SELECT *
                     FROM  archivo 
                     WHERE id_cliente = {$intIdOcaCliente}
                     AND estado = 1
                     AND nombre = 'recibo_extra_{$idPago}.pdf'   ";
     $qTmp = $conn->db_query($strQuery);
     if ($conn->db_num_rows($qTmp) <= 0) {
         $strQuery = "   INSERT INTO  archivo (id_cliente, id_tipo_documento, codigo, tipo, nombre)
                         VALUES ({$intIdOcaCliente}, 9, '{$codigo}', 'pdf', '{$codigo}')";
         $conn->db_query($strQuery);
     }
     $filename = $_path."recibo_extra_".$idPago.".pdf"; // el nombre con el que se descargará, puede ser diferente al original 
     $nombre = "recibo_extra_".$idPago.".pdf";
     header("Content-type: application/octet-stream"); 
     header("Content-Type: application/force-download"); 
     header("Content-Disposition: attachment; filename=\"$nombre\"\n"); 
     readfile($filename);
    

    // Output the generated PDF to Browser
    // Enviamos el fichero PDF al navegador.
    //$dompdf->stream($nombre);
    
}
if(isset($_GET['reciboExtraEngPdf'])){
    $idPago=$_GET['idPago'];
    $strQueryPdf = "SELECT ce.idEnganche, ce.idUsuarioPago, e.idVendedor,e.idCliente, idCobro,fechaPagoRealizado as fechaPago,montoPagado,noDeposito,bancoDeposito,noPago,e.apartamento,e.proyecto,noRecibo,
    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),
    IFNULL(CONCAT(primerApellido,' '),''),IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as nombreCliente  
    FROM  cobrosExtras ce
    INNER JOIN  enganche e ON ce.idEnganche = e.idEnganche
    INNER JOIN  agregarCliente ag ON e.idCliente = ag.idCliente   
    where idCobro ={$idPago}";

    //echo $strQueryPdf;
    $qTmp = $conn ->db_query($strQueryPdf);
    $rTmp = $conn->db_fetch_object($qTmp);    
    $montoCocina=0;
    if($rTmp->proyecto=='Marabi'){
        $logo = '../img/logo Marabi.png';
       
    }else if($rTmp->proyecto=='Naos'){
        $logo = '../img/logo naos.png';
    }
    if($rTmp->cocina=='Sin cocina' || $rTmp->cocina==''){
        $cocina = 'No';
    }else{
        $cocina = 'Si';
    }
    $proyecto = strtoupper($rTmp->proyecto);
    $intIdOcaCliente=$rTmp->idCliente;
    $meses = array(1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $montoLetras=monto_letras($rTmp->montoPagado);
  
        $fecha= date('d',strtotime($rTmp->fechaPago)).' De '.$meses[date('n',strtotime($rTmp->fechaPago))].' De '.date('Y',strtotime($rTmp->fechaPago));
        $noRecibo=$rTmp->noRecibo==''?$rTmp->idCobro.$rTmp->idCobro.$rTmp->noPago:$rTmp->noRecibo;
        $nombreCompleto=$rTmp->nombreCliente;
        $monto='Q.'.number_format($rTmp->montoPagado,2,".",",");
        
        $montoLetras=$montoLetras;
        $concepto='Pago enganche No.'.$rTmp->noPago;
        $cocina='no';
        $montoCocina='';
        $saldoCocina='';
        $tipoPago='enganche';
        $apartamento=$rTmp->apartamento;
        $noDeposito=$rTmp->noDeposito;
        $banco=$rTmp->bancoDeposito;
        $moneda='quetzales';
        $fechaActual='Recibo generado con fecha '.date('d/m/Y H:i:s');
        $codigoRecibo=$rTmp->idUsuarioPago.'-'.$rTmp->idVendedor.'-'.date('Ymd',strtotime($rTmp->fechaPago)).'-'.$rTmp->idCobro;
        $nombre = normalizarNombre($nombreCompleto)."_".$codigoRecibo.".pdf";
    // instantiate and use the dompdf class
  
    $dompdf = new Dompdf();
    $font = \FontLib\Font::load('Effra_Std_Rg.ttf');
    $font->parse();  // for getFontWeight() to work this call must be done first!
    echo $font->getFontName() .'<br>';
    echo $font->getFontSubfamily() .'<br>';
    echo $font->getFontSubfamilyID() .'<br>';
    echo $font->getFontFullName() .'<br>';
    echo $font->getFontVersion() .'<br>';
    echo $font->getFontWeight() .'<br>';
    echo $font->getFontPostscriptName() .'<br>';
    $font->close();
    $texto_r='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style>
                    @font-face {
                        font-family: effrarg;
                        src: url("Effra_Std_Lt.ttf");
                    }
                    @font-face {
                        font-family: effrargB;
                        src: url("Effra_Std_Rg.ttf");
                        font-weight: bold;
                    }
            </style>
        </head>
        <body>
        <img style="position:absolute;top:5.47in;left:6.30in;width:4.60in;height:0.70in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_1.png').'" />
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">El </span></SPAN><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.14in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">pago </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.45in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.65in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">medio </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.02in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.19in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cheque </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.62in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">est&aacute; </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.88in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">sujeto </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.24in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.34in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.47in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">condici&oacute;n </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.01in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.18in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">que </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.42in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.55in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">mismo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.49in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.94in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">sea</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.37in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">efectivo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.82in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.92in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">su </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.08in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">presentaci&oacute;n, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.84in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">si </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.96in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.09in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cheque </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.52in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">es </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.68in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">rechazado </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.28in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.49in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.62in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">falta </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.88in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">fondos </span></SPAN><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.40in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">o </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.71in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cualquier </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.23in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">otra </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.48in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">causa, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.00in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">empresa </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">se </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.66in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">reserva </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.10in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.23in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">derecho </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.70in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.79in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">proceder</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">acuerdo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.64in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.74in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">lo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">establecido </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.52in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.73in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.85in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">ley </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.04in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">(C&oacute;digo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.67in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">Comercio, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.26in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">C&oacute;digo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.94in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.67in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">permitido).</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:6.19in;left:1.00in;width:4.42in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_2.png').'" />
        <div style="position:absolute;top:6.21in;left:1.11in;width:4.14in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:8pt;font-family:effrarg;color:#000000">ORGINAL; Cliente - DUPLICADO; Contabilidad - TRIPLICADO - Archivo</span></SPAN><br/></div>
        <div style="position:absolute;top:6.21in;left:1.11in;width:4.14in;line-height:0.17in;"><DIV style="position:relative; left:0.63in;"><span style="font-style:normal;font-weight:bold;font-size:8pt;font-family:effrarg;color:#000000"></span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;width:3.41in;line-height:0.27in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">SPV </span></SPAN><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;line-height:0.27in;"><DIV style="position:relative; left:0.48in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$proyecto.', SOCIEDAD AN&Oacute;NIMA </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;width:3.41in;line-height:0.27in;"><DIV style="position:relative; left:1.20in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.25in;left:0.55in;width:3.41in;line-height:0.27in;"><DIV style="position:relative; left:2.36in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"></span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">4TA. </span></SPAN><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:0.33in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Avenida </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:0.86in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">23</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">-</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">80, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.28in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">zona </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.62in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">14 </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.49in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Guatemala</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:1.25in;left:8.07in;width:0.85in;line-height:0.23in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:effrarg;color:#000000">RECIBO DE CAJA</span></SPAN><br/></div>
        <img style="position:absolute;top:1.19in;left:8.95in;width:0.78in;height:0.47in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_3.png').'" />
        <div style="position:absolute;top:1.19in;left:9.06in;width:2.5in;line-height:0.39in;"><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Times New Roman;color:#ff0000">No</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Times New Roman;color:#ff0000">. '.$noRecibo.'</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Times New Roman;color:#ff0000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.24in;left:0.49in;width:10.94in;height:0.32in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_1.png').'" />
        <img style="position:absolute;top:2.24in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_2.png').'" />
        <div style="position:absolute;top:2.31in;left:0.62in;width:1.06in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">RECIBIMOS </span></SPAN><br/></div>
        <div style="position:absolute;top:2.31in;left:0.62in;width:10.66in;line-height:0.17in;"><DIV style="position:relative; left:0.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$nombreCompleto.' </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:2.27in;left:1.56in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_1.png').'" />
        <img style="position:absolute;top:1.82in;left:0.52in;width:7.07in;height:0.34in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_3.png').'" />
        <div style="position:absolute;top:1.90in;left:0.66in;width:6.8in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">FECHA: </span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$fecha.'</span><br/></SPAN></div>
        <img style="position:absolute;top:1.85in;left:1.07in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_2.png').'" />
        <img style="position:absolute;top:1.81in;left:7.59in;width:3.84in;height:0.34in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_4.png').'" />
        <div style="position:absolute;top:1.86in;left:7.73in;width:3.58in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MONTO:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$monto.' </span><br/></SPAN></div>
        <img style="position:absolute;top:1.84in;left:8.18in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_3.png').'" />
        <img style="position:absolute;top:2.56in;left:0.49in;width:10.93in;height:0.32in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_4.png').'" />
        <img style="position:absolute;top:2.56in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_5.png').'" />
        <div style="position:absolute;top:2.62in;left:0.61in;width:1.20in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">LA </span></SPAN><br/></div>
        <div style="position:absolute;top:2.62in;left:0.61in;width:1.20in;line-height:0.17in;"><DIV style="position:relative; left:0.20in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CANTIDAD </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:2.62in;left:0.61in;width:10.60in;line-height:0.17in;"><DIV style="position:relative; left:0.94in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$montoLetras.'</span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:2.57in;left:1.68in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_4.png').'" />
        <img style="position:absolute;top:2.88in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_5.png').'" />
        <img style="position:absolute;top:2.95in;left:0.51in;width:10.90in;height:0.19in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_5.png').'" />
        <img style="position:absolute;top:2.88in;left:11.13in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_6.png').'" />
        <img style="position:absolute;top:3.32in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_6.png').'" />
        <div style="position:absolute;top:3.39in;left:0.62in;width:1.40in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">POR </span></SPAN><br/></div>
        <div style="position:absolute;top:3.39in;left:0.62in;width:1.40in;line-height:0.17in;"><DIV style="position:relative; left:0.33in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CONCEPTO </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:3.39in;left:0.62in;width:10.60in;line-height:0.17in;"><DIV style="position:relative; left:1.14in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$concepto.'</span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:3.35in;left:1.90in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_7.png').'" />
        <img style="position:absolute;top:3.64in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_6.png').'" />
        ';
        if($rTmp->proyecto=='Naos'){
            $texto_r .= '<div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">PAGO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:0.43in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">POR </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:0.76in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CONCEPTO </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:1.57in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:1.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MUEBLES </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:2.48in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:2.71in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">COCINA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        <div style="position:absolute;top:3.72in;left:5.97in;width:2.21in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">ABONO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.72in;left:5.97in;width:2.21in;line-height:0.17in;"><DIV style="position:relative; left:0.53in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Q.____________________</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        <div style="position:absolute;top:3.72in;left:8.65in;width:2.22in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">SALDO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.72in;left:8.65in;width:2.22in;line-height:0.17in;"><DIV style="position:relative; left:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Q. </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.72in;left:8.65in;width:2.22in;line-height:0.17in;"><DIV style="position:relative; left:0.68in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">____________________</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        ';
        }
        
        $texto_r .= '<img style="position:absolute;top:3.70in;left:10.75in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_8.png').'" />
        <img style="position:absolute;top:3.96in;left:0.49in;width:3.68in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_7.png').'" />
        <div style="position:absolute;top:4.04in;left:0.61in;width:0.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">RESERVA:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.03in;left:1.25in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_9.png').'" />
        <img style="position:absolute;top:3.96in;left:4.16in;width:3.68in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_8.png').'" />
        <div style="position:absolute;top:4.04in;left:4.28in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">ENGANCHE:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.99in;left:5.04in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <div style="position:absolute;top:4.04in;left:5.10in;width:0.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:effrarg;color:#000000">X</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.96in;left:7.83in;width:3.61in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_9.png').'" />
        <div style="position:absolute;top:4.04in;left:7.95in;width:1.06in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CANCELACI&Oacute;N:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.99in;left:8.92in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:4.28in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_10.png').'" />
        <div style="position:absolute;top:4.37in;left:0.61in;width:10.11in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">APARTAMENTO:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$apartamento.' </span><br/></SPAN></div>
        
        <img style="position:absolute;top:4.61in;left:0.49in;width:4.01in;height:0.65in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_11.png').'" />
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">N</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">O</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">.</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN><br/></div>
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.16in;"><DIV style="position:relative; left:0.25in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.17in;"><DIV style="position:relative; left:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CHEQUE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.68in;left:0.61in;width:3.60in;line-height:0.50in;"><DIV style="position:relative; left:0in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$noDeposito.'</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.70in;left:0.61in;width:1.11in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.70in;left:2.22in;width:1.65in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">N</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">O</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">.</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN><br/></div>
        <div style="position:absolute;top:4.70in;left:2.22in;width:1.65in;line-height:0.16in;"><DIV style="position:relative; left:0.25in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.70in;left:2.22in;width:1.65in;line-height:0.17in;"><DIV style="position:relative; left:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">TRANSFERENCIA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:4.60in;left:4.49in;width:4.03in;height:0.66in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_12.png').'" />
        <div style="position:absolute;top:4.70in;left:4.61in;width:0.56in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">BANCO:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.68in;left:4.61in;width:3.70in;line-height:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$banco.'</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.61in;left:8.52in;width:2.92in;height:0.65in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_13.png').'" />
        <div style="position:absolute;top:4.70in;left:8.64in;width:0.67in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MONEDA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.94in;left:8.64in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">QUETZALES</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.91in;left:9.55in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <div style="position:absolute;top:4.96in;left:9.60in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:effrarg;color:#000000">X</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.94in;left:10.25in;width:0.69in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DOLARES</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.44in;left:6.28in;width:4.62in;height:0.74in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_7.png').'" />
        <img style="position:absolute;top:5.44in;left:0.91in;width:4.62in;height:0.74in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_8.png').'" />
        <div style="position:absolute;top:5.68in;left:1in;width:4.50in;line-height:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$codigoRecibo.' </span></SPAN><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">FIRMA </span></SPAN><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.13in;"><DIV style="position:relative; left:0.36in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">DEL </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.13in;"><DIV style="position:relative; left:0.59in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">VENDEDOR </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.90in;left:2.27in;width:1.94in;line-height:0.16in;"><DIV style="position:relative; left:1.22in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">/</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:2.27in;width:1.94in;line-height:0.14in;"><DIV style="position:relative; left:1.29in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">COBRADOR</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:0.25in;left:0.54in;width:2.33in;height:0.95in" src="'.fcnBase64($logo).'" />
        <img style="position:absolute;top:2.57in;left:1.77in;width:9.65in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_1.png').'" />
        <img style="position:absolute;top:3.65in;left:6.64in;width:1.51in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_2.png').'" />
        <img style="position:absolute;top:3.65in;left:9.33in;width:1.50in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_3.png').'" />
        <img style="position:absolute;top:4.86in;left:0.51in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_4.png').'" />
        <img style="position:absolute;top:4.86in;left:4.53in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_5.png').'" />
        <img style="position:absolute;top:1.83in;left:8.27in;width:3.11in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_6.png').'" />
        <img style="position:absolute;top:1.83in;left:1.15in;width:6.40in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_7.png').'" />

        <img style="position:absolute;top:3.32in;left:1.99in;width:9.42in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_9.png').'" />
        ';
        if($rTmp->proyecto=='Naos'){
            $texto_r .= '<img style="position:absolute;top:3.67in;left:3.89in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />';
        }
        
        $texto_r .= '
        <img style="position:absolute;top:3.99in;left:1.46in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:1.29in;left:9.49in;width:1.87in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_14.png').'" />
        <img style="position:absolute;top:4.29in;left:1.69in;width:9.72in;height:0.31in" src="" />
        
        <img style="position:absolute;top:4.91in;left:10.98in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_17.png').'" />
        <div style="position:absolute;top:7.28in;left:7.88in;width:5.87in;height:0.31in"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#ff0000">'.$fechaActual.'</span><br/></SPAN></div>
        
        <img style="position:absolute;top:5.56in;left:1.22in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_19.png').'" />
        <div style="position:absolute;top:5.64in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">hecho </span></SPAN><br/></div>
        </body>
    </html>';
    $dompdf->load_html(($texto_r));

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('legal', 'landscape');

    // Render the HTML as PDF
    $dompdf->render();

     //Guardar PDF en servidor
     $_path = "../public/";
     $pdfD = $dompdf->output();
     file_put_contents($_path."recibo_extra_enganche_".$idPago.".pdf", $pdfD);
     $codigo = "recibo_extra_enganche_".$idPago.".pdf";
     $strQuery = "   SELECT *
                     FROM  archivo 
                     WHERE id_cliente = {$intIdOcaCliente}
                     AND estado = 1
                     AND nombre = 'recibo_extra_enganche_{$idPago}.pdf'   ";
     $qTmp = $conn->db_query($strQuery);
     if ($conn->db_num_rows($qTmp) <= 0) {
         $strQuery = "   INSERT INTO  archivo (id_cliente, id_tipo_documento, codigo, tipo, nombre)
                         VALUES ({$intIdOcaCliente}, 9, '{$codigo}', 'pdf', '{$codigo}')";
         $conn->db_query($strQuery);
     }
     $filename = $_path."recibo_extra_enganche_".$idPago.".pdf"; // el nombre con el que se descargará, puede ser diferente al original 
     $nombre = "recibo_extra_enganche_".$idPago.".pdf";
     header("Content-type: application/octet-stream"); 
     header("Content-Type: application/force-download"); 
     header("Content-Disposition: attachment; filename=\"$nombre\"\n"); 
     readfile($filename);
    

    // Output the generated PDF to Browser
    // Enviamos el fichero PDF al navegador.
    //$dompdf->stream($nombre);
    
}

if(isset($_GET['estadoCuentaPdf'])){
    $idEnganche=$_GET['idEnganche'];
    $hoy=date('Y-m-d');
    $strQueryPdf = "SELECT CURDATE(), (SELECT ped.fechaPago FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) ORDER BY ped.fechaPago ASC LIMIT 1) as fechaParaPago,
    ac.codigo,e.apartamento, e.pagoPromesa,date_format(fechaPagoReserva, '%d/%m/%Y') as fechaPagoReservaFormat,e.noReciboReserva,e.MontoReserva,e.descuento_porcentual_monto,(dg.cambioDolar * a.precio) as precio,
                    ((dg.cambioDolar * dg.parqueoExtra)*e.parqueosExtras) as parqueoExtra,((dg.cambioDolar * a.bodega_precio) * bodegasExtras) as bodegaPrecio,e.enganchePorcMonto,
                    ifnull(date_format(pf.fechaPago, '%d-%m-%Y'),'') as fechaPagoFinalFormat,
                    (SELECT ifnull((SUM(case when accion ='adicionar' AND enganche=0 then monto else 0 end) -  SUM(case when accion ='descontar' AND enganche=0 then monto else 0 end)),0) as contracargo FROM  contrapagos cp where cp.idEnganche = e.idEnganche  ) contracargo,
					--(SELECT ifnull((SUM(case when accion ='sumar' AND enganche=1 then monto else 0 end) -  SUM(case when accion ='restar' AND enganche=1 then monto else 0 end)),0) as contracargo FROM  contrapagos cp where cp.idEnganche = e.idEnganche  ) contracargoEnganche,
                    -- 0 as contracargo,
					0 as contracargoEnganche,
                    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,e.proyecto,e.idCliente,
                    (SELECT SUM(monto)  FROM prograEngancheDetalle ped WHERE ped.idEnganche = e.idEnganche AND pagado=1 and validado = 1) as totalEnganche,
                    (SELECT SUM(montoPagado)  FROM cobrosExtras ce WHERE ce.idEnganche = e.idEnganche AND validado=1 AND tipoCobroExtra = 'enganche') as totalEngancheExtra,
                    (case 
                    when 
                    (SELECT DATEDIFF(NOW(),fechaPago) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) ORDER BY ped.fechaPago ASC LIMIT 1) <= 0 then 0
                    when (SELECT SUM(montoPagado) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1) >= ((e.enganchePorcMonto- e.MontoReserva)/e.pagosEnganche) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}') then 0
                    else  ((((e.enganchePorcMonto- e.MontoReserva)/e.pagosEnganche) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}') - (SELECT SUM(montoPagado) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1)))
                    end) as pagoPendiente
                FROM  enganche e
                INNER JOIN  apartamentos a ON e.apartamento = a.apartamento
                INNER JOIN  datosGlobales dg ON a.idProyecto = dg.idGlobal
                LEFT JOIN  pagoFinal pf ON e.idEnganche = pf.idEnganche
                INNER JOIN  agregarCliente ac ON e.idCliente = ac.idCliente 
                WHERE e.idEnganche = {$idEnganche}
                limit 1 ;";

            //echo $strQueryPdf;exit();
            $qTmp = $conn ->db_query($strQueryPdf);
            $rTmp = $conn->db_fetch_object($qTmp);
            if($rTmp->proyecto=='Marabi'){
                $logo = '../img/logo Marabi.png';
               
            }else if($rTmp->proyecto=='Naos'){
                $logo = '../img/logo naos.png';
            }
    $pagoFinal = $rTmp->contracargo + $rTmp->bodegaPrecio + $rTmp->parqueoExtra + $rTmp->precio - $rTmp->descuento_porcentual_monto - $rTmp->enganchePorcMonto ;
    $totalApartamento = $pagoFinal + $rTmp->enganchePorcMonto;
    $pagoFinal = $totalApartamento + $rTmp->contracargoEnganche - ($rTmp->totalEnganche + $rTmp->MontoReserva + $rTmp->totalEngancheExtra); 
    $porcentaje = 100 * (($rTmp->totalEnganche + $rTmp->MontoReserva + $rTmp->totalEngancheExtra)/$rTmp->enganchePorcMonto);

        $codigo=$rTmp->codigo;
        $nombreCliente=$rTmp->client_name;
        $idCliente=$rTmp->idCliente;
        $apartamento=$rTmp->apartamento;
        $precioVenta='Q.'.number_format($totalApartamento,2,".",",");
        $engancheTotal='Q.'.number_format($rTmp->enganchePorcMonto,2,".",",");
        $SaldoContraEntrega='Q.'.number_format($pagoFinal,2,".",",");
        $enganchePagado='Q.'.number_format($rTmp->totalEnganche + $rTmp->MontoReserva + $rTmp->totalEngancheExtra,2,".",",");
        $porcentajePagado=number_format($porcentaje,2,".",",").'%';
        $saldoEngancheFloat = $rTmp->enganchePorcMonto - ($rTmp->totalEnganche + $rTmp->MontoReserva + $rTmp->totalEngancheExtra);
        $saldoEnganche = $saldoEngancheFloat >= 0 ? $saldoEngancheFloat : 0;
        $saldoEnganche='Q.'.number_format($saldoEnganche,2,".",",");
        $saldoAlDia='Q.'.number_format($rTmp->pagoPendiente ,2,".",",");
        $fecha=date('d/m/Y');
        
       
    $dompdf = new Dompdf();
    $texto_r='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style>
                @page {
                    margin:0;padding:0; // you can set margin and padding 0 
                } 
                @font-face {
                    font-family: effrarg;
                    src: url("Effra_Std_Lt.ttf");
                }
                @font-face {
                    font-family: effrargB;
                    src: url("Effra_Std_Rg.ttf");
                    font-weight: bold;
                }
            </style>
    </head>
    <body>
    <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/ci_70.png').'" />
    <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/avalia-logo.png').'" />
    <div style="text-align: justify;position:absolute;top:10.50in;left:0.38in;width:7.90in;line-height:0.12in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:effrarg;color:#000000">Estimado cliente, adjunto encontrara su estado de cuenta. En caso de inconformidad con la información que aparece en este, documento comuníquese al PBX: 2375-7300 de lunes a viernes de 8:00 a 17:00 horas o comuníquese al correo info@avalia.gt</span></div>
    <div style="text-align: justify;position:absolute;top:11.00in;left:0.38in;width:7.90in;line-height:0.12in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:effrarg;color:#000000">En caso usted hubiera realizado el pago el día de hoy, el mismo se vera reflejado al momento de la acreditación de los fondos por parte del Banco en la cuenta bancaria.</span></div>
    <img style="position:absolute;top:0.04in;left:0.00in;width:9.29in;height:11.95in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/ri_1.png').'" />
    <img style="position:absolute;top:1.23in;left:0.49in;width:8.15in;height:2.59in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/ci_1.png').'" />
    <img style="position:absolute;top:1.29in;left:0.75in;width:1.53in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:1.25in;left:0.86in;width:0.56in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"></span><br/></SPAN></div>
    <div style="text-align: justify;position:absolute;top:1.25in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Código</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
    <div style="text-align: justify;position:absolute;top:1.53in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$codigo.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
    <img style="position:absolute;top:1.29in;left:2.26in;width:4.12in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:1.25in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Nombre Cliente </span></SPAN><br/></div>
    <div style="text-align: justify;position:absolute;top:1.53in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$nombreCliente.' </span></SPAN><br/></div>
    <img style="position:absolute;top:1.29in;left:6.40in;width:1.78in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:1.25in;left:6.51in;width:0.97in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Apartamento</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
    <div style="text-align: justify;position:absolute;top:1.53in;left:6.51in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$apartamento.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
    <img style="position:absolute;top:1.91in;left:0.75in;width:1.54in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:1.85in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Precio Venta </span></SPAN><br/></div>
    <div style="text-align: justify;position:absolute;top:2.15in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$precioVenta.' </span></SPAN><br/></div>
    <img style="position:absolute;top:1.91in;left:2.26in;width:4.12in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:1.85in;left:2.37in;width:1.17in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Enganche Total</span></SPAN><br/></div>
    <div style="text-align: justify;position:absolute;top:2.15in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$engancheTotal.'</span></SPAN><br/></div>
    <img style="position:absolute;top:1.91in;left:6.40in;width:1.78in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:1.85in;left:6.51in;width:1.57in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Saldo contra Entrega</span></SPAN><br/></div>
    <div style="text-align: justify;position:absolute;top:2.15in;left:6.51in;width:1.57in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$SaldoContraEntrega.'</span></SPAN><br/></div>
    <img style="position:absolute;top:2.52in;left:0.75in;width:1.55in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:2.45in;left:0.86in;width:1.35in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Enganche Pagado</span></SPAN><br/></div>
    <div style="text-align: justify;position:absolute;top:2.75in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$enganchePagado.'</span></SPAN><br/></div>
    <img style="position:absolute;top:2.52in;left:2.26in;width:4.12in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:2.45in;left:2.37in;width:1.41in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Porcentaje Pagado</span></SPAN><br/></div>
    <div style="text-align: justify;position:absolute;top:2.75in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$porcentajePagado.'</span></SPAN><br/></div>            
    <img style="position:absolute;top:2.52in;left:6.40in;width:1.78in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:2.45in;left:6.51in;width:1.51in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Pendiente Por Pagar</span></SPAN><br/></div>
    <div style="text-align: justify;position:absolute;top:2.75in;left:6.51in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$saldoEnganche.'</span></SPAN><br/></div>
    <img style="position:absolute;top:4.67in;left:0.41in;width:0.77in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:4.60in;left:0.52in;width:0.29in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">No.</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
    <img style="position:absolute;top:4.67in;left:1.64in;width:1.16in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:4.60in;left:1.75in;width:0.47in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Cuota</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
    <img style="position:absolute;top:4.66in;left:3.26in;width:1.10in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:4.60in;left:3.38in;width:0.48in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Fecha</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
    <img style="position:absolute;top:4.67in;left:4.97in;width:1.10in;height:0.33in" src="" />
    <div style="text-align: justify;position:absolute;top:4.60in;left:5.08in;width:0.83in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">No. </span></SPAN><br/></div>
    <div style="text-align: justify;position:absolute;top:4.60in;left:5.08in;width:0.83in;line-height:0.22in;"><DIV style="position:relative; left:0.29in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Recibo</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>

    <div style="text-align: justify;position:absolute;top:4.60in;left:7.06in;width:0.45in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Saldo</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>

    <div style="text-align: justify;position:absolute;top:3.95in;left:0.52in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#ff8c66">ESTADO DE CUENTA</span><br/></SPAN></div>

    <div style="text-align: justify;position:absolute;top:3.09in;left:0.86in;width:2.23in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Pendiente por pagar a la fecha</span></SPAN><br/></div>
    <div style="text-align: justify;position:absolute;top:3.40in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$saldoAlDia.'</span></SPAN><br/></div>

    <img style="position:absolute;top:1.54in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
    <img style="position:absolute;top:1.84in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
    <img style="position:absolute;top:2.14in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
    <img style="position:absolute;top:2.45in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
    <img style="position:absolute;top:2.76in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
    <img style="position:absolute;top:4.28in;left:0.50in;width:8.14in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
    <img style="position:absolute;top:4.91in;left:0.50in;width:8.14in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
    <img style="position:absolute;top:3.06in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
    <img style="position:absolute;top:3.39in;left:0.83in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
    
    <img style="position:absolute;top:0.57in;left:0.31in;width:1.165in;height:0.475in" src="'.fcnBase64($logo).'" />';
        $hoy=date('Y-m-d');        
        $strQuery = "SELECT ped.*,pe.montoEnganche,date_format(fechaPago, '%d/%m/%Y') as fechaPagoFormat,ifnull(date_format(fechaPagoRealizado, '%d/%m/%Y'),'') as fechaPagoRealizadoFormat 
                    FROM  prograEnganche pe
                    INNER JOIN  prograEngancheDetalle ped on pe.idEnganche = ped.idEnganche
                    where pe.idEnganche={$idEnganche}
                    AND (fechaPago < '{$hoy}' OR (pagado = 1 AND validado = 1))
                    ORDER BY ped.noPago";
        $top=4.73;
        $top=$top+0.18;
       
        $texto_r.='    
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:0.52in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">Reserva</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:1.75in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">Q.'.number_format($rTmp->MontoReserva,2,".",",").'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:3.38in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$rTmp->fechaPagoReservaFormat.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:5.08in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$rTmp->noReciboReserva.'</span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:7.06in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">Q.'.number_format($rTmp->enganchePorcMonto -$rTmp->MontoReserva,2,".",",").'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
    ';
        $qTmp = $conn ->db_query($strQuery);
        $engancheResta = $rTmp->enganchePorcMonto -$rTmp->MontoReserva;
        $acumulado = 0;
        $salto_pagina = 0;
     while ($rTmp = $conn->db_fetch_object($qTmp)){
        $salto_pagina ++;
        if($rTmp->pagado == 1 && $rTmp->validado == 1){
            $fechaPago=$rTmp->fechaPagoRealizadoFormat;
            $color='#000000'; 
            $noRecibo=$rTmp->noRecibo==''?$rTmp->idDetalle:$rTmp->noRecibo;
            $acumulado+=$rTmp->monto;
        }else{
            $fechaPago=$rTmp->fechaPagoFormat;
            $color='#FA0404';
            $noRecibo='Pago Pendiente';
        }
         $top=$top+0.18;
        
         if($salto_pagina >=28){
            $salto_pagina = 0;
            $texto_r.='        
            <div style="page-break-after: always;">
        </div>
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/ci_70.png').'" />
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/avalia-logo.png').'" />
            <div style="text-align: justify;position:absolute;top:10.50in;left:0.38in;width:7.90in;line-height:0.12in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:effrarg;color:#000000">Estimado cliente, adjunto encontrara su estado de cuenta. En caso de inconformidad con la información que aparece en este, documento comuníquese al PBX: 2375-7300 de lunes a viernes de 8:00 a 17:00 horas o comuníquese al correo info@avalia.gt</span></div>
            <div style="text-align: justify;position:absolute;top:11.00in;left:0.38in;width:7.90in;line-height:0.12in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:effrarg;color:#000000">En caso usted hubiera realizado el pago el día de hoy, el mismo se vera reflejado al momento de la acreditación de los fondos por parte del Banco en la cuenta bancaria.</span></div>
            <img style="position:absolute;top:0.04in;left:0.00in;width:9.29in;height:11.95in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/ri_1.png').'" />
            <img style="position:absolute;top:1.23in;left:0.49in;width:8.15in;height:2.59in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/ci_1.png').'" />
            <img style="position:absolute;top:1.29in;left:0.75in;width:1.53in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.25in;left:0.86in;width:0.56in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"></span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.25in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Código</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.53in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$codigo.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.29in;left:2.26in;width:4.12in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.25in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Nombre Cliente </span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:1.53in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$nombreCliente.' </span></SPAN><br/></div>
            <img style="position:absolute;top:1.29in;left:6.40in;width:1.78in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.25in;left:6.51in;width:0.97in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Apartamento</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.53in;left:6.51in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$apartamento.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.91in;left:0.75in;width:1.54in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.85in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Precio Venta </span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.15in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$precioVenta.' </span></SPAN><br/></div>
            <img style="position:absolute;top:1.91in;left:2.26in;width:4.12in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.85in;left:2.37in;width:1.17in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Enganche Total</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.15in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$engancheTotal.'</span></SPAN><br/></div>
            <img style="position:absolute;top:1.91in;left:6.40in;width:1.78in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.85in;left:6.51in;width:1.57in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Saldo contra Entrega</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.15in;left:6.51in;width:1.57in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$SaldoContraEntrega.'</span></SPAN><br/></div>
            <img style="position:absolute;top:2.52in;left:0.75in;width:1.55in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:2.45in;left:0.86in;width:1.35in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Enganche Pagado</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.75in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$enganchePagado.'</span></SPAN><br/></div>
            <img style="position:absolute;top:2.52in;left:2.26in;width:4.12in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:2.45in;left:2.37in;width:1.41in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Porcentaje Pagado</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.75in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$porcentajePagado.'</span></SPAN><br/></div>            
            <img style="position:absolute;top:2.52in;left:6.40in;width:1.78in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:2.45in;left:6.51in;width:1.51in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Pendiente Por Pagar</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.75in;left:6.51in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$saldoEnganche.'</span></SPAN><br/></div>
            <img style="position:absolute;top:4.67in;left:0.41in;width:0.77in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:4.60in;left:0.52in;width:0.29in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">No.</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.67in;left:1.64in;width:1.16in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:4.60in;left:1.75in;width:0.47in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Cuota</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.66in;left:3.26in;width:1.10in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:4.60in;left:3.38in;width:0.48in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Fecha</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.67in;left:4.97in;width:1.10in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:4.60in;left:5.08in;width:0.83in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">No. </span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:4.60in;left:5.08in;width:0.83in;line-height:0.22in;"><DIV style="position:relative; left:0.29in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Recibo</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>

            <div style="text-align: justify;position:absolute;top:4.60in;left:7.06in;width:0.45in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Saldo</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>

            <div style="text-align: justify;position:absolute;top:3.95in;left:0.52in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#ff8c66">ESTADO DE CUENTA</span><br/></SPAN></div>

            <div style="text-align: justify;position:absolute;top:3.09in;left:0.86in;width:2.23in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Pendiente por pagar a la fecha</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:3.40in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$saldoAlDia.'</span></SPAN><br/></div>

            <img style="position:absolute;top:1.54in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:1.84in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:2.14in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:2.45in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:2.76in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:4.28in;left:0.50in;width:8.14in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:4.91in;left:0.50in;width:8.14in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:3.06in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:3.39in;left:0.83in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            
            <img style="position:absolute;top:0.57in;left:0.31in;width:1.165in;height:0.475in" src="'.fcnBase64($logo).'" />';
                $hoy=date('Y-m-d');        
                $strQuery = "SELECT ped.*,pe.montoEnganche,date_format(fechaPago, '%d/%m/%Y') as fechaPagoFormat,ifnull(date_format(fechaPagoRealizado, '%d/%m/%Y'),'') as fechaPagoRealizadoFormat 
                            FROM  prograEnganche pe
                            INNER JOIN  prograEngancheDetalle ped on pe.idEnganche = ped.idEnganche
                            where pe.idEnganche={$idEnganche}
                            AND (fechaPago < '{$hoy}' OR (pagado = 1 AND validado = 1))
                            ORDER BY ped.noPago";
                $top=4.73;
                $top=$top+0.18;
            
                
        }
        $texto_r.='    
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:0.52in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:'.$color.'">'.$rTmp->noPago.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:1.75in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:'.$color.'">Q.'.number_format($rTmp->monto,2,".",",").'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:3.38in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:'.$color.'">'.$fechaPago.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:5.08in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:'.$color.'">'.$noRecibo.'</span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:7.06in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:'.$color.'">Q.'.number_format($engancheResta - $acumulado,2,".",",").'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>';
    }
    $strQuery = "SELECT ce.*,ifnull(date_format(fechaPagoRealizado, '%d/%m/%Y'),'') as fechaPagoFormat,ifnull(date_format(fechaPagoRealizado, '%d/%m/%Y'),'') as fechaPagoRealizadoFormat 
                    FROM  cobrosExtras ce
                    where ce.idEnganche={$idEnganche}
                    AND tipoCobroExtra = 'enganche'
                    ORDER BY ce.noPago";
        $qTmp = $conn ->db_query($strQuery);
        
     while ($rTmp = $conn->db_fetch_object($qTmp)){
        $salto_pagina ++;
        if($rTmp->validado == 1){
            $fechaPago=$rTmp->fechaPagoRealizadoFormat;
            $color='#000000'; 
            $noRecibo=$rTmp->noRecibo==''?$rTmp->idEnganche.$rTmp->idEnganche.$rTmp->noPago:$rTmp->noRecibo;
            $acumulado+=$rTmp->montoPagado;
        }else{
            $fechaPago=$rTmp->fechaPagoFormat;
            $color='#FA0404';
            $noRecibo='Pago Pendiente';
        }
        $top=$top+0.18;
        
        if($salto_pagina >=28){
            $salto_pagina = 0;

            $texto_r.='<div style="page-break-after: always;"> </div>';
            $texto_r.='<img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/ci_70.png').'" />
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/avalia-logo.png').'" />
            <div style="text-align: justify;position:absolute;top:10.50in;left:0.38in;width:7.90in;line-height:0.12in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:effrarg;color:#000000">Estimado cliente, adjunto encontrara su estado de cuenta. En caso de inconformidad con la información que aparece en este, documento comuníquese al PBX: 2375-7300 de lunes a viernes de 8:00 a 17:00 horas o comuníquese al correo info@avalia.gt</span></div>
            <div style="text-align: justify;position:absolute;top:11.00in;left:0.38in;width:7.90in;line-height:0.12in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:effrarg;color:#000000">En caso usted hubiera realizado el pago el día de hoy, el mismo se vera reflejado al momento de la acreditación de los fondos por parte del Banco en la cuenta bancaria.</span></div>
            <img style="position:absolute;top:0.04in;left:0.00in;width:9.29in;height:11.95in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/ri_1.png').'" />
            <img style="position:absolute;top:1.23in;left:0.49in;width:8.15in;height:2.59in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/ci_1.png').'" />
            <img style="position:absolute;top:1.29in;left:0.75in;width:1.53in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.25in;left:0.86in;width:0.56in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"></span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.25in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Código</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.53in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$codigo.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.29in;left:2.26in;width:4.12in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.25in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Nombre Cliente </span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:1.53in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$nombreCliente.' </span></SPAN><br/></div>
            <img style="position:absolute;top:1.29in;left:6.40in;width:1.78in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.25in;left:6.51in;width:0.97in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Apartamento</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.53in;left:6.51in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$apartamento.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.91in;left:0.75in;width:1.54in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.85in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Precio Venta </span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.15in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$precioVenta.' </span></SPAN><br/></div>
            <img style="position:absolute;top:1.91in;left:2.26in;width:4.12in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.85in;left:2.37in;width:1.17in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Enganche Total</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.15in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$engancheTotal.'</span></SPAN><br/></div>
            <img style="position:absolute;top:1.91in;left:6.40in;width:1.78in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:1.85in;left:6.51in;width:1.57in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Saldo contra Entrega</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.15in;left:6.51in;width:1.57in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$SaldoContraEntrega.'</span></SPAN><br/></div>
            <img style="position:absolute;top:2.52in;left:0.75in;width:1.55in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:2.45in;left:0.86in;width:1.35in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Enganche Pagado</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.75in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$enganchePagado.'</span></SPAN><br/></div>
            <img style="position:absolute;top:2.52in;left:2.26in;width:4.12in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:2.45in;left:2.37in;width:1.41in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Porcentaje Pagado</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.75in;left:2.37in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$porcentajePagado.'</span></SPAN><br/></div>            
            <img style="position:absolute;top:2.52in;left:6.40in;width:1.78in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:2.45in;left:6.51in;width:1.51in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Pendiente Por Pagar</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:2.75in;left:6.51in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$saldoEnganche.'</span></SPAN><br/></div>
            <img style="position:absolute;top:4.67in;left:0.41in;width:0.77in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:4.60in;left:0.52in;width:0.29in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">No.</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.67in;left:1.64in;width:1.16in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:4.60in;left:1.75in;width:0.47in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Cuota</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.66in;left:3.26in;width:1.10in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:4.60in;left:3.38in;width:0.48in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Fecha</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.67in;left:4.97in;width:1.10in;height:0.33in" src="" />
            <div style="text-align: justify;position:absolute;top:4.60in;left:5.08in;width:0.83in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">No. </span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:4.60in;left:5.08in;width:0.83in;line-height:0.22in;"><DIV style="position:relative; left:0.29in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Recibo</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>

            <div style="text-align: justify;position:absolute;top:4.60in;left:7.06in;width:0.45in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Saldo</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>

            <div style="text-align: justify;position:absolute;top:3.95in;left:0.52in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#ff8c66">ESTADO DE CUENTA</span><br/></SPAN></div>

            <div style="text-align: justify;position:absolute;top:3.09in;left:0.86in;width:2.23in;line-height:0.22in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrargB;color:#000000">Pendiente por pagar a la fecha</span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:3.40in;left:0.86in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:#000000">'.$saldoAlDia.'</span></SPAN><br/></div>

            <img style="position:absolute;top:1.54in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:1.84in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:2.14in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:2.45in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:2.76in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:4.28in;left:0.50in;width:8.14in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:4.91in;left:0.50in;width:8.14in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:3.06in;left:0.84in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:3.39in;left:0.83in;width:7.40in;height:0.01in" src="'.fcnBase64('./SodaPDFEstadoCuentaAvalia/OutDocument/vi_19.png').'" />
            
            <img style="position:absolute;top:0.57in;left:0.31in;width:1.165in;height:0.475in" src="'.fcnBase64($logo).'" />';
                $hoy=date('Y-m-d');        
                $strQuery = "SELECT ped.*,pe.montoEnganche,date_format(fechaPago, '%d/%m/%Y') as fechaPagoFormat,ifnull(date_format(fechaPagoRealizado, '%d/%m/%Y'),'') as fechaPagoRealizadoFormat 
                            FROM  prograEnganche pe
                            INNER JOIN  prograEngancheDetalle ped on pe.idEnganche = ped.idEnganche
                            where pe.idEnganche={$idEnganche}
                            AND (fechaPago < '{$hoy}' OR (pagado = 1 AND validado = 1))
                            ORDER BY ped.noPago";
                $top=4.73;
                $top=$top+0.18;
            
               
        }
        $texto_r.='    
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:0.52in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:'.$color.'">'.$rTmp->noPago.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:1.75in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:'.$color.'">Q.'.number_format($rTmp->montoPagado,2,".",",").'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:3.38in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:'.$color.'">'.$fechaPago.'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:5.08in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:'.$color.'">'.$noRecibo.'</span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:'.$top.'in;left:7.06in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:effrarg;color:'.$color.'">Q.'.number_format($engancheResta - $acumulado,2,".",",").'</span><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
    ';

    }

    $texto_r.='</html>';
    $dompdf->load_html(($texto_r));

    // (Optional) Setup the paper size and orientation
    $customPaper = array(0,0,666.141,864.56);
    $dompdf->setPaper($customPaper);
    // Render the HTML as PDF
    $dompdf->render();

    //Guardar PDF en servidor
    $_path = "../public/";
    $pdfD = $dompdf->output();
    file_put_contents($_path."estado_cuenta_".$idEnganche.".pdf", $pdfD);
    $codigo = "estado_cuenta_".$idEnganche.".pdf";
    $strQuery = "   SELECT *
                    FROM  archivo 
                    WHERE id_cliente = {$idCliente}
                    AND estado = 1
                    AND nombre = 'estado_cuenta_{$idEnganche}.pdf'   ";
    $qTmp = $conn->db_query($strQuery);
    if ($conn->db_num_rows($qTmp) <= 0) {
        $strQuery = "   INSERT INTO  archivo (id_cliente, id_tipo_documento, codigo, tipo, nombre)
                        VALUES ({$idCliente}, 10, '{$codigo}', 'pdf', '{$codigo}')";
        $conn->db_query($strQuery);
    }
    $filename = $_path."estado_cuenta_".$idEnganche.".pdf"; // el nombre con el que se descargará, puede ser diferente al original 
    $nombre = "estado_cuenta_".$idEnganche.".pdf";
    header("Content-type: application/octet-stream"); 
    header("Content-Type: application/force-download"); 
    header("Content-Disposition: attachment; filename=\"$nombre\"\n"); 
    readfile($filename);
    // Output the generated PDF to Browser
    // Enviamos el fichero PDF al navegador.
    //$dompdf->stream();
}
if(isset($_GET['reservaPdf'])){
    $idPago=$_GET['idPago'];
    $strQueryPdf = "SELECT c.*,IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as nombreCompleto
                    FROM  enganche c
                    INNER JOIN  apartamentos a ON c.apartamento = a.apartamento
                    INNER JOIN  agregarCliente ag ON c.idCliente= ag.idCliente
                    WHERE c.idEnganche={$idPago};";

            $qTmp = $conn ->db_query($strQueryPdf);
            $rTmp = $conn->db_fetch_object($qTmp);
    $montoCocina=0;
    if($rTmp->proyecto=='Marabi'){
        $logo = '../img/logo Marabi.png';
       
    }else if($rTmp->proyecto=='Naos'){
        $logo = '../img/logo naos.png';
    }
    if($rTmp->cocina=='Sin cocina' || $rTmp->cocina==''){
        $cocina = 'No';
    }else{
        $cocina = 'Si';
    }
    $proyecto = strtoupper($rTmp->proyecto);
    $intIdOcaCliente=$rTmp->idCliente;
    $meses = array(1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $montoLetras=monto_letras($rTmp->MontoReserva);

        $fecha=date('d',strtotime($rTmp->fechaPagoReserva)).' De '.$meses[date('n',strtotime($rTmp->fechaPagoReserva))].' De '.date('Y',strtotime($rTmp->fechaPagoReserva));
        $noRecibo=$rTmp->noReciboReserva==''?$rTmp->idEnganche.$rTmp->idCliente.$rTmp->torres:$rTmp->noReciboReserva;
        $nombreCompleto=$rTmp->nombreCompleto;
        $monto='Q.'.number_format($rTmp->MontoReserva,2,".",",");
        $montoLetras=$montoLetras;
        $concepto='Pago de Reserva';
        $cocina='no';
        $montoCocina='';
        $saldoCocina='';
        $tipoPago='reserva';
        $apartamento=$rTmp->apartamento;
        $noDeposito=$rTmp->noDepositoReserva;
        $banco=$rTmp->bancoDepositoReserva;
        $moneda='quetzales';
        $fechaActual='Recibo generado con fecha '.date('d/m/Y H:i:s');
        $codigoRecibo=$rTmp->idVendedor.'-'.$rTmp->idVendedor.'-'.date('Ymd',strtotime($rTmp->fechaPagoReserva)).'-'.$rTmp->idEnganche.$rTmp->idCliente.$rTmp->torres;
        $nombre = normalizarNombre($nombreCompleto)."_".$codigoRecibo.".pdf";
            // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $texto_r='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link rel="stylesheet" type="text/css" href="../css/stylesRecibo.css"/>
            <style>
            @font-face {
                font-family: effrarg;
                src: url("Effra_Std_Lt.ttf");
            }
            @font-face {
                font-family: effrargB;
                src: url("Effra_Std_Rg.ttf");
                font-weight: bold;
            }
            </style>  
        </head>
        <body>
        <img style="position:absolute;top:5.47in;left:6.30in;width:4.60in;height:0.70in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_1.png').'" />
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">El </span></SPAN><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.14in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">pago </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.45in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.65in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">medio </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.02in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.19in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cheque </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.62in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">est&aacute; </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.88in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">sujeto </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.24in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.34in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.47in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">condici&oacute;n </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.01in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.18in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">que </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.42in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.55in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">mismo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.94in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">sea</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.37in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">efectivo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.82in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.92in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">su </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.08in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">presentaci&oacute;n, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.84in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">si </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.96in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.09in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cheque </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.52in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">es </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.68in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">rechazado </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.28in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.49in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.62in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">falta </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.88in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">fondos </span></SPAN><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.40in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">o </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.71in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cualquier </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.23in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">otra </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.48in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">causa, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.00in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">empresa </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">se </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.66in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">reserva </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.10in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.23in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">derecho </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.70in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">proceder</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">acuerdo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.64in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.74in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">lo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">establecido </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.52in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.73in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.85in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">ley </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.04in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">(C&oacute;digo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.67in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">Comercio, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.26in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">C&oacute;digo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.67in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">permitido).</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:6.19in;left:1.00in;width:4.42in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_2.png').'" />
        <div style="position:absolute;top:6.20in;left:1.11in;width:4.14in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:8pt;font-family:effrarg;color:#000000">ORGINAL; Cliente - DUPLICADO; Contabilidad - TRIPLICADO - Archivo</span></SPAN><br/></div>
        <div style="position:absolute;top:6.20in;left:1.11in;width:4.14in;line-height:0.17in;"><DIV style="position:relative; left:0.63in;"><span style="font-style:normal;font-weight:bold;font-size:8pt;font-family:effrarg;color:#000000"></span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.24in;left:0.55in;width:3.41in;line-height:0.27in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">SPV </span></SPAN><br/></div>
        <div style="position:absolute;top:1.24in;left:0.55in;line-height:0.27in;"><DIV style="position:relative; left:0.48in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$proyecto.', SOCIEDAD AN&Oacute;NIMA </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.24in;left:0.55in;width:3.41in;line-height:0.27in;"><DIV style="position:relative; left:1.20in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.24in;left:0.55in;width:3.41in;line-height:0.27in;"><DIV style="position:relative; left:2.36in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"></span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">4TA. </span></SPAN><br/></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:0.33in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Avenida </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:0.86in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">23</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">-</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">80, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.28in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">zona </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.62in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">14 </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Guatemala</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:1.24inin;left:8.07in;width:0.85in;line-height:0.23in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Calibri;color:#000000">RECIBO DE CAJA</span></SPAN><br/></div>
        <img style="position:absolute;top:1.19in;left:8.95in;width:0.78in;height:0.47in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_3.png').'" />
        <div style="position:absolute;top:1.18in;left:9.06in;width:2.5in;line-height:0.39in;"><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Times New Roman;color:#ff0000">No</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Times New Roman;color:#ff0000">. '.$noRecibo.'</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Times New Roman;color:#ff0000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.24in;left:0.49in;width:10.94in;height:0.32in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_1.png').'" />
        <img style="position:absolute;top:2.24in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_2.png').'" />
        <div style="position:absolute;top:2.30in;left:0.62in;width:1.06in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">RECIBIMOS </span></SPAN><br/></div>
        <div style="position:absolute;top:2.30in;left:0.62in;width:10.66in;line-height:0.17in;"><DIV style="position:relative; left:0.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$nombreCompleto.' </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:2.27in;left:1.56in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_1.png').'" />
        <img style="position:absolute;top:1.82in;left:0.52in;width:7.07in;height:0.34in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_3.png').'" />
        <div style="position:absolute;top:1.89in;left:0.66in;width:6.8in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">FECHA: </span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$fecha.'</span><br/></SPAN></div>
        <img style="position:absolute;top:1.85in;left:1.07in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_2.png').'" />
        <img style="position:absolute;top:1.81in;left:7.59in;width:3.84in;height:0.34in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_4.png').'" />
        <div style="position:absolute;top:1.85in;left:7.73in;width:3.58in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MONTO:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$monto.' </span><br/></SPAN></div>
        <img style="position:absolute;top:1.84in;left:8.18in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_3.png').'" />
        <img style="position:absolute;top:2.56in;left:0.49in;width:10.93in;height:0.32in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_4.png').'" />
        <img style="position:absolute;top:2.56in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_5.png').'" />
        <div style="position:absolute;top:2.61in;left:0.61in;width:1.20in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">LA </span></SPAN><br/></div>
        <div style="position:absolute;top:2.61in;left:0.61in;width:1.20in;line-height:0.17in;"><DIV style="position:relative; left:0.20in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CANTIDAD </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:2.61in;left:0.61in;width:10.60in;line-height:0.17in;"><DIV style="position:relative; left:0.94in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$montoLetras.'</span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:2.57in;left:1.68in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_4.png').'" />
        <img style="position:absolute;top:2.88in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_5.png').'" />
        <img style="position:absolute;top:2.95in;left:0.51in;width:10.90in;height:0.19in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_5.png').'" />
        <img style="position:absolute;top:2.88in;left:11.13in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_6.png').'" />
        <img style="position:absolute;top:3.32in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_6.png').'" />
        <div style="position:absolute;top:3.38in;left:0.62in;width:1.40in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">POR </span></SPAN><br/></div>
        <div style="position:absolute;top:3.38in;left:0.62in;width:1.40in;line-height:0.17in;"><DIV style="position:relative; left:0.33in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CONCEPTO </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:3.38in;left:0.62in;width:10.60in;line-height:0.17in;"><DIV style="position:relative; left:1.14in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$concepto.'</span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:3.35in;left:1.90in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_7.png').'" />
        <img style="position:absolute;top:3.64in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_6.png').'" />
        ';
        if($rTmp->proyecto=='Naos'){
            $texto_r .= '<div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">PAGO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:0.43in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">POR </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:0.76in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CONCEPTO </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:1.57in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:1.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MUEBLES </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:2.48in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:2.71in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">COCINA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        <div style="position:absolute;top:3.71in;left:5.97in;width:2.21in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">ABONO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.71in;left:5.97in;width:2.21in;line-height:0.17in;"><DIV style="position:relative; left:0.53in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Q.____________________</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        <div style="position:absolute;top:3.71in;left:8.65in;width:2.22in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">SALDO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.71in;left:8.65in;width:2.22in;line-height:0.17in;"><DIV style="position:relative; left:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Q. </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:8.65in;width:2.22in;line-height:0.17in;"><DIV style="position:relative; left:0.68in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">____________________</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        ';
        }
        
        $texto_r .= '<img style="position:absolute;top:3.67in;left:10.75in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_8.png').'" />
        <img style="position:absolute;top:3.96in;left:0.49in;width:3.68in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_7.png').'" />
        <div style="position:absolute;top:4.03in;left:0.61in;width:0.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">RESERVA:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        
        <img style="position:absolute;top:4.03in;left:1.25in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_9.png').'" />
        <img style="position:absolute;top:3.96in;left:4.16in;width:3.68in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_8.png').'" />
        
        <div style="position:absolute;top:4.03in;left:4.28in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">ENGANCHE:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.99in;left:5.04in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:3.96in;left:7.83in;width:3.61in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_9.png').'" />
        <div style="position:absolute;top:4.03in;left:7.95in;width:1.06in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CANCELACI&Oacute;N:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.99in;left:8.92in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:4.28in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_10.png').'" />
        <div style="position:absolute;top:4.36in;left:0.61in;width:10.11in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">APARTAMENTO:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$apartamento.' </span><br/></SPAN></div>
        
        <img style="position:absolute;top:4.61in;left:0.49in;width:4.01in;height:0.65in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_11.png').'" />
        <div style="position:absolute;top:4.69in;left:0.61in;width:1.11in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">N</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">O</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">.</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN><br/></div>
        <div style="position:absolute;top:4.69in;left:0.61in;width:1.11in;line-height:0.16in;"><DIV style="position:relative; left:0.25in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.69in;left:0.61in;width:1.11in;line-height:0.17in;"><DIV style="position:relative; left:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CHEQUE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.69in;left:0.61in;width:3.60in;line-height:0.50in;"><DIV style="position:relative; left:0in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$noDeposito.'</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.69in;left:0.61in;width:1.11in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.69in;left:2.22in;width:1.65in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">N</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">O</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">.</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN><br/></div>
        <div style="position:absolute;top:4.69in;left:2.22in;width:1.65in;line-height:0.16in;"><DIV style="position:relative; left:0.25in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.69in;left:2.22in;width:1.65in;line-height:0.17in;"><DIV style="position:relative; left:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">TRANSFERENCIA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:4.60in;left:4.49in;width:4.03in;height:0.66in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_12.png').'" />
        <div style="position:absolute;top:4.69in;left:4.61in;width:0.56in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">BANCO:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.69in;left:4.61in;width:3.70in;line-height:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$banco.'</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.61in;left:8.52in;width:2.92in;height:0.65in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_13.png').'" />
        <div style="position:absolute;top:4.69in;left:8.64in;width:0.67in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MONEDA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.93in;left:8.64in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">QUETZALES</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.91in;left:9.55in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <div style="position:absolute;top:4.95in;left:9.60in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:effrarg;color:#000000">X</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.93in;left:10.25in;width:0.69in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DOLARES</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.44in;left:6.28in;width:4.62in;height:0.74in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_7.png').'" />
        <img style="position:absolute;top:5.44in;left:0.91in;width:4.62in;height:0.74in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_8.png').'" />
        <div style="position:absolute;top:5.67in;left:1in;width:4.50in;line-height:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$codigoRecibo.' </span></SPAN><br/></div>
        <div style="position:absolute;top:5.92in;left:2.27in;width:1.94in;line-height:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">FIRMA </span></SPAN><br/></div>
        <div style="position:absolute;top:5.92in;left:2.27in;width:1.94in;line-height:0.13in;"><DIV style="position:relative; left:0.36in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">DEL </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.92in;left:2.27in;width:1.94in;line-height:0.13in;"><DIV style="position:relative; left:0.59in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">VENDEDOR </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.89in;left:2.27in;width:1.94in;line-height:0.16in;"><DIV style="position:relative; left:1.22in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">/</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.92in;left:2.27in;width:1.94in;line-height:0.14in;"><DIV style="position:relative; left:1.29in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">COBRADOR</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:0.25in;left:0.54in;width:2.33in;height:0.95in" src="'.fcnBase64($logo).'" />
        <img style="position:absolute;top:2.57in;left:1.77in;width:9.65in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_1.png').'" />
        <img style="position:absolute;top:3.65in;left:6.64in;width:1.51in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_2.png').'" />
        <img style="position:absolute;top:3.65in;left:9.33in;width:1.50in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_3.png').'" />
        <img style="position:absolute;top:4.86in;left:0.51in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_4.png').'" />
        <img style="position:absolute;top:4.86in;left:4.53in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_5.png').'" />
        <img style="position:absolute;top:1.83in;left:8.27in;width:3.11in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_6.png').'" />
        <img style="position:absolute;top:1.83in;left:1.15in;width:6.40in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_7.png').'" />

        <img style="position:absolute;top:3.32in;left:1.99in;width:9.42in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_9.png').'" />
        ';
        if($rTmp->proyecto=='Naos'){
            $texto_r .= '<img style="position:absolute;top:3.67in;left:3.89in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />';
        }
        
        $texto_r .= '
        <img style="position:absolute;top:3.99in;left:1.46in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:1.29in;left:9.49in;width:1.87in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_14.png').'" />
        <img style="position:absolute;top:4.29in;left:1.69in;width:9.72in;height:0.31in" src="" />
        <div style="position:absolute;top:4.03in;left:1.50in;width:0.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:effrarg;color:#000000">X</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.91in;left:10.98in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_17.png').'" />
        <div style="position:absolute;top:7.0in;left:7.88in;width:5.87in;height:0.31in"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#ff0000">'.$fechaActual.'</span><br/></SPAN></div>
        
        <img style="position:absolute;top:5.56in;left:1.22in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_19.png').'" />
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">hecho </span></SPAN><br/></div>
        </body>
    </html>
    ';
    $dompdf->load_html(($texto_r));

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('legal', 'landscape');

    // Render the HTML as PDF
    $dompdf->render();

    $_path = "../public/";
    $pdfD = $dompdf->output();
    file_put_contents($_path."reciboReserva_".$idPago.".pdf", $pdfD);
    $codigo = "reciboReserva_".$idPago.".pdf";
    $strQuery = "   SELECT *
                    FROM  archivo 
                    WHERE id_cliente = {$intIdOcaCliente}
                    AND estado = 1
                    AND nombre = 'reciboReserva_".$idPago.".pdf'   ";
    $qTmp = $conn->db_query($strQuery);
    if ($conn->db_num_rows($qTmp) <= 0) {
        $strQuery = "   INSERT INTO  archivo (id_cliente, id_tipo_documento, codigo, tipo, nombre)
                        VALUES ({$intIdOcaCliente}, 9, '{$codigo}', 'pdf', '{$codigo}')";
        $conn->db_query($strQuery);
    }
    $filename = $_path."reciboReserva_".$idPago.".pdf"; // el nombre con el que se descargará, puede ser diferente al original 
    $nombre = "reciboReserva_".$idPago.".pdf";
    header("Content-type: application/octet-stream"); 
    header("Content-Type: application/force-download"); 
    header("Content-Disposition: attachment; filename=\"$nombre\"\n"); 
    readfile($filename);
    // Enviamos el fichero PDF al navegador.
    //$dompdf->stream($nombre, array("Attachment" => false));

    
}
if(isset($_GET['reservaMontoPdf'])){
    $idPago=$_GET['idPago'];
    $strQueryPdf = "SELECT * FROM reservaApartamento where idReserva ={$idPago};";

            $qTmp = $conn ->db_query($strQueryPdf);
            $rTmp = $conn->db_fetch_object($qTmp);
    $montoCocina=0;
    if($rTmp->proyecto=='Marabi'){
        $logo = '../img/logo Marabi.png';
       
    }else if($rTmp->proyecto=='Naos'){
        $logo = '../img/logo naos.png';
    }
    // if($rTmp->cocina=='Sin cocina' || $rTmp->cocina==''){
    //     $cocina = 'No';
    // }else{
    //     $cocina = 'Si';
    // }
    $proyecto = strtoupper($rTmp->proyecto);
    $intIdOcaCliente=$rTmp->idReserva;
    $meses = array(1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $montoLetras=monto_letras($rTmp->montoReserva);
    
        $fecha=date('d',strtotime($rTmp->fechaPagoReserva)).' De '.$meses[date('n',strtotime($rTmp->fechaPagoReserva))].' De '.date('Y',strtotime($rTmp->fechaPagoReserva));
        $noRecibo=$rTmp->noReciboReserva==''?$rTmp->idReserva.$rTmp->idReserva.$rTmp->torre:$rTmp->noReciboReserva;
        $nombreCompleto=$rTmp->nombreCompleto;
        $monto='Q.'.number_format($rTmp->montoReserva,2,".",",");
        $montoLetras=$montoLetras;
        $concepto='Pago de Reserva';
        $cocina='no';
        $montoCocina='';
        $saldoCocina='';
        $tipoPago='reserva';
        $apartamento=$rTmp->apartamento;
        $noDeposito=$rTmp->noDepositoReserva;
        $banco=$rTmp->bacoDepositoReserva;
        $moneda='quetzales';
        $fechaActual='Recibo generado con fecha '.date('d/m/Y H:i:s');
        $codigoRecibo=$rTmp->idVendedor.'-'.$rTmp->idVendedor.'-'.date('Ymd',strtotime($rTmp->fechaPagoReserva)).'-'.$rTmp->idReserva.$rTmp->idReserva.$rTmp->torre;
        $nombre = normalizarNombre($nombreCompleto)."_".$codigoRecibo.".pdf";
            // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $texto_r='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link rel="stylesheet" type="text/css" href="../css/stylesRecibo.css"/>
            <style>
                @font-face {
                    font-family: effrarg;
                    src: url("Effra_Std_Lt.ttf");
                }
                @font-face {
                    font-family: effrargB;
                    src: url("Effra_Std_Rg.ttf");
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
        <img style="position:absolute;top:5.47in;left:6.30in;width:4.60in;height:0.70in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_1.png').'" />
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">El </span></SPAN><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.14in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">pago </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.45in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.65in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">medio </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.02in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.19in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cheque </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.62in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">est&aacute; </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.88in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">sujeto </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.24in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.34in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.47in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">condici&oacute;n </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.01in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.18in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">que </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.42in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.55in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">mismo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.48in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.94in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">sea</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.37in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">efectivo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.82in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.92in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">su </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.08in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">presentaci&oacute;n, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.84in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">si </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.96in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.09in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cheque </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.52in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">es </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.68in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">rechazado </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.28in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.49in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.62in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">falta </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.88in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">fondos </span></SPAN><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.40in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">o </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.71in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">cualquier </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.23in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">otra </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.48in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">causa, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.00in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">empresa </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">se </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.66in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">reserva </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.10in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">el </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.23in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">derecho </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.70in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.78in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">proceder</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">acuerdo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.64in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">a </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.74in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">lo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:0.87in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">establecido </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.52in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">por </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.73in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">la </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:1.85in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">ley </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.04in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">(C&oacute;digo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.50in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">de </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:2.67in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">Comercio, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.26in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">C&oacute;digo </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.93in;left:6.41in;width:4.38in;line-height:0.17in;"><DIV style="position:relative; left:3.67in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">permitido).</span><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:6.19in;left:1.00in;width:4.42in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_2.png').'" />
        <div style="position:absolute;top:6.20in;left:1.11in;width:4.14in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:8pt;font-family:effrarg;color:#000000">ORGINAL; Cliente - DUPLICADO; Contabilidad - TRIPLICADO - Archivo</span></SPAN><br/></div>
        <div style="position:absolute;top:6.20in;left:1.11in;width:4.14in;line-height:0.17in;"><DIV style="position:relative; left:0.63in;"><span style="font-style:normal;font-weight:bold;font-size:8pt;font-family:effrarg;color:#000000"></span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.24in;left:0.55in;width:3.41in;line-height:0.27in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">SPV </span></SPAN><br/></div>
        <div style="position:absolute;top:1.24in;left:0.55in;line-height:0.27in;"><DIV style="position:relative; left:0.48in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$proyecto.', SOCIEDAD AN&Oacute;NIMA </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.24in;left:0.55in;width:3.41in;line-height:0.27in;"><DIV style="position:relative; left:1.20in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.24in;left:0.55in;width:3.41in;line-height:0.27in;"><DIV style="position:relative; left:2.36in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"></span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">4TA. </span></SPAN><br/></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:0.33in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Avenida </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:0.86in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">23</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">-</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">80, </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.28in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">zona </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.62in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">14 </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:1.48in;left:0.55in;width:2.51in;line-height:0.19in;"><DIV style="position:relative; left:1.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Guatemala</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <div style="position:absolute;top:1.24in;left:8.07in;width:0.85in;line-height:0.23in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Calibri;color:#000000">RECIBO DE CAJA</span></SPAN><br/></div>
        <img style="position:absolute;top:1.19in;left:8.95in;width:0.78in;height:0.47in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_3.png').'" />
        <div style="position:absolute;top:1.18in;left:9.06in;width:2.5in;line-height:0.39in;"><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Times New Roman;color:#ff0000">No</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Times New Roman;color:#ff0000">. '.$noRecibo.'</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Times New Roman;color:#ff0000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.24in;left:0.49in;width:10.94in;height:0.32in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_1.png').'" />
        <img style="position:absolute;top:2.24in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_2.png').'" />
        <div style="position:absolute;top:2.30in;left:0.62in;width:1.06in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">RECIBIMOS </span></SPAN><br/></div>
        <div style="position:absolute;top:2.30in;left:0.62in;width:10.66in;line-height:0.17in;"><DIV style="position:relative; left:0.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$nombreCompleto.' </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:2.27in;left:1.56in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_1.png').'" />
        <img style="position:absolute;top:1.82in;left:0.52in;width:7.07in;height:0.34in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_3.png').'" />
        <div style="position:absolute;top:1.89in;left:0.66in;width:6.8in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">FECHA: </span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$fecha.'</span><br/></SPAN></div>
        <img style="position:absolute;top:1.85in;left:1.07in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_2.png').'" />
        <img style="position:absolute;top:1.81in;left:7.59in;width:3.84in;height:0.34in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_4.png').'" />
        <div style="position:absolute;top:1.85in;left:7.73in;width:3.58in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MONTO:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$monto.' </span><br/></SPAN></div>
        <img style="position:absolute;top:1.84in;left:8.18in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_3.png').'" />
        <img style="position:absolute;top:2.56in;left:0.49in;width:10.93in;height:0.32in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_4.png').'" />
        <img style="position:absolute;top:2.56in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_5.png').'" />
        <div style="position:absolute;top:2.61in;left:0.61in;width:1.20in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">LA </span></SPAN><br/></div>
        <div style="position:absolute;top:2.61in;left:0.61in;width:1.20in;line-height:0.17in;"><DIV style="position:relative; left:0.20in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CANTIDAD </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:2.61in;left:0.61in;width:10.60in;line-height:0.17in;"><DIV style="position:relative; left:0.94in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$montoLetras.'</span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:2.57in;left:1.68in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_4.png').'" />
        <img style="position:absolute;top:2.88in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_5.png').'" />
        <img style="position:absolute;top:2.95in;left:0.51in;width:10.90in;height:0.19in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_5.png').'" />
        <img style="position:absolute;top:2.88in;left:11.13in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_6.png').'" />
        <img style="position:absolute;top:3.32in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_6.png').'" />
        <div style="position:absolute;top:3.38in;left:0.62in;width:1.40in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">POR </span></SPAN><br/></div>
        <div style="position:absolute;top:3.38in;left:0.62in;width:1.40in;line-height:0.17in;"><DIV style="position:relative; left:0.33in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CONCEPTO </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:3.38in;left:0.62in;width:10.60in;line-height:0.17in;"><DIV style="position:relative; left:1.14in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$concepto.'</span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:3.35in;left:1.90in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_7.png').'" />
        <img style="position:absolute;top:3.64in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_6.png').'" />
        ';
        if($rTmp->proyecto=='Naos'){
            $texto_r .= '<div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">PAGO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:0.43in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">POR </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:0.76in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CONCEPTO </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:1.57in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:1.80in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MUEBLES </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:2.48in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:0.61in;width:3.28in;line-height:0.17in;"><DIV style="position:relative; left:2.71in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">COCINA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        <div style="position:absolute;top:3.71in;left:5.97in;width:2.21in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">ABONO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.71in;left:5.97in;width:2.21in;line-height:0.17in;"><DIV style="position:relative; left:0.53in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Q.____________________</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        <div style="position:absolute;top:3.71in;left:8.65in;width:2.22in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">SALDO </span></SPAN><br/></div>
                        <div style="position:absolute;top:3.71in;left:8.65in;width:2.22in;line-height:0.17in;"><DIV style="position:relative; left:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">Q. </span></SPAN></DIV><br/></div>
                        <div style="position:absolute;top:3.71in;left:8.65in;width:2.22in;line-height:0.17in;"><DIV style="position:relative; left:0.68in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">____________________</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
                        ';
        }
        
        $texto_r .= '<img style="position:absolute;top:3.70in;left:10.75in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_8.png').'" />
        <img style="position:absolute;top:3.96in;left:0.49in;width:3.68in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_7.png').'" />
        <div style="position:absolute;top:4.03in;left:0.61in;width:0.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">RESERVA:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        
        <img style="position:absolute;top:4.03in;left:1.25in;width:0.26in;height:0.30in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_9.png').'" />
        <img style="position:absolute;top:3.96in;left:4.16in;width:3.68in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_8.png').'" />
        
        <div style="position:absolute;top:4.03in;left:4.28in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">ENGANCHE:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.99in;left:5.04in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:3.96in;left:7.83in;width:3.61in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_9.png').'" />
        <div style="position:absolute;top:4.03in;left:7.95in;width:1.06in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CANCELACI&Oacute;N:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.99in;left:8.92in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:4.28in;left:0.49in;width:10.94in;height:0.33in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_10.png').'" />
        <div style="position:absolute;top:4.36in;left:0.61in;width:10.11in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">APARTAMENTO:</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$apartamento.' </span><br/></SPAN></div>
        
        <img style="position:absolute;top:4.61in;left:0.49in;width:4.01in;height:0.65in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_11.png').'" />
        <div style="position:absolute;top:4.69in;left:0.61in;width:1.11in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">N</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">O</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">.</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN><br/></div>
        <div style="position:absolute;top:4.69in;left:0.61in;width:1.11in;line-height:0.16in;"><DIV style="position:relative; left:0.25in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.69in;left:0.61in;width:1.11in;line-height:0.17in;"><DIV style="position:relative; left:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">CHEQUE</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.69in;left:0.61in;width:3.60in;line-height:0.50in;"><DIV style="position:relative; left:0in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$noDeposito.'</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.69in;left:0.61in;width:1.11in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.69in;left:2.22in;width:1.65in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">N</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">O</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">.</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN><br/></div>
        <div style="position:absolute;top:4.69in;left:2.22in;width:1.65in;line-height:0.16in;"><DIV style="position:relative; left:0.25in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DE</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:4.69in;left:2.22in;width:1.65in;line-height:0.17in;"><DIV style="position:relative; left:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">TRANSFERENCIA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:4.60in;left:4.49in;width:4.03in;height:0.66in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_12.png').'" />
        <div style="position:absolute;top:4.69in;left:4.61in;width:0.56in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">BANCO:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.69in;left:4.61in;width:3.70in;line-height:0.50in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000">'.$banco.'</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.61in;left:8.52in;width:2.92in;height:0.65in" src="'.fcnBase64('./SodaPDF/OutDocument/vi_13.png').'" />
        <div style="position:absolute;top:4.69in;left:8.64in;width:0.67in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">MONEDA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.93in;left:8.64in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">QUETZALES</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.91in;left:9.55in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <div style="position:absolute;top:4.95in;left:9.60in;width:0.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:effrarg;color:#000000">X</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <div style="position:absolute;top:4.93in;left:10.25in;width:0.69in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">DOLARES</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.44in;left:6.28in;width:4.62in;height:0.74in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_7.png').'" />
        <img style="position:absolute;top:5.44in;left:0.91in;width:4.62in;height:0.74in" src="'.fcnBase64('./SodaPDF/OutDocument/ci_8.png').'" />
        <div style="position:absolute;top:5.67in;left:1in;width:4.50in;line-height:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#000000"> '.$codigoRecibo.' </span></SPAN><br/></div>
        <div style="position:absolute;top:5.92in;left:2.27in;width:1.94in;line-height:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">FIRMA </span></SPAN><br/></div>
        <div style="position:absolute;top:5.92in;left:2.27in;width:1.94in;line-height:0.13in;"><DIV style="position:relative; left:0.36in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">DEL </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.92in;left:2.27in;width:1.94in;line-height:0.13in;"><DIV style="position:relative; left:0.59in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">VENDEDOR </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.89in;left:2.27in;width:1.94in;line-height:0.16in;"><DIV style="position:relative; left:1.22in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000">/</span><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000"> </span></SPAN></DIV><br/></div>
        <div style="position:absolute;top:5.92in;left:2.27in;width:1.94in;line-height:0.14in;"><DIV style="position:relative; left:1.29in;"><span style="font-style:normal;font-weight:normal;font-size:7pt;font-family:effrarg;color:#000000">COBRADOR</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:0.25in;left:0.54in;width:2.33in;height:0.95in" src="'.fcnBase64($logo).'" />
        <img style="position:absolute;top:2.57in;left:1.77in;width:9.65in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_1.png').'" />
        <img style="position:absolute;top:3.65in;left:6.64in;width:1.51in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_2.png').'" />
        <img style="position:absolute;top:3.65in;left:9.33in;width:1.50in;height:0.25in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_3.png').'" />
        <img style="position:absolute;top:4.86in;left:0.51in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_4.png').'" />
        <img style="position:absolute;top:4.86in;left:4.53in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_5.png').'" />
        <img style="position:absolute;top:1.83in;left:8.27in;width:3.11in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_6.png').'" />
        <img style="position:absolute;top:1.83in;left:1.15in;width:6.40in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_7.png').'" />

        <img style="position:absolute;top:3.32in;left:1.99in;width:9.42in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_9.png').'" />
        ';
        if($rTmp->proyecto=='Naos'){
            $texto_r .= '<img style="position:absolute;top:3.67in;left:3.89in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />';
        }
        
        $texto_r .= '
        <img style="position:absolute;top:3.99in;left:1.46in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_12.png').'" />
        <img style="position:absolute;top:1.29in;left:9.49in;width:1.87in;height:0.31in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_14.png').'" />
        <img style="position:absolute;top:4.29in;left:1.69in;width:9.72in;height:0.31in" src="" />
        <div style="position:absolute;top:4.03in;left:1.50in;width:0.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:effrarg;color:#000000">X</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:effrarg;color:#000000"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.91in;left:10.98in;width:0.27in;height:0.27in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_17.png').'" />
        <div style="position:absolute;top:7.0in;left:7.88in;width:5.87in;height:0.31in"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:effrarg;color:#ff0000">'.$fechaActual.'</span><br/></SPAN></div>
        
        <img style="position:absolute;top:5.56in;left:1.22in;width:3.97in;height:0.39in" src="'.fcnBase64('./SodaPDF/OutDocument/ri_19.png').'" />
        <div style="position:absolute;top:5.63in;left:6.41in;width:4.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:8pt;font-family:effrarg;color:#000000">hecho </span></SPAN><br/></div>
        </body>
    </html>
    ';
    $dompdf->load_html(($texto_r));

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('legal', 'landscape');

    // Render the HTML as PDF
    $dompdf->render();

    // Enviamos el fichero PDF al navegador.
    $dompdf->stream($nombre, array("Attachment" => false));

    
}
if(isset($_GET['cotizacionPdf'])){
    $idCotizacion=$_GET['idCotizacion'];


    $strQueryPdf = "SELECT c.*,a.cuartos,a.sqmts,a.jardin_mts,((dg.cambioDolar * a.precio) + (c.parqueosExtras * dg.parqueoExtra * dg.cambioDolar)  +(c.parqueosExtrasMoto * dg.parqueoExtraMoto * dg.cambioDolar) + (c.bodegasExtras * a.bodega_precio * dg.cambioDolar) ) as precioTotal,
                        ( ((dg.cambioDolar * a.precio) + (c.parqueosExtras * dg.parqueoExtra * dg.cambioDolar)  +(c.parqueosExtrasMoto * dg.parqueoExtraMoto * dg.cambioDolar) + (c.bodegasExtras * a.bodega_precio * dg.cambioDolar)) - c.descuento_porcentual_monto ) as precioNeto,
                        CONCAT(IFNULL(CONCAT(u.primer_nombre,' '),''),IFNULL(CONCAT(u.primer_apellido,' '),'')) as nombreVendedor, u.mail,u.telefono as telefonoVendedor,
                        (dg.iusi/10) as tasaIusi,seguro as tasaSeguro,dg.porcentajeFacturacion, dg.rate as tasaInteres,dg.parqueoExtra as costoParqueo,
                        dg.cocinaTipoA,dg.cocinaTipoB,(c.bodegasExtras + a.bodega_mts) as bodegasExtras,(a.parqueo + a.parqueo_moto + parqueosExtras + parqueosExtrasMoto) as totalParqueos
                        FROM  cotizacion c
                        INNER JOIN  apartamentos a ON c.apartamento = a.apartamento
                        INNER JOIN  datosGlobales dg on a.idProyecto = dg.idGlobal
                        INNER JOIN  usuarios u ON c.idVendedor = u.id_usuario
                            WHERE c.idCotizacion={$idCotizacion};";

            //echo $strQuery;
            $qTmp = $conn ->db_query($strQueryPdf);
            $rTmp = $conn->db_fetch_object($qTmp);
    if($rTmp->proyecto=='Marabi'){
        $montoCocina=0;
        if($rTmp->cocina=='cocinaTipoA'){
            $montoCocina=$rTmp->cocinaTipoA;
        }
        else if($rTmp->cocina=='cocinaTipoB'){
            $montoCocina=$rTmp->cocinaTipoB;
        }
        if($rTmp->bancoFin=='CREDITO HIPOTECARIO NACIONAL'){
            $tasaInteres=5.5;
        }else{
            $tasaInteres=$rTmp->tasaInteres;
        }
        $precioNeto=$rTmp->precioNeto+$montoCocina;
        $precioTotal=$rTmp->precioTotal+$montoCocina;
        $im = $tasaInteres/ 12 / 100;	
        $Im2 = $im + 1 ;			
        $im2 = pow(	($Im2), - (12 * $rTmp->plazoFinanciamiento ) );
        $cuotaCredito = (($precioNeto - $rTmp->enganchePorcMonto) * $im) / (1- $im2 ) ;					
        $cuotaSeguro = (($precioNeto*$rTmp->tasaSeguro)/100)/12;
        $ventaPorcionFactura = ($precioNeto * $rTmp->porcentajeFacturacion)/100;
        $cuotaIusi = (($ventaPorcionFactura * $rTmp->tasaIusi)/100)/12;
        $cuotaMensual = $cuotaIusi + $cuotaSeguro + $cuotaCredito ; 
        $ingresoFamiliar = $cuotaMensual/0.35; 
        if($rTmp->cocina=='Sin cocina' || $rTmp->cocina==''){
            $cocina = 'No';
        }else{
            $cocina = 'Si';
        }

        $habitaciones=$rTmp->cuartos>1?$rTmp->cuartos.' dormitorios':$rTmp->cuartos.' dormitorio';
        $fase='Modulo '.$rTmp->torres;
        $torre=$rTmp->torres;
        $costoParqueo=$rTmp->costoParqueo;
        $parqueoExtra=$rTmp->parqueosExtras + $rTmp->parqueosExtrasMoto;
        $totalParqueos='Q.'.number_format(round($rTmp->costoParqueo * $rTmp->parqueosExtras),2,".",",");
        $conCocina=$cocina;
        $nivel="Nivel ".$rTmp->nivel;
        $apartamento=$rTmp->apartamento;
        $nombreCompleto=$rTmp->nombreCompleto;
        $correo=$rTmp->correo;
        $telefono=$rTmp->telefono;
        $tamanio=$rTmp->sqmts.' m2';
        $noHabitacion=$rTmp->cuartos;
        $parqueos=$rTmp->totalParqueos;
        $areaJardin=$rTmp->jardin_mts.' m2';
        $bodegas=$rTmp->bodegasExtras;
        $precioTotal='Q.'.number_format($precioTotal,2,".",",");
        $descuento=$rTmp->descuento_porcentual.' %';
        
        $engancheMonto='Q.'.number_format(round($rTmp->enganchePorcMonto - $rTmp->MontoReserva),2,".",",");
        $enganche=$rTmp->enganchePorc.' %';
        $enganchePagos=$rTmp->pagosEnganche;
        $engancheMensual='Q.'.number_format(($rTmp->enganchePorcMonto - $rTmp->MontoReserva)/$rTmp->pagosEnganche,2,".",",");
        $reserva='Q.'.number_format($rTmp->MontoReserva,2,".",",");
        $saldoContraEntrega='Q.'.number_format(round($precioNeto - $rTmp->enganchePorcMonto),2,".",",");
        $saldoFinanciar='Q.'.number_format(round($precioNeto - $rTmp->enganchePorcMonto),2,".",",");
        $precioNeto='Q.'.number_format(round($precioNeto),2,".",",");
        $cuotaMantenimiento='Q.'.number_format(400,2,".",",");
        $correoVendedor=$rTmp->mail;
        $fechaActual=date("d/m/Y");
        $plazoFinanciamiento=$rTmp->plazoFinanciamiento.' Años';
        $cuotaCredito='Q.'.number_format(round($cuotaCredito),2,".",",");
        $cuotaTotal='Q.'.number_format(round($cuotaMensual),2,".",",");
        $iusi='Q.'.number_format(round($cuotaIusi),2,".",",");
        $seguro='Q.'.number_format(round($cuotaSeguro),2,".",",");
        $ingresoFamiliar='Q.'.number_format(round($ingresoFamiliar),2,".",",");
        $contacto=$rTmp->nombreVendedor;
        $telefonoContacto=$rTmp->telefonoVendedor;
        $letraApartamento =  trim(str_replace(range(0,9),'',$apartamento));
        $nombre = 'Cotizacion_'.$apartamento."_".date('dmYHis').".pdf";
        if($rTmp->nivel >= 1 && $rTmp->nivel <= 4){
            if($letraApartamento =='A'){
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-A.png';
                $plantaTorre = './SodaPDFCotMarabi/OutDocument/PLANTA-A-T.png';
            }
            else if($letraApartamento =='B'){
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-B.png';
                $plantaTorre = './SodaPDFCotMarabi/OutDocument/PLANTA-B-T.png';
            }
            else if($letraApartamento =='C'){
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-C.png';
                $plantaTorre = './SodaPDFCotMarabi/OutDocument/PLANTA-C-T.png';
            }
            else if($letraApartamento =='D'){
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-D.png';
                $plantaTorre = './SodaPDFCotMarabi/OutDocument/PLANTA-D-T.png';
            }else{
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-E.png';
                $plantaTorre = './SodaPDFCotMarabi/OutDocument/PLANTA-E-T.png';
            }
        }
        if($rTmp->nivel >= 5 ){
            if($letraApartamento =='A'){
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-A.png';
                $plantaTorre = './SodaPDFCotMarabi/OutDocument/PLANTA-A-T.png';
            }
            else if($letraApartamento =='B'){
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-B-5-13.png';
                $plantaTorre = './SodaPDFCotMarabi/OutDocument/PLANTA-B-T.png';
            }
            else if($letraApartamento =='C'){
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-C-5-13.png';
                $plantaTorre = './SodaPDFCotMarabi/OutDocument/PLANTA-C-T.png';
            }
            else if($letraApartamento =='D'){
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-D-5-13.png';
                $plantaTorre = './SodaPDFCotMarabi/OutDocument/PLANTA-D-T.png';
            }else{
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-E-5-13.png';
                $plantaTorre = './SodaPDFCotMarabi/OutDocument/PLANTA-E-T.png';
            }
        }
        



        $dompdf = new Dompdf();
        $texto_r='
        
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <html>
        <head>
            <link rel="stylesheet" type="text/css" href="../css/styleCotMarabi.css"/>
            <style>
                @page {
                    margin:0;padding:0; // you can set margin and padding 0 
                } 
                @font-face {
                    font-family: Effra Light;
                    src: url("Effra_Std_Lt.ttf");
                }
                @font-face {
                    font-family: Effra;
                    src: url("Effra_Std_Rg.ttf");
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
        <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_1.png').'" />
        <img style="position:absolute;top:0.00in;left:0.00in;width:6.77in;height:4.08in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_1.png').'" />
        <img style="position:absolute;top:0.00in;left:6.77in;width:2.50in;height:4.08in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_2.png').'" />
        <img style="position:absolute;top:0.60in;left:0.38in;width:2.50in;height:0.98in" src="'.fcnBase64('../img/logo Marabi.png').'" />
        <div style="text-align: justify;position:absolute;top:2.09in;left:0.38in;width:3.58in;line-height:0.36in;"><span style="font-style:normal;font-weight:bold;font-size:21pt;font-family:Effra;color:#152746">Vida moderna y accesible.</span><span style="font-style:normal;font-weight:normal;font-size:21pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.00in;left:0.38in;width:2.42in;height:0.59in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/mariscal.png').'" />
        <img style="position:absolute;top:4.33in;left:4.64in;width:4.64in;height:1.23in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_3.png').'" />
        <div style="text-align: justify;position:absolute;top:10.53in;left:0.38in;width:3.03in;line-height:0.27in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#adadad">*Imágenes con fines ilustrativos,</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#adadad"> </span><br/><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#adadad">sujeto a cambios sin previo aviso.</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#adadad"> </span><br/></SPAN></div>
        <img style="position:absolute;top:11.58in;left:0.00in;width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
        <div style="text-align: justify;position:absolute;top:4.35in;left:0.45in;width:0.68in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Cocina</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.45in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
        <div style="text-align: justify;position:absolute;top:4.87in;left:0.45in;width:0.41in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Sala</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.97in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_3.png').'" />
        <div style="text-align: justify;position:absolute;top:5.38in;left:0.45in;width:0.92in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Comedor</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.48in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_4.png').'" />
        <div style="text-align: justify;position:absolute;top:5.89in;left:0.45in;width:3.74in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Dormitorio principal con Walk-In Closet y</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:6.00in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_5.png').'" />
        <div style="text-align: justify;position:absolute;top:6.17in;left:0.45in;width:1.42in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">baño completo</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        ';if($rTmp->cuartos>1){
            $descripcion1='dormitorios secundarios con area de closet, baño secundario completo y ambos parqueos en sótano.';
            $texto_r .='<div style="text-align: justify;position:absolute;top:6.69in;left:0.45in;width:3.80in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Dormitorio secundario con área de clóset</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:6.79in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_6.png').'" />
            <div style="text-align: justify;position:absolute;top:7.20in;left:0.45in;width:2.49in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Baño secundario completo</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:7.30in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_7.png').'" />
            <div style="text-align: justify;position:absolute;top:7.72in;left:0.45in;width:0.66in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Balcón</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:7.82in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_8.png').'" />
        <div style="text-align: justify;position:absolute;top:8.23in;left:0.45in;width:1.70in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Área de lavandería</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:8.33in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_9.png').'" />
        <div style="text-align: justify;position:absolute;top:8.74in;left:0.45in;width:0.74in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Bodega</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:8.85in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_10.png').'" />
        <div style="text-align: justify;position:absolute;top:9.26in;left:0.45in;width:4.04in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Parqueos en sótano tipo Tandem (Parqueos adicionales a la venta</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:9.36in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_11.png').'" />
        <div style="text-align: justify;position:absolute;top:9.54in;left:0.85in;width:0.80in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>';
            
        }else{
            $descripcion1=' un parqueo en sótano.';
            $texto_r .='
            <div style="text-align: justify;position:absolute;top:6.69in;left:0.45in;width:0.66in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Balcón</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:6.79in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_8.png').'" />
        <div style="text-align: justify;position:absolute;top:7.20in;left:0.45in;width:1.70in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Área de lavandería</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:7.30in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_9.png').'" />
        <div style="text-align: justify;position:absolute;top:7.72in;left:0.45in;width:0.74in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Bodega</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:7.82in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_10.png').'" />
        <div style="text-align: justify;position:absolute;top:8.23in;left:0.45in;width:4.04in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Parqueos en sótano (Parqueos adicionales a</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:8.33in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_11.png').'" />
        <div style="text-align: justify;position:absolute;top:8.51in;left:0.45in;width:0.80in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">la venta)</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            ';
        }
        $texto_r .='
        <img style="position:absolute;top:0.42in;left:5.10in;width:3.33in;height:3.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/torreMarabi.png').'" />
        <img style="position:absolute;top:5.65in;left:5.10in;width:3in;height:2.5in" src="'.fcnBase64($planta).'" />
        <img style="position:absolute;top:9in;left:6in;width:1.7in;height:1.7in" src="'.fcnBase64($plantaTorre).'" />
        <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
        <img style="position:absolute;top:4.42in;left:4.71in;width:4.40in;height:0.44in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_1.png').'" />
        <div style="text-align: justify;position:absolute;top:4.42in;left:4.74in;line-height:0.49in;"><span style="font-style:normal;font-weight:bold;font-size:26pt;font-family:Effra;color:#fddfdb">'.$habitaciones.'</span><span style="font-style:normal;font-weight:normal;font-size:26pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
        <img style="position:absolute;top:4.86in;left:4.73in;width:2.24in;height:0.44in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_2.png').'" />
        <div style="text-align: justify;position:absolute;top:4.86in;left:4.76in;line-height:0.46in;"><span style="font-style:normal;font-weight:normal;font-size:27pt;font-family:Effra Light;color:#fddfdb">'.$nivel.'</span><span style="font-style:normal;font-weight:normal;font-size:27pt;font-family:Effra Light;color:#fddfdb"> </span><br/></SPAN></div>
        <div style="page-break-after: always;">
        </div>
            <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_12.png').'" />
            <img style="position:absolute;top:0.60in;left:0.38in;width:2.17in;height:0.85in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_7.png').'" />
            <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_5.png').'" />
            <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#fddfdb">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.96in;left:0.38in;width:2.57in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#ff9267">¡Tu nuevo hogar te está esperando!</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.37in;left:0.38in;width:8.53in;line-height:0.19in;"><span  style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Recibe un cordial saludo de parte del equipo de Avalia Desarrollos. A continuación te presentamos la cotización del apartamento '.$apartamento.' de '.$noHabitacion.' habitaciones, sala,comedor, cocina, balcón, área de lavandería, dormitorio principal con walk-in closet y baño completo, '.$descripcion1.' Parqueos adicionales se venden por separado.</span></div>
            <div style="text-align: justify;position:absolute;top:4.88in;left:0.38in;width:2.09in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#ff9267">Información de apartamento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.75in;left:0.38in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.33in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            <img style="position:absolute;top:1.75in;left:3.33in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.33in;left:3.33in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_9.png').'" />
            <img style="position:absolute;top:1.75in;left:6.28in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.33in;left:6.28in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_11.png').'" />
            <img style="position:absolute;top:5.17in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_12.png').'" />
            <img style="position:absolute;top:5.38in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:5.97in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_14.png').'" />
            <img style="position:absolute;top:5.38in;left:2.51in;width:1.92in;height:0.59in" src="" />
            <img style="position:absolute;top:5.97in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_16.png').'" />
            <img style="position:absolute;top:5.38in;left:4.73in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:5.97in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_18.png').'" />
            <img style="position:absolute;top:5.38in;left:6.94in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:5.97in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_20.png').'" />
            <img style="position:absolute;top:6.27in;left:4.73in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:6.85in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_22.png').'" />
            <img style="position:absolute;top:6.27in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:6.85in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_24.png').'" />
            <img style="position:absolute;top:6.27in;left:2.51in;width:1.92in;height:0.59in" src="" />
            <img style="position:absolute;top:6.85in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_26.png').'" />
            <div style="text-align: justify;position:absolute;top:8.36in;left:0.38in;width:1.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#ff9267">Información de precios</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <img style="position:absolute;top:8.65in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_27.png').'" />
            <img style="position:absolute;top:8.92in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:9.50in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_39.png').'" />
            <img style="position:absolute;top:8.92in;left:2.51in;width:1.92in;height:0.59in" src="" />
            <img style="position:absolute;top:9.50in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_37.png').'" />
            <img style="position:absolute;top:8.92in;left:4.73in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:9.50in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_37.png').'" />
            <img style="position:absolute;top:8.92in;left:6.94in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:9.50in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_37.png').'" />
            <img style="position:absolute;top:9.80in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:10.38in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_37.png').'" />
            <img style="position:absolute;top:9.80in;left:2.51in;width:1.92in;height:0.59in" src="" />
            <img style="position:absolute;top:10.38in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_39.png').'" />
            <img style="position:absolute;top:9.80in;left:4.73in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:10.38in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_41.png').'" />
            <img style="position:absolute;top:9.80in;left:6.94in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:10.38in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_43.png').'" />
            <img style="position:absolute;top:10.68in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:11.27in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_45.png').'" />
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_46.png').'" />
            <div style="text-align: justify;position:absolute;top:9.75in;left:6.94in;width:0.61in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Reserva</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.75in;left:4.72in;width:1.38in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Enganche mensual</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.75in;left:2.51in;width:1.42in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Pagos de enganche</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.64in;left:0.38in;width:1.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Saldo contra entrega</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.75in;left:0.38in;width:1.13in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">% de enganche</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.87in;left:6.94in;width:0.74in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Enganche</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.87in;left:4.73in;width:0.86in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Precio neto</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.87in;left:2.51in;width:0.83in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Descuento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.87in;left:0.38in;width:0.86in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Precio total</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.70in;left:0.38in;width:1.04in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cotizado para</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.22in;left:2.51in;width:1.06in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Área de Jardín</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.22in;left:0.38in;width:0.81in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Parqueo(s)</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.22in;left:4.72in;width:0.66in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Bodegas</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.34in;left:6.94in;width:0.98in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Habitaciones</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.34in;left:4.72in;width:0.63in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Tamaño</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.34in;left:2.51in;width:1.00in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.34in;left:0.38in;width:0.39in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Nivel</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.70in;left:6.28in;width:0.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Teléfono</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.70in;left:3.33in;width:0.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Correo</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
            <img style="position:absolute;top:1.01in;left:7.09in;width:1.41in;height:0.35in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_3.png').'" />
            <div style="text-align: justify;position:absolute;top:0.98in;left:7.12in;line-height:0.37in;"><span style="font-style:normal;font-weight:bold;font-size:20pt;font-family:Effra;color:#fddfdb">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.00in;left:0.42in;width:2.54in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_4.png').'" />
            <div style="text-align: justify;position:absolute;top:2.10in;left:0.45in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$nombreCompleto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.00in;left:3.33in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_5.png').'" />
            <div style="text-align: justify;position:absolute;top:2.10in;left:3.36in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$correo.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.00in;left:6.29in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_6.png').'" />
            <div style="text-align: justify;position:absolute;top:2.09in;left:6.32in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$telefono.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            
            <div style="text-align: justify;position:absolute;top:5.74in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$nivel.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            
            <div style="text-align: justify;position:absolute;top:5.74in;left:2.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            
            <div style="text-align: justify;position:absolute;top:5.74in;left:4.76in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$tamanio.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            
            <div style="text-align: justify;position:absolute;top:5.73in;left:6.97in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$noHabitacion.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:6.53in;left:0.38in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_11.png').'" />
            <div style="text-align: justify;position:absolute;top:6.62in;left:0.41in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$parqueos.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:6.53in;left:2.50in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_12.png').'" />
            <div style="text-align: justify;position:absolute;top:6.63in;left:2.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$areaJardin.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:6.53in;left:4.74in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_13.png').'" />
            <div style="text-align: justify;position:absolute;top:6.63in;left:4.77in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$bodegas.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:9.17in;left:0.37in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_14.png').'" />
            <div style="text-align: justify;position:absolute;top:9.26in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$precioTotal.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:9.17in;left:2.49in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_15.png').'" />
            <div style="text-align: justify;position:absolute;top:9.26in;left:2.52in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$descuento.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:9.17in;left:4.72in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_16.png').'" />
            <div style="text-align: justify;position:absolute;top:9.26in;left:4.75in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$precioNeto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:9.17in;left:6.93in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_17.png').'" />
            <div style="text-align: justify;position:absolute;top:9.26in;left:6.96in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$engancheMonto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:10.05in;left:0.37in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_18.png').'" />
            <div style="text-align: justify;position:absolute;top:10.14in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$enganche.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:10.05in;left:2.50in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_19.png').'" />
            <div style="text-align: justify;position:absolute;top:10.14in;left:2.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$enganchePagos.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:10.05in;left:4.71in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_20.png').'" />
            <div style="text-align: justify;position:absolute;top:10.15in;left:4.74in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$engancheMensual.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:10.05in;left:6.93in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_21.png').'" />
            <div style="text-align: justify;position:absolute;top:10.15in;left:6.96in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$reserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:10.94in;left:0.38in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_22.png').'" />
            <div style="text-align: justify;position:absolute;top:11.03in;left:0.41in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$saldoContraEntrega.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.58in;left:6.28in;width:2.82in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_23.png').'" />
            <div style="text-align: justify;position:absolute;top:11.66in;left:6.64in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#fddfdb">'.$correoVendedor.'</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.53in;left:7.63in;width:1.41in;height:0.35in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_24.png').'" />
            <div style="text-align: justify;position:absolute;top:1.62in;left:7.66in;line-height:0.23in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Effra;color:#ff9166">'.$fechaActual.'</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#ff9166"> </span><br/></SPAN></div>
            <img style="position:absolute;top:3.56in;left:1.27in;width:0.35in;height:0.17in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_25.png').'" />
            <img style="position:absolute;top:3.55in;left:1.79in;width:0.17in;height:0.17in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_26.png').'" />
            <div style="page-break-after: always;">
            </div>
            <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_13.png').'" />
            <img style="position:absolute;top:0.60in;left:0.38in;width:2.17in;height:0.85in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_7.png').'" />
            <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_47.png').'" />
            <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#fddfdb">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.95in;left:0.38in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.53in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_49.png').'" />
            <img style="position:absolute;top:1.95in;left:3.33in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.53in;left:3.33in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_51.png').'" />
            <img style="position:absolute;top:1.95in;left:6.28in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.53in;left:6.28in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_53.png').'" />
            <img style="position:absolute;top:3.61in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_54.png').'" />
            <img style="position:absolute;top:3.88in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:4.47in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_56.png').'" />
            <img style="position:absolute;top:3.88in;left:2.51in;width:1.92in;height:0.59in" src="" />
            <img style="position:absolute;top:4.47in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_58.png').'" />
            <img style="position:absolute;top:3.88in;left:4.73in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:4.47in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_60.png').'" />
            <img style="position:absolute;top:3.88in;left:6.94in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:4.47in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_62.png').'" />
            <img style="position:absolute;top:4.77in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:5.35in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_64.png').'" />
            <img style="position:absolute;top:4.77in;left:2.51in;width:1.92in;height:0.59in" src="" />
            <img style="position:absolute;top:5.35in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_66.png').'" />
            <img style="position:absolute;top:6.44in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_67.png').'" />
            <img style="position:absolute;top:7.33in;left:0.38in;width:1.93in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_69.png').'" />
            <img style="position:absolute;top:7.33in;left:0.38in;width:1.94in;height:0.01in" src="" />
            <img style="position:absolute;top:7.33in;left:2.51in;width:1.92in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_71.png').'" />
            <img style="position:absolute;top:7.33in;left:2.50in;width:1.94in;height:0.01in" src="" />
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_72.png').'" />
            <div style="text-align: justify;position:absolute;top:1.90in;left:3.33in;width:0.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Correo</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:0.38in;width:1.04in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cotizado para</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.70in;left:0.38in;width:0.72in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Contacto</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.13in;left:0.38in;width:1.98in;line-height:0.21in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Effra;color:#ff9267">Información de contacto</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.72in;left:2.51in;width:1.88in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Ingreso familiar requerido</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.26in;left:0.38in;width:1.72in;line-height:0.21in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Effra;color:#ff9267">Información de cuota</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.72in;left:0.38in;width:0.85in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cuota total</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.84in;left:0.38in;width:1.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Plazo financiamiento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.84in;left:6.94in;width:0.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Seguro</span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.84in;left:4.73in;width:0.38in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">IUSI  </span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:6.28in;width:0.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Teléfono</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.84in;left:2.51in;width:1.24in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cuota de crédito</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.70in;left:2.51in;width:1.37in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Teléfono contacto</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.18in;left:0.45in;width:8.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Precio total incluye gastos de escrituración e impuestos de traspaso. Monto reserva: Q. 10,000.00 que serán acreditados al valor del enganche.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.43in;left:0.45in;width:8.53in;line-height:0.18in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">La presente cotización tiene una vigencia de 10 días hábiles contados a partir de la fecha de emisión.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.68in;left:0.45in;width:8.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Este material es utilizado para fines informativos y de referencia. SPV Marabi, S.A. se reserva el derecho de hacer modificaciones a los modelos a su discreción para efectos del desarrollo del proyecto.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.18in;left:0.45in;width:8.53in;line-height:0.18in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Los precios están sujetos a cambios sin previo aviso. Este material es utilizado para fines informativos y de referencia.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
            <img style="position:absolute;top:1.01in;left:7.11in;width:1.41in;height:0.35in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_27.png').'" />
            <div style="text-align: justify;position:absolute;top:0.98in;left:7.14in;line-height:0.37in;"><span style="font-style:normal;font-weight:bold;font-size:20pt;font-family:Effra;color:#fddfdb">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.20in;left:0.38in;width:2.54in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_28.png').'" />
            <div style="text-align: justify;position:absolute;top:2.30in;left:0.41in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$nombreCompleto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.20in;left:3.33in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_29.png').'" />
            <div style="text-align: justify;position:absolute;top:2.30in;left:3.36in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$correo.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.20in;left:6.27in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_30.png').'" />
            <div style="text-align: justify;position:absolute;top:2.29in;left:6.30in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$telefono.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.74in;left:7.65in;width:1.41in;height:0.35in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_31.png').'" />
            <div style="text-align: justify;position:absolute;top:1.83in;left:7.68in;line-height:0.23in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Effra;color:#ff9166">'.$fechaActual.'</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#ff9166"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.15in;left:0.37in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_32.png').'" />
            <div style="text-align: justify;position:absolute;top:4.24in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$plazoFinanciamiento.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.15in;left:2.50in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_33.png').'" />
            <div style="text-align: justify;position:absolute;top:4.24in;left:2.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$cuotaCredito.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.14in;left:4.72in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_34.png').'" />
            <div style="text-align: justify;position:absolute;top:4.23in;left:4.75in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$iusi.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.14in;left:6.93in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_35.png').'" />
            <div style="text-align: justify;position:absolute;top:4.23in;left:6.96in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$seguro.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:5.03in;left:0.37in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_36.png').'" />
            <div style="text-align: justify;position:absolute;top:5.13in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$cuotaTotal.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:5.03in;left:2.50in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_37.png').'" />
            <div style="text-align: justify;position:absolute;top:5.12in;left:2.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$ingresoFamiliar.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:7.01in;left:0.37in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_38.png').'" />
            <div style="text-align: justify;position:absolute;top:7.10in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$contacto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:7.01in;left:2.50in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_39.png').'" />
            <div style="text-align: justify;position:absolute;top:7.10in;left:2.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$telefonoContacto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.58in;left:6.29in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_40.png').'" />
            <div style="text-align: justify;position:absolute;top:11.66in;left:6.53in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#fddfdb">'.$correoVendedor.'</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <div style="page-break-after: always;">
            </div>
            <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_14.png').'" />
            <img style="position:absolute;top:0.60in;left:0.38in;width:2.17in;height:0.85in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_9.png').'" />
            <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_73.png').'" />
            <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#fddfdb">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.20in;left:0.38in;width:2.64in;line-height:0.21in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Effra;color:#ff9267">Descripción general de acabados</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.75in;left:0.38in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.33in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_75.png').'" />
            <img style="position:absolute;top:1.75in;left:3.33in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.33in;left:3.33in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_77.png').'" />
            <img style="position:absolute;top:1.75in;left:6.28in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.33in;left:6.28in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_79.png').'" />
            <img style="position:absolute;top:3.49in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_80.png').'" />
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_81.png').'" />
            <div style="text-align: justify;position:absolute;top:1.70in;left:0.38in;width:1.04in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Cotizado para</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.70in;left:3.33in;width:0.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Correo</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.70in;left:6.28in;width:0.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Teléfono</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            
            <div style="text-align: justify;position:absolute;top:3.63in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.68in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Muros: Levantado de block en muros divisorios entre apartamentos y divisiones interiores de tablayeso, con acabado liso en color blanco mate.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.18in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.23in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Pisos y forros de baños: El piso porcelanato tipo gress imitación madera. El forro de paredes de muros de los baños de piso a cielo es tipo gress color ceniza.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.73in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.78in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Cocinas: Muebles con interiores de melamina en color blanco liso; puertas de los muebles aéreos con exteriores en color NOGAL PARIS y muebles base con exteriores en color BLANCO, top de cuarzo incluye lavatrastos de una fosa sin escurridor lateral. Mezcladora de lavatrastos cuello alto manejo mojonando.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.53in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.58in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Clósets: Interiores y puertas en melamina en color NOGAL PARIS incluye sercheros de tubo niquelado con apoyos laterales. Gaveta con rieles de cierre suave y uñero a 45° como jalador. Puertas corredizas con riel aéreo y carrilera inferior plástica.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.13in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.13in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Muebles de baño: Mueble de melamina en color blanco, suspendido a la pared y gaveta con mecanismo de cierre suave.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.43in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.43in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Puertas: </span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Puertas enchapadas de madera de ingeniería con enchape de 6mm; chapas con acabado satinado y tope de puerta con acabado satinado, recibidor de caucho y fijado al piso con bisagras con acabado satinado.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.98in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.98in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Baranda de balcones: </span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Vidrio templado de 10 mm, fundido al bordillo y herrajes de acero inoxidable.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.28in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.28in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Grifería:</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> En lavamanos mezcladora de cuello alto y en ducha mezcladora de control monomando y barra para teleducha extraíble.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.58in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.58in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Losa sanitaria: </span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> En lavamanos de losa corrida de sobreponer y en inodoros tipo ovalín, con sistema de descarga eficiente.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.88in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.88in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Emplacado eléctrico: </span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> Tipo BTICINO línea matrix color blanco, con electricidad de 220 v para estufa eléctrica y calentador de paso.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.18in;left:0.45in;width:0.08in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">•</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.18in;left:0.85in;width:7.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Contador de agua:</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> Individual por apartamento.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            
            <div style="text-align: justify;position:absolute;top:10.14in;left:0.45in;width:8.10in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Apartamento no incluye: Lámparas e iluminación especial, vidrio templado en baños; calentador, filtro de agua, electrodomésticos, instalaciones especiales.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
            <img style="position:absolute;top:2.01in;left:0.37in;width:2.54in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_41.png').'" />
            <div style="text-align: justify;position:absolute;top:2.10in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$nombreCompleto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.01in;left:3.33in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_42.png').'" />
            <div style="text-align: justify;position:absolute;top:2.10in;left:3.36in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$correo.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.00in;left:6.28in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_43.png').'" />
            <div style="text-align: justify;position:absolute;top:2.09in;left:6.31in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$telefono.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.54in;left:7.65in;width:1.41in;height:0.35in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_44.png').'" />
            <div style="text-align: justify;position:absolute;top:1.63in;left:7.68in;line-height:0.23in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Effra;color:#ff9166">'.$fechaActual.'</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#ff9166"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.02in;left:7.10in;width:1.41in;height:0.35in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_45.png').'" />
            <div style="text-align: justify;position:absolute;top:0.89in;left:7.13in;line-height:0.37in;"><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#fddfdb">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.59in;left:6.36in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ri_46.png').'" />
            <div style="text-align: justify;position:absolute;top:11.67in;left:6.60in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#fddfdb">'.$correoVendedor.'</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
        </body>
        </html>
        ';

        
    }else if($rTmp->proyecto=='Naos'){
        $montoCocina=0;
        if($rTmp->cocina=='cocinaTipoA'){
            $montoCocina=$rTmp->cocinaTipoA;
        }
        else if($rTmp->cocina=='cocinaTipoB'){
            $montoCocina=$rTmp->cocinaTipoB;
        }
        if($rTmp->bancoFin=='CREDITO HIPOTECARIO NACIONAL'){
            $tasaInteres=5.5;
        }else{
            $tasaInteres=$rTmp->tasaInteres;
        }
        $precioNeto=$rTmp->precioNeto+$montoCocina;
        $precioTotal=$rTmp->precioTotal+$montoCocina;
        $im = $tasaInteres/ 12 / 100;	
        $Im2 = $im + 1 ;			
        $im2 = pow(	($Im2), - (12 * $rTmp->plazoFinanciamiento ) );
        $cuotaCredito = (($precioNeto - $rTmp->enganchePorcMonto) * $im) / (1- $im2 ) ;					
        $cuotaSeguro = (($precioNeto*$rTmp->tasaSeguro)/100)/12;
        $ventaPorcionFactura = ($precioNeto * $rTmp->porcentajeFacturacion)/100;
        $cuotaIusi = (($ventaPorcionFactura * $rTmp->tasaIusi)/100)/12;
        $cuotaMensual = $cuotaIusi + $cuotaSeguro + $cuotaCredito ; 
        $ingresoFamiliar = $cuotaMensual/0.35; 
        if($rTmp->cocina=='Sin cocina' || $rTmp->cocina==''){
            $cocina = 'No';
        }else{
            $cocina = 'Si';
        }

        $habitaciones=$rTmp->cuartos>1?$rTmp->cuartos.' dormitorios':$rTmp->cuartos.' dormitorio';
        $fase='Modulo '.$rTmp->torres;
        $torre=$rTmp->torres;
        $costoParqueo='Q.'.number_format($rTmp->costoParqueo,2,".",",");
        $parqueoExtra=$rTmp->parqueosExtras + $rTmp->parqueosExtrasMoto;
        $totalParqueos='Q.'.number_format(round($rTmp->costoParqueo * $rTmp->parqueosExtras),2,".",",");
        $conCocina=$cocina;
        $nivel="Nivel ".$rTmp->nivel;
        $apartamento=$rTmp->apartamento;
        $nombreCompleto=$rTmp->nombreCompleto;
        $correo=$rTmp->correo;
        $telefono=$rTmp->telefono;
        $tamanio=$rTmp->sqmts.' m2';
        $noHabitacion=$rTmp->cuartos;
        $NoHabitacionSec = $noHabitacion -1;
        if($NoHabitacionSec == 1){

        } 
        $parqueos=$rTmp->totalParqueos;
        $areaJardin=$rTmp->jardin_mts.' m2';
        $bodegas=$rTmp->bodegasExtras;
        $precioTotal='Q.'.number_format($precioTotal,2,".",",");
        $descuento=$rTmp->descuento_porcentual.' %';
        
        $engancheMonto='Q.'.number_format(round($rTmp->enganchePorcMonto - $rTmp->MontoReserva),2,".",",");
        $enganche=$rTmp->enganchePorc.' %';
        $enganchePagos=$rTmp->pagosEnganche;
        $engancheMensual='Q.'.number_format(($rTmp->enganchePorcMonto - $rTmp->MontoReserva)/$rTmp->pagosEnganche,2,".",",");
        $reserva='Q.'.number_format($rTmp->MontoReserva,2,".",",");
        $saldoContraEntrega='Q.'.number_format(round($precioNeto - $rTmp->enganchePorcMonto),2,".",",");
        $saldoFinanciar='Q.'.number_format(round($precioNeto - $rTmp->enganchePorcMonto),2,".",",");
        $precioNeto='Q.'.number_format(round($precioNeto),2,".",",");
        $cuotaMantenimiento='Q.'.number_format(400,2,".",",");
        $correoVendedor=$rTmp->mail;
        $fechaActual=date("d/m/Y");
        $plazoFinanciamiento=$rTmp->plazoFinanciamiento.' Años';
        $cuotaCredito='Q.'.number_format(round($cuotaCredito),2,".",",");
        $cuotaTotal='Q.'.number_format(round($cuotaMensual),2,".",",");
        $iusi='Q.'.number_format(round($cuotaIusi),2,".",",");
        $seguro='Q.'.number_format(round($cuotaSeguro),2,".",",");
        $ingresoFamiliar='Q.'.number_format(round($ingresoFamiliar),2,".",",");
        $contacto=$rTmp->nombreVendedor;
        $telefonoContacto=$rTmp->telefonoVendedor;
        $letraApartamento =  trim(str_replace(range(0,9),'',$apartamento));
        $nombre = 'Cotizacion_'.$apartamento."_".date('dmYHis').".pdf";
        if($rTmp->nivel==1){
            if($letraApartamento =='A'){
                $planta = './SodaPDFCotNaos/OutDocument/NAOS_N1_APTO_A-1.jpg';
                $plantaTorre = './SodaPDFCotNaos/OutDocument/NAOS_N1_APTO_A_UBICACIÓN-1.jpg';
            }
            else if($letraApartamento =='B'){
                $planta = './SodaPDFCotNaos/OutDocument/NAOS_N1_APTO_B-1.jpg';
                $plantaTorre = './SodaPDFCotNaos/OutDocument/NAOS_N1_APTO_B_UBICACIÓN-1.jpg';
            }
            else if($letraApartamento =='C'){
                $planta = './SodaPDFCotNaos/OutDocument/NAOS_N1_APTO_C-1.jpg';
                $plantaTorre = './SodaPDFCotNaos/OutDocument/NAOS_N1_APTO_C_UBICACIÓN-1.jpg';
            }
            else if($letraApartamento =='D'){
                $planta = './SodaPDFCotNaos/OutDocument/NAOS_N1_APTO_D-1.jpg';
                $plantaTorre = './SodaPDFCotNaos/OutDocument/NAOS_N1_APTO_D_UBICACIÓN-1.jpg';
            }
        }else{
            if($letraApartamento =='A'){
                $planta = './SodaPDFCotNaos/OutDocument/NAOS_N2alN6_APTO_A-1.jpg';
                $plantaTorre = './SodaPDFCotNaos/OutDocument/NAOS_N2 al N6_APTO_A_UBICACIÓN-1.jpg';
            }
            else if($letraApartamento =='B'){
                $planta = './SodaPDFCotNaos/OutDocument/NAOS_N2alN6_APTO_B-1.jpg';
                $plantaTorre = './SodaPDFCotNaos/OutDocument/NAOS_N2 al N6_APTO_B_UBICACIÓN-1.jpg';
            }
            else if($letraApartamento =='C'){
                $planta = './SodaPDFCotNaos/OutDocument/NAOS_N2alN6_APTO_C-1.jpg';
                $plantaTorre = './SodaPDFCotNaos/OutDocument/NAOS_N2 al N6_APTO_C_UBICACIÓN-1.jpg';
            }
            else if($letraApartamento =='D'){
                $planta = './SodaPDFCotNaos/OutDocument/NAOS_N2alN6_APTO_D-1.jpg';
                $plantaTorre = './SodaPDFCotNaos/OutDocument/NAOS_N2 al N6_APTO_D_UBICACIÓN-1.jpg';
            }
        }
        $plantaTorre = './SodaPDFCotNaos/OutDocument/fasesNaos2.jpg';
        



        $dompdf = new Dompdf();
        $texto_r='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <html>
        <head>
        <link rel="stylesheet" type="text/css" href="styleCotNaos.css"/>
            <style>
                @page {
                    margin:0;padding:0; // you can set margin and padding 0 
                } 
                @font-face {
                    font-family: Effra Light;
                    src: url("Effra_Std_Lt.ttf");
                }
                @font-face {
                    font-family: Effra;
                    src: url("Effra_Std_Rg.ttf");
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
        <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_1.png').'" />
        <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:5.76in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_1.png').'" />
        <img style="position:absolute;top:0.36in;left:0.38in;width:1.67in;height:0.74in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/naosLogo.jpg').'" />
        <div style="text-align: justify;position:absolute;top:1.93in;left:0.57in;width:1.32in;line-height:0.17in;"><span style="font-weight:bold;font-size:10pt;font-family:Effra;color:#de5b68">Ingresar al brochure</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.16in;left:0.55in;width:1.33in;height:1.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_2.jpeg').'" />
        <div style="text-align: center;position:absolute;top:3.54in;left:0.80in;width:0.89in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#de5b68">Ubicación del proyecto</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/><DIV style="position:relative; left:0.13in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></DIV></div>
        <img style="position:absolute;top:3.96in;left:0.55in;width:0.62in;height:0.62in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_3.jpeg').'" />
        <img style="position:absolute;top:3.96in;left:1.27in;width:0.62in;height:0.62in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_4.jpeg').'" />
        <img style="position:absolute;top:4.68in;left:0.73in;width:0.27in;height:0.26in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/logo-waze.png').'" />
        <img style="position:absolute;top:4.68in;left:1.48in;width:0.18in;height:0.26in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/logo-maps.png').'" />
        <div style="text-align: justify;position:absolute;top:5.20in;left:3.71in;width:3.69in;line-height:0.29in;"><span style="font-style:normal;font-weight:bold;font-size:16pt;font-family:Effra;color:#bfdbe5">VIDA SEGURA, CERCA DE TODO.</span><span style="font-style:normal;font-weight:normal;font-size:16pt;font-family:Effra;color:#bfdbe5"> </span><br/></SPAN></div>
        <img style="position:absolute;top:6.00in;left:0.00in;width:3.09in;height:1.00in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_2.png').'" />
        <img style="position:absolute;top:6.17in;left:5.01in;width:3.83in;height:2.63in" src="'.fcnBase64($planta).'" />
        <div style="text-align: justify;position:absolute;top:8.85in;left:5.73in;width:0.44in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        
        <img style="position:absolute;top:9.33in;left:6.95in;width:1.67in;height:1.89in" src="" />
        <img style="position:absolute;top:8.9in;left:4.90in;width:4.53in;height:2.604in" src="'.fcnBase64($plantaTorre).'" />
        <div style="text-align: justify;position:absolute;top:10.53in;left:0.38in;width:3.03in;line-height:0.27in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#adadad">*Imágenes con fines ilustrativos,</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#adadad"> </span><br/><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#adadad">sujeto a cambios sin previo aviso.</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#adadad"> </span><br/></SPAN></div>
        <img style="position:absolute;top:11.58in;left:0.00in;width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_3.png').'" />
        <div style="text-align: justify;position:absolute;top:7.52in;left:0.45in;width:1.84in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Dormitorio principal</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:7.62in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_2.png').'" />
        <div style="text-align: justify;position:absolute;top:8.03in;left:0.45in;width:2.66in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">'.$NoHabitacionSec.' dormitorio(s) secundario(s)</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:8.14in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_3.png').'" />
        <div style="text-align: justify;position:absolute;top:8.55in;left:0.45in;width:0.51in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Baño</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:8.65in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_4.png').'" />
        <div style="text-align: justify;position:absolute;top:9.06in;left:0.45in;width:1.43in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Sala y comedor</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:9.17in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_5.png').'" />
        <div style="text-align: justify;position:absolute;top:9.58in;left:0.45in;width:2.48in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">Área de cocina y lavandería</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:9.68in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_6.png').'" />
        <div style="text-align: justify;position:absolute;top:10.09in;left:0.45in;width:1.95in;line-height:0.24in;"><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746">1 parqueo para moto</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:10.20in;left:0.32in;width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_7.png').'" />
        <img style="position:absolute;top:0.32in;left:2.32in;width:6.67in;height:4.61in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/torreNaos.jpg').'" />
        <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/avalia-logo.png').'" />
        <img style="position:absolute;top:6.06in;left:0.26in;width:2.24in;height:0.43in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_1.png').'" />
        <div style="text-align: justify;position:absolute;top:6.15in;left:0.29in;line-height:0.33in;"><span style="font-style:normal;font-weight:bold;font-size:17pt;font-family:Effra;color:#de5a67">'.$fase.'</span><span style="font-style:normal;font-weight:normal;font-size:17pt;font-family:Effra;color:#de5a67"> </span><br/></SPAN></div>
        <img style="position:absolute;top:6.53in;left:0.26in;width:2.25in;height:0.41in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_2.png').'" />
        <div style="text-align: justify;position:absolute;top:6.48in;left:0.29in;line-height:0.41in;"><span style="font-style:normal;font-weight:bold;font-size:22pt;font-family:Effra;color:#152746">'.$nivel.'</span><span style="font-style:normal;font-weight:normal;font-size:22pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="page-break-after: always;">
        </div>
        <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_8.png').'" />
        <img style="position:absolute;top:0.60in;left:0.38in;width:2.17in;height:0.89in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_14.png').'" />
        <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_4.png').'" />
        <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#de5b68">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:2.96in;left:0.38in;width:2.57in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#de5b68">¡Tu nuevo hogar te está esperando!</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:3.37in;left:0.38in;width:8.55in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Recibe un cordial saludo de parte del equipo de Avalia Desarrollos, desarrolladores de NAOS Apartamentos. A continuación te presentamos la cotización del apartamento tipo '.$apartamento.' de '.$noHabitacion.' habitaciones, sala, comedor, cocina, acceso a balcón francés, área de lavandería, dormitorio principal, '.$NoHabitacionSec.' Habitacion(es) adicional(es), 1 baño completo y parqueo de motocicleta. Parqueo de vehículos por medio de alquiler.</span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:4.40in;left:0.38in;width:2.09in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#de5b68">Información de apartamento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
        <img style="position:absolute;top:1.75in;left:0.38in;width:2.57in;height:0.59in" src="" />
        <img style="position:absolute;top:2.33in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
        <img style="position:absolute;top:1.75in;left:3.33in;width:2.57in;height:0.59in" src="" />
        <img style="position:absolute;top:2.33in;left:3.33in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_8.png').'" />
        <img style="position:absolute;top:1.75in;left:6.28in;width:2.57in;height:0.59in" src="" />
        <img style="position:absolute;top:2.33in;left:6.28in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_10.png').'" />
        <img style="position:absolute;top:4.69in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_11.png').'" />
        <img style="position:absolute;top:5.05in;left:0.38in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:5.63in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_13.png').'" />
        <img style="position:absolute;top:5.05in;left:2.51in;width:1.92in;height:0.59in" src="" />
        <img style="position:absolute;top:5.63in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_15.png').'" />
        <img style="position:absolute;top:5.05in;left:4.73in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:5.63in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_17.png').'" />
        <img style="position:absolute;top:5.05in;left:6.94in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:5.63in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_19.png').'" />
        <img style="position:absolute;top:5.93in;left:0.38in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:6.52in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_21.png').'" />
        <img style="position:absolute;top:5.93in;left:2.51in;width:1.92in;height:0.59in" src="" />
        <img style="position:absolute;top:6.52in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_23.png').'" />
        <img style="position:absolute;top:5.93in;left:4.73in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:6.52in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_25.png').'" />
        <img style="position:absolute;top:5.93in;left:6.94in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:6.52in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_27.png').'" />
        <div style="text-align: justify;position:absolute;top:6.68in;left:0.38in;width:1.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68">Información de precios</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
        <img style="position:absolute;top:6.97in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_28.png').'" />
        <img style="position:absolute;top:7.17in;left:0.38in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:7.75in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_30.png').'" />
        <img style="position:absolute;top:7.17in;left:2.51in;width:1.92in;height:0.59in" src="" />
        <img style="position:absolute;top:7.75in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_32.png').'" />
        <img style="position:absolute;top:8.05in;left:0.38in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:8.64in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_34.png').'" />
        <img style="position:absolute;top:8.05in;left:2.51in;width:1.92in;height:0.59in" src="" />
        <img style="position:absolute;top:8.64in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_36.png').'" />
        <img style="position:absolute;top:8.05in;left:4.73in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:8.64in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_38.png').'" />
        <img style="position:absolute;top:8.05in;left:6.94in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:8.64in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_40.png').'" />
        <img style="position:absolute;top:9.13in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_41.png').'" />
        <img style="position:absolute;top:9.47in;left:0.38in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:10.05in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_43.png').'" />
        <img style="position:absolute;top:9.47in;left:2.51in;width:1.92in;height:0.59in" src="" />
        <img style="position:absolute;top:10.05in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_45.png').'" />
        <img style="position:absolute;top:9.47in;left:4.73in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:10.05in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_47.png').'" />
        <img style="position:absolute;top:9.47in;left:6.94in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:10.05in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_49.png').'" />
        <img style="position:absolute;top:10.35in;left:0.38in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:10.93in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_51.png').'" />
        <img style="position:absolute;top:10.35in;left:2.51in;width:1.92in;height:0.59in" src="" />
        <img style="position:absolute;top:10.93in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_53.png').'" />
        <img style="position:absolute;top:10.35in;left:6.94in;width:1.93in;height:0.59in" src="" />
        <img style="position:absolute;top:10.93in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_55.png').'" />
        <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_56.png').'" />
        <div style="text-align: justify;position:absolute;top:9.42in;left:4.72in;width:0.38in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">IUSI  </span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:10.30in;left:2.51in;width:1.88in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Ingreso familiar requerido</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:10.30in;left:6.94in;width:1.17in;line-height:0.15in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cuota estimada mantenimiento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:9.42in;left:2.51in;width:1.24in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cuota de crédito</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:9.42in;left:0.38in;width:1.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Plazo financiamiento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:9.42in;left:6.94in;width:0.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Seguro</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:8.84in;left:0.38in;width:1.72in;line-height:0.21in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#de5b68">Información de cuota</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:10.30in;left:0.38in;width:0.85in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cuota total</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:8.01in;left:6.94in;width:1.20in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Saldo a financiar</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:5.89in;left:0.38in;width:0.98in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Habitaciones</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:1.70in;left:3.33in;width:0.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Correo</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:1.70in;left:6.28in;width:0.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Teléfono</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:5.00in;left:0.38in;width:0.57in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Módulo</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:5.00in;left:2.51in;width:0.39in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Nivel</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:5.00in;left:4.72in;width:1.00in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:5.00in;left:6.94in;width:0.63in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Tamaño</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:5.89in;left:2.51in;width:1.09in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Incluye cocina:</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:8.01in;left:4.72in;width:1.38in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Enganche mensual</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:5.89in;left:4.72in;width:1.75in;line-height:0.15in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Costo parqueo de moto extra</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:5.89in;left:6.94in;width:1.35in;line-height:0.15in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Parqueos de moto extra</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:7.12in;left:0.38in;width:1.76in;line-height:0.15in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Total parqueos de moto extra</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:7.12in;left:2.51in;width:0.86in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Precio total</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:1.70in;left:0.38in;width:1.04in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cotizado para</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:8.01in;left:0.38in;width:0.74in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Enganche</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:8.01in;left:2.51in;width:1.42in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Pagos de enganche</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/avalia-logo.png').'" />
        <img style="position:absolute;top:1.02in;left:7.14in;width:1.39in;height:0.41in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_3.png').'" />
        <div style="text-align: justify;position:absolute;top:0.96in;left:7.14in;line-height:0.37in;"><span style="font-style:normal;font-weight:bold;font-size:20pt;font-family:Effra;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.00in;left:0.38in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_4.png').'" />
        <div style="text-align: justify;position:absolute;top:2.09in;left:0.41in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$nombreCompleto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.09in;left:3.33in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_5.png').'" />
        <div style="text-align: justify;position:absolute;top:2.11in;left:3.36in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$correo.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.09in;left:6.28in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_6.png').'" />
        <div style="text-align: justify;position:absolute;top:2.10in;left:6.31in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$telefono.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:1.51in;left:7.56in;width:1.41in;height:0.35in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_7.png').'" />
        <div style="text-align: justify;position:absolute;top:1.60in;left:7.59in;line-height:0.23in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Effra;color:#ff9166">'.$fechaActual.'</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#ff9166"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.31in;left:2.50in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_8.png').'" />
        <div style="text-align: justify;position:absolute;top:5.41in;left:2.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$nivel.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.3in;left:4.71in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_9.png').'" />
        <div style="text-align: justify;position:absolute;top:5.41in;left:4.74in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.31in;left:6.92in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_10.png').'" />
        <div style="text-align: justify;position:absolute;top:5.40in;left:6.95in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$tamanio.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:6.20in;left:0.36in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_11.png').'" />
        <div style="text-align: justify;position:absolute;top:6.29in;left:0.39in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$noHabitacion.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.31in;left:0.36in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_12.png').'" />
        <div style="text-align: justify;position:absolute;top:5.40in;left:0.39in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$fase.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:6.20in;left:2.49in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_13.png').'" />
        <div style="text-align: justify;position:absolute;top:6.29in;left:2.52in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$conCocina.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        
        <div style="text-align: justify;position:absolute;top:6.29in;left:4.77in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$costoParqueo.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        
        <div style="text-align: justify;position:absolute;top:6.29in;left:6.95in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$parqueoExtra.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:7.44in;left:2.51in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_16.png').'" />
        <div style="text-align: justify;position:absolute;top:7.53in;left:2.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$precioTotal.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:7.52in;left:0.37in;width:1.93in;height:0.25in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_17.png').'" />
        <div style="text-align: justify;position:absolute;top:7.53in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$totalParqueos.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:8.31in;left:0.38in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_18.png').'" />
        <div style="text-align: justify;position:absolute;top:8.41in;left:0.41in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$engancheMonto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:8.32in;left:2.50in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_19.png').'" />
        <div style="text-align: justify;position:absolute;top:8.41in;left:2.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$enganchePagos.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:8.32in;left:4.72in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_20.png').'" />
        <div style="text-align: justify;position:absolute;top:8.41in;left:4.75in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$engancheMensual.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:8.31in;left:6.93in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_21.png').'" />
        <div style="text-align: justify;position:absolute;top:8.41in;left:6.96in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$saldoFinanciar.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:9.73in;left:0.37in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_22.png').'" />
        <div style="text-align: justify;position:absolute;top:9.82in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$plazoFinanciamiento.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:9.73in;left:2.50in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_23.png').'" />
        <div style="text-align: justify;position:absolute;top:9.82in;left:2.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$cuotaCredito.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:9.72in;left:4.71in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_24.png').'" />
        <div style="text-align: justify;position:absolute;top:9.82in;left:4.74in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$iusi.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:9.72in;left:6.92in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_25.png').'" />
        <div style="text-align: justify;position:absolute;top:9.81in;left:6.95in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$seguro.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:10.61in;left:0.37in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_26.png').'" />
        <div style="text-align: justify;position:absolute;top:10.71in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$cuotaTotal.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:10.61in;left:2.50in;width:1.93in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_27.png').'" />
        <div style="text-align: justify;position:absolute;top:10.71in;left:2.53in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$ingresoFamiliar.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:10.70in;left:6.93in;width:1.93in;height:0.25in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_28.png').'" />
        <div style="text-align: justify;position:absolute;top:10.71in;left:6.96in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$cuotaMantenimiento.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:11.58in;left:6.48in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_29.png').'" />
        <div style="text-align: justify;position:absolute;top:11.66in;left:7.09in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#fddfdb">'.$correoVendedor.'</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
        <img style="position:absolute;top:3.57in;left:3.59in;width:0.35in;height:0.18in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_30.png').'" />
        
        <img style="position:absolute;top:3.56in;left:4.12in;width:0.17in;height:0.17in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_31.png').'" />
        
        <div style="page-break-after: always;">
        </div>
        <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_9.png').'" />
        <img style="position:absolute;top:0.60in;left:0.38in;width:2.17in;height:0.89in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_14.png').'" />
        <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_57.png').'" />
        <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#de5b68">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
        <img style="position:absolute;top:1.95in;left:0.38in;width:2.57in;height:0.59in" src="" />
        <img style="position:absolute;top:2.53in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_59.png').'" />
        <img style="position:absolute;top:1.95in;left:3.33in;width:2.57in;height:0.59in" src="" />
        <img style="position:absolute;top:2.53in;left:3.33in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_61.png').'" />
        <img style="position:absolute;top:1.95in;left:6.28in;width:2.57in;height:0.59in" src="" />
        <img style="position:absolute;top:2.53in;left:6.28in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_63.png').'" />
        <img style="position:absolute;top:4.75in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_64.png').'" />
        <img style="position:absolute;top:4.98in;left:0.38in;width:4.18in;height:0.59in" src="" />
        <img style="position:absolute;top:5.57in;left:0.38in;width:4.19in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_66.png').'" />
        <img style="position:absolute;top:4.98in;left:4.73in;width:4.18in;height:0.59in" src="" />
        <img style="position:absolute;top:5.57in;left:4.72in;width:4.19in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_68.png').'" />
        <img style="position:absolute;top:8.11in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_69.png').'" />
        <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_70.png').'" />
        <div style="text-align: justify;position:absolute;top:4.46in;left:0.38in;width:1.98in;line-height:0.21in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#de5b68">Información de contacto</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:8.35in;left:0.38in;width:8.50in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Módulos:</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> Cada módulo/edificio contara con 6 niveles, 4 apartamentos por nivel. Los módulos no cuentan con elevadores.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:8.80in;left:0.38in;width:8.50in;line-height:0.20in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Parqueos:</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> Cada apartamento incluye 1 parqueo de motocicleta. Parqueos adicionales de motocicletas se venden por separado. En un terreno continuo al área de edificios, existira un número limitado de espacios para alquilar parqueos de vehículos.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:9.64in;left:0.38in;width:8.50in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Servicios:</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> Cada apartamento deberá pagar su cuota de mantenimiento, que le dara acceso a: A) Cierta cantidad de m³</span><span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Effra Light;color:#152746"></span></SPAN><br/></div>
        <div style="text-align: justify;position:absolute;top:9.64in;left:0.38in;width:8.50in;line-height:0.19in;"><DIV style="position:relative; left:8.29in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></DIV><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:10.24in;left:0.38in;width:8.50in;line-height:0.20in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Reglas de Convivencia:</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> Al vivir en una comunidad, todos los propietarios y habitantes del proyecto, estarán obligados a cumplir con normas de convivencia para el beneficio de todos, las cuales incluyen ornato, respeto dentro de su apartamento como hacia sus vecinos, cumplir con sus pagos de mantenimiento en tiempo, evitar los excesos que afecten la vida en comunidad.</span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:1.90in;left:0.38in;width:1.04in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cotizado para</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:1.90in;left:3.33in;width:0.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Correo</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:1.90in;left:6.28in;width:0.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Teléfono</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:2.78in;left:0.38in;width:3.17in;line-height:0.21in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#de5b68">Requisitos para adquirir tu apartamento</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:4.94in;left:0.38in;width:0.72in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Contacto</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:4.94in;left:4.72in;width:1.37in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Teléfono contacto</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:5.83in;left:0.38in;width:8.50in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">Precio total incluye gastos de escrituración e impuestos de traspaso. Monto reserva: Q. 2,000.00 que serán acreditados al valor del enganche.</span><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746"> *Gabinetes de cocina y electrodomésticos se venden por separado</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:6.43in;left:0.38in;width:8.50in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">La presente cotización tiene una vigencia de 10 días hábiles contados a partir de la fecha de emisión</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:6.84in;left:0.38in;width:8.50in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">Este material es utilizado para fines informativos y de referencia. </span><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">SPV NAOS, S.A. </span> <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">se reserva el derecho de hacer modificaciones a los modelos a su discreción para efectos del desarrollo del proyecto.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:7.44in;left:0.38in;width:8.50in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">Los precios están sujetos a cambios sin previo aviso. Este material es utilizado para fines informativos y de referencia.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:7.82in;left:0.38in;width:2.65in;line-height:0.21in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#de5b68">Descripción general del proyecto</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:3.18in;left:0.45in;width:3.49in;line-height:0.18in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">a) Tener buenas referencias de crédito y personales.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:3.43in;left:0.45in;width:8.17in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">b) Tener hasta un 40% de sus ingresos netos comprobables disponibles para poder realizar el pago del financiamiento por la</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">compra.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <div style="text-align: justify;position:absolute;top:3.88in;left:0.45in;width:5.40in;line-height:0.18in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">c) Por lo menos 1 año de continuidad laboral al momento de aplicar para el crédito</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/avalia-logo.png').'" />
        <img style="position:absolute;top:1.01in;left:7.13in;width:1.39in;height:0.41in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_32.png').'" />
        <div style="text-align: justify;position:absolute;top:0.96in;left:7.14in;line-height:0.37in;"><span style="font-style:normal;font-weight:bold;font-size:20pt;font-family:Effra;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:1.73in;left:7.54in;width:1.41in;height:0.35in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_33.png').'" />
        <div style="text-align: justify;position:absolute;top:1.82in;left:7.57in;line-height:0.23in;"><span style="font-style:normal;font-weight:bold;font-size:12pt;font-family:Effra;color:#ff9166">'.$fechaActual.'</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#ff9166"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.22in;left:0.37in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_34.png').'" />
        <div style="text-align: justify;position:absolute;top:2.31in;left:0.40in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$nombreCompleto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.23in;left:3.32in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_35.png').'" />
        <div style="text-align: justify;position:absolute;top:2.31in;left:3.35in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$correo.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:2.23in;left:6.27in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_36.png').'" />
        <div style="text-align: justify;position:absolute;top:2.31in;left:6.30in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$telefono.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.23in;left:0.36in;width:4.18in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_37.png').'" />
        <div style="text-align: justify;position:absolute;top:5.33in;left:0.39in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$contacto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:5.24in;left:4.74in;width:4.14in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_38.png').'" />
        <div style="text-align: justify;position:absolute;top:5.34in;left:4.77in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$telefonoContacto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
        <img style="position:absolute;top:11.59in;left:6.40in;width:2.57in;height:0.33in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_39.png').'" />
        <div style="text-align: justify;position:absolute;top:11.67in;left:7.02in;line-height:0.22in;"><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#fddfdb">'.$correoVendedor.'</span><span style="font-style:normal;font-weight:normal;font-size:12pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>

        </body>
        </html>';
    
    }
    
    $dompdf->load_html($texto_r);

    // (Optional) Setup the paper size and orientation 595.275
    $customPaper = array(0,0,666.141,864.56);
    $dompdf->setPaper($customPaper);

    // Render the HTML as PDF
    $dompdf->render();
    $_path = "../public/";
    $pdfD = $dompdf->output();
    file_put_contents($_path.$nombre, $pdfD);
    $filename = $_path.$nombre; // el nombre con el que se descargará, puede ser diferente al original 
    header("Content-type: application/octet-stream"); 
    header("Content-Type: application/force-download"); 
    header("Content-Disposition: attachment; filename=\"$nombre\"\n"); 
    readfile($filename); 
    unlink($filename);



    // Enviamos el fichero PDF al navegador.
    //$dompdf->stream($nombre);

}
if(isset($_GET['enganchePdf'])){
    $idEnganche=$_GET['idEnganche'];
    //$idEnganche=0;
    $strQueryPdf = "SELECT ag.*, c.*,a.bodega_precio, a.cuartos,a.sqmts,a.jardin_mts,((dg.cambioDolar * a.precio) + (c.parqueosExtras * dg.parqueoExtra * dg.cambioDolar) +(c.parqueosExtrasMoto * dg.parqueoExtraMoto * dg.cambioDolar) + (c.bodegasExtras * a.bodega_precio * dg.cambioDolar) ) as precioTotal,
                        ( ((dg.cambioDolar * a.precio) + (c.parqueosExtras * dg.parqueoExtra * dg.cambioDolar) +(c.parqueosExtrasMoto * dg.parqueoExtraMoto * dg.cambioDolar) + (c.bodegasExtras * a.bodega_precio * dg.cambioDolar)) - c.descuento_porcentual_monto ) as precioNeto,
                        CONCAT(IFNULL(CONCAT(u.primer_nombre,' '),''),IFNULL(CONCAT(u.primer_apellido,' '),'')) as nombreVendedor, u.mail,u.telefono as telefonoVendedor,
                        (dg.iusi/10) as tasaIusi,seguro as tasaSeguro,dg.porcentajeFacturacion, dg.rate as tasaInteres,dg.parqueoExtra as costoParqueo,
                        dg.cocinaTipoA,dg.cocinaTipoB,dg.fechaProyecto,cp.pais,
                        IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                        IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as nombreCompleto,ag.correoElectronico as correo, ag.telefono,
                        (c.bodegasExtras + a.bodega_mts) as bodegasExtras,(a.parqueo + a.parqueo_moto + parqueosExtras + parqueosExtrasMoto) as totalParqueos,t.fechaEntrega as fechaProyecto
                        FROM  enganche c
                        INNER JOIN  apartamentos a ON c.apartamento = a.apartamento
                        INNER JOIN  datosGlobales dg on a.idProyecto = dg.idGlobal
                        INNER JOIN  torres t on dg.idGlobal = t.proyecto AND t.noTorre = c.torres
                        LEFT JOIN  usuarios u ON c.idVendedor = u.id_usuario
                        INNER JOIN  agregarCliente ag ON c.idCliente= ag.idCliente
                        LEFT JOIN  catPais cp ON ag.Nacionalidad = cp.id_pais
                        WHERE c.idEnganche={$idEnganche};";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQueryPdf);
    $rTmp = $conn->db_fetch_object($qTmp);

    $montoCocina=0;
    if($rTmp->cocina=='cocinaTipoA'){
        $montoCocina=$rTmp->cocinaTipoA;
    }
    else if($rTmp->cocina=='cocinaTipoB'){
        $montoCocina=$rTmp->cocinaTipoB;
    }
    $intIdOcaCliente=$rTmp->idCliente;
    $precioNeto=$rTmp->precioNeto+$montoCocina;
    $precioTotal=$rTmp->precioTotal+$montoCocina;
    $im = $rTmp->tasaInteres/ 12 / 100;	
    $Im2 = $im + 1 ;			
    $im2 = pow(	($Im2), - (12 * $rTmp->plazoFinanciamiento ) );
    $cuotaCredito = (($precioNeto - $rTmp->enganchePorcMonto) * $im) / (1- $im2 ) ;					
    $cuotaSeguro = (($precioNeto*$rTmp->tasaSeguro)/100)/12;
    $ventaPorcionFactura = ($precioNeto * $rTmp->porcentajeFacturacion)/100;
    $cuotaIusi = (($ventaPorcionFactura * $rTmp->tasaIusi)/100)/12;
    $cuotaMensual = $cuotaIusi + $cuotaSeguro + $cuotaCredito ; 
    $ingresoFamiliar = $cuotaMensual/0.35;
   
    if($rTmp->cocina=='Sin cocina' || $rTmp->cocina==''){
        $cocina = 'No';
    }else{
        $cocina = 'Si';
    }
    $montoConImpuesto = (round($precioNeto) * $rTmp->porcentajeFacturacion)/100;
    $impuesto =  $montoConImpuesto - ($montoConImpuesto/ 1.12) ;
    $impuestoAccion = (round($precioNeto) - $montoConImpuesto) *0.03;
    $impuestoAccionOtros = 0;
    $impuestoAccionTotal = $impuesto + $impuestoAccion + $impuestoAccionOtros; 
    $meses = array(1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
   
    $Fha=0;
    if($rTmp->creditoFHA=='no'){
        $Fha='No';
    }else if($rTmp->creditoFHA=='si'){
        $Fha='Si';
    }
    //$pdf_contrato->Image('imagenPrueba' , 80 ,22, 35 , 38,'PNG', '../img/client_icon.png');
    $strQueryPdfC = "SELECT c.*,cp.pais,CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                        IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),''))  as nombreCompleto,c.correoElectronico as correo, c.telefono
                        FROM  codeudor c
                        INNER JOIN  enganche e ON c.idEnganche= e.idEnganche
                        LEFT JOIN  catPais cp ON c.Nacionalidad = cp.id_pais
                        WHERE c.idEnganche={$idEnganche};";

            //echo $strQuery;
            $conCodeudor=0;
            $qTmpC = $conn ->db_query($strQueryPdfC);
            if($rTmpC = $conn->db_fetch_object($qTmpC)){
                $idCodeudor = $rTmpC->idCodeudor!=''?$rTmpC->idCodeudor:0;
                $nitC=$rTmpC->nit;
                $nacionalidadC=$rTmpC->pais;
                $dpiC=$rTmpC->numeroDpi;
                $fechaEmisionC=date('d/m/Y',strtotime($rTmpC->fechaEmisionDpi));
                $fechaNacimientoC=date('d/m/Y',strtotime($rTmpC->fechaNacimiento));
                $estadoCivilC=$rTmpC->estadoCivil;
                $empresaLaboraC=$rTmpC->empresaLabora;
                $puestoC=$rTmpC->puestoLabora;
                $direccionC=$rTmpC->direccion;
                $direccionLaboraC=$rTmpC->direccionEmpresa;
                $sueldoC='Q.'.number_format(round($rTmpC->salarioMensual),2,".",",");
                $montoIngresosC='Q.'.number_format(round($rTmpC->montoOtrosIngresos),2,".",",");
                $ingresosC=$rTmpC->otrosIngresos;
                $nombreCompletoC=$rTmpC->nombreCompleto;
                $correoC=$rTmpC->correo;
                $telefonoC=$rTmpC->telefono;
                if($rTmpC->creditoFHA=='no'){
                    $FhaC='No';
                }else if($rTmpC->creditoFHA=='si'){
                    $FhaC='Si';
                }
                $conCodeudor=1;
            }
    $habitaciones=$rTmp->cuartos.' dormitorios';
    $codigo=$rTmp->codigo;
    $nit=$rTmp->nit;
    $dpi=$rTmp->numeroDpi;
    $fechaEmision=date('d/m/Y',strtotime($rTmp->fechaEmisionDpi));
    $nacionalidad=$rTmp->pais;
    $fechaNacimiento=date('d/m/Y',strtotime($rTmp->fechaNacimiento));
    $estadoCivil=$rTmp->estadoCivil;
    $empresaLabora=$rTmp->empresaLabora;
    $puesto=$rTmp->puestoLabora;
    $direccion=$rTmp->direccion;
    $direccionLabora=$rTmp->direccionEmpresa;
    $sueldo='Q.'.number_format(round($rTmp->salarioMensual),2,".",",");
    $montoIngresos='Q.'.number_format(round($rTmp->montoOtrosIngresos),2,".",",");
    $ingresos=$rTmp->otrosIngresos;
    $Fha=$Fha;
    $noDeposito=$rTmp->noDepositoReserva;
    $bancoDeposito=$rTmp->bancoDepositoReserva;
    $noCheque=$rTmp->noChequeReserva;
    $bancoCheque=$rTmp->bancoChequeReserva;
    $modoPago=$rTmp->formaPago;
    $fechaEntrega=date('d/m/Y',strtotime($rTmp->fechaProyecto));
    $fase='Modulo '.$rTmp->torres;
    $torre=$rTmp->torres;
    $costoParqueo=$rTmp->costoParqueo;
    $bodegasPrecio=$rTmp->bodega_precio;
    $parqueoExtra=$rTmp->parqueosExtras + $rTmp->parqueosExtrasMoto;
    $totalParqueos='Q.'.number_format(round($rTmp->costoParqueo * $rTmp->parqueosExtras),2,".",",");
    $conCocina=$cocina;
    $nivel="Nivel ".$rTmp->nivel;
    $apartamento=$rTmp->apartamento;
    $nombreCompleto=$rTmp->nombreCompleto;
    $correo=$rTmp->correo;
    $telefono=$rTmp->telefono;
    $tamanio=$rTmp->sqmts;
    $noHabitacion=$rTmp->cuartos;
    $noHabitacionSec = $noHabitacion -1;
    $parqueos=$rTmp->totalParqueos;
    $areaJardin=$rTmp->jardin_mts;
    $bodegas=$rTmp->bodegasExtras;
    $precioSinImpuestoAccionTotal='Q.'.number_format(round($precioNeto) - $impuestoAccionTotal,2,".",",");
    $precioSinImpuestoAccion='Q.'.number_format((round($precioNeto) - $montoConImpuesto) - $impuestoAccion,2,".",",");
    $precioTotal='Q.'.number_format($precioTotal,2,".",",");
    $montoConImpuestoAccion='Q.'.number_format(round($precioNeto) - $montoConImpuesto,2,".",",");
    $precioSinImpuesto='Q.'.number_format($montoConImpuesto - $impuesto,2,".",",");
    $montoConImpuesto='Q.'.number_format($montoConImpuesto,2,".",",");
    $montoConImpuestoOtros='Q.'.number_format(0,2,".",",");
    $montoConImpuestoTotal='Q.'.number_format(round($precioNeto),2,".",",");
    $impuesto='Q.'.number_format($impuesto,2,".",",");
    $impuestoAccion='Q.'.number_format($impuestoAccion,2,".",",");
    $impuestoAccionOtros='Q.'.number_format($impuestoAccionOtros,2,".",",");
    $impuestoAccionTotal='Q.'.number_format($impuestoAccionTotal,2,".",",");
    $precioSinImpuestoAccionOtros='Q.'.number_format(0,2,".",",");
    $pagoPromesa='Q.'.number_format(0,2,".",",");
    $descuento=$rTmp->descuento_porcentual.' %';
    $saldoContraEntrega='Q.'.number_format(round($precioNeto - $rTmp->enganchePorcMonto),2,".",",");
    $pagoContraEntrega='Q.'.number_format(round($precioNeto - $rTmp->enganchePorcMonto),2,".",",");
    $precioNeto='Q.'.number_format(round($precioNeto),2,".",",");
    $engancheMonto='Q.'.number_format(round($rTmp->enganchePorcMonto - $rTmp->MontoReserva),2,".",",");
    $engancheMontoCompleto='Q.'.number_format(round($rTmp->enganchePorcMonto),2,".",",");
    $enganche=$rTmp->enganchePorc.' %';
    $engancheNombre='Enganche '.$rTmp->enganchePorc.' %';
    $enganchePagos=$rTmp->pagosEnganche;
    $engancheMensual='Q.'.number_format(($rTmp->enganchePorcMonto - $rTmp->MontoReserva)/$rTmp->pagosEnganche,2,".",",");
    $reserva='Q.'.number_format($rTmp->MontoReserva,2,".",",");
    $cuotaMantenimiento='Q.'.number_format(400,2,".",",");
    $correoVendedor=$rTmp->mail;
    $fechaActual=date("d/m/Y");
    $plazoFinanciamiento=$rTmp->plazoFinanciamiento.' Años';
    $cuotaCredito='Q.'.number_format(round($cuotaCredito),2,".",",");
    $cuotaTotal='Q.'.number_format(round($cuotaMensual),2,".",",");
    $iusi='Q.'.number_format(round($cuotaIusi),2,".",",");
    $seguro='Q.'.number_format(round($cuotaSeguro),2,".",",");
    $ingresoFamiliar='Q.'.number_format(round($ingresoFamiliar),2,".",",");
    $contacto=$rTmp->nombreVendedor;
    $telefonoContacto=$rTmp->telefonoVendedor;
    $NoReserva='Reserva';
    $observaciones=$rTmp->observaciones.'. '.$rTmp->observacionesForm;
    $cantReserva='Q.'.number_format($rTmp->MontoReserva,2,".",",");
    $diaReserva=date('d',strtotime($rTmp->fechaPagoReserva));
    $mesReserva=$meses[date('n',strtotime($rTmp->fechaPagoReserva))];
    $anioReserva=date('Y',strtotime($rTmp->fechaPagoReserva));

    if($rTmp->proyecto=='Marabi'){
        $nombre = 'Enganche_'.$apartamento."_".date('dmYHis').".pdf";
        $dompdf = new Dompdf();
        $texto_r='
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <html>
        <head>
            <link rel="stylesheet" type="text/css" href="../css/styleEngMarabi.css"/>
            <style>
                @page {
                    margin:0;padding:0; // you can set margin and padding 0 
                } 
                @font-face {
                    font-family: Effra Light;
                    src: url("Effra_Std_Lt.ttf");
                }
                @font-face {
                    font-family: Effra;
                    src: url("Effra_Std_Rg.ttf");
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/vi_1.png').'" />
            <img style="position:absolute;top:0.60in;left:0.38in;width:1.17in;height:0.46in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ri_1.png').'" />
            <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_1.png').'" />
            <div style="text-align: justify;text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#fddfdb">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:0.99in;left:7.14in;width:1.41in;line-height:0.34in;"><span style="font-style:normal;font-weight:bold;font-size:20pt;font-family:Effra;color:#fddfdb">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.60in;left:0.38in;width:2.09in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#ff9267">Información de apartamento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.75in;left:0.38in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.33in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_3.png').'" />
            <img style="position:absolute;top:1.75in;left:3.33in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.33in;left:3.33in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_5.png').'" />
            <img style="position:absolute;top:1.75in;left:6.28in;width:2.57in;height:0.59in" src="" />
            <img style="position:absolute;top:2.33in;left:6.28in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_7.png').'" />
            <img style="position:absolute;top:2.89in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_8.png').'" />
            <img style="position:absolute;top:3.22in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:3.80in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_10.png').'" />
            <img style="position:absolute;top:3.22in;left:2.51in;width:1.92in;height:0.59in" src="" />
            <img style="position:absolute;top:3.80in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_12.png').'" />
            <img style="position:absolute;top:3.22in;left:4.73in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:3.80in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_14.png').'" />
            <img style="position:absolute;top:3.22in;left:6.94in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:3.80in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_16.png').'" />
            <img style="position:absolute;top:4.10in;left:4.73in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:4.68in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_18.png').'" />
            <img style="position:absolute;top:4.10in;left:6.94in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:4.68in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_20.png').'" />
            <img style="position:absolute;top:4.10in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:4.68in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_22.png').'" />
            <img style="position:absolute;top:4.10in;left:2.51in;width:1.92in;height:0.59in" src="" />
            <img style="position:absolute;top:4.68in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_24.png').'" />
            <div style="text-align: justify;position:absolute;top:5.00in;left:0.38in;width:1.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#ff9267">Información de precios</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <img style="position:absolute;top:5.29in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_25.png').'" />
            <img style="position:absolute;top:5.65in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:6.23in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_27.png').'" />
            <img style="position:absolute;top:5.65in;left:2.51in;width:1.92in;height:0.59in" src="" />
            <img style="position:absolute;top:6.23in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_29.png').'" />
            <img style="position:absolute;top:5.65in;left:4.73in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:6.23in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_31.png').'" />
            <img style="position:absolute;top:5.65in;left:6.94in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:6.23in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_33.png').'" />
            <img style="position:absolute;top:6.53in;left:0.38in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:7.12in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_35.png').'" />
            <img style="position:absolute;top:6.53in;left:2.51in;width:1.92in;height:0.59in" src="" />
            <img style="position:absolute;top:7.12in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_37.png').'" />
            <img style="position:absolute;top:6.53in;left:4.73in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:7.12in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_39.png').'" />
            <img style="position:absolute;top:6.53in;left:6.94in;width:1.93in;height:0.59in" src="" />
            <img style="position:absolute;top:7.12in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_41.png').'" />
            <div style="text-align: justify;position:absolute;top:7.40in;left:0.38in;width:1.62in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#ff9267">Desgloce de inversión</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <img style="position:absolute;top:7.69in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_42.png').'" />
            <div style="text-align: justify;position:absolute;top:8.25in;left:2.72in;width:1.52in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Monto con impuesto</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.25in;left:5.30in;width:0.80in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Impuestos</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.25in;left:7.16in;width:1.52in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Precio sin impuestos</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.89in;left:0.38in;width:1.88in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Inmuebles 70% (IVA 12%)</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.89in;left:2.99in;width:0.99in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$montoConImpuesto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.89in;left:5.25in;width:0.90in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$impuesto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.89in;left:7.42in;width:0.99in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$precioSinImpuesto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:9.32in;left:0.38in;width:8.47in;height:0.03in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_43.png').'" />
            <img style="position:absolute;top:9.34in;left:0.38in;width:8.48in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_44.png').'" />
            <div style="text-align: justify;position:absolute;top:9.52in;left:0.38in;width:1.88in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Acción 30% (Timbres 3%)</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.52in;left:2.99in;width:0.99in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$montoConImpuestoAccion.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.52in;left:5.30in;width:0.81in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$impuestoAccion.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.52in;left:7.42in;width:0.99in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$precioSinImpuestoAccion.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:9.95in;left:0.38in;width:8.47in;height:0.03in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_45.png').'" />
            <img style="position:absolute;top:9.97in;left:0.38in;width:8.48in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_46.png').'" />
            <div style="text-align: justify;position:absolute;top:10.15in;left:0.38in;width:0.46in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Otros</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.15in;left:3.24in;width:0.50in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$montoConImpuestoOtros.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.15in;left:5.45in;width:0.50in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$impuestoAccionOtros.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.15in;left:7.67in;width:0.50in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$precioSinImpuestoAccionOtros.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:10.58in;left:0.38in;width:8.47in;height:0.03in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_47.png').'" />
            <img style="position:absolute;top:10.61in;left:0.38in;width:8.48in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_48.png').'" />
            <div style="text-align: justify;position:absolute;top:10.79in;left:0.38in;width:0.41in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Total</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.79in;left:2.92in;width:1.14in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">'.$montoConImpuestoTotal.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.79in;left:5.25in;width:0.91in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">'.$impuestoAccionTotal.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.79in;left:7.42in;width:1.00in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">'.$precioSinImpuestoAccionTotal.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_49.png').'" />
            <div style="text-align: justify;position:absolute;top:4.44in;left:0.38in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$parqueos.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.90in;left:2.51in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$descuento.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.44in;left:2.51in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$areaJardin.' m²</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.0in;left:4.73in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$reserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.0in;left:6.94in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$enganchePagos.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.44in;left:6.94in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$bodegasPrecio.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.44in;left:4.73in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$bodegas.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.90in;left:0.38in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$precioTotal.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.54in;left:6.94in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$noHabitacion.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.0in;left:2.51in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$engancheMonto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.54in;left:4.73in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$tamanio.' m²</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.54in;left:5.08in;width:2.55in;line-height:0.10in;"><span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:6pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.54in;left:2.51in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.54in;left:0.38in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$nivel.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.90in;left:4.73in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$precioNeto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.05in;left:6.28in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$telefono.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.05in;left:3.33in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$correo.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.90in;left:6.94in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"'.$pagoPromesa.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.05in;left:0.38in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$nombreCompleto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.0in;left:0.38in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$enganche.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.49in;left:4.73in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Precion neto</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.49in;left:2.51in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Descuento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.49in;left:0.38in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Total con impuestos</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.49in;left:6.94in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Pago de promesa</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.60in;left:6.94in;width2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cantidad de cuotas</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.60in;left:4.73in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Reserva</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.16in;left:0.38in;width:2.55in;line-height:0.32in;"><span style="font-style:normal;font-weight:bold;font-size:18pt;font-family:Effra;color:#ff9267">Desglose de inversión</span><span style="font-style:normal;font-weight:normal;font-size:18pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.60in;left:2.51in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Enganche</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.60in;left:0.38in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">% de enganche</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.05in;left:2.51in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Área de Jardín</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.70in;left:0.38in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Cotizado para</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.05in;left:0.38in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Parqueo(s)</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.05in;left:6.94in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Precio de bodegas</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.05in;left:4.73in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Bodegas</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.17in;left:6.94in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Habitaciones</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.17in;left:4.73in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Tamaño</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.17in;left:2.51in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.17in;left:0.38in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Nivel</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.70in;left:6.28in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Teléfono</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.70in;left:3.33in;width:2.55in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Correo</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/avalia-logo.png').'" />
            <div style="page-break-after: always;">
            </div>
            <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/vi_2.png').'" />
            <img style="position:absolute;top:0.60in;left:0.38in;width:1.17in;height:0.46in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ri_3.png').'" />
            <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_50.png').'" />
            <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#fddfdb">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:0.99in;left:7.14in;width:1.41in;line-height:0.34in;"><span style="font-style:normal;font-weight:bold;font-size:20pt;font-family:Effra;color:#fddfdb">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.64in;left:0.62in;width:2.10in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#ff9267">Enganche '.$enganche.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.64in;left:2.41in;width:2.00in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#ff9267">'.$engancheMonto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.88in;left:0.38in;width:8.47in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_51.png').'" />
            <img style="position:absolute;top:1.89in;left:0.38in;width:8.48in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_52.png').'" />
            <div style="text-align: justify;position:absolute;top:1.96in;left:2.57in;width:0.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Cantidad</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.96in;left:4.61in;width:0.27in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Día</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.96in;left:6.41in;width:0.33in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Mes</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.96in;left:8.24in;width:0.32in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Año</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.25in;left:0.86in;width:0.61in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Reserva</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.25in;left:2.44in;width:0.93in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$cantReserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.25in;left:4.63in;width:0.21in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$diaReserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.25in;left:6.19in;width:0.76in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$mesReserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.25in;left:8.21in;width:0.39in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$anioReserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>';
            
            $count=1;
            $top=0;
            $topl=0;
            $strQueryDetalle = "SELECT * 
            FROM  prograEnganche pe
            INNER JOIN  prograEngancheDetalle ped on pe.idEnganche = ped.idEnganche
            where pe.idEnganche={$idEnganche}
            ORDER BY ped.noPago";
        
            //echo $strQuery;
            $qTmpD = $conn ->db_query($strQueryDetalle);
            while ($rTmpD = $conn->db_fetch_object($qTmpD)){
                $rTmpD;
                $topDesc=2.50 + $top;
                $topline=2.76 + $topl; 
                $texto_r .='
                <div style="text-align: justify;position:absolute;top:'.$topDesc.'in;left:1.11in;width:0.12in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">'.$count.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
                <div style="text-align: justify;position:absolute;top:'.$topDesc.'in;left:2.50in;width:1.5in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">Q.'.number_format($rTmpD->montoReal,2,".",",").'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
                <div style="text-align: justify;position:absolute;top:'.$topDesc.'in;left:4.63in;width:1.5in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.date('d',strtotime($rTmpD->fechaPago)).'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
                <div style="text-align: justify;position:absolute;top:'.$topDesc.'in;left:6.19in;width:1.5in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$meses[date('n',strtotime($rTmpD->fechaPago))].'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
                <div style="text-align: justify;position:absolute;top:'.$topDesc.'in;left:8.21in;width:1.5in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.date('Y',strtotime($rTmpD->fechaPago)).'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>';
                
                $count++;
                $top=$top+0.25;
                $topl=$topl+0.29;
            }
            $texto_r .='
            <div style="text-align: justify;position:absolute;top:10.77in;left:0.38in;width:0.93in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$engancheMontoCompleto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.77in;left:3.33in;width:0.93in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$reserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.77in;left:6.28in;width:1.03in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$saldoContraEntrega.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.16in;left:0.38in;width:2.74in;line-height:0.32in;"><span style="font-style:normal;font-weight:bold;font-size:18pt;font-family:Effra;color:#ff9267">Desglose de inversión</span><span style="font-style:normal;font-weight:normal;font-size:18pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.37in;left:0.38in;width:2.10in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Total de cuotas de enganche</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.37in;left:3.33in;width:0.61in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Reserva</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.37in;left:6.28in;width:2.01in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Último pago contra entrega</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.00in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_96.png').'" />
            <img style="position:absolute;top:11.00in;left:3.33in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_98.png').'" />
            <img style="position:absolute;top:11.00in;left:6.28in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_100.png').'" />
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_101.png').'" />
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/avalia-logo.png').'" />
            <div style="page-break-after: always;">
            </div>
            <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/vi_3.png').'" />
            <img style="position:absolute;top:0.60in;left:0.38in;width:1.17in;height:0.46in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ri_5.png').'" />
            <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_102.png').'" />
            <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#fddfdb">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:0.99in;left:7.14in;width:1.41in;line-height:0.34in;"><span style="font-style:normal;font-weight:bold;font-size:20pt;font-family:Effra;color:#fddfdb">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#fddfdb"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.67in;left:0.38in;width:0.97in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Condiciones:</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.20in;left:0.38in;width:8.11in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$observaciones.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            
            <img style="position:absolute;top:7.47in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_104.png').'" />
            
            <img style="position:absolute;top:7.47in;left:3.33in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_106.png').'" />
            
            <img style="position:absolute;top:7.47in;left:6.28in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_108.png').'" />
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/ci_109.png').'" />
            <div style="text-align: justify;position:absolute;top:1.16in;left:0.38in;width:2.74in;line-height:0.32in;"><span style="font-style:normal;font-weight:bold;font-size:18pt;font-family:Effra;color:#ff9267">Desglose de inversión</span><span style="font-style:normal;font-weight:normal;font-size:18pt;font-family:Effra;color:#ff9267"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.59in;left:0.38in;width:0.81in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Firmante 1</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.59in;left:3.33in;width:0.81in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Firmante 2</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.59in;left:6.28in;width:0.81in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Firmante 3</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEngancheMarabi/OutDocument/avalia-logo.png').'" />
        </body>
        </html>
        ';

    }else if($rTmp->proyecto=='Naos'){
        $nombre = 'Enganche_'.$apartamento."_".date('dmYHis').".pdf";
        $dompdf = new Dompdf();
        $countP=1;
        if($Fha == 'No'){
            $colorLetrasSi='#152746';
            $colorLetrasNo='#FFFFFF';
            $fondoNo='37';
            $fondoSi='36';
        }else if($Fha == 'Si'){
            $colorLetrasSi='#FFFFFF';
            $colorLetrasNo='#152746'; 
            $fondoNo='36';
            $fondoSi='37';
        }

        if($FhaC == 'No'){
            $colorLetrasSiC='#152746';
            $colorLetrasNoC='#FFFFFF';
            $fondoNoC='37';
            $fondoSiC='36';
        }else if($FhaC == 'Si'){
            $colorLetrasSiC='#FFFFFF';
            $colorLetrasNoC='#152746'; 
            $fondoNoC='36';
            $fondoSiC='37';
        }
        

        $texto_r='
            <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
            <html>
            <head>
                <link rel="stylesheet" type="text/css" href="../css/styleEngNaos.css"/>
                <style>
                    @page {
                        margin:0;padding:0; // you can set margin and padding 0 
                    } 
                    @font-face {
                        font-family: Effra Light;
                        src: url("Effra_Std_Lt.ttf");
                    }
                    @font-face {
                        font-family: Effra;
                        src: url("Effra_Std_Rg.ttf");
                        font-weight: bold;
                    }
                    .borderDiv {
                        border: solid;
                      }
                </style>
            </head>
            <body>
            <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_1.png').'" />
            <img style="position:absolute;top:0.34in;left:0.38in;width:1.42in;height:0.58in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/logo naos.png').'" />
            <img style="position:absolute;top:0.34in;left:6.95in;width:2.32in;height:0.67in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_1.png').'" />
            <div style="text-align: justify;position:absolute;top:0.45in;left:7.14in;width:1.30in;line-height:0.18in;"><span style="font-style:normal;font-weight:bold;font-size:14pt;font-family:Effra;color:#de5b68">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra;color:#de5b68"> </span><br/><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_2.png').'" />
            <div style="text-align: justify;position:absolute;top:2.60in;left:0.38in;width:1.23in;line-height:0.18in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#de5b68">Datos personales</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.89in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_3.png').'" />

            <img style="position:absolute;top:3.43in;left:0.38in;width:4.07in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_5.png').'" />
            
            <img style="position:absolute;top:3.43in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_7.png').'" />
            
            <img style="position:absolute;top:3.43in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_9.png').'" />
            
            <img style="position:absolute;top:4.07in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_11.png').'" />
            
            <img style="position:absolute;top:4.07in;left:2.50in;width:6.37in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_13.png').'" />
            
            <img style="position:absolute;top:4.73in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_15.png').'" />
            
            <img style="position:absolute;top:4.73in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_17.png').'" />
            
            <img style="position:absolute;top:4.73in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_19.png').'" />
            
            <img style="position:absolute;top:5.37in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_21.png').'" />
            
            <img style="position:absolute;top:4.73in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_23.png').'" />
            
            <img style="position:absolute;top:5.37in;left:2.50in;width:4.07in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_25.png').'" />
            
            <img style="position:absolute;top:5.37in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_27.png').'" />
            
            <img style="position:absolute;top:6.00in;left:0.38in;width:4.07in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_29.png').'" />
            
            <img style="position:absolute;top:6.00in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_31.png').'" />
            
            <img style="position:absolute;top:6.00in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_33.png').'" />
            
            <img style="position:absolute;top:6.63in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_35.png').'" />
            <img style="position:absolute;top:6.40in;left:2.51in;width:0.90in;height:0.24in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_'.$fondoSi.'.png').'" />
            <img style="position:absolute;top:6.40in;left:2.52in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_2.png').'" />
            <img style="position:absolute;top:6.62in;left:2.52in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_3.png').'" />
            <img style="position:absolute;top:6.41in;left:2.51in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_4.png').'" />
            <img style="position:absolute;top:6.41in;left:3.39in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_5.png').'" />
            <div style="text-align: justify;position:absolute;top:6.37in;left:2.90in;width:0.16in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:'.$colorLetrasSi.'">Si</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:6.40in;left:3.55in;width:0.90in;height:0.24in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_'.$fondoNo.'.png').'" />
            <img style="position:absolute;top:6.40in;left:3.56in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_6.png').'" />
            <img style="position:absolute;top:6.62in;left:3.56in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_7.png').'" />
            <img style="position:absolute;top:6.41in;left:3.55in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_8.png').'" />
            <img style="position:absolute;top:6.41in;left:4.44in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_9.png').'" />
            <div style="text-align: justify;position:absolute;top:6.37in;left:3.90in;width:0.24in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:'.$colorLetrasNo.'">No</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#ffffff"> </span><br/></SPAN></div>
            
            <img style="position:absolute;top:6.63in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_39.png').'" />
            <div style="text-align: justify;position:absolute;top:5.78in;left:4.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$sueldo.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.52in;left:4.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$nacionalidad.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.87in;left:2.51in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$direccion.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.78in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$direccionLabora.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.87in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$fechaEmision.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.52in;left:2.51in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$correo.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.22in;left:6.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$dpi.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.22in;left:4.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$nit.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.17in;left:6.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$puesto.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.22in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$nombreCompleto.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.78in;left:6.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$montoIngresos.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.52in;left:6.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$fechaNacimiento.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.44in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$ingresos.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.17in;left:2.51in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$empresaLabora.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.17in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$estadoCivil.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.52in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$telefono.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.93in;left:6.94in;width:1.28in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Puesto en empresa</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.57in;left:4.73in;width:1.05in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Sueldo mensual</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.57in;left:0.38in;width:1.57in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Dirección de la empresa</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.57in;left:6.94in;width:1.42in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Monto otros ingresos</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.20in;left:0.38in;width:1.00in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Otros ingresos</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.20in;left:2.51in;width:1.99in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#152746">Ha obtenido créditos con el FHA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.20in;left:4.73in;width:1.89in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Número de caso o referencia</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.93in;left:2.51in;width:1.48in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Empresa donde labora</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:0.44in;left:4.38in;width:2.79in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">NAOS-'.$codigo.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.30in;left:2.51in;width:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Email</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.93in;left:0.38in;width:0.76in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Estado civil</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.18in;left:0.38in;width:1.23in;line-height:0.18in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#de5b68">Oferta de reserva</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.30in;left:4.73in;width:0.86in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Nacionalidad</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.30in;left:0.38in;width:0.62in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Teléfono</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.63in;left:2.51in;width:1.27in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Dirección domicilio</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.63in;left:0.38in;width:0.96in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Fecha emisión</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.00in;left:6.94in;width:0.26in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">DPI</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.00in;left:4.73in;width:0.26in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">NIT</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.00in;left:0.38in;width:1.22in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Nombre completo</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.50in;left:0.38in;width:8.53in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Recibe un cordial saludo de parte del equipo de Avalia Desarrollos, desarrolladores de NAOS Apartamentos. A continuación le detallamos el apartamento de la presente oferta.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.97in;left:0.38in;width:8.53in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Apartamento '.$apartamento.' de '.$noHabitacion.' habitaciones, sala, comedor, cocina, acceso a balcón francés, área de lavandería, dormitorio principal, '.$NoHabitacionSec.' habitacion(es) adicional(es). 1 baño completo y parqueo de motocicleta. Se anexa la cotización a la presente oferta de reserva, la cual es de pleno conocimiento del cliente.</span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.26in;left:8.07in;width:0.85in;line-height:0.18in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#de5b68">'.$fechaActual.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.30in;left:6.94in;width:1.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Fecha nacimiento</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
                        
          ';  
          if($conCodeudor==1){
              $texto_r.='<div style="text-align: justify;position:absolute;top:9.13in;left:6.94in;width:1.28in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Puesto en empresa</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:9.77in;left:4.73in;width:1.05in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Sueldo mensual</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:9.77in;left:0.38in;width:1.57in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Dirección de la empresa</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:9.77in;left:6.94in;width:1.42in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Monto otros ingresos</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:10.40in;left:0.38in;width:1.00in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Otros ingresos</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:10.40in;left:2.51in;width:1.99in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#152746">Ha obtenido créditos con el FHA</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:10.40in;left:4.73in;width:1.89in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Número de caso o referencia</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:9.13in;left:2.51in;width:1.48in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Empresa donde labora</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>          
              <div style="text-align: justify;position:absolute;top:8.50in;left:2.51in;width:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Email</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:9.13in;left:0.38in;width:0.76in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Estado civil</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:8.50in;left:6.94in;width:1.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Fecha nacimiento</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:8.50in;left:4.73in;width:0.86in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Nacionalidad</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:8.50in;left:0.38in;width:0.62in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Teléfono</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:7.83in;left:2.51in;width:1.27in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Dirección domicilio</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:7.83in;left:0.38in;width:0.96in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Fecha emisión</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:7.20in;left:6.94in;width:0.26in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">DPI</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:7.20in;left:4.73in;width:0.26in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">NIT</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:7.20in;left:0.38in;width:1.22in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Nombre completo</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
          
              
              <div style="text-align: justify;position:absolute;top:9.98in;left:4.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$sueldoC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:8.72in;left:4.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$nacionalidadC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:8.07in;left:2.51in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$direccionC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:9.98in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$direccionLaboraC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:8.07in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$fechaEmisionC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:8.72in;left:2.51in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$correoC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:7.42in;left:6.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$dpiC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:7.42in;left:4.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$nitC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:9.37in;left:6.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$puestoC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:7.42in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$nombreCompletoC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:9.98in;left:6.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$montoIngresosC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:8.72in;left:6.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$fechaNacimientoC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:10.64in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$ingresosC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:9.37in;left:2.51in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$empresaLaboraC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:9.37in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$estadoCivilC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
              <div style="text-align: justify;position:absolute;top:8.72in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$telefonoC.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
          
              <img style="position:absolute;top:7.63in;left:0.38in;width:4.07in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_5.png').'" />
                      
                      <img style="position:absolute;top:7.63in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_7.png').'" />
                      
                      <img style="position:absolute;top:7.63in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_9.png').'" />
                      
                      <img style="position:absolute;top:8.27in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_11.png').'" />
                      
                      <img style="position:absolute;top:8.27in;left:2.50in;width:6.37in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_13.png').'" />
                      
                      <img style="position:absolute;top:8.93in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_15.png').'" />
                      
                      <img style="position:absolute;top:8.93in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_17.png').'" />
                      
                      <img style="position:absolute;top:8.93in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_19.png').'" />
                      
                      <img style="position:absolute;top:9.57in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_21.png').'" />
                      
                      <img style="position:absolute;top:8.93in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_23.png').'" />
                      
                      <img style="position:absolute;top:9.57in;left:2.50in;width:4.07in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_25.png').'" />
                      
                      <img style="position:absolute;top:9.57in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_27.png').'" />
                      
                      <img style="position:absolute;top:10.20in;left:0.38in;width:4.07in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_29.png').'" />
                      
                      <img style="position:absolute;top:10.20in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_31.png').'" />
                      
                      <img style="position:absolute;top:10.20in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_33.png').'" />
                      
                      <img style="position:absolute;top:10.83in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_35.png').'" />
                      <img style="position:absolute;top:10.60in;left:2.51in;width:0.90in;height:0.24in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_'.$fondoSiC.'.png').'" />
                      <img style="position:absolute;top:10.60in;left:2.52in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_2.png').'" />
                      <img style="position:absolute;top:10.82in;left:2.52in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_3.png').'" />
                      <img style="position:absolute;top:10.61in;left:2.51in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_4.png').'" />
                      <img style="position:absolute;top:10.61in;left:3.39in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_5.png').'" />
                      <div style="text-align: justify;position:absolute;top:10.60in;left:2.90in;width:0.16in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:'.$colorLetrasSiC.'">Si</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
                      <img style="position:absolute;top:10.60in;left:3.55in;width:0.90in;height:0.24in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_'.$fondoNoC.'.png').'" />
                      <img style="position:absolute;top:10.60in;left:3.56in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_6.png').'" />
                      <img style="position:absolute;top:10.82in;left:3.56in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_7.png').'" />
                      <img style="position:absolute;top:10.61in;left:3.55in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_8.png').'" />
                      <img style="position:absolute;top:10.61in;left:4.44in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_9.png').'" />
                      <div style="text-align: justify;position:absolute;top:10.60in;left:3.90in;width:0.24in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:'.$colorLetrasNoC.'">No</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#ffffff"> </span><br/></SPAN></div>
                      
                      <img style="position:absolute;top:10.83in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_39.png').'" />';
          }
                      
                      
                      $texto_r.='<img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/avalia-logo.png').'" />
            <div style="text-align: justify;position:absolute;top:11.61in;left:7.96in;width:0.96in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68">Página '.$countP.' de 5</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>';$texto_r .='<div style="page-break-after: always;">
            </div>';
            $countP++;
            $texto_r .='<div style="page-break-after: always;">
            </div>';
            $texto_r .='<img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_10.png').'" />
            <img style="position:absolute;top:0.34in;left:0.38in;width:1.42in;height:0.58in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/logo naos.png').'" />
            <img style="position:absolute;top:0.34in;left:6.95in;width:2.32in;height:0.67in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_40.png').'" />
            <div style="text-align: justify;position:absolute;top:0.50in;left:7.14in;width:1.30in;line-height:0.18in;"><span style="font-style:normal;font-weight:bold;font-size:14pt;font-family:Effra;color:#de5b68">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:14pt;font-family:Effra;color:#de5b68"> </span><br/><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.28in;left:0.38in;width:1.32in;line-height:0.18in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#de5b68">Carencia de bienes</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.57in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_41.png').'" />
            <img style="position:absolute;top:1.93in;left:0.38in;width:0.90in;height:0.24in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_42.png').'" />
            <img style="position:absolute;top:1.93in;left:0.39in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_11.png').'" />
            <img style="position:absolute;top:2.16in;left:0.39in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_12.png').'" />
            <img style="position:absolute;top:1.94in;left:0.38in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_13.png').'" />
            <img style="position:absolute;top:1.94in;left:1.27in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_14.png').'" />
            <div style="text-align: justify;position:absolute;top:1.91in;left:0.77in;width:0.16in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#ffffff">Si</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#ffffff"> </span><br/></SPAN></div>
            <img style="position:absolute;top:1.93in;left:1.60in;width:0.90in;height:0.24in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_43.png').'" />
            <img style="position:absolute;top:1.93in;left:1.60in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_15.png').'" />
            <img style="position:absolute;top:2.16in;left:1.60in;width:0.89in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_16.png').'" />
            <img style="position:absolute;top:1.94in;left:1.60in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_17.png').'" />
            <img style="position:absolute;top:1.94in;left:2.48in;width:0.02in;height:0.23in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_18.png').'" />
            <div style="text-align: justify;position:absolute;top:1.91in;left:1.94in;width:0.24in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">No</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.36in;left:0.38in;width:1.34in;line-height:0.18in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68">Datos del inmueble</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.65in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_44.png').'" />
            
            <img style="position:absolute;top:3.21in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_46.png').'" />
            
            <img style="position:absolute;top:3.21in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_48.png').'" />
            
            <img style="position:absolute;top:3.21in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_50.png').'" />
            
            <img style="position:absolute;top:3.21in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_52.png').'" />
            
            <img style="position:absolute;top:3.84in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_54.png').'" />
            
            <img style="position:absolute;top:3.84in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_56.png').'" />
            <div style="text-align: justify;position:absolute;top:4.04in;left:0.38in;width:1.10in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68">Forma de pago</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <img style="position:absolute;top:4.33in;left:0.38in;width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_57.png').'" />

            <img style="position:absolute;top:4.92in;left:0.38in;width:4.07in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_59.png').'" />
            
            <img style="position:absolute;top:4.92in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_61.png').'" />
            
            <img style="position:absolute;top:4.92in;left:6.94in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_63.png').'" />
            
            <img style="position:absolute;top:5.55in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_65.png').'" />
            
            <img style="position:absolute;top:5.55in;left:2.50in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_67.png').'" />
            
            <img style="position:absolute;top:5.55in;left:4.72in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_69.png').'" />
            
            <img style="position:absolute;top:6.19in;left:0.38in;width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_71.png').'" />
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_72.png').'" />
            <div style="text-align: justify;position:absolute;top:4.72in;left:0.38in;width:2.58in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">Enganche Fraccionado/Crédito Bancario</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.72in;left:4.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$precioTotal.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.36in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$reserva.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.65in;left:2.51in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$bancoCheque.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.65in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$noCheque.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.36in;left:2.51in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$engancheMonto.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.01in;left:6.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$bancoDeposito.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.72in;left:6.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$engancheMontoCompleto.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.01in;left:4.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$noDeposito.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.36in;left:4.73in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$saldoContraEntrega.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.01in;left:2.51in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$torre.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.01in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.99in;left:0.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746">'.$fechaEntrega.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.75in;left:0.38in;width:4.65in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Fecha estimada de entrega (sujeto a declaración de elegibilidad del FHA)</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.12in;left:2.51in;width:1.45in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Enganche a fraccionar</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.12in;left:0.38in;width:1.20in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Monto de Reserva</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.12in;left:4.73in;width:1.14in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Monto a financiar</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.26in;left:8.07in;width:0.85in;line-height:0.18in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#de5b68">'.$fechaActual.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.49in;left:6.94in;width:1.31in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Monto de Enganche</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.49in;left:4.73in;width:0.81in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Precio Total</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.67in;left:0.38in;width:4.49in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746">Por este medio confirmo que no tengo bienes inmuebles a mi nombre</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.49in;left:0.38in;width:0.94in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Modo de pago</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.41in;left:2.51in;width:0.43in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Banco</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.41in;left:0.38in;width:1.63in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">No. Cheque/Documento</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.78in;left:6.94in;width:0.43in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Banco</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.78in;left:4.73in;width:1.85in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">No. Depósito/Transferencia</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.78in;left:2.51in;width:0.51in;line-height:0.17in;"><span style="font-style:normal;font-weight:nboldormal;font-size:10pt;font-family:Effra;color:#152746">Módulo</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.78in;left:0.38in;width:0.90in;line-height:0.17in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#152746">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:11.61in;left:7.96in;width:0.96in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#de5b68">Página 2 de 5</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/avalia-logo.png').'" /><div style="page-break-after: always;">
            </div>';
            $texto_r .='<div style="page-break-after: always;">
            </div>';
            $countP++;
            $texto_r .='<img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_19.png').'" />
            <img style="position:absolute;top:0.34in;left:0.38in;width:1.42in;height:0.58in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/logo naos.png').'" />
            <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_73.png').'" />
            <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#de5b68">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:0.94in;left:7.14in;width:0.58in;line-height:0.34in;"><span style="font-style:normal;font-weight:bold;font-size:20pt;font-family:Effra;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.94in;left:0.44in;width:1.46in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#de5b68">Monto de Enganche</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.94in;left:2.45in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#de5b68">'.$engancheMontoCompleto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <img style="position:absolute;top:2.14in;left:0.38in;width:8.47in;height:0.02in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_74.png').'" />
            <img style="position:absolute;top:2.15in;left:0.38in;width:8.48in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_75.png').'" />
            <div style="text-align: justify;position:absolute;top:2.23in;left:2.57in;width:0.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Cantidad</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.23in;left:4.61in;width:0.27in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Día</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.23in;left:6.41in;width:0.33in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Mes</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.23in;left:8.24in;width:0.32in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Año</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            
            <div style="position:absolute;top:2.42in;left:0.86in;width:0.61in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">Reserva</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="position:absolute;top:2.42in;left:2.57in;width:0.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$cantReserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="position:absolute;top:2.42in;left:4.63in;width:0.21in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$diaReserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="position:absolute;top:2.42in;left:6.19in;width:0.76in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$mesReserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="position:absolute;top:2.42in;left:8.21in;width:0.39in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$anioReserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            
            ';$count=1;
            $top=0;
            $topl=0;
            $strQueryDetalle = "SELECT * 
            FROM  prograEnganche pe
            INNER JOIN  prograEngancheDetalle ped on pe.idEnganche = ped.idEnganche
            where pe.idEnganche={$idEnganche}
            ORDER BY ped.noPago";
        
            //echo $strQuery;
            $qTmpD = $conn ->db_query($strQueryDetalle);
            while ($rTmpD = $conn->db_fetch_object($qTmpD)){
                $rTmpD;
                $topDesc=2.61 + $top;
                $topline=2.76 + $topl; 
                $texto_r .='
                <div style="position:absolute;top:'.$topDesc.'in;left:1.11in;width:0.12in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746">'.$count.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="position:absolute;top:'.$topDesc.'in;left:2.57in;width:0.68in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">Q.'.number_format($rTmpD->montoReal,2,".",",").'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="position:absolute;top:'.$topDesc.'in;left:4.63in;width:0.21in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.date('d',strtotime($rTmpD->fechaPago)).'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="position:absolute;top:'.$topDesc.'in;left:6.19in;width:0.76in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$meses[date('n',strtotime($rTmpD->fechaPago))].'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="position:absolute;top:'.$topDesc.'in;left:8.21in;width:0.39in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.date('Y',strtotime($rTmpD->fechaPago)).'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>';
                
                $count++;
                $top=$top+0.20;
                $topl=$topl+0.29;
            }
            
            $texto_r .='
            
            <img style="position:absolute;top:8.96in;left:0.38in;width:8.47in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_96.png').'" />

            <img style="position:absolute;top:11.00in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_98.png').'" />
     
            <img style="position:absolute;top:11.00in;left:3.33in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_100.png').'" />
           
            <img style="position:absolute;top:11.00in;left:6.28in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_102.png').'" />
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_103.png').'" />
            <div style="text-align: justify;position:absolute;top:10.79in;left:0.38in;width:0.93in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$engancheMonto.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.79in;left:3.33in;width:0.84in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$reserva.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.79in;left:6.28in;width:1.03in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746">'.$saldoContraEntrega.'</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.16in;left:0.38in;width:2.74in;line-height:0.32in;"><span style="font-style:normal;font-weight:normal;font-size:18pt;font-family:Effra;color:#de5b68">Desglose de inversión</span><span style="font-style:normal;font-weight:normal;font-size:18pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.37in;left:0.38in;width:2.10in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Total de cuotas de enganche</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.37in;left:3.33in;width:0.61in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Reserva</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.37in;left:6.28in;width:1.27in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Monto a financiar</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:11.61in;left:7.96in;width:0.96in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#de5b68">Página 3 de 5</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/avalia-logo.png').'" />
            ';$texto_r .='<div style="page-break-after: always;">
            </div>';
            $countP++;
            $texto_r .='
            <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_20.png').'" />
            <img style="position:absolute;top:0.34in;left:0.38in;width:1.42in;height:0.58in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/logo naos.png').'" />
            <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_104.png').'" />
            <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#de5b68">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:0.94in;left:7.14in;width:0.58in;line-height:0.34in;"><span style="font-style:normal;font-weight:bold;font-size:20pt;font-family:Effra;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:1.64in;left:0.38in;width:3.19in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#de5b68">Condiciones que aplican a la presente oferta</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            
            <img style="position:absolute;top:8.65in;left:0.46in;width:4.07in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_106.png').'" />
            <img style="position:absolute;top:8.65in;left:4.78in;width:4.07in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_108.png').'" />
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_109.png').'" />
            <div style="text-align: justify;position:absolute;top:8.40in;left:0.47in;width:0.75in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Concepto</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.40in;left:4.78in;width:1.32in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Monto penalizado</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.69in;left:0.47in;width:4.02in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Por desistimiento de la compra después de 10 días de haber firmado la misma.</span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.69in;left:0.47in;width:6.47in;line-height:0.16in;"><div style="text-align: justify;position:relative; left:4.30in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">El pago de la reserva realizado.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></DIV></div>
            <div style="text-align: justify;position:absolute;top:9.18in;left:0.47in;width:4.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Por dejar de cancelar consecutivamente 3 abonos de</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">enganche.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.18in;left:4.75in;width:4.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">100% sobre en el enganche pactado o el enganche pagado hasta dicho momento y la liberación.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.67in;left:0.47in;width:4.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Por crédito denegado en FHA y/o insitución financiera previo a la entrega del inmueble. </span></SPAN><br/></div>
            <div style="text-align: justify;position:absolute;top:9.67in;left:4.75in;width:4.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">7% sobre el valor total de venta o el enganche pactado, lo que sea mayor. En caso el cliente hubiera pagado por concepto de enganche un monto sobre el excedente del 7% dicho excedente le sera devuelto al cliente</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.65in;left:0.47in;width:3.95in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Por desistimiento de la compra, previa aprobación</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">del crédito por parte del FHA y/o Institución financiera.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.65in;left:4.75in;width:4.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">7% sobre el valor total de venta o el enganche pactado, lo que sea mayor. En caso el cliente hubiera pagado por concepto de enganche un monto sobre el excedente del 7% dicho excedente le sera devuelto al cliente</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
 
            <div style="text-align: justify;position:absolute;top:1.64in;left:8.07in;width:0.85in;line-height:0.18in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#de5b68">'.$fechaActual.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div> 
           
            <div style="text-align: justify;position:absolute;top:2.01in;left:0.45in;width:8.38in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746">SPV NAOS, S.A. acepta la presente oferta para una futura compra - venta de bienes inmuebles, sujeto a aprobación de crédito bancario y sujeto expresamente a las condiciones siguientes:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.51in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> El (los) cliente (es) de manera expresa y sin reserva alguna acepta (n) en su totalidad la presente oferta, así como las condiciones a que la misma se sujeta y que se detallan en el presente documento.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.55in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">a.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.02in;left:0.68in;width:8.17in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> El (los) cliente(s) se comprometen con la firma del presente documento a en un plazo de 6 meses previo de la entrega de las unidades habitacionales a obtener y entregar toda la documentación necesaria para la gestión y eventual aprobación del crédito para la compra y posterior emisión de cédula hipotecaria, así como cualquier otra documentación necesaria para la actualización y la formalización de este negocio.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.06in;left:0.45in;width:0.05in;line-height:0.12in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">b.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.65in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> De igual manera, pasado 1 mes de aprobado el crédito por el FHA y la institución bancaria, sin haberse escriturado la compraventa, el comprador se obliga a completar o actualizar los documentos que se le requieren y ejecutar la firma del crédito, caso contrario, se entenderá como desistimiento de la compra y se penalizará como corresponde.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.69in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">c.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.32in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> Todo pago realizado con cheque, se tendrá por efectivo al cobrarse el mismo, por lo que se cobraran Q. 100.00 en concepto de gastos administrativos en caso el cheque resulte rechazado.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.36in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">d.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.80in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> Las cuotas mensuales que finalmente deberán pagarse al banco o cualquier otra institución financiera que otorgara el crédito para la adquisición el inmueble, serán determinadas por dicha institución al momento del desembolso del crédito y están sujetas a la políticas de crédito de la misma, por lo que la empresa no se hace responsable por aprobaciones, rechazos o cualquier variación a lo pactado.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.84in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">e.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.50in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> SPV NAOS, S.A. se reserva a rechazar cualquier solicitud, tramite o expediente de acuerdo a loas políticas internas, de cumplimiento, regulatorias y/o gubernamentales de calificación de clientes.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.54in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">f.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.00in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> Con la firma del presente acuerdo de reserva, el cliente voluntariamente y de conformidad con los términos del Artículo 64 de la ley de Acceso a la Información Pública, autoriza expresamente para que tanto SPV NAOS, S.A. como las entidades financieras con las que La Empresa realiza gestiones para la obtención de financiamientos para los compradores que puedan adquirir o consultar información relacionada con los presentes compradores, con el fin de tramitar, analizar y resolver la solicitud para obtener; precalificación de crédito y el  financiamiento relacionado con la compra de vivienda y/o liberación de gravámenes hipotecarios, liberándo desde ya a SPV NAOS, S.A. o cualquiera de las entidades financieras con las que se haga una gestión de crédito de las responsabilidades descritas en dicho artículo.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.04in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">g.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.40in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#152746">EL INCUMPLIMIENTO DE ESTE ACUERDO EN ALGUNA DE SUS PARTES POR PARTE DEL CLIENTE, DA DERECHO A SPV NAOS, S.A A DAR POR CONCLUIDA LA NEGOCIACIÓN, DISPONER LIBREMENTE DE LA UNIDAD RESERVADA Y COMERCIALIZARLA NUEVAMENTE Y A PENALIZAR DESCONTANDO DEL ENGANCHE RECIBIDO, EN CONCEPTO DE INDEMNIZACIÓN Y PERJUICIOS.<span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.44in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">h.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.10in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> Las penalizaciones a las que se esta sujeto por el incumplimiento del presente acuerdo son las siguientes:</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:8.14in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">i.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:11.61in;left:7.96in;width:0.96in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#de5b68">Página 4 de 5</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/avalia-logo.png').'" />
             
            ';$texto_r .='<div style="page-break-after: always;">
            </div>';
            $countP++;
            $texto_r .='
            <img style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/vi_21.png').'" />
            <img style="position:absolute;top:0.34in;left:0.38in;width:1.42in;height:0.58in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/logo naos.png').'" />
            <img style="position:absolute;top:0.60in;left:6.95in;width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_110.png').'" />
            <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in;width:1.40in;line-height:0.27in;"><span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:Effra;color:#de5b68">Apartamento</span><span style="font-style:normal;font-weight:normal;font-size:15pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:0.94in;left:7.14in;width:0.58in;line-height:0.34in;"><span style="font-style:normal;font-weight:bold;font-size:20pt;font-family:Effra;color:#152746">'.$apartamento.'</span><span style="font-style:normal;font-weight:normal;font-size:20pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.25in;left:0.45in;width:1.16in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#de5b68">Observaciones:</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:7.44in;left:0.45in;width:8.40in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746">'.$observaciones.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>

            <img style="position:absolute;top:9.87in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_112.png').'" />
            
            <img style="position:absolute;top:9.87in;left:3.33in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_114.png').'" />
            
            <img style="position:absolute;top:9.87in;left:6.28in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_116.png').'" />
            
            <img style="position:absolute;top:10.57in;left:0.38in;width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_118.png').'" />
            <div style="text-align: justify;position:absolute;top:11.03in;left:0.47in;width:4.70in;line-height:0.16in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746">Documento en dos originales a) Cliente b) Desarrolladora, todas con igual valor.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.50in;left:0.00in;width:9.27in;height:0.50in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/ci_119.png').'" />
            <div style="text-align: justify;position:absolute;top:1.64in;left:8.07in;width:0.85in;line-height:0.18in;"><span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#de5b68">'.$fechaActual.'</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.32in;left:0.45in;width:2.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Firmante El Cliente o El Comprador</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.32in;left:3.33in;width:2.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Firmante El Cliente o El Comprador</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:9.32in;left:6.28in;width:2.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Firmante El Cliente o El Comprador</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:10.03in;left:0.45in;width:2.54in;line-height:0.19in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Firmante El Cliente o El Comprador</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.01in;left:0.68in;width:8.17in;line-height:0.18in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> Los pagos de enganche deberán cancelarse puntualmente sin necesidad de cobro ni requerimiento alguno y que sean depositados en las cuentas bancarias detalladas a continuación.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:2.05in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">j.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: left;position:absolute;top:2.50in;left:1.70in;width:2.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Banco industrial</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"></span><br/></SPAN></div>
            <div style="text-align: left;position:absolute;top:2.50in;left:3.20in;width:2.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">306-000540-6</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: left;position:absolute;top:2.50in;left:4.70in;width:2.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">SPV NAOS, S.A.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"></span><br/></SPAN></div>
            <div style="text-align: left;position:absolute;top:2.50in;left:6.20in;width:2.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Monetaria Quetzales</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            
            <div style="text-align: left;position:absolute;top:2.70in;left:1.70in;width:3.50in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Banrural</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"></span><br/></SPAN></div>
            <div style="text-align: left;position:absolute;top:2.70in;left:3.20in;width:4.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">34-4596927-4</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: left;position:absolute;top:2.70in;left:4.70in;width:2.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">SPV NAOS, S.A.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"></span><br/></SPAN></div>
            <div style="text-align: left;position:absolute;top:2.70in;left:6.20in;width:2.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Monetaria Quetzales</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            
            <div style="text-align: left;position:absolute;top:2.90in;left:1.70in;width:3.25in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">BAM</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"></span><br/></SPAN></div>
            <div style="text-align: left;position:absolute;top:2.90in;left:3.20in;width:4.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">30-4026041-3</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: left;position:absolute;top:2.90in;left:4.70in;width:2.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">SPV NAOS, S.A.</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"></span><br/></SPAN></div>
            <div style="text-align: left;position:absolute;top:2.90in;left:6.20in;width:2.00in;line-height:0.16in;"><span style="font-style:normal;font-weight:bold;font-size:11pt;font-family:Effra;color:#152746">Monetaria Quetzales</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>

            
            <div style="text-align: justify;position:absolute;top:3.16in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> Al momento de hacer el deposito o la transferencia, el cliente deberá de enviar el comprobante del pago al asesor de ventas, haciendo referencia en la descripción del pago, la unidad a la que hay que acreditar el pago.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.20in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">k.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.70in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> Los precios y el enganche pactados en este acuerdo se garantizan únicamente al ser cumplido a cabalidad el plan de pagos y las obligaciones adquiridas en el presente documento. </span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:3.74in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">l.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.20in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> El (los) cliente (es) de manera expresa y sin reserva alguna acepta (n) en su totalidad la presente oferta, así como las condiciones a que el mismo se sujeta y que se detallan en el presente apartado. </span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.24in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">m.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.70in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> SPV NAOS, S.A. informará al cliente con anticipación cuando deberá presentarse a sus oficinas centrales a firmar la escritura de compraventa, para lo cual es necesario haber cancelado el enganche en su totalidad; caso contrario se procederá con el cobro por incumplimiento como se detalla en el presente documento.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:4.74in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">n.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.32in;left:0.68in;width:8.17in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> Asi mismo, el cliente manifiesta que  frente a cualquier incumplimiento que pueda existir por parte del Comprador y que el presente acuerdo sea sujeto a rescisión, la entidad SPV NAOS, S.A; queda liberada frente de toda obligación y responsabilidad de lo indicado en el Oferta de Reserva, pudiendo SPV NAOS, S.A., disponer libremente, sin responsabilidad y a su criterio de los bienes inmuebles indicados en dicho documento. </span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:5.36in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">o.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.00in;left:0.68in;width:8.17in;line-height:0.18in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#de5b68"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#152746">ES IMPORTANTE RECORDARLE A LOS CLIENTES QUE A PARTIR DE LA FIRMA DE LA PRESENTE OFERTA, LOS CLIENTES HAN ADQUIRIDO UNA OBLIGACIÓN DE PAGO, POR LO QUE ES IMPORTANTE QUE CUIDEN SU HISTORIAL DE CRÉDITO, PARA QUE AL MOMENTO DE REALIZAR LA CALIFICACIÓN PARA LA OBTENCIÓN DEL FINANCIAMIENTO CON EL BANCO, NO AFECTE SU CRÉDITO POR UNA MALA REFERENCIA CREDITICIA.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.04in;left:0.45in;width:0.05in;line-height:0.13in;"><span style="font-style:normal;font-weight:bold;font-size:9pt;font-family:Effra;color:#de5b68">p.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"></span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:6.80in;left:0.45in;width:8.40in;line-height:0.17in;"><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746">Tanto el cliente como SPV NAOS, S.A. da por entendido que el presente documento es suficiente para el reclamo de los puntos anteriores en caso de incumplimiento, dando lugar a la legalización del mismo en caso que aún no se firmara la promesa de compraventa.</span><span style="font-style:normal;font-weight:normal;font-size:9pt;font-family:Effra Light;color:#152746"> </span><br/></SPAN></div>
            <div style="text-align: justify;position:absolute;top:11.61in;left:7.96in;width:0.96in;line-height:0.19in;"><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68">Página 5 de 5</span><span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:Effra;color:#de5b68"> </span><br/></SPAN></div>
            <img style="position:absolute;top:11.65in;left:0.38in;width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFEngancheNaos/OutDocument/avalia-logo.png').'" />

            </body>
            </html>
            ';
    }
    $dompdf->load_html($texto_r);

    // (Optional) Setup the paper size and orientation 595.275
    $customPaper = array(0,0,666.141,864.56);
    $dompdf->setPaper($customPaper);

    // Render the HTML as PDF
    $dompdf->render();

    //Guardar PDF en servidor
    $_path = "../public/";
    $pdfD = $dompdf->output();
    file_put_contents($_path."enganche_".$idEnganche.".pdf", $pdfD);
    $codigo = "enganche_".$idEnganche.".pdf";
    $strQuery = "   SELECT *
                    FROM  archivo 
                    WHERE id_cliente = {$intIdOcaCliente}
                    AND estado = 1
                    AND nombre = 'enganche_{$idEnganche}.pdf'   ";
    $qTmp = $conn->db_query($strQuery);
    if ($conn->db_num_rows($qTmp) <= 0) {
        $strQuery = "   INSERT INTO  archivo (id_cliente, id_tipo_documento, codigo, tipo, nombre)
                        VALUES ({$intIdOcaCliente}, 6, '{$codigo}', 'pdf', '{$codigo}')";
        $conn->db_query($strQuery);
    }
    $filename = $_path."enganche_".$idEnganche.".pdf"; // el nombre con el que se descargará, puede ser diferente al original 
    $nombre = "enganche_".$idEnganche.".pdf";
    header("Content-type: application/octet-stream"); 
    header("Content-Type: application/force-download"); 
    header("Content-Disposition: attachment; filename=\"$nombre\"\n"); 
    readfile($filename);
    // Enviamos el fichero PDF al navegador.
    //$dompdf->stream($nombre);
    echo 'SE DESCARGO EXITOSAMENTE EL ENGANCHE DE '.$nombreCompleto;
    
}

function fcnBase64($imagen){
    $imagenBase64 = "data:image/png;base64,".base64_encode(file_get_contents($imagen));
    return $imagenBase64;

}
 
function monto_letras($numero)
{
    $num = str_replace(",", "", $numero);
    $num = number_format($num, 2, '.', '');
    $cents = substr($num, strlen($num) - 2, strlen($num) - 1);
    $num = (int) $num;
    $numf = milmillon($num);
    $centf = milmillon($cents);
    $centavos = $cents>0?' y '.$centf. ' CENTAVOS':'';
    return $numf . " QUETZALES " . $centavos;
}
function milmillon($nummierod)
{
    if ($nummierod >= 1000000000 && $nummierod < 2000000000) {
        $num_letrammd = "MIL " . (cienmillon($nummierod % 1000000000));
    }
    if ($nummierod >= 2000000000 && $nummierod < 10000000000) {
        $num_letrammd = unidad(Floor($nummierod / 1000000000)) . " MIL " . (cienmillon($nummierod % 1000000000));
    }
    if ($nummierod < 1000000000) {
        $num_letrammd = cienmillon($nummierod);
    }

    return $num_letrammd;
}
function cienmillon($numcmeros)
{
    if ($numcmeros == 100000000) {
        $num_letracms = "CIEN MILLONES";
    }

    if ($numcmeros >= 100000000 && $numcmeros < 1000000000) {
        $num_letracms = centena(Floor($numcmeros / 1000000)) . " MILLONES " . (millon($numcmeros % 1000000));
    }
    if ($numcmeros < 100000000) {
        $num_letracms = decmillon($numcmeros);
    }

    return $num_letracms;
}
function decmillon($numerodm)
{
    if ($numerodm == 10000000) {
        $num_letradmm = "DIEZ MILLONES";
    }

    if ($numerodm > 10000000 && $numerodm < 20000000) {
        $num_letradmm = decena(Floor($numerodm / 1000000)) . "MILLONES " . (cienmiles($numerodm % 1000000));
    }
    if ($numerodm >= 20000000 && $numerodm < 100000000) {
        $num_letradmm = decena(Floor($numerodm / 1000000)) . " MILLONES " . (millon($numerodm % 1000000));
    }
    if ($numerodm < 10000000) {
        $num_letradmm = millon($numerodm);
    }

    return $num_letradmm;
}
function millon($nummiero)
{
    if ($nummiero >= 1000000 && $nummiero < 2000000) {
        $num_letramm = "UN MILLON " . (cienmiles($nummiero % 1000000));
    }
    if ($nummiero >= 2000000 && $nummiero < 10000000) {
        $num_letramm = unidad(Floor($nummiero / 1000000)) . " MILLONES " . (cienmiles($nummiero % 1000000));
    }
    if ($nummiero < 1000000) {
        $num_letramm = cienmiles($nummiero);
    }

    return $num_letramm;
}
function cienmiles($numcmero)
{
    if ($numcmero == 100000) {
        $num_letracm = "CIEN MIL";
    }

    if ($numcmero >= 100000 && $numcmero < 1000000) {
        $num_letracm = centena(Floor($numcmero / 1000)) . " MIL " . (centena($numcmero % 1000));
    }
    if ($numcmero < 100000) {
        $num_letracm = decmiles($numcmero);
    }

    return $num_letracm;
}
function decmiles($numdmero)
{
    if ($numdmero == 10000) {
        $numde = "DIEZ MIL";
    }

    if ($numdmero > 10000 && $numdmero < 20000) {
        $numde = decena(Floor($numdmero / 1000)) . "MIL " . (centena($numdmero % 1000));
    }
    if ($numdmero >= 20000 && $numdmero < 100000) {
        $numde = decena(Floor($numdmero / 1000)) . " MIL " . (miles($numdmero % 1000));
    }
    if ($numdmero < 10000) {
        $numde = miles($numdmero);
    }

    return $numde;
}
function miles($nummero)
{
    if ($nummero >= 1000 && $nummero < 2000) {
        $numm = "MIL " . (centena($nummero % 1000));
    }
    if ($nummero >= 2000 && $nummero < 10000) {
        $numm = unidad(Floor($nummero / 1000)) . " MIL " . (centena($nummero % 1000));
    }
    if ($nummero < 1000) {
        $numm = centena($nummero);
    }

    return $numm;
}
function centena($numc)
{
    if ($numc >= 100) {
        if ($numc >= 900 && $numc <= 999) {
            $numce = "NOVECIENTOS ";
            if ($numc > 900) {
                $numce = $numce . (decena($numc - 900));
            }

        } else if ($numc >= 800 && $numc <= 899) {
            $numce = "OCHOCIENTOS ";
            if ($numc > 800) {
                $numce = $numce . (decena($numc - 800));
            }

        } else if ($numc >= 700 && $numc <= 799) {
            $numce = "SETECIENTOS ";
            if ($numc > 700) {
                $numce = $numce . (decena($numc - 700));
            }

        } else if ($numc >= 600 && $numc <= 699) {
            $numce = "SEISCIENTOS ";
            if ($numc > 600) {
                $numce = $numce . (decena($numc - 600));
            }

        } else if ($numc >= 500 && $numc <= 599) {
            $numce = "QUINIENTOS ";
            if ($numc > 500) {
                $numce = $numce . (decena($numc - 500));
            }

        } else if ($numc >= 400 && $numc <= 499) {
            $numce = "CUATROCIENTOS ";
            if ($numc > 400) {
                $numce = $numce . (decena($numc - 400));
            }

        } else if ($numc >= 300 && $numc <= 399) {
            $numce = "TRESCIENTOS ";
            if ($numc > 300) {
                $numce = $numce . (decena($numc - 300));
            }

        } else if ($numc >= 200 && $numc <= 299) {
            $numce = "DOSCIENTOS ";
            if ($numc > 200) {
                $numce = $numce . (decena($numc - 200));
            }

        } else if ($numc >= 100 && $numc <= 199) {
            if ($numc == 100) {
                $numce = "CIEN ";
            } else {
                $numce = "CIENTO " . (decena($numc - 100));
            }

        }
    } else {
        $numce = decena($numc);
    }

    return $numce;
}
function decena($numdero)
{

    if ($numdero >= 90 && $numdero <= 99) {
        $numd = "NOVENTA ";
        if ($numdero > 90) {
            $numd = $numd . "Y " . (unidad($numdero - 90));
        }

    } else if ($numdero >= 80 && $numdero <= 89) {
        $numd = "OCHENTA ";
        if ($numdero > 80) {
            $numd = $numd . "Y " . (unidad($numdero - 80));
        }

    } else if ($numdero >= 70 && $numdero <= 79) {
        $numd = "SETENTA ";
        if ($numdero > 70) {
            $numd = $numd . "Y " . (unidad($numdero - 70));
        }

    } else if ($numdero >= 60 && $numdero <= 69) {
        $numd = "SESENTA ";
        if ($numdero > 60) {
            $numd = $numd . "Y " . (unidad($numdero - 60));
        }

    } else if ($numdero >= 50 && $numdero <= 59) {
        $numd = "CINCUENTA ";
        if ($numdero > 50) {
            $numd = $numd . "Y " . (unidad($numdero - 50));
        }

    } else if ($numdero >= 40 && $numdero <= 49) {
        $numd = "CUARENTA ";
        if ($numdero > 40) {
            $numd = $numd . "Y " . (unidad($numdero - 40));
        }

    } else if ($numdero >= 30 && $numdero <= 39) {
        $numd = "TREINTA ";
        if ($numdero > 30) {
            $numd = $numd . "Y " . (unidad($numdero - 30));
        }

    } else if ($numdero >= 20 && $numdero <= 29) {
        if ($numdero == 20) {
            $numd = "VEINTE ";
        } else {
            $numd = "VEINTI" . (unidad($numdero - 20));
        }

    } else if ($numdero >= 10 && $numdero <= 19) {
        switch ($numdero) {
            case 10:
                {
                    $numd = "DIEZ ";
                    break;
                }
            case 11:
                {
                    $numd = "ONCE ";
                    break;
                }
            case 12:
                {
                    $numd = "DOCE ";
                    break;
                }
            case 13:
                {
                    $numd = "TRECE ";
                    break;
                }
            case 14:
                {
                    $numd = "CATORCE ";
                    break;
                }
            case 15:
                {
                    $numd = "QUINCE ";
                    break;
                }
            case 16:
                {
                    $numd = "DIECISEIS ";
                    break;
                }
            case 17:
                {
                    $numd = "DIECISIETE ";
                    break;
                }
            case 18:
                {
                    $numd = "DIECIOCHO ";
                    break;
                }
            case 19:
                {
                    $numd = "DIECINUEVE ";
                    break;
                }
        }
    } else {
        $numd = unidad($numdero);
    }

    return $numd;
}
function unidad($numuero)
{
    switch ($numuero) {
        case 9:
            {
                $numu = "NUEVE";
                break;
            }
        case 8:
            {
                $numu = "OCHO";
                break;
            }
        case 7:
            {
                $numu = "SIETE";
                break;
            }
        case 6:
            {
                $numu = "SEIS";
                break;
            }
        case 5:
            {
                $numu = "CINCO";
                break;
            }
        case 4:
            {
                $numu = "CUATRO";
                break;
            }
        case 3:
            {
                $numu = "TRES";
                break;
            }
        case 2:
            {
                $numu = "DOS";
                break;
            }
        case 1:
            {
                $numu = "UNO";
                break;
            }
        case 0:
            {
                $numu = "";
                break;
            }
    }
    return $numu;
}
function normalizarNombre($nombre){

    $aa=preg_replace('/[^a-zA-Z0-9_ -]/s','',$nombre);
    $bb=str_replace('/', '-', $aa);

    return $bb;

  }
?>

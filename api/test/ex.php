<?php
require('html2pdf.php');
include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";

$conn = new dbClassMysql();
$func = new Functions();

$idCotizacion=84;


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
    //if($rTmp->proyecto=='Marabi'){
        $montoCocina=0;
        if($rTmp->cocina=='cocinaTipoA'){
            $montoCocina=$rTmp->cocinaTipoA;
        }
        else if($rTmp->cocina=='cocinaTipoB'){
            $montoCocina=$rTmp->cocinaTipoB;
        }
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
                $planta = './SodaPDFCotMarabi/OutDocument/PLANTA-A-5-13.png';
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
        



        //$dompdf = new Dompdf();
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
        <img style="position:absolute;top:5.65in;left:5.10in;width:3in;height:3in" src="'.fcnBase64($planta).'" />
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
if(isset($texto_r))
{
	$pdf=new PDF_HTML();
	$pdf->SetFont('Arial','',12);
	$pdf->AddPage();
	$pdf->WriteHTML($texto_r);
	$pdf->Output();
	exit;
}
function fcnBase64($imagen){
    $imagenBase64 = "data:image/png;base64,".base64_encode(file_get_contents($imagen));
    return $imagenBase64;

}
?>
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>HTML2PDF</title>
<style type="text/css">
input, textarea {
	font-family: lucida console;
	font-size: 9pt;
	border: 1px solid #B0B0B0;
}
body {
	font-family: lucida console;
	font-size: 9pt;
	background-color: #F0F0F0;
}
</style>
</head>
<body>
<h1>HTML2PDF</h1>
<div style="border: 1px solid black">
Supported tags are the following:
<ul type="square">
<li>&lt;br&gt; and &lt;p&gt;</li>
<li>&lt;b&gt;, &lt;i&gt; and &lt;u&gt;</li>
<li>&lt;img&gt; (src and width (or height) are mandatory)</li>
<li>&lt;a&gt; (href is mandatory)</li>
<li>&lt;font&gt;: possible attributes are
<ul>
<li>color: hex color code</li>
<li>face: available fonts are: arial, times, courier, helvetica, symbol</li>
</ul>
</li>
</ul>
To display these tags without interpreting them, use &amp;lt; and &amp;gt;
</div><br>
<form method="post" action="<?php //echo $_SERVER['PHP_SELF']; ?>" target="_blank">
Content:<br>
<textarea name="text" cols="80" rows="15"></textarea><br><br>
<input type="submit" value="Generate PDF">
</form>
</body>
</html> -->

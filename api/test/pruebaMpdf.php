<?php 
    error_reporting(0);
    include_once "../class/dbClassMysql.php";
    include_once "../class/functions.php";
    require_once '../class/vendor/autoload.php';
    date_default_timezone_set('America/Guatemala');
    $conn = new dbClassMysql();
    $func = new Functions();
    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8',
     'format' => [235, 305],
     'fontDir' => array_merge($fontDirs, [__DIR__]),
     'fontdata' => $fontData + 
		[
			'effrarg' => [
				'R' => 'Effra_Std_Lt.ttf',
			],
			'effrargB' => [
				'R' => 'Effra_Std_Rg.ttf',
			],
		],
          
    ]);

    //$idCotizacion=6273;
    //$idCotizacion=297;

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
            if($rTmp->bancoFin == "CREDITO HIPOTECARIO NACIONAL" ){
                $tasaInteres=6.7;
            }else{
                $tasaInteres = $rTmp->tasaInteres;
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
            //$bodegas=1;
            $precioTotal='Q.'.number_format($precioTotal,2,".",",");
            //$precioTotal='Q.'.number_format(875750,2,".",",");
            $descuento=$rTmp->descuento_porcentual.' %';
            
            $engancheMonto='Q.'.number_format(round($rTmp->enganchePorcMonto - $rTmp->MontoReserva),2,".",",");
            $enganche=$rTmp->enganchePorc.' %';
            //$enganche='';
            $enganchePagos=$rTmp->pagosEnganche;
            $engancheMensual='Q.'.number_format(($rTmp->enganchePorcMonto - $rTmp->MontoReserva)/$rTmp->pagosEnganche,2,".",",");
            //$engancheMensual='Q.'.number_format(15000,2,".",",");
            $reserva='Q.'.number_format($rTmp->MontoReserva,2,".",",");
            $saldoContraEntrega='Q.'.number_format(round($precioNeto - $rTmp->enganchePorcMonto),2,".",",");
            //$saldoContraEntrega='Q.'.number_format(875750-350000,2,".",",");
            $saldoFinanciar='Q.'.number_format(round($precioNeto - $rTmp->enganchePorcMonto),2,".",",");
            //$saldoFinanciar='Q.'.number_format(525750,2,".",",");
            $precioNeto='Q.'.number_format(round($precioNeto),2,".",",");
            //$precioNeto='Q.'.number_format(875750,2,".",",");
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
        $css_r='<style type="text/css">
            
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        .s1 {
            color: #142746;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 21pt;
        }

        .s2 {
            color: #FCDFDB;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 26pt;
        }

        .s3 {
            color: #FCDFDB;
            font-family: "Gill Sans MT", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 27pt;
        }

        .s4 {
            color: #142746;
            font-family: "Gill Sans MT", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
        }

        .s5 {
            color: #ACACAC;
            font-family: "Gill Sans MT", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 14pt;
        }

        .s6 {
            color: #FCDFDB;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 15pt;
        }

        .s7 {
            color: #FCDFDB;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 20pt;
        }

        .s8 {
            color: #142746;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 11pt;
        }

        .s9 {
            color: #FF9166;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
        }

        .s10 {
            color: #142746;
            font-family: "Gill Sans MT", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 11pt;
        }

        .s11 {
            color: #142746;
            font-family: "Gill Sans MT", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 11pt;
        }

        .s12 {
            color: black;
            font-family: "Gill Sans MT", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 1pt;
        }

        .s13 {
            color: #FF9267;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 11pt;
        }

        p {
            color: #142746;
            font-family: "Gill Sans MT", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
            margin: 0pt;
        }

        a {
            color: #FCDFDB;
            font-family: "Gill Sans MT", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
        }

        .s14 {
            color: #FF9267;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
        }

        .s15 {
            color: #FCDFDB;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
        }

        li {
            display: block;
        }

        #l1 {
            padding-left: 0pt;
        }

        #l1>li>*:first-child:before {
            content: "• ";
            color: #142746;
            font-family: "Gill Sans MT", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }
    </style>';
        $texto_r='
        <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">

        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        </head>

        <body>
            <!- Página 1 ->
            <div style="position:absolute;top:0.00in;left:0.00in;width:6.77in;height:4.08in">
                <img style="position:absolute;top:0.00in;left:0.00in;width:6.77in;height:4.08in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_1.png').'" /> 
            </div>
            <div style="position:absolute;top:0.60in;left:0.38in;">
                <img style="width:2.50in;height:0.98in" src="'.fcnBase64('../img/logo Marabi.png').'" />
            </div>
            <div style="position:absolute;top:0.00in;left:6.77in;">
                <img style="width:2.50in;height:4.08in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_2.png').'" />
            </div>
            <div style="position:absolute;top:0.42in;left:5.10in;">
                <img style="width:3.33in;height:3.33in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/torreMarabi.png').'" />
            </div>
            <div style="position:absolute;top:3.00in;left:0.38in;">
                <img style="width:2.42in;height:0.59in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/mariscal.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:2.09in;left:0.38in;width:3.58in;line-height:0.36in;">
                <span class="s1">Vida moderna y accesible.</span>
            </div>
            <div style="position:absolute;top:4.33in;left:4.64in;">
                <img style="width:4.64in;height:1.23in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_3.png').'" />
            </div>
            <div style="position:absolute;top:4.33in;left:4.64in;">
                <p class="s2" style="padding-top: 19pt;padding-left: 7pt;text-indent: 0pt;line-height: 29pt;text-align: left;">'.$habitaciones.'</p>
                <p class="s3" style="padding-left: 8pt;text-indent: 0pt;line-height: 31pt;text-align: left;">'.$nivel.'</p>
            </div>
            <div style="position:absolute;top:5.65in;left:5.10in;">
                <img style="width:3in;height:2.5in" src="'.fcnBase64($planta).'" />
            </div>
            <div style="position:absolute;top:9in;left:6in">
                <img style=";width:1.7in;height:1.7in" src="'.fcnBase64($plantaTorre).'" />
            </div>

            <div style="text-align: justify;position:absolute;top:4.35in;left:0.45in;">
                <p class="s4" style="width:0.68in;line-height:0.24in;">Cocina
                </p>
            </div>
            <div style="position:absolute;top:4.45in;left:0.32in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>

            <div style="text-align: justify;position:absolute;top:4.87in;left:0.45in;">
                <p class="s4" style="width:0.41in;line-height:0.24in;">Sala
                </p>
            </div>
            <div style="position:absolute;top:4.97in;left:0.32in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:5.38in;left:0.45in;">
                <p class="s4" style="width:3.74in;line-height:0.24in;">Comedor
                </p>
            </div>
            <div style="position:absolute;top:5.48in;left:0.32in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:5.89in;left:0.45in;">
                <p class="s4" style="width:3.74in;line-height:0.24in;">Dormitorio principal con Walk-in Closet y baño completo
                </p>
            </div>
            <div style="position:absolute;top:5.99in;left:0.32in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
        ';
            if($rTmp->cuartos>1){
                if($rTmp->cuartos==2){
                    $descripcionHab='Dormitorio secundario con área de clóset';
                }else{
                    $descripcionHab='Dormitorios secundarios con área de clóset';
                }
                $texto_r.='
                <div style="text-align: justify;position:absolute;top:6.69in;left:0.45in;">
                    <p class="s4" style="width:3.74in;line-height:0.24in;">'.$descripcionHab.'
                    </p>
                </div>
                <div style="position:absolute;top:6.79in;left:0.32in;">
                    <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
                </div>
                <div style="text-align: justify;position:absolute;top:7.20in;left:0.45in;">
                    <p class="s4" style="width:3.74in;line-height:0.24in;">Baño secundario completo
                    </p>
                </div>
                <div style="position:absolute;top:7.30in;left:0.32in;">
                    <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
                </div>
                <div style="text-align: justify;position:absolute;top:7.72in;left:0.45in;">
                    <p class="s4" style="width:3.74in;line-height:0.24in;">Balcón
                    </p>
                </div>
                <div style="position:absolute;top:7.82in;left:0.32in;">
                    <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
                </div>
                <div style="text-align: justify;position:absolute;top:8.23in;left:0.45in;">
                    <p class="s4" style="width:3.74in;line-height:0.24in;">Área de lavandería
                    </p>
                </div>
                <div style="position:absolute;top:8.33in;left:0.32in;">
                    <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
                </div>
                <div style="text-align: ustify;position:absolute;top:8.74in;left:0.45in;">
                    <p class="s4" style="width:3.74in;line-height:0.24in;">Bodega
                    </p>
                </div>
                <div style="position:absolute;top:8.84in;left:0.32in;">
                    <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
                </div>
                <div style="text-align:justify;position:absolute;top:9.26in;left:0.45in;">
                    <p class="s4" style="width:3.74in;line-height:0.24in;">Parqueos en sótano tipo Tandem (Parqueos adicionales a la venta)
                    </p>
                </div>
                <div style="position:absolute;top:9.36in;left:0.32in;">
                    <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
                </div>
                ';

            }else{
                $texto_r.='
                <div style="text-align: justify;position:absolute;top:6.69in;left:0.45in;">
                    <p class="s4" style="width:3.74in;line-height:0.24in;">Balcón
                    </p>
                </div>
                <div style="position:absolute;top:6.79in;left:0.32in;">
                    <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
                </div>
                <div style="text-align: justify;position:absolute;top:7.20in;left:0.45in;">
                    <p class="s4" style="width:3.74in;line-height:0.24in;">Área de lavandería
                    </p>
                </div>
                <div style="position:absolute;top:7.30in;left:0.32in;">
                    <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
                </div>
                <div style="text-align: justify;position:absolute;top:7.72in;left:0.45in;">
                    <p class="s4" style="width:3.74in;line-height:0.24in;">Bodega
                    </p>
                </div>
                <div style="position:absolute;top:7.72in;left:0.32in;">
                    <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
                </div>
                <div style="text-align:justify;position:absolute;top:8.23in;left:0.45in;">
                    <p class="s4" style="width:3.74in;line-height:0.24in;">Parqueos en sótano (Parqueos adicionales a la venta)
                    </p>
                </div>
                <div style="position:absolute;top:8.33in;left:0.32in;">
                    <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
                </div>
                ';
            };
            $texto_r.='
            
            <div style="position:absolute;top:10.53in;left:0.38in;">
                <p class="s5" style="padding-left: 9pt;text-indent: 0pt;line-height: 158%;text-align: left;">*Imágenes con fines ilustrativos,</p>
                <p class="s5" style="padding-left: 9pt;text-indent: 0pt;line-height: 158%;text-align: left;">sujeto a cambios sin previo aviso.</p>
            </div>
            <div style="position:absolute;top:11.58in;left:0.00in;">
                <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
            </div>
            <div style="position:absolute;top:11.65in;left:0.38in;">
                <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
            </div>
            <div style="position:absolute;top:11.65in;left:0.38in;"></div>
        
            <div style="page-break-after: always;">
            </div>

            <!- Página 2 ->
            <div style="position:absolute;top:0.00in;left:0.00in;">
                <img style="width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_12.png').'" />
            </div>

            <div style="position:absolute;top:0.60in;left:0.38in;">
                <img style="width:2.50in;height:0.98in" src="'.fcnBase64('../img/logo Marabi.png').'" />
            </div>
            <div style="position:absolute;top:0.60in;left:6.95in;">
                <img style="width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_5.png').'" />
            </div>
            <div style="position:absolute;top:0.75in;left:7.14in;">
                <p class="s6" style="width:1.40in;line-height:0.27in;">Apartamento</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.03in;left:7.14in;line-height:0.37in;">
                <span class="s7" >'.$apartamento.'</span>
            </div>
            <div style="text-align: justify;position:absolute;top:1.83in;left:7.68in;line-height:0.23in;">
                <p class="s9">'.$fechaActual.'</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:0.38in;width:1.04in;line-height:0.19in;">
                <p class="s8">Cotizado para</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:3.33in;width:0.54in;line-height:0.19in;">
                <p class="s8">Correo</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:6.28in;width:0.68in;line-height:0.19in;">
                <p class="s8">Teléfono</p>
            </div>
            <div style="text-align: justify;position:absolute;top:2.20in;left:0.41in;line-height:0.19in;">
                <p class="s10">'.$nombreCompleto.'</p>
            </div>
            <div style="position:absolute;top:2.40in;left:0.38in;">
                <img style="width:2.58in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>

            <div style="text-align: justify;position:absolute;top:2.20in;left:3.36in;line-height:0.19in;">
                <p class="s10">'.$correo.'</p>
            </div>
            <div style="position:absolute;top:2.40in;left:3.33in;">
                <img style="width:2.58in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:2.20in;left:6.31in;width:1.04in;line-height:0.19in;">
                <p class="s8">'.$telefono.'</p>
            </div>
            <div style="position:absolute;top:2.40in;left:6.28in;">
                <img style="width:2.58in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:2.96in;left:0.38in;width:2.57in;line-height:0.19in;">
                <p class="s13">¡Tu nuevo hogar te está esperando!</p>
            </div>
            <div style="text-align: justify;position:absolute;top:3.37in;left:0.38in;width:8.53in;line-height:0.19in;">
                <p style="padding-left: 1pt;text-indent: 0pt;line-height: 156%;text-align: justify;">Recibe un cordial saludo de
                    parte del equipo de Avalia Desarrollos. A continuación te presentamos la cotización del apartamento '.$apartamento.' de '.$habitaciones.'
                    habitaciones, sala,comedor, cocina, balcón, área de lavandería, dormitorio principal con walk-in closet y baño
                    completo, dormitorios secundarios con area de closet, baño secundario completo y ambos parqueos en sótano.
                    Parqueos adicionales se venden por separado.
                </p>
            </div>
            <div style="text-align: justify;position:absolute;top:4.88in;left:0.38in;width:2.09in;line-height:0.19in;">
                <p class="s13">Información de apartamento</p>
            </div>
            <div style="position:absolute;top:5.17in;left:0.38in;">
                <img style="width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_12.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:5.34in;left:0.38in;width:0.98in;line-height:0.19in;">
                <p class="s8">Nivel</p>
            </div>
            <div style="text-align: justify;position:absolute;top:5.34in;left:2.51in;width:0.98in;line-height:0.19in;">
                <p class="s8">Apartamento</p>
            </div>
            <div style="text-align: justify;position:absolute;top:5.34in;left:4.72in;width:0.98in;line-height:0.19in;">
                <p class="s8">Tamaño</p>
            </div>
            <div style="text-align: justify;position:absolute;top:5.34in;left:6.94in;width:0.98in;line-height:0.19in;">
                <p class="s8">Habitaciones</p>
            </div>
            <div style="text-align: justify;position:absolute;top:5.74in;left:0.38in;line-height:0.19in;">
                <p class="s10">'.$nivel.'</p>
            </div>
            <div style="position:absolute;top:5.94in;left:0.38in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:5.74in;left:2.51in;width:0.98in;line-height:0.19in;">
                <p class="s10">'.$apartamento.'</p>
            </div>
            <div style="position:absolute;top:5.94in;left:2.51in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:5.74in;left:4.72in;line-height:0.19in;">
                <p class="s10">'.$tamanio.'</p>
            </div>
            <div style="position:absolute;top:5.94in;left:4.72in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:5.74in;left:6.94in;line-height:0.19in;">
                <p class="s10">'.$habitaciones.'</p>
            </div>
            <div style="position:absolute;top:5.94in;left:6.94in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:6.22in;left:0.38in;width:1.98in;line-height:0.19in;">
                <p class="s8">Parqueo tipo tandem</p>
            </div>
            <div style="text-align: justify;position:absolute;top:6.22in;left:2.51in;width:0.98in;line-height:0.19in;">
                <p class="s8">Área de Jardín</p>
            </div>
            <div style="text-align: justify;position:absolute;top:6.22in;left:4.72in;width:0.98in;line-height:0.19in;">
                <p class="s8">Bodegas</p>
            </div>
            <div style="text-align: justify;position:absolute;top:6.62in;left:0.38in;line-height:0.19in;">
                <p class="s8">'.$parqueos.'</p>
            </div>
            <div style="position:absolute;top:6.82in;left:0.38in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:6.62in;left:2.51in;line-height:0.19in;">
                <p class="s8">'.$areaJardin.'</p>
            </div>
            <div style="position:absolute;top:6.82in;left:2.51in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:6.62in;left:4.72in;line-height:0.19in;">
                <p class="s8">'.$bodegas.'</p>
            </div>
            <div style="position:absolute;top:6.82in;left:4.72in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:8.36in;left:0.38in;width:2.09in;line-height:0.19in;">
                <p class="s13">Información de precios</p>
            </div>
            <div style="position:absolute;top:8.65in;left:0.38in;">
                <img style="width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_27.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:8.87in;left:0.38in;width:0.98in;line-height:0.19in;">
                <p class="s8">Precio Total</p>
            </div>
            <div style="text-align: justify;position:absolute;top:8.87in;left:2.51in;width:0.98in;line-height:0.19in;">
                <p class="s8">Descuento</p>
            </div>
            <div style="text-align: justify;position:absolute;top:8.87in;left:4.72in;width:0.98in;line-height:0.19in;">
                <p class="s8">Precio Neto</p>
            </div>
            <div style="text-align: justify;position:absolute;top:8.87in;left:6.94in;width:0.98in;line-height:0.19in;">
                <p class="s8">Enganche</p>
            </div>
            <div style="text-align: justify;position:absolute;top:9.26in;left:0.38in;line-height:0.19in;">
                <p class="s10">'.$precioTotal.'</p>
            </div>
            <div style="position:absolute;top:9.47in;left:0.38in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:9.27in;left:2.51in;line-height:0.19in;">
                <p class="s10">'.$descuento.'</p>
            </div>
            <div style="position:absolute;top:9.47in;left:2.51in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:9.27in;left:4.72in;line-height:0.19in;">
                <p class="s10">'.$precioNeto.'</p>
            </div>
            <div style="position:absolute;top:9.47in;left:4.72in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:9.27in;left:6.94in;line-height:0.19in;">
                <p class="s10">'.$engancheMonto.'</p>
            </div>
            <div style="position:absolute;top:9.47in;left:6.94in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>

            <div style="text-align: justify;position:absolute;top:9.75in;left:0.38in;line-height:0.19in;">
                <p class="s8">% de enganche</p>
            </div>
            <div style="text-align: justify;position:absolute;top:9.75in;left:2.51in;line-height:0.19in;">
                <p class="s8">Pagos de enganche</p>
            </div>
            <div style="text-align: justify;position:absolute;top:9.75in;left:4.72in;line-height:0.19in;">
                <p class="s8">Enganche mensual</p>
            </div>
            <div style="text-align: justify;position:absolute;top:9.75in;left:6.94in;line-height:0.19in;">
                <p class="s8">Reserva</p>
            </div>
            <div style="text-align: justify;position:absolute;top:10.15in;left:0.38in;line-height:0.19in;">
                <p class="s10">'.$enganche.'</p>
            </div>
            <div style="position:absolute;top:10.35in;left:0.38in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:10.15in;left:2.51in;width:1.00in;line-height:0.19in;">
                <p class="s10">'.$enganchePagos.'</p>
            </div>
            <div style="position:absolute;top:10.35in;left:2.51in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:10.15in;left:4.72in;line-height:0.19in;">
                <p class="s10">'.$engancheMensual.'</p>
            </div>
            <div style="position:absolute;top:10.35in;left:4.72in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:10.15in;left:6.94in;line-height:0.19in;">
                <p class="s10">'.$reserva.'</p>
            </div>
            <div style="position:absolute;top:10.35in;left:6.94in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:10.64in;left:0.38in;line-height:0.19in;">
                <p class="s8">Saldo contra entrega</p>
            </div>
            <div style="text-align: justify;position:absolute;top:11.04in;left:0.38in;line-height:0.19in;">
                <p class="s10">'.$saldoContraEntrega.'</p>
            </div>
            <div style="position:absolute;top:11.24in;left:0.38in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="position:absolute;top:11.58in;left:0.00in;">
                <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
            </div>
            <div style="position:absolute;top:11.65in;left:0.38in;">
                <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
            </div>
            <div style="position:absolute;top:11.66in;left:6.53in;">
                <p style="text-indent: 0pt;text-align: right;">
                    <a href="">'.$correoVendedor.'</a>
                </p>
            </div>
            
            <div style="page-break-after: always;">
            </div>

            <!- Página 3 ->
            <div style="position:absolute;top:0.00in;left:0.00in;">
                <img style="width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_12.png').'" />
            </div>

            <div style="position:absolute;top:0.60in;left:0.38in;">
                <img style="width:2.50in;height:0.98in" src="'.fcnBase64('../img/logo Marabi.png').'" />
            </div>
            <div style="position:absolute;top:0.60in;left:6.95in;">
                <img style="width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_5.png').'" />
            </div>
            <div style="position:absolute;top:0.75in;left:7.14in;">
                <p class="s6" style="width:1.40in;line-height:0.27in;">Apartamento</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.03in;left:7.14in;line-height:0.37in;">
                <span class="s7" >'.$apartamento.'</span>
            </div>
            <div style="text-align: justify;position:absolute;top:1.83in;left:7.68in;line-height:0.23in;">
                <p class="s9">'.$fechaActual.'</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:0.38in;width:1.04in;line-height:0.19in;">
                <p class="s8">Cotizado para</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:3.33in;width:0.54in;line-height:0.19in;">
                <p class="s8">Correo</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:6.28in;width:0.68in;line-height:0.19in;">
                <p class="s8">Teléfono</p>
            </div>
            <div style="text-align: justify;position:absolute;top:2.20in;left:0.41in;line-height:0.19in;">
                <p class="s10">'.$nombreCompleto.'</p>
            </div>
            <div style="position:absolute;top:2.40in;left:0.38in;">
                <img style="width:2.58in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:2.20in;left:3.36in;line-height:0.19in;">
                <p class="s10">'.$correo.'</p>
            </div>
            <div style="position:absolute;top:2.40in;left:3.33in;">
                <img style="width:2.58in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:2.20in;left:6.31in;width:1.04in;line-height:0.19in;">
                <p class="s8">'.$telefono.'</p>
            </div>
            <div style="position:absolute;top:2.40in;left:6.28in;">
                <img style="width:2.58in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:3.26in;left:0.38in;width:2.09in;line-height:0.19in;">
                <p class="s13">Información de cuota</p>
            </div>
            <div style="position:absolute;top:3.55in;left:0.38in;">
                <img style="width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_27.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:3.84in;left:0.38in;line-height:0.19in;">
                <p class="s8">Plazo Financiamiento</p>
            </div>
            <div style="text-align: justify;position:absolute;top:3.84in;left:2.51in;line-height:0.19in;">
                <p class="s8">Cuota de crédito</p>
            </div>
            <div style="text-align: justify;position:absolute;top:3.84in;left:4.72in;width:1.50in;line-height:0.19in;">
                <p class="s8">IUSI</p>
            </div>
            <div style="text-align: justify;position:absolute;top:3.84in;left:6.94in;line-height:0.19in;">
                <p class="s8">Seguro</p>
            </div>
            <div style="text-align: justify;position:absolute;top:4.24in;left:0.38in;line-height:0.19in;">
                <p class="s10">'.$plazoFinanciamiento.'</p>
            </div>
            <div style="position:absolute;top:4.44in;left:0.38in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:4.24in;left:2.51in;line-height:0.19in;">
                <p class="s10">'.$cuotaCredito.'</p>
            </div>
            <div style="position:absolute;top:4.44in;left:2.51in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:4.24in;left:4.72in;line-height:0.19in;">
                <p class="s10">'.$iusi.'</p>
            </div>
            <div style="position:absolute;top:4.44in;left:4.72in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:4.24in;left:6.94in;line-height:0.19in;">
                <p class="s10">'.$seguro.'</p>
            </div>
            <div style="position:absolute;top:4.44in;left:6.94in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:4.72in;left:0.38in;line-height:0.19in;">
                <p class="s8">Cuota total</p>
            </div>
            <div style="text-align: justify;position:absolute;top:4.72in;left:2.51in;line-height:0.19in;">
                <p class="s8">Ingreso familiar requerido</p>
            </div>
            <div style="text-align: justify;position:absolute;top:5.12in;left:0.38in;line-height:0.19in;">
                <p class="s10">'.$cuotaTotal.'</p>
            </div>
            <div style="position:absolute;top:5.32in;left:0.38in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:5.12in;left:2.51in;line-height:0.19in;">
                <p class="s10">'.$ingresoFamiliar.'</p>
            </div>
            <div style="position:absolute;top:5.32in;left:2.51in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:6.13in;left:0.38in;width:2.09in;line-height:0.19in;">
                <p class="s13">Información de contacto</p>
            </div>  
            <div style="position:absolute;top:6.42in;left:0.38in;">
                <img style="width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_27.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:6.70in;left:0.38in;line-height:0.19in;">
                <p class="s8">Contacto</p>
            </div>
            <div style="text-align: justify;position:absolute;top:6.70in;left:2.51in;line-height:0.19in;">
                <p class="s8">Teléfono contacto</p>
            </div>
            <div style="text-align: justify;position:absolute;top:7.10in;left:0.38in;line-height:0.19in;">
                <p class="s10">'.$contacto.'</p>
            </div>
            <div style="position:absolute;top:7.30in;left:0.38in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:7.10in;left:2.51in;line-height:0.19in;">
                <p class="s10">'.$telefonoContacto.'</p>
            </div>
            <div style="position:absolute;top:7.30in;left:2.51in;">
                <img style="width:1.94in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:8.18in;left:0.45in;width:8.53in;line-height:0.19in;">
                <p>
                    Precio total incluye gastos de escrituración e impuestos de traspaso. Monto reserva: Q. 10,000.00 que serán acreditados al valor del enganche. La presente cotización tiene una vigencia de 10 días hábiles contados a partir de la fecha de emisión.
                    
                </p>
                <br>
                <p>
                    Este material es utilizado para fines informativos y de referencia. SPV Marabi, S.A. se reserva el derecho de hacer modificaciones a los modelos a su discreción para efectos del desarrollo del proyecto.
                </p>
                <br>
                <p>
                    Los precios están sujetos a cambios sin previo aviso. Este material es utilizado para fines informativos y de referencia.
                </p>
                    
            </div>

            <div style="position:absolute;top:11.58in;left:0.00in;">
                <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
            </div>
            <div style="position:absolute;top:11.65in;left:0.38in;">
                <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
            </div>
            <div style="position:absolute;top:11.66in;left:6.53in;">
                <p style="text-indent: 0pt;text-align: right;">
                    <a href="">'.$correoVendedor.'</a>
                </p>
            </div>
            
            <div style="page-break-after: always;">
            </div>

            <!- Página 4 ->
            <div style="position:absolute;top:0.00in;left:0.00in;">
                <img style="width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_12.png').'" />
            </div>
            <div style="position:absolute;top:0.60in;left:0.38in;">
                <img style="width:2.50in;height:0.98in" src="'.fcnBase64('../img/logo Marabi.png').'" />
            </div>
            <div style="position:absolute;top:0.60in;left:6.95in;">
                <img style="width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_5.png').'" />
            </div>
            <div style="position:absolute;top:0.75in;left:7.14in;">
                <p class="s6" style="width:1.40in;line-height:0.27in;">Apartamento</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.03in;left:7.14in;line-height:0.37in;">
                <span class="s7" >'.$apartamento.'</span>
            </div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:0.38in;width:1.04in;line-height:0.19in;">
                <p class="s8">Cotizado para</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:3.33in;width:0.54in;line-height:0.19in;">
                <p class="s8">Correo</p>
            </div>
            <div style="text-align: justify;position:absolute;top:1.90in;left:6.28in;width:0.68in;line-height:0.19in;">
                <p class="s8">Teléfono</p>
            </div>
            <div style="text-align: justify;position:absolute;top:2.20in;left:0.41in;line-height:0.19in;">
                <p class="s10">'.$nombreCompleto.'</p>
            </div>
            <div style="position:absolute;top:2.40in;left:0.38in;">
                <img style="width:2.58in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:2.20in;left:3.36in;line-height:0.19in;">
                <p class="s10">'.$correo.'</p>
            </div>
            <div style="position:absolute;top:2.40in;left:3.33in;">
                <img style="width:2.58in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:2.20in;left:6.31in;width:1.04in;line-height:0.19in;">
                <p class="s8">'.$telefono.'</p>
            </div>
            <div style="text-align: justify;position:absolute;top:3.20in;left:0.38in;line-height:0.19in;">
                <p class="s13">Descripción general de acabados</p>
            </div>  
            <div style="position:absolute;top:3.40in;left:0.38in;">
                <img style="width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_27.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:3.63in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;">Muros: Levantado de block en muros divisorios entre apartamentos y divisiones interiores de tablayeso, con acabado liso en color blanco mate.
                </p>
            </div>
            <div style="position:absolute;top:3.73in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:4.23in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;">Pisos y forros de baños: El piso porcelanato tipo gress imitación madera. El forro de paredes de muros de los baños de piso a cielo es tipo gress color ceniza.
                </p>
            </div>
            <div style="position:absolute;top:4.33in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:4.78in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;"> Cocinas: Muebles con interiores de melamina en color blanco liso; puertas de los muebles aéreos con exteriores en color NOGAL PARIS y muebles base con exteriores en color BLANCO, top de cuarzo incluye lavatrastos de una fosa sin escurridor lateral. Mezcladora de lavatrastos cuello alto manejo mojonando.
                </p>
            </div>
            <div style="position:absolute;top:4.88in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:5.58in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;"> Clósets: Interiores y puertas en melamina en color NOGAL PARIS incluye sercheros de tubo niquelado con apoyos laterales. Gaveta con rieles de cierre suave y uñero a 45° como jalador. Puertas corredizas con riel aéreo y carrilera inferior plástica.
                </p>
            </div>
            <div style="position:absolute;top:5.68in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:6.13in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;">  Muebles de baño: Mueble de melamina en color blanco, suspendido a la pared y gaveta con mecanismo de cierre suave.
                </p>
            </div>
            <div style="position:absolute;top:6.23in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:6.43in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;">  Puertas: Puertas enchapadas de madera de ingeniería con enchape de 6mm; chapas con acabado satinado y tope de puerta con acabado satinado, recibidor de caucho y fijado al piso con bisagras con acabado satinado.
                </p>
            </div>
            <div style="position:absolute;top:6.53in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:6.98in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;">  Baranda de balcones: Vidrio templado de 10 mm, fundido al bordillo y herrajes de acero inoxidable.
                </p>
            </div>
            <div style="position:absolute;top:7.08in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:7.28in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;">  Grifería: En lavamanos mezcladora de cuello alto y en ducha mezcladora de control monomando y barra para teleducha extraíble.
                </p>
            </div>
            <div style="position:absolute;top:7.38in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:7.78in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;">  Losa sanitaria: En lavamanos de losa corrida de sobreponer y en inodoros tipo ovalín, con sistema de descarga eficiente.
                </p>
            </div>
            <div style="position:absolute;top:7.88in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:8.08in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;">  Emplacado eléctrico: Tipo BTICINO línea matrix color blanco, con electricidad de 220 v para estufa eléctrica y calentador de paso.
                </p>
            </div>
            <div style="position:absolute;top:8.18in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="text-align: justify;position:absolute;top:8.58in;left:0.85in;">
                <p style="width:7.80in;line-height:0.24in;">  Contador de agua: Individual por apartamento.
                </p>
            </div>
            <div style="position:absolute;top:8.68in;left:0.45in;">
                <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_2.png').'" />
            </div>
            <div style="position:absolute;top:2.40in;left:6.28in;">
                <img style="width:2.58in;" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_7.png').'" />
            </div>
            <div style="position:absolute;top:11.58in;left:0.00in;">
                <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
            </div>
            <div style="position:absolute;top:11.65in;left:0.38in;">
                <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
            </div>
            <div style="position:absolute;top:11.66in;left:6.53in;">
                <p style="text-indent: 0pt;text-align: right;">
                    <a href="">'.$correoVendedor.'</a>
                </p>
            </div>
            <div style="text-align: justify;position:absolute;top:10.14in;left:0.45in;width:8.10in;line-height:0.19in;">
                <p
                    >Apartamento no incluye: Lámparas e iluminación especial, vidrio templado en baños; calentador, filtro de agua, electrodomésticos, instalaciones especiales.
                </p>
            </div>
        </body>

        </html>';
    }else if($rTmp->proyecto=='Naos'){
        $montoCocina=0;
        if($rTmp->cocina=='cocinaTipoA'){
            $montoCocina=$rTmp->cocinaTipoA;
        }
        else if($rTmp->cocina=='cocinaTipoB'){
            $montoCocina=$rTmp->cocinaTipoB;
        }
        if($rTmp->bancoFin == "CREDITO HIPOTECARIO NACIONAL 5.5" ){
            $tasaInteres=5.5;
        }else if ($rTmp->bancoFin =="CREDITO HIPOTECARIO NACIONAL"){
            $tasaInteres=6.7;
        } else{
            $tasaInteres = $rTmp->tasaInteres;
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

        $css_r='<style type="text/css">
            * {
                margin: 0;
                padding: 0;
                text-indent: 0;
            }
            p {
                margin: 0;
            }

            .ft00 {
                font-size: 13px;
                font-family: SUBAAC+Effra;
                color: #de5b68;
            }

            .ft01 {
                font-size: 22px;
                font-family: SUBAAC+Effra;
                color: #bedbe4;
            }

            .ft02 {
                font-size: 19px;
                font-family: SUBAAD+EffraLight;
                color: #acacac;
            }

            .ft03 {
                font-size: 19px;
                font-family: SUBAAD+EffraLight;
                color: #142746;
            }

            .ft04 {
                font-size: 23px;
                font-family: SUBAAC+Effra;
                color: #de5a67;
            }

            .ft05 {
                font-size: 31px;
                font-family: SUBAAC+Effra;
                color: #142746;
            }

            .ft200 {
                font-size: 20px;
                font-family: SUBAAC+Effra;
                color: #de5b68;
            }

            .ft201 {
                font-size: 14px;
                font-family: SUBAAC+Effra;
                color: #de5b68;
            }

            .ft202 {
                font-size: 13px;
                font-family: SUBAAD+EffraLight;
                color: #142746;
            }

            .ft203 {
                font-size: 14px;
                font-family: SUBAAC+Effra;
                color: #142746;
                font-weight: bold;
            }

            .ft204 {
                font-size: 16px;
                font-family: SUBAAC+Effra;
                color: #de5b68;
            }

            .ft205 {
                font-size: 28px;
                font-family: SUBAAC+Effra;
                color: #142746;
            }

            .ft206 {
                font-size: 14px;
                font-family: SUBAAD+EffraLight;
                color: #142746;
            }

            .ft207 {
                font-size: 16px;
                font-family: SUBAAC+Effra;
                color: #ff9166;
            }

            .ft208 {
                font-size: 16px;
                font-family: SUBAAD+EffraLight;
                color: #fcdfdb;
            }

            .ft209 {
                font-size: 13px;
                line-height: 23px;
                font-family: SUBAAD+EffraLight;
                color: #142746;
            }

            .ft2010 {
                font-size: 14px;
                line-height: 21px;
                font-family: SUBAAC+Effra;
                color: #142746;
            }

            .ft300 {
                font-size: 20px;
                font-family: SUBAAC+Effra;
                color: #de5b68;
            }
    
            .ft301 {
                font-size: 16px;
                font-family: SUBAAC+Effra;
                color: #de5b68;
            }
    
            .ft302 {
                font-size: 14px;
                font-family: SUBAAC+Effra;
                color: #142746;
            }
    
            .ft303 {
                font-size: 14px;
                font-family: SUBAAD+EffraLight;
                color: #142746;
            }
    
            .ft304 {
                font-size: 16px;
                font-family: SUBAAD+EffraLight;
                color: #de5b68;
            }
    
            .ft305 {
                font-size: 13px;
                font-family: SUBAAD+EffraLight;
                color: #142746;
            }
    
            .ft306 {
                font-size: 28px;
                font-family: SUBAAC+Effra;
                color: #142746;
            }
    
            .ft307 {
                font-size: 16px;
                font-family: SUBAAC+Effra;
                color: #ff9166;
            }
    
            .ft308 {
                font-size: 16px;
                font-family: SUBAAD+EffraLight;
                color: #fcdfdb;
            }
    
            .ft309 {
                font-size: 14px;
                line-height: 28px;
                font-family: SUBAAD+EffraLight;
                color: #142746;
            }
    
            .ft3010 {
                font-size: 14px;
                line-height: 27px;
                font-family: SUBAAD+EffraLight;
                color: #142746;
            }
    
            .ft3011 {
                font-size: 13px;
                line-height: 20px;
                font-family: SUBAAD+EffraLight;
                color: #142746;
            }
        </style>';

        $texto_r='
            <!DOCTYPE html
            PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">

                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
                </head>

                <body>
                    <div style="position:absolute;top:0.00in;left:0.00in;width:9.27in;height:5.76in">
                        <img style="width:9.27in;height:5.76in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_1.png').'" />
                    </div>
                    <div style="top:0.00in;left:0.00in;width:9.27in;height:12.00in">
                        <img style="width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_1.png').'" />
                    </div>
                    <div style="position:absolute;top:0.36in;left:0.38in;">
                        <img style="width:1.67in;height:0.74in" src="./SodaPDFCotNaos/OutDocument/naosLogo.jpg" />
                    </div>
                    <div style="position:absolute;top:0.32in;left:2.32in;">
                        <img style="width:6.67in;height:4.61in" src="./SodaPDFCotNaos/OutDocument/torreNaos.jpg" />
                    </div>
                    <div style="position:absolute;top:2.16in;left:0.55in;">
                        <img style="width:1.33in;height:1.33in" src="./SodaPDFCotNaos/OutDocument/ri_2.jpeg" />
                    </div>
                    <div style="position:absolute;top:3.96in;left:0.55in;">
                        <img style="width:0.62in;height:0.62in" src="./SodaPDFCotNaos/OutDocument/ri_3.jpeg" />
                    </div>
                    <div style="position:absolute;top:3.96in;left:1.27in;">
                        <img style="width:0.62in;height:0.62in" src="./SodaPDFCotNaos/OutDocument/ri_4.jpeg" />
                    </div>
                    <div style="position:absolute;top:4.68in;left:0.73in;">
                        <img style="width:0.27in;height:0.26in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/logo-waze.png').'" />
                    </div>
                    <div style="position:absolute;top:4.68in;left:1.48in;">
                        <img style="width:0.18in;height:0.26in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/logo-maps.png').'" />
                    </div>
                    <div style="text-align: center;position:absolute;top:3.54in;left:0.80in;width:0.89in;line-height:0.16in;">
                        <span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:Effra;color:#de5b68">
                            Ubicación del proyecto
                        </span>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.20in;left:3.71in;width:3.69in;line-height:0.29in;">
                        <p class="ft01">
                            VIDA SEGURA, CERCA DE TODO.
                        </p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.93in;left:0.57in;width:1.32in;line-height:0.17in;">
                        <p style="position:absolute;top:214px;left:62px;white-space:nowrap" class="ft00">Ingresar al brochure</p>
                    </div>
                    <div style="position:absolute;top:6.00in;left:0.00in;">
                        <img style="width:3.09in;height:1.00in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_2.png').'" />
                    </div>
                    <div style="position:absolute;top:6.17in;left:5.01in;">
                        <img style="width:3.83in;height:2.63in" src="'.$planta.'" />
                    </div>
                    <div style="position:absolute;top:8.9in;left:4.90in;">
                        <img style="width:4.53in;height:2.604in" src="'.$plantaTorre.'" />
                    </div>

                    <div style="text-align: justify;position:absolute;top:6.15in;left:0.29in;line-height:0.33in;">
                        <p class="ft04">'.$fase.'</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:6.48in;left:0.29in;line-height:0.41in;">
                        <p class="ft05" >'.$nivel.'</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:10.53in;left:0.38in;width:3.03in;line-height:0.27in;">
                        <p class="ft02">*Imágenes con fines ilustrativos,
                        </p>
                        <p class="ft02">sujeto a cambios sin previo aviso.
                        </p> 
                    </div>
                    
                    <div style="position:absolute;top:7.62in;left:0.32in;">
                        <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_2.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:7.52in;left:0.45in;line-height:0.24in;">
                        <p class="ft03">Dormitorio principal</p>
                    </div>
                    <div style="position:absolute;top:8.14in;left:0.32in;">
                        <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_2.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.03in;left:0.45in;line-height:0.24in;">
                        <p class="ft03">'.$NoHabitacionSec.' dormitorio(s) secundario(s)</p>
                    </div>
                    <div style="position:absolute;top:8.65in;left:0.32in;">
                        <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_2.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.55in;left:0.45in;line-height:0.24in;">
                        <p class="ft03">Baño</p>
                    </div>
                    <div style="position:absolute;top:9.17in;left:0.32in;">
                        <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_2.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.06in;left:0.45in;line-height:0.24in;">
                        <p class="ft03">Sala y comedor</p>
                    </div>
                    <div style="position:absolute;top:9.68in;left:0.32in;">
                        <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_2.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.58in;left:0.45in;line-height:0.24in;">
                        <p class="ft03">Área de cocina y lavandería</p>
                    </div>
                    <div style="position:absolute;top:10.20in;left:0.32in;">
                        <img style="width:0.05in;height:0.05in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_2.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:10.09in;left:0.45in;line-height:0.24in;">
                        <p class="ft03">1 parqueo para moto</p>
                    </div>
                    <div style="position:absolute;top:11.58in;left:0.00in;">
                        <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_3.png').'" />
                    </div>  
                    <div style="position:absolute;top:11.65in;left:0.38in;">
                        <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/avalia-logo.png').'" />
                    </div>  
                    
                    <div style="page-break-after: always;">
                    </div>
        
                    <!- Página 2 ->
                    <div style="position:absolute;top:0.00in;left:0.00in;">
                        <img style="width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_8.png').'" />
                    </div>
                    <div style="position:absolute;top:0.60in;left:0.38in;">
                        <img style="width:2.17in;height:0.89in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_14.png').'" />
                    </div>
                    <div style="position:absolute;top:0.60in;left:6.95in;">
                        <img style="width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_4.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in">
                        <p style="position:absolute;top:90px;left:771px;white-space:nowrap" class="ft200">Apartamento</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.05in;left:7.14in;line-height:0.37in;">
                        <span class="ft205">'.$apartamento.'</span>
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.70in;left:0.38in;">
                        <p class="ft203">Cotizado para</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.70in;left:3.33in;">
                        <p class="ft203">Correo</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.70in;left:6.28in;">
                        <p class="ft203">Teléfono</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:2.09in;left:0.38in;">
                        <p class="ft206">'.$nombreCompleto.'</p>
                    </div>
                    <div style="position:absolute;top:2.29in;left:0.38in;">
                        <img style="width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:2.09in;left:3.33in;">
                        <p class="ft206">'.$correo.'</p>
                    </div>
                    <div style="position:absolute;top:2.29in;left:3.33in;">
                        <img style="width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:2.09in;left:6.28in;">
                        <p class="ft206">'.$telefono.'</p>
                    </div>
                    <div style="position:absolute;top:2.29in;left:6.28in;">
                        <img style="width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.60in;left:7.59in;line-height:0.23in;">
                        <p class="ft207">'.$fechaActual.'</p>
                    </div>
                    
                    <div style="position:absolute;top:11.58in;left:0.00in;">
                        <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_3.png').'" />
                    </div>  
                    <div style="text-align: justify;position:absolute;top:2.96in;left:0.38in;width:2.57in;line-height:0.19in;">
                        <p class="ft201">¡Tu nuevo hogar te está esperando!</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:3.37in;left:0.38in;width:8.55in;">
                        <span class="ft209">Recibe un cordial saludo de parte del equipo de Avalia Desarrollos, desarrolladores de NAOS Apartamentos. A continuación te presentamos la cotización del apartamento tipo '.$apartamento.' de '.$noHabitacion.' habitaciones, sala, comedor, cocina, acceso a balcón francés, área de lavandería, dormitorio principal, '.$NoHabitacionSec.' Habitacion(es) adicional(es), 1 baño completo y parqueo de motocicleta. Parqueo de vehículos por medio de alquiler.
                        </span>
                    </div>
                    <div style="text-align: justify;position:absolute;top:4.40in;left:0.38in;width:2.09in;line-height:0.19in;">
                        <span class="ft201">Información de apartamento</span>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.00in;left:0.38in;line-height:0.19in;">
                        <p class="ft203">Módulo</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.00in;left:2.51in;line-height:0.19in;">
                        <p class="ft203">Nivel</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.00in;left:4.72in;line-height:0.19in;">
                        <p class="ft203">Apartamento</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.00in;left:6.94in;line-height:0.19in;">
                        <p class="ft203">Tamaño</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.40in;left:0.39in;line-height:0.19in;">
                        <p class="ft206">'.$fase.'</p>
                    </div>
                    <div style="position:absolute;top:5.60in;left:0.38in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.40in;left:2.52in;line-height:0.19in;">
                        <p class="ft206">'.$nivel.'</p>
                    </div>
                    <div style="position:absolute;top:5.60in;left:2.51in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.40in;left:4.73in;width:1.00in;line-height:0.19in;">
                        <p class="ft206">'.$apartamento.'</p>
                    </div>
                    <div style="position:absolute;top:5.60in;left:4.72in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.40in;left:6.95in;line-height:0.19in;">
                        <p class="ft206">'.$tamanio.'</p>
                    </div>
                    <div style="position:absolute;top:5.60in;left:6.94in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.89in;left:0.38in;line-height:0.19in;">
                        <p class="ft203">Habitaciones</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.89in;left:2.51in;line-height:0.19in;">
                        <p class="ft203">Incluye cocina</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.89in;left:4.72in;width:1.75in;line-height:0.19in;">
                        <p class="ft203">Costo parqueo de moto extra</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.89in;left:6.94in;width:1.75in;line-height:0.19in;">
                        <p class="ft203">Parqueos de moto extra</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:6.29in;left:0.39in;line-height:0.19in;">
                        <p class="ft206">'.$habitaciones.'</p>
                    </div>
                    <div style="position:absolute;top:6.49in;left:0.38in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:6.29in;left:2.52in;width:1.00in;line-height:0.19in;">
                        <p class="ft206">'.$conCocina.'</p>
                    </div>
                    <div style="position:absolute;top:6.49in;left:2.51in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:6.29in;left:4.73in;width:1.00in;line-height:0.19in;">
                        <p class="ft206">'.$costoParqueo.'</p>
                    </div>
                    <div style="position:absolute;top:6.49in;left:4.72in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:6.29in;left:6.95in;line-height:0.19in;">
                        <p class="ft206">'.$parqueoExtra.'</p>
                    </div>
                    <div style="position:absolute;top:6.49in;left:6.94in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>

                    <div style="position:absolute;top:4.69in;left:0.38in;">
                        <img style="width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_11.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:6.68in;left:0.38in;width:2.09in;line-height:0.19in;">
                        <span class="ft201">Información de precios</span>
                    </div>
                    <div style="position:absolute;top:6.97in;left:0.38in;">
                        <img style="width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_11.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:7.12in;left:0.38in;width:1.76in;line-height:0.19in;">
                        <p class="ft203">Total parqueos de moto extra</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:7.12in;left:2.51in;line-height:0.19in;">
                        <p class="ft203">Precio total</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:7.53in;left:0.39in;line-height:0.19in;">
                        <p class="ft206">'.$totalParqueos.'</p>
                    </div>
                    <div style="position:absolute;top:7.73in;left:0.38in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:7.53in;left:2.52in;width:1.00in;line-height:0.19in;">
                        <p class="ft206">'.$precioTotal.'</p>
                    </div>
                    <div style="position:absolute;top:7.73in;left:2.52in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.01in;left:0.38in;line-height:0.19in;">
                        <p class="ft203">Enganche</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.01in;left:2.51in;line-height:0.19in;">
                        <p class="ft203">Pagos de enganche</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.01in;left:4.72in;width:1.75in;line-height:0.19in;">
                        <p class="ft203">Enganche mensual</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.01in;left:6.94in;width:1.75in;line-height:0.19in;">
                        <p class="ft203">Saldo financiar</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.41in;left:0.39in;line-height:0.19in;">
                        <p class="ft206">'.$engancheMonto.'</p>
                    </div>
                    <div style="position:absolute;top:8.61in;left:0.38in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.41in;left:2.52in;width:1.00in;line-height:0.19in;">
                        <p class="ft206">'.$enganchePagos.'</p>
                    </div>
                    <div style="position:absolute;top:8.61in;left:2.51in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.41in;left:4.73in;width:1.00in;line-height:0.19in;">
                        <p class="ft206">'.$engancheMensual.'</p>
                    </div>
                    <div style="position:absolute;top:8.61in;left:4.72in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.41in;left:6.95in;line-height:0.19in;">
                        <p class="ft206">'.$saldoContraEntrega.'</p>
                    </div>
                    <div style="position:absolute;top:8.61in;left:6.94in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.84in;left:0.38in;width:2.09in;line-height:0.19in;">
                        <span class="ft201">Información de cuota</span>
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.42in;left:0.38in;line-height:0.19in;">
                        <p class="ft203">Plazo financiamiento</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.42in;left:2.51in;line-height:0.19in;">
                        <p class="ft203">Cuota crédito</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.42in;left:4.72in;width:1.75in;line-height:0.19in;">
                        <p class="ft203">IUSI</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.42in;left:6.94in;width:1.75in;line-height:0.19in;">
                        <p class="ft203">Seguro</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.82in;left:0.39in;line-height:0.19in;">
                        <p class="ft206">'.$plazoFinanciamiento.'</p>
                    </div>
                    <div style="position:absolute;top:10.02in;left:0.38in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.82in;left:2.52in;width:1.00in;line-height:0.19in;">
                        <p class="ft206">'.$cuotaCredito.'</p>
                    </div>
                    <div style="position:absolute;top:10.02in;left:2.51in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.82in;left:4.73in;width:1.00in;line-height:0.19in;">
                        <p class="ft206">'.$iusi.'</p>
                    </div>
                    <div style="position:absolute;top:10.02in;left:4.72in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.82in;left:6.95in;line-height:0.19in;">
                        <p class="ft206">'.$seguro.'</p>
                    </div>
                    <div style="position:absolute;top:10.02in;left:6.94in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:10.30in;left:0.38in;line-height:0.19in;">
                        <p class="ft203">Cuota total</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:10.30in;left:2.51in;line-height:0.19in;">
                        <p class="ft203">Ingreso familiar requerido</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:10.30in;left:6.94in;width:1.75in;line-height:0.19in;">
                        <p class="ft203">Cuota estimada mantenimiento</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:10.71in;left:0.39in;line-height:0.19in;">
                        <p class="ft206">'.$cuotaTotal.'</p>
                    </div>
                    <div style="position:absolute;top:10.91in;left:0.38in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:10.71in;left:2.52in;width:1.00in;line-height:0.19in;">
                        <p class="ft206">'.$ingresoFamiliar.'</p>
                    </div>
                    <div style="position:absolute;top:10.91in;left:2.51in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:10.71in;left:6.95in;line-height:0.19in;">
                        <p class="ft206">'.$cuotaMantenimiento.'</p>
                    </div>
                    <div style="position:absolute;top:10.91in;left:6.94in;">
                        <img style="width:1.94in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>

                    <div style="position:absolute;top:9.13in;left:0.38in;">
                        <img style="width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_11.png').'" />
                    </div>
                    <div style="position:absolute;top:11.65in;left:0.38in;">
                        <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/avalia-logo.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:11.66in;left:7.09in;line-height:0.22in;">
                        <span class="ft208">'.$correoVendedor.'</span>
                    </div>  

                    <div style="page-break-after: always;">
                    </div>
        
                    <!- Página 3 ->

                    <div style="position:absolute;top:0.00in;left:0.00in;">
                        <img style="width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/vi_8.png').'" />
                    </div>
                    <div style="position:absolute;top:0.60in;left:0.38in;">
                        <img style="width:2.17in;height:0.89in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ri_14.png').'" />
                    </div>
                    <div style="position:absolute;top:0.60in;left:6.95in;">
                        <img style="width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_4.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:0.75in;left:7.14in">
                        <p style="position:absolute;top:90px;left:771px;white-space:nowrap" class="ft200">Apartamento</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.05in;left:7.14in;line-height:0.37in;">
                        <span class="ft205">'.$apartamento.'</span>
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.70in;left:0.38in;">
                        <p class="ft203">Cotizado para</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.70in;left:3.33in;">
                        <p class="ft203">Correo</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.70in;left:6.28in;">
                        <p class="ft203">Teléfono</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:2.09in;left:0.38in;">
                        <p class="ft206">'.$nombreCompleto.'</p>
                    </div>
                    <div style="position:absolute;top:2.29in;left:0.38in;">
                        <img style="width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:2.09in;left:3.33in;">
                        <p class="ft206">'.$correo.'</p>
                    </div>
                    <div style="position:absolute;top:2.29in;left:3.33in;">
                        <img style="width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:2.09in;left:6.28in;">
                        <p class="ft206">'.$telefono.'</p>
                    </div>
                    <div style="position:absolute;top:2.29in;left:6.28in;">
                        <img style="width:2.58in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_6.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:1.60in;left:7.59in;line-height:0.23in;">
                        <p class="ft207">'.$fechaActual.'</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:2.78in;left:0.38in;width:3.17in;line-height:0.21in;">
                        <span class="ft301">Requisitos para adquirir tu apartamento</span>
                    </div>
                    <div style="text-align:justify;position:absolute;top:3.18in;left:0.45in;width:8.17in;line-height:0.18in;">
                        <p class="ft305">a) Tener buenas referencias de crédito y personales.</p>
                    </div>
                    <div style="text-align:justify;position:absolute;top:3.43in;left:0.45in;width:8.17in;line-height:0.18in;">
                        <p class="ft305">b) Tener hasta un 40% de sus ingresos netos comprobables disponibles para poder realizar el pago del financiamiento por la compra.</p>
                    </div>
                    <div style="text-align:justify;position:absolute;top:3.88in;left:0.45in;width:8.17in;line-height:0.18in;">
                        <p class="ft305">c) Por lo menos 1 año de continuidad laboral al momento de aplicar para el crédito.</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:4.46in;left:0.38in;width:1.98in;line-height:0.21in;">
                        <p class="ft301">Información de contacto</p>
                    </div>
                    <div style="position:absolute;top:4.75in;left:0.38in;">
                        <img style="width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_11.png').'" />
                    </div>
                    <div style="text-align:justify;position:absolute;top:4.94in;left:0.38in;width:0.72in;line-height:0.19in;">
                        <p class="ft203">Contacto</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.33in;left:0.39in;line-height:0.19in;">
                        <p class="ft303">'.$contacto.'</p>
                    </div>
                    <div style="position:absolute;top:5.53in;left:0.38in">
                        <img style="width:4.19in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_68.png').'" />
                    </div>
                    <div style="text-align:justify;position:absolute;top:4.94in;left:4.72in;line-height:0.19in;">
                        <p class="ft203">Teléfono contacto</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.33in;left:4.73in;line-height:0.19in;">
                        <p class="ft303">'.$telefonoContacto.'</p>
                    </div>
                    <div style="position:absolute;top:5.53in;left:4.72in">
                        <img style="width:4.19in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_68.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:5.83in;left:0.38in;width:8.50in;line-height:0.19in;">
                        <p class="ft3010">Precio total incluye gastos de escrituración e impuestos de traspaso. Monto reserva: Q. 2,000.00 que serán acreditados al valor del enganche.<b> *Gabinetes de cocina y electrodomésticos se venden por separado.</b></p> 
                    </div>
                    <div style="text-align: justify;position:absolute;top:6.43in;left:0.38in;width:8.50in;line-height:0.19in;">
                        <p class="ft3010">La presente cotización tiene una vigencia de 10 días hábiles contados a partir de la fecha de emisión.</p> 
                    </div>
                    <div style="text-align: justify;position:absolute;top:6.84in;left:0.38in;width:8.50in;line-height:0.19in;">
                        <p class="ft3010">Este material es utilizado para fines informativos y de referencia. <b>SPV NAOS, S.A.</b> se reserva el derecho de hacer modificaciones a los modelos a su discreción para efectos del desarrollo del proyecto.</p> 
                    </div>
                    <div style="text-align: justify;position:absolute;top:7.44in;left:0.38in;width:8.50in;line-height:0.19in;">
                        <p class="ft3010">Los precios están sujetos a cambios sin previo aviso. Este material es utilizado para fines informativos y de referencia.</p> 
                    </div>
                    <div style="text-align: justify;position:absolute;top:7.82in;left:0.38in;line-height:0.21in;">
                        <p class="ft304">Descripción general del proyecto</p>
                    </div>
                    <div style="position:absolute;top:8.11in;left:0.38in;">
                        <img style="width:8.53in;height:0.01in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_11.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.35in;left:0.38in;width:8.50in;line-height:0.19in;">
                        <p class="ft303"><b>Módulos:</b> Cada módulo/edificio contara con 6 niveles, 4 apartamentos por nivel. Los módulos no cuentan con elevadores.</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:8.80in;left:0.38in;width:8.50in;line-height:0.19in;">
                        <p class="ft303"><b>Parqueos:</b> Cada apartamento incluye 1 parqueo de motocicleta. Parqueos adicionales de motocicletas se venden por separado. En un terreno continuo al área de edificios, existira un número limitado de espacios para alquilar parqueos de vehículos.</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:9.64in;left:0.38in;width:8.50in;line-height:0.19in;">
                        <p class="ft303"><b>Servicios:</b> Cada apartamento deberá pagar su cuota de mantenimiento, que le dara acceso a: A) Cierta cantidad de m³.</p>
                    </div>
                    <div style="text-align: justify;position:absolute;top:10.24in;left:0.38in;width:8.50in;line-height:0.19in;">
                        <p class="ft303"><b>Reglas de Convivencia:</b> Al vivir en una comunidad, todos los propietarios y habitantes del proyecto, estarán obligados a cumplir con normas de convivencia para el beneficio de todos, las cuales incluyen ornato, respeto dentro de su apartamento como hacia sus vecinos, cumplir con sus pagos de mantenimiento en tiempo, evitar los excesos que afecten la vida en comunidad.</p>
                    </div>

                    <div style="position:absolute;top:11.58in;left:0.00in;">
                        <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/ci_3.png').'" />
                    </div>  
                    <div style="position:absolute;top:11.65in;left:0.38in;">
                        <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotNaos/OutDocument/avalia-logo.png').'" />
                    </div>
                    <div style="text-align: justify;position:absolute;top:11.66in;left:7.09in;line-height:0.22in;">
                        <span class="ft208">'.$correoVendedor.'</span>
                    </div>  

                    
                    
            </html>
        ';


        
        $texto_r1='
        <p style="position:absolute;top:90px;left:771px;white-space:nowrap" class="ft00">Apartamento</p>
		<p style="position:absolute;top:488px;left:41px;white-space:nowrap" class="ft01">Información de contacto</p>
		<p style="position:absolute;top:908px;left:41px;white-space:nowrap" class="ft02">Módulos:</p>
		<p style="position:absolute;top:908px;left:106px;white-space:nowrap" class="ft03">&#160;Cada módulo/edificio
			contara con 6 niveles, 4 apartamentos por nivel. Los módulos no cuentan con elevadores.</p>
		<p style="position:absolute;top:958px;left:41px;white-space:nowrap" class="ft02">Parqueos:</p>
		<p style="position:absolute;top:958px;left:113px;white-space:nowrap" class="ft03">
			&#160;Cada&#160;apartamento&#160;incluye&#160;1&#160;parqueo&#160;de&#160;motocicleta.&#160;Parqueos&#160;adicionales&#160;de&#160;motocicletas&#160;se&#160;venden&#160;por&#160;separado.&#160;En
		</p>
		<p style="position:absolute;top:986px;left:41px;white-space:nowrap" class="ft03">un terreno continuo al área de
			edificios, existira un número limitado de espacios para alquilar parqueos de vehículos.</p>
		<p style="position:absolute;top:1047px;left:41px;white-space:nowrap" class="ft02">Servicios:</p>
		<p style="position:absolute;top:1047px;left:111px;white-space:nowrap" class="ft03">&#160;Cada apartamento deberá
			pagar su cuota de mantenimiento, que le dara acceso a: A) Cierta cantidad de m³</p>
		<p style="position:absolute;top:1113px;left:41px;white-space:nowrap" class="ft02">
			Reglas&#160;de&#160;Convivencia:</p>
		<p style="position:absolute;top:1113px;left:206px;white-space:nowrap" class="ft03">
			&#160;Al&#160;vivir&#160;en&#160;una&#160;comunidad,&#160;todos&#160;los&#160;propietarios&#160;y&#160;habitantes&#160;del&#160;proyecto,&#160;estarán&#160;obligados&#160;a&#160;cumplir&#160;con
		</p>
		<p style="position:absolute;top:1142px;left:41px;white-space:nowrap" class="ft09">
			normas&#160;&#160;de&#160;&#160;convivencia&#160;&#160;para&#160;&#160;el&#160;&#160;beneficio&#160;&#160;de&#160;&#160;todos,&#160;&#160;las&#160;&#160;cuales&#160;&#160;incluyen&#160;&#160;ornato,&#160;&#160;respeto&#160;&#160;dentro&#160;&#160;de&#160;&#160;su&#160;&#160;apartamento&#160;&#160;como&#160;&#160;hacia&#160;&#160;sus<br />vecinos,
			cumplir con sus pagos de mantenimiento en tiempo, evitar los excesos que afecten la vida en comunidad.</p>
		<p style="position:absolute;top:211px;left:41px;white-space:nowrap" class="ft02">Cotizado para</p>
		<p style="position:absolute;top:211px;left:360px;white-space:nowrap" class="ft02">Correo</p>
		<p style="position:absolute;top:211px;left:678px;white-space:nowrap" class="ft02">Teléfono</p>
		<p style="position:absolute;top:307px;left:41px;white-space:nowrap" class="ft01">Requisitos para adquirir tu
			apartamento</p>
		<p style="position:absolute;top:540px;left:41px;white-space:nowrap" class="ft02">Contacto</p>
		<p style="position:absolute;top:540px;left:510px;white-space:nowrap" class="ft02">Teléfono contacto</p>
		<p style="position:absolute;top:636px;left:41px;white-space:nowrap" class="ft010">
			Precio&#160;total&#160;incluye&#160;gastos&#160;de&#160;escrituración&#160;e&#160;impuestos&#160;de&#160;traspaso.&#160;Monto&#160;reserva:&#160;Q.&#160;2,000.00&#160;que&#160;serán&#160;acreditados&#160;al&#160;valor&#160;del<br />enganche.
		</p>
		<p style="position:absolute;top:663px;left:114px;white-space:nowrap" class="ft02">&#160;*Gabinetes de cocina y
			electrodomésticos se venden por separado</p>
		<p style="position:absolute;top:700px;left:41px;white-space:nowrap" class="ft03">La presente cotización tiene
			una vigencia de 10 días hábiles contados a partir de la fecha de emisión</p>
		<p style="position:absolute;top:745px;left:41px;white-space:nowrap" class="ft03">
			Este&#160;material&#160;es&#160;utilizado&#160;para&#160;fines&#160;informativos&#160;y&#160;de&#160;referencia.&#160;
		</p>
		<p style="position:absolute;top:745px;left:497px;white-space:nowrap" class="ft02">SPV&#160;NAOS,&#160;S.A.&#160;
		</p>
		<p style="position:absolute;top:745px;left:622px;white-space:nowrap" class="ft03">
			se&#160;reserva&#160;el&#160;derecho&#160;de&#160;hacer&#160;modificaciones&#160;a</p>
		<p style="position:absolute;top:772px;left:41px;white-space:nowrap" class="ft03">los modelos a su discreción
			para efectos del desarrollo del proyecto.</p>
		<p style="position:absolute;top:810px;left:41px;white-space:nowrap" class="ft03">Los precios están sujetos a
			cambios sin previo aviso. Este material es utilizado para fines informativos y de referencia.</p>
		<p style="position:absolute;top:851px;left:41px;white-space:nowrap" class="ft04">Descripción general del
			proyecto</p>
		<p style="position:absolute;top:350px;left:49px;white-space:nowrap" class="ft05">a) Tener buenas referencias de
			crédito y personales.</p>
		<p style="position:absolute;top:378px;left:49px;white-space:nowrap" class="ft05">b) Tener hasta un 40% de sus
			ingresos netos comprobables disponibles para poder realizar el pago del financiamiento por la</p>
		<p style="position:absolute;top:405px;left:49px;white-space:nowrap" class="ft011">compra.<br />c) Por lo menos 1
			año de continuidad laboral al momento de aplicar para el crédito</p>
		<p style="position:absolute;top:117px;left:771px;white-space:nowrap" class="ft06">3B1</p>
		<p style="position:absolute;top:206px;left:818px;white-space:nowrap" class="ft07">04/11/2022</p>
		<p style="position:absolute;top:255px;left:43px;white-space:nowrap" class="ft03">Sheyle&#160;&#160;Ruiz</p>
		<p style="position:absolute;top:255px;left:362px;white-space:nowrap" class="ft03">Sheyle@chn.con.gt</p>
		<p style="position:absolute;top:255px;left:680px;white-space:nowrap" class="ft03">33271798</p>
		<p style="position:absolute;top:582px;left:42px;white-space:nowrap" class="ft03">Cesar&#160;&#160;Rios</p>
		<p style="position:absolute;top:583px;left:515px;white-space:nowrap" class="ft03">59780555</p>
		<p style="position:absolute;top:1268px;left:758px;white-space:nowrap" class="ft08">crios@avalia.gt</p>
        ';

    }

    $mpdf ->writeHtml($css_r,\Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf ->writeHtml($texto_r,\Mpdf\HTMLParserMode::HTML_BODY);
    
    $mpdf ->Output($nombre,'D');
}
if(isset($_GET['docFHAPdf'])){
    $id_cliente=$_GET['idCliente'];
    $rTmpRefBancarias = array();
    $rTmpDetallePatrimonial = (object) array( "direccion_inmueble_1" => "",
    "direccion_inmueble_2" => "",
    "finca_1"=> "",
    "folio_2"=> "",
    "libro_2"=> "",
    "departamento_2" => "",
    "valor_inmueble_2" => "",
    "folio_1"=> "",
    "libro_1"=> "",
    "departamento_1" => "",
    "valor_inmueble_1" => "",
    "finca_2"=> "",
    "marca_1" => "",
    "tipo_vehiculo_1" => "",
    "modelo_vehiculo_1" => "",
    "marca_2" => "",
    "tipo_vehiculo_2" => "",
    "modelo_vehiculo_2" => "",
    "valor_estimado_1" => "",
    "valor_estimado_2"=> "",
    "departamento_nombre_1" => "",
    "departamento_nombre_2" => "" );
    $rTmpDetalleComisiones = array(  "mes_1"  => "",
    "mes_2"  => "",
    "mes_3"  => "",
    "mes_4"  => "",
    "mes_5"  => "",
    "mes_6"  => "",
    "hora_extra_mes_1"  => "",
    "hora_extra_mes_2"  => "",
    "hora_extra_mes_3"  => "",
    "hora_extra_mes_4"  => "",
    "hora_extra_mes_5"  => "",
    "hora_extra_mes_6"  => "",
    "comisiones_mes_1"  => "",
    "comisiones_mes_2"  => "",
    "comisiones_mes_3"  => "",
    "comisiones_mes_4"  => "",
    "comisiones_mes_5"  => "",
    "comisiones_mes_6"  => "",
    "bonificaciones_mes_1"  => "",
    "bonificaciones_mes_2"  => "",
    "bonificaciones_mes_3"  => "",
    "bonificaciones_mes_4"  => "",
    "bonificaciones_mes_5"  => "",
    "bonificaciones_mes_6"  => "",
    "fecha_creacion"  => date("Y-m-d"));
    $rTmpIngresosEgresos = array(  "tipoContrato"  => "",
    "vigencia_vence"  => "",
    "salario_nominal"  => "",
    "bono_catorce"  => "",
    "aguinaldo"  => "",
    "honorarios"  => "",
    "otros_ingresos_fha"  => "",
    "igss"  => "",
    "isr"  => "",
    "plan_pensiones"  => "",
    "judiciales"  => "",
    "otros_descuentos_fha"  => "",);
    $rTmpHistorialLaboral  = array(    "empresa_1"  => "",
    "cargo_1"  => "",
    "desde_1"  => "",
    "hasta_1"  => "",
    "empresa_2"  => "",
    "cargo_2"  => "",
    "desde_2"  => "",
    "hasta_2"  => "",
    "empresa_3"  => "",
    "cargo_3"  => "",
    "desde_3"  => "",
    "hasta_3"  => "",
    "empresa_4"  => "",
    "cargo_4"  => "",
    "desde_4"  => "",
    "hasta_4"  => "",);
    $rTmpRefFamiliar = array(    "nombre_referencia_1"  => "",
    "parentesco_referencia_1"  => "",
    "domicilio_1"  => "",
    "telefono_1"  => "",
    "trabajo_1"  => "",
    "trabajo_direccion_1"  => "",
    "trabajo_telefono_1"  => "",
    "nombre_referencia_2"  => "",
    "parentesco_referencia_2"  => "",
    "domicilio_2"  => "",
    "telefono_2"  => "",
    "trabajo_2"  => "",
    "trabajo_direccion_2"  => "",
    "trabajo_telefono_2"  => "",);
    $rTmpRefBancarias = array(    "banco_1"  => "",
    "tipo_cuenta_1"  => "",
    "no_cuenta_1"  => "",
    "saldo_actual_1"  => "",
    "banco_2"  => "",
    "tipo_cuenta_2"  => "",
    "no_cuenta_2"  => "",
    "saldo_actual_2"  => "",);
    $rTmpRefCrediticias = array(    "banco_prestamo_1"  => "",
    "tipo_prestamo_1"  => "",
    "no_prestamo_1"  => "",
    "monto_1"  => "",
    "saldo_actual_prestamo_1"  => "",
    "pago_mensual_prestamo_1"  => "",
    "fecha_vencimiento_prestamo_1"  => "",
    "banco_prestamo_2"  => "",
    "tipo_prestamo_2"  => "",
    "no_prestamo_2"  => "",
    "monto_2"  => "",
    "saldo_actual_prestamo_2"  => "",
    "pago_mensual_prestamo_2"  => "",
    "fecha_vencimiento_prestamo_2"  => "",
    "banco_prestamo_3"  => "",
    "tipo_prestamo_3"  => "",
    "monto_3"  => "",
    "saldo_actual_prestamo_3"  => "",
    "pago_mensual_prestamo_3"  => "",
    "fecha_vencimiento_prestamo_3"  => "",);

    $strQueryPdf = "SELECT ag.*, a.*,e.*,
                    IF(tipoCliente='individual', 
                    CONCAT(if(ag.primerNombre='','',IFNULL(CONCAT(ag.primerNombre,' '),'')),if(ag.segundoNombre='','',IFNULL(CONCAT(ag.segundoNombre,' '),'')),if(ag.tercerNombre='','',IFNULL(CONCAT(ag.tercerNombre,' '),'')),if(ag.primerApellido='','',IFNULL(CONCAT(ag.primerApellido,' '),'')),
                    if(ag.segundoApellido='','',IFNULL(CONCAT(ag.segundoApellido,' '),'')),if(ag.apellidoCasada='','',IFNULL(CONCAT(ag.apellidoCasada,' '),''))), nombre_sa)  as client_name,
                    (SELECT GROUP_CONCAT(idCodeudor) as ids FROM `codeudor` WHERE idCliente = {$id_cliente}  group by idCliente) as codeudores
                    
                FROM agregarCliente ag
                LEFT JOIN enganche e ON ag.idCliente = e.idCliente 
                LEFT JOIN apartamentos a ON e.apartamento = a.apartamento
                LEFT JOIN datosGlobales dg ON e.proyecto = dg.proyecto
                LEFT JOIN codeudor c ON e.idEnganche = c.idEnganche
                WHERE  ag.idCliente = {$id_cliente}";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQueryPdf);
    $rTmp = $conn->db_fetch_object($qTmp);
    
    $caja=0;
    $bancos=0;
    $cuentas_cobrar=0;
    $terrenos=0;
    $viviendas=0;
    $vehiculos=0;
    $inversiones=0;
    $bonos=0;
    $acciones=0;
    $muebles=0;
    $cuentas_pagar_corto_plazo=0;
    $cuentas_pagar_largo_plazo=0;
    $prestamos_hipotecarios=0;
    $sostenimiento_hogar=0;
    $alquiler=0;
    $prestamos=0;
    $impuestos=0;
    $extrafinanciamientos=0;
    $deudas_particulares=0;
    $codeudores = $rTmp->codeudores != '' ? $rTmp->codeudores : '0';
    $strQueryPatrimonial = "SELECT * FROM infoPatrimonial
                                WHERE  idCliente = {$id_cliente}
                            UNION 
                            SELECT * FROM infoPatrimonialCo 
                            WHERE idCliente in ({$codeudores})";

    //echo $strQuery;
    $qTmpPatrimonial = $conn ->db_query($strQueryPatrimonial);
    while($rTmpPatrimonial = $conn->db_fetch_object($qTmpPatrimonial)){
        $caja += $rTmpPatrimonial->caja;
        $bancos+= $rTmpPatrimonial->bancos;
        $cuentas_cobrar+= $rTmpPatrimonial->cuentas_cobrar;
        $terrenos+= $rTmpPatrimonial->terrenos;
        $viviendas+= $rTmpPatrimonial->viviendas;
        $vehiculos+= $rTmpPatrimonial->vehiculos;
        $inversiones+= $rTmpPatrimonial->inversiones;
        $bonos+= $rTmpPatrimonial->bonos;
        $acciones+= $rTmpPatrimonial->acciones;
        $muebles+= $rTmpPatrimonial->muebles;
        $cuentas_pagar_corto_plazo+= $rTmpPatrimonial->cuentas_pagar_corto_plazo;
        $cuentas_pagar_largo_plazo+= $rTmpPatrimonial->cuentas_pagar_largo_plazo;
        $prestamos_hipotecarios+= $rTmpPatrimonial->prestamos_hipotecarios;
        $sostenimiento_hogar+= $rTmpPatrimonial->sostenimiento_hogar;
        $alquiler+= $rTmpPatrimonial->alquiler;
        $prestamos+= $rTmpPatrimonial->prestamos;
        $impuestos+= $rTmpPatrimonial->impuestos;
        $extrafinanciamientos+= $rTmpPatrimonial->extrafinanciamientos;
        $deudas_particulares+= $rTmpPatrimonial->deudas_particulares;
    }
    $strQueryDetallePatrimonial = " SELECT dp.*,cd1.nombre_depto as departamento_nombre_1,cd2.nombre_depto as departamento_nombre_2 FROM detallePatrimonial dp
    LEFT JOIN catDepartamento cd1 ON dp.departamento_1 = cd1.id_depto
    LEFT JOIN catDepartamento cd2 ON dp.departamento_2 = cd2.id_depto
    WHERE  idCliente = {$id_cliente}";

    //echo $strQuery;
    $qTmpDetallePatrimonial = $conn ->db_query($strQueryDetallePatrimonial);
    if($conn ->db_num_rows($qTmpDetallePatrimonial) > 0){
        $rTmpDetallePatrimonial = $conn->db_fetch_object($qTmpDetallePatrimonial);
    }
    $direccion_inmueble_1 = $rTmpDetallePatrimonial->direccion_inmueble_1;
    $direccion_inmueble_2 = $rTmpDetallePatrimonial->direccion_inmueble_2;
   
    
    $soltero='';
    $casado='';
    if($rTmp->estadoCivil=='Soltero' || $rTmp->estadoCivil=='Divorciado'){
        $soltero = 'X';
    }else if($rTmp->estadoCivil=='Casado' || $rTmp->estadoCivil=='Viudo'){
        $casado='X';
    }
    $strQueryIngresosEgresos = " SELECT * FROM detalleIngresosDescuentosMensuales
    WHERE  idCliente = {$id_cliente}";

    //echo $strQuery;
    $qTmpIngresosEgresos = $conn ->db_query($strQueryIngresosEgresos);
    if($conn ->db_num_rows($qTmpIngresosEgresos) > 0){
        $rTmpIngresosEgresos = $conn->db_fetch_object($qTmpIngresosEgresos);
    }

    
    $strQueryDetalleComisiones = " SELECT * FROM detalleComisiones
    WHERE  idCliente = {$id_cliente}";

    //echo $strQuery;
    $qTmpDetalleComisiones = $conn ->db_query($strQueryDetalleComisiones);
    if($conn ->db_num_rows($qTmpDetalleComisiones) > 0){
        $rTmpDetalleComisiones = $conn->db_fetch_object($qTmpDetalleComisiones);
    }
    
    $arr = array();
    for($x=1;$x<=6;$x++){
        $arr[] = nombreMes(date("m",strtotime($rTmpDetalleComisiones->fechaCreacion."- ".$x." month")));
    }
    $strQueryHistorialLaboral = " SELECT * FROM historialLaboral
    WHERE  idCliente = {$id_cliente}";

    //echo $strQuery;
    $qTmpHistorialLaboral = $conn ->db_query($strQueryHistorialLaboral);
    if($conn ->db_num_rows($qTmpHistorialLaboral) > 0){
        $rTmpHistorialLaboral = $conn->db_fetch_object($qTmpHistorialLaboral);
    }

    $strQueryRefFamiliar = " SELECT * FROM refFamiliar
    WHERE  idCliente = {$id_cliente}";

    //echo $strQuery;
    $qTmpRefFamiliar = $conn ->db_query($strQueryRefFamiliar);
    if($conn ->db_num_rows($qTmpRefFamiliar) > 0){
        $rTmpRefFamiliar = $conn->db_fetch_object($qTmpRefFamiliar);
    }
    

    $strQueryRefBancarias = " SELECT * FROM refbancarias
    WHERE  idCliente = {$id_cliente}";

    //echo $strQuery;
    $qTmpRefBancarias = $conn ->db_query($strQueryRefBancarias);
    if($conn ->db_num_rows($qTmpRefBancarias) > 0){
        $rTmpRefBancarias = $conn->db_fetch_object($qTmpRefBancarias);
    }


    $strQueryRefCrediticias = " SELECT * FROM refCrediticias
    WHERE  idCliente = {$id_cliente}";

    //echo $strQuery;
    $qTmpRefCrediticias = $conn ->db_query($strQueryRefCrediticias);
    if($conn ->db_num_rows($qTmpRefCrediticias) > 0){
        $rTmpRefCrediticias = $conn->db_fetch_object($qTmpRefCrediticias);

    }
    
    if ($rTmp->proyecto == 'Marabi'){
        $logo = '../img/logo Marabi.png';
        $fondo = '<div style="position:absolute;top:0.00in;left:0.00in;">
        <img style="width:9.27in;height:12.00in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/vi_12.png').'" />
    </div>';
    $fondo_apartamento = '<img style="width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_5.png').'" />';
    $color = '#FCDFDB';
    $color_depto = '#FCDFDB';
    }else if($rTmp->proyecto == 'Naos'){
        $logo = './SodaPDFCotNaos/OutDocument/logo-transparente.png';
        $fondo = '';
        $fondo_apartamento = '<img style="width:2.32in;height:0.83in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_37.png').'" />';
        $color = '#dd5a67';
        $color_depto = '#000000';
    }
    //Si hay codeudor
    $texto_rCo = '';
    if($rTmp->codeudores!=''){
        $arrayCo = explode ( ',', $rTmp->codeudores );

        foreach ( $arrayCo as $codeudorPdf ) {
            $CorTmpRefBancarias = (object) array();
            $CorTmpDetallePatrimonial =(object) array("direccion_inmueble_1" => "",
            "direccion_inmueble_2" => "",
            "finca_1"=> "",
            "folio_2"=> "",
            "libro_2"=> "",
            "departamento_2" => "",
            "valor_inmueble_2" => "",
            "folio_1"=> "",
            "libro_1"=> "",
            "departamento_1" => "",
            "valor_inmueble_1" => "",
            "finca_2"=> "",
            "marca_1" => "",
            "tipo_vehiculo_1" => "",
            "modelo_vehiculo_1" => "",
            "marca_2" => "",
            "tipo_vehiculo_2" => "",
            "modelo_vehiculo_2" => "",
            "valor_estimado_1" => "",
            "valor_estimado_2"=> "",
            "departamento_nombre_1" => "",
            "departamento_nombre_2" => "" );
            $CorTmpDetalleComisiones =(object) array();
            $CorTmpIngresosEgresos = (object) array();
            $CorTmpHistorialLaboral  = (object) array();
            $CorTmpRefFamiliar = (object) array();
            $CorTmpRefBancarias = (object) array();
            $CorTmpRefCrediticias = (object) array();
            $CorTmpPatrimonial = (object) array();

            $strQueryCo = "SELECT ag.*,cp.pais as NacionalidadNombre, 
                    CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),''))  as client_name,
            ag.correoElectronico as client_mail
            FROM codeudor ag
            LEFT JOIN catPais cp ON ag.Nacionalidad = cp.id_pais
            WHERE ag.idCodeudor = {$codeudorPdf}";

            //echo $strQuery;
            $qTmpCo = $conn ->db_query($strQueryCo);
            $rTmpCo = $conn->db_fetch_object($qTmpCo);

            $CostrQueryDetallePatrimonial = " SELECT dp.*,cd1.nombre_depto as departamento_nombre_1,cd2.nombre_depto as departamento_nombre_2 FROM detallePatrimonialCo dp
            LEFT JOIN catDepartamento cd1 ON dp.departamento_1 = cd1.id_depto
            LEFT JOIN catDepartamento cd2 ON dp.departamento_2 = cd2.id_depto
            WHERE  idCliente = {$codeudorPdf}";
        
            //echo $CostrQueryDetallePatrimonial;exit();
            $CoqTmpDetallePatrimonial = $conn ->db_query($CostrQueryDetallePatrimonial);
            $CorTmpDetallePatrimonial = $conn->db_fetch_object($CoqTmpDetallePatrimonial);
            $Codireccion_inmueble_1 = $CorTmpDetallePatrimonial->direccion_inmueble_1;
            $Codireccion_inmueble_2 = $CorTmpDetallePatrimonial->direccion_inmueble_2;
           
            
            $Cosoltero='';
            $Cocasado='';
            if($rTmpCo->estadoCivil=='Soltero' || $rTmpCo->estadoCivil=='Divorciado'){
                $Cosoltero = 'X';
            }else if($rTmpCo->estadoCivil=='Casado' || $rTmpCo->estadoCivil=='Viudo'){
                $Cocasado='X';
            }
            $CostrQueryPatrimonial = "SELECT * FROM infoPatrimonialCo 
                            WHERE idCliente in ({$codeudorPdf})";

            //echo $strQuery;
            $CoqTmpPatrimonial = $conn ->db_query($CostrQueryPatrimonial);
            $CorTmpPatrimonial = $conn->db_fetch_object($CoqTmpPatrimonial);

            $CostrQueryIngresosEgresos = " SELECT * FROM detalleIngresosDescuentosMensualesCo
            WHERE  idCliente = {$codeudorPdf}";
        
            //echo $strQuery;
            $CoqTmpIngresosEgresos = $conn ->db_query($CostrQueryIngresosEgresos);
            $CorTmpIngresosEgresos = $conn->db_fetch_object($CoqTmpIngresosEgresos);
            
            $CostrQueryDetalleComisiones = " SELECT * FROM detalleComisionesCo
            WHERE  idCliente = {$codeudorPdf}";
        
            //echo $strQuery;
            $CoqTmpDetalleComisiones = $conn ->db_query($CostrQueryDetalleComisiones);
            $CorTmpDetalleComisiones = $conn->db_fetch_object($CoqTmpDetalleComisiones);
            $Coarr = array();
            for($x=1;$x<=6;$x++){
                $Coarr[] = nombreMes(date("m",strtotime($CorTmpDetalleComisiones->fechaCreacion."- ".$x." month")));
            }
            $CostrQueryHistorialLaboral = " SELECT * FROM historialLaboralCo
            WHERE  idCliente = {$codeudorPdf}";
        
            //echo $strQuery;
            $CoqTmpHistorialLaboral = $conn ->db_query($CostrQueryHistorialLaboral);
            $CorTmpHistorialLaboral = $conn->db_fetch_object($CoqTmpHistorialLaboral);
        
            $CostrQueryRefFamiliar = " SELECT * FROM refFamiliarCo
            WHERE  idCliente = {$codeudorPdf}";
        
            //echo $strQuery;
            $CoqTmpRefFamiliar = $conn ->db_query($CostrQueryRefFamiliar);
            $CorTmpRefFamiliar = $conn->db_fetch_object($CoqTmpRefFamiliar);
        
            $CostrQueryRefBancarias = " SELECT * FROM refbancariasCo
            WHERE  idCliente = {$codeudorPdf}";
        
            //echo $strQuery;
            $CoqTmpRefBancarias = $conn ->db_query($CostrQueryRefBancarias);
            $CorTmpRefBancarias = $conn->db_fetch_object($CoqTmpRefBancarias);
        
            $CostrQueryRefCrediticias = " SELECT * FROM refCrediticiasCo
            WHERE  idCliente = {$codeudorPdf}";
        
            //echo $strQuery;
            $CoqTmpRefCrediticias = $conn ->db_query($CostrQueryRefCrediticias);
            $CorTmpRefCrediticias = $conn->db_fetch_object($CoqTmpRefCrediticias);

            $texto_rCo = '
            <div style="page-break-after: always;">
            </div>
            '.$fondo.'
            <div style="position:absolute;top:0.60in;left:0.55in;">
                <img style="width:1.67in;height:0.74in" src="'.fcnBase64($logo).'" />
            </div>

            <div style="position:absolute;top:0.46in;left:6.06in;width:2.49in;height:0.84in">
                '.$fondo_apartamento.'
            </div>
            <div style="position:absolute;top:0.73in;left:6.27in;width:1.43in;line-height:0.21in;">
                <span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:SUBAAC+Effra;color:'.$color.'">Apartamento</span>
            </div>
            <div style="position:absolute;top:1.06in;left:6.33in;width:1.43in;line-height:0.20in;">
                <span style="font-style:normal;font-weight:bold;font-size:16pt;font-family:SUBAAC+Effra;color:'.$color_depto.'">'.$rTmp->apartamento.'</span>
            </div>
            <div style="position:absolute;top:1.64in;left:0.55in;width:7.91in;line-height:0.14in;">
                <span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:SUBAAC+Effra;color:#002060">Información del Cliente:</span>
            </div>
            <div style="position:absolute;top:1.64in;left:2.30in;width:7.91in;line-height:0.14in;">
                '.$rTmpCo->client_name.'
            </div>
            <div style="position:absolute;top:1.64in;left:2.30in;width:7.91in;line-height:0.14in;">
                    <span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#002060">____________________________________________________________________________________________________</span>
                </div>
                <div style="position:absolute;top:1.94in;left:0.55in;width:2.74in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">¡Tu nuevo hogar te está esperando!</span>
                </div>
                <div style="position:absolute;top:2.98in;left:0.52in;">
                    <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
                </div>
                <div style="position:absolute;top:2.80in;left:0.55in;width:2.45in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">Información General del Cliente</span>
                </div>
                <div style="position:absolute;top:3.53in;left:0.52in;width:2.15in">
                    '.$rTmpCo->profesion.'
                </div>
                <div style="position:absolute;top:3.73in;left:0.52in;">
                    <img style="width:2.15in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_2.png').'" />
                </div>
                <div style="position:absolute;top:3.20in;left:0.55in;width:0.77in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Profesión</span>
                </div>
                <div style="position:absolute;top:3.53in;left:3.20in;width:2.00in;">
                    '.$rTmpCo->estatura.'cm.
                </div>
                <div style="position:absolute;top:3.73in;left:3.20in;">
                    <img style="width:2.15in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_2.png').'" />
                </div>
                <div style="position:absolute;top:3.20in;left:3.23in;width:0.66in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Estatura</span>
                </div>
                <div style="position:absolute;top:3.53in;left:5.89in;width:2.00in;">
                '.$rTmpCo->peso.'lbs.
            </div>
                <div style="position:absolute;top:3.73in;left:5.89in;">
                    <img style="width:2.15in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_2.png').'" />
                </div>
                <div style="position:absolute;top:3.20in;left:5.92in;width:0.41in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Peso</span>
                </div>
                <div style="position:absolute;top:4.04in;left:0.55in;width:2.47in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">Información del Núcleo Familiar</span>
                </div>
                <div style="position:absolute;top:4.22in;left:0.52in;">
                    <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
                </div>
                <div style="position:absolute;top:4.41in;left:0.55in;width:0.92in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Estado Civil</span>
                </div>

                <div style="position:absolute;top:4.42in;left:4.30in;width:3.96in;line-height:0.14in;">
                    <span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#162846">¿Cuántas Personas dependen económicamente de usted?</span>
                </div>
                <div style="position:absolute;top:4.71in;left:4.27in;">
                    '.$rTmpCo->noDependientes.'
                </div>
                <div style="position:absolute;top:4.91in;left:4.27in;">
                    <img style="width:3.76in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_20.png').'" />
                </div>
                <div style="position:absolute;top:4.72in;left:0.55in;width:0.59in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Soltero</span>
                </div>
                <div style="position:absolute;top:4.68in;left:1.37in;">
                    '.$soltero.'
                </div>
                <div style="position:absolute;top:4.68in;left:1.27in;">
                    <img style="width:0.25in;height:0.18in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/ri_6.png').'" />
                </div>
                <div style="position:absolute;top:4.98in;left:0.55in;width:0.60in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Casado</span>
                </div>
                <div style="position:absolute;top:4.94in;left:1.37in;">
                    '.$Cocasado.'
                </div>
                <div style="position:absolute;top:4.94in;left:1.27in;">
                    <img style="width:0.25in;height:0.18in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/ri_6.png').'" />
                </div>
                <div style="position:absolute;top:5.18in;left:1.63in;width:2.23in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">desde ______ /_______ /________</span>
                </div>
                <div style="position:absolute;top:5.47in;left:0.55in;width:6.18in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">Información Patrimonial y Presupuestaria (Incluye únicamente Codeudor)</span>
                </div>
                <div style="position:absolute;top:5.65in;left:0.52in;">
                    <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
                </div>
                <div style="position:absolute;top:5.86in;left:0.55in;width:0.53in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Activo</span>
                </div>
                <div style="position:absolute;top:5.86in;left:4.31in;width:0.53in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Pasivo</span>
                </div>
                <div style="position:absolute;top:6.13in;left:0.67in;width:0.37in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Caja</span>
                </div>
                <div style="position:absolute;top:6.12in;left:2.13in;">
                    Q.'.number_format($CorTmpPatrimonial->caja,2,".",",").'
                </div>
                <div style="position:absolute;top:6.32in;left:2.13in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:6.13in;left:4.42in;width:2.06in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Ctas por Pagar corto plazo</span>
                </div>
                <div style="position:absolute;top:6.12in;left:6.96in;">
                Q.'.number_format($CorTmpPatrimonial->cuentas_pagar_corto_plazo,2,".",",").'
                </div>
                <div style="position:absolute;top:6.32in;left:6.96in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:6.41in;left:0.67in;width:0.58in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Bancos</span>
                </div>
                <div style="position:absolute;top:6.40in;left:2.13in;">
                Q.'.number_format($CorTmpPatrimonial->bancos,2,".",",").'
            </div>
                <div style="position:absolute;top:6.60in;left:2.13in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:6.41in;left:4.42in;width:2.05in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Ctas por Pagar largo plazo</span>
                </div>
                <div style="position:absolute;top:6.40in;left:6.96in;">
                Q.'.number_format($CorTmpPatrimonial->cuentas_pagar_largo_plazo,2,".",",").'
                </div>
                <div style="position:absolute;top:6.60in;left:6.96in;">
                <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
            </div>
                <div style="position:absolute;top:6.70in;left:0.67in;width:1.24in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Ctas por Cobrar</span>
                </div>
                <div style="position:absolute;top:6.69in;left:2.13in;">
                Q.'.number_format($CorTmpPatrimonial->cuentas_cobrar,2,".",",").'
            </div>
                <div style="position:absolute;top:6.89in;left:2.13in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:6.70in;left:4.42in;width:1.84in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Prestamos Hipotecarios</span>
                </div>
                <div style="position:absolute;top:6.69in;left:6.96in;">
                Q.'.number_format($CorTmpPatrimonial->prestamos_hipotecarios,2,".",",").'
            </div>
                <div style="position:absolute;top:6.89in;left:6.96in;">
                <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
            </div>
                <div style="position:absolute;top:6.98in;left:0.67in;width:0.71in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Terrenos</span>
                </div>
                <div style="position:absolute;top:6.97in;left:2.13in;">
                Q.'.number_format($CorTmpPatrimonial->terrenos,2,".",",").'
            </div>
                <div style="position:absolute;top:7.17in;left:2.13in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:6.98in;left:4.31in;width:1.40in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Gastos mensuales</span>
                </div>
                <div style="position:absolute;top:7.27in;left:0.67in;width:0.77in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Viviendas</span>
                </div>
                <div style="position:absolute;top:7.26in;left:2.13in;">
                Q.'.number_format($CorTmpPatrimonial->viviendas,2,".",",").'
            </div>
                <div style="position:absolute;top:7.46in;left:2.13in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:7.27in;left:4.42in;width:1.93in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Sostenimiento del Hogar</span>
                </div>
                <div style="position:absolute;top:7.26in;left:6.96in;">
                Q.'.number_format($CorTmpPatrimonial->sostenimiento_hogar,2,".",",").'
            </div>
                <div style="position:absolute;top:7.46in;left:6.96in;">
                <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
            </div>
                <div style="position:absolute;top:7.56in;left:0.67in;width:0.78in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Vehículos</span>
                </div>
                <div style="position:absolute;top:7.55in;left:2.13in;">
                Q.'.number_format($CorTmpPatrimonial->vehiculos,2,".",",").'
            </div>
                <div style="position:absolute;top:7.75in;left:2.13in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:7.56in;left:4.42in;width:0.64in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Alquiler</span>
                </div>
                <div style="position:absolute;top:7.55in;left:6.96in;">
                Q.'.number_format($CorTmpPatrimonial->alquiler,2,".",",").'
            </div>
                <div style="position:absolute;top:7.75in;left:6.96in;">
                <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
            </div>
                <div style="position:absolute;top:7.84in;left:0.67in;width:0.90in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Inversiones</span>
                </div>
                <div style="position:absolute;top:7.83in;left:2.13in;">
                Q.'.number_format($CorTmpPatrimonial->inversiones,2,".",",").'
            </div>
                <div style="position:absolute;top:8.03in;left:2.13in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:7.84in;left:4.42in;width:0.83in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Préstamos</span>
                </div>
                <div style="position:absolute;top:7.83in;left:6.96in;">
                Q.'.number_format($CorTmpPatrimonial->prestamos,2,".",",").'
            </div>
                <div style="position:absolute;top:8.03in;left:6.96in;">
                <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
            </div>
                <div style="position:absolute;top:8.13in;left:0.67in;width:0.52in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Bonos</span>
                </div>
                <div style="position:absolute;top:8.12in;left:2.13in;">
                Q.'.number_format($CorTmpPatrimonial->bonos,2,".",",").'
            </div>
                <div style="position:absolute;top:8.32in;left:2.13in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:8.13in;left:4.42in;width:0.83in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Impuestos</span>
                </div>
                <div style="position:absolute;top:8.12in;left:6.96in;">
                Q.'.number_format($CorTmpPatrimonial->impuestos,2,".",",").'
            </div>
                <div style="position:absolute;top:8.32in;left:6.96in;">
                <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
            </div>
                <div style="position:absolute;top:8.41in;left:0.67in;width:0.72in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Acciones</span>
                </div>
                <div style="position:absolute;top:8.40in;left:2.13in;">
                Q.'.number_format($CorTmpPatrimonial->acciones,2,".",",").'
            </div>
                <div style="position:absolute;top:8.60in;left:2.13in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:8.41in;left:4.42in;width:1.87in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Extrafinanciamientos TC</span>
                </div>
                <div style="position:absolute;top:8.40in;left:6.96in;">
                Q.'.number_format($CorTmpPatrimonial->extrafinanciamientos,2,".",",").'
            </div>
                <div style="position:absolute;top:8.60in;left:6.96in;">
                <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
            </div>
                <div style="position:absolute;top:8.70in;left:0.67in;width:0.69in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Muebles</span>
                </div>
                <div style="position:absolute;top:8.69in;left:2.13in;">
                Q.'.number_format($CorTmpPatrimonial->muebles,2,".",",").'
            </div>
                <div style="position:absolute;top:8.89in;left:2.13in;">
                    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
                </div>
                <div style="position:absolute;top:8.70in;left:4.42in;width:1.54in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Deudas Particulares</span>
                </div>
                <div style="position:absolute;top:8.69in;left:6.96in;">
                Q.'.number_format($CorTmpPatrimonial->deudas_particulares,2,".",",").'
            </div>
                <div style="position:absolute;top:8.89in;left:6.96in;">
                <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
            </div>
                <div style="position:absolute;top:8.98in;left:0.55in;width:5.94in;line-height:0.13in;">
                    <span style="font-style:italic;font-weight:normal;font-size:9pt;font-family:YuGothicUISemibold;color:#162846">*en caso de aplicar con otro solicitante, llenar este apartado en conjunto en un solo formulario.</span>
                </div>
                <div style="position:absolute;top:9.30in;left:0.55in;width:1.49in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">Detalle Patrimonial</span>
                </div>
                <div style="position:absolute;top:9.59in;left:0.52in;">
                    <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
                </div>

                <div style="position:absolute;top:9.69in;left:0.55in;width:1.37in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Bienes Inmuebles</span>
                </div>
                <div style="position:absolute;top:9.93in;left:0.67in;width:1.80in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección del Inmueble</span>
                </div>
                <div style="position:absolute;top:9.92in;left:2.50in;width:6.05in;font-size:8pt">
                    <span style="font-size:10pt;">'.$Codireccion_inmueble_1.'</span>
                </div>
                <div style="position:absolute;top:10.12in;left:2.50in;">
                    <img style="width:6.05in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>

                <div style="position:absolute;top:10.24in;left:0.67in;width:0.45in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Finca</span>
                </div>
                <div style="position:absolute;top:10.23in;left:1.12in;width:1.00in;">
                    '.$CorTmpDetallePatrimonial->finca_1.'
                </div>
                <div style="position:absolute;top:10.43in;left:1.12in;">
                    <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:10.24in;left:2.22in;width:0.45in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Folio</span>
                </div>
                <div style="position:absolute;top:10.23in;left:2.67in;width:1.00in;">
                    '.$CorTmpDetallePatrimonial->folio_1.'
                </div>
                <div style="position:absolute;top:10.43in;left:2.67in;">
                    <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:10.24in;left:3.77in;width:0.45in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Libro</span>
                </div>
                <div style="position:absolute;top:10.23in;left:4.22in;width:1.00in;">
                    '.$CorTmpDetallePatrimonial->libro_1.'
                </div>
                <div style="position:absolute;top:10.43in;left:4.22in;">
                    <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:10.24in;left:5.32in;width:1.05in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Departamento</span>
                </div>
                <div style="position:absolute;top:10.23in;left:6.42in;width:2.15in;">
                '.$CorTmpDetallePatrimonial->departamento_nombre_1.'
                </div>
                <div style="position:absolute;top:10.43in;left:6.42in;">
                    <img style="width:2.15in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>

                <div style="position:absolute;top:10.63in;left:0.67in;width:1.80in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección del Inmueble</span>
                </div>
                <div style="position:absolute;top:10.62in;left:2.50in;width:6.05in;font-size:8pt">
                    <span style="font-size:10pt;">'.$Codireccion_inmueble_2.'</span>
                </div>
                <div style="position:absolute;top:10.82in;left:2.50in;">
                    <img style="width:6.05in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:10.94in;left:0.67in;width:0.45in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Finca</span>
                </div>
                <div style="position:absolute;top:10.93in;left:1.12in;width:1.00in;">
                    '.$CorTmpDetallePatrimonial->finca_2.'
                </div>
                <div style="position:absolute;top:11.13in;left:1.12in;">
                    <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:10.94in;left:2.22in;width:0.45in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Folio</span>
                </div>
                <div style="position:absolute;top:10.93in;left:2.67in;width:1.00in;">
                    '.$CorTmpDetallePatrimonial->folio_2.'
                </div>
                <div style="position:absolute;top:11.13in;left:2.67in;">
                    <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:10.94in;left:3.77in;width:0.45in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Libro</span>
                </div>
                <div style="position:absolute;top:10.93in;left:4.22in;width:1.00in;">
                    '.$CorTmpDetallePatrimonial->libro_2.'
                </div>
                <div style="position:absolute;top:11.13in;left:4.22in;">
                    <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:10.94in;left:5.32in;width:1.05in;line-height:0.16in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Departamento</span>
                </div>
                <div style="position:absolute;top:10.93in;left:6.42in;width:2.15in;">
                '.$CorTmpDetallePatrimonial->departamento_nombre_2.'
                </div>
                <div style="position:absolute;top:11.13in;left:6.42in;">
                    <img style="width:2.15in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
            <div style="position:absolute;top:2.15in;left:0.55in;width:7.89in;line-height:0.15in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#142746">Recibe un cordial saludo de parte del equipo de Avalia Desarrollos, desarrolladores de NAOS Apartamentos.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#142746"> </span><br/></SPAN></div>
            <div style="position:absolute;top:2.35in;left:0.55in;width:7.66in;line-height:0.15in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#142746">A continuación, solicitamos información complementaria para iniciar la gestión de tu crédito hipotecario.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#142746"> </span><br/></SPAN></div>
            <div style="position:absolute;top:11.58in;left:0.00in;">
                <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
            </div>
            <div style="position:absolute;top:11.65in;left:0.38in;">
                <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
            </div>
            
            
            

            <div style="page-break-after: always;">
            </div>
            '.$fondo.'
            <div style="position:absolute;top:0.60in;left:0.55in;">
                <img style="width:1.67in;height:0.74in" src="'.fcnBase64($logo).'" />
            </div>
            <div style="position:absolute;top:0.46in;left:6.06in;width:2.49in;height:0.84in">
            '.$fondo_apartamento.'
            </div>
            <div style="position:absolute;top:0.73in;left:6.27in;width:1.43in;line-height:0.21in;">
                <span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:SUBAAC+Effra;color:'.$color.'">Apartamento</span>
            </div>
            <div style="position:absolute;top:1.06in;left:6.33in;width:1.43in;line-height:0.20in;">
                <span style="font-style:normal;font-weight:bold;font-size:16pt;font-family:SUBAAC+Effra;color:'.$color_depto.'">'.$rTmp->apartamento.'</span>
            </div>

            <div style="position:absolute;top:1.64in;left:0.55in;width:0.78in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Vehículos</span>
            </div>

            <div style="position:absolute;top:1.88in;left:0.67in;width:0.60in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Marca:</span>
            </div>
            <div style="position:absolute;top:1.87in;left:1.20in;width:1.20in;">
                '.$CorTmpDetallePatrimonial->marca_1.'
            </div>
            <div style="position:absolute;top:2.07in;left:1.20in;">
                <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:1.88in;left:2.45in;width:0.60in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo:</span>
            </div>
            <div style="position:absolute;top:1.87in;left:2.83in;width:1.20in;">
                '.$CorTmpDetallePatrimonial->tipo_vehiculo_1.'
            </div>
            <div style="position:absolute;top:2.07in;left:2.83in;">
                <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:1.88in;left:4.10in;width:0.60in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Modelo:</span>
            </div>
            <div style="position:absolute;top:1.87in;left:4.70in;width:1.20in;">
                '.$CorTmpDetallePatrimonial->modelo_vehiculo_1.'
            </div>
            <div style="position:absolute;top:2.07in;left:4.70in;">
                <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:1.88in;left:5.95in;width:1.50in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Valor estimado:</span>
            </div>
            <div style="position:absolute;top:1.87in;left:7.10in;width:1.20in;">
                Q.'.number_format($CorTmpDetallePatrimonial->valor_estimado_1,2,".",",").'
            </div>
            <div style="position:absolute;top:2.07in;left:7.10in;">
                <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>

            <div style="position:absolute;top:2.18in;left:0.67in;width:0.60in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Marca:</span>
            </div>
            <div style="position:absolute;top:2.17in;left:1.20in;width:1.20in;">
                '.$CorTmpDetallePatrimonial->marca_2.'
            </div>
            <div style="position:absolute;top:2.37in;left:1.20in;">
                <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:2.18in;left:2.45in;width:0.60in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo:</span>
            </div>
            <div style="position:absolute;top:2.17in;left:2.83in;width:1.20in;">
                '.$CorTmpDetallePatrimonial->tipo_vehiculo_2.'
            </div>
            <div style="position:absolute;top:2.37in;left:2.83in;">
                <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:2.18in;left:4.10in;width:0.60in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Modelo:</span>
            </div>
            <div style="position:absolute;top:2.17in;left:4.70in;width:1.20in;">
                '.$CorTmpDetallePatrimonial->modelo_vehiculo_2.'
            </div>
            <div style="position:absolute;top:2.37in;left:4.70in;">
                <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:2.18in;left:5.95in;width:1.50in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Valor estimado:</span>
            </div>
            <div style="position:absolute;top:2.17in;left:7.10in;width:1.20in;">
                Q.'.number_format($CorTmpDetallePatrimonial->valor_estimado_2,2,".",",").'
            </div>
            <div style="position:absolute;top:2.37in;left:7.10in;">
                <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:2.75in;left:0.52in;">
                <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
            </div>
            <div style="position:absolute;top:2.57in;left:0.55in;width:6.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">Información de Ingresos en Relación de Dependencia (si aplica)</span>
            </div>
            <div style="position:absolute;top:2.93in;left:2.65in;">
                '.$rTmpCo->empresaLabora.'
            </div>
            <div style="position:absolute;top:3.13in;left:2.65in;">
                <img style="width:3.23in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:2.93in;left:0.67in;width:1.90in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Sociedad a la que labora:</span>
            </div>
            <div style="position:absolute;top:2.93in;left:6.96in;">
                '.$rTmpCo->telefonoReferencia.'
            </div>
            <div style="position:absolute;top:3.13in;left:6.96in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:2.93in;left:6.25in;width:1.90in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Teléfono:</span>
            </div>
            <div style="position:absolute;top:3.33in;left:0.67in;width:1.33in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo de Contrato:</span>
            </div>
            <div style="position:absolute;top:3.31in;left:2.75in;">
                X
            </div>
            <div style="position:absolute;top:3.33in;left:2.70in;">
                <img style="width:0.25in;height:0.18in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/ri_6.png').'" />
            </div>
            <div style="position:absolute;top:3.33in;left:3.00in;width:1.14in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Indefinido</span>
            </div>
            <div style="position:absolute;top:3.33in;left:4.32in;">
                <img style="width:0.25in;height:0.18in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/ri_6.png').'" />
            </div>
            <div style="position:absolute;top:3.33in;left:4.62in;width:3.14in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Vigencia definida vence: ____/____/____</span>
            </div>
            <div style="position:absolute;top:3.70in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Detalle de Ingresos Mensuales:</span>
            </div>
            <div style="position:absolute;top:3.95in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Salario Nominal:</span>
            </div>
            <div style="position:absolute;top:4.15in;left:2.67in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:3.95in;left:2.67in;">
                Q.'.number_format($CorTmpIngresosEgresos->salario_nominal,2,".",",").'
            </div>
            <div style="position:absolute;top:4.20in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Bono 14 (1/12):</span>
            </div>
            <div style="position:absolute;top:4.40in;left:2.67in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:4.20in;left:2.67in;">
                Q.'.number_format($CorTmpIngresosEgresos->bono_catorce,2,".",",").'
            </div>
            <div style="position:absolute;top:4.45in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Aguinaldo (172):</span>
            </div>
            <div style="position:absolute;top:4.65in;left:2.67in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:4.45in;left:2.67in;">
                Q.'.number_format($CorTmpIngresosEgresos->aguinaldo,2,".",",").'
            </div>
            <div style="position:absolute;top:4.70in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Honorarios:</span>
            </div>
            <div style="position:absolute;top:4.90in;left:2.67in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:4.70in;left:2.67in;">
                Q.'.number_format($CorTmpIngresosEgresos->honorarios,2,".",",").'
            </div>
            <div style="position:absolute;top:4.95in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Otros:</span>
            </div>
            <div style="position:absolute;top:5.15in;left:2.67in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:4.95in;left:2.67in;">
                Q.'.number_format($CorTmpIngresosEgresos->otros_ingresos_fha,2,".",",").'
            </div>

            <div style="position:absolute;top:3.70in;left:4.84in;width:2.60in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Detalle de Descuentos Mensuales:</span>
            </div>
            <div style="position:absolute;top:3.95in;left:4.84in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">IGSS:</span>
            </div>
            <div style="position:absolute;top:4.15in;left:6.84in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:3.95in;left:6.84in;">
                Q.'.number_format($CorTmpIngresosEgresos->igss,2,".",",").'
            </div>
            <div style="position:absolute;top:4.20in;left:4.84in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">ISR:</span>
            </div>
            <div style="position:absolute;top:4.40in;left:6.84in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:4.20in;left:6.84in;">
                Q.'.number_format($CorTmpIngresosEgresos->isr,2,".",",").'
            </div>
            <div style="position:absolute;top:4.45in;left:4.84in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Plan de Pensiones:</span>
            </div>
            <div style="position:absolute;top:4.65in;left:6.84in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:4.45in;left:6.84in;">
                Q.'.number_format($CorTmpIngresosEgresos->plan_pensiones,2,".",",").'
            </div>
            <div style="position:absolute;top:4.70in;left:4.84in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Judiciales:</span>
            </div>
            <div style="position:absolute;top:4.90in;left:6.84in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:4.70in;left:6.84in;">
                Q.'.number_format($CorTmpIngresosEgresos->judiciales,2,".",",").'
            </div>
            <div style="position:absolute;top:4.95in;left:4.84in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Otros:</span>
            </div>
            <div style="position:absolute;top:5.15in;left:6.84in;">
                <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:4.95in;left:6.84in;">
                Q.'.number_format($CorTmpIngresosEgresos->otros_descuentos_fha,2,".",",").'
            </div>
            <div style="position:absolute;top:5.30in;left:0.67in;width:5.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Detalle de Comisiones y Bonificaciones últimos 6 meses:</span>
            </div>
            <div style="position:absolute;top:5.60in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Mes:</span>
            </div>
            <div style="position:absolute;top:5.90in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Horas Extra:</span>
            </div>
            <div style="position:absolute;top:6.20in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Comisiones:</span>
            </div>
            <div style="position:absolute;top:6.50in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Bonificaciones:</span>
            </div>

            <div style="position:absolute;top:5.55in;left:1.70in;">
                '.$Coarr[0].'
            </div>
            <div style="position:absolute;top:5.75in;left:1.70in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:5.55in;left:2.85in;">
                '.$Coarr[1].'
            </div>
            <div style="position:absolute;top:5.75in;left:2.85in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:5.55in;left:4.00in;">
                '.$Coarr[2].'
            </div>
            <div style="position:absolute;top:5.75in;left:4.00in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:5.55in;left:5.15in;">
                '.$Coarr[3].'
            </div>
            <div style="position:absolute;top:5.75in;left:5.15in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:5.55in;left:6.30in;">
                '.$Coarr[4].'
            </div>
            <div style="position:absolute;top:5.75in;left:6.30in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:5.55in;left:7.45in;">
                '.$Coarr[5].'
            </div>
            <div style="position:absolute;top:5.75in;left:7.45in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>

            <div style="position:absolute;top:5.85in;left:1.70in;">
                Q.'.number_format($CorTmpDetalleComisiones->hora_extra_mes_1,2,".",",").'
            </div>
            <div style="position:absolute;top:6.05in;left:1.70in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:5.85in;left:2.85in;">
                Q.'.number_format($CorTmpDetalleComisiones->hora_extra_mes_2,2,".",",").'
            </div>
            <div style="position:absolute;top:6.05in;left:2.85in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:5.85in;left:4.00in;">
                Q.'.number_format($CorTmpDetalleComisiones->hora_extra_mes_3,2,".",",").'
            </div>
            <div style="position:absolute;top:6.05in;left:4.00in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:5.85in;left:5.15in;">
                Q.'.number_format($CorTmpDetalleComisiones->hora_extra_mes_4,2,".",",").'
            </div>
            <div style="position:absolute;top:6.05in;left:5.15in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:5.85in;left:6.30in;">
                Q.'.number_format($CorTmpDetalleComisiones->hora_extra_mes_5,2,".",",").'
            </div>
            <div style="position:absolute;top:6.05in;left:6.30in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:5.85in;left:7.45in;">
                Q.'.number_format($CorTmpDetalleComisiones->hora_extra_mes_6,2,".",",").'
            </div>
            <div style="position:absolute;top:6.05in;left:7.45in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>

            <div style="position:absolute;top:6.15in;left:1.70in;">
                Q.'.number_format($CorTmpDetalleComisiones->comisiones_mes_1,2,".",",").'
            </div>
            <div style="position:absolute;top:6.35in;left:1.70in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.15in;left:2.85in;">
                Q.'.number_format($CorTmpDetalleComisiones->comisiones_mes_2,2,".",",").'
            </div>
            <div style="position:absolute;top:6.35in;left:2.85in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.15in;left:4.00in;">
                Q.'.number_format($CorTmpDetalleComisiones->comisiones_mes_3,2,".",",").'
            </div>
            <div style="position:absolute;top:6.35in;left:4.00in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.15in;left:5.15in;">
                Q.'.number_format($CorTmpDetalleComisiones->comisiones_mes_4,2,".",",").'
            </div>
            <div style="position:absolute;top:6.35in;left:5.15in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.15in;left:6.30in;">
                Q.'.number_format($CorTmpDetalleComisiones->comisiones_mes_5,2,".",",").'
            </div>
            <div style="position:absolute;top:6.35in;left:6.30in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.15in;left:7.45in;">
                Q.'.number_format($CorTmpDetalleComisiones->comisiones_mes_1,2,".",",").'
            </div>
            <div style="position:absolute;top:6.35in;left:7.45in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.50in;left:1.70in;">
                Q.'.number_format($CorTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
            </div>
            <div style="position:absolute;top:6.70in;left:1.70in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.50in;left:2.85in;">
                Q.'.number_format($CorTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
            </div>
            <div style="position:absolute;top:6.70in;left:2.85in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.50in;left:4.00in;">
                Q.'.number_format($CorTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
            </div>
            <div style="position:absolute;top:6.70in;left:4.00in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.50in;left:5.15in;">
                Q.'.number_format($CorTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
            </div>
            <div style="position:absolute;top:6.70in;left:5.15in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.50in;left:6.30in;">
                Q.'.number_format($CorTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
            </div>
            <div style="position:absolute;top:6.70in;left:6.30in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:6.50in;left:7.45in;">
                Q.'.number_format($CorTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
            </div>
            <div style="position:absolute;top:6.70in;left:7.45in;">
                <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:7.00in;left:0.67in;width:5.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Historial Laboral últimos dos años</span>
            </div>
            <div style="position:absolute;top:7.20in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Empresa</span>
            </div>
            <div style="position:absolute;top:7.20in;left:3.77in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Cargo</span>
            </div>
            <div style="position:absolute;top:7.20in;left:5.92in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Desde</span>
            </div>
            <div style="position:absolute;top:7.20in;left:7.53in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Hasta</span>
            </div>';
            $Cotop = 7.70;
            $Cotop_text = 7.50;
            if($CorTmpHistorialLaboral->empresa_1!=''){
                $Cohasta_1 = $CorTmpHistorialLaboral->hasta_1 != '0000-00-00' ? date('d/m/Y',strtotime($CorTmpHistorialLaboral->hasta_1)) : 'Actual';
                $texto_rCo .='
                <div style="position:absolute;top:'.$Cotop_text.'in;left:0.67in;">
                    '.$CorTmpHistorialLaboral->empresa_1.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                    <img style="width:2.80in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:3.77in;">
                    '.$CorTmpHistorialLaboral->cargo_1.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.77in;">
                    <img style="width:1.90in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:5.92in;">
                    '.date('d/m/Y',strtotime($CorTmpHistorialLaboral->desde_1)).'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.92in;">
                    <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:07.53in;">
                    '.$Cohasta_1.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:7.53in;">
                    <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>';
                $Cotop += 0.20;
                $Cotop_text += 0.20;
            }
            if($CorTmpHistorialLaboral->empresa_2!=''){
                $Cotop += 0.10;
                $Cotop_text += 0.10;
                $Cohasta_2 = $CorTmpHistorialLaboral->hasta_2 != '0000-00-00' ? date('d/m/Y',strtotime($CorTmpHistorialLaboral->hasta_2)) : 'Actual';
                $texto_rCo .='
                <div style="position:absolute;top:'.$Cotop_text.'in;left:0.67in;">
                    '.$CorTmpHistorialLaboral->empresa_2.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                    <img style="width:2.80in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:3.77in;">
                    '.$CorTmpHistorialLaboral->cargo_2.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.77in;">
                    <img style="width:1.90in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:5.92in;">
                    '.date('d/m/Y',strtotime($CorTmpHistorialLaboral->desde_2)).'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.92in;">
                    <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:07.53in;">
                    '.$Cohasta_2.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:7.53in;">
                    <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>';
                $Cotop += 0.20;
                $Cotop_text += 0.20;
            }
            if($CorTmpHistorialLaboral->empresa_3!=''){
                $Cotop += 0.10;
                $Cotop_text += 0.10;
                $Cohasta_3 = $CorTmpHistorialLaboral->hasta_3 != '0000-00-00' ? date('d/m/Y',strtotime($CorTmpHistorialLaboral->hasta_3)) : 'Actual';
                $texto_rCo .='
                <div style="position:absolute;top:'.$Cotop_text.'in;left:0.67in;">
                    '.$CorTmpHistorialLaboral->empresa_3.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                    <img style="width:2.80in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:3.77in;">
                    '.$CorTmpHistorialLaboral->cargo_3.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.77in;">
                    <img style="width:1.90in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:5.92in;">
                    '.date('d/m/Y',strtotime($CorTmpHistorialLaboral->desde_3)).'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.92in;">
                    <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:07.53in;">
                    '.$Cohasta_3.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:7.53in;">
                    <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>';
                $Cotop += 0.20;
                $Cotop_text += 0.20;
            }
            if($CorTmpHistorialLaboral->empresa_4!=''){
                $Cotop += 0.10;
                $Cotop_text += 0.10;
                $Cohasta_4 = $CorTmpHistorialLaboral->hasta_4 != '0000-00-00' ? date('d/m/Y',strtotime($CorTmpHistorialLaboral->hasta_4)) : 'Actual';
                $texto_rCo .='
                <div style="position:absolute;top:'.$Cotop_text.'in;left:0.67in;">
                    '.$CorTmpHistorialLaboral->empresa_4.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                    <img style="width:2.80in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:3.77in;">
                    '.$CorTmpHistorialLaboral->cargo_4.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.77in;">
                    <img style="width:1.90in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:5.92in;">
                    '.date('d/m/Y',strtotime($CorTmpHistorialLaboral->desde_4)).'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.92in;">
                    <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop_text.'in;left:07.53in;">
                    '.$Cohasta_4.'
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:7.53in;">
                    <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>';
                $Cotop += 0.20;
                $Cotop_text += 0.20;
            }
            $Cotop += 0.10;
            $Cotop_text += 0.10;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.52in;">
                <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop_text.'in;left:0.52in;width:5.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Referencias Familiares, Bancarias y Crediticias</span>
            </div>';
            $Cotop += 0.10;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:4.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Ref. Familiar 1</span>
            </div>
            ';
            $Cotop += 0.30;
            $Cotop_linea = $Cotop + 0.20;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Nombre:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:1.40in;width:5.00in;">
                '.$CorTmpRefFamiliar->nombre_referencia_1.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:1.40in;">
                <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:6.30in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Parentesco:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:7.15in;width:5.00in;">
                '.$CorTmpRefFamiliar->parentesco_referencia_1.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:7.15in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            ';
            $Cotop += 0.30;
            $Cotop_linea = $Cotop + 0.20;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:1.40in;width:5.00in;">
                '.$CorTmpRefFamiliar->domicilio_1.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:1.40in;">
                <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:6.30in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Teléfono:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:7.15in;width:5.00in;">
                '.$CorTmpRefFamiliar->telefono_1.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:7.15in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            ';
            $Cotop += 0.35;
            $Cotop_linea = $Cotop + 0.20;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Trabajo:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:1.40in;width:5.00in;">
                '.$CorTmpRefFamiliar->trabajo_1.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:1.40in;">
                <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:6.30in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Teléfono:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:7.15in;width:5.00in;">
                '.$CorTmpRefFamiliar->trabajo_telefono_1.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:7.15in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            ';
            $Cotop += 0.30;
            $Cotop_linea = $Cotop + 0.20;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:1.40in;width:5.00in;">
                '.$CorTmpRefFamiliar->trabajo_direccion_1.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:1.40in;">
                <img style="width:7.30in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            ';

            $Cotop += 0.40;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:4.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Ref. Familiar 2</span>
            </div>
            ';
            $Cotop += 0.30;
            $Cotop_linea = $Cotop + 0.20;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Nombre:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:1.40in;width:5.00in;">
                '.$CorTmpRefFamiliar->nombre_referencia_2.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:1.40in;">
                <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:6.30in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Parentesco:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:7.15in;width:5.00in;">
                '.$CorTmpRefFamiliar->parentesco_referencia_2.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:7.15in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            ';
            $Cotop += 0.30;
            $Cotop_linea = $Cotop + 0.20;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:1.40in;width:5.00in;">
                '.$CorTmpRefFamiliar->domicilio_2.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:1.40in;">
                <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:6.30in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Teléfono:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:7.15in;width:5.00in;">
                '.$CorTmpRefFamiliar->telefono_2.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:7.15in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            ';
            $Cotop += 0.35;
            $Cotop_linea = $Cotop + 0.20;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Trabajo:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:1.40in;width:5.00in;">
                '.$CorTmpRefFamiliar->trabajo_2.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:1.40in;">
                <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:6.30in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Teléfono:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:7.15in;width:5.00in;">
                '.$CorTmpRefFamiliar->trabajo_telefono_2.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:7.15in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            ';
            $Cotop += 0.30;
            $Cotop_linea = $Cotop + 0.20;
            $texto_rCo .='
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.00in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección:</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:1.40in;width:5.00in;">
                '.$CorTmpRefFamiliar->trabajo_direccion_2.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:1.40in;">
                <img style="width:7.30in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            ';
            $texto_rCo .='<div style="position:absolute;top:11.58in;left:0.00in;">
                <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
            </div>
            <div style="position:absolute;top:11.65in;left:0.38in;">
                <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
            </div>   
            <div style="page-break-after: always;">
            </div>
            '.$fondo.'
            <div style="position:absolute;top:0.60in;left:0.55in;">
                <img style="width:1.67in;height:0.74in" src="'.fcnBase64($logo).'" />
            </div>
            <div style="position:absolute;top:0.46in;left:6.06in;width:2.49in;height:0.84in">
            '.$fondo_apartamento.'
            </div>
            <div style="position:absolute;top:0.73in;left:6.27in;width:1.43in;line-height:0.21in;">
                <span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:SUBAAC+Effra;color:'.$color.'">Apartamento</span>
            </div>
            <div style="position:absolute;top:1.06in;left:6.33in;width:1.43in;line-height:0.20in;">
                <span style="font-style:normal;font-weight:bold;font-size:16pt;font-family:SUBAAC+Effra;color:'.$color_depto.'">'.$rTmp->apartamento.'</span>
            </div>

            <div style="position:absolute;top:1.64in;left:0.55in;width:4.78in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Referencias Bancarías</span>
            </div>
            <div style="position:absolute;top:1.85in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Banco</span>
            </div>
            <div style="position:absolute;top:2.05in;left:0.67in;">
                '.$CorTmpRefBancarias->banco_1.'
            </div>
            <div style="position:absolute;top:2.25in;left:0.67in;">
                <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:1.85in;left:3.50in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo de Cuenta</span>
            </div>
            <div style="position:absolute;top:2.05in;left:3.50in;">
                '.$CorTmpRefBancarias->tipo_cuenta_1.'
            </div>
            <div style="position:absolute;top:2.25in;left:3.50in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:1.85in;left:5.25in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">No. de Cuenta</span>
            </div>
            <div style="position:absolute;top:2.05in;left:5.25in;">
                '.$CorTmpRefBancarias->no_cuenta_1.'
            </div>
            <div style="position:absolute;top:2.25in;left:5.25in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:1.85in;left:7.00in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Saldo Actual</span>
            </div>
            <div style="position:absolute;top:2.05in;left:7.00in;">
                Q.'.number_format($CorTmpRefBancarias->saldo_actual_1,2,".",",").'
            </div>
            <div style="position:absolute;top:2.25in;left:7.00in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>';
            $Cotop = 2.35;
            $Cotop_linea = 2.55;
            if($CorTmpRefBancarias->banco_2!=''){
                $texto_rCo .= '
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                    '.$CorTmpRefBancarias->banco_2.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:0.67in;">
                    <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;">
                    '.$CorTmpRefBancarias->tipo_cuenta_2.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:3.50in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;">
                    '.$CorTmpRefBancarias->no_cuenta_2.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:5.25in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:7.00in;">
                    Q.'.number_format($CorTmpRefBancarias->saldo_actual_2,2,".",",").'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:7.00in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>';
                $Cotop += 0.30;
                $Cotop_linea += 0.30;
            }
            $Cotop += 0.10;
            $texto_rCo .= '<div style="position:absolute;top:'.$Cotop.'in;left:0.55in;width:4.78in;line-height:0.16in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Referencias Crediticias</span>
            </div>';
            $Cotop += 0.20;
            $texto_rCo .= '
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Banco</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo de Préstamo</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">No. de Préstamo</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:7.00in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Monto</span>
            </div>
            ';
            $Cotop += 0.20;
            $Cotop_linea = $Cotop + 0.20;
            $texto_rCo .= '
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                '.$CorTmpRefCrediticias->banco_prestamo_1.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:0.67in;">
                <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;">
                '.$CorTmpRefCrediticias->tipo_prestamo_1.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:3.50in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;">
                '.$CorTmpRefCrediticias->no_prestamo_1.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:5.25in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:7.00in;">
                Q.'.number_format($CorTmpRefCrediticias->monto_1,2,".",",").'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:7.00in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>';
            $Cotop += 0.30;
            $texto_rCo .= '
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Saldo Actual</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Pago Mensual</span>
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;width:2.35in;">
                <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Fecha Vencimiento</span>
            </div>
            ';
            $Cotop += 0.20;
            $Cotop_linea = $Cotop + 0.20;
            $Cofecha_vencimiento = $CorTmpRefCrediticias->fecha_vencimiento_prestamo_1 != '0000-00-00' ? date('d/m/Y',strtotime($CorTmpRefCrediticias->fecha_vencimiento_prestamo_1)) : '';
            $texto_rCo .= '
            <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                Q.'.number_format($CorTmpRefCrediticias->saldo_actual_prestamo_1,2,".",",").'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:0.67in;">
                <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;">
                Q.'.number_format($CorTmpRefCrediticias->pago_mensual_prestamo_1,2,".",",").'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:3.50in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>
            <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;">
                '.$Cofecha_vencimiento.'
            </div>
            <div style="position:absolute;top:'.$Cotop_linea.'in;left:5.25in;">
                <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
            </div>';

            if($CorTmpRefCrediticias->banco_prestamo_2!=''){
                $Cotop += 0.30;
                $texto_rCo .= '
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Banco</span>
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo de Préstamo</span>
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">No. de Préstamo</span>
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:7.00in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Monto</span>
                </div>
                ';
                $Cotop += 0.20;
                $Cotop_linea = $Cotop + 0.20;
                $texto_rCo .= '
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                    '.$CorTmpRefCrediticias->banco_prestamo_2.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:0.67in;">
                    <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;">
                    '.$CorTmpRefCrediticias->tipo_prestamo_2.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:3.50in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;">
                    '.$CorTmpRefCrediticias->no_prestamo_2.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:5.25in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:7.00in;">
                    Q.'.number_format($CorTmpRefCrediticias->monto_2,2,".",",").'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:7.00in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>';
                $Cotop += 0.30;
                $texto_rCo .= '
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Saldo Actual</span>
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Pago Mensual</span>
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Fecha Vencimiento</span>
                </div>
                ';
                $Cotop += 0.20;
                $Cotop_linea = $Cotop + 0.20;
                $Cofecha_vencimiento_2 = $CorTmpRefCrediticias->fecha_vencimiento_prestamo_2 != '0000-00-00' ? date('d/m/Y',strtotime($CorTmpRefCrediticias->fecha_vencimiento_prestamo_2)) : '';
                $texto_rCo .= '
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                    Q.'.number_format($CorTmpRefCrediticias->saldo_actual_prestamo_2,2,".",",").'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:0.67in;">
                    <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;">
                    Q.'.number_format($CorTmpRefCrediticias->pago_mensual_prestamo_2,2,".",",").'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:3.50in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;">
                    '.$Cofecha_vencimiento_2.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:5.25in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>';
            }
            if($CorTmpRefCrediticias->banco_prestamo_3!=''){
                $Cotop += 0.30;
                $texto_rCo .= '
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Banco</span>
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo de Préstamo</span>
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">No. de Préstamo</span>
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:7.00in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Monto</span>
                </div>
                ';
                $Cotop += 0.20;
                $Cotop_linea = $Cotop + 0.20;
                $texto_rCo .= '
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                    '.$CorTmpRefCrediticias->banco_prestamo_3.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:0.67in;">
                    <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;">
                    '.$CorTmpRefCrediticias->tipo_prestamo_3.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:3.50in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;">
                    '.$CorTmpRefCrediticias->no_prestamo_3.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:5.25in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:7.00in;">
                    Q.'.number_format($CorTmpRefCrediticias->monto_3,2,".",",").'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:7.00in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>';
                $Cotop += 0.30;
                $texto_rCo .= '
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Saldo Actual</span>
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Pago Mensual</span>
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;width:2.35in;">
                    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Fecha Vencimiento</span>
                </div>
                ';
                $Cotop += 0.20;
                $Cotop_linea = $Cotop + 0.20;
                $Cofecha_vencimiento_3 = $CorTmpRefCrediticias->fecha_vencimiento_prestamo_3 != '0000-00-00' ? date('d/m/Y',strtotime($CorTmpRefCrediticias->fecha_vencimiento_prestamo_3)) : '';
                $texto_rCo .= '
                <div style="position:absolute;top:'.$Cotop.'in;left:0.67in;">
                    Q.'.number_format($CorTmpRefCrediticias->saldo_actual_prestamo_3,2,".",",").'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:0.67in;">
                    <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:3.50in;">
                    Q.'.number_format($CorTmpRefCrediticias->pago_mensual_prestamo_3,2,".",",").'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:3.50in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>
                <div style="position:absolute;top:'.$Cotop.'in;left:5.25in;">
                    '.$Cofecha_vencimiento_3.'
                </div>
                <div style="position:absolute;top:'.$Cotop_linea.'in;left:5.25in;">
                    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
                </div>

            ';
            }
            $texto_rCo .= '
            <div style="position:absolute;top:11.58in;left:0.00in;">
                <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
            </div>
            <div style="position:absolute;top:11.65in;left:0.38in;">
                <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
            </div>';

        }
    }
    
    
    
    $css_r = '
    <style type="text/css">
        table {border-collapse: collapse;}
        table td {padding: 0px} 

        .ft00 {
            font-family: SUBAAC+Effra;
        }

    </style>';

    $texto_r = '<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8";Content-Disposition:inline;filename=ResumenFha.pdf />  
    </head>
    <body>
    '.$fondo.'
    <div style="position:absolute;top:0.60in;left:0.55in;">
        <img style="width:1.67in;height:0.74in" src="'.fcnBase64($logo).'" />
    </div>
    <div style="position:absolute;top:0.46in;left:6.06in;width:2.49in;height:0.84in">
    '.$fondo_apartamento.'
    </div>

    <div style="position:absolute;top:0.73in;left:6.27in;width:1.43in;line-height:0.21in;">
        <span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:SUBAAC+Effra;color:'.$color.';">Apartamento</span>
    </div>
    <div style="position:absolute;top:1.06in;left:6.33in;width:1.43in;line-height:0.20in;">
        <span style="font-style:normal;font-weight:bold;font-size:16pt;font-family:SUBAAC+Effra;color:'.$color_depto.'">'.$rTmp->apartamento.'</span>
    </div>
    <div style="position:absolute;top:1.64in;left:0.55in;width:7.91in;line-height:0.14in;">
        <span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:SUBAAC+Effra;color:#002060">Información del Cliente:</span>
    </div>
    <div style="position:absolute;top:1.64in;left:2.30in;width:7.91in;line-height:0.14in;">
        '.$rTmp->client_name.'
    </div>';
    if($rTmp->codeudores!=''){
        $texto_r .= '
        <div style="position:absolute;top:1.80in;left:6.50in;width:7.91in;line-height:0.14in;">
            <span style="font-style:normal;font-weight:bold;font-size:10pt;font-family:SUBAAC+Effra;color:#002060">CUENTA CON CODEUDOR</span>
        </div>
        ';
    }
    $texto_r .= '
    <div style="position:absolute;top:1.64in;left:2.30in;width:7.91in;line-height:0.14in;">
        <span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#002060">____________________________________________________________________________________________________</span>
    </div>
    <div style="position:absolute;top:1.94in;left:0.55in;width:2.74in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">¡Tu nuevo hogar te está esperando!</span>
    </div>
    <div style="position:absolute;top:2.98in;left:0.52in;">
        <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
    </div>
    <div style="position:absolute;top:2.80in;left:0.55in;width:2.45in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">Información General del Cliente</span>
    </div>
    <div style="position:absolute;top:3.53in;left:0.52in;width:2.15in">
        '.$rTmp->profesion.'
    </div>
    <div style="position:absolute;top:3.73in;left:0.52in;">
        <img style="width:2.15in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_2.png').'" />
    </div>
    <div style="position:absolute;top:3.20in;left:0.55in;width:0.77in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Profesión</span>
    </div>
    <div style="position:absolute;top:3.53in;left:3.20in;width:2.00in;">
        '.$rTmp->estatura.'cm.
    </div>
    <div style="position:absolute;top:3.73in;left:3.20in;">
        <img style="width:2.15in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_2.png').'" />
    </div>
    <div style="position:absolute;top:3.20in;left:3.23in;width:0.66in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Estatura</span>
    </div>
    <div style="position:absolute;top:3.53in;left:5.89in;width:2.00in;">
    '.$rTmp->peso.'lbs.
</div>
    <div style="position:absolute;top:3.73in;left:5.89in;">
        <img style="width:2.15in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_2.png').'" />
    </div>
    <div style="position:absolute;top:3.20in;left:5.92in;width:0.41in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Peso</span>
    </div>
    <div style="position:absolute;top:4.04in;left:0.55in;width:2.47in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">Información del Núcleo Familiar</span>
    </div>
    <div style="position:absolute;top:4.22in;left:0.52in;">
        <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
    </div>
    <div style="position:absolute;top:4.41in;left:0.55in;width:0.92in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Estado Civil</span>
    </div>

    <div style="position:absolute;top:4.42in;left:4.30in;width:3.96in;line-height:0.14in;">
        <span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#162846">¿Cuántas Personas dependen económicamente de usted?</span>
    </div>
    <div style="position:absolute;top:4.71in;left:4.27in;">
        '.$rTmp->noDependientes.'
    </div>
    <div style="position:absolute;top:4.91in;left:4.27in;">
        <img style="width:3.76in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_20.png').'" />
    </div>
    <div style="position:absolute;top:4.72in;left:0.55in;width:0.59in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Soltero</span>
    </div>
    <div style="position:absolute;top:4.68in;left:1.37in;">
        '.$soltero.'
    </div>
    <div style="position:absolute;top:4.68in;left:1.27in;">
        <img style="width:0.25in;height:0.18in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/ri_6.png').'" />
    </div>
    <div style="position:absolute;top:4.98in;left:0.55in;width:0.60in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Casado</span>
    </div>
    <div style="position:absolute;top:4.94in;left:1.37in;">
        '.$casado.'
    </div>
    <div style="position:absolute;top:4.94in;left:1.27in;">
        <img style="width:0.25in;height:0.18in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/ri_6.png').'" />
    </div>
    <div style="position:absolute;top:5.18in;left:1.63in;width:2.23in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">desde ______ /_______ /________</span>
    </div>
    <div style="position:absolute;top:5.47in;left:0.55in;width:6.18in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">Información Patrimonial y Presupuestaria (Incluye Cliente y Codeudor)</span>
    </div>
    <div style="position:absolute;top:5.65in;left:0.52in;">
        <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
    </div>
    <div style="position:absolute;top:5.86in;left:0.55in;width:0.53in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Activo</span>
    </div>
    <div style="position:absolute;top:5.86in;left:4.31in;width:0.53in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Pasivo</span>
    </div>
    <div style="position:absolute;top:6.13in;left:0.67in;width:0.37in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Caja</span>
    </div>
    <div style="position:absolute;top:6.12in;left:2.13in;">
        Q.'.number_format($caja,2,".",",").'
    </div>
    <div style="position:absolute;top:6.32in;left:2.13in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:6.13in;left:4.42in;width:2.06in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Ctas por Pagar corto plazo</span>
    </div>
    <div style="position:absolute;top:6.12in;left:6.96in;">
    Q.'.number_format($cuentas_pagar_corto_plazo,2,".",",").'
    </div>
    <div style="position:absolute;top:6.32in;left:6.96in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:6.41in;left:0.67in;width:0.58in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Bancos</span>
    </div>
    <div style="position:absolute;top:6.40in;left:2.13in;">
    Q.'.number_format($bancos,2,".",",").'
</div>
    <div style="position:absolute;top:6.60in;left:2.13in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:6.41in;left:4.42in;width:2.05in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Ctas por Pagar largo plazo</span>
    </div>
    <div style="position:absolute;top:6.40in;left:6.96in;">
    Q.'.number_format($cuentas_pagar_largo_plazo,2,".",",").'
    </div>
    <div style="position:absolute;top:6.60in;left:6.96in;">
    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
</div>
    <div style="position:absolute;top:6.70in;left:0.67in;width:1.24in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Ctas por Cobrar</span>
    </div>
    <div style="position:absolute;top:6.69in;left:2.13in;">
    Q.'.number_format($cuentas_cobrar,2,".",",").'
</div>
    <div style="position:absolute;top:6.89in;left:2.13in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:6.70in;left:4.42in;width:1.84in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Prestamos Hipotecarios</span>
    </div>
    <div style="position:absolute;top:6.69in;left:6.96in;">
    Q.'.number_format($prestamos_hipotecarios,2,".",",").'
</div>
    <div style="position:absolute;top:6.89in;left:6.96in;">
    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
</div>
    <div style="position:absolute;top:6.98in;left:0.67in;width:0.71in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Terrenos</span>
    </div>
    <div style="position:absolute;top:6.97in;left:2.13in;">
    Q.'.number_format($terrenos,2,".",",").'
</div>
    <div style="position:absolute;top:7.17in;left:2.13in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:6.98in;left:4.31in;width:1.40in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Gastos mensuales</span>
    </div>
    <div style="position:absolute;top:7.27in;left:0.67in;width:0.77in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Viviendas</span>
    </div>
    <div style="position:absolute;top:7.26in;left:2.13in;">
    Q.'.number_format($viviendas,2,".",",").'
</div>
    <div style="position:absolute;top:7.46in;left:2.13in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:7.27in;left:4.42in;width:1.93in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Sostenimiento del Hogar</span>
    </div>
    <div style="position:absolute;top:7.26in;left:6.96in;">
    Q.'.number_format($sostenimiento_hogar,2,".",",").'
</div>
    <div style="position:absolute;top:7.46in;left:6.96in;">
    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
</div>
    <div style="position:absolute;top:7.56in;left:0.67in;width:0.78in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Vehículos</span>
    </div>
    <div style="position:absolute;top:7.55in;left:2.13in;">
    Q.'.number_format($vehiculos,2,".",",").'
</div>
    <div style="position:absolute;top:7.75in;left:2.13in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:7.56in;left:4.42in;width:0.64in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Alquiler</span>
    </div>
    <div style="position:absolute;top:7.55in;left:6.96in;">
    Q.'.number_format($alquiler,2,".",",").'
</div>
    <div style="position:absolute;top:7.75in;left:6.96in;">
    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
</div>
    <div style="position:absolute;top:7.84in;left:0.67in;width:0.90in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Inversiones</span>
    </div>
    <div style="position:absolute;top:7.83in;left:2.13in;">
    Q.'.number_format($inversiones,2,".",",").'
</div>
    <div style="position:absolute;top:8.03in;left:2.13in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:7.84in;left:4.42in;width:0.83in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Préstamos</span>
    </div>
    <div style="position:absolute;top:7.83in;left:6.96in;">
    Q.'.number_format($prestamos,2,".",",").'
</div>
    <div style="position:absolute;top:8.03in;left:6.96in;">
    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
</div>
    <div style="position:absolute;top:8.13in;left:0.67in;width:0.52in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Bonos</span>
    </div>
    <div style="position:absolute;top:8.12in;left:2.13in;">
    Q.'.number_format($bonos,2,".",",").'
</div>
    <div style="position:absolute;top:8.32in;left:2.13in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:8.13in;left:4.42in;width:0.83in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Impuestos</span>
    </div>
    <div style="position:absolute;top:8.12in;left:6.96in;">
    Q.'.number_format($impuestos,2,".",",").'
</div>
    <div style="position:absolute;top:8.32in;left:6.96in;">
    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
</div>
    <div style="position:absolute;top:8.41in;left:0.67in;width:0.72in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Acciones</span>
    </div>
    <div style="position:absolute;top:8.40in;left:2.13in;">
    Q.'.number_format($acciones,2,".",",").'
</div>
    <div style="position:absolute;top:8.60in;left:2.13in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:8.41in;left:4.42in;width:1.87in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Extrafinanciamientos TC</span>
    </div>
    <div style="position:absolute;top:8.40in;left:6.96in;">
    Q.'.number_format($extrafinanciamientos,2,".",",").'
</div>
    <div style="position:absolute;top:8.60in;left:6.96in;">
    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
</div>
    <div style="position:absolute;top:8.70in;left:0.67in;width:0.69in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Muebles</span>
    </div>
    <div style="position:absolute;top:8.69in;left:2.13in;">
    Q.'.number_format($muebles,2,".",",").'
</div>
    <div style="position:absolute;top:8.89in;left:2.13in;">
        <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
    </div>
    <div style="position:absolute;top:8.70in;left:4.42in;width:1.54in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Deudas Particulares</span>
    </div>
    <div style="position:absolute;top:8.69in;left:6.96in;">
    Q.'.number_format($deudas_particulares,2,".",",").'
</div>
    <div style="position:absolute;top:8.89in;left:6.96in;">
    <img style="width:1.61in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_4.png').'" />
</div>
    <div style="position:absolute;top:8.98in;left:0.55in;width:5.94in;line-height:0.13in;">
        <span style="font-style:italic;font-weight:normal;font-size:9pt;font-family:YuGothicUISemibold;color:#162846">*en caso de aplicar con otro solicitante, llenar este apartado en conjunto en un solo formulario.</span>
    </div>
    <div style="position:absolute;top:9.30in;left:0.55in;width:1.49in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">Detalle Patrimonial</span>
    </div>
    <div style="position:absolute;top:9.59in;left:0.52in;">
        <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
    </div>

    <div style="position:absolute;top:9.69in;left:0.55in;width:1.37in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Bienes Inmuebles</span>
    </div>
    <div style="position:absolute;top:9.93in;left:0.67in;width:1.80in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección del Inmueble</span>
    </div>
    <div style="position:absolute;top:9.92in;left:2.50in;width:6.05in;font-size:8pt">
        <span style="font-size:10pt;">'.$direccion_inmueble_1.'</span>
    </div>
    <div style="position:absolute;top:10.12in;left:2.50in;">
        <img style="width:6.05in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>

    <div style="position:absolute;top:10.24in;left:0.67in;width:0.45in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Finca</span>
    </div>
    <div style="position:absolute;top:10.23in;left:1.12in;width:1.00in;">
        '.$rTmpDetallePatrimonial->finca_1.'
    </div>
    <div style="position:absolute;top:10.43in;left:1.12in;">
        <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:10.24in;left:2.22in;width:0.45in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Folio</span>
    </div>
    <div style="position:absolute;top:10.23in;left:2.67in;width:1.00in;">
        '.$rTmpDetallePatrimonial->folio_1.'
    </div>
    <div style="position:absolute;top:10.43in;left:2.67in;">
        <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:10.24in;left:3.77in;width:0.45in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Libro</span>
    </div>
    <div style="position:absolute;top:10.23in;left:4.22in;width:1.00in;">
        '.$rTmpDetallePatrimonial->libro_1.'
    </div>
    <div style="position:absolute;top:10.43in;left:4.22in;">
        <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:10.24in;left:5.32in;width:1.05in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Departamento</span>
    </div>
    <div style="position:absolute;top:10.23in;left:6.42in;width:2.15in;">
    '.$rTmpDetallePatrimonial->departamento_nombre_1.'
    </div>
    <div style="position:absolute;top:10.43in;left:6.42in;">
        <img style="width:2.15in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>

    <div style="position:absolute;top:10.63in;left:0.67in;width:1.80in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección del Inmueble</span>
    </div>
    <div style="position:absolute;top:10.62in;left:2.50in;width:6.05in;font-size:8pt">
        <span style="font-size:10pt;">'.$direccion_inmueble_2.'</span>
    </div>
    <div style="position:absolute;top:10.82in;left:2.50in;">
        <img style="width:6.05in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:10.94in;left:0.67in;width:0.45in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Finca</span>
    </div>
    <div style="position:absolute;top:10.93in;left:1.12in;width:1.00in;">
        '.$rTmpDetallePatrimonial->finca_2.'
    </div>
    <div style="position:absolute;top:11.13in;left:1.12in;">
        <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:10.94in;left:2.22in;width:0.45in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Folio</span>
    </div>
    <div style="position:absolute;top:10.93in;left:2.67in;width:1.00in;">
        '.$rTmpDetallePatrimonial->folio_2.'
    </div>
    <div style="position:absolute;top:11.13in;left:2.67in;">
        <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:10.94in;left:3.77in;width:0.45in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Libro</span>
    </div>
    <div style="position:absolute;top:10.93in;left:4.22in;width:1.00in;">
        '.$rTmpDetallePatrimonial->libro_2.'
    </div>
    <div style="position:absolute;top:11.13in;left:4.22in;">
        <img style="width:1.00in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:10.94in;left:5.32in;width:1.05in;line-height:0.16in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Departamento</span>
    </div>
    <div style="position:absolute;top:10.93in;left:6.42in;width:2.15in;">
    '.$rTmpDetallePatrimonial->departamento_nombre_2.'
    </div>
    <div style="position:absolute;top:11.13in;left:6.42in;">
        <img style="width:2.15in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
<div style="position:absolute;top:2.15in;left:0.55in;width:7.89in;line-height:0.15in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#142746">Recibe un cordial saludo de parte del equipo de Avalia Desarrollos, desarrolladores de NAOS Apartamentos.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#142746"> </span><br/></SPAN></div>
<div style="position:absolute;top:2.35in;left:0.55in;width:7.66in;line-height:0.15in;"><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#142746">A continuación, solicitamos información complementaria para iniciar la gestión de tu crédito hipotecario.</span><span style="font-style:normal;font-weight:normal;font-size:10pt;font-family:YuGothicUISemibold;color:#142746"> </span><br/></SPAN></div>
<div style="position:absolute;top:11.58in;left:0.00in;">
    <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
</div>
<div style="position:absolute;top:11.65in;left:0.38in;">
    <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
</div>   
<div style="page-break-after: always;">
</div>
'.$fondo.'
    <div style="position:absolute;top:0.60in;left:0.55in;">
        <img style="width:1.67in;height:0.74in" src="'.fcnBase64($logo).'" />
    </div>
<div style="position:absolute;top:0.46in;left:6.06in;width:2.49in;height:0.84in">
'.$fondo_apartamento.'
</div>
<div style="position:absolute;top:0.73in;left:6.27in;width:1.43in;line-height:0.21in;">
    <span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:SUBAAC+Effra;color:'.$color.'">Apartamento</span>
</div>
<div style="position:absolute;top:1.06in;left:6.33in;width:1.43in;line-height:0.20in;">
    <span style="font-style:normal;font-weight:bold;font-size:16pt;font-family:SUBAAC+Effra;color:'.$color_depto.'">'.$rTmp->apartamento.'</span>
</div>

<div style="position:absolute;top:1.64in;left:0.55in;width:0.78in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Vehículos</span>
</div>

<div style="position:absolute;top:1.88in;left:0.67in;width:0.60in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Marca:</span>
</div>
<div style="position:absolute;top:1.87in;left:1.20in;width:1.20in;">
    '.$rTmpDetallePatrimonial->marca_1.'
</div>
<div style="position:absolute;top:2.07in;left:1.20in;">
    <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:1.88in;left:2.45in;width:0.60in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo:</span>
</div>
<div style="position:absolute;top:1.87in;left:2.83in;width:1.20in;">
    '.$rTmpDetallePatrimonial->tipo_vehiculo_1.'
</div>
<div style="position:absolute;top:2.07in;left:2.83in;">
    <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:1.88in;left:4.10in;width:0.60in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Modelo:</span>
</div>
<div style="position:absolute;top:1.87in;left:4.70in;width:1.20in;">
    '.$rTmpDetallePatrimonial->modelo_vehiculo_1.'
</div>
<div style="position:absolute;top:2.07in;left:4.70in;">
    <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:1.88in;left:5.95in;width:1.50in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Valor estimado:</span>
</div>
<div style="position:absolute;top:1.87in;left:7.10in;width:1.20in;">
    Q.'.number_format($rTmpDetallePatrimonial->valor_estimado_1,2,".",",").'
</div>
<div style="position:absolute;top:2.07in;left:7.10in;">
    <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>

<div style="position:absolute;top:2.18in;left:0.67in;width:0.60in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Marca:</span>
</div>
<div style="position:absolute;top:2.17in;left:1.20in;width:1.20in;">
    '.$rTmpDetallePatrimonial->marca_2.'
</div>
<div style="position:absolute;top:2.37in;left:1.20in;">
    <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:2.18in;left:2.45in;width:0.60in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo:</span>
</div>
<div style="position:absolute;top:2.17in;left:2.83in;width:1.20in;">
    '.$rTmpDetallePatrimonial->tipo_vehiculo_2.'
</div>
<div style="position:absolute;top:2.37in;left:2.83in;">
    <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:2.18in;left:4.10in;width:0.60in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Modelo:</span>
</div>
<div style="position:absolute;top:2.17in;left:4.70in;width:1.20in;">
    '.$rTmpDetallePatrimonial->modelo_vehiculo_2.'
</div>
<div style="position:absolute;top:2.37in;left:4.70in;">
    <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:2.18in;left:5.95in;width:1.50in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Valor estimado:</span>
</div>
<div style="position:absolute;top:2.17in;left:7.10in;width:1.20in;">
    Q.'.number_format($rTmpDetallePatrimonial->valor_estimado_2,2,".",",").'
</div>
<div style="position:absolute;top:2.37in;left:7.10in;">
    <img style="width:1.20in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:2.75in;left:0.52in;">
    <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
</div>
<div style="position:absolute;top:2.57in;left:0.55in;width:6.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#de5b68">Información de Ingresos en Relación de Dependencia (si aplica)</span>
</div>
<div style="position:absolute;top:2.93in;left:2.65in;">
    '.$rTmp->empresaLabora.'
</div>
<div style="position:absolute;top:3.13in;left:2.65in;">
    <img style="width:3.23in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:2.93in;left:0.67in;width:1.90in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Sociedad a la que labora:</span>
</div>
<div style="position:absolute;top:2.93in;left:6.96in;">
    '.$rTmp->telefonoReferencia.'
</div>
<div style="position:absolute;top:3.13in;left:6.96in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:2.93in;left:6.25in;width:1.90in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Teléfono:</span>
</div>
<div style="position:absolute;top:3.33in;left:0.67in;width:1.33in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo de Contrato:</span>
</div>
<div style="position:absolute;top:3.31in;left:2.75in;">
    X
</div>
<div style="position:absolute;top:3.33in;left:2.70in;">
    <img style="width:0.25in;height:0.18in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/ri_6.png').'" />
</div>
<div style="position:absolute;top:3.33in;left:3.00in;width:1.14in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Indefinido</span>
</div>
<div style="position:absolute;top:3.33in;left:4.32in;">
    <img style="width:0.25in;height:0.18in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/ri_6.png').'" />
</div>
<div style="position:absolute;top:3.33in;left:4.62in;width:3.14in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Vigencia definida vence: ____/____/____</span>
</div>
<div style="position:absolute;top:3.70in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Detalle de Ingresos Mensuales:</span>
</div>
<div style="position:absolute;top:3.95in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Salario Nominal:</span>
</div>
<div style="position:absolute;top:4.15in;left:2.67in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:3.95in;left:2.67in;">
    Q.'.number_format($rTmpIngresosEgresos->salario_nominal,2,".",",").'
</div>
<div style="position:absolute;top:4.20in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Bono 14 (1/12):</span>
</div>
<div style="position:absolute;top:4.40in;left:2.67in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:4.20in;left:2.67in;">
    Q.'.number_format($rTmpIngresosEgresos->bono_catorce,2,".",",").'
</div>
<div style="position:absolute;top:4.45in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Aguinaldo (172):</span>
</div>
<div style="position:absolute;top:4.65in;left:2.67in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:4.45in;left:2.67in;">
    Q.'.number_format($rTmpIngresosEgresos->aguinaldo,2,".",",").'
</div>
<div style="position:absolute;top:4.70in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Honorarios:</span>
</div>
<div style="position:absolute;top:4.90in;left:2.67in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:4.70in;left:2.67in;">
    Q.'.number_format($rTmpIngresosEgresos->honorarios,2,".",",").'
</div>
<div style="position:absolute;top:4.95in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Otros:</span>
</div>
<div style="position:absolute;top:5.15in;left:2.67in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:4.95in;left:2.67in;">
    Q.'.number_format($rTmpIngresosEgresos->otros_ingresos_fha,2,".",",").'
</div>

<div style="position:absolute;top:3.70in;left:4.84in;width:2.60in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Detalle de Descuentos Mensuales:</span>
</div>
<div style="position:absolute;top:3.95in;left:4.84in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">IGSS:</span>
</div>
<div style="position:absolute;top:4.15in;left:6.84in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:3.95in;left:6.84in;">
    Q.'.number_format($rTmpIngresosEgresos->igss,2,".",",").'
</div>
<div style="position:absolute;top:4.20in;left:4.84in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">ISR:</span>
</div>
<div style="position:absolute;top:4.40in;left:6.84in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:4.20in;left:6.84in;">
    Q.'.number_format($rTmpIngresosEgresos->isr,2,".",",").'
</div>
<div style="position:absolute;top:4.45in;left:4.84in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Plan de Pensiones:</span>
</div>
<div style="position:absolute;top:4.65in;left:6.84in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:4.45in;left:6.84in;">
    Q.'.number_format($rTmpIngresosEgresos->plan_pensiones,2,".",",").'
</div>
<div style="position:absolute;top:4.70in;left:4.84in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Judiciales:</span>
</div>
<div style="position:absolute;top:4.90in;left:6.84in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:4.70in;left:6.84in;">
    Q.'.number_format($rTmpIngresosEgresos->judiciales,2,".",",").'
</div>
<div style="position:absolute;top:4.95in;left:4.84in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Otros:</span>
</div>
<div style="position:absolute;top:5.15in;left:6.84in;">
    <img style="width:1.61in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:4.95in;left:6.84in;">
    Q.'.number_format($rTmpIngresosEgresos->otros_descuentos_fha,2,".",",").'
</div>
<div style="position:absolute;top:5.30in;left:0.67in;width:5.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Detalle de Comisiones y Bonificaciones últimos 6 meses:</span>
</div>
<div style="position:absolute;top:5.60in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Mes:</span>
</div>
<div style="position:absolute;top:5.90in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Horas Extra:</span>
</div>
<div style="position:absolute;top:6.20in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Comisiones:</span>
</div>
<div style="position:absolute;top:6.50in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Bonificaciones:</span>
</div>

<div style="position:absolute;top:5.55in;left:1.70in;">
    '.$arr[0].'
</div>
<div style="position:absolute;top:5.75in;left:1.70in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:5.55in;left:2.85in;">
    '.$arr[1].'
</div>
<div style="position:absolute;top:5.75in;left:2.85in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:5.55in;left:4.00in;">
    '.$arr[2].'
</div>
<div style="position:absolute;top:5.75in;left:4.00in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:5.55in;left:5.15in;">
    '.$arr[3].'
</div>
<div style="position:absolute;top:5.75in;left:5.15in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:5.55in;left:6.30in;">
    '.$arr[4].'
</div>
<div style="position:absolute;top:5.75in;left:6.30in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:5.55in;left:7.45in;">
    '.$arr[5].'
</div>
<div style="position:absolute;top:5.75in;left:7.45in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>

<div style="position:absolute;top:5.85in;left:1.70in;">
    Q.'.number_format($rTmpDetalleComisiones->hora_extra_mes_1,2,".",",").'
</div>
<div style="position:absolute;top:6.05in;left:1.70in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:5.85in;left:2.85in;">
    Q.'.number_format($rTmpDetalleComisiones->hora_extra_mes_2,2,".",",").'
</div>
<div style="position:absolute;top:6.05in;left:2.85in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:5.85in;left:4.00in;">
    Q.'.number_format($rTmpDetalleComisiones->hora_extra_mes_3,2,".",",").'
</div>
<div style="position:absolute;top:6.05in;left:4.00in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:5.85in;left:5.15in;">
    Q.'.number_format($rTmpDetalleComisiones->hora_extra_mes_4,2,".",",").'
</div>
<div style="position:absolute;top:6.05in;left:5.15in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:5.85in;left:6.30in;">
    Q.'.number_format($rTmpDetalleComisiones->hora_extra_mes_5,2,".",",").'
</div>
<div style="position:absolute;top:6.05in;left:6.30in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:5.85in;left:7.45in;">
    Q.'.number_format($rTmpDetalleComisiones->hora_extra_mes_6,2,".",",").'
</div>
<div style="position:absolute;top:6.05in;left:7.45in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>

<div style="position:absolute;top:6.15in;left:1.70in;">
    Q.'.number_format($rTmpDetalleComisiones->comisiones_mes_1,2,".",",").'
</div>
<div style="position:absolute;top:6.35in;left:1.70in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.15in;left:2.85in;">
    Q.'.number_format($rTmpDetalleComisiones->comisiones_mes_2,2,".",",").'
</div>
<div style="position:absolute;top:6.35in;left:2.85in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.15in;left:4.00in;">
    Q.'.number_format($rTmpDetalleComisiones->comisiones_mes_3,2,".",",").'
</div>
<div style="position:absolute;top:6.35in;left:4.00in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.15in;left:5.15in;">
    Q.'.number_format($rTmpDetalleComisiones->comisiones_mes_4,2,".",",").'
</div>
<div style="position:absolute;top:6.35in;left:5.15in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.15in;left:6.30in;">
    Q.'.number_format($rTmpDetalleComisiones->comisiones_mes_5,2,".",",").'
</div>
<div style="position:absolute;top:6.35in;left:6.30in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.15in;left:7.45in;">
    Q.'.number_format($rTmpDetalleComisiones->comisiones_mes_1,2,".",",").'
</div>
<div style="position:absolute;top:6.35in;left:7.45in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.50in;left:1.70in;">
    Q.'.number_format($rTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
</div>
<div style="position:absolute;top:6.70in;left:1.70in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.50in;left:2.85in;">
    Q.'.number_format($rTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
</div>
<div style="position:absolute;top:6.70in;left:2.85in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.50in;left:4.00in;">
    Q.'.number_format($rTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
</div>
<div style="position:absolute;top:6.70in;left:4.00in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.50in;left:5.15in;">
    Q.'.number_format($rTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
</div>
<div style="position:absolute;top:6.70in;left:5.15in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.50in;left:6.30in;">
    Q.'.number_format($rTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
</div>
<div style="position:absolute;top:6.70in;left:6.30in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:6.50in;left:7.45in;">
    Q.'.number_format($rTmpDetalleComisiones->bonificaciones_mes_1,2,".",",").'
</div>
<div style="position:absolute;top:6.70in;left:7.45in;">
    <img style="width:1.00in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:7.00in;left:0.67in;width:5.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Historial Laboral últimos dos años</span>
</div>
<div style="position:absolute;top:7.20in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Empresa</span>
</div>
<div style="position:absolute;top:7.20in;left:3.77in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Cargo</span>
</div>
<div style="position:absolute;top:7.20in;left:5.92in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Desde</span>
</div>
<div style="position:absolute;top:7.20in;left:7.53in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Hasta</span>
</div>';
$top = 7.70;
$top_text = 7.50;
if($rTmpHistorialLaboral->empresa_1!=''){
    $hasta_1 = $rTmpHistorialLaboral->hasta_1 != '0000-00-00' ? date('d/m/Y',strtotime($rTmpHistorialLaboral->hasta_1)) : 'Actual';
    $texto_r .='
    <div style="position:absolute;top:'.$top_text.'in;left:0.67in;">
        '.$rTmpHistorialLaboral->empresa_1.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:0.67in;">
        <img style="width:2.80in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:3.77in;">
        '.$rTmpHistorialLaboral->cargo_1.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.77in;">
        <img style="width:1.90in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:5.92in;">
        '.date('d/m/Y',strtotime($rTmpHistorialLaboral->desde_1)).'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.92in;">
        <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:07.53in;">
        '.$hasta_1.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:7.53in;">
        <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>';
    $top += 0.20;
    $top_text += 0.20;
}
if($rTmpHistorialLaboral->empresa_2!=''){
    $top += 0.10;
    $top_text += 0.10;
    $hasta_2 = $rTmpHistorialLaboral->hasta_2 != '0000-00-00' ? date('d/m/Y',strtotime($rTmpHistorialLaboral->hasta_2)) : 'Actual';
    $texto_r .='
    <div style="position:absolute;top:'.$top_text.'in;left:0.67in;">
        '.$rTmpHistorialLaboral->empresa_2.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:0.67in;">
        <img style="width:2.80in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:3.77in;">
        '.$rTmpHistorialLaboral->cargo_2.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.77in;">
        <img style="width:1.90in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:5.92in;">
        '.date('d/m/Y',strtotime($rTmpHistorialLaboral->desde_2)).'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.92in;">
        <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:07.53in;">
        '.$hasta_2.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:7.53in;">
        <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>';
    $top += 0.20;
    $top_text += 0.20;
}
if($rTmpHistorialLaboral->empresa_3!=''){
    $top += 0.10;
    $top_text += 0.10;
    $hasta_3 = $rTmpHistorialLaboral->hasta_3 != '0000-00-00' ? date('d/m/Y',strtotime($rTmpHistorialLaboral->hasta_3)) : 'Actual';
    $texto_r .='
    <div style="position:absolute;top:'.$top_text.'in;left:0.67in;">
        '.$rTmpHistorialLaboral->empresa_3.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:0.67in;">
        <img style="width:2.80in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:3.77in;">
        '.$rTmpHistorialLaboral->cargo_3.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.77in;">
        <img style="width:1.90in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:5.92in;">
        '.date('d/m/Y',strtotime($rTmpHistorialLaboral->desde_3)).'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.92in;">
        <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:07.53in;">
        '.$hasta_3.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:7.53in;">
        <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>';
    $top += 0.20;
    $top_text += 0.20;
}
if($rTmpHistorialLaboral->empresa_4!=''){
    $top += 0.10;
    $top_text += 0.10;
    $hasta_4 = $rTmpHistorialLaboral->hasta_4 != '0000-00-00' ? date('d/m/Y',strtotime($rTmpHistorialLaboral->hasta_4)) : 'Actual';
    $texto_r .='
    <div style="position:absolute;top:'.$top_text.'in;left:0.67in;">
        '.$rTmpHistorialLaboral->empresa_4.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:0.67in;">
        <img style="width:2.80in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:3.77in;">
        '.$rTmpHistorialLaboral->cargo_4.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.77in;">
        <img style="width:1.90in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:5.92in;">
        '.date('d/m/Y',strtotime($rTmpHistorialLaboral->desde_4)).'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.92in;">
        <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top_text.'in;left:07.53in;">
        '.$hasta_4.'
    </div>
    <div style="position:absolute;top:'.$top.'in;left:7.53in;">
        <img style="width:1.07in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>';
    $top += 0.20;
    $top_text += 0.20;
}
$top += 0.10;
$top_text += 0.10;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.52in;">
    <img style="width:8.05in;height:0.03in"  src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_17.png').'" />
</div>
<div style="position:absolute;top:'.$top_text.'in;left:0.52in;width:5.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Referencias Familiares, Bancarias y Crediticias</span>
</div>';
$top += 0.10;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:4.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Ref. Familiar 1</span>
</div>
';
$top += 0.30;
$top_linea = $top + 0.20;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Nombre:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:1.40in;width:5.00in;">
    '.$rTmpRefFamiliar->nombre_referencia_1.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:1.40in;">
    <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:6.30in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Parentesco:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:7.15in;width:5.00in;">
    '.$rTmpRefFamiliar->parentesco_referencia_1.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:7.15in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
';
$top += 0.30;
$top_linea = $top + 0.20;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:1.40in;width:5.00in;">
    '.$rTmpRefFamiliar->domicilio_1.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:1.40in;">
    <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:6.30in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Teléfono:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:7.15in;width:5.00in;">
    '.$rTmpRefFamiliar->telefono_1.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:7.15in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
';
$top += 0.35;
$top_linea = $top + 0.20;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Trabajo:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:1.40in;width:5.00in;">
    '.$rTmpRefFamiliar->trabajo_1.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:1.40in;">
    <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:6.30in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Teléfono:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:7.15in;width:5.00in;">
    '.$rTmpRefFamiliar->trabajo_telefono_1.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:7.15in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
';
$top += 0.30;
$top_linea = $top + 0.20;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:1.40in;width:5.00in;">
    '.$rTmpRefFamiliar->trabajo_direccion_1.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:1.40in;">
    <img style="width:7.30in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
';

$top += 0.40;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:4.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Ref. Familiar 2</span>
</div>
';
$top += 0.30;
$top_linea = $top + 0.20;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Nombre:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:1.40in;width:5.00in;">
    '.$rTmpRefFamiliar->nombre_referencia_2.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:1.40in;">
    <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:6.30in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Parentesco:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:7.15in;width:5.00in;">
    '.$rTmpRefFamiliar->parentesco_referencia_2.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:7.15in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
';
$top += 0.30;
$top_linea = $top + 0.20;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:1.40in;width:5.00in;">
    '.$rTmpRefFamiliar->domicilio_2.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:1.40in;">
    <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:6.30in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Teléfono:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:7.15in;width:5.00in;">
    '.$rTmpRefFamiliar->telefono_2.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:7.15in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
';
$top += 0.35;
$top_linea = $top + 0.20;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Trabajo:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:1.40in;width:5.00in;">
    '.$rTmpRefFamiliar->trabajo_2.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:1.40in;">
    <img style="width:4.80in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:6.30in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Teléfono:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:7.15in;width:5.00in;">
    '.$rTmpRefFamiliar->trabajo_telefono_2.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:7.15in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
';
$top += 0.30;
$top_linea = $top + 0.20;
$texto_r .='
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.00in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Dirección:</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:1.40in;width:5.00in;">
    '.$rTmpRefFamiliar->trabajo_direccion_2.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:1.40in;">
    <img style="width:7.30in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
';
$texto_r .='<div style="position:absolute;top:11.58in;left:0.00in;">
    <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
</div>
<div style="position:absolute;top:11.65in;left:0.38in;">
    <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
</div>   
<div style="page-break-after: always;">
</div>
'.$fondo.'
    <div style="position:absolute;top:0.60in;left:0.55in;">
        <img style="width:1.67in;height:0.74in" src="'.fcnBase64($logo).'" />
    </div>
<div style="position:absolute;top:0.46in;left:6.06in;width:2.49in;height:0.84in">
'.$fondo_apartamento.'
</div>
<div style="position:absolute;top:0.73in;left:6.27in;width:1.43in;line-height:0.21in;">
    <span style="font-style:normal;font-weight:bold;font-size:15pt;font-family:SUBAAC+Effra;color:'.$color.'">Apartamento</span>
</div>
<div style="position:absolute;top:1.06in;left:6.33in;width:1.43in;line-height:0.20in;">
    <span style="font-style:normal;font-weight:bold;font-size:16pt;font-family:SUBAAC+Effra;color:'.$color_depto.'">'.$rTmp->apartamento.'</span>
</div>

<div style="position:absolute;top:1.64in;left:0.55in;width:4.78in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Referencias Bancarías</span>
</div>
<div style="position:absolute;top:1.85in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Banco</span>
</div>
<div style="position:absolute;top:2.05in;left:0.67in;">
    '.$rTmpRefBancarias->banco_1.'
</div>
<div style="position:absolute;top:2.25in;left:0.67in;">
    <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:1.85in;left:3.50in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo de Cuenta</span>
</div>
<div style="position:absolute;top:2.05in;left:3.50in;">
    '.$rTmpRefBancarias->tipo_cuenta_1.'
</div>
<div style="position:absolute;top:2.25in;left:3.50in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:1.85in;left:5.25in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">No. de Cuenta</span>
</div>
<div style="position:absolute;top:2.05in;left:5.25in;">
    '.$rTmpRefBancarias->no_cuenta_1.'
</div>
<div style="position:absolute;top:2.25in;left:5.25in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:1.85in;left:7.00in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Saldo Actual</span>
</div>
<div style="position:absolute;top:2.05in;left:7.00in;">
    Q.'.number_format($rTmpRefBancarias->saldo_actual_1,2,".",",").'
</div>
<div style="position:absolute;top:2.25in;left:7.00in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>';
$top = 2.35;
$top_linea = 2.55;
if($rTmpRefBancarias->banco_2!=''){
    $texto_r .= '
    <div style="position:absolute;top:'.$top.'in;left:0.67in;">
        '.$rTmpRefBancarias->banco_2.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:0.67in;">
        <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.50in;">
        '.$rTmpRefBancarias->tipo_cuenta_2.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:3.50in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.25in;">
        '.$rTmpRefBancarias->no_cuenta_2.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:5.25in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:7.00in;">
        Q.'.number_format($rTmpRefBancarias->saldo_actual_2,2,".",",").'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:7.00in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>';
    $top += 0.30;
    $top_linea += 0.30;
}
$top += 0.10;
$texto_r .= '<div style="position:absolute;top:'.$top.'in;left:0.55in;width:4.78in;line-height:0.16in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#dd5a67">Referencias Crediticias</span>
</div>';
$top += 0.20;
$texto_r .= '
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Banco</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:3.50in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo de Préstamo</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:5.25in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">No. de Préstamo</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:7.00in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Monto</span>
</div>
';
$top += 0.20;
$top_linea = $top + 0.20;
$texto_r .= '
<div style="position:absolute;top:'.$top.'in;left:0.67in;">
    '.$rTmpRefCrediticias->banco_prestamo_1.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:0.67in;">
    <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:3.50in;">
    '.$rTmpRefCrediticias->tipo_prestamo_1.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:3.50in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:5.25in;">
    '.$rTmpRefCrediticias->no_prestamo_1.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:5.25in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:7.00in;">
    Q.'.number_format($rTmpRefCrediticias->monto_1,2,".",",").'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:7.00in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>';
$top += 0.30;
$texto_r .= '
<div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Saldo Actual</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:3.50in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Pago Mensual</span>
</div>
<div style="position:absolute;top:'.$top.'in;left:5.25in;width:2.35in;">
    <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Fecha Vencimiento</span>
</div>
';
$top += 0.20;
$top_linea = $top + 0.20;
$fecha_vencimiento = $rTmpRefCrediticias->fecha_vencimiento_prestamo_1 != '0000-00-00' ? date('d/m/Y',strtotime($rTmpRefCrediticias->fecha_vencimiento_prestamo_1)) : '';
$texto_r .= '
<div style="position:absolute;top:'.$top.'in;left:0.67in;">
    Q.'.number_format($rTmpRefCrediticias->saldo_actual_prestamo_1,2,".",",").'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:0.67in;">
    <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:3.50in;">
    Q.'.number_format($rTmpRefCrediticias->pago_mensual_prestamo_1,2,".",",").'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:3.50in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>
<div style="position:absolute;top:'.$top.'in;left:5.25in;">
    '.$fecha_vencimiento.'
</div>
<div style="position:absolute;top:'.$top_linea.'in;left:5.25in;">
    <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
</div>';

if($rTmpRefCrediticias->banco_prestamo_2!=''){
    $top += 0.30;
    $texto_r .= '
    <div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Banco</span>
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.50in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo de Préstamo</span>
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.25in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">No. de Préstamo</span>
    </div>
    <div style="position:absolute;top:'.$top.'in;left:7.00in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Monto</span>
    </div>
    ';
    $top += 0.20;
    $top_linea = $top + 0.20;
    $texto_r .= '
    <div style="position:absolute;top:'.$top.'in;left:0.67in;">
        '.$rTmpRefCrediticias->banco_prestamo_2.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:0.67in;">
        <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.50in;">
        '.$rTmpRefCrediticias->tipo_prestamo_2.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:3.50in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.25in;">
        '.$rTmpRefCrediticias->no_prestamo_2.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:5.25in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:7.00in;">
        Q.'.number_format($rTmpRefCrediticias->monto_2,2,".",",").'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:7.00in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>';
    $top += 0.30;
    $texto_r .= '
    <div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Saldo Actual</span>
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.50in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Pago Mensual</span>
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.25in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Fecha Vencimiento</span>
    </div>
    ';
    $top += 0.20;
    $top_linea = $top + 0.20;
    $fecha_vencimiento_2 = $rTmpRefCrediticias->fecha_vencimiento_prestamo_2 != '0000-00-00' ? date('d/m/Y',strtotime($rTmpRefCrediticias->fecha_vencimiento_prestamo_2)) : '';
    $texto_r .= '
    <div style="position:absolute;top:'.$top.'in;left:0.67in;">
        Q.'.number_format($rTmpRefCrediticias->saldo_actual_prestamo_2,2,".",",").'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:0.67in;">
        <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.50in;">
        Q.'.number_format($rTmpRefCrediticias->pago_mensual_prestamo_2,2,".",",").'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:3.50in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.25in;">
        '.$fecha_vencimiento_2.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:5.25in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>';
}
if($rTmpRefCrediticias->banco_prestamo_3!=''){
    $top += 0.30;
    $texto_r .= '
    <div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Banco</span>
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.50in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Tipo de Préstamo</span>
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.25in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">No. de Préstamo</span>
    </div>
    <div style="position:absolute;top:'.$top.'in;left:7.00in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Monto</span>
    </div>
    ';
    $top += 0.20;
    $top_linea = $top + 0.20;
    $texto_r .= '
    <div style="position:absolute;top:'.$top.'in;left:0.67in;">
        '.$rTmpRefCrediticias->banco_prestamo_3.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:0.67in;">
        <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.50in;">
        '.$rTmpRefCrediticias->tipo_prestamo_3.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:3.50in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.25in;">
        '.$rTmpRefCrediticias->no_prestamo_3.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:5.25in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:7.00in;">
        Q.'.number_format($rTmpRefCrediticias->monto_3,2,".",",").'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:7.00in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>';
    $top += 0.30;
    $texto_r .= '
    <div style="position:absolute;top:'.$top.'in;left:0.67in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Saldo Actual</span>
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.50in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Pago Mensual</span>
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.25in;width:2.35in;">
        <span style="font-style:normal;font-weight:normal;font-size:11pt;font-family:YuGothicUISemibold;color:#162846">Fecha Vencimiento</span>
    </div>
    ';
    $top += 0.20;
    $top_linea = $top + 0.20;
    $fecha_vencimiento_3 = $rTmpRefCrediticias->fecha_vencimiento_prestamo_3 != '0000-00-00' ? date('d/m/Y',strtotime($rTmpRefCrediticias->fecha_vencimiento_prestamo_3)) : '';
    $texto_r .= '
    <div style="position:absolute;top:'.$top.'in;left:0.67in;">
        Q.'.number_format($rTmpRefCrediticias->saldo_actual_prestamo_3,2,".",",").'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:0.67in;">
        <img style="width:2.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:3.50in;">
        Q.'.number_format($rTmpRefCrediticias->pago_mensual_prestamo_3,2,".",",").'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:3.50in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>
    <div style="position:absolute;top:'.$top.'in;left:5.25in;">
        '.$fecha_vencimiento_3.'
    </div>
    <div style="position:absolute;top:'.$top_linea.'in;left:5.25in;">
        <img style="width:1.50in;height:0.03in" src="'.fcnBase64('./SodaPDFDocFHA/OutDocument/vi_13.png').'" />
    </div>';
}

$texto_r .= '<div style="position:absolute;top:11.58in;left:0.00in;">
    <img style="width:9.27in;height:0.42in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/ci_4.png').'" />
</div>
<div style="position:absolute;top:11.65in;left:0.38in;">
    <img style="width:1.06in;height:0.28in" src="'.fcnBase64('./SodaPDFCotMarabi/OutDocument/avalia-logo.png').'" />
</div>';
$texto_r .= $texto_rCo;

$texto_r .='</body>
    </html>';

    $mpdf ->writeHtml($css_r,\Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf ->writeHtml($texto_r,\Mpdf\HTMLParserMode::HTML_BODY);
    
    $mpdf ->Output(normalizarNombre($rTmp->client_name).'.pdf', 'I');


}
function normalizarNombre($nombre){

    $aa=preg_replace('/[^a-zA-Z0-9_ -]/s','',$nombre);
    $bb=str_replace('/', '-', $aa);

    return $bb;

  }

function fcnBase64($imagen){
    $imagenBase64 = "data:image/png;base64,".base64_encode(file_get_contents($imagen));
    return $imagenBase64;

}

function NombreMes($mes) {
	switch ($mes) {
		case 1: return 'Enero';
		case 2: return 'Febrero';
		case 3: return 'Marzo';
		case 4: return 'Abril';
		case 5: return 'Mayo';
		case 6: return 'Junio';
		case 7: return 'Julio';
		case 8: return 'Agosto';
		case 9: return 'Septiembre';
		case 10: return 'Octubre';
		case 11: return 'Noviembre';
		case 12: return 'Diciembre';
		default: return '';
	}
}
    
?>
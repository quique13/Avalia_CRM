<?php
set_time_limit(300);
include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";

$conn = new dbClassMysql();
$func = new Functions();

$func->getHeaders();
$res = array(
    "err"=> true,
    "mss"=> "Error 404",
    "mssError" =>""
);
session_name("inmobiliaria");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>"; 
}
$id_usuario=$_SESSION['id_usuario'];
$arrayProyectos = explode(",",$_SESSION['proyectos']);
$proyectos = '';
$countP=0;
foreach($arrayProyectos as $valor)
{
    if($countP==0)
        $coma='';
    else
        $coma=',';
    $proyectos .= $coma."'".$valor."'";
    $countP++;
}
$countP=0;
//Buscar
$strDatoBuscar= isset($_POST['datoBuscar']) ? trim($_POST['datoBuscar']):'';
$strProyectoBuscar= isset($_POST['proyectoBsc']) ? trim($_POST['proyectoBsc']):'';
$strTorreBuscar= isset($_POST['torreBsc']) ? trim($_POST['torreBsc']):'';
$strApartamentoBuscar= isset($_POST['apartamentoBsc']) ? trim($_POST['apartamentoBsc']):'';
$strNivelBuscar= isset($_POST['nivelBsc']) ? trim($_POST['nivelBsc']):'';
$strFechaFinal= isset($_POST['fechaFinal']) ? trim($_POST['fechaFinal']):'';
$intEstadoDes= isset($_POST['desestimiento']) ? intval($_POST['desestimiento']):1;
if($strProyectoBuscar!='' && $strProyectoBuscar!=0){
	$strFechaConsulta.= " AND dg.idGlobal ='{$strProyectoBuscar}' ";
}
if($strTorreBuscar!='' && $strTorreBuscar!=0){
	$strFechaConsulta.= " AND t.idTorre ='{$strTorreBuscar}' ";
}
if($strNivelBuscar!='' && $strNivelBuscar!=0){
	$strFechaConsulta.= " AND n.idNivel ='{$strNivelBuscar}' ";
}
if($strFechaFinal!=''){
	$fechaFiltro= date("Y-m-d 23:59:59",strtotime($strFechaFinal));
	//$hoy=date('Y-m-d');
	$hoy = date("d-m-Y",strtotime($strFechaFinal));
	//resta 5 dias
	$hoy = date("Y-m-d 23:59:59",strtotime($hoy."- 0 days"));
}else{
	//$hoy=date('Y-m-d');
	$hoy = date("d-m-Y");
	//resta 5 dias
	$hoy = date("Y-m-d 23:59:59",strtotime($hoy."- 0 days"));
}
 
$strQuery = "SELECT ac.codigo,e.proyecto, 
					IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
					IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,
					e.apartamento,e.idEnganche as id,
					(SELECT ped.fechaPago FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) ORDER BY ped.fechaPago ASC LIMIT 1) as fechaParaPago,
					(SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) AND fechaPago <'{$hoy }') as pagosAtrasados,
					(SELECT DATEDIFF(NOW(),fechaPago) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) ORDER BY ped.fechaPago ASC LIMIT 1) as dias,
					(SELECT ifnull(SUM(ped.monto),0) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) AND (DATEDIFF(NOW(),fechaPago)<30 AND DATEDIFF(NOW(),fechaPago)>0)  ) as 'cero_treinta',
					(SELECT ifnull(SUM(ped.monto),0) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) AND (DATEDIFF(NOW(),fechaPago)<90 AND DATEDIFF(NOW(),fechaPago)>=30) ) as 'treinta_noventa',
					(SELECT ifnull(SUM(ped.monto),0) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) AND (DATEDIFF(NOW(),fechaPago)>=90) ) as 'noventa_mas',
					(e.MontoReserva + enganchePorcMonto) as enganche,
					(SELECT SUM(monto) FROM prograEngancheDetalle ped WHERE ped.idEnganche = e.idEnganche) as totalEnganche,
					(SELECT ifnull(SUM(ped.monto),0) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}') + e.MontoReserva  as compromisoMonto,
					(SELECT ifnull(SUM(ped.monto),0) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND pagado = 1 AND validado = 1 AND fechaPagoRealizado <'{$hoy }' ) + e.MontoReserva as pagado,
					(case 
                    when 
                    (SELECT DATEDIFF(NOW(),fechaPago) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) ORDER BY ped.fechaPago ASC LIMIT 1) <= 0 then 0
                    when (SELECT SUM(montoPagado) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1) >= ((e.enganchePorcMonto- e.MontoReserva)/e.pagosEnganche) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}') then 0
                    else  
                    if(( ( ((e.enganchePorcMonto - (SELECT SUM(monto) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) ) - e.MontoReserva)/(e.pagosEnganche - (SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) )) ) >0 AND ( ( ((e.enganchePorcMonto - (SELECT SUM(monto) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) ) - e.MontoReserva)/(e.pagosEnganche - (SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) )) ) < ( ((e.enganchePorcMonto - e.MontoReserva)/e.pagosEnganche) ) ,

                        (
                            ( 
                                (
                                    (
                                        e.enganchePorcMonto  - (SELECT SUM(monto) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1)- e.MontoReserva
                                    )/
                                    (
                                        e.pagosEnganche -  
                                        (
                                            SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1
                                        )
                                    ) 
                                ) * 
                                (
                                    SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}'
                                )
                            )-
                            (
                                SELECT SUM(montoPagado) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1
                            )
                        )/
                        ( 
                            ( 
                                (
                                    (
                                        e.enganchePorcMonto - 
                                        (
                                            SELECT SUM(monto) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1
                                        ) 
                                    ) - 
                                    e.MontoReserva
                                )/
                                (
                                    e.pagosEnganche - 
                                    (
                                        SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1
                                    ) 
                                )
                            ) 
                        ),
                        (
                            (
                                (
                                    (
                                        e.enganchePorcMonto- e.MontoReserva
                                    )/
                                    e.pagosEnganche
                                ) * 
                                (
                                    SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}'
                                ) - 
                                (
                                    SELECT SUM(montoPagado) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1
                                )
                            ) / 
                            (
                                (
                                    e.enganchePorcMonto - e.MontoReserva
                                )/e.pagosEnganche
                            )
                        )
                    )
                    end) as orden,
					(
                        CASE
                            WHEN ac.codigo in('2021-11','2021-9','2021-33') then 1
                            else 0
                        END
                    )as ordenDes,
					e.MontoReserva,
					((SELECT ifnull(SUM(montoPagado),0) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1 AND fechaPagoRealizado <'{$hoy }' )) totalPagado,
					( ((e.enganchePorcMonto - e.MontoReserva)/e.pagosEnganche) ) cuotas,
					( ((e.enganchePorcMonto- e.MontoReserva)/e.pagosEnganche) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}')) as debePagar,
                    ( ( ((e.enganchePorcMonto - (SELECT SUM(monto) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) ) - e.MontoReserva)/(e.pagosEnganche - (SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) )) ) cuotasSinEspecial,
                    ( ((e.enganchePorcMonto  - (SELECT SUM(monto) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1)- e.MontoReserva)/(e.pagosEnganche - (SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1)) ) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}')) as debePagarSinEspecial,
					IFNULL(e.descuento_porcentual_monto,0) as descuento_porcentual_monto,
                    IFNULL((parqueosExtras * dg.parqueoExtra),0) as montoParqueo ,
                    IFNULL((parqueosExtrasMoto * dg.parqueoExtraMoto),0) as montoParqueoMoto ,
                    IFNULL((bodegasExtras * a.bodega_precio),0) as montoBodega ,
                    a.precio, CONCAT(u.primer_nombre,' ',u.primer_apellido) nombre_vendedor,
                    (SELECT max(fechaPagoRealizado) as fechaPagoRealizado FROM `prograEngancheDetalle`  where idEnganche = e.idEnganche AND pagado = 1)fecha_ultimo_pago,
					CASE
						WHEN ac.estado = 0 THEN (
													SELECT fecha FROM bitacora WHERE comentario LIKE CONCAT('%Se ha dado de baja cliente con DPI ', CONVERT(ac.numeroDpi, CHAR), '%') ORDER BY fecha LIMIT 1
												)
						ELSE 'N/A'
					END as fecha_desistido
					FROM  enganche e
					INNER JOIN  agregarCliente ac ON e.idCliente = ac.idCLiente AND ac.estado in ({$intEstadoDes})
                    INNER JOIN  prograEnganche pe ON e.idEnganche = pe.idEnganche
					INNER JOIN  datosGlobales dg ON e.proyecto = dg.proyecto
					INNER JOIN  torres t ON e.torres = t.noTorre AND dg.idGlobal = t.proyecto
					INNER JOIN  niveles n ON e.nivel = n.noNivel AND t.idTorre = n.idTorre
					INNER JOIN  apartamentos a ON a.apartamento = e.apartamento
					INNER JOIN usuarios u ON e.idVendedor = u.id_usuario
                    WHERE e.proyecto in ({$proyectos}) 
                    AND(
                            primerNombre like '%{$strDatoBuscar}%' OR
                            segundoNombre like '%{$strDatoBuscar}%' OR
                            primerApellido like '%{$strDatoBuscar}%' OR
                            segundoApellido like '%{$strDatoBuscar}%' OR	
                            correoElectronico like '%{$strDatoBuscar}%' OR
                            numeroDpi like '%{$strDatoBuscar}%' OR
                            e.apartamento like '%{$strDatoBuscar}%'
                        )
					{$strFechaConsulta}
                    ORDER BY ordenDes DESC, orden DESC";
    //echo $strQuery;exit();
    $qTmp = $conn ->db_query($strQuery);

//PHP EXCEL//
include ('../class/PHPExcel-master/Classes/PHPExcel.php');

$objPHPExcel = PHPExcel_IOFactory::load('../docsDownload/reporte_estado_cuenta.xlsx');

$objPHPExcel->getProperties()->setCreator("OCA Inter")
							 ->setLastModifiedBy("OCA Inter")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte Cuestionario Inter")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Reportes");
$no=0;
$count=1;

while ($rTmp = $conn->db_fetch_object($qTmp)){
	$count++;
	$no++;
	$porc_atrasado = 1 - ($rTmp->pagado / $rTmp->compromisoMonto);
	$color='';
	$pendPago = 0;
	$countP =0;
	$montoCuota = 0;
	$cuota = 0;
	$division=0;
	$resta = 0;
	$pagosAtrasados=0;
	if($rTmp->dias<=0){
		$color = '04FA13';
	}else{
		if($rTmp->totalPagado >= $rTmp->debePagar){
			$color = '04FA13';
		}else{
			if($rTmp->cuotasSinEspecial >0 && $rTmp->cuotasSinEspecial<$rTmp->cuotas){
				$pagosAtrasados = $rTmp->debePagarSinEspecial - $rTmp->totalPagado;
				$pagosAtrasados = ceil($pagosAtrasados/$rTmp->cuotasSinEspecial);
			}else{
				$pagosAtrasados = $rTmp->debePagar - $rTmp->totalPagado;
				$pagosAtrasados = ceil($pagosAtrasados/$rTmp->cuotas);
			}

			if($rTmp->totalPagado>=$rTmp->debePagar){
				$pendPago = 0;
			}else{
				if($rTmp->cuotasSinEspecial >0 && $rTmp->cuotasSinEspecial<$rTmp->cuotas){
					$pendPago = $rTmp->debePagarSinEspecial - $rTmp->totalPagado;
					$cuota = $rTmp->cuotasSinEspecial;
				}else{
					$pendPago = $rTmp->debePagar - $rTmp->totalPagado;
					$cuota = $rTmp->cuotas;
				}
			}
			
			// while($pendPago > 0){
				
			// 	$division = (ceil($pendPago / $cuota))-1;
			// 	$resta = $division * $cuota;
			// 	$montoCuota = $pendPago - $resta;
			// 	$countP ++;
			// 	$pendPago = $pendPago - $montoCuota;
				
			// }
			
			if($pagosAtrasados<0){
				$pagosAtrasados = 0;
			}
			if($pagosAtrasados <= 0){
				$color = '04FA13';
			}else if($pagosAtrasados> 0 && $pagosAtrasados <=1){
				$color = 'FAF304';
			}else if($pagosAtrasados >1){
				$color = 'FA0404';
			}
		}
	}
	if($pagosAtrasados==0){
		$compromisoMonto=$rTmp->pagado;
		$porc_atrasado=0;
		$cero_treinta=0;
		$treinta_noventa=0;
		$noventa_mas=0;
	}else{
		$compromisoMonto=$rTmp->compromisoMonto;
		$porc_atrasado=$porc_atrasado;
		$cero_treinta=$rTmp->cero_treinta;
		$treinta_noventa=$rTmp->treinta_noventa;
		$noventa_mas=$rTmp->noventa_mas;
	}
	$precio_total = ($rTmp->precio + $rTmp->montoBodega + $rTmp->montoParqueoMoto + $rTmp->montoParqueo) - $rTmp->descuento_porcentual_monto;
	$fecha_desistido = $rTmp->fecha_desistido =='N/A' ? 'N/A' : date("d/m/Y",strtotime($rTmp->fecha_desistido));
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$count,$rTmp->proyecto)
	->setCellValue('B'.$count, $rTmp->client_name)
	->setCellValue('C'.$count, $rTmp->codigo)
	->setCellValue('D'.$count, $rTmp->apartamento)
	->setCellValue('E'.$count, $precio_total)
	->setCellValue('F'.$count, $rTmp->nombre_vendedor)
	->setCellValue('G'.$count, $rTmp->fecha_ultimo_pago)
	->setCellValue('H'.$count, $pagosAtrasados)
	->setCellValue('J'.$count, $rTmp->totalEnganche + $rTmp->MontoReserva)
	->setCellValue('K'.$count, $compromisoMonto)
	->setCellValue('L'.$count, $rTmp->pagado)
	->setCellValue('M'.$count, $porc_atrasado)
	->setCellValue('N'.$count, $cero_treinta)
	->setCellValue('O'.$count, $treinta_noventa)
	->setCellValue('P'.$count, $noventa_mas)
	->setCellValue('Q'.$count, $fecha_desistido);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$count)->applyFromArray(
		array(
			'fill' => array(
				'type'  => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
						'rgb' => $color
				)
			)
		)

	);
}  
foreach(range('A','O') as $columnID)
{
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}  
$styleArray = array(
    'borders' => array(
        'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		  )
    ),
);
 
$objPHPExcel->getActiveSheet()->getStyle('A2:O'.$count)->applyFromArray($styleArray);
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('REPORTE');
$objPHPExcel->setActiveSheetIndex(0);

	$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	//$writer = new PHPExcel_Writer_Excel5($objPHPExcel);

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Reportes'.date('dmYHis').'.xlsx"');
	header('Cache-Control: max-age=0');
	$writer->save('php://output');
///// FIN //

$conn->db_close();
?>
<?php

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
$strFechaInicial = isset($_POST['fechaInicial']) && $_POST['fechaInicial']!='' ? date('Y-m-d',strtotime($_POST['fechaInicial'])):'';
$strFechafinal = isset($_POST['fechaFinal']) && $_POST['fechaFinal']!='' ? date('Y-m-d',strtotime($_POST['fechaFinal'])):'';
$strIncluir = isset($_POST['incluirBsc']) ? intval($_POST['incluirBsc']):0;
$strIncluirConsulta="";

if($strProyectoBuscar!='' && $strProyectoBuscar!=0){
	$strFechaConsulta.= " AND dg.idGlobal ='{$strProyectoBuscar}' ";
	$strFechaConsultaR.= " AND dg.idGlobal ='{$strProyectoBuscar}' ";
	
}
if($strFechaInicial!='' && $strFechaInicial!=0){
	$strFechaConsulta.= " AND ped.fechaPagoRealizado >='{$strFechaInicial}' ";
	$strFechaConsultaR.= " AND e.fechaPagoReserva >='{$strFechaInicial}' ";
}
if($strFechafinal!='' && $strFechafinal!=0){
	$strFechaConsulta.= " AND ped.fechaPagoRealizado <='{$strFechafinal}' ";
	$strFechaConsultaR.= " AND e.fechaPagoReserva <='{$strFechafinal}' ";
}
if($strIncluir < 2){
	$strIncluirConsulta = " AND ped.validado = '{$strIncluir}'";
	$strIncluirConsultaR = " AND e.validado = '{$strIncluir}'";
}
$strQuery = "SELECT ped.fechaPagoRealizado, IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
					IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name, 
					e.proyecto,e.apartamento, bancoDeposito,CONCAT('ENGANCHE ',noPago,'/',e.pagosEnganche) as descripcion,noDeposito,montoPagado,if(noRecibo !='',noRecibo,ped.idDetalle) as noRecibo					
					FROM prograEngancheDetalle ped
					INNER JOIN enganche e ON e.idEnganche = ped.idEnganche
					INNER JOIN  agregarCliente ac ON e.idCliente = ac.idCliente
					INNER JOIN  datosGlobales dg ON e.proyecto = dg.proyecto
                    WHERE pagado = 1 
					{$strFechaConsulta}
					{$strIncluirConsulta}
					UNION
					SELECT fechaPagoReserva as fechaPagoRealizado,IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
					IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,e.proyecto,e.apartamento, bancoDepositoReserva as bancoDeposito,CONCAT('PAGO RESERVA') as descripcion,noDepositoReserva as noDeposito,e.MontoReserva as montoPagado,if(noReciboReserva !='',noReciboReserva,CONCAT(e.idEnganche,e.idCliente,e.torres)) as noRecibo
                    FROM enganche e
					INNER JOIN  agregarCliente ac ON e.idCliente = ac.idCliente and ac.estado=1
					INNER JOIN  datosGlobales dg ON e.proyecto = dg.proyecto
                    where formaPago is not null  
                    {$strFechaConsultaR}
					UNION
					SELECT e.fechaPagoReserva as fechaPagoRealizado,nombreCompleto  as client_name,e.proyecto,e.apartamento, e.bacoDepositoReserva as bancoDeposito,CONCAT('ADELANTO RESERVA') as descripcion,e.noDepositoReserva as noDeposito,e.MontoReserva as montoPagado,if(e.noReciboReserva !='',e.noReciboReserva,CONCAT(e.idReserva,e.idReserva,e.torre)) as noRecibo
                    FROM reservaApartamento e
					INNER JOIN  datosGlobales dg ON e.proyecto = dg.proyecto
                    LEFT JOIN enganche en ON (select agc.idCliente FROM enganche eng INNER JOIN agregarCliente agc ON eng.idCliente = agc.idCliente and agc.estado = 1 WHERE eng.apartamento = e.apartamento ORDER BY eng.idEnganche DESC LIMIT 1) = en.idCLiente AND e.apartamento = en.apartamento     
                    WHERE e.estado = 1
                    AND (en.formaPago is null or e.noDepositoReserva != en.noDepositoReserva )
                    {$strFechaConsultaR}
					{$strIncluirConsultaR}
					UNION
					SELECT ped.fechaPagoRealizado, IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
					IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name, 
					e.proyecto,e.apartamento, bancoDeposito,CONCAT('ENGANCHE ',noPago,'/',e.pagosEnganche) as descripcion,noDeposito,montoPagado,if(noRecibo !='',noRecibo,CONCAT(ped.idCobro,ped.idCobro,ped.noPago)) as noRecibo					
					FROM cobrosExtras ped
					INNER JOIN enganche e ON e.idEnganche = ped.idEnganche
					INNER JOIN  agregarCliente ac ON e.idCliente = ac.idCliente
					INNER JOIN  datosGlobales dg ON e.proyecto = dg.proyecto
					WHERE 1=1
					{$strFechaConsulta}
					{$strIncluirConsulta}
                    ORDER BY fechaPagoRealizado ASC";
    //echo $strQuery;
	//exit();
    $qTmp = $conn ->db_query($strQuery);

//PHP EXCEL//
include ('../class/PHPExcel-master/Classes/PHPExcel.php');

$objPHPExcel = PHPExcel_IOFactory::load('../docsDownload/reporte_pagos_avalia.xlsx');

$objPHPExcel->getProperties()->setCreator("OCA Inter")
							 ->setLastModifiedBy("OCA Inter")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte Cuestionario Inter")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Reportes");
$no=0;
$count=2;

while ($rTmp = $conn->db_fetch_object($qTmp)){
	$count++;
	$no++;
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$count,date("d/m/Y",strtotime($rTmp->fechaPagoRealizado)))
	->setCellValue('B'.$count, $rTmp->client_name)
	->setCellValue('C'.$count, $rTmp->proyecto)
	->setCellValue('D'.$count, $rTmp->apartamento)
	->setCellValue('E'.$count, $rTmp->bancoDeposito)
	->setCellValue('F'.$count, $rTmp->descripcion)
	->setCellValue('G'.$count, $rTmp->noDeposito.' ')
	->setCellValue('H'.$count, $rTmp->noRecibo.' ')
	->setCellValue('I'.$count, $rTmp->montoPagado);
}  
foreach(range('A','I') as $columnID)
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
 
$objPHPExcel->getActiveSheet()->getStyle('A3:I'.$count)->applyFromArray($styleArray);
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('REPORTE');
$objPHPExcel->setActiveSheetIndex(0);

	$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	//$writer = new PHPExcel_Writer_Excel5($objPHPExcel);

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="ReportesPagos'.date('dmYHis').'.xlsx"');
	header('Cache-Control: max-age=0');
	$writer->save('php://output');
///// FIN //

$conn->db_close();
?>
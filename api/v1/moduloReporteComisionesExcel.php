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
$countP=0;

$countP=0;
//Buscar
$intVendedor= isset($_POST['vendedorCom']) ? $_POST['vendedorCom']:0;
$intProyecto= isset($_POST['proyectoBsc']) ? $_POST['proyectoBsc']:0;
$intTorre= isset($_POST['torreBsc']) ? $_POST['torreBsc']:0;
$intNivel= isset($_POST['nivelBsc']) ? $_POST['nivelBsc']:0;
$intApartamento= isset($_POST['apartamentoBsc']) ? $_POST['apartamentoBsc']:0;

$where=' WHERE 1=1';

if($intVendedor!=0 && $intVendedor!=10){
	$where.=" AND e.idVendedor ={$intVendedor}";
}
if($intProyecto!=0){
	$where.=" AND dg.idGlobal={$intProyecto}";
}
if($intTorre!=0){
	$where.=" AND t.idTorre={$intTorre}";
}
if($intNivel!=0){
	$where.=" AND n.idNivel={$intNivel}";
}
if($intApartamento!=0){
	$where.=" AND e.apartamento='{$intApartamento}'";
}
if($intVendedor==10){
	$strQuery = "SELECT dg.proyecto,t.noTorre,n.noNivel,a.apartamento,
	'Pedro Arguello'  as vendedor,porcentajeComision,aCom.precioComision,
	( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=1
                    and b.descripcion = 'Director de Ventas'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_1,
                        	( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=2
                    and b.descripcion = 'Director de Ventas'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_2,
                        	( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=3
                    and b.descripcion = 'Director de Ventas'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_3
	FROM enganche e 
	INNER JOIN usuarios u on e.idVendedor = u.id_usuario
	INNER JOIN datosGlobales dg on e.proyecto = dg.proyecto
	INNER JOIN torres t on e.torres = t.noTorre
	INNER JOIN niveles n on e.nivel = n.noNivel
	INNER JOIN apartamentos a on e.apartamento = a.apartamento
	INNER JOIN agregarCliente ac on e.idCliente = ac.idCliente and ac.estado = 1
	INNER JOIN catTipoComision ctc on trim(ac.tipoComision) = trim(ctc.descripcion) and dg.idGlobal = ctc.proyecto
	INNER JOIN catPagaComision cpc	on ctc.idTipoComision = cpc.idTipoComision and cpc.descripcion = 'Director de Ventas'
	INNER JOIN catFormaPagoComisiones catpc on cpc.idPagaComision = catpc.idPagaComision
	INNER JOIN apartamentoComisiones aCom on e.idEnganche = aCom.idEnganche
	{$where}
	GROUP BY e.idEnganche
	ORDER BY proyecto,noTorre,noNivel,apartamento";
}else if($intVendedor!=10 && $intVendedor!=0){
	$strQuery = "SELECT dg.proyecto,t.noTorre,n.noNivel,a.apartamento,
	CONCAT(IFNULL(CONCAT(primer_nombre,' '),''),IFNULL(CONCAT(segundo_nombre,' '),''),IFNULL(CONCAT(tercer_nombre,' '),''),IFNULL(CONCAT(primer_apellido,' '),''),
					IFNULL(CONCAT(segundo_apellido,' '),''),IFNULL(CONCAT(apellido_casada,' '),''))  as vendedor,porcentajeComision,aCom.precioComision, 
					( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=1
                    and b.descripcion = 'Vendedores'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_1,
                        	( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=2
                    and b.descripcion = 'Vendedores'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_2,
                        	( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=3
                    and b.descripcion = 'Vendedores'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_3
	FROM enganche e 
	INNER JOIN usuarios u on e.idVendedor = u.id_usuario
	INNER JOIN datosGlobales dg on e.proyecto = dg.proyecto
	INNER JOIN torres t on e.torres = t.noTorre
	INNER JOIN niveles n on e.nivel = n.noNivel
	INNER JOIN apartamentos a on e.apartamento = a.apartamento
	INNER JOIN agregarCliente ac on e.idCliente = ac.idCliente and ac.estado = 1
	INNER JOIN catTipoComision ctc on trim(ac.tipoComision) = trim(ctc.descripcion) and dg.idGlobal = ctc.proyecto
	INNER JOIN catPagaComision cpc	on ctc.idTipoComision = cpc.idTipoComision and cpc.descripcion = 'Vendedores'
	INNER JOIN catFormaPagoComisiones catpc on cpc.idPagaComision = catpc.idPagaComision
	INNER JOIN apartamentoComisiones aCom on e.idEnganche = aCom.idEnganche     
	{$where}                                                                                 
	GROUP BY e.idEnganche
	ORDER BY proyecto,noTorre,noNivel,apartamento";
}
else{
	$strQuery = "SELECT dg.proyecto,t.noTorre,n.noNivel,a.apartamento,
	CONCAT(IFNULL(CONCAT(primer_nombre,' '),''),IFNULL(CONCAT(segundo_nombre,' '),''),IFNULL(CONCAT(tercer_nombre,' '),''),IFNULL(CONCAT(primer_apellido,' '),''),
					IFNULL(CONCAT(segundo_apellido,' '),''),IFNULL(CONCAT(apellido_casada,' '),''))  as vendedor,porcentajeComision,aCom.precioComision,
					( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=1
                    and b.descripcion = 'Vendedores'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_1,
                        	( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=2
                    and b.descripcion = 'Vendedores'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_2,
                        	( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=3
                    and b.descripcion = 'Vendedores'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_3
	FROM enganche e 
	INNER JOIN usuarios u on e.idVendedor = u.id_usuario
	INNER JOIN datosGlobales dg on e.proyecto = dg.proyecto
	INNER JOIN torres t on e.torres = t.noTorre
	INNER JOIN niveles n on e.nivel = n.noNivel
	INNER JOIN apartamentos a on e.apartamento = a.apartamento
	INNER JOIN agregarCliente ac on e.idCliente = ac.idCliente and ac.estado = 1
	INNER JOIN catTipoComision ctc on trim(ac.tipoComision) = trim(ctc.descripcion) and dg.idGlobal = ctc.proyecto
	INNER JOIN catPagaComision cpc	on ctc.idTipoComision = cpc.idTipoComision and cpc.descripcion = 'Vendedores'
	INNER JOIN catFormaPagoComisiones catpc on cpc.idPagaComision = catpc.idPagaComision
	INNER JOIN apartamentoComisiones aCom on e.idEnganche = aCom.idEnganche     
	{$where}                                                                                 
	GROUP BY e.idEnganche
	UNION
	SELECT dg.proyecto,t.noTorre,n.noNivel,a.apartamento,
	'Pedro Arguello'  as vendedor,porcentajeComision,aCom.precioComision, 
	( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=1
                    and b.descripcion = 'Director de Ventas'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_1,
                        	( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=2
                    and b.descripcion = 'Director de Ventas'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_2,
                        	( SELECT ifnull(SUM(monto),0) as monto
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision ep ON a.idFormaPagoComisiones = ep.idFormaPagoComisiones
                    WHERE trim(c.descripcion) = trim(ac.tipoComision)
                    and c.proyecto = dg.idGlobal
                    and b.porcentajeComision >0
          			and b.porcentajeComision >0
                    and a.noPago=3
                    and b.descripcion = 'Director de Ventas'
         			and ep.idEnganche=e.idEnganche
                    group by a.idFormaPagoComisiones )pagado_3
	FROM enganche e 
	INNER JOIN usuarios u on e.idVendedor = u.id_usuario
	INNER JOIN datosGlobales dg on e.proyecto = dg.proyecto
	INNER JOIN torres t on e.torres = t.noTorre
	INNER JOIN niveles n on e.nivel = n.noNivel
	INNER JOIN apartamentos a on e.apartamento = a.apartamento
	INNER JOIN agregarCliente ac on e.idCliente = ac.idCliente and ac.estado = 1
	INNER JOIN catTipoComision ctc on trim(ac.tipoComision) = trim(ctc.descripcion) and dg.idGlobal = ctc.proyecto
	INNER JOIN catPagaComision cpc	on ctc.idTipoComision = cpc.idTipoComision and cpc.descripcion = 'Director de Ventas'
	INNER JOIN catFormaPagoComisiones catpc on cpc.idPagaComision = catpc.idPagaComision
	INNER JOIN apartamentoComisiones aCom on e.idEnganche = aCom.idEnganche
	{$where}
	GROUP BY e.idEnganche
	ORDER BY proyecto,noTorre,noNivel,apartamento";
}


	//exit($strQuery);
    $qTmp = $conn ->db_query($strQuery);

//PHP EXCEL//
include ('../class/PHPExcel-master/Classes/PHPExcel.php');

$objPHPExcel = PHPExcel_IOFactory::load('../docsDownload/reporte_pagos_comisiones_avalia.xlsx');

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
	if($rTmp->porcentajeComision>0){
		$count++;
		$no++;
		$totalComision=round($rTmp->precioComision*($rTmp->porcentajeComision/100));
		$totalPagado = $rTmp->pagado_1+$rTmp->pagado_2+$rTmp->pagado_3;
		$saldoPendiente = $totalComision - $totalPagado;
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$count, $rTmp->proyecto)
		->setCellValue('B'.$count, $rTmp->noTorre)
		->setCellValue('C'.$count, $rTmp->noNivel)
		->setCellValue('D'.$count, $rTmp->apartamento)
		->setCellValue('E'.$count, $rTmp->vendedor)
		->setCellValue('F'.$count, $rTmp->precioComision)
		->setCellValue('G'.$count, ($rTmp->porcentajeComision/100))
		->setCellValue('H'.$count, $totalComision)
		->setCellValue('I'.$count, $rTmp->pagado_1)
		->setCellValue('J'.$count, $rTmp->pagado_2)
		->setCellValue('K'.$count, $rTmp->pagado_3)
		->setCellValue('L'.$count, $saldoPendiente);
	}
	
}  
foreach(range('A','L') as $columnID)
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
 
$objPHPExcel->getActiveSheet()->getStyle('A3:L'.$count)->applyFromArray($styleArray);
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('REPORTE');
$objPHPExcel->setActiveSheetIndex(0);

	$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	//$writer = new PHPExcel_Writer_Excel5($objPHPExcel);

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="ReportesPagosComision'.date('dmYHis').'.xlsx"');
	header('Cache-Control: max-age=0');
	$writer->save('php://output');
///// FIN //

$conn->db_close();
?>
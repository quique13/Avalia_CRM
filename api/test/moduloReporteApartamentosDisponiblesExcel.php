<?php
//set_time_limit(1000);
ini_set('memory_limit', '-1');
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
$intEstadoApto= isset($_POST['estadoApto']) ? intval($_POST['estadoApto']):0;
if($intEstadoApto!=0){
	$strFechaConsulta = " AND a.estado ='{$intEstadoApto}'";
}


if($strProyectoBuscar!='' && $strProyectoBuscar!=0){
	$strFechaConsulta.= " AND a.idProyecto ='{$strProyectoBuscar}' ";
}
if($strTorreBuscar!='' && $strTorreBuscar!=0){
	$strFechaConsulta.= " AND a.idTorre ='{$strTorreBuscar}' ";
}
if($strNivelBuscar!='' && $strNivelBuscar!=0){
	$strFechaConsulta.= " AND a.idNivel ='{$strNivelBuscar}' ";
}

 
$strQuery = "SELECT dg.proyecto,noTorre,noNivel,a.apartamento,a.precio,ce.estado
					FROM  apartamentos a
					INNER JOIN  datosGlobales dg ON a.idProyecto = dg.idGlobal
					INNER JOIN  torres t ON a.idTorre = t.idTorre
					INNER JOIN  niveles n ON a.idNivel = n.idNivel 
					INNER JOIN catEstadoApartamento ce ON a.estado = ce.idCatEstado
					WHERE 1=1
					{$strFechaConsulta}
					ORDER BY dg.proyecto,t.noTorre,n.noNivel,a.apartamento";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);

	

//PHP EXCEL//
include ('../class/PHPExcel-master/Classes/PHPExcel.php');

$objPHPExcel = PHPExcel_IOFactory::load('../docsDownload/reporte_apartamento_disponibilidad.xlsx');

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

	$strQueryEn = "SELECT
						e.descuento_porcentual_monto,
						e.fechaPagoInicial,
						(
					SELECT
						ifnull((SUM(CASE WHEN accion = 'adicionar' AND enganche = 0 THEN monto ELSE 0 END) - SUM(CASE WHEN accion = 'descontar' AND enganche = 0 THEN monto ELSE 0 END)),
						0) AS contracargo
					FROM
						contrapagos cp
					WHERE
						cp.idEnganche = e.idEnganche ) contracargo,
									(
					SELECT
						ifnull((SUM(CASE WHEN accion = 'sumar' AND enganche = 1 THEN monto ELSE 0 END) - SUM(CASE WHEN accion = 'restar' AND enganche = 1 THEN monto ELSE 0 END)),
						0) AS contracargo
					FROM
						contrapagos cp
					WHERE
						cp.idEnganche = e.idEnganche ) contracargoEnganche,
					((dg.cambioDolar * dg.parqueoExtra)* e.parqueosExtras) AS parqueoExtraMonto,
					((dg.cambioDolar * a.bodega_precio) * bodegasExtras) AS bodegaPrecioMonto
				FROM
					enganche e
				INNER JOIN agregarCliente ac ON
					e.idCliente = ac.idCliente
					AND ac.estado = 1
				LEFT JOIN datosGlobales dg ON
					e.proyecto = dg.proyecto
				LEFT JOIN apartamentos a ON
					e.apartamento = a.apartamento
				WHERE 
					e.apartamento = '{$rTmp->apartamento}'";
	$qTmpEn = $conn ->db_query($strQueryEn);
	$rTmpEn = $conn->db_fetch_object($qTmpEn);
	$fecha = $rTmpEn->fechaPagoInicial!='' ? date('d-m-Y',strtotime($rTmpEn->fechaPagoInicial)) : '' ;
	$precio = ($rTmpEn->contracargo + $rTmpEn->bodegaPrecioMonto + $rTmpEn->parqueoExtraMonto +  $rTmp->precio - $rTmpEn->descuento_porcentual_monto);
	$count++;
	$no++;
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$count,$no)
	->setCellValue('B'.$count,$rTmp->proyecto)
	->setCellValue('C'.$count, $rTmp->noTorre)
	->setCellValue('D'.$count, $rTmp->noNivel)
	->setCellValue('E'.$count, $rTmp->apartamento)
	->setCellValue('F'.$count, $precio)
	->setCellValue('G'.$count, $rTmp->estado )
	->setCellValue('H'.$count, $fecha);
}  
foreach(range('A','H') as $columnID)
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
 
$objPHPExcel->getActiveSheet()->getStyle('A2:H'.$count)->applyFromArray($styleArray);
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
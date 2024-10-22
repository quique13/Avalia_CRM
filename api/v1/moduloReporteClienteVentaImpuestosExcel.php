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
$strProyectoBuscar= isset($_POST['proyectoBsc']) ? trim($_POST['proyectoBsc']):'';
$strTorreBuscar= isset($_POST['torreBsc']) ? trim($_POST['torreBsc']):'';
$strNivelBuscar= isset($_POST['nivelBsc']) ? trim($_POST['nivelBsc']):'';

if($strProyectoBuscar!='' && $strProyectoBuscar!=0){
	$strFechaConsulta.= " AND a.idProyecto ='{$strProyectoBuscar}' ";
}
if($strTorreBuscar!='' && $strTorreBuscar!=0){
	$strFechaConsulta.= " AND a.idTorre ='{$strTorreBuscar}' ";
}
if($strNivelBuscar!='' && $strNivelBuscar!=0){
	$strFechaConsulta.= " AND a.idNivel ='{$strNivelBuscar}' ";
}

 
$strQuery = "SELECT ac.codigo,e.proyecto,
IF(tipoCliente='individual',
CONCAT(IFNULL(CONCAT(primerNombre,
' '),
''),
IFNULL(CONCAT(segundoNombre,
' '),
''),
IFNULL(CONCAT(tercerNombre,
' '),
''),
IFNULL(CONCAT(primerApellido,
' '),
''),
IFNULL(CONCAT(segundoApellido,
' '),
''),
IFNULL(CONCAT(apellidoCasada,
' '),
'')),
nombre_sa)as client_name,e.apartamento,dg.porcentajeFacturacion as porcion_facturar, (100 - dg.porcentajeFacturacion) as porcion_accion,
e.descuento_porcentual_monto,(dg.cambioDolar * a.precio) as precio,
((dg.cambioDolar * dg.parqueoExtra)*e.parqueosExtras) as parqueoExtra,((dg.cambioDolar * a.bodega_precio) * bodegasExtras) as bodegaPrecio,
0 as  contracargo
FROM enganche e 
INNER JOIN agregarCliente ac ON e.idCliente=ac.idCLiente AND ac.estado in(1)
INNER JOIN apartamentos a ON e.apartamento = a.apartamento
INNER JOIN datosGlobales dg ON a.idProyecto = dg.idGlobal
LEFT JOIN usuarios u ON e.idVendedor = u.id_usuario
WHERE 1=1
{$strFechaConsulta}
ORDER BY e.proyecto,e.torres,e.nivel,e.apartamento";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);

//PHP EXCEL//
include ('../class/PHPExcel-master/Classes/PHPExcel.php');

$objPHPExcel = PHPExcel_IOFactory::load('../docsDownload/formato_cliente_venta_impuestos.xlsx');

$objPHPExcel->getProperties()->setCreator("OCA Inter")
							 ->setLastModifiedBy("OCA Inter")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte Cuestionario Inter")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Reportes");
$no=0;
$count=1;
$total_apartamento = 0;
$porcion_facturar = 0;
$porcion_accion = 0;
$iva = 0;
$timbres = 0;
$porcentaje_facturar = 0;
$accion = 0;

while ($rTmp = $conn->db_fetch_object($qTmp)){

	$porcentaje_facturar = $rTmp->porcion_facturar;
	$porcentaje_accion = $rTmp->porcion_accion;

	$precio_venta = $rTmp->precio + $rTmp->bodegaPrecio + $rTmp->parqueoExtra + $rTmp->contracargo - $rTmp->descuento_porcentual_monto; 
	$porcion_facturar = ($rTmp->porcion_facturar/100) * $precio_venta ;
	$porcion_accion = ($rTmp->porcion_accion/100) * $precio_venta ;
	$iva =  $porcion_facturar - ($porcion_facturar/1.12) ;
	$timbres = $porcion_accion - ($porcion_accion/1.03) ;


	$count++;
	$no++;
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$count,$no)
	->setCellValue('B'.$count,$rTmp->proyecto)
	->setCellValue('C'.$count, $rTmp->apartamento)
	->setCellValue('D'.$count, $rTmp->client_name)
	->setCellValue('E'.$count, $precio_venta)
	->setCellValue('F'.$count, $porcion_facturar)
	->setCellValue('G'.$count, $porcion_accion)
	->setCellValue('H'.$count, $iva)
	->setCellValue('I'.$count, $timbres);
	
}  
$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('L2',($porcentaje_facturar/100))
	->setCellValue('L3',($porcentaje_accion/100))
	->setCellValue('L4',(12/100))
	->setCellValue('L5',(3/100));

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
 
$objPHPExcel->getActiveSheet()->getStyle('A2:I'.$count)->applyFromArray($styleArray);
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
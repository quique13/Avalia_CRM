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
nombre_sa)as client_name,e.apartamento,ac.correoElectronico,ac.telefono,
CONCAT(IFNULL(CONCAT(primer_nombre),
''),
IFNULL(CONCAT(' ',segundo_nombre),
''),
IFNULL(CONCAT(' ',tercer_nombre),
''),
IFNULL(CONCAT(' ',primer_apellido),
''),
IFNULL(CONCAT(' ',segundo_apellido),
''),
IFNULL(CONCAT(' ',apellido_casada),
'')) as vendedor
FROM enganche e 
INNER JOIN agregarCliente ac ON e.idCliente=ac.idCLiente AND ac.estado in(1)
INNER JOIN apartamentos a ON e.apartamento = a.apartamento
LEFT JOIN usuarios u ON e.idVendedor = u.id_usuario
WHERE 1=1
{$strFechaConsulta}
ORDER BY e.proyecto,e.torres,e.nivel,e.apartamento";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);

//PHP EXCEL//
include ('../class/PHPExcel-master/Classes/PHPExcel.php');

$objPHPExcel = PHPExcel_IOFactory::load('../docsDownload/formato_cliente_venta.xlsx');

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
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$count,$no)
	->setCellValue('B'.$count,$rTmp->proyecto)
	->setCellValue('C'.$count, $rTmp->client_name)
	->setCellValue('D'.$count, $rTmp->apartamento)
	->setCellValue('E'.$count, $rTmp->correoElectronico)
	->setCellValue('F'.$count, $rTmp->telefono)
	->setCellValue('G'.$count, $rTmp->vendedor);
	
}  
foreach(range('A','G') as $columnID)
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
 
$objPHPExcel->getActiveSheet()->getStyle('A2:G'.$count)->applyFromArray($styleArray);
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
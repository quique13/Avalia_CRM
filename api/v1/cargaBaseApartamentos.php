<?php
include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";
include('progressCarga.php');

$conn = new dbClassMysql();
$func = new Functions();

$func->getHeaders();
$res = array(
    "arr"=> true,
    "mss"=> "Error 404"
);
session_name("inmobiliaria");
session_start();
$id_usuario=$_SESSION['id_usuario'];
// echo $progres.'<br><br><br><br><br>';
// sleep(1);
/** Set default timezone (will throw a notice otherwise) */
require_once "../class/PHPExcel-master/Classes/PHPExcel/IOFactory.php";
$inputFileName = $_FILES['archivo']['tmp_name'];
//$inputFileName = 'ARCH3.xlsx';
//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch (Exception $e) {
    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
    . '": ' . $e->getMessage());
}
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$row = 2;
$continuo=1;
$en_blanco=0;
$contadorRegInsertados=0;
$totalReg=0;
while ($en_blanco<=5) //tiene conflico con los controles...secuenciales
{
	$correlativo++;
	//  Read a row of data into an array
	$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row);
	$proyecto=$rowData[0][0];
	$torre=$rowData[0][1];
	$nivel=$rowData[0][2];
	$apartamento=$rowData[0][3];
	$codigo=$rowData[0][4];
	$habitaciones=$rowData[0][5];
	$precio=$rowData[0][6];
	$iusi=$rowData[0][7];
	$mts=$rowData[0][8];
	$mts_jardin=$rowData[0][9];
	$mts_bodega=$rowData[0][10];
	$parqueo=$rowData[0][11];
	$parqueoMoto=$rowData[0][12];
	$bodegaPrecio=$rowData[0][13];
    $intIdApartamento=0;

  if($proyecto=='' OR empty($proyecto) OR is_null($proyecto) ){
	$en_blanco++;
    //echo $en_blanco;
  }else{
    $strQuery = "   INSERT  apartamentos (idApartamento, idProyecto, idTorre, idNivel, apartamento, 
                        codigo, estado, cuartos, precio, iusi_seguro, 
                        sqmts, jardin_mts, bodega_mts, parqueo, parqueo_moto, 
                        bodega_precio, idUsuarioCreacion, suspendido)
                    VALUES ({$intIdApartamento},(SELECT idGlobal FROM  datosGlobales WHERE proyecto = '{$proyecto}'),
                    (SELECT idTorre FROM  torres t 
                    INNER JOIN  datosGlobales p ON t.proyecto = p.idGlobal  
                    WHERE t.noTorre = {$torre}
                    AND p.proyecto = '{$proyecto}'),
                    (SELECT idNivel FROM  niveles n 
                    INNER JOIN  torres t ON n.idTorre = t.idTorre
                    INNER JOIN  datosGlobales p ON t.proyecto = p.idGlobal  
                    WHERE noNivel= {$nivel}
                    AND t.noTorre = {$torre}
                    AND p.proyecto = '{$proyecto}'),
                    '{$apartamento}','{$codigo}',1,{$habitaciones},{$precio},{$iusi},{$mts},{$mts_jardin},{$mts_bodega},{$parqueo},{$parqueoMoto},
                    {$bodegaPrecio},{$id_usuario},0)";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
            $contadorRegInsertados ++;
            //$Total_registros=$totalReg;
           //$porcentaje = $contadorRegInsertados * 100 / $Total_registros; //saco mi valor en porcentaje
        // echo "<script>callprogress(".round($porcentaje).",".round($contadorRegInsertados).",".$Total_registros.",' apartamentos cargados de ','progress-bar-danger','progress-bar-danger')</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
	  	// flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
	  	// ob_flush();
    }
  }
    $row++;
}
$res = array(
    "err" => false,
    "mss" => $contadorRegInsertados,
    "apartamento" => $arr
);
$conn->db_close();
echo json_encode($res);
?>

<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
/*
 * PHP Excel - Read a simple 2007 XLSX Excel file
 */

/** Set default timezone (will throw a notice otherwise) */
date_default_timezone_set('America/Guatemala');

require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';
$inputFileName = 'ARCH3.xlsx';

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch (Exception $e) {
    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) 
    . '": ' . $e->getMessage());
}

//  Get worksheet dimensions
$dia='';
$mes='';
$anio='';
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$arrayFechasHora=array(3,5,7,36);
$arrayFechas=array(20,43);
$arrayMeses=array(1=>'ene', 2=>'feb', 3=>'mar', 4=>'abr', 5=>'may', 6=>'jun', 7=>'jul', 8=>'ago', 9=>'sep', 10=>'oct', 11=>'nov', 12=>'dic');
$arrayMesesIngles=array(1=>'jan', 2=>'feb', 3=>'mar', 4=>'apr', 5=>'may', 6=>'jun', 7=>'jul', 8=>'agu', 9=>'sep', 10=>'oct', 11=>'nov', 12=>'dec');
//  Loop through each row of the worksheet in turn
echo '<table border="1">' . "\n";
for ($row = 1; $row <= $highestRow; ++$row) {
	
	echo '<tr>' . "\n";
    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, 
    NULL, TRUE, FALSE);
    foreach($rowData[0] as $k=>$v)
	{
		if(in_array($k,$arrayFechasHora))
			$cell_value = PHPExcel_Style_NumberFormat::toFormattedString($v, 'yyyy-mm-dd hh:mm');
		elseif(in_array($k,$arrayFechas))
		{
			 list($dia,$mes,$anio)=explode('-',$v);
			 $key = array_search($mes, $arrayMeses);
			 $key2 = array_search($mes, $arrayMesesIngles);
    		 	if ($key !== false) {
				 	$v=str_replace($mes,$key,$v);
					$cell_value = date('Y-m-d',strtotime($v));
				}
				elseif ($key2 !== false) {
				 	$v=str_replace($mes,$key2,$v);
					$cell_value = date('Y-m-d',strtotime($v));
				}
				else
					$cell_value = PHPExcel_Style_NumberFormat::toFormattedString($v, 'yyyy-mm-dd');
		}
		else
			$cell_value=$v;
		echo '<td>' . $cell_value . '</td>' . "\n";
	}
	echo '</tr>' . "\n";	
}
echo '</table>' . "\n";



/*
require_once '../Classes/PHPExcel.php';
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);

$objPHPExcel = $objReader->load("Archivo.xlsx");
$objWorksheet = $objPHPExcel->getActiveSheet();

$highestRow = $objWorksheet->getHighestRow(); 
$highestColumn = $objWorksheet->getHighestColumn(); 

$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 

echo '<table border="1">' . "\n";
for ($row = 1; $row <= $highestRow; ++$row) {
  echo '<tr>' . "\n";

  for ($col = 0; $col <= $highestColumnIndex; ++$col) {
	  $cell = $objWorksheet->getCellByColumnAndRow($col, $row);
	  
	  if($col==3)
	  $cell_value = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'dd/mm/yyyy hh:mm');
	  else
	  $cell_value=$cell->getCalculatedValue();
	  
    echo '<td>'.$cell_value.'</td>' . "\n";
	
  }

  echo '</tr>' . "\n";
}
echo '</table>' . "\n";
*/

?>



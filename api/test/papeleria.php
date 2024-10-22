<html>
 <head>
  <title>PHP.pdf</title>
 </head>
 <body>

<?php

ini_set( "display_errors", 0);
header('Access-Control-Allow-Origin: *');

header("Content-type:application/pdf");
header("Content-Disposition:inline;filename=".normalizarNombre($_GET[nombre_cliente]).'.pdf');

include("numeroaletras.php");
$convertidor = new EnLetras();
// $convertidor->substituir_un_mil_por_mil = true;


require('./fpdf/fpdf.php');

include("../conexion/conexion_mysql.php");
$con=conexion();
$con27=conexion_27();
//$mysqli=conexion();

require('./fpdm/fpdm.php');
require('./vendor/autoload.php');
//use mikehaertl\pdftk\Pdf;



	$control=$_GET[control];


  $sql_preca = mysqli_query($con, "select no_remesa, concat(v.nombres,' ',v.apellido_paterno,' ',v.apellido_materno) as nombre_completo, v.numero_tc,cb.CODIGO,
  monto_aceptado,plazo,nombre_banco,v.numero_cuenta,tipo_cuenta,v.dpi,v.nit,v.email, v.cuota 
  FROM venta v
  INNER JOIN carga_base cb
  ON cb.control=v.control
  WHERE v.control='".$control."'");
  $data = mysqli_fetch_array($sql_preca);


  $meses = array(1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

  if($_GET[opcion]==1){
    $pdf_contrato = new mikehaertl\pdftk\Pdf('./plantilla_carta_final.pdf');
  }else if($_GET[opcion]==2){
    $pdf_contrato = new mikehaertl\pdftk\Pdf('./pdf_carta_formato_v2.pdf');
  }


  $ahorro=0;
  $monetaria=0;
  if($data['tipo_cuenta']=='ahorro')
  {
    $ahorro='Opción2';
  }else if($data['tipo_cuenta']=='monetaria'){
    $monetaria='Opción1';
  }
  if($data['no_remesa']==0){
    $CODIGO='N/A';
  }else{
    $CODIGO=$data['CODIGO'];
  }
  if($data['tipo_cuenta']!='cheque'){
    $fields = array(

      'dia_creado'=>date("d"),
      'mes_creado'=>nombre_mes(date("n")),
      'anio_creado'=>date("Y"),
      'atencion_a'=>$data['nombre_completo'],
      'no_tarjeta'=>$data['numero_tc'],
      'monto'=>number_format($data['monto_aceptado'],2),
      'moneda'=>'QUETZALES',
      'plazo'=>$data['plazo'],
      'no_cuenta'=>$data['numero_cuenta'],
      'monetaria'=>$monetaria,
      'ahorro'=>$ahorro,
      'banco'=>$data['nombre_banco'],
      'nombre_completo'=>$data['nombre_completo'],
      'dpi'=>$data['dpi'],
      'nit'=>$data['nit'],
      'email'=>$data['email'],
      'cuota_mensual'=>$data['cuota'],
      'deposito'=>number_format($data['monto_aceptado'],2),
  
    );
  }
  


  $pdf_contrato->fillForm($fields);

  // $string_notas=$data['observaciones_anexo1'];
  // $splitlineas = preg_split ("/\r\n|\n|\r/", $string_notas);



	//ADJUNTOS - IMÁGENES
	$pdf=new FPDF();
	$pdf->SetFont('Arial','B',16);
	$ubadjuntos="../adjuntos/".$control."/";
	$contadoradjuntos_imgs=0;
  if (is_dir($ubadjuntos)){
    if ($dh = opendir($ubadjuntos)){
      while (($file = readdir($dh)) !== false){
				$ext=pathinfo($file, PATHINFO_EXTENSION);
        if(!is_dir($file) AND ($ext == "jpg" || $ext == "jpeg" || $ext == "JPG" || $ext == "png") ){
					$contadoradjuntos_imgs++;
					$pdf->AddPage('Portrait', 'Letter', 0);
					$pdf->Cell(90, 120, "", 0, 1, 'C',$pdf->Image($ubadjuntos.$file,20,20,175,0));
        }
      }
      closedir($dh);
    }
  }
	$adjuntosdir="./procesados/temporal".$control.".pdf";
	if($contadoradjuntos_imgs>0){
		$pdf->Output($adjuntosdir,"F");
	}


/////////////////////////////////////////
	$papeleria = new mikehaertl\pdftk\Pdf();
	//$papeleria->addFile($pdf_checklist, 'A');
  //$papeleria->addFile($pdf_formato_unico, 'B');
	$papeleria->addFile($pdf_contrato, 'A');


	if($contadoradjuntos_imgs>0) $papeleria->addFile($adjuntosdir, 'B');
/////////////////////////////////////////
	//ADJUNTOS - PDF
	$ubadjuntos="../adjuntos/".$control."/";
	$contadoradjuntos_pdf='C';
	if (is_dir($ubadjuntos)){
		if ($dh = opendir($ubadjuntos)){
			while (($file = readdir($dh)) !== false){
				$ext=pathinfo($file, PATHINFO_EXTENSION);
				if(!is_dir($file) AND $ext == "pdf"){
					++$contadoradjuntos_pdf;
					//$pdf_adjunto= new Pdf($file);
					//$referencia= (string)$contadoradjuntos_pdf;
					$papeleria->addFile($ubadjuntos.$file, $contadoradjuntos_pdf);
				}
			}
			closedir($dh);
		}
	}
/////////////////////////////////////////

	$papeleria->saveAs('./procesados/'.normalizarNombre($_GET['nombre_cliente']).'.pdf');

	unlink($adjuntosdir);

	readfile('./procesados/'.normalizarNombre($_GET['nombre_cliente']).'.pdf');

  function eliminar_tildes($cadena){

    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú");
    $permitidas=    array ("a","e","i","o","u","A","E","I","O","U");
    $texto = str_replace($no_permitidas, $permitidas ,$cadena);
    return strtoupper($texto);

  }

  function nombre_mes($mes)
{
	if($mes==1)
	return "Enero";
	elseif($mes==2)
	return "Febrero";
	elseif($mes==3)
	return "Marzo";
	elseif($mes==4)
	return "Abril";
	elseif($mes==5)
	return "Mayo";
	elseif($mes==6)
	return "Junio";
	elseif($mes==7)
	return "Julio";
	elseif($mes==8)
	return "Agosto";
	elseif($mes==9)
	return "Septiembre";
	elseif($mes==10)
	return "Octubre";
	elseif($mes==11)
	return "Noviembre";
	elseif($mes==12)
	return "Diciembre";
}

  function fechaCastellano ($fecha) {
    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
    $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
  }

  function normalizarNombre($nombre){

    $aa=preg_replace('/[^a-zA-Z0-9_ -]/s','',$nombre);
    $bb=str_replace('/', '-', $aa);

    return $bb;

  }

	function comprobar_existencia($archivo){

	  if( !file_exists($archivo) ){
	      return false;
	  }else{
	      return true;
	  }


	}


  function getNombreDpto($id_depto,$mysqli)
  {
  	$row='';
  	$query=$mysqli->query("SELECT nombre_depto from bpoSystem.catDepartamento WHERE id_pais=1 AND id_depto=$id_depto");

  	if($query){
      $row=$query->fetch_array();
    }
  	return strtoupper($row['nombre_depto']);
  }

  function getNombreMuni($id_depto, $id_muni, $mysqli)
  {
  	$row='';
  	$query=$mysqli->query("SELECT nombre_muni from bpoSystem.catMunicipios WHERE id_pais=1 AND id_depto=$id_depto AND id_muni=$id_muni");

  	if($query){
      $row=$query->fetch_array();
    }
  	return strtoupper($row['nombre_muni']);
  }


  function format_price($number,$decPlaces,$decSep,$thouSep){

      //$number - number for format
      //$decPlaces - number of decimal places
      //$decSep - separator for decimals
      //$thouSep - separator for thousands

      //first remove all white spaces
      $number=preg_replace('/\s+/', '',$number);
      //split string into array
      $numberArr = str_split($number);
      //reverse array and not preserve key, keys will help to find decimal place
      $numberArrRev=array_reverse($numberArr);

      //find first occurrence of non number character, that will be a decimal place
      //store $key into variable $decPointIsHere
      foreach ($numberArrRev as $key => $value) {
          if(!is_numeric($value)){
              if($decPointIsHere==""){
                  $decPointIsHere=$key;
              }
          }
      }

      //decimal comma or whatever it is replace with dot
      //$decPointIsHere is the key of the element that will contain decimal separator dot
      if($decPointIsHere!=""){
          $numberArrRev[$decPointIsHere]=".";
      }

      //again check through array for non numerical characters but skipping allready processed keys
      //if is not number remove from array

      foreach ($numberArrRev as $key => $value) {
          if(!is_numeric($value) && $key>$decPointIsHere){
              unset($numberArrRev[$key]);
          }
      }

      //reverse back, at the start reversed array $numberArrRev to $numberArr
      $numberArr=array_reverse($numberArrRev);

      //create string from array
      $numberClean=implode("",$numberArr);

      // apply php number_format function
      return number_format($numberClean,$decPlaces,$decSep,$thouSep);

  }



?>

</body>
</html>

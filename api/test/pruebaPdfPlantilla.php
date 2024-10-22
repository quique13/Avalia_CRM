<?php 
    include_once "../class/dbClassMysql.php";
    include_once "../class/functions.php";
    require_once '../class/vendor/autoload.php';
    use mikehaertl\pdftk\Pdf;
    date_default_timezone_set('America/Guatemala');
    $conn = new dbClassMysql();
    $func = new Functions();

    // Fill form with data array
    $pdf = new Pdf('../plantillasPdf/cotizacionMarabi.pdf');
    $result = $pdf->fillForm([
            'habitaciones'=>'2',
            'correo' => 'wsaavedra.91@gmail.com',
        ])
        ->needAppearances()
        ->saveAs('../plantillasPdf/procesados/cotizacionMarabi.pdf');
    
    // Always check for errors
    if ($result === false) {
        $error = $pdf->getError();
    }
    $pdf ->Output();
   
?>


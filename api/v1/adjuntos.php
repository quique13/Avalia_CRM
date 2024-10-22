<?php

include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";
require_once '../class/dompdfPHP7/src/Autoloader.php';
//require_once ('../class/PDFMergerNew/PDFMerger-master/PDFMerger.php');
require_once '../class/PDFMerger/PDFMerger/PDFMerger.php';
require_once '../class/merge-pdf/MergePdf.class.php';

//use PDFMerger\PDFMerger;
Dompdf\Autoloader::register();
use Dompdf\Dompdf;

$conn = new dbClassMysql();
$func = new Functions();
$nombreCliente = $_GET['nombreCliente'];
$idCliente = $_GET['idCliente'];
$id_tipo_documento = isset($_GET['id_tipo_documento']) ? intval($_GET['id_tipo_documento']) : 0;
$_AND = $id_tipo_documento != 0 ? "AND a.id_tipo_documento = {$id_tipo_documento}" : "";
$strQuery = "   SELECT a.*
                FROM  archivo a
                INNER JOIN  tipo_documento td ON a.id_tipo_documento = td.id_tipo_documento
                WHERE a.id_cliente ={$idCliente}
                AND a.estado = 1
                {$_AND}
                ORDER by td.orden, a.tipo DESC";
$qTmp = $conn->db_query($strQuery);
if ($conn->db_num_rows($qTmp) > 0) {
    $_path = "../public/";
    $leter = 65;
    $contadoradjuntos_imgs = 0;
    $dompdf = new Dompdf();
    $arrayPdf = array();
    $PathFile = '';
    $PathFileEj = '';
    $texto_r = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
        <head>
            <link rel="stylesheet" type="text/css" href="../css/styleCotMarabi.css"/>
            <style>
                @page {
                    margin:0;padding:0; // you can set margin and padding 0
                }
                body {
                    font-family: Times New Roman;
                    font-size: 33px;
                    text-align: center;
                    border: thin solid black;
                }
            </style>
        </head>
        <body>
        ';
    while ($rTmp = $conn->db_fetch_object($qTmp)) {
        if (file_exists($_path . $rTmp->codigo)) {

            if (in_array($rTmp->tipo, array("jpg", "jpeg", "JPG", "png"))) {
                $texto_r .= '<div style="page-break-after: always;"></div>';
                $texto_r .= '<img style="position:absolute;top:0.00in;left:0.00in;" src="' . fcnBase64($_path . $rTmp->codigo) . '" />';
                $contadoradjuntos_imgs++;
                $texto_r .= '<div style="page-break-after: always;"></div>';
            } else if ($rTmp->tipo == 'pdf') {
                $arrayPdf[] = $_path . $rTmp->codigo;
                //$pdf->addPDF($_path . $rTmp->codigo);
                //$PathFile .= file_get_contents($_path . $rTmp->codigo);
                //file_put_contents($_path."adjuntosPdf_". $idCliente."_".$rTmp->codigo,$PathFile);
            }
        }
    }
    //$pdf->addPDF($_path . "202201080025591.pdf");
    //$pdf->merge('browser','merged.pdf');
    //file_put_contents($_path."adjuntosPdfEjM_". $idCliente.".pdf",$pdf);

    //$guardarPdf = $pdf->Output();
    //$merge->output();
    //file_put_contents($_path."adjuntosPdf".$idCliente.".pdf", $guardarPdf);
    $texto_r .= '
      </body>
        </html>
        ';
    $dompdf->load_html(($texto_r));

// (Optional) Setup the paper size and orientation
    $customPaper = array(0, 0, 666.141, 864.56);
    $dompdf->setPaper($customPaper);

// Render the HTML as PDF
    $dompdf->render();
    $pdfD = $dompdf->output();

    file_put_contents($_path . "adjuntos_" . $idCliente . ".pdf", $pdfD);

    $arrayPdf[] = $_path . "adjuntos_" . $idCliente . ".pdf";

    MergePdf::merge(
        $arrayPdf,
        MergePdf::DESTINATION__DISK_INLINE,

    );
// Output the generated PDF to Browser
// Enviamos el fichero PDF al navegador.
//$dompdf->stream('adjuntoCliente'.$idCliente,array("Attachment" => false));

// MergePdf::merge(
//     Array(

//         $_path."adjuntos_".$idCliente.".pdf",
//         $_path . "enganche_196.pdf",
//         $_path . "0.643435001640196525.pdf",
//         $_path . "0.132519001640196507.pdf",
//         $_path . "0.593520001640196430.pdf",
//         $_path . "0.489747001636386067.pdf",
//         $_path . "recibo_2236.pdf",
//         $_path . "reciboReserva_196.pdf",
//         $_path . "recibo_2235.pdf"

//     ),
//     MergePdf::DESTINATION__DISK_INLINE
// );

} else {

}

function normalizarNombre($nombre)
{

    $aa = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $nombre);
    $bb = str_replace('/', '-', $aa);
    return $bb;
}
function fcnBase64($imagen)
{
    $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($imagen));
    return $imagenBase64;

}
function fcnBase64Pdf($imagen)
{
    $imagenBase64 = "data:application/pdf;base64," . base64_encode(file_get_contents($imagen));
    return $imagenBase64;

}
?>

</body>
</html>

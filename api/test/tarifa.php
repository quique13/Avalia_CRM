<?php

include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";

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
//login
$intIdTarifa = isset($_POST['idTarifa']) ? intval($_POST['idTarifa']):0;
$intTarifa1 = isset($_POST['tarifa_1']) ? trim($_POST['tarifa_1']):0;
$intTarifa2 = isset($_POST['tarifa_2']) ? trim($_POST['tarifa_2']):0;
$intTarifa3 = isset($_POST['tarifa_3']) ? trim($_POST['tarifa_3']):0;




    

if (isset($_GET['agregar_editar_tarifa'])) {         
    $errorMsj = "";
    $strQuery = "   INSERT  catTarifas (idTarifa,tarifa_1,tarifa_2,tarifa_3)
                    VALUES ({$intIdTarifa},{$intTarifa1},{$intTarifa2},{$intTarifa3})
                    ON DUPLICATE KEY UPDATE
                    tarifa_1 = '{$intTarifa1}',
                    tarifa_2 =  '{$intTarifa2}',
                    tarifa_3 =  '{$intTarifa3}'";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        
        $title = $intIdTarifa > 0 ? " Editadas" : " Guardadas";
        $res = array(
            'err' => false,
            "mss" => "Tarifas {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "idTarifa" => $intIdTarifa
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if(isset($_GET['get_lista_tarifas'])){

    $strQuery = "SELECT idTarifa, 'Activo' as estado, tarifa_1,tarifa_2,tarifa_3
                    FROM  catTarifas t
                    ORDER by idTarifa DESC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_tarifas" => $arr
    );
}
if(isset($_GET['get_tarifa'])){

    $strQuery = "SELECT * 
                    FROM  catTarifas a
                    WHERE IdTarifa = {$intIdTarifa};";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "tarifa" => $arr
    );
}
$conn->db_close();
echo json_encode($res);
?>
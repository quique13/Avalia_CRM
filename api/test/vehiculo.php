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
$intIdBanco = isset($_POST['idVehiculo']) ? intval($_POST['idVehiculo']):0;
$strMarca = isset($_POST['marca']) ? trim($_POST['marca']):0;
$strTipo = isset($_POST['tipo']) ? trim($_POST['tipo']):0;
$strModelo = isset($_POST['modelo']) ? trim($_POST['modelo']):0;
$intEstado = isset($_POST['estado']) ? intval($_POST['estado']):0;

if(isset($_GET['get_lista_vehiculos_marca'])){

    $strQuery = "SELECT DISTINCT marca 
    FROM  catVehiculos 
    ORDER by marca ASC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_marcas" => $arr
    );
}
if(isset($_GET['get_lista_vehiculos_tipo'])){

    $strQuery = "SELECT DISTINCT tipo 
    FROM  catVehiculos 
    WHERE marca = '{$strMarca}'
    ORDER by tipo ASC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_tipos" => $arr
    );
}
if(isset($_GET['get_lista_vehiculos_modelo'])){

    $strQuery = "SELECT DISTINCT modelo 
    FROM  catVehiculos 
    WHERE marca = '{$strMarca}' AND tipo = '{$strTipo}'
    ORDER by modelo ASC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_modelos" => $arr
    );
}
$conn->db_close();
echo json_encode($res);
?>
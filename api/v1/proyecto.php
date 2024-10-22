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
$strProyecto= isset($_POST['proyecto']) ? trim($_POST['proyecto']):'';
$intIdProyecto = isset($_POST['idProyecto']) ? intval($_POST['idProyecto']):0;
$intTarifa = isset($_POST['tarifa']) ? floatval($_POST['tarifa']):0;
$intParqueoExtra = isset($_POST['parqueoExtra']) ? floatval($_POST['parqueoExtra']):0;
$intParqueoDisponible = isset($_POST['parqueoDisponible']) ? intval($_POST['parqueoDisponible']):0;
$intCambioDolar = isset($_POST['cambioDolar']) ? floatval($_POST['cambioDolar']):0;
$intPorcentajeEnganche = isset($_POST['porcentajeEnganche']) ? floatval($_POST['porcentajeEnganche']):0;
$intDeposito = isset($_POST['deposito']) ? floatval($_POST['deposito']):0;
$intIusi = isset($_POST['iusi']) ? floatval($_POST['iusi']):0;
$intSeguro = isset($_POST['seguro']) ? floatval($_POST['seguro']):0;
$intPorcentajeFacturacion = isset($_POST['porcentajeFacturacion']) ? floatval($_POST['porcentajeFacturacion']):0;
$intMontoReserva = isset($_POST['montoReserva']) ? floatval($_POST['montoReserva']):0;
$strFechaProyecto = isset($_POST['fechaProyecto']) ? trim($_POST['fechaProyecto']):'';
$intCocinaTipoA = isset($_POST['cocinaTipoA']) ? floatval($_POST['cocinaTipoA']):0;
$intCocinaTipoB = isset($_POST['cocinaTipoB']) ? floatval($_POST['cocinaTipoB']):0;
$intParqueoExtraMoto = isset($_POST['parqueoExtraMoto']) ? floatval($_POST['parqueoExtraMoto']):0;
$intEstado = isset($_POST['estado']) ? intval($_POST['estado']):0;



if (isset($_GET['agregar_editar_proyecto'])) {         
    $errorMsj = "";
    $strQuery = "   INSERT  datosGlobales (idGlobal, proyecto, rate, parqueoExtra, parqueosDisponibles, 
                                                        cambioDolar, porcentajeEnganche, deposito, iusi, seguro, 
                                                        porcentajeFacturacion, montoReserva, fechaProyecto, usuarioCreacion, 
                                                        cocinaTipoA, cocinaTipoB, parqueoExtraMoto)
                    VALUES ({$intIdProyecto},'{$strProyecto}',{$intTarifa},{$intParqueoExtra},{$intParqueoDisponible},
                            {$intCambioDolar},{$intPorcentajeEnganche},{$intDeposito},{$intIusi},{$intSeguro},
                            {$intPorcentajeFacturacion},{$intMontoReserva},'{$strFechaProyecto}',{$id_usuario},
                            {$intCocinaTipoA},{$intCocinaTipoB},{$intEstado})
                    ON DUPLICATE KEY UPDATE
                    proyecto = '{$strProyecto}',
                    rate = {$intTarifa},
                    parqueoExtra = {$intParqueoExtra},
                    parqueosDisponibles = {$intParqueoDisponible},
                    cambioDolar = {$intCambioDolar},
                    porcentajeEnganche = {$intPorcentajeEnganche},
                    deposito ={$intDeposito},
                    iusi = {$intIusi},
                    seguro = {$intSeguro},
                    porcentajeFacturacion = {$intPorcentajeFacturacion},
                    montoReserva = {$intMontoReserva},
                    fechaProyecto = '{$strFechaProyecto}',
                    cocinaTipoA = {$intCocinaTipoA},
                    cocinaTipob = {$intCocinaTipoB},
                    parqueoExtraMoto = {$intParqueoExtraMoto},
                    suspendido =  '{$intEstado}'";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        $title = $intIdProyecto > 0 ? " Editado" : " Guardado";
        if($intIdProyecto==0){
            $strQuery = "SELECT idGlobal 
                        FROM  datosGlobales
                        where proyecto =  '{$strPrimerNombre}'
                        order by idGlobal desc limit 1 ;";

            //echo $strQuery;
            $qTmp = $conn ->db_query($strQuery);
            $rTmp = $conn->db_fetch_object($qTmp);
            $intIdProyecto = $rTmp->idGlobal;
        }

        $res = array(
            'err' => false,
            "mss" => "Proyecto {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "idProyecto" => $intIdProyecto
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if(isset($_GET['get_lista_proyectos'])){

    $strQuery = "SELECT * 
                    FROM  datosGlobales 
                    ORDER by idGlobal ASC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_proyectos" => $arr
    );
}
if(isset($_GET['get_proyecto'])){

    $strQuery = "SELECT * 
                    FROM  datosGlobales 
                    WHERE idGlobal = {$intIdProyecto};";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "proyecto" => $arr
    );
}
$conn->db_close();
echo json_encode($res);
?>
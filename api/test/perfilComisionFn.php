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
$intIdTipoComision = isset($_POST['tipo']) ? intval($_POST['tipo']):0;
$intIdPerfilComision = isset($_POST['idPerfilComision']) ? intval($_POST['idPerfilComision']):0;
$intPorcentajePerfil = isset($_POST['porcentaje_perfil']) ? floatval($_POST['porcentaje_perfil']):0;
$intEstado = isset($_POST['estado']) ? intval($_POST['estado']):0;
$strTipo = isset($_POST['tipo']) ? trim($_POST['tipo']):'';
$strPerfil = isset($_POST['perfil']) ? trim($_POST['perfil']):'';
$strProyecto = isset($_POST['proyecto']) ? trim($_POST['proyecto']):'';



    
if(isset($_GET['get_proyecto'])){

    $strQuery = "SELECT idGlobal,proyecto 
                    FROM  datosGlobales
                    ORDER BY proyecto";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "proyectos" => $arr
    );
}
if (isset($_GET['agregar_editar_perfil_comision'])) {         
    $errorMsj = "";
    $strQuery = "   INSERT  catPagaComision (idPagaComision,idTipoComision, descripcion,porcentajeComision, suspendido)
                    VALUES ({$intIdPerfilComision},{$intIdTipoComision},'{$strPerfil}','{$intPorcentajePerfil}',{$intEstado})
                    ON DUPLICATE KEY UPDATE
                    porcentajeComision = {$intPorcentajePerfil},
                    descripcion = '{$strPerfil}',
                    suspendido =  '{$intEstado}'";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        
        $title = $intIdPerfilComision > 0 ? " Editado" : " Guardado";
        if($intIdPerfilComision==0){
            $strQuery = "SELECT idPagaComision 
                        FROM  catPagaComision
                        where porcentajeComision =  {$intPorcentajePerfil}
                        and descripcion = '{$strPerfil}'
                        order by idPagaComision desc limit 1 ;";

            //echo $strQuery;
            $qTmp = $conn ->db_query($strQuery);
            $rTmp = $conn->db_fetch_object($qTmp);
            $intIdPerfilComision = $rTmp->idTipoComision;
        }
        $res = array(
            'err' => false,
            "mss" => "Usuario {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "idPerfilComision" => $intIdPerfilComision
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if(isset($_GET['get_lista_perfil_comision'])){

    $strQuery = "SELECT idPagaComision, if(cp.suspendido=0,'Activo','Inactivo') as estado, cp.descripcion, dg.proyecto,
                    cp.porcentajeComision,ct.descripcion as tipo
                    FROM catPagaComision cp
                    INNER JOIN catTipoComision ct ON cp.idTipoComision = ct.idTipoComision
                    INNER JOIN  datosGlobales dg ON idGlobal = ct.proyecto 
                    ORDER by proyecto,idPagaComision DESC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_perfil_comision" => $arr
    );
}
if(isset($_GET['get_tipo_comision'])){

    $strQuery = "SELECT * 
                    FROM  catTipoComision 
                    WHERE suspendido = 0
                    AND proyecto = {$strProyecto};";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "tipoComision" => $arr
    );
}
if(isset($_GET['get_perfil_comision'])){

    $strQuery = "SELECT cp.*,ct.idTipoComision as tipo,ct.proyecto 
                    FROM  catPagaComision cp
                    INNER JOIN catTipoComision ct ON cp.idTipoComision = ct.idTipoComision 
                    WHERE idPagaComision = {$intIdPerfilComision};";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "perfilComision" => $arr
    );
}
$conn->db_close();
echo json_encode($res);
?>
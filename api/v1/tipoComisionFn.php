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
$intIdNivel = isset($_POST['idNivel']) ? intval($_POST['idNivel']):0;
$intIdTipoComision = isset($_POST['idTipoComision']) ? intval($_POST['idTipoComision']):0;
$intEstado = isset($_POST['estado']) ? intval($_POST['estado']):0;
$strTipo = isset($_POST['tipo']) ? trim($_POST['tipo']):'';
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
if (isset($_GET['agregar_editar_tipo_comision'])) {         
    $errorMsj = "";
    $strQuery = "   INSERT  catTipoComision (idTipoComision, proyecto, descripcion, suspendido)
                    VALUES ({$intIdTipoComision},'{$strProyecto}','{$strTipo}',{$intEstado})
                    ON DUPLICATE KEY UPDATE
                    proyecto = {$strProyecto},
                    descripcion = '{$strTipo}',
                    suspendido =  '{$intEstado}'";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        
        $title = $intIdTipoComision > 0 ? " Editado" : " Guardado";
        if($intIdTipoComision==0){
            $strQuery = "SELECT idTipoComision 
                        FROM  catTipoComision
                        where proyecto =  '{$strProyecto}'
                        and descripcion = '{$strTipo}'
                        order by idTipoComision desc limit 1 ;";

            //echo $strQuery;
            $qTmp = $conn ->db_query($strQuery);
            $rTmp = $conn->db_fetch_object($qTmp);
            $intIdTipoComision = $rTmp->idTipoComision;
        }
        $res = array(
            'err' => false,
            "mss" => "Usuario {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "idTipoComision" => $intIdTipoComision
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if(isset($_GET['get_lista_tipo_comision'])){

    $strQuery = "SELECT idTipoComision, if(t.suspendido=0,'Activo','Inactivo') as estado, descripcion, dg.proyecto
                    FROM  catTipoComision t
                    INNER JOIN  datosGlobales dg ON idGlobal = t.proyecto 
                    ORDER by proyecto,idTipoComision DESC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_tipo_comision" => $arr
    );
}
if(isset($_GET['get_tipo_comision'])){

    $strQuery = "SELECT * 
                    FROM  catTipoComision 
                    WHERE IdTipoComision = {$intIdTipoComision};";

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
$conn->db_close();
echo json_encode($res);
?>
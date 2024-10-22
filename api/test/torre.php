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
$intIdTorre = isset($_POST['idTorre']) ? intval($_POST['idTorre']):0;
$intEstado = isset($_POST['estado']) ? intval($_POST['estado']):0;
$intTorre = isset($_POST['torre']) ? intval($_POST['torre']):0;
$intNivel = isset($_POST['niveles']) ? intval($_POST['niveles']):0;
$strfechaEntraga = isset($_POST['fechaEntrega']) ? trim($_POST['fechaEntrega']):'';
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
if (isset($_GET['agregar_editar_torre'])) {         
    $errorMsj = "";
    $strQuery = "   INSERT  torres (idTorre, proyecto, noTorre, fechaEntrega, idUsuarioCreado, suspendido)
                    VALUES ({$intIdTorre},'{$strProyecto}','{$intTorre}','{$strfechaEntraga}',{$id_usuario},{$intEstado})
                    ON DUPLICATE KEY UPDATE
                    proyecto = {$strProyecto},
                    noTorre = {$intTorre},
                    fechaEntrega = '{$strfechaEntraga}',
                    suspendido =  '{$intEstado}'";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        
        $title = $intIdTorre > 0 ? " Editado" : " Guardado";
        if($intIdTorre==0){
            $strQuery = "SELECT idTorre 
                        FROM  torres
                        where proyecto =  '{$strProyecto}'
                        and noTorre = '{$intTorre}'
                        order by idTorre desc limit 1 ;";

            //echo $strQuery;
            $qTmp = $conn ->db_query($strQuery);
            $rTmp = $conn->db_fetch_object($qTmp);
            $intIdTorre = $rTmp->idTorre;
        }
        for($i = 1; $i <= $intNivel; $i++){
            $strQueryN = "INSERT  niveles (idNivel, idTorre,noNivel, fechaEntrega, idUsuarioCreado, suspendido)
            VALUES ({$intIdNivel},'{$intIdTorre }','{$i}','{$strfechaEntraga}',{$id_usuario},{$intEstado})
            ON DUPLICATE KEY UPDATE
            idTorre = {$intIdTorre },
            noNivel = {$i},
            fechaEntrega = '{$strfechaEntraga}',
            suspendido =  '{$intEstado}'";
            $conn->db_query($strQueryN);
        }
        $res = array(
            'err' => false,
            "mss" => "Usuario {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "idTorre" => $intIdTorre
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if(isset($_GET['get_lista_torres'])){

    $strQuery = "SELECT idTorre, if(t.suspendido=0,'Activo','Inactivo') as estado, noTorre, dg.proyecto
                    FROM  torres t
                    INNER JOIN  datosGlobales dg ON idGlobal = t.proyecto 
                    ORDER by proyecto,noTorre DESC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_torres" => $arr
    );
}
if(isset($_GET['get_torre'])){

    $strQuery = "SELECT * 
                    FROM  torres 
                    WHERE IdTorre = {$intIdTorre};";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "torre" => $arr
    );
}
$conn->db_close();
echo json_encode($res);
?>
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
$intEstado = isset($_POST['estado']) ? intval($_POST['estado']):0;
$intTorre = isset($_POST['torre']) ? intval($_POST['torre']):0;
$intNivel = isset($_POST['nivel']) ? intval($_POST['nivel']):0;
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
if(isset($_GET['get_torres'])){

    $strQuery = "SELECT idTorre,noTorre 
                    FROM  torres t
                    INNER JOIN  datosGlobales dg ON idGlobal = t.proyecto
                    WHERE t.proyecto = {$strProyecto}
                    ORDER BY idTorre";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "torres" => $arr
    );
}
if (isset($_GET['agregar_editar_nivel'])) {         
    $errorMsj = "";
    $strQuery = "   INSERT  niveles (idNivel, idTorre,noNivel, fechaEntrega, idUsuarioCreado, suspendido)
                    VALUES ({$intIdNivel},'{$intTorre}','{$intNivel}','{$strfechaEntraga}',{$id_usuario},{$intEstado})
                    ON DUPLICATE KEY UPDATE
                    idTorre = {$intTorre},
                    noNivel = {$intNivel},
                    fechaEntrega = '{$strfechaEntraga}',
                    suspendido =  '{$intEstado}'";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        $title = $intIdTorre > 0 ? " Editado" : " Guardado";
        if($intIdTorre==0){
            $strQuery = "SELECT idNivel 
                        FROM  niveles
                        where idTorre = {$intTorre}
                        AND noNivel = {$intNivel}
                        order by idNivel desc limit 1 ;";

            //echo $strQuery;
            $qTmp = $conn ->db_query($strQuery);
            $rTmp = $conn->db_fetch_object($qTmp);
            $intIdTorre = $rTmp->idNIvel;
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
if(isset($_GET['get_lista_niveles'])){

    $strQuery = "SELECT idNivel, if(n.suspendido=0,'Activo','Inactivo') as estado, noNivel, dg.proyecto,t.noTorre
                    FROM  niveles n
                    INNER JOIN  torres t ON n.idTorre = t.idTorre
                    INNER JOIN  datosGlobales dg ON idGlobal = t.proyecto 
                    ORDER by proyecto,noTorre,noNivel DESC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_niveles" => $arr
    );
}
if(isset($_GET['get_nivel'])){

    $strQuery = "SELECT n.*,t.proyecto 
                    FROM  niveles n
                    INNER JOIN  torres t ON n.idTorre = t.idTorre
                    WHERE IdNivel = {$intIdNivel};";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "nivel" => $arr
    );
}
$conn->db_close();
echo json_encode($res);
?>
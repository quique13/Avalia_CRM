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
$intIdBanco = isset($_POST['idBanco']) ? intval($_POST['idBanco']):0;
$strBanco = isset($_POST['banco']) ? trim($_POST['banco']):0;
$intEstado = isset($_POST['estado']) ? intval($_POST['estado']):0;
$intEstadoMarabi = isset($_POST['interesMarabi']) ? floatval($_POST['interesMarabi']):0;
$intEstadoNaos = isset($_POST['interesNaos']) ? floatval($_POST['interesNaos']):0;




    

if (isset($_GET['agregar_editar_banco'])) {         
    $errorMsj = "";
    $strQuery = "   INSERT  catBanco (idBanco,banco, bancosFinanciar,interes_marabi,interes_naos)
                    VALUES ({$intIdBanco},'{$strBanco}',1,{$intEstadoMarabi},{$intEstadoNaos})
                    ON DUPLICATE KEY UPDATE
                    banco = '{$strBanco}',
                    bancosFinanciar =  '1',
                    interes_marabi={$intEstadoMarabi},
                    interes_naos={$intEstadoNaos}";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        
        $title = $intIdBanco > 0 ? " Editado" : " Guardado";
        $res = array(
            'err' => false,
            "mss" => "Banco {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "idTorre" => $intIdBanco
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if(isset($_GET['get_lista_bancos'])){

    $strQuery = "SELECT idBanco, if(t.bancosFinanciar=1,'Activo','Inactivo') as estado, banco,interes_marabi,interes_naos
                    FROM  catBanco t
                    WHERE bancosFinanciar=1
                    ORDER by idBanco DESC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_bancos" => $arr
    );
}
if(isset($_GET['get_banco'])){

    $strQuery = "SELECT * 
                    FROM  catBanco a
                    WHERE IdBanco = {$intIdBanco}
                    AND bancosFinanciar=1;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "banco" => $arr
    );
}
if(isset($_GET['get_bancos'])){

    $strQuery = "SELECT * 
                    FROM  catBanco a
                    WHERE bancosFinanciar=1
                    ORDER BY
                        case when idBanco = 4 then 0 else 1 end, 
                        banco ASC ;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "bancos" => $arr
    );
}
$conn->db_close();
echo json_encode($res);
?>
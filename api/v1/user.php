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

$intIdUser= isset($_POST['id_user']) ? intval($_POST['id_user']):0;
$strName = isset($_POST['name']) ? trim($_POST['name']):'';
$strUsername = isset($_POST['user']) ? trim($_POST['user']):'';
$intPassword = isset($_POST['password']) ? $func->encryptPassword(($_POST['password'])):0;
$intState= isset($_POST['state']) ? intval($_POST['state']):0;
if(isset($_GET['get_all'])){
    $strQuery = "SELECT * 
                    FROM user
                    ORDER BY name";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => "",
        "users" => $arr
    );
}
if(isset($_GET['get_by_user_id'])){
    $strQuery = "SELECT * 
                    FROM user 
                    WHERE id_user = {$intIdUser}";
                    
    $qTmp = $conn->db_query($strQuery);
    $res = array(
        "err" => false,
        "mss" => "",
        "users" => $conn->db_fetch_object($qTmp)
    );
}
if(isset($_GET['add_or_edit_user'])){
    $strQuery = "   INSERT user (id_user,name,username,password)
                    VALUES ({$intIdUser},'{$strName}','{$strUsername}','{$strPassword}')
                    ON DUPLICATE KEY UPDATE
                    name = '{$strName}',
                    username = '{$strUsername}',
                    state = {$intState} ";
    if($conn->db_query($strQuery)){
        $title = $intIdUser > 0 ? " Editado" : " Guardado";
        $res = array (
            'err' => false,
            "mss" => "Usuario" .$title ." Correctamente...."
        );
    }
                    
    $qTmp = $conn->db_query($strQuery);
    $res = array(
        "err" => false,
        "mss" => "",
        "users" => $conn->db_fetch_object($qTmp)
    );
}
$conn->db_close();
echo json_encode($res);
?>
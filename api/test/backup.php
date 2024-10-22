<?php

session_name("inmobiliaria");
session_start();
$_id_usuario = isset($_SESSION['id_usuario'])?$_SESSION['id_usuario']:0;

include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";

$conn = new dbClassMysql();
$func = new Functions();

$func->getHeaders();
$res = array(
    "arr"=> true,
    "mss"=> "Error 404"
);
//login
$strUsuario= isset($_POST['usuario']) ? trim($_POST['usuario']):'';
$strPass = isset($_POST['password']) ? trim($_POST['password']):'';
$intIdUsuario = isset($_POST['idUsuario']) ? intval($_POST['idUsuario']):0;
$intEstado = isset($_POST['estado']) ? intval($_POST['estado']):0;
$strPrimerNombre = isset($_POST['primerNombre']) ? trim($_POST['primerNombre']):'';
$strSegundoNombre = isset($_POST['segundoNombre']) ? trim($_POST['segundoNombre']):'';
$strPrimerApellido = isset($_POST['primerApellido']) ? trim($_POST['primerApellido']):'';
$strSegundoApellido = isset($_POST['segundoApellido']) ? trim($_POST['segundoApellido']):'';
$strApellidoCasada = isset($_POST['apellidoCasada']) ? trim($_POST['apellidoCasada']):'';
$strTercerNombre = isset($_POST['tercerNombre']) ? trim($_POST['tercerNombre']):'';
$strCorreo = isset($_POST['correo']) ? trim($_POST['correo']):'';
$strTelefono = isset($_POST['telefono']) ? trim($_POST['telefono']):'';
$strUsuario = isset($_POST['usuario']) ? trim($_POST['usuario']):'';
$strProyectos = isset($_POST['proyectos']) ? trim($_POST['proyectos']):'';
$intIdPerfil = isset($_POST['id_perfil']) ? intval($_POST['id_perfil']) : 0;
$strOldPass = isset($_POST['old_password']) ? trim($_POST['old_password']) : '';
$strNewPass = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    

if(isset($_GET['get_lista_backUp'])){

    $strQuery = "SELECT *,date_format(fecha, '%d-%m-%Y %H:%i:%s') as fechaFormat
                    FROM  bitacora_backup
                    WHERE estado = 1 
                    ORDER by id_bitacora_backup DESC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "get_lista_backUp" => $arr
    );
}
if(isset($_GET['get_usuario'])){

    $strQuery = "   SELECT * 
                    FROM  usuarios 
                    WHERE id_usuario = {$intIdUsuario};";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "usuario" => $arr
    );
}

if (isset($_GET['get_perfiles'])) {
    $strQuery = "   SELECT *
                    FROM perfil
                    WHERE estado = 1
                    ORDER BY nombre";
    $qTmp = $conn->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)) {
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => "",
        "perfiles" => $arr,
    );
}
if (isset($_GET['get_proyectos'])) {
    $strQuery = "   SELECT (group_concat(proyecto)) as proyecto, 'Todos' as todos, 0 as id
                    FROM catProyectos
                    WHERE estado = 1
                    UNION                    
                    SELECT proyecto,proyecto as todos, id
                    FROM catProyectos
                    WHERE estado = 1
                    ORDER BY id ;";
    $qTmp = $conn->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)) {
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => "",
        "proyectos" => $arr,
    );
}

if (isset($_GET['get_datos_globales'])) {
    $strQuery = "   SELECT idGlobal, proyecto
                    FROM datosGlobales
                    WHERE suspendido = 0
                    ORDER BY proyecto";
    $qTmp = $conn->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)) {
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => "",
        "datos_globales" => $arr,
    );
}

if (isset($_GET['cambio_contrasenia'])) {
    $res = array(
        "err"=> true,
        "mss"=> "Ha ocurrido un error..."
    );
    $strQuery = "   SELECT *
                    FROM usuarios
                    WHERE id_usuario = {$_id_usuario}
                    AND `password` = '{$strOldPass}'";
    $qTmp = $conn->db_query($strQuery);
    if ($conn->db_num_rows($qTmp) > 0) {
        $strQuery = "   UPDATE usuarios
                        SET `password` = '{$strNewPass}',
                            ultimo_password = '{$strOldPass}',
                            password_default = 0,                           
                            cambio_password = CURRENT_TIMESTAMP()
                        WHERE id_usuario = {$_id_usuario}";
        if ($conn->db_query($strQuery)) {
            $res = array(
                "err" => false,
                "mss" => "Contraseña actualizada correctamente...",
            );
            $_SESSION['password_default'] = 0;
        } 
    } else {
        $res['mss'] = "La contraseña anterior no coincide...";
    }
}

if (isset($_GET['restablecer_contrasenia'])) {
    $strQuery = "   UPDATE usuarios
                    SET `password` = '{$strPass}',
                        password_default = 1,
                        cambio_password = CURRENT_TIMESTAMP()
                    WHERE id_usuario = {$intIdUsuario}";
    if ($conn->db_query($strQuery)) {
        $res = array(
            "err" => false,
            "mss" => "Contraseña restablecida corectamente...",
        );
    } else {
        $res['mss'] = "Ha ocurrido un error al momento de restablecer la contraseña...";
    }
}


$conn->db_close();
echo json_encode($res);
?>
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
    
if (isset($_GET['login'])) {
    $strQuery = "   SELECT id_usuario, CONCAT(primer_nombre, ' ', primer_apellido) AS nombre, password_default,id_perfil,proyectos,
                        CASE
                            WHEN `password` = '{$strPass}' THEN 0
                            WHEN suspendido = 1 THEN 1
                            ELSE 2
                        END AS success
                    FROM usuarios
                    WHERE usuario = '{$strUsuario}'";
    $qTmp = $conn->db_query($strQuery);
    if ($conn->db_num_rows($qTmp) > 0) {
        $rTmp = $conn->db_fetch_object($qTmp);
        if ($rTmp->success == 0) {
            session_name("inmobiliaria");
            session_start();
            $_SESSION['id_usuario'] = $rTmp->id_usuario;
            $_SESSION['usuario'] = $strUsuario;
            $_SESSION['nombre'] = $rTmp->nombre;
            $_SESSION['password_default'] = $rTmp->password_default;
            $_SESSION['login'] = 'si';
            $_SESSION['id_perfil'] = $rTmp->id_perfil;
            $_SESSION['proyectos'] = $rTmp->proyectos;
            $_SESSION['alerta'] = 'no';
            $res = array(
                "err" => false,
                "mss" => "",
            );
        } else if ($rTmp->success == 1) {
            $res['mss'] = "La cuenta se encuentra suspendida";
        } else {
            $res['mss'] = "La contraseña no es valida";
        }
    } else {
        $res['mss'] = "El usuario no se encuentra registrado";
    }
}

if(isset($_GET['logout'])){
    session_destroy();
    $res = array(
        "err" => false,
        "mss" => "Salió con exito",
    );
}
if (isset($_GET['agregar_editar_usuario'])) {         
    $errorMsj = "";
    $strQuery = "   INSERT  usuarios (id_usuario,id_pais,usuario,primer_nombre,segundo_nombre,
                                                        primer_apellido,segundo_apellido,apellido_casada,tercer_nombre,mail,
                                                        password,cambio_password,ultimo_password,intentos_fallidos,telefono,
                                                        tipo_usuario,suspendido, id_perfil,proyectos)
                    VALUES ({$intIdUsuario},1,'{$strUsuario}','{$strPrimerNombre}','{$strSegundoNombre}',
                            '{$strPrimerApellido}', '{$strSegundoApellido}','{$strApellidoCasada}','{$strTercerNombre}','{$strCorreo}','{$strPass}',
                            '0000-00-00','{$strPassword}',0,'{$strTelefono}',1,{$intEstado}, {$intIdPerfil},'{$strProyectos}')
                    ON DUPLICATE KEY UPDATE
                    primer_nombre = '{$strPrimerNombre}',
                    segundo_nombre = '{$strSegundoNombre}',
                    primer_apellido = '{$strPrimerApellido}',
                    segundo_apellido = '{$strSegundoApellido}',
                    apellido_casada = '{$strApellidoCasada}',
                    tercer_nombre = '{$strTercerNombre}',
                    mail ='{$strCorreo}',
                    telefono = '{$strTelefono}',
                    usuario = '{$strUsuario}',
                    suspendido =  '{$intEstado}',
                    id_perfil = VALUES(id_perfil),
                    proyectos = '{$strProyectos}'";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        $title = $intIdOcaCliente > 0 ? " Editado" : " Guardado";
        if($intIdUsuario==0){
            $strQuery = "SELECT id_usuario 
                        FROM  usuarios
                        where primer_nombre =  '{$strPrimerNombre}'
                        and primer_apellido = '{$strPrimerApellido}'
                        and mail = '{$strCorreo}'
                        order by id_usuario desc limit 1 ;";

            //echo $strQuery;
            $qTmp = $conn ->db_query($strQuery);
            $rTmp = $conn->db_fetch_object($qTmp);
            $intIdUsuario = $rTmp->id_usuario;
        }

        $res = array(
            'err' => false,
            "mss" => "Usuario {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "idUsuario" => $intIdUsuario
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if(isset($_GET['get_lista_usuarios'])){

    $strQuery = "SELECT id_usuario, if(suspendido=0,'Activo','Inactivo') as estado, usuario, password_default,
                    CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) as nombre_completo 
                    FROM  usuarios 
                    ORDER by suspendido ASC, id_usuario DESC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_usuarios" => $arr
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
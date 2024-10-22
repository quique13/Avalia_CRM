<?php

include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";
date_default_timezone_set('America/Guatemala');

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
$arrayProyectos = explode(",",$_SESSION['proyectos']);
$proyectos = '';
$countP=0;
foreach($arrayProyectos as $valor)
{
    if($countP==0)
        $coma='';
    else
        $coma=',';
    $proyectos .= $coma."'".$valor."'";
    $countP++;
}
$countP=0;
//login

$intIdApartamento = isset($_POST['idApartamento']) ? intval($_POST['idApartamento']):0;
$intEstado = isset($_POST['estado']) ? intval($_POST['estado']):0;
$intTorre = isset($_POST['torre']) ? intval($_POST['torre']):0;
$intProyecto = isset($_POST['proyecto']) ? intval($_POST['proyecto']):0;
$intNivel = isset($_POST['nivel']) ? intval($_POST['nivel']):0;
$strApartamento = isset($_POST['apartamento']) ? trim($_POST['apartamento']):'';
$intCodigo = isset($_POST['codigo']) ? trim($_POST['codigo']):'';
$intCuarto = isset($_POST['cuartos']) ? intval($_POST['cuartos']):0;
$intPrecio = isset($_POST['precio']) ? floatval($_POST['precio']):0;
$intIusiSeguro = isset($_POST['iusi_seguro']) ? floatval($_POST['iusi_seguro']):0;
$intMts = isset($_POST['mts']) ? floatval($_POST['mts']):0;
$intMtsJardin = isset($_POST['mts_jardin']) ? floatval($_POST['mts_jardin']):0;
$intMtsBodega = isset($_POST['mts_bodega']) ? floatval($_POST['mts_bodega']):0;
$intParqueo = isset($_POST['parqueo']) ? intval($_POST['parqueo']):0;
$intParqueoMoto = isset($_POST['parqueo_moto']) ? intval($_POST['parqueo_moto']):0;
$intPrecioBodega = isset($_POST['precio_bodega']) ? floatval($_POST['precio_bodega']):0;
$strProyectoBuscar= isset($_POST['proyectoBscTxt']) ? trim($_POST['proyectoBscTxt']):'';
$strTorreBuscar= isset($_POST['torreBscTxt']) ? trim($_POST['torreBscTxt']):'';
$intEstado= isset($_POST['estado']) ? trim($_POST['estado']):'';



    
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
if(isset($_GET['get_niveles'])){

    $strQuery = "SELECT idNivel,noNivel 
                    FROM  niveles n
                    WHERE idTorre = {$intTorre}
                    ORDER BY idNivel";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "niveles" => $arr
    );
}
if (isset($_GET['agregar_editar_apartamento'])) {         
    $errorMsj = "";
    $strQuery = "   INSERT  apartamentos (idApartamento, idProyecto, idTorre, idNivel, apartamento, 
                                                        codigo, estado, cuartos, precio, iusi_seguro, 
                                                        sqmts, jardin_mts, bodega_mts, parqueo, parqueo_moto, 
                                                        bodega_precio, idUsuarioCreacion, suspendido)
                    VALUES ({$intIdApartamento},{$intProyecto},{$intTorre},{$intNivel},'{$strApartamento}',
                            '{$intCodigo}',1,{$intCuarto},{$intPrecio},{$intIusiSeguro},
                            {$intMts},{$intMtsJardin},{$intMtsBodega},{$intParqueo},{$intParqueoMoto},
                            {$intPrecioBodega},{$id_usuario},{$intEstado})
                    ON DUPLICATE KEY UPDATE
                    idProyecto = {$intProyecto},
                    idTorre = {$intTorre},
                    idNivel = {$intNivel},
                    apartamento = '{$strApartamento}',
                    codigo = '{$intCodigo}', 
                    cuartos = {$intCuarto}, 
                    precio = {$intPrecio}, 
                    iusi_seguro = {$intIusiSeguro}, 
                    sqmts = {$intMts}, 
                    jardin_mts = {$intMtsJardin}, 
                    bodega_mts = {$intMtsBodega}, 
                    parqueo = {$intParqueo}, 
                    parqueo_moto = {$intParqueoMoto}, 
                    bodega_precio  = {$intPrecioBodega},
                    estado =  '{$intEstado}'";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        $comentarioBitacora=$intIdApartamento > 0 ?'agregar_editar_apartamento Se ha editado apartamento '.$strApartamento.' con estado '.$intEstado:'agregar_editar_apartamento Se ha creado apartamento '.$strApartamento.' con estado '.$intEstado;
        insertBitacora($id_usuario,$comentarioBitacora,$conn);
        $title = $intIdApartamento > 0 ? " Editado" : " Guardado";
        if($intIdApartamento==0){
            $strQuery = "SELECT idApartamento 
                        FROM  apartamentos
                        where idProyecto = {$intProyecto} 
                        AND idTorre = {$intTorre}
                        AND idNivel = {$intNivel}
                        AND apartamento = '{$strApartamento}'
                        order by idApartamento desc limit 1 ;";

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
if(isset($_GET['get_lista_apartamentos'])){

    if($strProyectoBuscar!='' && $strProyectoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND dg.proyecto ='{$strProyectoBuscar}' ";
    }
    if($strTorreBuscar!='' && $strTorreBuscar!='Seleccione'){
        $strFechaConsulta.= " AND t.noTorre ='{$strTorreBuscar}' ";
    }
    if($intEstado!=0){
        $strFechaConsulta.= " AND a.estado ='{$intEstado}' ";
    }

    $strQuery = "SELECT idApartamento, if(a.suspendido=0,'Activo','Inactivo') as estado, cea.estado as estadoApto, n.noNivel, dg.proyecto,t.noTorre,a.*
                    FROM  apartamentos a
                    INNER JOIN  torres t ON a.idTorre = t.idTorre
                    INNER JOIN  datosGlobales dg ON idGlobal = a.idProyecto 
                    INNER JOIN  niveles n ON n.idNivel = a.idNivel 
                    INNER JOIN catEstadoApartamento cea ON a.estado = cea.idCatEstado
                    WHERE dg.proyecto in ({$proyectos}) 
                    {$strFechaConsulta}
                    ORDER by proyecto,noTorre,noNivel,apartamento ASC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_apartamentos" => $arr
    );
}
if(isset($_GET['get_apartamento'])){

    $strQuery = "SELECT * 
                    FROM  apartamentos a
                    WHERE IdApartamento = {$intIdApartamento};";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "apartamento" => $arr
    );
}
function insertBitacora($idUsuario, $comentario, $conn)
{
$ip = getRealIP();
    $query ="INSERT INTO bitacora
	(id_bitacora,
	id_usuario,
	fecha,
	comentario,
	ip)
	VALUES
	(NULL,
	'" . $idUsuario . "',
	'" . date('Y-m-d H:i:s') . "',
	'" . $comentario . "',
	'" . $ip . "')";
    $conn->db_query($query);
}
function getRealIP()
{
    if (isset($_SERVER["HTTP_CLIENT_IP"])) {
        return $_SERVER["HTTP_CLIENT_IP"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
        return $_SERVER["HTTP_X_FORWARDED"];
    } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
        return $_SERVER["HTTP_FORWARDED"];
    } else {
        return $_SERVER["REMOTE_ADDR"];
    }
}
$conn->db_close();
echo json_encode($res);
?>
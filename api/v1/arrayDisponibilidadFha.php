<?php

include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";

$conn = new dbClassMysql();
$func = new Functions();

$func->getHeaders();
$res = array(
    "err"=> true,
    "mss"=> "Error 404",
    "mssError" =>""
);
session_name("inmobiliaria");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>"; 
}
$id_usuario=$_SESSION['id_usuario'];
$proyecto=$_POST['proyecto'];
if($proyecto==0){
    $where='';
}else{
    $where=' AND a.idGlobal = '.$proyecto.' ';
}
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
if(isset($_GET['get_tabla_disponibilidad'])){
    $strQuery = "   SELECT a.idGlobal AS id_proyecto, a.proyecto,
                        b.idTorre, b.noTorre, 
                        c.idNivel, c.noNivel,
                        d.apartamento,
                        d.parqueo_externo,
                        (SELECT idCliente FROM enganche e where e.apartamento = d.apartamento order by idCliente Desc limit 1) as idCliente,
                        (SELECT procesoFha FROM agregarCliente ac INNER JOIN enganche e on e.idCliente = ac.idCliente WHERE e.apartamento = d.apartamento order by ac.idCliente Desc limit 1) as procesoFha,
                        (SELECT idEnganche FROM enganche e where e.apartamento = d.apartamento order by idEnganche Desc limit 1) as idEnganche,
                        CASE 
                            WHEN (SELECT procesoFha FROM agregarCliente ac INNER JOIN enganche e on e.idCliente = ac.idCliente WHERE e.apartamento = d.apartamento order by ac.idCliente Desc limit 1) = 'Credito aprobado' THEN '#7CB342'
                            WHEN (SELECT procesoFha FROM agregarCliente ac INNER JOIN enganche e on e.idCliente = ac.idCliente WHERE e.apartamento = d.apartamento order by ac.idCliente Desc limit 1) = 'En Proceso' THEN '#FFEB3B'
                            WHEN (SELECT procesoFha FROM agregarCliente ac INNER JOIN enganche e on e.idCliente = ac.idCliente WHERE e.apartamento = d.apartamento order by ac.idCliente Desc limit 1) = 'Suspendido' THEN '#EF831D'
                            WHEN (SELECT procesoFha FROM agregarCliente ac INNER JOIN enganche e on e.idCliente = ac.idCliente WHERE e.apartamento = d.apartamento order by ac.idCliente Desc limit 1) = 'Tecnica' THEN '#5B79FE'
                            WHEN (SELECT procesoFha FROM agregarCliente ac INNER JOIN enganche e on e.idCliente = ac.idCliente WHERE e.apartamento = d.apartamento order by ac.idCliente Desc limit 1) = 'Resguardo' THEN '#42B399'
                            ELSE '#E64A19'
                        END AS estado
                    FROM datosGlobales a
                    INNER JOIN torres b ON a.idGlobal = b.proyecto
                    INNER JOIN niveles c ON b.idTorre = c.idTorre
                    INNER JOIN apartamentos d ON c.idNivel = d.idNivel

                    WHERE a.proyecto in ({$proyectos}) 
                    AND a.suspendido = 0
                    AND b.suspendido = 0
                    AND c.suspendido = 0
                    {$where}
                    ORDER BY a.idGlobal, b.noTorre, c.noNivel, d.apartamento";
    $qTmp = $conn->db_query($strQuery);

    $apartamentos = array();
    $niveles = array();
    $id_nivel = 0;
    $index_nivel = -1;

    $torres = array();
    $id_torre = 0;
    $index_torre = -1;
    $length = 0;
    $parqueo_externo_total = 0;

    $proyectos = array();
    $id_proyecto = 0;
    $index_proyecto = -1;

    while($rTmp = $conn->db_fetch_object($qTmp)) {

        if ($id_proyecto != $rTmp->id_proyecto) {
            
            $torres = array();
            $id_proyecto = $rTmp->id_proyecto;
            $index_proyecto++;
        }

        if ($id_torre != $rTmp->idTorre) {
            $niveles = array();
            $length = 0;
            $parqueo_externo_total = $rTmp->parqueo_externo;
            $id_torre = $rTmp->idTorre;
            $index_torre++;
        } else {
            $parqueo_externo_total += $rTmp->parqueo_externo;
        }

        if ($id_nivel != $rTmp->idNivel) {             
            $apartamentos = array();
            $id_nivel = $rTmp->idNivel;
            $index_nivel++;
        } else {
            $count= sizeof($apartamentos) + 1;
            $length = $count > $length ? $count : $length; 
        }

        $apartamentos[] = array(
            "name" => $rTmp->apartamento,
            "color" => $rTmp->estado,
            "idCliente" => $rTmp->idCliente,
            "idEnganche" => $rTmp->idEnganche,
            "procesoFha" => $rTmp->procesoFha,
            "parqueo_externo" => $rTmp->parqueo_externo
        );
    
        $niveles[$index_nivel] = array(
            "name" => $rTmp->noNivel,
            "apartamentos" => $apartamentos
        );

        $torres[$index_torre] = array(
            "name" => $rTmp->noTorre,
            "length" => $length,
            "niveles" => $niveles,
            "parqueo_externo_total" => $parqueo_externo_total
        );

        $proyectos[$index_proyecto] = array(
            "nombre" => $rTmp->proyecto,
            "torres" => $torres
        );
    }
    $res = array(
        "err" => false,
        "mss" => $qTmp,
        "proyectos" => $proyectos
    );
}
$conn->db_close();
echo json_encode($res);
?>
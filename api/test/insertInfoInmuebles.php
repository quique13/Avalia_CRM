<?php

include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";

$conn = new dbClassMysql();
$func = new Functions();

$strQuery = "SELECT ac.idCliente,e.proyecto,e.idEnganche,e.parqueosExtras,ac.estado,e.apartamento 
                FROM agregarCliente ac
                INNER JOIN enganche e ON ac.idCliente = e.idCliente
                WHERE estado = 1  
                ORDER BY `e`.`parqueosExtras`  DESC";
//echo $strQuery;
$qTmp = $conn ->db_query($strQuery);
while ($rTmp = $conn->db_fetch_object($qTmp)){
    $strQueryInsert = "INSERT INTO infoInmuebles (idEnganche,noInmueble,tipo,identificacion) 
                                    VALUES({$rTmp->idEnganche},1,'Apartamento','{$rTmp->apartamento}') ";
    $qTmpInsert = $conn ->db_query($strQueryInsert);
    if($rTmp->proyecto=='Marabi'){
        $strQueryInsert = "INSERT INTO infoInmuebles (idEnganche,noInmueble,tipo)
                        VALUES ('{$rTmp->idEnganche}','2','Parqueo') ";
        $qTmpInsert = $conn ->db_query($strQueryInsert);
        if($rTmp->parqueosExtras>0){
           for($x=1;$x<=$rTmp->parqueosExtras;$x++){
               $noInmueble=$x+2;
               $strQueryInsert = "INSERT INTO infoInmuebles (idEnganche,noInmueble,tipo)
               VALUES ('{$rTmp->idEnganche}','{$noInmueble}','Parqueo') ";
               $qTmpInsert = $conn ->db_query($strQueryInsert);
           }
        }
    }

}



?>
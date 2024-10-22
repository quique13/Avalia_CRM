<?php

include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";
date_default_timezone_set('America/Guatemala');

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

$strQueryPago = "SELECT idEnganche,apartamento,enganchePorcMonto,MontoReserva,pagosEnganche, ((enganchePorcMonto-MontoReserva)/pagosEnganche) as cuota 
                    FROM enganche";
$qTmp = $conn ->db_query($strQueryPago);
while($rTmp = $conn->db_fetch_object($qTmp)){

    $strQuery = " UPDATE prograEngancheDetalle set montoReal =  $rTmp->cuota 
                    WHERE idEnganche = $rTmp->idEnganche";
    $conn->db_query($strQuery);
}

$conn->db_close();
echo json_encode($res);
?>
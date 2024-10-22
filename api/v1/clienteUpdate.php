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

$strQueryApto = "SELECT e.idEnganche, idGlobal,e.apartamento,e.descuento_porcentual_monto,(dg.cambioDolar * a.precio) as precio,
                    ((dg.cambioDolar * dg.parqueoExtra)*e.parqueosExtras) as parqueoExtra,((dg.cambioDolar * a.bodega_precio) * bodegasExtras) as bodegaPrecio
                    FROM enganche e
                    INNER JOIN apartamentos a ON e.apartamento = a.apartamento
                    INNER JOIN datosGlobales dg ON a.idProyecto = dg.idGlobal
                    LEFT JOIN pagoFinal pf ON e.idEnganche = pf.idEnganche
                    INNER JOIN agregarCliente ac ON e.idCliente = ac.idCliente AND ac.estado = 0";
$qTmp = $conn ->db_query($strQueryApto);
while($rTmp = $conn->db_fetch_object($qTmp)){

    $totalApartamento = $rTmp->precio + $rTmp->parqueoExtra + $rTmp->bodegaPrecio - $rTmp->descuento_porcentual_monto;
    $totalComisionApartamento = round($totalApartamento * 0.91491245);

    $strQuery = " INSERT INTO apartamentoComisiones (proyecto,apartamento,precioVenta,precioIva,precioTimbres,precioComision,
    iva,timbres,constante,idEnganche) 
    VALUES({$rTmp->idGlobal},'{$rTmp->apartamento}',{$totalApartamento},0,0,{$totalComisionApartamento},12,3,0.91491245,{$rTmp->idEnganche})";
    $conn->db_query($strQuery);
}

$conn->db_close();
echo json_encode($res);
?>
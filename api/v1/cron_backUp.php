<?php
error_reporting(0);
date_default_timezone_set('America/Guatemala');
$res = array(
    "mss"=> "BackUp"
);
//Datos de la base de datos
//$db_host = getenv('MYSQL_HOST', true) ?: getenv('MYSQL_HOST');
$db_host = 'localhost';
//$db_user = getenv('MYSQL_USER', true) ?: getenv('MYSQL_USER');
$db_user = 'CrmAvAlIa';
//$db_pwd  = getenv('MYSQL_PASSWORD', true) ?: getenv('MYSQL_PASSWORD');
$db_pwd  = 'wdJwn5g3fA(f';
//$db_name = getenv('MYSQL_DATABASE', true) ?: getenv('MYSQL_DATABASE'); 
$db_name = 'crm_avalia'; 

$fecha_actual = date("Y-m-d 00:00:00");
$dia_actaul = date("N");
if($dia_actaul == 1){
    $conn=mysqli_connect($db_host,$db_user,$db_pwd,$db_name);
    $strQueryEn = "SELECT count(id_bitacora_backup) FROM bitacora_backup WHERE estado = 1 AND fecha >'$fecha_actual';";

        //echo $strQuery;
        $qTmp = mysqli_query($conn,$strQueryEn);
        $row=mysqli_fetch_row($qTmp);
        $count = $row[0];
}else{
    $count = 1;
}


if($count == 0){
$tables=array();
$sql="SHOW TABLES";
$result=mysqli_query($conn,$sql);

while($row=mysqli_fetch_row($result)){
    $tables[]=$row[0];
}

$backupSQL="";
$query="SHOW CREATE DATABASE crm_avalia";
$result=mysqli_query($conn,$query);
$row=mysqli_fetch_row($result);
$backupSQL.="\n\n".$row[1].";\n\n";
foreach($tables as $table){
    $query="SHOW CREATE TABLE $table";
    $result=mysqli_query($conn,$query);
    $row=mysqli_fetch_row($result);
    $backupSQL.="\n\n".$row[1].";\n\n";

    $query="SELECT * FROM $table";
    $result=mysqli_query($conn,$query);
    
    $columnCount=mysqli_num_fields($result);

    for($i=0;$i<$columnCount;$i++){
        while($row=mysqli_fetch_row($result)){
            $backupSQL.="INSERT INTO $table VALUES(";
            for($j=0;$j<$columnCount;$j++){
                $row[$j]=$row[$j];
                if(isset($row[$j])){
                    $backupSQL.='"'.utf8_encode($row[$j]).'"';
                }else{
                    $backupSQL.='""';
                }
                if($j<($columnCount-1)){
                    $backupSQL.=',';
                }
            }
            $backupSQL.=");\n";
        }
    }
    $backupSQL.="\n";
}
$res = array(
    "mss"=> $db_name."_backup.sql "
);

if(!empty($backupSQL)){
    $backup_file_name=$db_name.'_backup.sql';
    $fileHandler=fopen('../../db/'.$backup_file_name,'w+');
    $number_of_lines=fwrite($fileHandler,$backupSQL);
    fclose($fileHandler);
    $query ="UPDATE bitacora_backup SET estado = 0 WHERE estado = 1;";
    mysqli_query($conn,$query);
}

    $query ="INSERT INTO bitacora_backup
	(id_bitacora_backup,
	fecha,
	url,
	nombre)
	VALUES
	(NULL,
	'" . date('Y-m-d H:i:s') . "',
	'../../db/{$backup_file_name}',
	'{$backup_file_name}')";
    mysqli_query($conn,$query);

}
echo json_encode($res);
?>
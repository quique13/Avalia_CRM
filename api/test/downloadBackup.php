<?php
//Datos de la base de datos
//$db_host = getenv('MYSQL_HOST', true) ?: getenv('MYSQL_HOST');
$db_host = 'localhost';
//$db_user = getenv('MYSQL_USER', true) ?: getenv('MYSQL_USER');
$db_user = 'CrmAvAlIa';
//$db_pwd  = getenv('MYSQL_PASSWORD', true) ?: getenv('MYSQL_PASSWORD');
$db_pwd  = 'wdJwn5g3fA(f';
//$db_name = getenv('MYSQL_DATABASE', true) ?: getenv('MYSQL_DATABASE'); 
$db_name = 'crm_avalia'; 

$conn=mysqli_connect($db_host,$db_user,$db_pwd,$db_name);
if(!empty($_GET['file'])){
    
    $fileName = basename($_GET['file']);
    $filePath = '../../db/'.$fileName;
    if(!empty($fileName) && file_exists($filePath)){
        $query ="INSERT INTO bitacora_backup
        (id_bitacora_backup,
        fecha,
        url,
        nombre)
        VALUES
        (NULL,
        '" . date('Y-m-d H:i:s') . "',
        '../../db/{$fileName}',
        '{$fileName}')";
        mysqli_query($conn,$query);
        // Define headers
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");
        
        // Read the file
        readfile($filePath);
        exit;
    }else{
        echo 'The file does not exist.';
    }
}
?>
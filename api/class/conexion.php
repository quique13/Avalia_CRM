<?php
	
	$host='';
	$user='root';
	$pass='abc123**';
	$db='avalia';

//$conexion = mysqli_connect("172.29.11.26", "pxoc", "gt14OCA2014", "mtwoguat_portal");
$mysqli = new mysqli($host, $user,$pass, $db);
if ($mysqli -> connect_errno) {
	die( "Fallo la conexión a MySQL: (" . $mysqli -> mysqli_connect_errno() 
	. ") " . $mysqli -> mysqli_connect_error());
}
else{
	
 /* $result=$mysqli->query("SELECT * FROM vxwguat_users");
  //printf("La selección devolvió %d filas.\n", $result->num_rows);
  while($row = $result->fetch_array())
  {
  	//echo $row[username]."<br>";
  }*/
}
?>
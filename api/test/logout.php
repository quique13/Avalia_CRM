<?php
session_name("inmobiliaria");
session_start();
$id_usuario=$_SESSION['id_usuario'];
$_SESSION['login']=='no';
$_SESSION = array();
session_destroy();
?>
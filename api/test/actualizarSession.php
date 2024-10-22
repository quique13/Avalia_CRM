<?php
session_start();
echo $_SESSION['id_usuario'].'<br>';
echo date('Y-m-d H:m:i').'<br>';
echo $_SESSION['autentificado'] ;
?>
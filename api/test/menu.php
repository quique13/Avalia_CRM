<?php
  include_once "../class/dbClassMysql.php";
  include_once "../class/functions.php";
  
  $conn = new dbClassMysql();
  $func = new Functions();
  $id_usuario = $_SESSION['id_usuario'];
  $strQuery = "   SELECT CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) as nombre
  FROM  usuarios 
  WHERE id_usuario = {$id_usuario};";

  $qTmp = $conn ->db_query($strQuery);
  $arr = array();
  if($rTmp = $conn->db_fetch_object($qTmp)){
    $nombreUsuarioCompleto = $rTmp->nombre;
  }

?>
<nav class="navbar navbar-expand-lg navbar-light" style="background-color:#94acbc;">
  <a href="./consulta.php" class="navbar-brand">
    <img height="30" src="../img/logo_avalia.png" alt="" />
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
    <? if($_SESSION['id_perfil']!=6){
      ?>
      <li class="nav-item dropdown">
      <input type="hidden" id="usuarioMenu" name="usuarioMenu" value="<?php echo $id_usuario; ?>"  >
      <a style="color:#142544" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Cotizaciones
      </a>
      <div class="dropdown-menu" style="background-color:#94acbc;" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="./cotizaciones.php">Cotizacion</a>
        <a class="dropdown-item" href="./apartamentosDisponibilidad.php">Disponibilidad de apartamentos</a>
        <a class="dropdown-item" href="./apartamentosDisponibilidadFha.php">Apartamentos Proceso FHA</a>
        <?
        if($_SESSION['id_perfil']!=4){
          echo '<a class="dropdown-item" href="./reservaApartamento.php">Reserva Apartamento </a>
          <a class="dropdown-item" href="./getEmailAgradecimiento.php">Email de agradecimiento</a>';
        }
        ?>
      </div>
      </li>
      <?
      if($_SESSION['id_perfil']!=4){
      echo '<li class="nav-item dropdown">
      <a style="color:#142544" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Formalización
      </a>
      <div class="dropdown-menu" style="background-color:#94acbc;" aria-labelledby="navbarDropdownMenuLink">';
        echo '<a class="dropdown-item" href="./agregarCliente.php">Agregar Cliente </a>';
        echo '<a class="dropdown-item" href="./agregarClienteConsulta.php">Consultar Cliente </a>
        <a class="dropdown-item" href="./consulta.php">Cotización Final </a>';

          echo '<a class="dropdown-item" href="./consultaCodeudor.php">Agregar Codeudor </a>';
          $id_perfil = $_SESSION['id_perfil'];
        if($_SESSION['id_perfil']!=5){
          if($id_perfil!=1){
            echo '<a class="dropdown-item" href="./ValidarformalizarEnganche.php">Validar Cotización Final</a>
            <a class="dropdown-item" href="./formalizarEnganche.php">Formalizar Enganche </a>';
          }
        }
      echo '</div>
      </li>'; }?>
      <?
    }
      
      if($_SESSION['id_perfil']!=4 ){
      echo '<li class="nav-item dropdown">
        <a style="color:#142544" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Adjuntos
        </a>
        <div class="dropdown-menu" style="background-color:#94acbc;" aria-labelledby="navbarDropdownMenuLink">
          <a style="color:#142544" class="nav-link" href="./subirAjuntos.php">Adjuntos Cliente</a>
          
        </div>
      </li>';
      }
      if($_SESSION['id_perfil']!=4 && $_SESSION['id_perfil']!=6){
        //if($_SESSION['id_perfil']!=5 ){
          echo'<li class="nav-item dropdown">
          <a style="color:#142544" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            FHA
          </a>
          <div class="dropdown-menu" style="background-color:#94acbc;" aria-labelledby="navbarDropdownMenuLink">
            <a style="color:#142544" class="nav-link" href="./subirAjuntosFha.php">Adjuntos FHA</a>
            <a style="color:#142544" class="nav-link" href="./docsFHAconsulta.php">Info. FHA</a>
            <a style="color:#142544" class="nav-link" href="./inspeccionesFha.php">Inspecciones</a>
          </div>
        </li>';
        //};
        
        echo'<li class="nav-item">
          <a style="color:#142544" class="nav-link" href="./moduloPagos.php">Modulo de Pagos </a>
        </li>';
          $id_perfil = $_SESSION['id_perfil'];
          if($id_perfil!=1 && $id_perfil!=5){
        echo '<li class="nav-item dropdown">
          <a style="color:#142544" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Reportes
          </a>
          <div class="dropdown-menu" style="background-color:#94acbc;" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="./moduloReporteApartamentosDisponibles.php">Disponibilidad Apartamentos</a>
            <a class="dropdown-item" href="./moduloReporteEstadoCuenta.php">Estado de cuenta</a>
            <a class="dropdown-item" href="./moduloReportePagos.php">Reporte de pagos</a>
            <a class="dropdown-item" href="./moduloReporteClienteVenta.php">Reporte de Clientes Venta</a>
            <a class="dropdown-item" href="./moduloReporteClienteVentaImpuestos.php">Reporte Aparatamento Impuestos</a>
          </div>
        </li>';
          }
          $id_perfil = $_SESSION['id_perfil'];
          if($id_perfil!=1 && $id_perfil!=5){
          echo '<li class="nav-item dropdown">
          <a style="color:#142544" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Mantenimiento
          </a>
          <div class="dropdown-menu" style="background-color:#94acbc;" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="./bancos.php">Bancos Pagos</a>
            <a class="dropdown-item" href="./bancosFin.php">Bancos Financiamiento</a>
            <a class="dropdown-item" href="./usuarios.php">Usuarios</a>
            <a class="dropdown-item" href="./proyectos.php">Proyectos</a>
            <a class="dropdown-item" href="./torres.php">Torres</a>
            <a class="dropdown-item" href="./niveles.php">Niveles</a>
            <a class="dropdown-item" href="./apartamentos.php">Apartamentos</a>
            <a class="dropdown-item" href="./tipoComision.php">Tipo Comisión</a>
            <a class="dropdown-item" href="./perfilComision.php">Perfil Comisión</a>
            <a class="dropdown-item" href="./tarifas.php">% Tarifa</a>
            <a class="dropdown-item" href="./backups.php">Bitacora BackUp</a>
          </div>
        </li>';
          }
      } ?>
      <li class="nav-item dropdown">
        <a style="color:#142544" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $nombreUsuarioCompleto.'   '; ?>
        </a>
        <div class="dropdown-menu" style="background-color:#94acbc;" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="javascript:" onClick="logoutNew(1);">Salir</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
<div class="modal fade" id="modal_confirm_menu">
  <div  class="modal-dialog mw-40 w-30" >
    <div class="modal-content" style="height:auto;">
      <div class="modal-header">
                  <h5 class="tittle" > Mensaje</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="body_confirm_menu">
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script type="text/javascript">
//actualizarSession();
function actualizarSession()
{
	if(typeof(refresh_session)!="undefined")
	{
		clearInterval( refresh_session );
		refresh_session = window.setInterval(function() {
				  $('#actualizar_session').load('actualizarSession.php');
			   }, (50000*2));
    }
	else
	{
		refresh_session = window.setInterval(function() {
				  $('#actualizar_session').load('actualizarSession.php');
			   }, (50000*2));
	}
}
  
function logoutNew(int) {
  console.log("salir");
  jQuery.ajax({
		contentType: "application/x-www-form-urlencoded",
		type: "POST",
		data: {},
		dataType: "html",
		url: "./logout.php",
		success: function (response) {
			location.href = "./index.php";
      
		}
	});
  setTimeout(function(){
    console.log("probando el logout");  
    location.reload();
      
		},20000)
  
}
var tiempo = 0;
function ejecutar(){
    setTimeout(function(){
        ejecutar();
    }, 50000)

    tiempo += 1;
    <?php 
      if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
      {
        ?>window.location.href = "index.php"; <?php 
      }else{
        ?>console.log('<p>Me ejecuté a los <?php echo $_SESSION['login'] ?>' + tiempo + ' milisegundos</p>');
      <?php }
    ?>
}
function ejecutarBackUp(){
  var usuarioMenu = $("#usuarioMenu").val();
  $.ajax({
    contentType: "application/x-www-form-urlencoded",
    type: "post",
    dataType: "json",
    data: {},
    cache: false,
    contentType: false,
    processData: false,
		url: "./cron_backUp.php",
    success:function (response){
      if(response.mss != 'BackUp' && usuarioMenu == 19){
        $("#modal_confirm_menu").modal({
        backdrop: 'static',
        keyboard: false,
        show: true
        });

        $("#body_confirm_menu").html('Ya se encuentra disponible el Backup semenal <br><br><div style="text-align:center"><button type="button" class="btn btn-success btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm_menu\').modal(\'hide\');downloadBackUp (\''+response.mss+'\');">Descargar</div>');
      }else{
        console.log(response.mss);
      } 
    },
	});  
}
function downloadBackUp(file){
  window.location.replace("downloadBackup.php?file="+file);
}
//ejecutar();
ejecutarBackUp();
</script>
<div id="actualizar_session" style="display:none"></div>

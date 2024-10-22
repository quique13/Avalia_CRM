<?php
session_name("inmobiliaria");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>"; 
}
$id_usuario=$_SESSION['id_usuario'];
$id_perfil = $_SESSION['id_perfil'];
$disabledGuardar = $id_perfil !=5 ? '' : 'disabled="disabled"';
$arrayProyectos = explode(",",$_SESSION['proyectos']);
$proyectos = '';
$countP=0;
foreach($arrayProyectos as $valor)
{
if($valor=="Marabi")
		$val=1;
	if($valor=="Naos")
		$val=2;
    $countP++;
	$proyectos .= '<option value="'.$val.'" >'.$valor.'</optinon>';
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>AVALIA DESARROLLOS</title>    
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="../css/styles.css" rel="stylesheet">
		<link href="../css/stylesEnganche.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Archivo&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/font-awesome-4.7.0/css/font-awesome.css">
</head>
<?php
include "menu.php";
?>
    <body>
		<script src="../dist/jquery/dist/jquery.js"></script>
		<script src="../dist/jquery/dist/jquery.min.js"></script>
		<script src="../dist/jquery/dist/jquery.min.map"></script>
		<script src="../js/jquery.number.js "></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

		<script src="../libs/cryptoJS/v3.1.2/rollups/aes.js"></script>
		<script src="../libs/cryptoJS/v3.1.2/rollups/md5.js"></script>

		<!-- DOCUMETNOS ADJUNTOS FUNCIONES -->
		<script src="../js/documentos_adjuntos.js"></script>

		<div class="wrapper">	
			<div class="content-wrapper">
				<div class="">
					<section class="content">
						<div class="row">
							<div class="col-md-12">
								<div class="box box-warning">
									<div  class="box-header with-border">
										<div class="col-lg-12 col-md-12" style="text-align:center;margin-bottom:10px;margin-top:10px;" id="headerCatalogo">
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/usersearchicon.png"> Subir Adjuntos</label>
											<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="divAlertPendiente" name="divAlertPendiente">
                								
											</div>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
										<div class="row">
											<div class="col-md-12" id="busquedaUsuarios">
												<form autocomplete="off"  enctype="multipart/form-data"  id="frmBuscarCliente" name="frmBuscarCliente" method="POST">
													<div class="row">	
														<div class="col-11 col-md-12">
															<div class="row">
																<input type="hidden" id="idOcaInfo" name="idOcaInfo">
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<label class="nodpitext">Proyecto:</label>
																	<select class="form-control" name="proyectoBsc" id="proyectoBsc"  onchange="torres(this.value,'torreBsc')">
																		<option value="0" >Seleccione</optinon>
																		<? echo $proyectos ?>
																	</select>
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<label class="nodpitext">Torre/Fase:</label>
																	<select class="form-control" name="torreBsc" id="torreBsc" onchange="niveles(this.value,'nivelBsc')">
																		<option value="0" >Seleccione</optinon>
																	</select>
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<label class="nodpitext">Nivel:</label>
																	<select class="form-control" name="nivelBsc" id="nivelBsc"  onchange="apartamentos(0,this.value,'apartamentoBsc')">
																		<option value="0" >Seleccione</optinon>
																	</select>
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<label class="nodpitext">Apartamento:</label>
																	<select class="form-control" name="apartamentoBsc" id="apartamentoBsc" onchange="">
																		<option value="0" >Seleccione</optinon>
																	</select>
																</div>
																<div class="col-lg-4 col-md-4 col-xs-10" style="text-align:left;margin-bottom:10px;">
																	<label class="nodpitext"></label>
																	<input class=" search form-control" type="" id="datoBuscar" name="datoBuscar" placeholder="Nombre, correo, DPI">	
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<label class="nodpitext">Desde: </label>	
																	<input class="form-control" type="date" id="fechaInicial" name="fechaInicial" value=""  >															
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<label class="nodpitext">Hasta: </label>
																	<input class="form-control" type="date" id="fechaFinal" name="fechaFinal" value=""  >
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<input type="hidden" id="esVendedor" name="esVendedor" value="<?php echo $vendedor; ?>"  >
																	<input type="hidden" id="usuarioVendedor" name="usuarioVendedor" value="<?php echo $id_usuario; ?>"  >
																	<label class="nodpitext">Desistimientos:</label>
																	<select class="form-control" name="desestimiento" id="desestimiento" >
																	<option value="1" >No Mostrar</optinon>
																		<option value="0,1" >Mostrar ambos</optinon>
																		<option value="0" >Mostrar desistimientos</optinon>
																	</select>
																</div>
																<div class="col-lg-1 col-md-1 col-xs-10" style="margin-bottom:10px;">
																	<label  class="nodpitext"  style="color: white">_____</label>
																	<button onclick="buscarCliente()" class="searchf" type="button">Buscar</button>															
																</div>
															</div>	
														</div>	
													</div>		
												</form>		
												<div id="contenedor" class="row" style="height:50vh; overflow-y: auto;overflow-x: hidden">	
													<div class="col-12 col-md-12" style="margin-bottom:10px;"  >
														<div class="row">
															<Label class="results">Resultados</label>
															<div class="table-responsive">
																<table id="resultadoCliente" class="table table-sm table-hover"  style="width:100%">
																	<tr>
																		<th style="width:10%;">Codigo</th>
																		<th style="width:40%;">Cliente</th>
																		<th style="width:15%;">Proyecto</th>
																		<th style="width:25%;">Cotizaciones</th> 
																		<th style="width:10%;">Acciones</th> 
																	</tr>
																</table>
															</div>
														</div>	
													</div>
												</div>								
											</div>
										</div>
									</div>
								</div>
							</div>
						<!-- /.col -->
						</div>
					</section>
					<!-- /.modal -->

					<!-- MODAL DOCUMENTOS ADJUNTOS -->
					<?php require_once("./documentos_adjuntos.php"); ?>


					<!-- <div class="modal fade" id="modalVerAdjuntos">
						<div class="modal-dialog mw-100 w-75">
							<div class="modal-content">
								<div class="modal-header" id="headerVerAdjuntos" style="padding:5px 15px;">
									<h5 class="tittle" >Documentos Adjuntos</h5>
									<button type="button" class="close" aria-label="Close" data-dismiss="modal">
										<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body" id="divVerAdjunt" style="padding:5px 15px;">
								
									<div class="row" id="rows">
										<div class="col-lg-4 col-md-4 col-xs-10" id="divListadoAdjuntos" style="padding:5px 15px;">
											<form autocomplete="off"  enctype="multipart/form-data"  id="frmListaAdjunto" name="frmListaAdjunto" method="POST">
												<Label class="results">Nombre</label>

												<div class="col-lg-12 col-md-12 col-xs-10">
													<label class="nodpitext">Filtro:</label>
													<select class="form-control" name="filtro-adjuntos" id="filtro-adjuntos" placeholder="Seleccione un filtro" onchange="verAdjuntos()">
														
													</select>
												</div>
												<hr/>

												<div class="table-responsive" >
													<table id="resultadoAdjuntos" class="table table-sm table-hover"   style="width:100%;">
													</table>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10">
													<label class="draganddroptexttitle" for="mail">Subir archivos aquí:</label>
													<input class="draganddrop" type="file" id="fliesAdjuntos[]" name="fliesAdjuntos[]" placeholder="Arrastra y suelta aquí " accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" multiple>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="text-align:center;padding:5px">
													<button onclick="guardarAdjuntos()" class="guardar" type="button">Adjuntar</button>
												</div>
											</form>
											
										</div>
										<div class="col-lg-8 col-md-8 col-xs-10" id="divVerAdjuntos" style="padding:5px 15px;">
										</div>
									</div>
								</div>								
							</div>
						</div>
					</div> -->
					<!-- /.modal -->
					<div class="modal fade" id="modal_confirm">
						<div  class="modal-dialog mw-40 w-30" >
							<div class="modal-content" style="height:auto;">
								<div class="modal-header">
                    				<h5 class="tittle" > Mensaje</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="body_confirm">
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>

					<!-- CAMBIO DE CONTRASEñA POR DEFECTO -->
					<div class="modal fade" id="modal-cambio-contrasenia">
						<div class="modal-dialog mw-50 w-25 " style="height:70%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > 	
										Cambio de contrase­ña
									</h5>									
									<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarClienteCoVer" style="padding:5px 15px;" >
									<div class="col-xs-12" id="loading-change"></div>
									<form action="javascript:;" method="post" name="form-login" id="form-login" class="login-form">
										<div class="form-group d-flex">
											<input type="password" class="form-control" placeholder="Contraseña Anterior"  name="old-password" id="old-password">
										</div>
										<div class="form-group d-flex">
											<input type="password" class="form-control" placeholder="Nueva Contraseña"  name="new-password" id="new-password">
										</div>
										<div class="form-group">
											<button type="submit" class="form-control btn btn-primary submit px-3" onclick="cambioContrasenia()">Cambiar contraseña</button>
										</div>
									</form>									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
			<script type="text/javascript">

			<?php
				if (intval($_SESSION['password_default']) == 1) {
					echo    "$('#modal-cambio-contrasenia').modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});"; 
				}
			?>

				function cambioContrasenia() {
					
					const old_password = $("#old-password").val();
					const new_password = $("#new-password").val();

					console.log("old_password", old_password, "new_password", new_password)

					if(old_password == '' || new_password == '')
					{
						$("#loading-change").html('<div class="alert alert-danger">Todos los parametros son obligatorios</div>');
						setTimeout(function(){
							$("#loading-change").html('');
						},5000)
						return true;
					}
					
					const old_hast = CryptoJS.MD5(old_password);
					const new_hast = CryptoJS.MD5(new_password);

					let formData = new FormData;
					formData.append("old_password", old_hast);
					formData.append("new_password", new_hast);

					$.ajax({
						url: "./usuario.php?cambio_contrasenia=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend: function () {
							$("#loading-change").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success: function (response) {
							if (response.err) {
								$("#loading-change").html('<div class="alert alert-danger"><b>Error</b> '+response.mss+'</div>');
								setTimeout(function () {
									$("#loading-change").html('');
								}, 4000);
							}
							else {
								location.reload();
							}
						},
						error: function () {
							$("#loading-change").html('<div class="alert alert-danger"><b>Error</b> Intente nuevamente</div>');
						}
					});					
				}

				function selectPagosEnganche(value,input){
					output="";
					for (let i =1; i <= parseInt(value); i++) {
						//console.log(i);
						output += ' <option value="'+i+'">'+i+' Meses</option>';
					}
					
					var option = document.getElementById(input);
					for (let i = option.options.length; i >= 1; i--) {
						option.remove(i);
					}
					$('#'+input).append(output);
				}
				function fncTipoCliente(){
					var radios = document.getElementsByName('tipoCliente');
					for (var i = 0, length = radios.length; i < length; i++) {
						if (radios[i].checked) {
							if(radios[i].value=='juridico'){
								document.querySelector('#lblDireccion').innerText = 'Dirección Fiscal S.A:';
								document.querySelector('#lblNumeroDpi').innerText = 'Dpi Representante legal:';
								document.querySelector('#lblNit').innerText = 'Nit S.A:';
								document.getElementById("divNombreSa").style.display = "";
								document.getElementById("divRtu").style.display = "";
								document.getElementById("divRepresentanteLegal").style.display = "";
								document.getElementById("divPatenteEmpresa").style.display = "";
								document.getElementById("divPatenteSociedad").style.display = "";
								document.getElementById("divPrimerNombre").style.display = "none";
								document.getElementById("divSegundoNombre").style.display = "none";
								document.getElementById("divTercerNombre").style.display = "none";
								document.getElementById("divPrimerApellido").style.display = "none";
								document.getElementById("divSegundoApellido").style.display = "none";
								document.getElementById("divApellidoCasada").style.display = "none";
							}else if(radios[i].value=='individual'){
								document.querySelector('#lblDireccion').innerText = 'Dirección';
								document.querySelector('#lblNumeroDpi').innerText = 'Número de DPI :';
								document.querySelector('#lblNit').innerText = 'Nit:';
								document.getElementById("divNombreSa").style.display = "none";
								document.getElementById("divRtu").style.display = "none";
								document.getElementById("divRepresentanteLegal").style.display = "none";
								document.getElementById("divPatenteEmpresa").style.display = "none";
								document.getElementById("divPatenteSociedad").style.display = "none";
								document.getElementById("divPrimerNombre").style.display = "";
								document.getElementById("divSegundoNombre").style.display = "";
								document.getElementById("divTercerNombre").style.display = "";
								document.getElementById("divPrimerApellido").style.display = "";
								document.getElementById("divSegundoApellido").style.display = "";
								document.getElementById("divApellidoCasada").style.display = "";
							}
							// do whatever you want with the checked radio
							//alert(radios[i].value);

							// only one radio can be logically checked, don't check the rest
							break;
						}
					}
				}
				function buscarCliente(){
					//console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarCliente"));
					formData.append("proyectoBscTxt", document.getElementById("proyectoBsc").options[document.getElementById("proyectoBsc").selectedIndex].text);
					formData.append("torreBscTxt", document.getElementById("torreBsc").options[document.getElementById("torreBsc").selectedIndex].text);
					formData.append("nivelBscTxt", document.getElementById("nivelBsc").options[document.getElementById("nivelBsc").selectedIndex].text);
					formData.append("apartamentoBscTxt", document.getElementById("apartamentoBsc").options[document.getElementById("apartamentoBsc").selectedIndex].text);
					$.ajax({
						url: "./cliente.php?get_concidencia_cliente=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							$.each(response.cotizaciones,function(i,e) {
								//console.log(e.user_name);
								if(e.creado=='si'){
									var check='<i class="fa fa-check-square-o"></i>';
								}else
								{
									var check="";
								}
								output += '<tr onCLick=""><td>'+e.codigo+'</td><td>'+e.client_name+' '+check+'</td><td>'+e.proyecto+'</td><td>'+e.apartamentoEnganche+'</td><td><button onclick="verAdjuntosInicial('+e.id+'), getFiltroAdjuntos()" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ></td></tr>';
							});
							////console.log(output);
							var tb = document.getElementById('resultadoCliente');
							while(tb.rows.length > 1) {
								tb.deleteRow(1);
							}
							$('#resultadoCliente').append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function niveles(torre,input,valueInput){
					////console.log(proyecto+" - "+input+" - "+valueInput);
					var formData = new FormData;
					formData.append("torreEng", torre);
					
					
					$.ajax({
						url: "./cliente.php?get_niveles_proyecto_com=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							var select='';
							output += ' <option value="">Seleccione</option>';
							$.each(response.levels,function(i,e) {
								if(valueInput==e.idNivel){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.idNivel+'">'+e.level+'</option>';
							});
							////console.log(output);
							var option = document.getElementById(input);
							for (let i = option.options.length; i >= 0; i--) {
								option.remove(i);
							}
							$('#'+input).append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function torres(proyecto,input,valueInput){
					////console.log(proyecto+" - "+input+" - "+valueInput);
					// if(proyecto==1){
					// 	console.log("proyecto");
					// 	document.getElementById("CocinaEng").disabled = true;
					// }else{
					// 	document.getElementById("CocinaEng").disabled = false;
					// }
					var formData = new FormData;
					formData.append("proyectoCliente", proyecto);
					$.ajax({
						url: "./cliente.php?get_torres_proyecto=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							var select='';
							output += ' <option value="">Seleccione</option>';
							$.each(response.torres,function(i,e) {
								if(valueInput==e.idTorre){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.idTorre+'">'+e.torre+'</option>';
							});
							////console.log(output);
							var option = document.getElementById(input);
							for (let i = option.options.length; i >= 0; i--) {
								option.remove(i);
							}
							$('#'+input).append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function getDepartamentos(pais,input,valueInput){
					//console.log("funcion buscar niveles");
					var formData = new FormData;
					formData.append("pais", 1);
					
					$.ajax({
						url: "./cliente.php?get_departamentos=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							var select='';
							output += ' <option value="">Seleccione</option>';
							$.each(response.departamentos,function(i,e) {
								if(valueInput==e.id_depto){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.id_depto+'">'+e.nombre_depto+'</option>';
							});
							////console.log(output);
							var option = document.getElementById(input);
							for (let i = option.options.length; i >= 0; i--) {
								option.remove(i);
							}
							$('#'+input).append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function getEngancheDetalle(idEnganche){
					//console.log("funcion buscar pagos");
					var formData = new FormData;
					formData.append("idEnganche", idEnganche);
					
					$.ajax({
						url: "./cliente.php?get_progra_detalle=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output='';
							var select='';
							var montoEngancheTotal=0;
							$.each(response.detallePagos,function(i,e) {
								montoEngancheTotal= e.montoEnganche;
								output+="<tr>";
								output+="<td >"+e.noPago+"<input id=\"noPago_"+e.noPago+"\" name=\"noPago[]\" type=\"hidden\" value=\""+e.noPago+"\" readonly=\"readonly\" ></td>";
								output+="<td><input onChange=\"pagoEspecial("+e.montoEnganche+")\" id=\"chk_"+e.noPago+"\" name=\"chk[]\" type=\"checkbox\" class=\"form-check-input\"> <label class=\"form-check-label\" for=\"exampleCheck1\">Especial</label></td>";
								output+="<td><input onkeyup=\"recalculoPagoEspecial("+e.montoEnganche+")\" id=\"cuota_"+e.noPago+"\" name=\"cuotas[]\" type=\"number\" value=\""+e.monto+"\" readonly=\"readonly\"></td>";
								output+="<td><input id=\"date_"+e.noPago+"\" name=\"date[]\" type=\"date\" value=\""+e.fechaPago+"\" readonly=\"readonly\" ></td>";
								output+="</tr>";
								//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
								console.log(output);
							});
							console.log(output);
							output+="<tr>";				
							output+="<td colspan=\"4\">"+"<h5 class=\"tittle\" >Total "+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoEngancheTotal)+"</h5>"+ "<input id=\"totalEnganche\" name=\"totalEnganche\" type=\"hidden\" value=\""+montoEngancheTotal+"\"></td>";
							output+="</tr>";
							var tb = document.getElementById('resultadoCuotas');
							while(tb.rows.length > 1) {
								tb.deleteRow(1);
							}
							$('#resultadoCuotas').append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function getNacionalidad(input,valueInput){
					//console.log("funcion buscar niveles");
					var formData = new FormData;
					$.ajax({
						url: "./cliente.php?get_nacionalidad=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							var select='';
							output += ' <option value="">Seleccione</option>';
							$.each(response.nacionalidades,function(i,e) {
								if(valueInput==e.id_pais){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.id_pais+'">'+e.pais+'</option>';
							});
							//console.log(output);
							var option = document.getElementById(input);
							for (let i = option.options.length; i >= 0; i--) {
								option.remove(i);
							}
							$('#'+input).append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function getVendedor(input,valueInput){
					//console.log("funcion buscar niveles");
					var formData = new FormData;
					$.ajax({
						url: "./cliente.php?get_vendedores=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							var select='';
							output += ' <option value="">Seleccione</option>';
							$.each(response.listado_usuarios,function(i,e) {
								if(valueInput==e.id_usuario){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.id_usuario+'">'+e.nombreVendedor+'</option>';
							});
							//console.log(output);
							var option = document.getElementById(input);
							for (let i = option.options.length; i >= 0; i--) {
								option.remove(i);
							}
							$('#'+input).append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function getMunicipios(depto,input,valueInput){
					//console.log("funcion buscar niveles");
					var formData = new FormData;
					formData.append("depto", depto);
					
					$.ajax({
						url: "./cliente.php?get_municipios=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							var select='';
							output += ' <option value="">Seleccione</option>';
							$.each(response.municipios,function(i,e) {
								if(valueInput==e.id_muni){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.id_muni+'">'+e.nombre_muni+'</option>';
							});
							//console.log(output);
							var option = document.getElementById(input);
							for (let i = option.options.length; i >= 0; i--) {
								option.remove(i);
							}
							$('#'+input).append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function apartamentos(proyecto,value,input,valueInput){
					//console.log("funcion buscar niveles");
					var formData = new FormData;
					formData.append("nivelEng", value);
					formData.append("apartamentoSelect", valueInput);
					$.ajax({
						url: "./cliente.php?get_apartamentos_proyecto_com=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							var select='';
							output += ' <option value="">Seleccione</option>';
							$.each(response.apartamentos,function(i,e) {
								if(valueInput==e.idApartamento){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.apartamento+'">'+e.apartamento+'</option>';
							});
							//console.log(output);
							var option = document.getElementById(input);
							for (let i = option.options.length; i >= 0; i--) {
								option.remove(i);
							}
							$('#'+input).append(output);
							
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function datosApartamento(apartamento='',cot=0){
					//console.log("funcion buscar niveles");
					var formData = new FormData;
					formData.append("apartamentoEng", apartamento);
					$.ajax({
						url: "./cliente.php?get_apartamento_detalle=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							$.each(response.detalle,function(i,e) {
								if(cot==0){
									$("#mt2Eng").val(e.sqmts);
									$("#habitacionesEng").val(e.cuartos);
									$("#bodegaEng").val(e.bodega_mts);
									$("#parqueoMotoEng").val(e.parqueo_moto);
									$("#parqueoEng").val(e.parqueo);
									$("#jardinMt2Eng").val(e.jardin_mts);
									if(e.price!=''){
										$("#precioTotalEng").val('Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.price));
									}
									$("#bodegaPrecioEng").val(e.bodega_precio);
									$("#cocinaTipoAEng").val(e.cocinaTipoA);
									$("#cocinaTipoBEng").val(e.cocinaTipoB);
									$("#iusiEng").val(e.iusi);
									$("#parqueoExtraEng").val(e.parqueoExtra);
									$("#parqueoExtraMotoEng").val(e.parqueoExtraMoto);
									$("#porcentajeEngacheEng").val(e.porcentajeEnganche);
									$("#porcentajeFacturacionEng").val(e.porcentajeFacturacion);
									$("#seguroEng").val(e.seguro);
									$("#ReservaEng").val(e.montoReserva);
									$("#rateEng").val(e.rate);
									$("#engancheEng").val(e.porcentajeEnganche);
									$("#montoReservaEng").val(e.montoReserva);
									porcentaje = parseFloat(e.porcentajeEnganche);
								}else if(cot==1){
									$("#mt2Cot").val(e.sqmts);
									$("#habitacionesCot").val(e.cuartos);
									$("#bodegaCot").val(e.bodega_mts);
									$("#parqueoMotoCot").val(e.parqueo_moto);
									$("#parqueoCot").val(e.parqueo);
									$("#jardinMt2Cot").val(e.jardin_mts);
									if(e.price!=''){
										$("#precioTotalCot").val('Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.price));
									}
									$("#bodegaPrecioCot").val(e.bodega_precio);
									$("#cocinaTipoACot").val(e.cocinaTipoA);
									$("#cocinaTipoBCot").val(e.cocinaTipoB);
									$("#iusiCot").val(e.iusi);
									$("#parqueoExtraCot").val(e.parqueoExtra);
									$("#parqueoExtraMotoCot").val(e.parqueoExtraMoto);
									$("#porcentajeEngacheCot").val(e.porcentajeEnganche);
									$("#porcentajeFacturacionCot").val(e.porcentajeFacturacion);
									$("#seguroCot").val(e.seguro);
									$("#ReservaCot").val(e.montoReserva);
									$("#rateCot").val(e.rate);
								}
								

							});
							//setDescuento('porcentaje', 0);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function datosVendedor(vendedor='',cot=0){
					//console.log("funcion buscar niveles");
					var formData = new FormData;
					formData.append("idVendedor", vendedor);
					$.ajax({
						url: "./cliente.php?get_vendedor_detalle=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							$.each(response.detalle,function(i,e) {
								console.log(cot)
								if(cot==0){
									$("#correoVendedorEng").val(e.mail);
									$("#telefonoVendedorEng").val(e.telefono);
								}else if(cot==1){
									$("#correoVendedorCot").val(e.mail);
									$("#telefonoVendedorCot").val(e.telefono);
								}
								

							});
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function buscarClienteUnico(idCotizacion,proyecto,clientName){
					//console.log("funcion buscar cliente unico");
					//var formData = new FormData(document.getElementById("frmBuscarCliente"));
					var formData = new FormData;
					formData.append("idCotizacion", idCotizacion);
					formData.append("proyectoCliente", proyecto);
					formData.append("clientName", clientName);
					$.ajax({
						url: "./cliente.php?get_concidencia_cliente_unico=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var ul='<ul class="nav nav-tabs" role="tablist">';
							var div='';
							var active = 'active';
							var count = 0;
							$.each(response.info,function(i,e) {
								//console.log(e.client_name);
								//Info Apartamento, Vendedor y CLiente
								$("#nombreClienteInfo").val(e.client_name);
								$("#codigoInfo").val(e.codigo);
								$("#emailClienteInfo").val(e.client_mail);
								$("#nivelInfo").val(e.nivel);
								$("#apartamentoInfo").val(e.apartamento);
								$("#tamanioInfo").val(e.sqmts);
								$("#habitacionInfo").val(e.cuartos);
								$("#areaBodegaInfo").val(e.bodega_mts);
								$("#parqueoMotoInfo").val(e.parqueo_moto);
								$("#parqueoCarroInfo").val(e.parqueo);
								$("#areaJardinInfo").val(e.jardin_mts);
								if(e.price!=''){
									$("#precioTotalInfo").val('Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.price));
								}
								$("#proyectoInfo").val(proyecto);
								$("#idInfo").val(idCliente);
								$("#idOcaInfo").val(e.idCliente);
								$("#ProyectoCl").val(proyecto);
								niveles(proyecto,'nivelCl',e.nivel);
								apartamentos(proyecto,e.nivel,'apartamentoCl',e.apartamento);
								getDepartamentos(1,'depto',e.departamento);
								getMunicipios(e.departamento,'municipio',e.municipio);
								getNacionalidad('nacionalidadCl',e.Nacionalidad);
								$("#telefonoClienteInfo").val(e.telefono);
								$("#telefonoFijo").val(e.telefonoFijo);
								$("#nitCl").val(e.nit);
								$("#dpiClienteInfo").val(e.numeroDpi);
								$("#vencimientoDpiClienteInfo").val(e.fechaVencimientoDpi);
								$("#direccionClienteInfo").val(e.direccion);
								$("#fechaNacimientoCl").val(e.fechaNacimiento);
								$("#estadoCivilCl").val(e.estadoCivil);
								$("#profesionCl").val(e.profesion);
								$("#dependientesCl").val(e.noDependientes);
								$("#empresaLaboraCl").val(e.empresaLabora);
								$("#direccionEmpresaCl").val(e.direccionEmpresa);
								$("#telefonoReferencia").val(e.telefonoReferencia);
								$("#puestoEmpresaCl").val(e.puestoLabora);
								$("#salarioMensualCl").val(e.salarioMensual);
								$("#otrosIngresosCl").val(e.otrosIngresos);
								$("#montoOtrosIngresosCl").val(e.montoOtrosIngresos);
								$("#nombreSa").val(e.nombre_sa);
								$("#rtu").val(e.rtu);
								$("#representanteLegal").val(e.representanteLegal);
								$("#patenteEmpresa").val(e.patenteEmpresa);
								$("#patenteSociedad").val(e.patenteSociedad);
								
								if(e.creditoFHA=='si'){
									document.getElementById('siCl').checked = true;
									console.log("credito fha "+e.creditoFHA);
								}else if(e.creditoFHA=='no'){
									document.getElementById('noCl').checked = true;
									console.log("credito fha "+e.creditoFHA);
								}
								if(e.tipoCliente=='individual'){
									document.getElementById('individual').checked = true;
									fncTipoCliente();
								}else if(e.tipoCliente=='juridico'){
									document.getElementById('juridico').checked = true;
									fncTipoCliente();
								}
								$("#fechaEmisionDpiCl").val(e.fechaEmisionDpi);
								//Informacioón Venta
								$("#descuentoInfo").val(e.descuento_porcentual);
								$("#engancheInfo").val(e.enganchePorc);
								$("#pagosEngancheInfo").val(e.pagosEnganche);
								$("#plazoFinanciamientoInfo").val(e.plazoFinanciamiento);
								$("#parqueoClienteInfo").val(e.parqueosExtras);
								$("#bodegaClienteInfo").val(e.bodegasExtras);
								$("#montoReservaClienteInfo").val(e.MontoReserva);

								// //Campos Enganche
								$("#nombreClienteEng").val(e.client_name);
								$("#correoEng").val(e.client_mail);
								$("#telefonoEng").val(e.telefono);
								$("#nitEng").val(e.nit);
								$("#dpiEng").val(e.numeroDpi);
								$("#fechaEmisionDpiEng").val(e.fechaEmisionDpi);
								$("#direccionEng").val(e.direccion);
								$("#nacionalidadEng").val(e.NacionalidadNombre);
								$("#fechaNacimientoEng").val(e.fechaNacimiento);
								$("#estadoCivilEng").val(e.estadoCivil);
								$("#empresaLaboraEng").val(e.empresaLabora);
								$("#puestoEmpresaEng").val(e.puestoLabora);
								$("#direccionEmpresaEng").val(e.direccionEmpresa);
								$("#salarioMensualEng").val(e.salarioMensual);
								$("#otrosIngresosEng").val(e.otrosIngresos);
								$("#montoOtrosIngresosEng").val(e.montoOtrosIngresos);

								// Campos Cotización
								$("#nombreClienteCot").val(e.client_name);
								$("#correoCot").val(e.client_mail);
								$("#telefonoCot").val(e.telefono);
								$("#nitCot").val(e.nit);
								$("#dpiCot").val(e.numeroDpi);
								$("#fechaEmisionDpiCot").val(e.fechaEmisionDpi);
								$("#direccionCot").val(e.direccion);
								$("#nacionalidadCot").val(e.NacionalidadNombre);
								$("#fechaNacimientoCot").val(e.fechaNacimiento);
								$("#estadoCivilCot").val(e.estadoCivil);
								$("#empresaLaboraCot").val(e.empresaLabora);
								$("#puestoEmpresaCot").val(e.puestoLabora);
								$("#direccionEmpresaCot").val(e.direccionEmpresa);
								$("#salarioMensualCot").val(e.salarioMensual);
								$("#otrosIngresosCot").val(e.otrosIngresos);
								$("#montoOtrosIngresosEng").val(e.montoOtrosIngresos);
								if(count>0){
									active='';
								}
								if(e.idEnganche<=0){
									ul='';
									div='';
								}else{
									ul =ul + '<li class="nav-item"><a class="nav-link '+active+'" data-toggle="tab" href="#'+e.apartamento+'">'+e.apartamento+'</a></li>';
									div += '<div id="'+e.apartamento+'" class="container tab-pane '+active+'">';
									div += '<div class="secinfo">';
									div += '<div class="row" >';
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;margin-top:10px;"><h3 style="" class="infoventtittle"><img class="saleicon" src="../img/apartment_info.png" alt="Cliente"> Información de apartamento</h3></div>';
									div += '<div class="col-lg-6 col-md-6 col-xs-10" >'
									div += '</div>'	
									// div += '<div class="col-lg-6 col-md-6 col-xs-10" style="text-align:right;margin-bottom:10px;" >'
									// div += '	<div class="row" >'
									// div += '		<div class="col-lg-4 col-md-4 col-xs-10" style="text-align:right;margin-bottom:10px;">'
									// div += '		</div>'
									// div += '		<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									// div += '			<img class="addclient" src="../img/more.gif" alt="agregar cliente" onclick="agregarCodeudor('+e.idCliente+','+e.idEnganche+')" ><span onclick="agregarCodeudor('+e.idCliente+','+e.idEnganche+')" class="addclienttext" >Agregar Codeudor </span>'
									// div += '		</div>'
									// div += '		<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									// div += '			<img class="addclient" src="../img/usersearchicon.png" alt="ver cliente" onclick="verCodeudor('+e.idCliente+','+e.idEnganche+')" ><span onclick="verCodeudor('+e.idCliente+','+e.idEnganche+')" class="addclienttext" >Ver Codeudor</span>'
									// div += '		</div>'
									// div += '	</div>'
									// div += '</div>'	
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;text-align:right;" ></div>';						
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;"><label class="niveltittle">Nivel:</label></div>';
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;"><input id="nivelInfo" value ="'+e.nivel+'" class="form-control" readonly></div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;"><label class="aptotittle">Apartamento:</label></div>';
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;"><input id="apartamentoInfo" value ="'+e.apartamento+'" class="form-control" readonly></div>';
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;"><label class="sizetittle">Tamaño en mt2:</label></div>'
									div +='<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;"><input id="tamanioInfo" value ="'+e.sqmts+'" class="form-control" readonly></div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="habtittle">Habitaciones:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="habitacionInfo"  value ="'+e.cuartos+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="areabodetittle">Área de bodega en mt2:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="areaBodegaInfo" value ="'+e.bodega_mts+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="parqmototittle">Parqueo de moto:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="parqueoMotoInfo" value ="'+e.parqueo_moto+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="parqcarrotittle">Parqueo de carro:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="parqueoCarroInfo" value ="'+e.parqueo+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="areajardintittle">Área de Jardin en mt2:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="areaJardinInfo" value ="'+e.jardin_mts+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="preciototaltittle">Precio total (GtQ):</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div +='<input id="precioTotalInfo" value ="'+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.price)+'" class="form-control" readonly>'
									div += '</div>'
									div += '</div></div>';
									div += '<div class="secinfo">';
									div += '<div class="row" >';
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;;margin-top:10px;">'
									div += '<h3 style="padding-left:0px;" class="infsaletittle"><img class="saleicon" src="../img/sale_info.png" alt="Cliente"> Información de venta</h3>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="descperceptittle">Descuento porcentual %: </label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="descuentoInfo" value="'+e.descuento_porcentual+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="engagetittle">Enganche: </label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="engancheInfo" value="'+e.enganchePorc+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="engagepaymentstittle">Pagos de enganche: </label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="pagosEngancheInfo" value="'+e.pagosEnganche+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="plazofin">Plazo de financimiento: </label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="plazoFinanciamientoInfo" value="'+e.plazoFinanciamiento+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="parqueoextra">Parqueo extra:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="parqueoClienteInfo" value="'+e.parqueosExtras+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="bodegaextra">Bodegas extra:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="bodegaClienteInfo" value="'+e.bodegasExtras+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="montoreservatittle">Monto reserva:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="montoReservaClienteInfo" value="'+e.MontoReserva+'" class="form-control" readonly>'
									div += '</div>'
									div += '</div></div>';
									div += '<div class="secinfo">';
									div += '<div class="row" >';
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;margin-top:10px;">'
									div += '<h3 style="padding-left:0px;" class="infovendtittle"><img class="iconselltittle" src="../img/client_icon.png" alt="Cliente"> Información del vendedor</h3>'
									div += '</div>'														
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="sellernametittle">Nombre del vendedor:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="vendedorInfo" value="'+e.nombreVendedor+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="sellermailtittle">Correo electronico:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="emailVendedorInfo" value="'+e.mailVendedor+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="sellerphonetittle">Teléfono:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="telefonoVendedorInfo" value="'+e.telefonoVendedor+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" >'
									div += '	<div class="row" >'
									div += '		<div class="col-lg-12 col-md-12 col-xs-10" style="text-align:center;margin-bottom:10px;">'
									div += '			<button name="btnEnganche" onclick="verEnganche('+e.idEnganche+')" class="inf" type="button">Ver Cotización Final</button>'															
									div += '		</div>'
									div += '	</div>'
									div += '</div>'								
									div += '</div></div>';
									div += '</div>';
									count ++;
								}
									
							});
							if(ul!=''){
								ul = ul + '</ul><div class="tab-content" id="renderDatos" name="renderDatos"></div>';
							}
							console.log(ul);
							$("#renderList").html(ul);
							$("#renderDatos").html(div);
							//document.getElementById("btnEnganche").disabled = false;
							$("#modalInfoCliente").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function verEnganche(idEnganche){
					var formData = new FormData;
					formData.append("idEnganche", idEnganche);
					$.ajax({
						url: "./cliente.php?get_enganche=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							$.each(response.info,function(i,e) {
								//Campos Enganche
								$("#idEnganche").val(e.idEnganche);
								$("#ProyectoEng").val(e.idGlobal);
								$("#idOcaEnganche").val(e.idCliente);
								torres(e.idGlobal,'torreEng',e.idTorre)
								niveles(e.idTorre,'nivelEng',e.idNivel);
								apartamentos(e.idGlobal,e.idNivel,'apartamentoEng',e.idApartamento);
								datosApartamento(e.apartamento,0);
								getEngancheDetalle(e.idEnganche)		
								$("#telefonoVendedorEng").val('');
								$("#correoVendedorEng").val('');
								getVendedor('nombreVendedorEng',e.idVendedor);
								datosVendedor(e.idVendedor,0)
								$("#mesesEnganche").val(e.pagosEnganche);
								selectPagosEnganche(e.pagosEnganche,'pagosEngancheEng')
								$("#nombreClienteEng").val(e.client_name);
								$("#correoEng").val(e.client_mail);
								$("#telefonoEng").val(e.telefono);
								$("#nitEng").val(e.nit);
								$("#dpiEng").val(e.numeroDpi);
								$("#fechaEmisionDpiEng").val(e.fechaEmisionDpi);
								$("#direccionEng").val(e.direccion);
								$("#nacionalidadEng").val(e.NacionalidadNombre);
								$("#fechaNacimientoEng").val(e.fechaNacimiento);
								$("#estadoCivilEng").val(e.estadoCivil);
								$("#CocinaEng").val(e.cocina);
								$("#empresaLaboraEng").val(e.empresaLabora);
								$("#puestoEmpresaEng").val(e.puestoLabora);
								$("#direccionEmpresaEng").val(e.direccionEmpresa);
								$("#salarioMensualEng").val(e.salarioMensual);
								$("#otrosIngresosEng").val(e.otrosIngresos);
								$("#montoOtrosIngresosEng").val(e.montoOtrosIngresos);
								$("#engancheEng").val(e.enganchePorc);
								$("#engancheMontoEng").val(e.enganchePorcMonto);
								$("#descuentoPorcentualEng").val(e.descuento_porcentual);
								$("#descuentoPorcentualMontoEng").val(e.descuento_porcentual_monto);
								$("#parqueosExtraEng").val(e.parqueosExtras);
								$("#bodegaExtraEng").val(e.bodegasExtras);
								$("#montoReservaEng").val(e.MontoReserva);
								$("#fechaPagoReservaEng").val(e.fechaPagoReserva);
								$("#plazoFinanciamientoEng").val(e.plazoFinanciamiento);
								$("#fechaPagoInicialEng").val(e.fechaPagoInicial);
								$("#pagosEngancheEng").val(e.pagosEnganche);
								$("#pagoPromesaEng").val(e.pagoPromesa);
								$("#descuentoEng").val(e.descuentoPorc);
								$("#formaPagoEng").val(e.formaPago);
								$("#noDepositoReservaEng").val(e.noDepositoReserva);
								$("#bancoChequeReservaEng").val(e.bancoChequeReserva);
								$("#bancoDepositoReservaEng").val(e.bancoDepositoReserva);
								$("#noChequeReservaEng").val(e.noChequeReserva);
								$("#observacionesEng").val(e.observaciones);
								console.log("function setDescuento "+"(porcentaje, "+e.descuento_porcentual+")");
								setDescuento('porcentaje', e.descuento_porcentual);	
							});
							$("#modalAgregarEnganche").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function Alertpendiente()
				{
					////console.log("dfdf");
					$("#divAlertPendiente").html('<div class="alert alert-danger">Función pendiente, se está trabajando</div>');
								setTimeout(function(){
									$("#divAlertPendiente").html('');
								},5000)
				}
				function guardarCodeudor(idCodeudor=''){
					//console.log("funcion guardar cliente");
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					// if($("#primerNombreCo").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Primer Nombre <br>'
					// }
					// if($("#primerApellidoCo").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Primer Apellido <br>'
					// }
					// if($("#segundoApellidoCo").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Segundo Apellido <br>'
					// }
					// if($("#correoCo").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Correo eléctronico <br>'
					// }else{
					// 	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
					// 	if(!emailReg.test($("#correoCo").val())){
					// 		error++;
					// 		msjError =msjError+ '*Correo electrónico invalido <br>';
					// 		//return false;	
					// 	}
					// }
					// if($("#telefonoCo").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Télefono <br>'
					// }
					// if($("#direccionCo").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Dirección <br>'
					// }
					// if($("#numeroDpiCo").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Número de DPI <br>'
					// }else{
					// 	var cui = $("#numeroDpiCo").val();
					// 	////console.log('CUI: '+ cui);
					// 	var cuiRegExp = /^[0-9]{4}\s?[0-9]{5}\s?[0-9]{4}$/;

					// 	if (!cuiRegExp.test(cui)) {
					// 		//console.log("CUI con formato inválido");
					// 		//callback('CUI con formato inválido');
					// 		error++;
					// 		msjError =msjError+ '*Número de DPI inválido <br>';
					// 		//return false;
					// 	}

					// 	cui = cui.replace(/\s/, '');
					// 	var depto = parseInt(cui.substring(9, 11), 10);
					// 	var muni = parseInt(cui.substring(11, 13));
					// 	var numero = cui.substring(0, 8);
					// 	var verificador = parseInt(cui.substring(8, 9));
					// 	// //console.log('depto: '+depto);
					// 	// //console.log('muni: '+muni);
					// 	// //console.log('numero: '+numero);
					// 	// //console.log('verificador: '+verificador);
					// 	// Se asume que la codificación de Municipios y
					// 	// departamentos es la misma que esta publicada en
					// 	// http://goo.gl/EsxN1a

					// 	// Listado de municipios actualizado segun:
					// 	// http://goo.gl/QLNglm

					// 	// Este listado contiene la cantidad de municipios
					// 	// existentes en cada departamento para poder
					// 	// determinar el código máximo aceptado por cada
					// 	// uno de los departamentos.
					// 	var munisPorDepto = [
					// 		/* 01 - Guatemala tiene:      */ 17 /* municipios. */,
					// 		/* 02 - El Progreso tiene:    */  8 /* municipios. */,
					// 		/* 03 - Sacatepéquez tiene:   */ 16 /* municipios. */,
					// 		/* 04 - Chimaltenango tiene:  */ 16 /* municipios. */,
					// 		/* 05 - Escuintla tiene:      */ 13 /* municipios. */,
					// 		/* 06 - Santa Rosa tiene:     */ 14 /* municipios. */,
					// 		/* 07 - Sololá tiene:         */ 19 /* municipios. */,
					// 		/* 08 - Totonicapán tiene:    */  8 /* municipios. */,
					// 		/* 09 - Quetzaltenango tiene: */ 24 /* municipios. */,
					// 		/* 10 - Suchitepéquez tiene:  */ 21 /* municipios. */,
					// 		/* 11 - Retalhuleu tiene:     */  9 /* municipios. */,
					// 		/* 12 - San Marcos tiene:     */ 30 /* municipios. */,
					// 		/* 13 - Huehuetenango tiene:  */ 32 /* municipios. */,
					// 		/* 14 - Quiché tiene:         */ 21 /* municipios. */,
					// 		/* 15 - Baja Verapaz tiene:   */  8 /* municipios. */,
					// 		/* 16 - Alta Verapaz tiene:   */ 17 /* municipios. */,
					// 		/* 17 - Petén tiene:          */ 14 /* municipios. */,
					// 		/* 18 - Izabal tiene:         */  5 /* municipios. */,
					// 		/* 19 - Zacapa tiene:         */ 11 /* municipios. */,
					// 		/* 20 - Chiquimula tiene:     */ 11 /* municipios. */,
					// 		/* 21 - Jalapa tiene:         */  7 /* municipios. */,
					// 		/* 22 - Jutiapa tiene:        */ 17 /* municipios. */
					// 	];

					// 	if (depto === 0 || muni === 0)
					// 	{
					// 		//console.log("CUI con código de municipio o departamento inválido.");
					// 		//callback("CUI con código de municipio o departamento inválido.");
					// 		error++;
					// 		msjError =msjError+ '*Número de DPI con código de municipio o departamento inválido. <br>';
					// 		//return false;
					// 	}

					// 	if (depto > munisPorDepto.length)
					// 	{
					// 		//console.log("CUI con código de departamento inválido.");
					// 		//callback("CUI con código de departamento inválido.");
					// 		error++;
					// 		msjError =msjError+ '*Número de DPI con código de departamento inválido. <br>';
					// 		//return false;
					// 	}

					// 	if (muni > munisPorDepto[depto -1])
					// 	{
					// 		//console.log("CUI con código de municipio inválido.");
					// 		//callback("CUI con código de municipio inválido.");
					// 		error++;
					// 		msjError =msjError+ '*Número de DPI con código de municipio inválido. <br>';
					// 		//return false;
					// 	}
						
					// 	// Se verifica el correlativo con base
					// 	// en el algoritmo del complemento 11.
					// 	var total = 0;

					// 	for (var i = 0; i < numero.length; i++)
					// 	{
					// 		total += numero[i] * (i + 2);
					// 	}
					// 	var modulo = (total % 11);
					// 	////console.log("CUI con módulo: " + modulo);
					// 	if(modulo === verificador){
					// 		////console.log(modulo +'==='+ verificador);
					// 	}else{
					// 		error++;
					// 		msjError =msjError+ '*Numeración no valida de DPI <br>';
					// 	}
					// 	if (/\s/.test(cui) || cui.includes("-")) {
					// 		//callback('No se aceptan espacios ni guiones.');
					// 		error++;
					// 		msjError =msjError+ '*Número de DPI No se aceptan espacios ni guiones. <br>';
					// 	}						
					// }
					// if($("#nitCo").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*NIT <br>'
					// }else{
					// 	var nit = $("#nitCo").val();
					// 	//console.log("NIT validación");
					// 	var nitRegExp = new RegExp('^[0-9]+(-?[0-9kK])?$');
					// 	if (!nitRegExp.test(nit)) {
					// 		////console.log("NIT inválido");
					// 		//callback('CUI con formato inválido');
					// 		error++;
					// 		msjError =msjError+ '*NIT inválido <br>';
					// 		//return false;
					// 	}
					// 	nit = nit.replace(/-/, '');
					// 	var lastChar = nit.length - 1;
					// 	var number = nit.substring(0, lastChar);
					// 	var expectedCheker = nit.substring(lastChar, lastChar + 1).toLowerCase();
					// 	var factor = number.length + 1;
					// 	var total = 0;

					// 	for (var i = 0; i < number.length; i++) {
					// 		var character = number.substring(i, i + 1);
					// 		var digit = parseInt(character, 10);

					// 		total += (digit * factor);
					// 		factor = factor - 1;
					// 	}
					// 	var modulus = (11 - (total % 11)) % 11;
					// 	var computedChecker = (modulus == 10 ? "k" : modulus.toString());

					// 	if(expectedCheker === computedChecker){
					// 		////console.log(expectedCheker+'==='+computedChecker) ;
					// 	}else{
					// 		error++;
					// 		msjError =msjError+ '*NIT inválido <br>';
					// 	}
					// }
	
					// if($("#fechaVencimientoDpiCo").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Fecha de Vencimiento DPI <br>'
					// }else{
						
					// 	var fecha_mayor = $("#fechaVencimientoDpiCo").val();
					// 	var partes_fecha_mayor = fecha_mayor.split('/');
					// 	var fecha_mayor_numero= new Date (partes_fecha_mayor[2]+'/'+partes_fecha_mayor[1]+'/'+partes_fecha_mayor[0]).setHours(0,0,0,0);
					// 	//console.log('fecha mayor' +fecha_mayor_numero);

					// 	var fecha_menor = $("#fechaHoyCo").val();
					// 	var partes_fecha_menor = fecha_menor.split('/');
					// 	var fecha_menor_numero= new Date (partes_fecha_menor[2]+'/'+partes_fecha_menor[1]+'/'+partes_fecha_menor[0]).setHours(0,0,0,0);
					// 	//console.log('fecha menor '+fecha_menor_numero);
					// 	if(fecha_mayor_numero.valueOf()<=fecha_menor_numero.valueOf()){
					// 		error++;
					// 		msjError =msjError+ '*Fecha de Vencimiento DPI No puede ser menor a fecha actual <br>'
					// 	}
					// }
					
					if(error==0){
						var formDataCliente = new FormData(document.getElementById("frmAgregarClienteCo"+idCodeudor+""));
						$.ajax({
							url: "./cliente.php?agregar_editar_codeudor=true",
							type: "post",
							dataType: "json",
							data: formDataCliente,
							cache: false,
							contentType: false,
							processData: false,
							beforeSend:function (){
								$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
							},
							success:function (response){
								$("#modal_confirm").modal({
									backdrop: 'static',
									keyboard: false,
									show: true
								});
								if (response.err === true) {
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
								}
								else {
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarCliente();buscarClienteUnico('+response.id+','+response.proyecto+','+response.clientName+');">Aceptar</div>');
								}					
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarClienteCo').animate({scrollTop:0}, 'fast');
						$("#divAlertClienteCo").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertClienteCo").html('');
							},5000)
					}
					
				}
				function guardarEnganche(){
					//console.log("funcion guardar cliente");
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					
					if($("#nombreClienteEng").val()==''){
						error++;
						msjError =msjError+ '*Primer Nombre <br>'
					}
					if($("#correoEng").val()==''){
						error++;
						msjError =msjError+ '*Correo eléctronico <br>'
					}
					if($("#telefonoEng").val()==''){
						error++;
						msjError =msjError+ '*Télefono <br>'
					}
					if($("#dpiEng").val()==''){
						error++;
						msjError =msjError+ '*Número de DPI <br>'
					}else{
						var cui = $("#dpiEng").val();
						////console.log('CUI: '+ cui);
						var cuiRegExp = /^[0-9]{4}\s?[0-9]{5}\s?[0-9]{4}$/;

						if (!cuiRegExp.test(cui)) {
							//console.log("CUI con formato inválido");
							//callback('CUI con formato inválido');
							error++;
							msjError =msjError+ '*Número de DPI inválido <br>';
							//return false;
						}

						cui = cui.replace(/\s/, '');
						var depto = parseInt(cui.substring(9, 11), 10);
						var muni = parseInt(cui.substring(11, 13));
						var numero = cui.substring(0, 8);
						var verificador = parseInt(cui.substring(8, 9));
						// //console.log('depto: '+depto);
						// //console.log('muni: '+muni);
						// //console.log('numero: '+numero);
						// //console.log('verificador: '+verificador);
						// Se asume que la codificación de Municipios y
						// departamentos es la misma que esta publicada en
						// http://goo.gl/EsxN1a

						// Listado de municipios actualizado segun:
						// http://goo.gl/QLNglm

						// Este listado contiene la cantidad de municipios
						// existentes en cada departamento para poder
						// determinar el código máximo aceptado por cada
						// uno de los departamentos.
						var munisPorDepto = [
							/* 01 - Guatemala tiene:      */ 17 /* municipios. */,
							/* 02 - El Progreso tiene:    */  8 /* municipios. */,
							/* 03 - Sacatepéquez tiene:   */ 16 /* municipios. */,
							/* 04 - Chimaltenango tiene:  */ 16 /* municipios. */,
							/* 05 - Escuintla tiene:      */ 13 /* municipios. */,
							/* 06 - Santa Rosa tiene:     */ 14 /* municipios. */,
							/* 07 - Sololá tiene:         */ 19 /* municipios. */,
							/* 08 - Totonicapán tiene:    */  8 /* municipios. */,
							/* 09 - Quetzaltenango tiene: */ 24 /* municipios. */,
							/* 10 - Suchitepéquez tiene:  */ 21 /* municipios. */,
							/* 11 - Retalhuleu tiene:     */  9 /* municipios. */,
							/* 12 - San Marcos tiene:     */ 30 /* municipios. */,
							/* 13 - Huehuetenango tiene:  */ 32 /* municipios. */,
							/* 14 - Quiché tiene:         */ 21 /* municipios. */,
							/* 15 - Baja Verapaz tiene:   */  8 /* municipios. */,
							/* 16 - Alta Verapaz tiene:   */ 17 /* municipios. */,
							/* 17 - Petén tiene:          */ 14 /* municipios. */,
							/* 18 - Izabal tiene:         */  5 /* municipios. */,
							/* 19 - Zacapa tiene:         */ 11 /* municipios. */,
							/* 20 - Chiquimula tiene:     */ 11 /* municipios. */,
							/* 21 - Jalapa tiene:         */  7 /* municipios. */,
							/* 22 - Jutiapa tiene:        */ 17 /* municipios. */
						];

						if (depto === 0 || muni === 0)
						{
							//console.log("CUI con código de municipio o departamento inválido.");
							//callback("CUI con código de municipio o departamento inválido.");
							error++;
							msjError =msjError+ '*Número de DPI con código de municipio o departamento inválido. <br>';
							//return false;
						}

						if (depto > munisPorDepto.length)
						{
							//console.log("CUI con código de departamento inválido.");
							//callback("CUI con código de departamento inválido.");
							error++;
							msjError =msjError+ '*Número de DPI con código de departamento inválido. <br>';
							//return false;
						}

						if (muni > munisPorDepto[depto -1])
						{
							//console.log("CUI con código de municipio inválido.");
							//callback("CUI con código de municipio inválido.");
							error++;
							msjError =msjError+ '*Número de DPI con código de municipio inválido. <br>';
							//return false;
						}

						// Se verifica el correlativo con base
						// en el algoritmo del complemento 11.
						var total = 0;

						for (var i = 0; i < numero.length; i++)
						{
							total += numero[i] * (i + 2);
						}

						var modulo = (total % 11);

						////console.log("CUI con módulo: " + modulo);

						if (/\s/.test(cui) || cui.includes("-")) {
							//callback('No se aceptan espacios ni guiones.');
							error++;
							msjError =msjError+ '*Número de DPI No se aceptan espacios ni guiones. <br>';
						}
					}
					if(error>=0){
						var formDataEnganche = new FormData(document.getElementById("frmAgregarEnganche"));
						formDataEnganche.append("proyectoCliente",$("#proyectoEnganche").val() );
						formDataEnganche.append("idCliente", $("#idEnganche").val());
						formDataEnganche.append("idOcaCliente", $("#idOcaEnganche").val());
						formDataEnganche.append("txtProyecto", document.getElementById("ProyectoEng").options[document.getElementById("ProyectoEng").selectedIndex].text);
						formDataEnganche.append("txtTorre", document.getElementById("torreEng").options[document.getElementById("torreEng").selectedIndex].text);
						formDataEnganche.append("txtNivel", document.getElementById("nivelEng").options[document.getElementById("nivelEng").selectedIndex].text);
						formDataEnganche.append("txtApartamento", document.getElementById("apartamentoEng").options[document.getElementById("apartamentoEng").selectedIndex].text);
						$.ajax({
							url: "./cliente.php?agregar_editar_enganche=true",
							type: "post",
							dataType: "json",
							data: formDataEnganche,
							cache: false,
							contentType: false,
							processData: false,
							beforeSend:function (){
								$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
							},
							success:function (response){
								$("#modal_confirm").modal({
									backdrop: 'static',
									keyboard: false,
									show: true
								});
								if (response.err === true) {
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
								}
								else {
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarCliente();buscarClienteUnico('+response.id+',\'\',\'\');verEnganche('+response.idEnganche+')">Aceptar</div>');
								}			
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarEnganche').animate({scrollTop:0}, 'fast');
						$("#divAlertEnganche").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertEnganche").html('');
							},5000)
					}
					
				}
				
				function calculoCuotas(){
					//guardarEnganche();
					var formDataEnganche = new FormData(document.getElementById("frmAgregarEnganche"));
					formDataEnganche.append("proyectoCliente",$("#proyectoEnganche").val() );
					formDataEnganche.append("idCliente", $("#idEnganche").val());
					formDataEnganche.append("idOcaCliente", $("#idOcaEnganche").val());
					formDataEnganche.append("idOcaCliente", $("#idOcaEnganche").val());
					$.ajax({
						url: "./cliente.php?cuotas_info=true",
						type: "post",
						dataType: "json",
						data: formDataEnganche,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var output;
							$.each(response.datosEnganche,function(i,e) {
								var precioQ = parseInt(e.precioQ);
								var totalParqueo = parseInt(e.totalParqueo);
								var totalQ = precioQ + totalParqueo;
								var descuento = totalQ * (parseInt(e.descuento_porcentual)/100);
								var totalQD = parseInt(totalQ) - parseInt(descuento);
								//console.log(totalQD);
								var enganche = (parseInt(e.enganchePorc)/100);
								var engancheMonto = totalQD * enganche;
								//console.log (engancheMonto);
								var totalEnganche = Math.round(engancheMonto - parseInt(e.montoReserva) - parseInt(e.pagoPromesa));
								cuota = totalEnganche/ parseInt(e.pagosEnganche);
								for(i=0;i<e.pagosEnganche;i++){
									var no=i+1;
									f = e.fechaPagoInicial; // Acá la fecha leída del INPUT
									vec = f.split('-'); // Parsea y pasa a un vector
									var fecha = new Date(vec[0], vec[1], vec[2]); // crea el Date
									fecha.setMonth(fecha.getMonth()+(i-1)); // Hace el cálculo
									res = fecha.getFullYear()+'-'+("0" + (fecha.getMonth()+1)).slice(-2)+'-'+("0" + fecha.getDate()).slice(-2); // carga el resultado
									output+="<tr>";
									output+="<td >"+no+"<input id=\"noPago_"+no+"\" name=\"noPago[]\" type=\"hidden\" value=\""+no+"\" readonly=\"readonly\" ></td>";
									output+="<td><input onChange=\"pagoEspecial("+totalEnganche+")\" id=\"chk_"+no+"\" name=\"chk[]\" type=\"checkbox\" class=\"form-check-input\"> <label class=\"form-check-label\" for=\"exampleCheck1\">Especial</label></td>";
									output+="<td><input onkeyup=\"recalculoPagoEspecial("+totalEnganche+")\" id=\"cuota_"+no+"\" name=\"cuotas[]\" type=\"number\" value=\""+cuota.toFixed(2)+"\" readonly=\"readonly\"></td>";
									output+="<td><input id=\"date_"+no+"\" name=\"date[]\" type=\"date\" value=\""+res+"\" readonly=\"readonly\" ></td>";
									output+="</tr>";
								}
								output+="<tr>";
								
								output+="<td colspan=\"4\">"+"<h5 class=\"tittle\" >Total "+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(totalEnganche)+"</h5>"+ "<input id=\"totalEnganche\" name=\"totalEnganche\" type=\"hidden\" value=\""+totalEnganche+"\"></td>";
								output+="</tr>";
								//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
								console.log(output);
								var tb = document.getElementById('resultadoCuotas');
								while(tb.rows.length > 1) {
									tb.deleteRow(1);
								}
							});
							
							$('#resultadoCuotas').append(output);				
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function calculoCuotasCotizacion(){
					output='';
					outputEng='';
					var bodegaExtra = $("#bodegaExtraCot").val()!=''?parseInt($("#bodegaExtraCot").val()):0
					var parqueoExtra = $("#parqueosExtraCot").val()!=''?parseInt($("#parqueosExtraCot").val()):0
					var porcentajeEnganche = parseFloat($("#engancheMontoCot").val());
					var montoEnganche = parseFloat($("#engancheMontoCot").val());
					var montoDescuento = parseFloat($("#descuentoPorcentualMontoCot").val());
					var montoReserva = parseFloat($("#montoReservaCot").val());
					var mesesEnganche = parseFloat($("#pagosEngancheCot").val());
					var tasaInteres = parseFloat($("#rateCot").val());
					var plazoFinanciamiento = parseFloat($("#plazoFinanciamientoCot").val());
					var facturacionPorcentaje = parseFloat($("#porcentajeFacturacionCot").val());
					var ventaAccion = 100-facturacionPorcentaje;
					var tasaIusi = parseFloat($("#iusiCot").val()) / 10;
					var tasaSeguro = parseFloat($("#seguroCot").val());
					var totalParqueo = parqueoExtra * parseFloat($("#parqueoExtraCot").val())
					console.log( parseInt($("#parqueosExtraCot").val()) +'*'+ parseFloat($("#parqueoExtraCot").val()) )
					var totalBodega = bodegaExtra * parseFloat($("#bodegaPrecioCot").val())
					console.log( parseInt($("#bodegaExtraCot").val()) +'*'+ parseFloat($("#bodegaPrecioCot").val()) )
					var precioTotal = $("#precioTotalCot").val();
						precioTotal = parseFloat( precioTotal.replace(/[Q,]/g,'') );
						precioTotal = precioTotal + totalParqueo + totalBodega; 
					var precioNeto = precioTotal - montoDescuento;
					var cuotaEnganche = montoEnganche/mesesEnganche;
					var pagoContraEntrega = parseFloat(precioNeto) - montoEnganche;
					var im = tasaInteres/ 12 / 100;
					console.log(tasaInteres+'/ 12 / 100')
					var im2 = Math.pow(	(1 + parseFloat(im)	), - (12 * plazoFinanciamiento ) );
					var cuotaCredito = (pagoContraEntrega * parseFloat(im)) / (1- parseFloat(im2) ) ;
					console.log('('+pagoContraEntrega +'*'+ parseFloat(im) +') / (1-'+ parseFloat(im2)+' )');
					var cuotaSeguro = ((precioNeto*tasaSeguro)/100)/12;
					var ventaPorcionFactura = (precioNeto * facturacionPorcentaje)/100
					var cuotaIusi = ((ventaPorcionFactura * tasaIusi)/100)/12
					var cuotaMensual = cuotaIusi + cuotaSeguro + cuotaCredito; 
					var ingresoFamiliar = cuotaMensual/0.35; 
		
					
					output+="<tr>";
						output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaMensual.toFixed(2)))+"</td>";
						output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaIusi.toFixed(2)))+"</td>";
						output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaSeguro.toFixed(2)))+"</td>";
						output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaCredito.toFixed(2)))+"</td>";
					output+="</tr>";;
					//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
					console.log(output);
					var tb = document.getElementById('resultadoCuota');
					while(tb.rows.length > 1) {
						tb.deleteRow(1);
					}
					outputEng+="<tr>";
					outputEng+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(montoEnganche.toFixed(2)))+"</td>";
					outputEng+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(cuotaEnganche.toFixed(2))+"</td>";
					outputEng+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(ingresoFamiliar.toFixed(2)))+"</td>";
					outputEng+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(pagoContraEntrega.toFixed(2)))+"</td>";
					outputEng+="</tr>";;
					//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
					console.log(outputEng);
					var tb = document.getElementById('resultadoEnganche');
					while(tb.rows.length > 1) {
						tb.deleteRow(1);
					}
					$('#resultadoCuota').append(output);
					$('#resultadoEnganche').append(outputEng);
				}
				function agregarCliente(){
					getDepartamentos(1,'depto',0);
					getMunicipios(0,'municipio',0);
					getNacionalidad('nacionalidadCl',0)
					document.getElementById("frmAgregarCliente").reset();
					$("#modalAgregarCliente").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					//console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarCliente"));
					$("#proyectoCliente").val("");
					$("#idCliente").val(0);
					$("#idOcaCliente").val(0);			
				}
				function agregarCodeudor(idCliente,idEnganche){
					getDepartamentos(1,'deptoCo',0);
					getMunicipios(0,'municipioCo',0);
					getNacionalidad('nacionalidadCo',0)
					document.getElementById("frmAgregarClienteCo").reset();
					$("#modalAgregarClienteCo").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					//console.log("funcion buscar cliente");
					$("#idClienteCo").val(idCliente);
					$("#idEngancheCo").val(idEnganche);
					$("#idCodeudor").val('');
				}
				function verCodeudor(idCliente,idEnganche){

					//console.log("funcion buscar cliente");
					$("#idClienteCo").val(idCliente);
					$("#idEngancheCo").val(idEnganche);
					var formData = new FormData;
					formData.append("idClienteCo", idCliente);
					formData.append("idEngancheCo", idEnganche);
					$.ajax({
						url: "./cliente.php?get_codeudores=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							var html ='<div class="secinfo" id="renderListCo" name="">';
							var ul='<ul class="nav nav-tabs" role="tablist">';
							var div='';
							var active = 'active';
							var count = 0;
							$.each(response.info,function(i,e) {

								var selectedEstadoCivil = '';
								var checkedFha = '';
								if(count>0){
									active='';
								}
								if(e.idEnganche<=0){
									ul='';
									div='';
								}else{
									count ++;
									ul =ul + '<li class="nav-item"><a class="nav-link '+active+'" data-toggle="tab" href="#'+e.idCodeudor+'">Codeudor '+count+'</a></li>';
									div += '<div id="'+e.idCodeudor+'" class="container tab-pane '+active+'">';
									div += '<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarClienteCo'+e.idCodeudor+'" name="frmAgregarClienteCo'+e.idCodeudor+'" method="POST">';

									
									div += '<div class="row" >';
									div += '<input type="hidden" id="idClienteCo" name="idClienteCo" value="'+e.idCliente+'">';
									div += '<input type="hidden" id="idEngancheCo" name="idEngancheCo" value="'+e.idEnganche+'">'
									div += '<input type="hidden" id="idCodeudor" name="idCodeudor" value="'+e.idCodeudor+'">	'
									div += '<div id="divAlertClienteCo" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '</div>'													
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Cliente:</label>'
									div += '<div class="row" >'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="primerNombreCo" name="primerNombreCo" placeHolder="Primer Nombre" value="'+e.primerNombre+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="segundoNombreCo" name="segundoNombreCo" placeHolder="Segundo Nombre" value="'+e.segundoNombre+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="primerApellidoCo" name="primerApellidoCo" placeHolder="Primer apellido" value="'+e.primerApellido+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="segundoApellidoCo" name="segundoApellidoCo" placeHolder="Segundo Apellido" value="'+e.segundoApellido+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="tercerNombreCo" name="tercerNombreCo" placeHolder="Tercer Nombre" value="'+e.tercerNombre+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="apellidoCasadaCo" name="apellidoCasadaCo" placeHolder="Apellido Casada" value="'+e.apellidoCasada+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Correo electrónico:</label>'
									div += '<input type="text" id="correoCo" name="correoCo" value="'+e.client_mail+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Télefono Fijo:</label>'
									div += '<input  type="text" id="telefonoFijoCo" name="telefonoFijoCo" value="'+e.telefonoFijo+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Télefono Celular:</label>'
									div += '<input  type="text" id="telefonoCo" name="telefonoCo" value="'+e.telefono+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección:</label>'
									div += '<textarea class="form-control" id="direccionCo" name="direccionCo" rows="2">'+e.direccion+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Nit:</label>'
									div += '<input type="text" id="nitCo" name="nitCo" value="'+e.nit+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Número de DPI:</label>'
									div += '<input type="text" id="numeroDpiCo" name="numeroDpiCo" value="'+e.numeroDpi+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Departamento:</label>'
									div += '<select class="form-control" name="deptoCo" id="deptoCo"   onchange="getMunicipios(this.value,\'municipioCo\',\'\')">'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Municipio:</label>'
									div += '<select class="form-control" name="municipioCo" id="municipioCo"  onchange="">'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Fecha Emisión DPI:</label>'
									div += '<input type="date" id="fechaEmisionDpiCo" name="fechaEmisionDpiCo"  value="'+e.fechaEmisionDpi+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Fecha vencimiento DPI:</label>'
									div += '<input id="fechaVencimientoDpiCo" name="fechaVencimientoDpiCo" value="'+e.fechaVencimientoDpi+'" type="date" class="form-control">'
									div += '<input id="fechaHoyCo" name="fechaHoyCo" type="hidden" class="form-control" value="<?php echo date("d/m/Y") ?>">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Nacionalidad:</label>'
									div += '<select class="form-control" name="nacionalidadCo"   id="nacionalidadCo" onchange="">'
									div += '</select>'
									div += '</div>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">'
									div += '<div class="row" >'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Fecha de nacimiento:</label>'
									div += '<input type="date" id="fechaNacimientoCo" value="'+e.fechaNacimiento+'" name="fechaNacimientoCo" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Estado Civil:</label>'
									div += '<select class="form-control" name="estadoCivilCo" id="estadoCivilCo" onchange="">'
									div += '<option value="" >Seleccione</optinon>'
									selectedEstadoCivil ="Soltero"== e.estadoCivil?"selected":""
									div += '<option value="Soltero" '+selectedEstadoCivil+' >Soltero(a)</optinon>'
									selectedEstadoCivil ="Casado"== e.estadoCivil?"selected":""
									div += '<option value="Casado" '+selectedEstadoCivil+'>Casado(a)</optinon>'
									selectedEstadoCivil ="Viudo"== e.estadoCivil?"selected":""
									div += '<option value="Viudo" '+selectedEstadoCivil+'>Viudo(a)</optinon>'
									selectedEstadoCivil ="Divorciado"== e.estadoCivil?"selected":""
									div += '<option value="Divorciado" '+selectedEstadoCivil+'>Divorciado(a)</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Profesión:</label>'
									div += '<input type="text" id="profesionCo" name="profesionCo" value="'+e.profesion+'"  class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">No. de dependientes:</label>'
									div += '<input type="number" id="dependientesCo" name="dependientesCo" value="'+e.noDependientes+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Ha tenido tramite FHA:</label>'
									div += '<div class=" form-check form-check-inline"  style="">'
									checkedFha =e.creditoFHA=="si"?"checked":""
									div += '<input class="form-check-input" type="radio" name="fhaCo" id="si" value="si" '+checkedFha+'>'
									div += '<label class="form-check-label" for="">Si</label>'
									div += '</div>'
									checkedFha =e.creditoFHA=="no"?"checked":""
									div += '<div class="form-check form-check-inline"  style="">'
									div += '<input class="form-check-input" type="radio" name="fhaCo" id="no" value="no" '+checkedFha+'>'
									div += '<label class="form-check-label">No</label>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Empresa donde labora:</label>'
									div += '<input type="text" id="empresaLaboraCo" name="empresaLaboraCo" value="'+e.empresaLabora+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Dirección de empresa:</label>'
									div += '<textarea class="form-control" id="direccionEmpresaCo" name="direccionEmpresaCo" rows="2">'+e.direccionEmpresa+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Télefono de Referencia:</label>'
									div += '<input  type="text" id="telefonoReferenciaCo" name="telefonoReferenciaCo" value="'+e.telefonoReferencia+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Puesto en Empresa:</label>'
									div += '<input type="text" id="puestoEmpresaCo" name="puestoEmpresaCo" value="'+e.puestoLabora+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Salario mensual:</label>'
									div += '<input type="text" id="salarioMensualCo" name="salarioMensualCo"  value="'+e.salarioMensual+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Otros ingresos:</label>'
									div += '<input type="text" id="montoOtrosIngresosCo" name="montoOtrosIngresosCo" value="'+e.montoOtrosIngresos+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Descripción Otros ingresos:</label>'
									div += '<input type="text" id="otrosIngresosCo" name="otrosIngresosCo" value="'+e.otrosIngresos+'" class="form-control">'
									div += '</div>'
									// div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									// div += '<label class="draganddroptexttitle" for="mail">DPI y Recibo de servicios:</label>'
									// div += '<input class="draganddrop" type="file" id="fliesDpiReciboCo[]" name="fliesDpiReciboCo[]" placeholder="Arrastra y suelta aquí " accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" multiple>'
									// div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Agregar Adjuntos</button>'
									div += '</div>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">'
									div += '<button onclick="guardarCodeudor('+e.idCodeudor+')" class="guardar" type="button">Guardar</button>'
									div += '</div>'
									div += '</div>'
									div += '</form>';
									div += '</div>';
									getDepartamentos(1,'deptoCo',e.departamento);
									getMunicipios(e.departamento,'municipioCo',e.municipio);
									getNacionalidad('nacionalidadCo',e.Nacionalidad);
								}
									
							});
								$("#modalAgregarClienteCoVer").modal({
									backdrop: 'static',
									keyboard: false,
									show: true
								});
								if(ul!=''){
									ul = ul + '</ul><div class="tab-content" id="renderDatosCo" name="renderDatos"></div>';
								}
								console.log(ul);
								html +='</div>';
								$("#bodyAgregarClienteCoVer").html(html);
								$("#renderListCo").html(ul);
								$("#renderDatosCo").html(div);
								$.each(response.info,function(i,e) {
									count ++;
									getDepartamentos(1,'deptoCo',e.departamento);
									getMunicipios(e.departamento,'municipioCo',e.municipio);
									getNacionalidad('nacionalidadCo',e.Nacionalidad);		
								});
								$("#salarioMensualCo").number( true, 2 );
								$("#montoOtrosIngresosCo").number( true, 2 );
								//document.getElementById("btnEnganche").disabled = false;
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				
				}
				function guardarCliente(){
					//console.log("funcion guardar cliente");
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					if($("#ProyectoCl").val()==''){
						error++;
						msjError =msjError+ '*Proyecto <br>'
					}
					if($("#nivelCl").val()==''){
						error++;
						msjError =msjError+ '*Nivel <br>'
					}
					if($("#apartamentoCl").val()==''){
						error++;
						msjError =msjError+ '*Apartamento <br>'
					}
					if($("#primerNombre").val()==''){
						error++;
						msjError =msjError+ '*Primer Nombre <br>'
					}
					if($("#primerApellido").val()==''){
						error++;
						msjError =msjError+ '*Primer Apellido <br>'
					}
					if($("#segundoApellido").val()==''){
						error++;
						msjError =msjError+ '*Segundo Apellido <br>'
					}
					if($("#correo").val()==''){
						error++;
						msjError =msjError+ '*Correo eléctronico <br>'
					}else{
						var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
						if(!emailReg.test($("#correo").val())){
							error++;
							msjError =msjError+ '*Correo electrónico invalido <br>';
							//return false;	
						}
					}
					if($("#telefono").val()==''){
						error++;
						msjError =msjError+ '*Télefono <br>'
					}
					if($("#direccion").val()==''){
						error++;
						msjError =msjError+ '*Dirección <br>'
					}
					if($("#numeroDpi").val()==''){
						error++;
						msjError =msjError+ '*Número de DPI <br>'
					}else{
						var cui = $("#numeroDpi").val();
						////console.log('CUI: '+ cui);
						var cuiRegExp = /^[0-9]{4}\s?[0-9]{5}\s?[0-9]{4}$/;

						if (!cuiRegExp.test(cui)) {
							//console.log("CUI con formato inválido");
							//callback('CUI con formato inválido');
							error++;
							msjError =msjError+ '*Número de DPI inválido <br>';
							//return false;
						}

						cui = cui.replace(/\s/, '');
						var depto = parseInt(cui.substring(9, 11), 10);
						var muni = parseInt(cui.substring(11, 13));
						var numero = cui.substring(0, 8);
						var verificador = parseInt(cui.substring(8, 9));
						// //console.log('depto: '+depto);
						// //console.log('muni: '+muni);
						// //console.log('numero: '+numero);
						// //console.log('verificador: '+verificador);
						// Se asume que la codificación de Municipios y
						// departamentos es la misma que esta publicada en
						// http://goo.gl/EsxN1a

						// Listado de municipios actualizado segun:
						// http://goo.gl/QLNglm

						// Este listado contiene la cantidad de municipios
						// existentes en cada departamento para poder
						// determinar el código máximo aceptado por cada
						// uno de los departamentos.
						var munisPorDepto = [
							/* 01 - Guatemala tiene:      */ 17 /* municipios. */,
							/* 02 - El Progreso tiene:    */  8 /* municipios. */,
							/* 03 - Sacatepéquez tiene:   */ 16 /* municipios. */,
							/* 04 - Chimaltenango tiene:  */ 16 /* municipios. */,
							/* 05 - Escuintla tiene:      */ 13 /* municipios. */,
							/* 06 - Santa Rosa tiene:     */ 14 /* municipios. */,
							/* 07 - Sololá tiene:         */ 19 /* municipios. */,
							/* 08 - Totonicapán tiene:    */  8 /* municipios. */,
							/* 09 - Quetzaltenango tiene: */ 24 /* municipios. */,
							/* 10 - Suchitepéquez tiene:  */ 21 /* municipios. */,
							/* 11 - Retalhuleu tiene:     */  9 /* municipios. */,
							/* 12 - San Marcos tiene:     */ 30 /* municipios. */,
							/* 13 - Huehuetenango tiene:  */ 32 /* municipios. */,
							/* 14 - Quiché tiene:         */ 21 /* municipios. */,
							/* 15 - Baja Verapaz tiene:   */  8 /* municipios. */,
							/* 16 - Alta Verapaz tiene:   */ 17 /* municipios. */,
							/* 17 - Petén tiene:          */ 14 /* municipios. */,
							/* 18 - Izabal tiene:         */  5 /* municipios. */,
							/* 19 - Zacapa tiene:         */ 11 /* municipios. */,
							/* 20 - Chiquimula tiene:     */ 11 /* municipios. */,
							/* 21 - Jalapa tiene:         */  7 /* municipios. */,
							/* 22 - Jutiapa tiene:        */ 17 /* municipios. */
						];

						if (depto === 0 || muni === 0)
						{
							//console.log("CUI con código de municipio o departamento inválido.");
							//callback("CUI con código de municipio o departamento inválido.");
							error++;
							msjError =msjError+ '*Número de DPI con código de municipio o departamento inválido. <br>';
							//return false;
						}

						if (depto > munisPorDepto.length)
						{
							//console.log("CUI con código de departamento inválido.");
							//callback("CUI con código de departamento inválido.");
							error++;
							msjError =msjError+ '*Número de DPI con código de departamento inválido. <br>';
							//return false;
						}

						if (muni > munisPorDepto[depto -1])
						{
							//console.log("CUI con código de municipio inválido.");
							//callback("CUI con código de municipio inválido.");
							error++;
							msjError =msjError+ '*Número de DPI con código de municipio inválido. <br>';
							//return false;
						}
						
						// Se verifica el correlativo con base
						// en el algoritmo del complemento 11.
						var total = 0;

						for (var i = 0; i < numero.length; i++)
						{
							total += numero[i] * (i + 2);
						}
						var modulo = (total % 11);
						////console.log("CUI con módulo: " + modulo);
						if(modulo === verificador){
							////console.log(modulo +'==='+ verificador);
						}else{
							error++;
							msjError =msjError+ '*Numeración no valida de DPI <br>';
						}
						if (/\s/.test(cui) || cui.includes("-")) {
							//callback('No se aceptan espacios ni guiones.');
							error++;
							msjError =msjError+ '*Número de DPI No se aceptan espacios ni guiones. <br>';
						}						
					}
					if($("#nitCl").val()==''){
						error++;
						msjError =msjError+ '*NIT <br>'
					}else{
						var nit = $("#nitCl").val();
						//console.log("NIT validación");
						var nitRegExp = new RegExp('^[0-9]+(-?[0-9kK])?$');
						if (!nitRegExp.test(nit)) {
							////console.log("NIT inválido");
							//callback('CUI con formato inválido');
							error++;
							msjError =msjError+ '*NIT inválido <br>';
							//return false;
						}
						nit = nit.replace(/-/, '');
						var lastChar = nit.length - 1;
						var number = nit.substring(0, lastChar);
						var expectedCheker = nit.substring(lastChar, lastChar + 1).toLowerCase();
						var factor = number.length + 1;
						var total = 0;

						for (var i = 0; i < number.length; i++) {
							var character = number.substring(i, i + 1);
							var digit = parseInt(character, 10);

							total += (digit * factor);
							factor = factor - 1;
						}
						var modulus = (11 - (total % 11)) % 11;
						var computedChecker = (modulus == 10 ? "k" : modulus.toString());

						if(expectedCheker === computedChecker){
							////console.log(expectedCheker+'==='+computedChecker) ;
						}else{
							error++;
							msjError =msjError+ '*NIT inválido <br>';
						}
					}
	
					if($("#fechaVencimientoDpi").val()==''){
						error++;
						msjError =msjError+ '*Fecha de Vencimiento DPI <br>'
					}else{
						
						var fecha_mayor = $("#fechaVencimientoDpi").val();
						var partes_fecha_mayor = fecha_mayor.split('/');
						var fecha_mayor_numero= new Date (partes_fecha_mayor[2]+'/'+partes_fecha_mayor[1]+'/'+partes_fecha_mayor[0]).setHours(0,0,0,0);
						//console.log('fecha mayor' +fecha_mayor_numero);

						var fecha_menor = $("#fechaHoy").val();
						var partes_fecha_menor = fecha_menor.split('/');
						var fecha_menor_numero= new Date (partes_fecha_menor[2]+'/'+partes_fecha_menor[1]+'/'+partes_fecha_menor[0]).setHours(0,0,0,0);
						//console.log('fecha menor '+fecha_menor_numero);
						if(fecha_mayor_numero.valueOf()<=fecha_menor_numero.valueOf()){
							error++;
							msjError =msjError+ '*Fecha de Vencimiento DPI No puede ser menor a fecha actual <br>'
						}
					}
					
					if(error==0){
						var formDataCliente = new FormData(document.getElementById("frmAgregarCliente"));
						
						$.ajax({
							url: "./cliente.php?agregar_editar_cliente=true",
							type: "post",
							dataType: "json",
							data: formDataCliente,
							cache: false,
							contentType: false,
							processData: false,
							beforeSend:function (){
								$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
							},
							success:function (response){
								$("#modal_confirm").modal({
									backdrop: 'static',
									keyboard: false,
									show: true
								});
								if (response.err == true) {
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
								}
								else {
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarCliente();buscarClienteUnico('+response.id+',\'\',\'\');">Aceptar</div>');
								}				
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarCliente').animate({scrollTop:0}, 'fast');
						$("#divAlertCliente").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertCliente").html('');
							},5000)
					}
					
				}
				function agregarEnganche(){
					$("#idOcaEnganche").val($("#idOcaInfo").val())
					torres(0,'torreEng')
					niveles(0,'nivelEng');
					apartamentos(0,0,'apartamentoEng');
					datosApartamento(0,0);
					getEngancheDetalle(0);
					getVendedor('nombreVendedorEng',0);
					$("#ProyectoEng").val('');
					$("#engancheEng").val('');
					$("#idEnganche").val('');
					$("#engancheMontoEng").val('');
					$("#descuentoPorcentualEng").val('');
					$("#descuentoPorcentualMontoEng").val('');
					$("#parqueosExtraEng").val('');
					$("#bodegaExtraEng").val('');
					$("#montoReservaEng").val('');
					$("#fechaPagoReservaEng").val('');
					$("#plazoFinanciamientoEng").val('');
					$("#fechaPagoInicialEng").val('');
					$("#pagosEngancheEng").val('');
					$("#pagoPromesaEng").val('');
					$("#descuentoEng").val('');
					$("#formaPagoEng").val('');
					$("#noDepositoReservaEng").val('');
					$("#bancoChequeReservaEng").val('');
					$("#bancoDepositoReservaEng").val('');
					$("#noChequeReservaEng").val('');
					$("#observacionesEng").val('');	
					$("#modalAgregarEnganche").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					//console.log("funcion buscar cliente");

					// $("#proyectoCliente").val("");
					// $("#idCliente").val(0);
					// $("#idOcaCliente").val(0);
					
				}
				function agregarCotizacion(){
					$("#idOcaCotizacion").val($("#idOcaInfo").val())
					torres(0,'torreCot')
					niveles(0,'nivelCot');
					apartamentos(0,0,'apartamentoCot');
					datosApartamento(0,1);
					getVendedor('nombreVendedorCot',0)
					//getCotizacionDetalle(0);
					$("#ProyectoCot").val('');
					$("#engancheCot").val('');
					$("#idCotizacion").val('');
					$("#engancheMontoCot").val('');
					$("#descuentoPorcentualCot").val('');
					$("#descuentoPorcentualMontoCot").val('');
					$("#parqueosExtraCot").val('');
					$("#bodegaExtraCot").val('');
					$("#montoReservaCot").val('');
					$("#plazoFinanciamientoCot").val('');
					$("#pagosEngancheCot").val('');
					$("#descuentoCot").val('');
					$("#modalAgregarCotizacion").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					//console.log("funcion buscar cliente");

					// $("#proyectoCliente").val("");
					// $("#idCliente").val(0);
					// $("#idOcaCliente").val(0);
					
				}
				function editarCliente(){
					//getDepartamentos(1,'depto',e.departamento);
					//getMunicipios(e.departamento,'municipio',e.municipio);
					var nombreCompleto=$("#nombreClienteInfo").val()
					console.log(nombreCompleto);
					var nombreSeparado = nombreCompleto.split(" ");
					$("#primerNombre").val(e.primerNombre);
					$("#segundoNombre").val(e.segundoNombre);
					$("#primerApellido").val(e.primerApellido);
					$("#segundoApellido").val(e.segundoApellido);
					$("#tercerNombre").val(e.tercerNombre);
					$("#apellidoCasada").val(e.apellidoCasada);
					$("#correo").val($("#emailClienteInfo").val());
					$("#telefono").val($("#telefonoClienteInfo").val());
					$("#direccion").val($("#direccionClienteInfo").val());
					$("#numeroDpi").val($("#dpiClienteInfo").val());
					$("#fechaVencimientoDpi").val($("#vencimientoDpiClienteInfo").val());
					$("#proyectoCliente").val($("#proyectoInfo").val());
					$("#idCliente").val($("#idInfo").val());
					$("#idOcaCliente").val($("#idOcaInfo").val());

					$("#modalAgregarCliente").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					//console.log("funcion editar cliente");
					
				}
				function logout() {
					var formData = new FormData;
				jQuery.ajax({
					url: "./usuario.php?logout=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					success: function (response) {
						location.href = "index.php";
					}
				});
			}
			function setDescuento(tipo,value){
				var precio =  $("#precioTotalEng").val()!='' ? $("#precioTotalEng").val():'0';
				var precio =parseFloat(precio.replace(/[Q,]/g,''));
				var bodegaExtra = $("#bodegaExtraEng").val()!=''?parseInt($("#bodegaExtraEng").val()):0
				var parqueoExtra = $("#parqueosExtraEng").val()!=''?parseInt($("#parqueosExtraEng").val()):0
				var totalParqueo = parqueoExtra * parseFloat($("#parqueoExtraEng").val())
				var totalBodega = bodegaExtra * parseFloat($("#bodegaPrecioEng").val())
				if($("#CocinaEng").val()!='Sin cocina'){
					var cocina = parseFloat($("#"+$("#CocinaEng").val()+"Eng").val())
				}else{
					var cocina = 0;
				}
				precioTotal = precio + totalBodega + totalParqueo + cocina;
				if(tipo=='porcentaje'){
					var montoPorcentaje = precioTotal * (value/100);
					$("#descuentoPorcentualMontoEng").val(montoPorcentaje);
				}else if(tipo=='monto'){
					if(precio>0){
						porcentaje = 100 * ($("#descuentoPorcentualMontoEng").val()/precioTotal);
						$("#descuentoPorcentualEng").val(porcentaje);
					}else{
						$("#descuentoPorcentualEng").val(0);
					}
					
				}
				setEnganche('porcentaje',$("#engancheEng").val());
			}
			function setDescuentoCot(tipo,value){
				var precio =  $("#precioTotalCot").val()!='' ? $("#precioTotalCot").val():'0';
				var precio =parseFloat(precio.replace(/[Q,]/g,''));
				var bodegaExtra = $("#bodegaExtraCot").val()!=''?parseInt($("#bodegaExtraCot").val()):0
				var parqueoExtra = $("#parqueosExtraCot").val()!=''?parseInt($("#parqueosExtraCot").val()):0
				var totalParqueo = parqueoExtra * parseFloat($("#parqueoExtraCot").val())
				var totalBodega = bodegaExtra * parseFloat($("#bodegaPrecioCot").val())
				precioTotal = precio + totalBodega + totalParqueo;
				console.log(precio +'+'+ totalBodega +'+'+ totalParqueo)
				console.log(precioTotal);
				////console.log(precio+ ' '+$("#precioTotalEng").val());
				if(tipo=='porcentaje'){
					var montoPorcentaje = precioTotal * (value/100);
					$("#descuentoPorcentualMontoCot").val(montoPorcentaje);
				}else if(tipo=='monto'){
					if(precioTotal>0){
						porcentaje = 100 * ($("#descuentoPorcentualMontoCot").val()/precioTotal);
						$("#descuentoPorcentualCot").val(porcentaje);
					}else{
						$("#descuentoPorcentualCot").val(0);
					}
					
				}
				setEngancheCot('porcentaje',$("#engancheCot").val());
			}
			function setEnganche(tipo,value){
				var precio =  $("#precioTotalEng").val()!=0 ? $("#precioTotalEng").val():'0';
				var precio = parseFloat(precio.replace(/[Q,]/g,''));
				var descuento = $("#descuentoPorcentualMontoEng").val()!='' ? $("#descuentoPorcentualMontoEng").val():'0';
				//console.log("desucento: "+descuento);
				descuento = descuento.replace(/[Q,]/g,'');
				var bodegaExtra = $("#bodegaExtraEng").val()!=''?parseInt($("#bodegaExtraEng").val()):0
				var parqueoExtra = $("#parqueosExtraEng").val()!=''?parseInt($("#parqueosExtraEng").val()):0
				var totalParqueo = parqueoExtra * parseFloat($("#parqueoExtraEng").val())
				var totalBodega = bodegaExtra * parseFloat($("#bodegaPrecioEng").val())
				if($("#CocinaEng").val()!='Sin cocina'){
					var cocina = parseFloat($("#"+$("#CocinaEng").val()+"Eng").val())
				}else{
					var cocina = 0;
				}
				var nuevoPrecio = precio + totalParqueo + totalBodega + cocina - descuento;
				console.log("nuevoPrecio: " +precio +" + "+ totalParqueo +" + "+ totalBodega +" + "+ cocina +" - "+ descuento);
				//console.log(precio);
				//console.log(precio+ ' '+nuevoPrecio);
				if(tipo=='porcentaje'){
					var montoPorcentaje = nuevoPrecio * (value/100);
					console.log (nuevoPrecio +" *  ("+value+"/100)");
					$("#engancheMontoEng").val(montoPorcentaje);
				}else if(tipo=='monto'){
					if(nuevoPrecio>0){
						porcentaje = 100 * ($("#engancheMontoEng").val()/nuevoPrecio);
						$("#engancheEng").val(porcentaje);
					}else{
						$("#engancheEng").val(0);
					}
					
				}
			}
			function setEngancheCot(tipo,value){
				var precio =  $("#precioTotalCot").val()!=0 ? $("#precioTotalCot").val():'0';
				var precio = parseFloat(precio.replace(/[Q,]/g,''));
				var descuento = $("#descuentoPorcentualMontoCot").val()!='' ? $("#descuentoPorcentualMontoCot").val():'0';
				//console.log("desucento: "+descuento);
				descuento = parseFloat(descuento.replace(/[Q,]/g,''));
				var bodegaExtra = $("#bodegaExtraCot").val()!=''?parseInt($("#bodegaExtraCot").val()):0
				var parqueoExtra = $("#parqueosExtraCot").val()!=''?parseInt($("#parqueosExtraCot").val()):0
				var totalParqueo = parqueoExtra * parseFloat($("#parqueoExtraCot").val())
				var totalBodega = bodegaExtra * parseFloat($("#bodegaPrecioCot").val())
				var nuevoPrecio = precio + totalParqueo+ totalBodega - descuento;
				//console.log(precio);
				//console.log(precio+ ' '+nuevoPrecio);
				if(tipo=='porcentaje'){
					var montoPorcentaje = nuevoPrecio * (value/100);
					$("#engancheMontoCot").val(montoPorcentaje);
				}else if(tipo=='monto'){
					if(nuevoPrecio>0){
						porcentaje = 100 * ($("#engancheMontoCot").val()/nuevoPrecio);
						$("#engancheCot").val(porcentaje);
					}else{
						$("#engancheCot").val(0);
					}
					
				}
			}
			function pagoEspecial(total){
				
				$("#resultadoCuotas input[type='checkbox']").each(function () {
					var split = this.id.split('_');
					if ($(this).is(':checked')) {
						document.getElementById("cuota_"+split[1]).readOnly = false;
					}else{
						document.getElementById("cuota_"+split[1]).readOnly = true;
					}
				});
				recalculoPagoEspecial(total);
			}
			function recalculoPagoEspecial(total){
				//console.log("entrando pago especial calculo")
				var montoPagosEspeciales = 0;
				var checked=0;
				$("#resultadoCuotas input[type='checkbox']").each(function () {
					if ($(this).is(':checked')) {
						var split = this.id.split('_');
						montoPagosEspeciales += $("#cuota_"+split[1]).val()
					}else{
					}
				});
				//console.log(parseInt(total) +"-"+ parseInt(montoPagosEspeciales));
				var nuevoTotal = parseInt(total) - parseInt(montoPagosEspeciales);

				//console.log(nuevoTotal);
				$("#resultadoCuotas input[type='checkbox']").each(function () {
					
					var split = this.id.split('_');
					if ($(this).is(':checked')) {
					}else{
						checked++;
					}
				});
				var cuota = nuevoTotal/parseInt(checked);
				//console.log(cuota +"="+ nuevoTotal+" / "+parseInt(checked))
				$("#resultadoCuotas input[type='checkbox']").each(function () {
					var checked=0;
					var split = this.id.split('_');
					if ($(this).is(':checked')) {
						//console.log("checked");
					}else{
						cantidad=$("#cuota_"+split[1]).val(cuota.toFixed(2));
					}
						
					
				});
			} 

			// function verAdjuntos(){
				
			// 	var idCliente = $("#idOcaInfo").val();
			// 	var nombreCLiente = $("#nombreClienteInfo").val();
			// 	var id_tipo_documento = $("#filtro-adjuntos option:selected").val();
			// 	var formData = new FormData;

			// 	formData.append("idOcaCliente", idCliente);
			// 	formData.append("id_tipo_documento", id_tipo_documento);

			// 	$("#modalVerAdjuntos").modal({
			// 				backdrop: 'static',
			// 				keyboard: false,
			// 				show: true
			// 			});
			// 	$.ajax({
			// 		url: "./cliente.php?get_adjuntos_listado=true",
			// 		type: "post",
			// 		dataType: "json",
			// 		data: formData,
			// 		cache: false,
			// 		contentType: false,
			// 		processData: false,
			// 		beforeSend:function (){
			// 			$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
			// 		},
			// 		success:function (response){
			// 			var output;
			// 			$.each(response.listado_adjuntos,function(i,e) {
			// 				//console.log(e.user_name);
			// 				if(e.creado=='si'){
			// 					var check='<i class="fa fa-check-square-o"></i>';
			// 				}else
			// 				{
			// 					var check="";
			// 				}
			// 				output += `	<tr>
			// 								<td style="width:90%;">
			// 									<label class="nodpitext">${e.nombre}</label>
			// 								</td>
			// 								<td style="width:10%;">
			// 									<button 
			// 										onclick="eliminarAdjuntos(${e.id_archivo})" 
			// 										class="btn btn-sm btn-danger"  
			// 										type="button"
			// 									>
			// 										<i class="fa fa-times"></i>
			// 									</button> 
			// 								</td>
			// 							</tr>`;
			// 			});
			// 			////console.log(output);
			// 			var tb = document.getElementById('resultadoAdjuntos');
			// 			while(tb.rows.length >= 1) {
			// 				tb.deleteRow(0);
			// 			}
			// 			$('#resultadoAdjuntos').append(output);
			// 		},
			// 		error:function (){
			// 			$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
			// 		}
			// 	});	
				

			// 	$("#divVerAdjuntos").html(`	</iframe>
			// 									<iframe 
			// 										frameborder='0' 
			// 										type='application/pdf' 
			// 										style='width:100%; height:100%' align='right' 
			// 										src='./adjuntos.php/${$("#nombreClienteInfo").val()}?idCliente=${$("#idOcaInfo").val()}&nombreCliente=${$("#nombreClienteInfo").val()}&id_tipo_documento=${id_tipo_documento}#page=1&zoom=50'
			// 									>
			// 								</iframe>`);							
			// }

			// function getFiltroAdjuntos() {
				
			// 	var idCliente = $("#idOcaInfo").val();
			// 	const option = document.getElementById("filtro-adjuntos");

			// 	let formData = new FormData();
			// 	formData.append("idOcaCliente", idCliente);

			// 	$.ajax({
			// 		url: "./cliente.php?get_filtros_adjuntos=true",
			// 		type: "post",
			// 		dataType: "json",
			// 		data: formData,
			// 		cache: false,
			// 		contentType: false,
			// 		processData: false,
			// 		beforeSend:function (){
			// 			$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
			// 		},
			// 		success:function (response){
			// 			var output;
			// 			output += ' <option value="0">Mostrar todos</option>';
			// 			$.each(response.filtros_adjuntos,function(i,e) {
			// 				// let select = "";
			// 				// if(idCliente==e.id){
			// 				// 	select = 'selected="selected"';
			// 				// }
			// 				output += `<option value="${e.id_tipo_documento}">${e.nombre}</option>`;
			// 			});						
			// 			for (let i = option.options.length; i >= 0; i--) {
			// 				option.remove(i);
			// 			}
			// 			$('#filtro-adjuntos').append(output);
			// 		},
			// 		error:function (){
			// 			$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
			// 		}
			// 	});
			// }

			// function eliminarAdjuntos(id_archivo)
            // {
            //     //console.log("funcion eliminar adjunto");
            //     var formData = new FormData;
            //     formData.append("id_archivo", id_archivo);
            //     $.ajax({
            //         url: "./cliente.php?deleteAdjunto=true",
            //         type: "post",
            //         dataType: "json",
            //         data: formData,
            //         cache: false,
            //         contentType: false,
            //         processData: false,
            //         beforeSend:function (){
            //             $("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
            //         },
            //         success:function (response){
			// 			//$("#modalVerAdjuntos").modal("hide");
			// 			$("#resultadoAdjuntos").html('');
			// 			$("#divVerAdjuntos").html('');
            //             verAdjuntos();
            //         },
            //         error:function (){
            //             $("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
            //         }
            //     });
            // }
			// function guardarAdjuntos(id,ruta)
            // {
            //     //console.log("funcion guardar adjunto");
			// 	var formData = new FormData(document.getElementById("frmListaAdjunto"));
			// 	var idCliente = $("#idOcaInfo").val();
			// 	var adjuntos = $("#fliesAdjuntos").val();
			// 	formData.append("idOcaCliente", idCliente);
            //     $.ajax({
            //         url: "./cliente.php?guardarAdjunto=true",
            //         type: "post",
            //         dataType: "json",
            //         data: formData,
            //         cache: false,
            //         contentType: false,
            //         processData: false,
            //         beforeSend:function (){
            //             $("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
            //         },
            //         success:function (response){
			// 			//$("#modalVerAdjuntos").modal("hide");
			// 			$("#resultadoAdjuntos").html('');
			// 			$("#divVerAdjuntos").html('');
            //             verAdjuntos();
            //         },
            //         error:function (){
            //             $("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
            //         }
            //     });
            // }
			function fechaVencimiento(){
				var fecha_emision = $("#fechaEmisionDpiCo").val();
				var partes_fecha_emision = fecha_emision.split('-');
				var fecha = new Date(partes_fecha_emision[0]+'-'+ partes_fecha_emision[1]+'-'+ partes_fecha_emision[2]); // crea el Date
				fecha.setFullYear(fecha.getFullYear()+10); // Hace el cálculo
				year = fecha.getFullYear();
				month = fecha.getMonth()+1;
				dt = fecha.getDate();
				if (dt < 10) {
					dt = '0' + dt;
				}
				if (month < 10) {
					month = '0' + month;
				}
				res = year+'-' + month + '-'+dt; // carga el resultado
				console.log(fecha_emision);
				console.log(fecha+' -- '+res);
				$("#fechaVencimientoDpiCo").val(res);
			}

		
		</script>
    </body>
</html>

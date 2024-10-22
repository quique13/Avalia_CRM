<?php
session_name("inmobiliaria");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>"; 
}
$id_usuario=$_SESSION['id_usuario'];
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
		<link rel="stylesheet" href="../dist/themes/default/style.min.css" />
</head>

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
		<script src="../js/documentos_adjuntosFha.js"></script>
		<script src="../dist/jstree.min.js"></script>
		<?php
			include "menu.php";
		?>

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
					<?php require_once("./documentos_adjuntos_fha.php"); ?>


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
				$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
					var target = $(e.target).attr("href") // activated tab
					//alert(target);
					if(target=='#checkList'){
						var idCliente = $("#idClienteFha").val();
						verAdjuntos(idCliente,0);
						getFiltroAdjuntos(idCliente,0);
					}else if(target=='#documentosGenerales'){
						var idCliente = $("#idClienteFha").val();
						verAdjuntos(idCliente,1);
						getFiltroAdjuntos(idCliente,1);
					}
				});
			<?php
				if (intval($_SESSION['password_default']) == 1) {
					echo    "$('#modal-cambio-contrasenia').modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});"; 
				}
			?>
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
								output += '<tr onCLick=""><td>'+e.codigo+'</td><td>'+e.client_name+' '+check+'</td><td>'+e.proyecto+'</td><td>'+e.apartamentoEnganche+'</td><td><button onclick="verAdjuntos('+e.id+',0), getFiltroAdjuntos('+e.idCliente+',0)" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ></td></tr>';
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
						url: "./cliente.php?get_niveles_proyecto=true",
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
					if(proyecto==1){
						console.log("proyecto");
						document.getElementById("CocinaEng").disabled = true;
					}else{
						document.getElementById("CocinaEng").disabled = false;
					}
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
				




				function Alertpendiente()
				{
					////console.log("dfdf");
					$("#divAlertPendiente").html('<div class="alert alert-danger">Función pendiente, se está trabajando</div>');
								setTimeout(function(){
									$("#divAlertPendiente").html('');
								},5000)
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

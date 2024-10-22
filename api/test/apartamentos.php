<?php
session_name("inmobiliaria");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>"; 
}
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
		<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="../css/styles.css" rel="stylesheet">
		<link href="../css/stylesEnganche.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Archivo&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/font-awesome-4.7.0/css/font-awesome.css">
        <script src="../libs/cryptoJS/v3.1.2/rollups/aes.js"></script>
		<script src="../libs/cryptoJS/v3.1.2/rollups/md5.js"></script>
</head>

    <body>
		<script src="../dist/jquery/dist/jquery.js"></script>
		<script src="../dist/jquery/dist/jquery.min.js"></script>
		<script src="../dist/jquery/dist/jquery.min.map"></script>
		<script src="../js/jquery.number.js "></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/apartment_info.png"> Apartamentos</label>
											<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="divAlertPendiente" name="divAlertPendiente">
                								
											</div>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
                                        <div class="row">
                                            <div class="col-md-12" id="">
                                                <div class="row"> 	
                                                    <div class="col-md-12 "> 
                                                        <div class="table-responsive">
															<div class="row">
																<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
																	<button onclick="agregarApartamento()" class="btn btn-xs" type="button"><img class="addclient" src="../img/more.gif" alt="agregar Apartamento" ></button>
																	<span class="addclienttext" >Agregar Apartamento</span>
																	<div class="btn-group">
																		<button onclick="window.open('../docsDownload/Formato carga apartamentos.xlsx')" class="descargar">
																			Descargar formato de carga
																			<i class="fa fa-download" aria-hidden="true"></i>
																		</button>
																	</div>
																</div>
																<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
																	<form action="./cargaBaseApartamentos.php" method="POST" style="margin-bottom:0px;" id="filesApartamentos" enctype="multipart/form-data">
																		<div class="row">
																			<div class="col-lg-4 col-md-4 col-xs-10" style="text-align:right;margin-bottom:10px;">
																				<label  class="draganddroptexttitle" for="adjuntos">Subir archivos aquí (.xlsx): </label>
																			</div>
																			<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
																				<input class="draganddropExcel" type="file" required name="archivo" id="archivo" class="form-control" placeholder="Arrastra y suelta aquí" accept=".xlsx">
																			</div>
																			<div class="col-lg-2 col-md-2 col-xs-10" style="text-align:right;margin-bottom:10px;">
																				<button onClick="uploadApartamentos()" type="button" class="guardar">
																					Subir
																				</button>
																			</div>	
																		</div>
																	</form>
																</div>
															</div>
															<div class="row">
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<label class="nodpitext">Proyecto:</label>
																	<select class="form-control" name="proyectoBsc" id="proyectoBsc"  onchange="torres(this.value,'torreBsc')">
																		<option value="0" >Seleccione</optinon>
																		<? echo $proyectos ?>
																	</select>
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<label class="nodpitext">Torre/Fase:</label>
																	<select class="form-control" name="torreBsc" id="torreBsc" onchange="">
																		<option value="0" >Seleccione</optinon>
																	</select>
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<label class="nodpitext">Estado:</label>
																	<select class="form-control" name="estadoBsc" id="estadoBsc" >
																		<option value="0" >Seleccione</optinon>
																		<option value="1" >Disponible</optinon>
																		<option value="2" >Reservado</optinon>
																		<option value="3" >Vendido</optinon>
																	</select>
																</div>
																<div class="col-lg-1 col-md-1 col-xs-10" style="margin-bottom:10px;">
																	<label  class="nodpitext"  style="color: white">_____</label>
																	<button onclick="apartamentosList()" class="searchf" type="button">Buscar</button>															
																</div>
															</div> 
                                                            <div id="divAlertCargados" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															</div>
                                                            <table width="100%" id="tableUsuarios" class="table table-sm table-hover"  style="width:100%">
                                                                <thead>
                                                                    <tr >
                                                                        <th>NO.</th>
																		<th>PROYECTO</th>
																		<th>TORRE</th>
																		<th>NIVEL</th>
																		<th>APARTAMENTO</th>
																		<th>CODIGO</th>
																		<th>ESTADO</th>
                                                                        <th>HABITACIONES</th>																	
																		<th>PRECIO</th>
																		<th>TAMAÑO</th>
																		<th>JARDIN</th>
																		<th>BODEGA</th>
																		<th>PARQ. CARRO</th>
																		<th>PARQ. MOTO</th>
																		<th>PRECIO BODEGA</th>
                                                                        <th>OPCIONES</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
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
					<!-- Modal -->
					<div class="modal fade" id="modalAgregarApartamento">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Agregar Apartamento</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarApartamento" style="padding:5px 15px;" >
									<div class="secinfo" >
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarApartamento" name="frmAgregarApartamento" method="POST">
											<div class="row" >
												<input type="hidden" id="idApartamento" name="idApartamento">	
												<div id="divAlertApartamento" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												</div>													
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Proyecto:</label>
															<select id="proyecto" name="proyecto" class="form-control"  onChange="getTorres(this.value,'torre',0)">
																<option selected>Seleccionar Proyecto</option>
															</select>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Torre:</label>
															<select id="torre" name="torre" class="form-control" onChange="getNiveles(this.value,'nivel',0)">
																<option value="" >Seleccionar Torre</option>
															</select>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Nivel:</label>
															<select id="nivel" name="nivel" class="form-control" >
																<option value="" >Seleccionar Nivel</option>
															</select>
														</div>
                                                        <div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Apartamento:</label>
															<input  type="text" id="apartamento" name="apartamento" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Código:</label>
															<input  type="text" id="codigo" name="codigo" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Habitaciones:</label>
															<input  type="number" id="cuartos" name="cuartos" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">precio:</label>
															<input  type="number" id="precio" name="precio" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Iusi + Seguro:</label>
															<input  type="number" id="iusi_seguro" name="iusi_seguro" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Tamaño mts:</label>
															<input  type="number" id="mts" name="mts" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Jardín mts:</label>
															<input  type="number" id="mts_jardin" name="mts_jardin" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Bodega:</label>
															<input  type="number" id="mts_bodega" name="mts_bodega" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Parqueo Carro:</label>
															<input  type="number" id="parqueo" name="parqueo" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Parqueo Moto:</label>
															<input  type="number" id="parqueo_moto" name="parqueo_moto" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Bodega Precio:</label>
															<input  type="number" id="precio_bodega" name="precio_bodega" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Estado:</label>
															<select id="estado" name="estado" class="form-control" >
																<option value=1 selected>Disponible</option>
																<option value=2>Reservado</option>
																<option value=3>Vendido</option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
													<button onclick="guardarApartamento()" class="guardar" type="button">Guardar</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
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
					<!-- /.modal -->		
                </div>
			</div>
		</div>
			<script type="text/javascript">
			function torres(proyecto,input,valueInput){
					////console.log(proyecto+" - "+input+" - "+valueInput);
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
			function uploadApartamentos(){
				var formData = new FormData($('#filesApartamentos')[0]);
				$.ajax({
						url: "./cargaBaseApartamentos.php",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#divAlertCargados").html('<div class="alert alert-warning">Cargando...</div>');
						},
						success:function (response){
							//alert("Se subio el archivo");
							$("#divAlertCargados").html('<div class="alert alert-success">Se cargaron un total de '+response.mss+' apartamentos</div>');
							setTimeout(function(){
								$("#divAlertCargados").html('');
								apartamentosList();
							},3000)
							
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
			}

			getProyectos('proyecto',0);
            apartamentosList();
			function getProyectos(input,valueInput){
					console.log("funcion buscar proyectos");
					var formData = new FormData;
					$.ajax({
						url: "./torre.php?get_proyecto=true",
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
							$.each(response.proyectos,function(i,e) {
								if(valueInput==e.idGlobal){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.idGlobal+'">'+e.proyecto+'</option>';
							});
							console.log(output);
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
				function getTorres(proyecto,input,valueInput){
					console.log(proyecto+' '+input+' '+valueInput+'funcion buscar proyectos');
					var formData = new FormData;
					formData.append("proyecto", proyecto);
					$.ajax({
						url: "./nivel.php?get_torres=true",
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
								output += ' <option '+select+' value="'+e.idTorre+'">'+e.noTorre+'</option>';
							});
							console.log(output);
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
				function getNiveles(torre,input,valueInput){
					console.log(torre+' '+input+' '+valueInput+'funcion buscar proyectos');
					var formData = new FormData;
					formData.append("torre", torre);
					$.ajax({
						url: "./apartamento.php?get_niveles=true",
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
							$.each(response.niveles,function(i,e) {
								if(valueInput==e.idNivel){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.idNivel+'">'+e.noNivel+'</option>';
							});
							console.log(output);
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
                function agregarApartamento(){
					getProyectos('proyecto',0);
					document.getElementById("frmAgregarApartamento").reset();
					$("#modalAgregarApartamento").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					console.log("funcion buscar usuario");
					$("#idApartamento").val(0);		
				}
                function guardarApartamento(){
					console.log("funcion guardar torre");
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					if($("#proyecto").val()==''){
						error++;
						msjError =msjError+ '*proyecto <br>'
					}
					if($("#torre").val()==''){
						error++;
						msjError =msjError+ '*Torre <br>'
					}
					if($("#nivel").val()==''){
						error++;
						msjError =msjError+ '*Nivel <br>'
					}
					if($("#apartamento").val()==''){
						error++;
						msjError =msjError+ '*Apartamento <br>'
					}
					if($("#codigo").val()==''){
						error++;
						msjError =msjError+ '*Codigo <br>'
					}
					if($("#cuartos").val()==''){
						error++;
						msjError =msjError+ '*Habitaciones <br>'
					}
					if($("#precio").val()==''){
						error++;
						msjError =msjError+ '*Precio <br>'
					}
					if($("#precio_bodega").val()==''){
						error++;
						msjError =msjError+ '*Precio Bodega <br>'
					}
					if($("#iusi_seguro").val()==''){
						error++;
						msjError =msjError+ '*Iusi + Seguro <br>'
					}
					if($("#mts").val()==''){
						error++;
						msjError =msjError+ '*Tamaño <br>'
					}
					if($("#mts_jardin").val()==''){
						error++;
						msjError =msjError+ '*Jardin <br>'
					}
					if($("#mts_bodega").val()==''){
						error++;
						msjError =msjError+ '*Bodega <br>'
					}
					if($("#parqueo").val()==''){
						error++;
						msjError =msjError+ '*Parqueo Carro <br>'
					}
					if($("#parqueo_moto").val()==''){
						error++;
						msjError =msjError+ '*Parqueo Moto <br>'
					}
					if(error==0){
						var formData = new FormData(document.getElementById("frmAgregarApartamento"));
                        formData.append("pass", $("#idNivel").val());
						$.ajax({
							url: "./apartamento.php?agregar_editar_apartamento=true",
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
								$("#divAlertApartamento").html('<div class="alert alert-success">'+response.mss+'</div>');
								setTimeout(function(){
									$("#modalAgregarApartamento").modal("hide");
									$("#divAlertApartamento").html('');
								},1000)
								apartamentosList();						
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarApartamento').animate({scrollTop:0}, 'fast');
						$("#divAlertApartamento").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertApartamento").html('');
							},5000)
					}
					
				}
                function apartamentosList(){
					console.log("funcion lista_usuarios");
                    var formData = new FormData(document.getElementById("frmAgregarApartamento"));
					formData.append("proyectoBscTxt", document.getElementById("proyectoBsc").options[document.getElementById("proyectoBsc").selectedIndex].text);
					formData.append("torreBscTxt", document.getElementById("torreBsc").options[document.getElementById("torreBsc").selectedIndex].text);
					formData.append("estado", $("#estadoBsc").val());
					$.ajax({
						url: "./apartamento.php?get_lista_apartamentos=true",
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
                            var count =0;
							$.each(response.listado_apartamentos,function(i,e) {
                                count++;
								if(e.creado=='si'){
									var check='<i class="fa fa-check-square-o"></i>';
								}else
								{
									var check="";
								}
								output += '<tr onCLick=""><td>'+count+'</td><td>'+e.proyecto+'</td><td>'+ e.noTorre +'</td> <td>'+ e.noNivel +'</td><td>'+ e.apartamento +'</td><td>'+ e.codigo +'</td><td>'+e.estadoApto+'</td><td>'+ e.cuartos +'</td><td>'+new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format( e.precio) +'</td><td>'+ e.sqmts +'</td><td>'+ e.jardin_mts +'</td><td>'+ e.bodega_mts +'</td><td>'+ e.parqueo +'</td><td>'+ e.parqueo_moto +'</td><td>'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format( e.bodega_precio) +'</td><td><button onclick="editarApartamento('+e.idApartamento+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="Editar Usuario" ></button></td></tr>';
							});
							//console.log(output);
							var tb = document.getElementById('tableUsuarios');
							while(tb.rows.length > 1) {
								tb.deleteRow(1);
							}
							$('#tableUsuarios').append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function editarApartamento(id){
					$("#modalAgregarApartamento").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					$("#idApartamento").val(0);	
					var formData = new FormData;
					formData.append("idApartamento", id);
					$.ajax({
						url: "./apartamento.php?get_apartamento=true",
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
							$.each(response.apartamento,function(i,e) {
								console.log(e.idApartamento);
								//Info Apartamento, Vendedor y CLiente
								$("#proyecto").val(e.idProyecto);
								getTorres(e.idProyecto,'torre',e.idTorre);
								getNiveles(e.idTorre,'nivel',e.idNivel);
								$("#estado").val(e.estado);
								$("#idApartamento").val(e.idApartamento);
								$("#apartamento").val(e.apartamento);
								$("#codigo").val(e.codigo);
								$("#cuartos").val(e.cuartos);
								$("#precio").val(e.precio);
								$("#iusi_seguro").val(e.iusi_seguro);
								$("#mts").val(e.sqmts);
								$("#mts_jardin").val(e.jardin_mts);
								$("#mts_bodega").val(e.bodega_mts);
								$("#parqueo").val(e.parqueo);
								$("#parqueo_moto").val(e.parqueo_moto);
								$("#precio_bodega").val(e.bodega_precio);
								

							});							
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
			</script>
    </body>
</html>

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
$disabledValidar = $id_usuario == 11 ? '' : 'disabled="disabled"';
$arrayProyectos = explode(",",$_SESSION['proyectos']);
$proyectos = '';
$countP=0;
$vendedor = 0;
if ($id_perfil != 3) {
    $readOnly = "readonly";
    $super = 1;
    $vendedor = 1;
}
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

    <body>
		<script src="../dist/jquery/dist/jquery.js"></script>
		<script src="../dist/jquery/dist/jquery.min.js"></script>
		<script src="../dist/jquery/dist/jquery.min.map"></script>
		<script src="../js/jquery.number.js "></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

		<!-- DOCUMETNOS ADJUNTOS FUNCIONES -->
		<script src="../js/documentos_adjuntos.js"></script>
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
                							<label class="apartamentosearchitittle"><img class="usersearchicon" src=""> Inspecciones FHA</label>				
											<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="divAlertPendiente" name="divAlertPendiente">
                								
											</div>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
										<div class="row">
											<div class="col-md-12" id="busquedaApartamentos">
												<form autocomplete="off"  enctype="multipart/form-data"  id="frmBuscarApartamento" name="frmBuscarApartamento" method="POST">	
													<div class="row">	
														<div class="col-1 col-md-1" style="margin-bottom:10px;">
														</div>
														<div class="col-11 col-md-11" style="margin-bottom:10px;">
															<div class="row">
															<input id="id_perfil" name="id_perfil" type="hidden" value="<?php echo $id_perfil ?>" >
															<input id="id_usuario" name="id_usuario" type="hidden" value="<?php echo $id_usuario ?>" >
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
																<div class="col-lg-1 col-md-1 col-xs-10" style="margin-bottom:10px;">
																	<label  class="nodpitext"  style="color: white">_____</label>
																	<button onclick="buscarApartamentoEstadoCuenta()" class="searchf" type="button">Buscar</button>															
																</div>
															</div>	
														</div>
														<div class="col-1 col-md-1" style="text-align:center;margin-bottom:10px;">
														</div>	
													</div>
												</form>		
												<div id="contenedor" class="row" style="height:50vh; overflow-y: auto;overflow-x: hidden">	
													<div class="col-12 col-md-12" style="margin-bottom:10px;"  >
														<div class="row">
															<Label class="results">Resultados</label>
															<div class="table-responsive">
																<table id="resultadoApartamento" class="table table-sm table-hover"  style="width:100%">
																	<tr>
																		<th style="width:9%;text-align:center">Apartamento</th>
																		<th style="width:10%;text-align:center">Precio Venta</th>
																		<th style="width:9%;text-align:center" >Precio FHA</th>
																		<th style="width:9%;text-align:center" >Resguardo</th>
																		<th style="width:9%;text-align:center" >Inspección 1</th>
																		<th style="width:9%;text-align:center" >Inspección 2</th>
																		<th style="width:9%;text-align:center" >Inspección 3</th>
																		<th style="width:9%;text-align:center" >Total</th>
																		<th style="width:9%;text-align:center" >Ingreso Expediente</th>
																		<th style="width:9%;text-align:center" >Saldo</th>
																		<th style="width:9%;">Acciones</th> 
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
					<div class="modal fade" id="modalAgregarInspeccion"  >
						<div class="modal-dialog mw-100 w-75" style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content">
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Agregar Datos de inspeccion</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarInspeccion"  style="padding:5px 15px;">
									<div class="secinfo">
										<div class="row" >
											

											<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarInspeccion" name="frmAgregarInspeccion" method="POST">
													<input type="hidden" id="idInspeccion" name="idInspeccion">
													<div class="row" >
														<div id="divAlertPago" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Precio FHA:</label>
															<input type="text" id="precioFha" name="precioFha" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Resguardo:</label>
															<input type="text" id="resguardo" name="resguardo" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Inspección 1:</label>
															<input type="text" id="inspeccion_1" name="inspeccion_1" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Inspección 1 Monto:</label>
															<input type="text" id="inspeccion_1_monto" name="inspeccion_1_monto" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Inspección 2:</label>
															<input type="text" id="inspeccion_2" name="inspeccion_2" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Inspección 2 Monto:</label>
															<input type="text" id="inspeccion_2_monto" name="inspeccion_2_monto" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Inspección 3:</label>
															<input type="text" id="inspeccion_3" name="inspeccion_3" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Inspección 3 Monto:</label>
															<input type="text" id="inspeccion_3_monto" name="inspeccion_3_monto" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Ingreso Expediente:</label>
															<input type="text" id="ingreso_expediente" name="ingreso_expediente" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Ingreso Expediente Monto:</label>
															<input type="text" id="ingreso_expediente_monto" name="ingreso_expediente_monto" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;" id="divBotones" name="divBotones">
															<br><br><button onclick="guardarInspeccion()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
														</div>
														
														<script type="text/javascript">
															$("#precioFha").number( true, 2 );
															$("#resguardo").number( true, 2 );
															$("#inspeccion_1_monto").number( true, 2 );
															$("#inspeccion_2_monto").number( true, 2 );
															$("#inspeccion_3_monto").number( true, 2 );
															$("#ingreso_expediente_monto").number( true, 2 );
														</script>
													</div>
												</form>
											</div>
											<div id="divAlertCliente" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>		
				</div>
			</div>
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
			<script type="text/javascript">
				function buscarApartamentoEstadoCuenta(){
					//console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarApartamento"));
					formData.append("proyectoBscTxt", document.getElementById("proyectoBsc").options[document.getElementById("proyectoBsc").selectedIndex].text);
					formData.append("torreBscTxt", document.getElementById("torreBsc").options[document.getElementById("torreBsc").selectedIndex].text);
					formData.append("nivelBscTxt", document.getElementById("nivelBsc").options[document.getElementById("nivelBsc").selectedIndex].text);
					formData.append("apartamentoBscTxt", document.getElementById("apartamentoBsc").options[document.getElementById("apartamentoBsc").selectedIndex].text);
					$.ajax({
						url: "./cliente.php?get_apartamento_lista_inspecciones=true",
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

								var precio_venta = (parseFloat(e.montoParqueo) + parseFloat(e.montoParqueoMoto) + parseFloat(e.montoBodega) + parseFloat(e.precio)) - parseFloat(e.descuento_porcentual_monto)
								var resguardo_millar_1 = e.inspeccion1_monto === null || e.inspeccion1_monto === "" || parseFloat(e.inspeccion1_monto) < 1 ? (e.resguardo * 0.5)/1000 : parseFloat(e.inspeccion1_monto);
								var resguardo_millar_2 = e.inspeccion2_monto === null || e.inspeccion2_monto === "" || parseFloat(e.inspeccion2_monto) < 1 ? (e.resguardo * 0.5)/1000 : parseFloat(e.inspeccion2_monto);
								var resguardo_millar_3 = e.inspeccion3_monto === null || e.inspeccion3_monto === "" || parseFloat(e.inspeccion3_monto) < 1 ? (e.resguardo * 0.5)/1000 : parseFloat(e.inspeccion3_monto);
								var ingreso_expediente = e.ingresoExpediente_monto === null || e.ingresoExpediente_monto === "" || parseFloat(e.ingresoExpediente_monto) < 1 ? ((precio_venta * 5) / 1000) : parseFloat(e.ingresoExpediente_monto);

								var inspeccion_1 = e.inspeccion1 === null || e.inspeccion1 === "" ? 'Sin inspección' : 'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(resguardo_millar_1.toFixed(2)) ;
								var inspeccion_2 = e.inspeccion2 === null || e.inspeccion2 === "" ? 'Sin inspección' : 'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(resguardo_millar_2.toFixed(2)) ;
								var inspeccion_3 = e.inspeccion3 === null || e.inspeccion3 === "" ? 'Sin inspección' : 'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(resguardo_millar_3.toFixed(2)) ;
								
								var colorCodigo1 = inspeccion_1 === 'Sin inspección' ? '#FA0404' : '#000000';
								var colorCodigo2 = inspeccion_2 === 'Sin inspección' ? '#FA0404' : '#000000';
								var colorCodigo3 = inspeccion_3 === 'Sin inspección' ? '#FA0404' : '#000000';

								var total = 0;
								
								if(e.inspeccion1 !== null && e.inspeccion1 !== ""){
									total += e.inspeccion1_monto === null || e.inspeccion1_monto === "" || parseFloat(e.inspeccion1_monto) < 1  ? (e.resguardo * 0.5)/1000 : parseFloat(e.inspeccion1_monto);
								}
								if(e.inspeccion2 !== null && e.inspeccion2 !== ""){
									total += e.inspeccion2_monto === null || e.inspeccion2_monto === "" || parseFloat(e.inspeccion2_monto) < 1  ? (e.resguardo * 0.5)/1000 : parseFloat(e.inspeccion2_monto);
								}
								if(e.inspeccion3 !== null && e.inspeccion3 !== ""){
									total += e.inspeccion3_monto === null || e.inspeccion3_monto === "" || parseFloat(e.inspeccion3_monto) < 1  ? (e.resguardo * 0.5)/1000 : parseFloat(e.inspeccion3_monto);
								}
								
								
								

								var saldo = ingreso_expediente - total;

								output += '<tr onCLick=""><td align="right">'+e.apartamento+' </td><td align="right">Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(precio_venta)+' </td><td align="right">Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.precioFha)+' </td><td align="right">Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.resguardo)+' </td><td align="right" style="color:'+colorCodigo1+'">'+inspeccion_1+'</td><td align="right" style="color:'+colorCodigo2+'">'+inspeccion_2+' </td><td align="right" style="color:'+colorCodigo3+'"> '+inspeccion_3+'</td><td align="right">Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(total.toFixed(2))+'</td><td align="right"> Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(ingreso_expediente.toFixed(2))+'</td><td align="right"> Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(saldo.toFixed(2))+'</td><td align="center"><button onclick="editarInspeccion(\''+e.apartamento+'\')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ></td></tr>';
							});
							////console.log(output);
							var tb = document.getElementById('resultadoApartamento');
							while(tb.rows.length > 1) {
								tb.deleteRow(1);
							}
							$('#resultadoApartamento').append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function editarInspeccion(idInspeccion){
					var formData = new FormData;
					formData.append("idInspeccion", idInspeccion);
					$.ajax({
						url: "./cliente.php?get_inspeccion_info=true",
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
							$.each(response.detalleInspeccion,function(i,e) {
								//Campos Enganche
								var button = "";
								$("#idInspeccion").val(e.apartamento);	
								$("#precioFha").val(e.precioFha);
								$("#resguardo").val(e.resguardo);
								$("#inspeccion_1").val(e.inspeccion1);
								$("#inspeccion_2").val(e.inspeccion2);
								$("#inspeccion_3").val(e.inspeccion3);
								$("#inspeccion_1_monto").val(parseFloat(e.inspeccion1_monto));
								$("#inspeccion_2_monto").val(parseFloat(e.inspeccion2_monto));
								$("#inspeccion_3_monto").val(parseFloat(e.inspeccion3_monto));
								$("#ingreso_expediente").val(e.ingresoExpediente);
								$("#ingreso_expediente_monto").val(parseFloat(e.ingresoExpediente_monto));
								

							});
							$("#modalAgregarInspeccion").modal({
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
				function guardarInspeccion(){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarInspeccion"));
					$.ajax({
						url: "./cliente.php?agregar_editar_inspeccion=true",
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
							$('#bodyAgregarInspeccion').animate({scrollTop:0}, 'fast');
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err === true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarApartamentoEstadoCuenta()">Aceptar</div>');
							}				
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
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
				function apartamentos(proyecto,value,input,valueInput){
					//console.log("funcion buscar niveles");
					var formData = new FormData;
					formData.append("nivelEng", value);
					formData.append("apartamentoSelect", valueInput);
					$.ajax({
						url: "./cliente.php?get_apartamentos_proyecto=true",
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
				function estadoCuentaPdf(){
					idEnganche =  $("#idEngancheEstadoCuenta").val();
					$("#modalVerAdjuntosR").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					document.getElementById("tituloModal").innerHTML = "Estado de Cuenta";
				$("#divVerAdjuntosR").html("</iframe><iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/EstadoCuenta"+idEnganche+"?idEnganche="+idEnganche+"&estadoCuentaPdf=true#page=1&zoom=100'></iframe>");							
				}
				function enviarEstadoCuenta(){	
					idPago =  $("#idEngancheEstadoCuenta").val();
					var formData = new FormData();
					formData.append("idPago", idPago);
					$.ajax({
						url: "./emailRecibo.php?get_send_mail_estado_cuenta=true",
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
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err == true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');">Aceptar</div>');
							}	
							//buscarCliente();
							//buscarClienteUnico(response.id,response.proyecto,response.clientName);						
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function verReciboPago(idPago){
					$("#modalVerAdjuntosR").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					document.getElementById("tituloModal").innerHTML = "Recibo de Pago";
				$("#divVerAdjuntosR").html("</iframe>  SE DESCARGO RECIBO EXITOSAMENTE...<iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/reciboNo"+idPago+"?idPago="+idPago+"&reciboPdf=true#page=1&zoom=100'></iframe>");							
				}
				function verReciboPagoExtra(idPago){ 
					$("#modalVerAdjuntosR").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					document.getElementById("tituloModal").innerHTML = "Recibo de Pago Extra";
				$("#divVerAdjuntosR").html("</iframe>  SE DESCARGO RECIBO EXITOSAMENTE...<iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/reciboNo"+idPago+"?idPago="+idPago+"&reciboExtraPdf=true#page=1&zoom=100'></iframe>");							
				}
				function verReciboPagoExtraEng(idPago){ 
					$("#modalVerAdjuntosR").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					document.getElementById("tituloModal").innerHTML = "Recibo de Pago Extra";
				$("#divVerAdjuntosR").html("</iframe>  SE DESCARGO RECIBO EXITOSAMENTE...<iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/reciboNo"+idPago+"?idPago="+idPago+"&reciboExtraEngPdf=true#page=1&zoom=100'></iframe>");							
				}
				function enviarRecibo(idPago){	
					var formData = new FormData();
					formData.append("idPago", idPago);
					$.ajax({
						url: "./emailRecibo.php?get_send_mail_recibo=true",
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
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err == true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');">Aceptar</div>');
							}	
							//buscarCliente();
							//buscarClienteUnico(response.id,response.proyecto,response.clientName);						
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function verReciboReserva(idPago){
					$("#modalVerAdjuntosR").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					document.getElementById("tituloModal").innerHTML = "Recibo de Pago Reserva";
				$("#divVerAdjuntosR").html("</iframe> SE DESCARGO RECIBO EXITOSAMENTE...<iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/reciboNo"+idPago+"?idPago="+idPago+"&reservaPdf=true#page=1&zoom=100'></iframe>");							
				}
				function enviarReciboReserva(idPago){	
					var formData = new FormData();
					formData.append("idPago", idPago);
					$.ajax({
						url: "./emailRecibo.php?get_send_mail_reserva=true",
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
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err == true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');">Aceptar</div>');
							}	
							//buscarCliente();
							//buscarClienteUnico(response.id,response.proyecto,response.clientName);						
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
			</script>
    </body>
</html>

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
		<?php
			include "menu.php";
		?>
		<!-- DOCUMETNOS ADJUNTOS FUNCIONES -->
		<!-- <script src="../js/documentos_adjuntos.js"></script> -->
		<div class="wrapper">	
			<div class="content-wrapper">
				<div class="">
					<section class="content">
						<div class="row">
							<div class="col-md-12">
								<div class="box box-warning">
									<div  class="box-header with-border">
										<div class="col-lg-12 col-md-12" style="text-align:center;margin-bottom:10px;margin-top:10px;" id="headerCatalogo">
                							<label class="apartamentosearchitittle"><img class="usersearchicon" src=""> Búsqueda de Formalización</label>
											<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="divAlertPendiente" name="divAlertPendiente">
                								
											</div>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
										<div class="row">
											<div class="col-md-12" id="busquedaApartamentos">
												<form autocomplete="off"  enctype="multipart/form-data"  id="frmBuscarApartamento" name="frmBuscarApartamento" method="POST">	
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
															<input class=" search form-control" type="" id="datoBuscar" name="datoBuscar" placeholder="Nombre, correo, télefono">	
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
															<button onclick="buscarApartamento()" class="searchf" type="button">Buscar</button>															
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
																		<th style="width:10%;">Codigo</th>
																		<th style="width:40%;">Cliente</th>
																		<th style="width:15%;">Proyecto</th>
																		<th style="width:25%;">Apartamento</th> 
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
					<div class="modal fade" id="modalAgregarEnganche"  >
						<div class="modal-dialog mw-100 w-75" style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content">
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Formalización de Enganche</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarEnganche"  style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarFormalizarEnganche" name="frmAgregarFormalizarEnganche" method="POST">
											<div class="row" >
												<input type="hidden" id="idEnganche" name="idEnganche">
												<input type="hidden" id="pagosEngancheEng" name="pagosEngancheEng">
												<input type="hidden" id="bodegaPrecioCot" name="bodegaPrecioCot">
												<input type="hidden" id="cocinaTipoACot" name="cocinaTipoACot">
												<input type="hidden" id="cocinaTipoBCot" name="cocinaTipoBCot">
												<input type="hidden" id="iusiCot" name="iusiCot">
												<input type="hidden" id="porcentajeFacturacionCot" name="porcentajeFacturacionCot">
												<input type="hidden" id="seguroCot" name="seguroCot">
												<input type="hidden" id="rateCot" name="rateCot">
												<input type="hidden" id="idOcaInfo" name="idOcaInfo">
												<input type="hidden" id="nombreClienteInfo" name="nombreClienteInfo">
												

												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarCliente" name="frmAgregarCliente" method="POST">
														<div class="row" >
															<div id="divAlertEnganche" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																<div class="table-responsive">
																	<table id="resultadoEncabezado" class="table table-sm table-hover"  style="width:100%">
																	</table>
																</div>
															</div>
															
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext"><img class="infoselicon" src="../img/sale_info.png" alt=""> Información de venta</label>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Forma de pago:</label>
																<select class="form-control" name="formaPagoEng" id="formaPagoEng"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="Enganche Fraccionado/Crédito Bancario" >Enganche Fraccionado/Crédito Bancario</optinon>
																	<option value="Contado" >Contado</optinon>
																	<option value="Otro" >Otro</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco depósito reserva:</label>
																<select class="form-control" name="bancoDepositoReservaEng" id="bancoDepositoReservaEng"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco Cheque/Transferencia:</label>
																<select class="form-control" name="bancoChequeReservaEng" id="bancoChequeReservaEng"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Deposito/ Cheque/ transferencia Reserva:</label>
																<input type="text" id="noDepositoReservaEng" name="noDepositoReservaEng" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Recibo:</label>
																<input type="text" id="noReciboEng" name="noReciboEng" class="form-control">
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Observaciones:</label>
																<textarea class="form-control" id="observacionesForm" name="observacionesForm" rows="2"></textarea>
															</div>
															<!-- <div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Cheque reserva:</label>
																<input type="text" id="noChequeReservaEng" name="noChequeReservaEng" class="form-control">
															</div> -->
															<!-- <div id="divCalculoCuota" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;text-align:right;display:none" >
																<br><br><button onclick="calculoCuotas()" class="cuotas" type="button">Calcular Cuotas</button>
															</div> -->
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
																<br><br><button onclick="guardarFormalizarEnganche()" class="guardar" type="button">Guardar</button>
																<button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Agregar Adjuntos</button>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																<div class="table-responsive">
																	<table id="resultadoCuotas" class="table table-sm table-hover"  style="width:100%">
																		<tr>
																			<th style="width:10%;">No.</th>
																			<th style="width:20%;">Pago Especial</th> 
																			<th style="width:35%;">Cuota</th>
																			<th style="width:35%;">Fecha</th>
																		</tr>
																	</table>
																</div>
															</div>
															
															<script type="text/javascript">
																$("#descuentoPorcentualMontoEng").number( true, 2 );
																$("#descuentoPorcentualEng").number( true, 2 );
																$("#engancheEng").number( true, 2 );
																$("#engancheMontoEng").number( true, 2 );
																$("#montoReservaEng").number( true, 2 );
																$("#pagoPromesaEng").number( true, 2 );
																$("#descuentoEng").number( true, 2 );
																
															</script>
														</div>
													</form>
												</div>
												<div id="divAlertCliente" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
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
					<div class="modal fade" id="modalVerAdjuntosReserva">
						<div class="modal-dialog mw-100 w-75">
							<div class="modal-content">
								<div class="modal-header" id="headerVerAdjuntosReserva" style="padding:5px 15px;">
									<h5 class="tittle" >Documento Reserva</h5>
									<button type="button" class="close" aria-label="Close" data-dismiss="modal">
										<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body" id="divVerAdjuntosReserva" style="padding:5px 15px;">					
									<!-- <div class="col-lg-12 col-md-12 col-xs-10" id="divVerAdjuntos" style="padding:5px 15px;">
									</div> -->
								</div>
								
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>	
					<!-- MODAL DOCUMENTOS ADJUNTOS -->
					<?php //require_once("./documentos_adjuntos.php"); ?>				
				</div>
			</div>
		</div>
			<script type="text/javascript">
			function getBancos(input,valueInput){
				//console.log("funcion buscar niveles");
				var formData = new FormData;
				
				$.ajax({
					url: "./cliente.php?get_bancos=true",
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
						$.each(response.bancos,function(i,e) {
							if(valueInput==e.banco){
								select= 'selected="selected"';
							}else{
								select='';
							}
							output += ' <option '+select+' value="'+e.banco+'">'+e.banco+'</option>';
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
				function buscarApartamento(){
					//console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarApartamento"));
					formData.append("proyectoBscTxt", document.getElementById("proyectoBsc").options[document.getElementById("proyectoBsc").selectedIndex].text);
					formData.append("torreBscTxt", document.getElementById("torreBsc").options[document.getElementById("torreBsc").selectedIndex].text);
					formData.append("nivelBscTxt", document.getElementById("nivelBsc").options[document.getElementById("nivelBsc").selectedIndex].text);
					formData.append("apartamentoBscTxt", document.getElementById("apartamentoBsc").options[document.getElementById("apartamentoBsc").selectedIndex].text);
					$.ajax({
						url: "./cliente.php?get_apartamento_lista=true",
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
								output += '<tr onCLick=""><td>'+e.codigo+'</td><td>'+e.client_name+' </td><td>'+e.proyecto+'</td><td>'+e.apartamento+'</td><td><button onclick="formalizarEnganche(\''+e.id+'\')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ><button onclick="verEnganche('+e.id+')" class="btn btn-xs" type="button"><img class="" src="../img/Engagement.png" alt="ver Recibo" ></button></td></tr>';
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
							var count = 0;
							output+="<tr>";
								output+="<td >Pago Reserva</td>";
								output+="<td></td>";
								output+="<td><input  id=\"reserva\"  type=\"number\" value=\""+response.reserva+"\" readonly=\"readonly\"></td>";
								output+="<td><input id=\"date_reserva\"  type=\"date\" value=\""+response.fechaReserva+"\" readonly=\"readonly\" ></td>";
								output+="</tr>";
							$.each(response.detallePagos,function(i,e) {
								count++;
								montoEngancheTotal= e.montoEnganche;
								checkDisabled = e.pagado==1?'disabled="disabled"':'';
								checked = e.pagoEspecial==1?'checked':'';
								output+="<tr>";
								output+="<td >"+e.noPago+"<input id=\"noPago_"+e.noPago+"\" name=\"noPago[]\" type=\"hidden\" value=\""+e.noPago+"\" readonly=\"readonly\" ></td>";
								output+="<td><input id=\"chkD_"+e.noPago+"\" type=\"hidden\" value=\"No\" name=\"chk[]\"><input "+checked+" onChange=\"pagoEspecial("+e.montoEnganche+")\" id=\"chk_"+e.noPago+"\" name=\"chk[]\" type=\"checkbox\" class=\"form-check-input\" "+checkDisabled+"> <label class=\"form-check-label\" for=\"exampleCheck1\">Especial</label></td>";
								output+="<td><input onkeyup=\"recalculoPagoEspecial("+e.montoEnganche+")\" id=\"cuota_"+e.noPago+"\" name=\"cuotas[]\" type=\"number\" value=\""+e.monto+"\" readonly=\"readonly\"></td>";
								output+="<td><input id=\"date_"+e.noPago+"\" name=\"date[]\" type=\"date\" value=\""+e.fechaPago+"\" ></td>";
								output+="</tr>";
								//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
							});
							
							output+="<tr>";				
							output+="<td colspan=\"4\">"+"<h5 class=\"tittle\" >Total "+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoEngancheTotal)+"</h5>"+ "<input id=\"totalEnganche\" name=\"totalEnganche\" type=\"hidden\" value=\""+montoEngancheTotal+"\"></td>";
							output+="</tr>";
							var tb = document.getElementById('resultadoCuotas');
							while(tb.rows.length > 1) {
								tb.deleteRow(1);
							}
							$('#resultadoCuotas').append(output);
							if(count==0){
								calculoCuotas(idEnganche);
							}
							//$('#resultadoCuotas').append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}

				function formalizarEnganche(idEnganche){
					var formData = new FormData;
					formData.append("idEnganche", idEnganche);
					$.ajax({
						url: "./cliente.php?get_formalizar_enganche=true",
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
							outputE="";
							var pagoFinal = parseFloat(response.contracargo) + parseFloat(response.bodega) + parseFloat(response.parqueo) + parseFloat(response.precio) - parseFloat(response.descuento) - parseFloat(response.enganchePorcMonto);
							var totalApartamento = parseFloat(pagoFinal) + parseFloat(response.enganchePorcMonto)
							console.log (pagoFinal +'=' +response.bodega +'+'+ response.parqueo +'+'+ response.precio +'-'+ response.descuento +'-'+ response.enganchePorcMonto)
							if(response.fechaPagoFinalFormat!=''){
								var checkF='<i class="fa fa-check-square-o"> Pagado</i>';
							}else
							{
								var checkF='<i class="fa fa-times"> Pendiente</i>';
							}
							var montoEngancheReserva = parseFloat(response.enganchePorcMonto);
							outputE+="<tr>";
								outputE+="<th >Codigo</th>";
								outputE+="<th>Nombre Cliente</th>";
								outputE+="<th>Apartamento</th>";
								outputE+="</tr>";
								outputE+="<tr>";
								outputE+="<td >"+response.codigo+"</td>";
								outputE+="<td>"+response.nombreCliente+"</td>";
								outputE+="<td>"+response.apartamento+"</td>";
								outputE+="</tr>";
								outputE+="<tr>";
								outputE+="<th >Precio de Venta</th>";
								outputE+="<th>Enganche Total</th>";
								outputE+="<th>Saldo contra Entrega</th>";
								outputE+="</tr>";
								outputE+="<tr>";
								outputE+="<td >"+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(totalApartamento)+"</td>";
								outputE+="<td>"+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoEngancheReserva)+"</td>";
								outputE+="<td>"+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(pagoFinal)+"</td>";
								outputE+="</tr>";
							$.each(response.info,function(i,e) {
								//Campos Enganche
								$("#idEnganche").val(e.idEnganche);
								getEngancheDetalle(e.idEnganche);
								getBancos('bancoDepositoReservaEng',e.bancoDepositoReserva);	
								$("#formaPagoEng").val(e.formaPago);
								$("#noDepositoReservaEng").val(e.noDepositoReserva);
								$("#noReciboEng").val(e.noReciboReserva);
								//$("#bancoChequeReservaEng").val(e.bancoChequeReserva);
								//$("#bancoDepositoReservaEng").val(e.bancoDepositoReserva);
								$("#noChequeReservaEng").val(e.noChequeReserva);
								$("#pagosEngancheEng").val(e.pagosEnganche);
								$("#observacionesForm").val(e.observacionesForm);

								$("#bodegaPrecioCot").val(e.bodega_precio);
								$("#cocinaTipoACot").val(e.cocinaTipoA);
								$("#cocinaTipoBCot").val(e.cocinaTipoB);
								$("#iusiCot").val(e.iusi);
								$("#porcentajeFacturacionCot").val(e.porcentajeFacturacion);
								$("#seguroCot").val(e.seguro);
								$("#rateCot").val(e.rate);
								$("#idOcaInfo").val(e.idCliente);
								$("#nombreClienteInfo").val(e.client_name);
								
								
								
							});
							var tbE = document.getElementById('resultadoEncabezado');
							console.log(tbE)
							while(tbE.rows.length >= 1) {
								tbE.deleteRow(0);
							}
							$('#resultadoEncabezado').append(outputE);
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
				
				
				function guardarFormalizarEnganche(){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarFormalizarEnganche"));
					formDataEnganche.append("idEnganche", $("#idEnganche").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_formalizar_enganche=true",
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
							if (response.err == true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarApartamento();verEnganche('+response.idEnganche+')">Aceptar</div>');
							}	
													
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function verEnganche(idEnganche){
					$("#modalVerAdjuntosReserva").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
				$("#divVerAdjuntosReserva").html("</iframe> SE DESCARGO EL ENGANCHE EXITOSAMENTE...<iframe frameborder='0' style='width:100%; height:100%' align='right' src='./generarPdf.php/engancheNo"+idEnganche+"?idEnganche="+idEnganche+"&enganchePdf=true'></iframe>");							
				}
				function calculoCuotas(idEnganche){
					//guardarEnganche();
					var formDataEnganche = new FormData;
					formDataEnganche.append("idEnganche", idEnganche);
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
								var precioQ = parseFloat(e.precioQ);
								var totalParqueo = parseFloat(e.totalParqueo);
								var totalQ = precioQ + totalParqueo;
								var descuento = totalQ * (parseFloat(e.descuento_porcentual)/100);
								console.log(descuento);
								var totalQD = parseFloat(totalQ) - parseFloat(descuento);
								console.log(totalQD);
								var enganche = (parseFloat(e.enganchePorc)/100);
								var engancheMonto = parseFloat(e.enganchePorcMonto);
								//var engancheMonto = totalQD * enganche;
								console.log (engancheMonto);
								var totalEnganche = Math.round(engancheMonto - parseFloat(e.montoReserva));
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
									output+="<td><input id=\"chkD_"+no+"\" type=\"hidden\" value=\"No\" name=\"chk[]\"><input onChange=\"pagoEspecial("+totalEnganche+")\" id=\"chk_"+no+"\" name=\"chk[]\" type=\"checkbox\" class=\"form-check-input\"> <label class=\"form-check-label\" for=\"exampleCheck1\">Especial</label></td>";
									output+="<td><input onkeyup=\"recalculoPagoEspecial("+totalEnganche+")\" id=\"cuota_"+no+"\" name=\"cuotas[]\" type=\"number\" value=\""+cuota.toFixed(2)+"\" readonly=\"readonly\"></td>";
									output+="<td><input id=\"date_"+no+"\" name=\"date[]\" type=\"date\" value=\""+res+"\" ></td>";
									output+="</tr>";
								}
								output+="<tr>";
								
								output+="<td colspan=\"4\">"+"<h5 class=\"tittle\" >Enganche Total a Pagar "+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(totalEnganche + parseFloat(e.montoReserva))+"</h5>"+ "<input id=\"totalEnganche\" name=\"totalEnganche\" type=\"hidden\" value=\""+totalEnganche+"\"></td>";
								output+="</tr>";
								//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
								//console.log(output);
								var tb = document.getElementById('resultadoCuotas');
								while(tb.rows.length > 2) {
									tb.deleteRow(2);
								}
							});
							
							$('#resultadoCuotas').append(output);				
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
			function setDescuento(tipo,value){
				var precio =  $("#precioTotalEng").val()!='' ? $("#precioTotalEng").val():'0';
				var precio = parseFloat(precio.replace(/[Q,]/g,''));
				////console.log(precio);
				////console.log(precio+ ' '+$("#precioTotalEng").val());
				if(tipo=='porcentaje'){
					var montoPorcentaje = precio * (value/100);
					$("#descuentoPorcentualMontoEng").val(montoPorcentaje);
				}else if(tipo=='monto'){
					if(precio>0){
						porcentaje = 100 * ($("#descuentoPorcentualMontoEng").val()/precio);
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
				var nuevoPrecio = precio - descuento;
				//console.log(precio);
				//console.log(precio+ ' '+nuevoPrecio);
				if(tipo=='porcentaje'){
					var montoPorcentaje = nuevoPrecio * (value/100);
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
						document.getElementById("chkD_"+split[1]).disabled = true;
					}else{
						document.getElementById("cuota_"+split[1]).readOnly = true;
						document.getElementById("chkD_"+split[1]).disabled = false;
					}
				});
				recalculoPagoEspecial(total);
			}
			function recalculoPagoEspecial(total){
				//console.log("entrando pago especial calculo")
				var montoPagosEspeciales = 0;
				var checked=0;
				var disabled=0;
				var montoPagado = 0;
				$("#resultadoCuotas input[type='checkbox']").each(function () {
					if ($(this).is(':disabled')) {
						var split = this.id.split('_');
						console.log("cuota disabled "+$("#cuota_"+split[1]).val());
						montoPagado += parseFloat($("#cuota_"+split[1]).val());
						disabled++;
					}else{
					}
				});
				console.log("Total: "+montoPagado);
				$("#resultadoCuotas input[type='checkbox']").each(function () {
					if ($(this).is(':checked')) {
						var split = this.id.split('_');
						montoPagosEspeciales += parseFloat($("#cuota_"+split[1]).val())
					}else{
					}
				});
				console.log(parseFloat(total)+" - "+parseFloat(montoPagado)+" - "+parseFloat(montoPagosEspeciales));
				var nuevoTotal = (parseFloat(total) -  parseFloat(montoPagado)) - parseFloat(montoPagosEspeciales);

				//console.log(nuevoTotal);
				$("#resultadoCuotas input[type='checkbox']").each(function () {
					
					var split = this.id.split('_');
					if ($(this).is(':checked')) {
					}else{
						checked++;
					}
				});
				var cuota = nuevoTotal/parseFloat(checked - disabled);
				//console.log(cuota +"="+ nuevoTotal+" / "+parseInt(checked))
				$("#resultadoCuotas input[type='checkbox']").each(function () {
					var checked=0;
					var split = this.id.split('_');
					if ($(this).is(':checked')) {
						//console.log("checked");
					}else{
						if($(this).is(':disabled')){
							console.log("disabled");
						}else{
							cantidad=$("#cuota_"+split[1]).val(cuota.toFixed(2));
						}
						
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
			</script>
    </body>
</html>

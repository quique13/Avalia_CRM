<?php
session_name("inmobiliaria");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>"; 
}
$id_usuario=$_SESSION['id_usuario'];
$id_perfil=$_SESSION['id_perfil'];
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
                							<label class="apartamentosearchitittle"><img class="usersearchicon" src=""> Búsqueda Reserva de Apartamento</label>
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
															<input type="hidden" id="idPerfil" name="idPerfil" value="<?php echo $id_perfil; ?>"  >
															<input id="id_usuario" name="id_usuario" type="hidden" value="<?php echo $id_usuario ?>" >
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
																		<th style="width:30%;">Nombre</th>
																		<th style="width:15%;">Proyecto</th>
																		<th style="width:10%;">Apartamento</th> 
																		<th style="width:10%;">Monto Reserva</th> 
																		<th style="width:10%;">Fecha</th>
																		<th style="width:10%;">Estado</th> 
																		<th style="width:15%;">Acciones</th> 
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
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Reserva de Apartamento</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarEnganche"  style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarFormalizarEnganche" name="frmAgregarFormalizarEnganche" method="POST">
											<div class="row" >
												<input type="hidden" id="idReserva" name="idReserva">	
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
																<label class="nodpitext"><img class="infoselicon" src="../img/sale_info.png" alt=""> Información de reserva</label>
															</div>
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Proyecto:</label>
																<select class="form-control" name="ProyectoCot" id="ProyectoCot"  onchange="torres(this.value,'torreCot')">
																	<option value="0" >Seleccione</optinon>
																	<? echo $proyectos ?>
																</select>
															</div>
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Torre/Fase:</label>
																<select class="form-control" name="torreCot" id="torreCot" onchange="niveles(this.value,'nivelCot')">
																</select>
															</div>
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Nivel:</label>
																<select class="form-control" name="nivelCot" id="nivelCot"  onchange="apartamentos(0,this.value,'apartamentoCot')">
																</select>
															</div>
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Apartamento:</label>
																<select class="form-control" name="apartamentoCot" id="apartamentoCot" onchange="datosApartamento(this.value,1)">
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Nombre Cliente:</label>
																<input type="text" id="nombreCompleto" name="nombreCompleto" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. DPI:</label>
																<input type="text" id="numeroDpi" name="numeroDpi" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Monto de Reserva:</label>
																<input type="text" id="montoReserva" name="montoReserva" class="form-control">
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
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Fecha pago:</label>
																<input type="date" id="fechaPago" name="fechaPago" max="<?php echo date("Y-m-d") ?>" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Nombre Contacto:</label>
																<select class="form-control" name="nombreVendedorCot" id="nombreVendedorCot"  onchange="">
																</select>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Observaciones:</label>
																<textarea class="form-control" id="observacionesForm" name="observacionesForm" rows="2"></textarea>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
																<br><br><button onclick="guardarMontoReserva()" class="guardar" type="button">Guardar</button>
																<button onclick="desistirApartamento()" class="guardar" type="button">Desistir</button>
																<button onclick="validarPago()" class="guardar" type="button" id="btnValidar" <?php echo $disabledGuardar ?>>Validar</button>
																<!-- <button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Agregar Adjuntos</button> -->
															</div>
															<script type="text/javascript">
																$("#montoReserva").number( true, 2 );
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
					<div class="modal fade" id="modalVerAdjuntosR">
						<div class="modal-dialog mw-100 w-75">
							<div class="modal-content">
								<div class="modal-header" id="headerVerAdjuntosR" style="padding:5px 15px;">
									<h5 class="tittle" id="tituloModal" >Recibo Pago</h5>
									<button type="button" class="close" aria-label="Close" data-dismiss="modal">
										<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body" id="divVerAdjuntosR" style="padding:5px 15px;">					
									<!-- <div class="col-lg-12 col-md-12 col-xs-10" id="divVerAdjuntos" style="padding:5px 15px;">
									</div> -->
								</div>
								
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>	
					<!-- MODAL DOCUMENTOS ADJUNTOS -->
					<?php require_once("./documentos_adjuntos.php"); ?>				
				</div>
			</div>
		</div>
			<script type="text/javascript">
				if($("#idPerfil").val()!=5){
					formalizarEnganche(0);
				}
				
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
						url: "./cliente.php?get_apartamento_lista_reserva=true",
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
							var colorCodigo='';
							$.each(response.cotizaciones,function(i,e) {
								//console.log(e.user_name);
								if(e.creado=='si'){
									var check='<i class="fa fa-check-square-o"></i>';
								}else
								{
									var check="";
								}
								colorCodigo='';
								if(e.estado =='Pendiente de Validar'){
									colorCodigo='#FA0404';
								}
								
								output += '<tr style="color:'+colorCodigo+'" onCLick=""><td>'+e.nombreCompleto+'</td><td>'+e.proyecto+' </td><td>'+e.apartamento+'</td><td>'+e.montoReserva+'</td><td>'+e.fechaPagoReservaFormat+'</td><td>'+e.estado+'</td><td><button onclick="formalizarEnganche(\''+e.idReserva+'\')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ><button onclick="verReciboReserva('+e.idReserva+')" class="btn btn-xs" type="button"><img class="" src="../img/Engagement.png" alt="ver Recibo" ></button><button onclick="eliminarMontoReserva(\''+e.idReserva+'\')" class="btn btn-xs" type="button"><img class="" src="../img/Less_button.png" alt="abrir cliente" ></td></tr>';
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
				function verReciboReserva(idPago){
					$("#modalVerAdjuntosR").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					document.getElementById("tituloModal").innerHTML = "Recibo de Pago Reserva";
				$("#divVerAdjuntosR").html("</iframe> SE DESCARGO RECIBO EXITOSAMENTE...<iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/reciboNo"+idPago+"?idPago="+idPago+"&reservaMontoPdf=true#page=1&zoom=100'></iframe>");							
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

				function formalizarEnganche(idReserva){
					getVendedor('nombreVendedorCot',0)
					getBancos('bancoDepositoReservaEng',0);
					getBancos('bancoChequeReservaEng',0);
					$("#idReserva").val(idReserva);
					var formData = new FormData;
					formData.append("idReserva", idReserva);
					$.ajax({
						url: "./cliente.php?get_reserva_apartamento=true",
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
								//Campos Reserva
								getVendedor('nombreVendedorCot',e.idVendedor)
								getBancos('bancoDepositoReservaEng',e.bacoDepositoReserva);
								getBancos('bancoChequeReservaEng',e.bacoChequeReserva);
								$("#formaPagoEng").val(e.formaPago);
								$("#noDepositoReservaEng").val(e.noDepositoReserva);
								$("#noReciboEng").val(e.noReciboReserva);
								$("#noChequeReservaEng").val(e.noChequeReserva);
								$("#observacionesForm").val(e.observaciones);
								$("#nombreCompleto").val(e.nombreCompleto);
								$("#numeroDpi").val(e.numeroDpi);
								$("#nombreVendedorCot").val(e.idVendedor);
								$("#montoReserva").val(e.montoReserva);
								$("#bancoDepositoReservaEng").val(e.bacoDepositoReserva);
								$("#bancoChequeReservaEng").val(e.bacoChequeReserva);
								$("#fechaPago").val(e.fechaPagoReserva);
								$("#ProyectoCot").val(e.idProyecto);
								torres(e.idProyecto,'torreCot',e.idTorre)
								niveles(e.idTorre,'nivelCot',e.idNivel);
								apartamentos(e.idProyecto,e.idNivel,'apartamentoCot',e.idApartamento);
								if(e.estado == 1 && idReserva != 0){
									$('#btnValidar').show();
									if(e.validado==0){
										if($("#id_usuario").val()== 11){
											console.log("validar");
											document.getElementById("btnValidar").innerHTML = 'Validar';
											document.getElementById("btnValidar").disabled = false;
										}else{
											document.getElementById("btnValidar").innerHTML = 'Pendiente';
											document.getElementById("btnValidar").disabled = true;
										}
									}else{
										document.getElementById("btnValidar").innerHTML = 'Validado';
										document.getElementById("btnValidar").disabled = true;
									}
								}else
									$('#btnValidar').hide();
								
								
								
								
							});
							$("#modalAgregarEnganche").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if(idReserva == 0){
								$('#btnValidar').hide();
							}
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function validarPago(idReserva){
					var formData = new FormData(document.getElementById("frmAgregarFormalizarEnganche"));
					formData.append("idReserva", $("#idReserva").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_pago_validar_reserva=true",
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
							$('#bodyAgregarEnganche').animate({scrollTop:0}, 'fast');
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err === true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');formalizarEnganche('+response.idMontoReserva+')">Aceptar</div>');
							}				
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
				function guardarMontoReserva(){
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					if($("#nombreCompleto").val()==''){
						error++;
						msjError =msjError+ '*Nombre Completo <br>'
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
					 if($("#montoReserva").val()==''){
						error++;
						msjError =msjError+ '*Monto Reserva <br>'
					}
					if($("#formaPagoEng").val()==''){
						error++;
						msjError =msjError+ '*Forma de Pago <br>'
					}
					if($("#bancoDepositoReservaEng").val()==''){
						error++;
						msjError =msjError+ '*Banco depósito Reserva <br>'
					}
					if($("#noDepositoReservaEng").val()==''){
						error++;
						msjError =msjError+ '*No. Deposito Reserva <br>'
					}
					if($("#nombreVendedorCot").val()==''){
						error++;
						msjError =msjError+ '*Nombre Vendedor <br>'
					}						
					if(error==0){
						var formData = new FormData(document.getElementById("frmAgregarFormalizarEnganche"));
						formData.append("idReserva", $("#idReserva").val());
						formData.append("txtProyecto", document.getElementById("ProyectoCot").options[document.getElementById("ProyectoCot").selectedIndex].text);
						formData.append("txtTorre", document.getElementById("torreCot").options[document.getElementById("torreCot").selectedIndex].text);
						formData.append("txtNivel", document.getElementById("nivelCot").options[document.getElementById("nivelCot").selectedIndex].text);
						formData.append("txtApartamento", document.getElementById("apartamentoCot").options[document.getElementById("apartamentoCot").selectedIndex].text);

						$.ajax({
							url: "./cliente.php?agregar_editar_monto_reserva=true",
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
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarApartamento()">Aceptar</div>');
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

				function eliminarMontoReserva(idReserva){
					var formData = new FormData;
					formData.append("idReserva", idReserva);
					

					$.ajax({
						url: "./cliente.php?eliminar_monto_reserva=true",
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
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarApartamento()">Aceptar</div>');
							}	
													
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function desistirApartamento(){
					var formData = new FormData;
					formData.append("idReserva", $("#idReserva").val());
					

					$.ajax({
						url: "./cliente.php?desistirApto=true",
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
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarApartamento()">Aceptar</div>');
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
				$("#divVerAdjuntosReserva").html("</iframe><iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/engancheNo"+idEnganche+"?idEnganche="+idEnganche+"&enganchePdf=true#page=1&zoom=100'></iframe>");							
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
								var engancheMonto = totalQD * enganche;
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

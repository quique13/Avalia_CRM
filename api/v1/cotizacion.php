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

		<div class="wrapper">	
			<div class="content-wrapper">
				<div class="">
					<section class="content">
						<div class="row">
							<div class="col-md-12">
								<div class="box box-warning">
									<div  class="box-header with-border">
										<div class="col-lg-12 col-md-12" style="text-align:center;margin-bottom:10px;margin-top:10px;" id="headerCatalogo">
                							<!-- <label class="usersearchitittle"><img class="usersearchicon" src="../img/usersearchicon.png"> Busqueda de clientes</label> -->
											<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="divAlertPendiente" name="divAlertPendiente">
                								
											</div>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
										<div class="row">
										</div>
									</div>
								</div>
							</div>
						<!-- /.col -->
						</div>
					</section>
					<!-- /.modal -->
					<div class="modal fade" id="modalAgregarCotizacion"  >
						<div class="modal-dialog mw-100 w-75" style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content">
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Cotización</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarCotizacion"  style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarCotizacion" name="frmAgregarCotizacion" method="POST">
											<div class="row" >
												<input type="hidden" id="idCotizacion" name="idCotizacion">
												<input type="hidden" id="bodegaPrecioCot" name="bodegaPrecioCot">
												<input type="hidden" id="cocinaTipoACot" name="cocinaTipoACot">
												<input type="hidden" id="cocinaTipoBCot" name="cocinaTipoBCot">
												<input type="hidden" id="iusiCot" name="iusiCot">
												<input type="hidden" id="parqueoExtraCot" name="parqueoExtraCot">
												<input type="hidden" id="parqueoExtraMotoCot" name="parqueoExtraMotoCot">
												<input type="hidden" id="porcentajeEngacheCot" name="porcentajeEngacheCot">
												<input type="hidden" id="porcentajeFacturacionCot" name="porcentajeFacturacionCot">
												<input type="hidden" id="seguroCot" name="seguroCot">
												<input type="hidden" id="ReservaCot" name="ReservaCot">
												<input type="hidden" id="rateCot" name="rateCot">
												
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarCliente" name="frmAgregarCliente" method="POST">
														<div class="row" >
															<div id="divAlertCotizacion" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext"><img class="infoselicon" src="../img/apartment_info.png" alt=""> Información de apartamento</label>
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
	
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Tamaño en mt2:</label>
																<input type="text" id="mt2Cot" name="mt2Cot" class="form-control" readonly>
															</div>
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Habitaciones:</label>
																<input type="text" id="habitacionesCot" name="habitacionesCot" class="form-control" readonly>
															</div>
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Bodega:</label>
																<input type="text" id="bodegaCot" name="bodegaCot" class="form-control" readonly>
															</div>
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Parqueo para moto</label>
																<input type="text" id="parqueoMotoCot" name="parqueoMotoCot" class="form-control" readonly>
															</div>
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Parqueo para carro</label>
																<input type="text" id="parqueoCot" name="parqueoCot" class="form-control" readonly>
															</div>
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Ärea Jardín mt2</label>
																<input type="text" id="jardinMt2Cot" name="jardinMt2Cot" class="form-control" readonly>
															</div>
															<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Precio Total (GtQ)</label>
																<input type="text" id="precioTotalCot" name="precioTotalCot" class="form-control" readonly>
															</div>

															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext"><img class="infoselicon" src="../img/client_icon.png" alt=""> Información Cliente</label>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Nombre del Cliente:</label>
																<input type="text" id="nombreClienteCot" name="nombreClienteCot" class="form-control">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Correo:</label>
																<input type="text" id="correoCot" name="correoCot" class="form-control">
															</div>
															<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Télefono:</label>
																<input type="text" id="telefonoCot" name="telefonoCot" class="form-control">
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext"><img class="infoselicon" src="../img/sale_info.png" alt=""> Información de venta</label>
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" onChange="setDescuentoCot('porcentaje',descuentoPorcentualCot.value)">
																<label class="nodpitext">Parqueos extra:</label>
																<input type="number" id="parqueosExtraCot" name="parqueosExtraCot" class="form-control">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" onChange="setDescuentoCot('porcentaje',descuentoPorcentualCot.value)">
																<label class="nodpitext">Bodegas extra:</label>
																<input type="number" id="bodegaExtraCot" name="bodegaExtraCot" class="form-control">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Descuento (%):</label>
																<input  type="text" id="descuentoPorcentualCot" name="descuentoPorcentualCot" class="form-control"  onChange="setDescuentoCot('porcentaje',this.value)">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Descuento:</label>
																<input type="text" id="descuentoPorcentualMontoCot" name="descuentoPorcentualMontoCot" class="form-control" onChange="setDescuentoCot('monto',this.value)">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Enganche(%):</label>
																<input type="text" id="engancheCot" name="engancheCot" class="form-control" onChange="setEngancheCot('porcentaje',this.value)">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Enganche:</label>
																<input type="text" id="engancheMontoCot" name="engancheMontoCot" class="form-control" onChange="setEngancheCot('monto',this.value)">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Monto Reserva:</label>
																<input type="text" id="montoReservaCot" name="montoReservaCot" class="form-control">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Plazo Financiamiento:</label>
																<select class="form-control" name="plazoFinanciamientoCot" id="plazoFinanciamientoCot"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="5" >5 años</optinon>
																	<option value="10" >10 años</optinon>
																	<option value="15" >15 años</optinon>
																	<option value="20" >20 años</optinon>
																	<option value="25" >25 años</optinon>
																</select>
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Pagos de Enganche:</label>
																<select class="form-control" name="pagosEngancheCot" id="pagosEngancheCot"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="1" >1 mes</optinon>
																	<option value="2" >2 meses</optinon>
																	<option value="3" >3 meses</optinon>
																	<option value="4" >4 meses</optinon>
																	<option value="5" >5 meses</optinon>
																	<option value="6" >6 meses</optinon>
																	<option value="7" >7 meses</optinon>
																	<option value="8" >8 meses</optinon>
																	<option value="9" >9 meses</optinon>
																	<option value="10" >10 meses</optinon>
																	<option value="11" >11 meses</optinon>
																	<option value="12" >12 meses</optinon>
																	<option value="13" >13 meses</optinon>
																	<option value="14" >14 meses</optinon>
																	<option value="15" >15 meses</optinon>
																	<option value="16" >16 meses</optinon>
																	<option value="17" >17 meses</optinon>
																	<option value="18" >18 meses</optinon>
																	<option value="19" >19 meses</optinon>
																	<option value="20" >20 meses</optinon>	
																</select>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext"><img class="infoselicon" src="../img/client_icon.png" alt=""> Información Vendedor</label>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Nombre Contacto:</label>
																<select class="form-control" name="nombreVendedorCot" id="nombreVendedorCot"  onchange="datosVendedor(this.value,1)">
																</select>
															</div>
															<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Télefono Contacto:</label>
																<input type="text" id="telefonoVendedorCot" name="telefonoVendedorCot" class="form-control" readonly>
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Email Contacto:</label>
																<input type="text" id="correoVendedorCot" name="correoVendedorCot" class="form-control" readonly>
															</div>
															<div id="" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;text-align:right;" >
																<br><br><button onclick="calculoCuotasCotizacion()" class="cuotas" type="button">Calcular Cuotas</button>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;text-align:left;">
																<br><br><button onclick="guardarCotizacion()" class="guardar" type="button">Guardar</button>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																<div class="table-responsive">
																	<table id="resultadoCuota" class="table table-sm table-hover"  style="width:100%">
																		<tr>
																			<th style="width:25%;">Cuota Mensual</th> 
																			<th style="width:25%;">Cuota IUSI</th>
																			<th style="width:25%;">Cuota seguro</th>
																			<th style="width:25%;">Cuota crédito</th>
																		</tr>
																	</table>
																	<table id="resultadoEnganche" class="table table-sm table-hover"  style="width:100%">
																		<tr>
																			<th style="width:25%;">Enganche</th> 
																			<th style="width:25%;">Cuota Enganche</th>
																			<th style="width:25%;">Ingreso Familiar Requerido</th>
																			<th style="width:25%;">Pago Contra entrega</th>
																		</tr>
																	</table>
																</div>
															</div>
															<script type="text/javascript">
																$("#descuentoPorcentualMontoCot").number( true, 2 );
																$("#descuentoPorcentualCot").number( true, 2 );
																$("#engancheCot").number( true, 2 );
																$("#engancheMontoCot").number( true, 2 );
																$("#montoReservaCot").number( true, 2 );
																
															</script>
														</div>
													</form>
												</div>
												<div id="divAlertCotizacion" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
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
					<div class="modal fade" id="modalVerAdjuntos">
						<div class="modal-dialog mw-100 w-75">
							<div class="modal-content">
								<div class="modal-header" id="headerVerAdjuntos" style="padding:5px 15px;">
									<h5 class="tittle" >Documento Cotización</h5>
									<button type="button" class="close" aria-label="Close" data-dismiss="modal">
										<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body" id="divVerAdjuntos" style="padding:5px 15px;">					
									<!-- <div class="col-lg-12 col-md-12 col-xs-10" id="divVerAdjuntos" style="padding:5px 15px;">
									</div> -->
								</div>
								
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
				</div>
			</div>
		</div>
			<script type="text/javascript">
			agregarCotizacion()
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
							var porcentaje=0;
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
									$("#engancheCot").val(e.porcentajeEnganche);
									$("#montoReservaCot").val(e.montoReserva);
									porcentaje = parseFloat(e.porcentajeEnganche);
								}
							});
							setDescuentoCot('porcentaje', 0);
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
				function guardarCotizacion(){
					//console.log("funcion guardar cliente");
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					
					if(error>=0){
						var formDataCotizacion = new FormData(document.getElementById("frmAgregarCotizacion"));
						formDataCotizacion.append("idCotizacion",$("#idCotizacion").val() );
						formDataCotizacion.append("txtProyecto", document.getElementById("ProyectoCot").options[document.getElementById("ProyectoCot").selectedIndex].text);
						formDataCotizacion.append("txtTorre", document.getElementById("torreCot").options[document.getElementById("torreCot").selectedIndex].text);
						formDataCotizacion.append("txtNivel", document.getElementById("nivelCot").options[document.getElementById("nivelCot").selectedIndex].text);
						formDataCotizacion.append("txtApartamento", document.getElementById("apartamentoCot").options[document.getElementById("apartamentoCot").selectedIndex].text);
						$.ajax({
							url: "./cliente.php?agregar_editar_cotizacion=true",
							type: "post",
							dataType: "json",
							data: formDataCotizacion,
							cache: false,
							contentType: false,
							processData: false,
							beforeSend:function (){
								$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
							},
							success:function (response){
								$('#bodyAgregarCotizacion').animate({scrollTop:0}, 'fast');
								$("#divAlertCotizacion").html('<div class="alert alert-success">'+response.mss+'</div>');
									setTimeout(function(){
										$("#divAlertCotizacion").html('');
									},2000)
									$("#modalVerAdjuntos").modal({
										backdrop: 'static',
										keyboard: false,
										show: true
									});
								// document.getElementById("divCalculoCuota").style.display = "";
								// buscarCliente();
								// buscarClienteUnico(response.id,response.proyecto,response.clientName);
								// verCotizacion(response.idCotizacion);
								//alert(response.idCotizacion);
								$("#divVerAdjuntos").html("</iframe> SE DESCARGO COTIZACIÓN EXITOSAMENTE...<iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/cotizacionNo"+response.idCotizacion+"?idCotizacion="+response.idCotizacion+"#page=1&zoom=100'></iframe>");							
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarCotizacion').animate({scrollTop:0}, 'fast');
						$("#divAlertCotizacion").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertCotizacion").html('');
							},5000)
					}
					
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
				function agregarCotizacion(){
					$("#idOcaCotizacion").val($("#idOcaInfo").val())
					torres(0,'torreCot')
					niveles(0,'nivelCot');
					apartamentos(0,0,'apartamentoCot');
					getVendedor('nombreVendedorCot',0)
					//getCotizacionDetalle(0);
					$("#ProyectoCot").val('');
					$("#engancheCot").val('');
					$("#idCotizacion").val(0);
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
					datosApartamento(0,1);
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
			function setEngancheCot(tipo,value){
				var precio =  $("#precioTotalCot").val()!='' ? $("#precioTotalCot").val():'0';
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
			</script>
    </body>
</html>

<?php
/*session_name("constructora");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>";
}
include("./class/config.php");*/
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Busqueda de usuarios</title>    
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="../css/styles.css" rel="stylesheet">
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
										<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="headerCatalogo">
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/usersearchicon.png"> Busqueda de clientes</label>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
										<div class="row">
											<div class="col-md-12" id="busquedaUsuarios">
												<form autocomplete="off"  enctype="multipart/form-data"  id="frmBuscarCliente" name="frmBuscarCliente" method="POST">
													<div class="row">	
														<div class="col-1 col-md-1">
														</div>
														<div class="col-11 col-md-11">
															<div class="row">
																<div class="col-lg-2 col-md-2 col-xs-10" style="text-align:left;margin-bottom:10px;">
																	<button class="back-1" type="button">Regresar</button>
																</div>
																<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
																	<div class="col-lg-12 col-md-12 col-xs-10" style="text-align:left;margin-bottom:10px;">
																		<div class="form-check-inline">
																			<label class="form-check-label">
																				<input type="radio" class="form-check-input" value="naos" id="optProyecto" name="optProyecto">NAOS
																			</label>
																		</div>
																		<div class="form-check-inline">
																			<label class="form-check-label">
																				<input type="radio" class="form-check-input" value="marabi" id="optProyecto" name="optProyecto">MARABI
																			</label>
																		</div>
																		<div class="form-check-inline ">
																			<label class="form-check-label">
																				<input type="radio" class="form-check-input" value="ambos" id="optProyecto" name="optProyecto" checked>AMBOS
																			</label>
																		</div>
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="text-align:left;margin-bottom:10px;">
																		<input class=" search form-control" type="" id="datoBuscar" name="datoBuscar" placeholder="Nombre o correo">	
																	</div>
																	
																	
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="text-align:right;margin-bottom:10px;">
																	<button onclick="agregarCliente()" class="btn btn-xs" type="button"><img class="addclient" src="../img/more.gif" alt="agregar cliente" ></button>
																	<span class="addclienttext" >Agregar Cliente</span>
																</div>
															</div>	
														</div>	
													</div>		
													<div class="row">	
														<div class="col-1 col-md-1" style="margin-bottom:10px;">
														</div>
														<div class="col-11 col-md-11" style="margin-bottom:10px;">
															<div class="row">
																<div class="col-lg-1 col-md-1 col-xs-10" style="margin-bottom:10px;">
																	<label class="">Desde: </label>	
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<input class="form-control" type="date" id="fechaInicial" value=""  >															
																</div>
																<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">															
																</div>
																<div class="col-lg-1 col-md-1 col-xs-10" style="margin-bottom:10px;">
																	<label class="">Hasta: </label>
																</div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<input class="form-control" type="date" id="fechaFinal" value=""  >
																	
																</div>
																<div class="col-lg-1 col-md-1 col-xs-10" style="margin-bottom:10px;">
																	<button onclick="buscarCliente()" class="searchf" type="button">Buscar</button>															
																</div>
															</div>	
														</div>
														<div class="col-1 col-md-1" style="text-align:center;margin-bottom:10px;">
														</div>	
													</div>
												</form>		
												<div id="contenedor" class="row" style="height:60%; overflow-y: auto;overflow-x: hidden">	
													<div class="col-5 col-md-5" style="margin-bottom:10px;">
														<div class="row">
															<Label class="results">Resultados</label>
															<div class="table-responsive">
																<table id="resultadoCliente" class="table table-sm table-hover"  style="width:100%">
																	<tr>
																		<th style="width:60%;">Cliente</th>
																		<th style="width:20%;">Cotizaciones</th> 
																		<th style="width:20%;">Proyecto</th>
																	</tr>
																</table>
															</div>
														</div>	
													</div>
													<div id="divLinea" class=" col-1 col-md-1" style="text-align:center;margin-bottom:10px;">
														<div class="line"></div>
													</div>
													<div class="col-6 col-md-6" style="margin-bottom:10px;">
														<div class="row">
															<div class="col-12 col-md-12" style="margin-bottom:10px;">
																<input id="nombreCliente" class="usernametittle" placeholder="" >
															</div>
															<div class="secinfo">
															<!-- sección de información cliente -->
																<div class="row" >
																	<input type="hidden" id="idInfo" name="idInfo">
																	<input type="hidden" id="proyectoInfo" name="proyectoInfo">
																	<input type="hidden" id="idOcaInfo" name="idOcaInfo">
																	<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;;margin-top:10px;">
																		<h3 style="padding-left:0px;" class="titleinf"><img class="usericon" src="../img/client_icon.png" alt="Cliente"> Información del Cliente <button onclick="editarCliente()" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="agregar cliente" ></button></h3>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="usernametittleinfo">Nombre del Cliente: </label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="nombreClienteInfo" class="form-control" value="" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="emailtittle">Correo  electronico: </label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="emailClienteInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="phonetittle">Teléfono: </label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="telefonoClienteInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="dpitittle">DPI: </label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="dpiClienteInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="vencimientodpitittle">Vencimiento DPI: </label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="vencimientoDpiClienteInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="adresstittle">Dirección: </label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="direccionClienteInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="text-align:center;margin-bottom:10px;">
																		<button onclick="crearEnganche()" class="engancheb" type="button">Realizar enganche</button>															
																	</div>
																</div>
															</div>
															<div class="secinfo">
        														<!-- sección de información apartamento -->
																<div class="row" >
																	<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;margin-top:10px;">
																		<h3 style="" class="infoventtittle"><img class="saleicon" src="../img/apartment_info.png" alt="Cliente"> Información de apartamento</h3>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="niveltittle">Nivel:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="nivelInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="aptotittle">Apartamento:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="apartamentoInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="sizetittle">Tamaño en mt2:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="tamanioInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="habtittle">Habitaciones:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="habitacionInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="areabodetittle">Área de bodega en mt2:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="areaBodegaInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="parqmototittle">Parqueo de moto:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="parqueoMotoInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="parqcarrotittle">Parqueo de carro:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="parqueoCarroInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="areajardintittle">Área de Jardin en mt2:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="areaJardinInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="preciototaltittle">Precio total (GtTQ):</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="precioTotalInfo" class="form-control" readonly>
																	</div>
																</div>
																
															</div>
															<div class="secinfo">
															<!-- sección de información cliente -->
																<div class="row" >
																	<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;;margin-top:10px;">
																		<h3 style="padding-left:0px;" class="infsaletittle"><img class="saleicon" src="../img/sale_info.png" alt="Cliente"> Información de venta</h3>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="descperceptittle">Descuento porcentual %: </label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="descuentoInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="engagetittle">Enganche: </label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="engancheInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="engagepaymentstittle">Pagos de enganche: </label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="pagosEngancheInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="plazofin">Plazo de financimiento: </label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="plazoFinanciamientoInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="parqueoextra">Parqueo extra:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="parqueoClienteInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="bodegaextra">Bodegas extra:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="bodegaClienteInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="montoreservatittle">Monto reserva:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="montoReservaClienteInfo" class="form-control" readonly>
																	</div>
																</div>
																
															</div>
															<div class="secinfo">
															<!-- sección de información cliente -->
																<div class="row" >
																	<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;margin-top:10px;">
																		<h3 style="padding-left:0px;" class="infovendtittle"><img class="iconselltittle" src="../img/client_icon.png" alt="Cliente"> Información del vendedor</h3>
																	</div>																	
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="sellernametittle">Nombre del vendedor:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="vendedorInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="sellermailtittle">Correo electronico:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="emailVendedorInfo" class="form-control" readonly>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="sellerphonetittle">Teléfono:</label>
																	</div>
																	<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																		<input id="telefonoVendedorInfo" class="form-control" readonly>
																	</div>
																</div>
																
															</div>

														</div>	
													</div>
													
													<div class="col-8 col-md-8" style="margin-bottom:10px;">
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
					<div class="modal fade" id="modalAgregarCliente">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Agregar Cliente</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarCliente" style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarCliente" name="frmAgregarCliente" method="POST">
											<div class="row" >
												<input type="hidden" id="idCliente" name="idCliente">
												<input type="hidden" id="proyectoCliente" name="proyectoCliente">
												<input type="hidden" id="idOcaCliente" name="idOcaCliente">																
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<label class="nodpitext">Cliente:</label>
													<div class="row" >
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="primerNombre" name="primerNombre" placeHolder="Primer Nombre" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="segundoNombre" name="segundoNombre" placeHolder="Segundo Nombre" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="primerApellido" name="primerApellido" placeHolder="Primer apellido" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="segundoApellido" name="segundoApellido" placeHolder="Segundo Apellido" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="apellidoCasada" name="apellidoCasada" placeHolder="Apellido Casada" class="form-control" >
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Correo electrónico:</label>
															<input type="text" id="correo" name="correo" class="form-control" >
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Télefono:</label>
															<input  type="text" id="telefono" name="telefono" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Dirección:</label>
															<textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
														</div>
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
														<label class="nodpitext">Número de DPI:</label>
														<input type="text" id="numeroDpi" name="numeroDpi" class="form-control">
													</div>
													<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
														<label class="nodpitext">Fecha de vencimiento DPI:</label>
														<input id="fechaVencimientoDpi" name="fechaVencimientoDpi" type="date" class="form-control">
														<input id="fechaHoy" name="fechaHoy" type="hidden" class="form-control" value="<?php echo date("d/m/Y") ?>">
													</div>
													<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
														<label class="draganddroptexttitle" for="mail">DPI y Recibo de servicios:</label>
														<input class="draganddrop" type="file" id="imgDpi" name="imgDpi" placeholder="Arrastra y suelta aquí " accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps">
													</div>
													<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
														<br><br><button onclick="guardarCliente()" class="guardar" type="button">Guardar</button>
													</div>
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
					<!-- /.modal -->
				</div>
			</div>
		</div>
			<script type="text/javascript">
				function buscarCliente(){
					console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarCliente"));
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
								console.log(e.user_name);
								if(e.creado=='si'){
									var check='<i class="fa fa-check-square-o"></i>';
								}else
								{
									var check="";
								}
								output += '<tr onCLick="buscarClienteUnico(\''+e.id+'\',\''+e.proyecto+'\')"><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
							});
							//console.log(output);
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
				function buscarClienteUnico(idCotizacion,proyecto){
					console.log("funcion buscar cliente unico");
					//var formData = new FormData(document.getElementById("frmBuscarCliente"));
					var formData = new FormData;
					formData.append("idCotizacion", idCotizacion);
					formData.append("proyectoCliente", proyecto);
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
							$.each(response.info,function(i,e) {
								console.log(e.client_name);
								$("#nombreClienteInfo").val(e.client_name);
								$("#emailClienteInfo").val(e.client_mail);
								$("#vendedorInfo").val(e.user_name);
								$("#emailVendedorInfo").val(e.email);
								$("#telefonoVendedorInfo").val(e.phone);
								$("#nivelInfo").val(e.level);
								$("#apartamentoInfo").val(e.codification);
								$("#tamanioInfo").val(e.sqmts);
								$("#habitacionInfo").val(e.rooms);
								$("#areaBodegaInfo").val(e.warehouse_mts);
								$("#parqueoMotoInfo").val(e.bike_parking);
								$("#parqueoCarroInfo").val(e.car_parking);
								$("#areaJardinInfo").val(e.garden_mts);
								$("#precioTotalInfo").val('Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.price));
								$("#proyectoInfo").val(proyecto);
								$("#idInfo").val(idCotizacion);
								$("#idOcaInfo").val(e.idCliente);
								$("#telefonoClienteInfo").val(e.telefono);
								$("#dpiClienteInfo").val(e.numeroDpi);
								$("#vencimientoDpiClienteInfo").val(e.fechaVencimientoDpi);
								$("#direccionClienteInfo").val(e.direccion);
							});
							
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function guardarCliente(){
					console.log("funcion guardar cliente");
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
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
						//console.log('CUI: '+ cui);
						var cuiRegExp = /^[0-9]{4}\s?[0-9]{5}\s?[0-9]{4}$/;

						if (!cuiRegExp.test(cui)) {
							console.log("CUI con formato inválido");
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
						// console.log('depto: '+depto);
						// console.log('muni: '+muni);
						// console.log('numero: '+numero);
						// console.log('verificador: '+verificador);
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
							console.log("CUI con código de municipio o departamento inválido.");
							//callback("CUI con código de municipio o departamento inválido.");
							error++;
							msjError =msjError+ '*Número de DPI con código de municipio o departamento inválido. <br>';
							//return false;
						}

						if (depto > munisPorDepto.length)
						{
							console.log("CUI con código de departamento inválido.");
							//callback("CUI con código de departamento inválido.");
							error++;
							msjError =msjError+ '*Número de DPI con código de departamento inválido. <br>';
							//return false;
						}

						if (muni > munisPorDepto[depto -1])
						{
							console.log("CUI con código de municipio inválido.");
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

						//console.log("CUI con módulo: " + modulo);

						if (/\s/.test(cui) || cui.includes("-")) {
							//callback('No se aceptan espacios ni guiones.');
							error++;
							msjError =msjError+ '*Número de DPI No se aceptan espacios ni guiones. <br>';
						}
					}if($("#fechaVencimientoDpi").val()==''){
						error++;
						msjError =msjError+ '*Fecha de Vencimiento DPI <br>'
					}else{
						
						var fecha_mayor = $("#fechaVencimientoDpi").val();
						var partes_fecha_mayor = fecha_mayor.split('/');
						var fecha_mayor_numero= new Date (partes_fecha_mayor[2]+'/'+partes_fecha_mayor[1]+'/'+partes_fecha_mayor[0]).setHours(0,0,0,0);
						console.log('fecha mayor' +fecha_mayor_numero);

						var fecha_menor = $("#fechaHoy").val();
						var partes_fecha_menor = fecha_menor.split('/');
						var fecha_menor_numero= new Date (partes_fecha_menor[2]+'/'+partes_fecha_menor[1]+'/'+partes_fecha_menor[0]).setHours(0,0,0,0);
						console.log('fecha menor '+fecha_menor_numero);
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
								$("#divAlertCliente").html('<div class="alert alert-success">'+response.mss+'</div>');
								setTimeout(function(){
									$("#modalAgregarCliente").modal("hide");
									$("#divAlertCliente").html('');
								},1000)
								
								buscarCliente();
								buscarClienteUnico(response.id,response.proyecto);						
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$("#divAlertCliente").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertCliente").html('');
							},5000)
					}
					
				}
				function agregarCliente(){
					document.getElementById("frmAgregarCliente").reset();
					$("#modalAgregarCliente").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarCliente"));
					$("#proyectoCliente").val("");
					$("#idCliente").val(0);
					$("#idOcaCliente").val(0);
					
				}
				function editarCliente(){
					var nombreCompleto=$("#nombreClienteInfo").val()
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
					console.log("funcion editar cliente");
					
				}
			</script>
    </body>
</html>

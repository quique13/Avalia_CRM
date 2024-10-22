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
                							<label class="apartamentosearchitittle"><img class="usersearchicon" src=""> Busqueda de Estado de Cuenta</label>				
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
																		<th style="width:10%;">Código</th>
																		<th style="width:40%;">Cliente</th>
																		<th style="width:2%;">Proyecto</th>
																		<th style="width:10%;text-align:center" >Apartamento</th>
																		<th title ="Se basa en la cantidad de dinero que debería de pagar a la fecha de hoy" style="width:2%;text-align:center" >Cuotas atrasadas</th>
																		<th title="atrasado de 1-30 días color amarrillo, mayor a 30 días de atraso color rojo, al día en sus pagos color verde" style="width:2%;">Status</th> 
																		
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
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Estado de cuenta</h5>
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
												<input type="hidden" id="idOcaInfo" name="idOcaInfo">
												<input type="hidden" id="nombreClienteInfo" name="nombreClienteInfo">

												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarCliente" name="frmAgregarCliente" method="POST">
														<div class="row" >
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:right;">
																<label id="pendPago" name="pendPago"></label>
																<input type="color" id="colorAlerta" name="colorAlerta" value="" disabled="disabled">
															</div>
															<div id="divAlertEnganche" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																<div class="table-responsive">
																	<table id="resultadoEncabezado" class="table table-sm table-hover"  style="width:100%">
																	</table>
																</div>
															</div>
															<div class="col-lg-10 col-md-10 col-xs-10" style="margin-bottom:10px;">
															</div>
															<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																<input type="hidden" id="idEngancheEstadoCuenta" name="idEngancheEstadoCuenta">
																<button onclick="estadoCuentaPdf()" class="guardar" title="Generar PDF de estado de cuenta" type="button">Generar</button>
																<button onclick="enviarEstadoCuenta()" class="btn btn-xs" type="button" <?php echo $disabledGuardar ?>><i class="fa fa-envelope" aria-hidden="true"></i></button>
															</div>
															<ul class="nav nav-tabs" role="tablist">
																<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#estadoCuentaCliente">Estado de Cuenta Cliente</a></li>
																<li class="nav-item"><a class="nav-link " data-toggle="tab" href="#estadoCuentaComision">Estado de Cuenta Comisiones</a></li>
															</ul>
															<div class="col-lg-12 col-md-12 col-xs-12 tab-content" id="renderDatos" name="renderDatos">
																<div id="estadoCuentaCliente" class="container tab-pane active" >
																	<div class="table-responsive">
																		<table id="resultadoCuotas" class="table table-sm table-hover"  style="width:100%">
																			<tr>
																				<th style="width:8%;">No.</th>
																				<th style="width:11%;" title="Monto acordado en el documento reserva">Cuota</th>
																				<th style="width:11%;" title="Monto a pagar que se calcula con el acumulado de saldo pendiente divido los pagos restantes">Pago</th>
																				<th style="width:10%;">Fecha</th>
																				<th style="width:10%;">Pagado</th>
																				<th style="width:10%;">Fecha Pagado</th>
																				<th style="width:15%;">Saldo</th>
																				<th style="width:15%;">Opciones</th>
																			</tr>
																		</table>
																		<table id="resultadoCuotasCobroExtra" class="table table-sm table-hover"  style="width:100%">
																		</table>
																		<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																			<button onclick="agregarPagoExtraEng()" class="inf" title="Agregar pago extra Enganche" type="button">Pago Extra Enganche</button>
																			<!-- <button onclick="agregarPagoExtra()" class="guardar" title="Agregar pago extra" type="button">Pago Extra</button> -->
																		</div>
																		
																	</div>
																</div>
																<div id="estadoCuentaComision" class="container tab-pane " style="display: none;">
																	<div class="table-responsive">
																		<table id="resultadoCuotasComision" class="table table-sm table-hover"  style="width:100%">
																			<tr>
																				<th colspan="9" style="width:100%;">Comisiones Vendedor</th>	
																			</tr>	
																			<tr>
																				<th style="width:15%;">Pago No.</th>
																				<th style="width:10%;">Monto</th>
																				<th style="width:10%;">Estado</th>
																				<th style="width:10%;">Pagado</th>
																				<th style="width:10%;">No. Cheque</th>
																				<th style="width:15%;">Banco</th>
																				<th style="width:10%;">Fecha Pagado</th>
																				<th style="width:10%;">Saldo</th>
																				<th style="width:10%;">Opc.</th>
																			</tr>
																		</table>
																		<table id="resultadoCuotasComisionDir" class="table table-sm table-hover"  style="width:100%">
																			<tr>
																				<th colspan="9" style="width:100%;">Comisiones Director de Ventas</th>
																				
																			</tr>
																			<tr>
																				<th style="width:15%;">Pago No.</th>
																				<th style="width:10%;">Monto</th>
																				<th style="width:10%;">Estado</th>
																				<th style="width:10%;">Pagado</th>
																				<th style="width:10%;">No. Cheque</th>
																				<th style="width:15%;">Banco</th>
																				<th style="width:10%;">Fecha Pagado</th>
																				<th style="width:10%;">Saldo</th>
																				<th style="width:10%;">Opc.</th>
																			</tr>

																		</table>
																	</div>
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
					<!-- /.modal -->
					<div class="modal fade" id="modalAgregarPago"  >
						<div class="modal-dialog mw-100 w-75" style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content">
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Agregar Pago</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarPago"  style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarFormalizarPago" name="frmAgregarFormalizarPago" method="POST">
											<div class="row" >
												<input type="hidden" id="idPago" name="idPago">

												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarPago" name="frmAgregarPago" method="POST">
													<div class="row" >
															<div id="divAlertPago" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Tipo de pago:</label>
																<select class="form-control" name="tipoPago" id="tipoPago"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="Deposito" >Deposito Directo</optinon>
																	<option value="Cheque" >Cheque</optinon>
																	<option value="Transferencia" >Transferencia Bancaría</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Monto:</label>
																<input type="text" id="montoPago" name="montoPago" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco depósito:</label>
																<select class="form-control" name="bancoDeposito" id="bancoDeposito"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>
																	<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>
																	<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>
																	<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>
																	<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>
																	<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco Cheque/Transferencia:</label>
																<select class="form-control" name="bancoCheque" id="bancoCheque"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>
																	<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>
																	<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>
																	<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>
																	<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>
																	<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>
																	<option value="NO APLICA" >NO APLICA</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Deposito/ Cheque/ Transferencia:</label>
																<input type="text" id="noDeposito" name="noDeposito" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Fecha pago:</label>
																<input type="date" id="fechaPago" name="fechaPago" max="<?php echo date("Y-m-d") ?>" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Recibo:</label>
																<input type="text" id="noRecibo" name="noRecibo" class="form-control">
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Observaciones:</label>
																<textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
															</div>
															<!-- <div id="divCalculoCuota" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;text-align:right;display:none" >
																<br><br><button onclick="calculoCuotas()" class="cuotas" type="button">Calcular Cuotas</button>
															</div> -->
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;" id="divBotones" name="divBotones">
																<br><br><button onclick="guardarPago()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
																<button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Agregar Adjuntos</button>
																<button onclick="validarPago()" class="guardar" type="button" id="btnValidar" <?php echo $disabledValidar ?>>Validar</button>
															</div>
															
															<script type="text/javascript">
																// $("#montoPago").number( true, 2 );
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
						<!-- MODAL DOCUMENTOS ADJUNTOS -->
						<?php require_once("./documentos_adjuntos.php"); ?>
					</div>

					<div class="modal fade" id="modalAgregarPagoExtra"  >
						<div class="modal-dialog mw-100 w-75" style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content">
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Agregar Pago Extra</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarPagoExtra"  style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarFormalizarPagoExtra" name="frmAgregarFormalizarPagoExtra" method="POST">
											<div class="row" >
												<input type="hidden" id="idPagoExtra" name="idPagoExtra">

												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarPagoExtra" name="frmAgregarPagoExtra" method="POST">
													<div class="row" >
															<div id="divAlertPago" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Tipo de cobro:</label>
																<select class="form-control" name="tipoCobroExtra" id="tipoCobroExtra"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="cocina" >Cocina</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Tipo de pago:</label>
																<select class="form-control" name="tipoPagoExtra" id="tipoPagoExtra"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="Deposito" >Deposito Directo</optinon>
																	<option value="Cheque" >Cheque</optinon>
																	<option value="Transferencia" >Transferencia Bancaría</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Monto:</label>
																<input type="text" id="montoPagoExtra" name="montoPagoExtra" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco depósito:</label>
																<select class="form-control" name="bancoDepositoExtra" id="bancoDepositoExtra"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>
																	<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>
																	<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>
																	<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>
																	<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>
																	<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco Cheque/Transferencia:</label>
																<select class="form-control" name="bancoChequeExtra" id="bancoChequeExtra"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>
																	<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>
																	<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>
																	<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>
																	<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>
																	<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>
																	<option value="NO APLICA" >NO APLICA</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Deposito/ Cheque/ Transferencia:</label>
																<input type="text" id="noDepositoExtra" name="noDepositoExtra" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Fecha pago:</label>
																<input type="date" id="fechaPagoExtra" name="fechaPagoExtra" max="<?php echo date("Y-m-d") ?>" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Recibo:</label>
																<input type="text" id="noReciboExtra" name="noReciboExtra" class="form-control">
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Observaciones:</label>
																<textarea class="form-control" id="observacionesExtra" name="observacionesExtra" rows="2"></textarea>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;" id="divBotones" name="divBotones">
																<br><br><button onclick="guardarPagoExtra()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
																<button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Agregar Adjuntos</button>
															<!--	<button onclick="validarPagoExtra()" class="guardar" type="button" id="btnValidar">Validar</button> -->
															</div>
															
															<script type="text/javascript">
																// $("#montoPago").number( true, 2 );
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
						<!-- MODAL DOCUMENTOS ADJUNTOS -->
						<?php require_once("./documentos_adjuntos.php"); ?>
					</div>
					<div class="modal fade" id="modalAgregarPagoExtraEng"  >
						<div class="modal-dialog mw-100 w-75" style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content">
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Agregar Pago Extra</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarPagoExtraEng"  style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarFormalizarPagoExtraEng" name="frmAgregarFormalizarPagoExtraEng" method="POST">
											<div class="row" >
												<input type="hidden" id="idPagoExtraEng" name="idPagoExtraEng">

												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarPagoExtraEng" name="frmAgregarPagoExtraEng" method="POST">
													<div class="row" >
															<div id="divAlertPago" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Tipo de cobro:</label>
																<select class="form-control" name="tipoCobroExtraEng" id="tipoCobroExtraEng"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="enganche" >Enganche</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Tipo de pago:</label>
																<select class="form-control" name="tipoPagoExtraEng" id="tipoPagoExtraEng"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="Deposito" >Deposito Directo</optinon>
																	<option value="Cheque" >Cheque</optinon>
																	<option value="Transferencia" >Transferencia Bancaría</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Monto:</label>
																<input type="text" id="montoPagoExtraEng" name="montoPagoExtraEng" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco depósito:</label>
																<select class="form-control" name="bancoDepositoExtraEng" id="bancoDepositoExtraEng"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>
																	<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>
																	<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>
																	<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>
																	<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>
																	<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco Cheque/Transferencia:</label>
																<select class="form-control" name="bancoChequeExtraEng" id="bancoChequeExtraEng"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>
																	<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>
																	<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>
																	<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>
																	<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>
																	<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>
																	<option value="NO APLICA" >NO APLICA</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Deposito/ Cheque/ Transferencia:</label>
																<input type="text" id="noDepositoExtraEng" name="noDepositoExtraEng" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Fecha pago:</label>
																<input type="date" id="fechaPagoExtraEng" name="fechaPagoExtraEng" max="<?php echo date("Y-m-d") ?>" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Recibo:</label>
																<input type="text" id="noReciboExtraEng" name="noReciboExtraEng" class="form-control">
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Observaciones:</label>
																<textarea class="form-control" id="observacionesExtraEng" name="observacionesExtraEng" rows="2"></textarea>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;" id="divBotones" name="divBotones">
																<br><br><button onclick="guardarPagoExtraEng()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
																<button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Agregar Adjuntos</button>
																<button onclick="validarPagoExtraEng()" class="guardar" type="button" id="btnValidarExtraEng" <?php echo $disabledValidar; ?>>Validar</button>
															</div>
															
															<script type="text/javascript">
																// $("#montoPago").number( true, 2 );
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
						<!-- MODAL DOCUMENTOS ADJUNTOS -->
						<?php require_once("./documentos_adjuntos.php"); ?>
					</div>
					
					<div class="modal fade" id="modalAgregarPagoComision"  >
						<div class="modal-dialog mw-100 w-75" style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content">
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Agregar Pago</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarPagoComision"  style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarFormalizarPagoComision" name="frmAgregarFormalizarPagoComision" method="POST">
											<div class="row" >
												<input type="hidden" id="apartamentoPagoComision" name="apartamentoPagoComision">
												<input type="hidden" id="idFormaPagoComision" name="idFormaPagoComision">

												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												<div style="text-align: right;">
													<button onclick="nuevoPagoComision()" class="agregarPagoComision" type="button" <?php echo $disabledGuardar ?>>Agregar Nuevo Pago</button>
												</div>
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarPagoComision" name="frmAgregarPagoComision" method="POST">
														<ul id="ulPagosComision" name="ulPagosComision" class="nav nav-tabs" role="tablist">
															<li class="nav-item">
																<a class="nav-link active" data-toggle="tab" href="#pago_1">Pago 1</a>
															</li>
														</ul>
														<div class="tab-content" id="renderDatosComision" name="renderDatosComision">
															<div class="container tab-pane active" id="pago_1" >
																<div id="divAlertPago" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																</div>
																<input type="hidden" id="idPagoComision_1" name="idPagoComision_1">
																<div class="row" >
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																		<label class="nodpitext">Monto:</label>
																		<input type="text" id="montoPago_1" name="montoPago_1" class="form-control">
																	</div>
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																		<label class="nodpitext">Banco Cheque:</label>
																		<select class="form-control" name="bancoCheque_1" id="bancoCheque_1"  onchange="">
																			<option value="" >SELECCIONE</optinon>
																			<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>
																			<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>
																			<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>
																			<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>
																			<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>
																			<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>
																			<option value="NO APLICA" >NO APLICA</optinon>
																		</select>
																	</div>
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																		<label class="nodpitext">No. Cheque:</label>
																		<input type="text" id="noDeposito_1" name="noDeposito_1" class="form-control">
																	</div>
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																		<label class="nodpitext">Fecha pago:</label>
																		<input type="date" id="fechaPago_1" name="fechaPago_1" max="<?php echo date("Y-m-d") ?>" class="form-control">
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																		<label class="nodpitext">Observaciones:</label>
																		<textarea class="form-control" id="observaciones_1" name="observaciones_1" rows="2"></textarea>
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;" id="divBotones" name="divBotones">
																		<br><br><button onclick="guardarPagoComision(1)" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
																		<button disabled onclick="validarPagoComision()" class="guardar" type="button" id="btnValidar_1" <?php echo $disabledValidar ?>>Pendiente</button>
																	</div>
																	
																	<script type="text/javascript">
																		$("#montoPago_1").number( true, 2 );
																	</script>
																</div>
															</div>
														</div>
														
													</form>
												</div>
												<div id="divAlertClienteComision" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
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
					<div class="modal fade" id="modalAgregarPagoF"  >
						<div class="modal-dialog mw-100 w-75" style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content">
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Agregar Pago Final</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarPagoF"  style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarFormalizarPagoF" name="frmAgregarFormalizarPagoF" method="POST">
											<div class="row" >
												<input type="hidden" id="idEngancheF" name="idEngancheF">

												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarPagoF" name="frmAgregarPagoF" method="POST">
													<div class="row" >
															<div id="divAlertPagoF" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Tipo de pago:</label>
																<select class="form-control" name="tipoPagoF" id="tipoPagoF"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="Deposito" >Deposito Directo</optinon>
																	<option value="Cheque" >Cheque</optinon>
																	<option value="Transferencia" >Transferencia Bancaría</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Monto:</label>
																<input type="text" id="montoPagoF" name="montoPagoF" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco depósito:</label>
																<select class="form-control" name="bancoDepositoF" id="bancoDepositoF"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>
																	<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>
																	<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>
																	<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>
																	<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>
																	<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco Desembolso:</label>
																<select class="form-control" name="bancoChequeF" id="bancoChequeF"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>
																	<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>
																	<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>
																	<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>
																	<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>
																	<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>
																	<option value="NO APLICA" >NO APLICA</optinon>
																</select>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Deposito/ Cheque/ Transferencia:</label>
																<input type="text" id="noDepositoF" name="noDepositoF" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Fecha pago:</label>
																<input type="date" id="fechaPagoF" name="fechaPagoF" class="form-control">
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Forma Desembolso:</label>
																<select class="form-control" name="tipoDesembolsoF" id="tipoDesembolsoF"  onchange="">
																	<option value="" >SELECCIONE</optinon>
																	<option value="parcial" >Parcial</optinon>
																	<option value="total" >Total</optinon>
																</select>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Observaciones:</label>
																<textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
																<br><br><button onclick="guardarPagoF()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
															</div>
															<script type="text/javascript">
																$("#montoPago").number( true, 2 );
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
					<div class="modal fade" id="modalAgregarContra"  >
						<div class="modal-dialog mw-100 w-75" style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content">
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Agregar Contracargo</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarContra"  style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarFormalizarContra" name="frmAgregarFormalizarContra" method="POST">
											<div class="row" >
												<input type="hidden" id="idEngancheContra" name="idEngancheContra">
												<input type="hidden" id="idContra" name="idContra">

												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div id="divAlertContra" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Acción:</label>
															<select class="form-control" name="accionContra" id="accionContra"  onchange="">
																<option value="" >SELECCIONE</optinon>
																<option value="adicionar" >Adicionar</optinon>
																<option value="descontar" >Rebajar</optinon>
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Monto:</label>
															<input type="text" id="montoContra" name="montoContra" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Observaciones:</label>
															<textarea class="form-control" id="observacionesContra" name="observacionesContra" rows="2"></textarea>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
															<br><br><button onclick="guardarContra()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
														</div>
														<script type="text/javascript">
															$("#montoContra").number( true, 2 );
														</script>
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
				</div>
			</div>
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
				function buscarApartamentoEstadoCuenta(){
					//console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarApartamento"));
					formData.append("proyectoBscTxt", document.getElementById("proyectoBsc").options[document.getElementById("proyectoBsc").selectedIndex].text);
					formData.append("torreBscTxt", document.getElementById("torreBsc").options[document.getElementById("torreBsc").selectedIndex].text);
					formData.append("nivelBscTxt", document.getElementById("nivelBsc").options[document.getElementById("nivelBsc").selectedIndex].text);
					formData.append("apartamentoBscTxt", document.getElementById("apartamentoBsc").options[document.getElementById("apartamentoBsc").selectedIndex].text);
					$.ajax({
						url: "./cliente.php?get_apartamento_lista_estado_cuenta=true",
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
								var color;
								var pagosAtrasados = 0;
								var pendPago = 0;
								var title ='';
								var count =0;
								var montoCuota = 0;
								var cuota = 0;
								var division=0;
								var resta = 0;
								if(parseFloat(e.pagosAtrasadosReales) < 1){
								color = '#04FA13';
								colorReal = '#04FA13';
								}else if(e.pagosAtrasadosReales> 0 && e.pagosAtrasadosReales <= 1){
									color = '#FAF304';
									colorReal = '#FAF304';
								}else if(e.pagosAtrasadosReales >1){
									color = '#FA0404';
									colorReal = '#FA0404'
								}	
								
																	
								var colorCodigo='';
								if(e.estado ==0){
									colorCodigo = '#FA0404';
								}else{
									colorCodigo='';
								}
								output += '<tr onCLick=""><td style="background-color:'+colorCodigo+'">'+e.codigo+' </td><td>'+e.client_name+' </td><td>'+e.proyecto+' </td><td align="center">'+e.apartamento+'</td><td align="center" title="'+title+'">'+e.pagosAtrasadosReales+' </td><td> <input type="color" value="'+color+'" disabled="disabled"></td><td><button onclick="getEngancheDetalle(\''+e.id+'\')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ></td></tr>';
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
					var id_perfil = $("#id_perfil").val();
					var id_usuario = $("#id_usuario").val();
					var formData = new FormData;
					formData.append("idEnganche", idEnganche);
					
					$.ajax({
						url: "./cliente.php?get_progra_detalle_estado=true",
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
							$("#idEngancheEstadoCuenta").val(idEnganche);
							$("#idOcaInfo").val(response.idCliente);
							$("#nombreClienteInfo").val(response.nombreCliente);
							var output='';
							var outputExtra='';
							var outputE='';
							var select='';
							var montoEngancheTotal=0;
							var count = 0;
							var block;
							var outputCom='';
							var outputComDir='';
							var precioVentaSinImpuesto = parseFloat(response.precioComision);
							var totalComision=0;
							var pagosAtrasados=0;
							var noPagosEnganche = parseInt(response.pagosEnganche);
							console.log(noPagosEnganche +" pagos enganche");
							if(id_perfil == 1){
								block='disabled="disabled"';
							}
							if( id_perfil == 3 || id_usuario == 11 || id_usuario == 10){
								document.getElementById("estadoCuentaComision").style.display = "";
							}else{
								document.getElementById("estadoCuentaComision").style.display = "";
							}
							//Tabla Encabezado
							
							//FIN
							$("#colorAlerta").val('#04FA13');
							if(parseFloat(response.diasPago)<=0){
								$("#colorAlerta").val('#04FA13');
							}else{
								if(parseFloat(response.totalPagado)>=parseFloat(response.debePagar_2)){
									$("#colorAlerta").val( '#04FA13');
								}else{
									if(parseFloat(response.cuotasSinEspecial) >0 && parseFloat(response.cuotasSinEspecial)<parseFloat(response.cuotas)){
										pagosAtrasados = parseFloat(parseFloat(response.debePagarSinEspecial) - parseFloat(response.totalPagado)).toFixed(2);
										pagosAtrasados = Math.ceil(pagosAtrasados/parseFloat(response.cuotasSinEspecial));
									}else{
										pagosAtrasados = parseFloat(parseFloat(response.debePagar_2) - parseFloat(response.totalPagado)).toFixed(2);
										pagosAtrasados = Math.ceil(pagosAtrasados/parseFloat(response.cuotas));
									}
									
									if(pagosAtrasados<0){
										pagosAtrasados = 0;
									}
									if(pagosAtrasados <= 0){
										$("#colorAlerta").val('#04FA13');
									}else if(pagosAtrasados> 0 && pagosAtrasados <=1){
										$("#colorAlerta").val( '#FAF304');
									}else if(pagosAtrasados >1){
										$("#colorAlerta").val('#FA0404');
									}
								}
							}
							// if(response.diasPago <= 0){
							// 	$("#colorAlerta").val('#04FA13');
							// }else if(response.diasPago > 0 && response.diasPago <30){
							// 	$("#colorAlerta").val('#FAF304');
							// }else if(response.diasPago >=30){
							// 	$("#colorAlerta").val('#FA0404');
							// }
							if(pagosAtrasados<=0){
								var montoPendiente = 0;
							}else{
								var montoPendiente = response.pagoPendiente_2
							}
							var saldo = parseFloat(response.enganchePorcMonto)- parseFloat(response.reserva)
							output+="<tr>";
							output+="<td >Reserva</td>";
							output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(response.reserva)+"</td>";
							output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(response.reserva)+"</td>";
							output+="<td>"+response.fechaPagoReservaFormat+"</td>";
							output+='<td><i class="fa fa-check-square-o"></i> Pagado</td>';
							output+="<td>"+response.fechaPagoReservaFormat+"</td>";
							output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(saldo)+"</td>";
							output+='<td><button onclick="" class="btn btn-xs" type="button" disabled="disabled"><img class="" src="../img/edit_button.png" alt="agregar Pago" ></button><button onclick="verReciboReserva('+idEnganche+')" class="btn btn-xs" type="button"><img class="" src="../img/Engagement.png" alt="ver Recibo" ></button><button onclick="enviarReciboReserva('+idEnganche+')" class="btn btn-xs" type="button"><i class="fa fa-envelope" aria-hidden="true"></i></button></td>';
							output+="</tr>";
							var acumulado=0;
							$.each(response.detallePagos,function(i,e) {
								var colorCodigo='';
								count++;
								montoEngancheTotal= parseFloat(e.montoEnganche);
								if(e.pagado==1){
									if(e.validado==1){
										colorCodigo = '';
									}else if(e.validado==0){
										colorCodigo = '#FA0404';
									}
									var check='<i class="fa fa-check-square-o"></i> Pagado';
									var buttonRecibo='<button onclick="verReciboPago('+e.idDetalle+')" class="btn btn-xs" type="button"><img class="" src="../img/Engagement.png" alt="ver Recibo" ></button><button onclick="enviarRecibo('+e.idDetalle+')" class="btn btn-xs" type="button"><i class="fa fa-envelope" aria-hidden="true"></i></button>';
									acumulado += parseFloat(e.monto);
								}else
								{
									var check='<i class="fa fa-times"></i> Pendiente';
									var buttonRecibo='';
								}
								checkDisabled = e.pagado==1?'disabled="disabled':'';
								
								output+="<tr >";
								output+="<td >"+e.noPago+"<input id=\"noPago_"+e.noPago+"\" name=\"noPago[]\" type=\"hidden\" value=\""+e.noPago+"\" readonly=\"readonly\" ></td>";
								output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.montoReal)+"</td>";
								output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.montoPagado)+"</td>";
								output+="<td>"+e.fechaPagoFormat+"</td>";
								output+="<td style='background-color:"+colorCodigo+"'>"+check+"</td>";
								output+="<td>"+e.fechaPagoRealizadoFormat+"</td>";
								output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoEngancheTotal - acumulado)+"</td>";
								output+='<td><button onclick="editarPago('+e.idDetalle+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="agregar Pago" ></button>'+buttonRecibo+'</td>';
								output+="</tr>";
								//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
							});
							var pagoExtraEnganche=0
							if(response.detallePagosExtraEng.length>0){
								var pagadoTotal=0
								var pagoExtraEnganche=0
								$.each(response.detallePagosExtraEng,function(i,e) {
									var colorCodigo='';
									if(e.validado==1){
										colorCodigo = '';
									}else if(e.validado==0){
										colorCodigo = '#FA0404';
									}
									pagadoTotal += parseFloat(e.montoPagado);
									count++;
									acumulado += parseFloat(e.montoPagado);
									pagoExtraEnganche  += parseFloat(e.montoPagado);
									var checkEng='<i class="fa fa-check-square-o"></i> Pagado';
									var buttonRecibo='<button onclick="verReciboPagoExtraEng('+e.idCobro+')" class="btn btn-xs" type="button"><img class="" src="../img/Engagement.png" alt="ver Recibo" ></button>';
									output+="<tr >";
									output+="<td >"+e.noPago+"<input id=\"noPago_"+e.noPago+"\" name=\"noPago[]\" type=\"hidden\" value=\""+e.noPago+"\" readonly=\"readonly\" ></td>";
									output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.montoPagado)+"</td>";
									output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.montoPagado)+"</td>";
									output+="<td>"+e.fechaFormat+"</td>";
									output+="<td style='background-color:"+colorCodigo+"'>"+checkEng+"</td>";
									output+="<td>"+e.fechaFormat+"</td>";
									output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoEngancheTotal - acumulado)+"</td>";
									output+='<td><button onclick="editarPagoExtra('+e.idCobro+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="agregar Pago" ></button>'+buttonRecibo+'</td>';
									output+="</tr>";
								});
							}else{
								console.log("No hay array")
							}
							if(response.detallePagosExtra.length>0){
								var pagadoTotal=0
								outputExtra+="<tr>";
								outputExtra+="<th style='width:25%;'>No.</th>";
								outputExtra+="<th style='width:25%;'>Monto Pagado</th>";
								outputExtra+="<th style='width:25%;'>Fecha Pagado</th>";
								outputExtra+="<th style='width:25%;'>Opciones</th>";
								outputExtra+="</tr>";
								$.each(response.detallePagosExtra,function(i,e) {
									pagadoTotal += parseFloat(e.montoPagado);
									count++;
									var buttonRecibo='<button onclick="verReciboPagoExtra('+e.idCobro+')" class="btn btn-xs" type="button"><img class="" src="../img/Engagement.png" alt="ver Recibo" ></button>';
									outputExtra+="<tr >";
									outputExtra+="<td >"+e.noPago+"<input id=\"noPago_"+e.noPago+"\" name=\"noPago[]\" type=\"hidden\" value=\""+e.noPago+"\" readonly=\"readonly\" ></td>";
									outputExtra+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.montoPagado)+"</td>";
									outputExtra+="<td>"+e.fechaFormat+"</td>";
									outputExtra+='<td><button onclick="editarPagoExtra('+e.idCobro+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="agregar Pago" ></button>'+buttonRecibo+'</td>';
									outputExtra+="</tr>";
								});
								outputExtra+="<tr>";
								outputExtra+="<th>Total Pagado</th>";
								outputExtra+="<th colspan='3'>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(pagadoTotal)+"</th>";
								outputExtra+="</tr>";
							}else{
								console.log("No hay array")
							}
							
							var montoV = 0;
							var montoD = 0;
							var saldoV = 0;
							var saldoD = 0;
							$.each(response.detalleComisiones,function(i,e) {
								if(parseFloat(e.monto)==0){
									var checkCom='<i class="fa fa-times"></i>';
								}else {
									var checkCom='<i class="fa fa-check-square-o"></i>';
								}
								
								var montoCom = ((precioVentaSinImpuesto * (parseFloat(e.porcentajeComision)/100) ) *(parseFloat(e.porcentajePago)/100))
								montoCom = montoCom.toFixed(0);
								totalComision=totalComision+ parseFloat(montoCom);
								var buttonReciboCom='';
								//montoCom=montoCom.toFixed(0);
								if(e.estado=='Cumplido'){
									var color = "background-color:#04FA13";
								}else{
									var color = "background-color:#FA0404";
								}
								if(e.descripcion=='Vendedores'){
									montoV = montoV + parseFloat(montoCom);
									saldoV = saldoV + parseFloat(e.monto);
									outputCom+="<tr>";
								outputCom+="<td >"+e.noPago+"<input id=\"noPago_"+e.idFormaPagoComisiones+"\" name=\"noPago[]\" type=\"hidden\" value=\""+e.idFormaPagoComisiones+"\" readonly=\"readonly\" ></td>";
								outputCom+="<td style=\"text-align:right\">Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoCom)+"</td>";
								outputCom+="<td style=\""+color+"\"><b>"+e.estado+"</b></td>";
								outputCom+="<td style=\"text-align:center\">"+checkCom+"</td>";
								outputCom+="<td style=\"text-align:center\">"+e.noCheque+"</td>";
								outputCom+="<td>"+e.bancoPago+"</td>";
								outputCom+="<td style=\"text-align:center\">"+e.fechaPago+"</td>";
								outputCom+="<td style=\"text-align:right\">Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoCom-parseFloat(e.monto))+"</td>";
								outputCom+='<td><button onclick="editarPagoComision('+e.idFormaPagoComisiones+',\''+response.apartamento+'\','+montoCom+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="agregar Pago" ></button>'+buttonReciboCom+'</td>';
								outputCom+="</tr>";
								//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';

								}
								else if(e.descripcion=='Director de Ventas'){
									montoD = montoD + parseFloat(montoCom);
									saldoD = saldoD + parseFloat(e.monto);
									outputComDir+="<tr>";
									outputComDir+="<td >"+e.noPago+"<input id=\"noPago_"+e.idFormaPagoComisiones+"\" name=\"noPago[]\" type=\"hidden\" value=\""+e.idFormaPagoComisiones+"\" readonly=\"readonly\" ></td>";
									outputComDir+="<td style=\"text-align:right\">Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoCom)+"</td>";
									outputComDir+="<td style=\""+color+"\"><b>"+e.estado+"</b></td>";
									outputComDir+="<td style=\"text-align:center\">"+checkCom+"</td>";
									outputComDir+="<td style=\"text-align:center\">"+e.noCheque+"</td>";
									outputComDir+="<td>"+e.bancoPago+"</td>";
									outputComDir+="<td style=\"text-align:center\">"+e.fechaPago+"</td>";
									outputComDir+="<td style=\"text-align:right\">Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoCom - parseFloat(e.monto))+"</td>";
									outputComDir+='<td><button onclick="editarPagoComision('+e.idFormaPagoComisiones+',\''+response.apartamento+'\','+montoCom+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="agregar Pago" ></button>'+buttonReciboCom+'</td>';
									outputComDir+="</tr>";
								}
								
								//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
							});
							outputCom+="<tr>";
								outputCom+="<td ><b>Sub-Total</b></td>";
								outputCom+="<td style=\"text-align:right\">Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoV)+"</td>";
								outputCom+="<td ></td>";
								outputCom+="<td style=\"text-align:center\"></td>";
								outputCom+="<td style=\"text-align:center\"></td>";
								outputCom+="<td></td>";
								outputCom+="<td style=\"text-align:center\"></td>";
								outputCom+="<td style=\"text-align:right\">Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoV-saldoV)+"</td>";
								outputCom+='<td></td>';
								outputCom+="</tr>";
								outputComDir+="<tr>";
								outputComDir+="<td ><b>Sub-Total</b></td>";
								outputComDir+="<td tyle=\"text-align:right\">Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoD)+"</td>";
								outputComDir+="<td ></td>";
								outputComDir+="<td style=\"text-align:center\"></td>";
								outputComDir+="<td style=\"text-align:center\"></td>";
								outputComDir+="<td></td>";
								outputComDir+="<td style=\"text-align:center\"></td>";
								outputComDir+="<td style=\"text-align:right\">Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoD-saldoD)+"</td>";
								outputComDir+='<td></td>';
								outputComDir+="</tr>";
								outputComDir+="<tr>";
								outputComDir+="<td ><b>Total</b></td>";
								outputComDir+="<td tyle=\"text-align:right\">Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoD+montoV)+"</td>";
								outputComDir+="<td ></td>";
								outputComDir+="<td style=\"text-align:center\"></td>";
								outputComDir+="<td style=\"text-align:center\"></td>";
								outputComDir+="<td></td>";
								outputComDir+="<td style=\"text-align:center\"></td>";
								outputComDir+="<td style=\"text-align:right\">Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format((montoV-saldoV)+(montoD-saldoD))+"</td>";
								outputComDir+='<td></td>';
								outputComDir+="</tr>";
								var pagadoResta = 0;
								if(response.enganchePorcMonto >= (acumulado+parseFloat(response.reserva))){
									pagadoResta = acumulado + parseFloat(response.reserva);
								}else{
									pagadoResta = acumulado + parseFloat(response.reserva);
								}
							var pagoFinal = parseFloat(response.contracargo) + parseFloat(response.bodega) + parseFloat(response.parqueo) + parseFloat(response.precio) - parseFloat(response.descuento)  ;
							var totalApartamento = parseFloat(pagoFinal)  + parseFloat(response.promesa)
							console.log(parseFloat(pagoFinal) +'+'+ parseFloat(response.contracargoEnganche) +'-'+ parseFloat(pagoExtraEnganche) +'-'+ pagadoResta );
							pagoFinal = parseFloat(pagoFinal) + parseFloat(response.contracargoEnganche) - pagadoResta ; 
							  
							//console.log (pagoFinal +'=' +response.bodega +'+'+ response.parqueo +'+'+ response.precio +'-'+ response.descuento +'-'+ pagadoResta)
							if(response.fechaPagoFinalFormat!=''){
									var checkF='<i class="fa fa-check-square-o"> Pagado</i>';
								}else
								{
									var checkF='<i class="fa fa-times"> Pendiente</i>';
								}
							var cuotaEnganche = parseFloat(montoEngancheTotal) / parseInt(noPagosEnganche);
							var montoEngancheReserva = parseFloat(montoEngancheTotal) + parseFloat(response.reserva);
							outputE+="<tr>";
							outputE+="<th >Código</th>";
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
							outputE+="<tr>";
							outputE+="<th >Enganche Pagado</th>";
							outputE+="<th>Porcentaje Pagado</th>";
							outputE+="<th>Pend. por Pagar Enganche</th>";
							outputE+="</tr>";
							outputE+="<tr>";
							porcentaje = 100 * ((parseFloat(acumulado)+parseFloat(response.reserva))/montoEngancheReserva)
							outputE+="<td >"+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(acumulado+parseFloat(response.reserva))+"</td>";
							outputE+="<td>"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(porcentaje.toFixed(2))+"%</td>";
							var pendPorPagar = montoEngancheTotal - acumulado < 0 ? 0 : montoEngancheTotal - acumulado; 
							outputE+="<td>"+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(pendPorPagar)+"</td>";
							outputE+="</tr>";
							outputE+="<tr>";
							outputE+="<th>Tipo Comisión</th>";
							outputE+="<th>Precio Venta sin Impuestos</th>";
							outputE+="<th>Comisión Total</th>";
							outputE+="</tr>";
							outputE+="<tr>";
							outputE+="<td>"+response.tipoComision+"</td>";
							outputE+="<td>"+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(response.precioComision)+"</td>";
							outputE+="<td>"+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(totalComision)+"</td>";
							outputE+="</tr>";
							outputE+="<tr>";
							outputE+="<tr>";
							outputE+="<th>Enganche(Reserva no incluida)</th>";
							outputE+="<th>Pagos Enganche</th>";
							outputE+="<th>Cuota Enganche</th>";
							outputE+="</tr>";
							outputE+="<tr>";
							outputE+="<td>"+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoEngancheTotal)+"</td>";
							outputE+="<td>"+noPagosEnganche+"</td>"
							outputE+="<td>"+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(cuotaEnganche)+"</td>";
							outputE+="</tr>";
							outputE+="<tr>";
							outputE+="<td style='font-size:130%;'><b>Vendedor:</b> "+response.vendedor+"</td>";
							outputE+="<td></td>";
							outputE+="<td></td>";
							outputE+="</tr>";
							outputE+="<tr>";
							output+='<tr>'
							output+='<th style="width:10%;">Descripción</th>'
							output+='<th style="width:15%;">Monto</th>'
							output+='<th colspan=\"4\" style="width:45%;">Observaciones</th>'
							output+='<th style="width:15%;">Opciones</th>'
							output+='</tr>'
							output+="<tr>";
							$("#pendPago").text(' Pendiente Por pagar a la fecha Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoPendiente));
							$.each(response.detalleContra,function(i,e) {
								if(e.accion=='sumar'){
									var signo='';
									var accion='Adicionar';
								}else
								{
									var signo='-';
									var accion='Rebajar';
								}
								output+="<tr>";
								output+="<td >"+accion+"</td>";
								output+="<td>"+signo+" Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.monto)+"</td>";
								output+="<td colspan=\"4\">"+e.observaciones+"</td>";
								output+='<td><button '+block+' onclick="editarContra('+e.idContraPago+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="agregar Pago" ></button></td>';
								output+="</tr>";
								//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
							});
							
							output+="<td >Pago Final</td>";
							output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(pagoFinal)+"</td>";
							output+="<td></td>";
							output+='<td>'+checkF+'</td>';
							output+="<td></td>";
							output+="<td>"+response.fechaPagoFinalFormat+"</td>";
							output+='<td><button onclick="editarPagoFinal('+idEnganche+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" tittle="agregar/editar Pago" ></button><button '+block+' onclick="agregarContracargo('+idEnganche+')" class="btn btn-xs" type="button"><img class="" src="../img/Add_button.png" tittle="agregar Contracargo" ></button></td>';
							output+="</tr>";
							output+="<td colspan=\"7\">"+"<h5 class=\"tittle\" >Total Apartamento "+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(totalApartamento)+"</h5></td>";
							var tb = document.getElementById('resultadoCuotas');
							while(tb.rows.length > 1) {
								tb.deleteRow(1);
							}
							var tb = document.getElementById('resultadoCuotasCobroExtra');
							while(tb.rows.length > 0) {
								tb.deleteRow(0);
							}
							var tbC = document.getElementById('resultadoCuotasComision');
							while(tbC.rows.length > 2) {
								tbC.deleteRow(2);
							}
							var tbCD = document.getElementById('resultadoCuotasComisionDir');
							while(tbCD.rows.length > 2) {
								tbCD.deleteRow(2);
							}
							// if(count==0){
							// 	calculoCuotas(idEnganche);
							// }
							$('#resultadoCuotas').append(output);
							$('#resultadoCuotasCobroExtra').append(outputExtra);
							$('#resultadoCuotasComision').append(outputCom);
							$('#resultadoCuotasComisionDir').append(outputComDir);
							var tbE = document.getElementById('resultadoEncabezado');
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
				function editarPago(idPago){
					var formData = new FormData;
					formData.append("idDetalle", idPago);
					$.ajax({
						url: "./cliente.php?get_pago_info=true",
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
							$.each(response.detallePago,function(i,e) {
								//Campos Enganche
								var button = "";
								$("#idPago").val(e.idDetalle);	
								$("#tipoPago").val(e.tipoPago);
								getBancos('bancoDeposito',e.bancoDeposito);
								getBancos('bancoCheque',e.bancoCheque);	
								$("#noDeposito").val(e.noDeposito);
								$("#observaciones").val(e.observaciones);
								$("#noCheque").val(e.noCheque);
								$("#noRecibo").val(e.noRecibo);
								$("#fechaPago").val(e.fechaPagoRealizado);
								if(e.pagado == 1){
									$("#montoPago").val(e.montoPagado);
									$('#btnValidar').show();
									if(e.validado==0){
										if($("#id_usuario").val()== 11 || $("#id_usuario").val()== 1){
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
								}else if(e.pagado == 0){
									$("#montoPago").val(e.monto);
									$('#btnValidar').hide();
								}
								
								//$('#divBotones').append(button);
							});
							$("#modalAgregarPago").modal({
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
				function editarPagoExtra(idPago){
					var formData = new FormData;
					formData.append("idCobro", idPago);
					$.ajax({
						url: "./cliente.php?get_pago_extra_info=true",
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
							$.each(response.detallePago,function(i,e) {
								//Campos Enganche
								var button = "";
								$("#idPagoExtraEng").val(e.idCobro);	
								$("#tipoPagoExtraEng").val(e.tipoPago);
								$("#tipoCobroExtraEng").val(e.tipoCobroExtra);
								getBancos('bancoDepositoExtraEng',e.bancoDeposito);
								getBancos('bancoChequeExtraEng',e.bancoCheque);	
								$("#noDepositoExtraEng").val(e.noDeposito);
								$("#observacionesExtraEng").val(e.observaciones);
								$("#noReciboExtraEng").val(e.noRecibo);
								$("#fechaPagoExtraEng").val(e.fechaPagoRealizado);
								$("#montoPagoExtraEng").val(e.montoPagado);
								
								if(e.validado==0){
								if($("#id_usuario").val()== 11 || $("#id_usuario").val()== 1){
									console.log("validar");
									document.getElementById("btnValidarExtraEng").innerHTML = 'Validar';
									document.getElementById("btnValidarExtraEng").disabled = false;
								}else{
									document.getElementById("btnValidarExtraEng").innerHTML = 'Pendiente';
									document.getElementById("btnValidarExtraEng").disabled = true;
								}
								}else{
									document.getElementById("btnValidarExtraEng").innerHTML = 'Validado';
									document.getElementById("btnValidarExtraEng").disabled = true;
								}
								 
								
								//$('#divBotones').append(button);
							});
							$("#modalAgregarPagoExtraEng").modal({
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
				function editarPagoComision(idFormaPagoComision,apartamento,monto){
					var formData = new FormData;
					formData.append("idFormaPagoComision", idFormaPagoComision);
					formData.append("apartamentoPagoComision", apartamento);
					formData.append("idEnganche", $("#idEngancheEstadoCuenta").val());
					var lis = document.querySelectorAll('#ulPagosComision li');
					var count = 1;
					for(var i=1; li=lis[i]; i++) {
						count ++;
						li.parentNode.removeChild(li);
						$('#pago_'+count).remove();	
					}
					$.ajax({
						url: "./cliente.php?get_pago_info_comision=true",
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
							$("#idFormaPagoComision").val(idFormaPagoComision);	
							$("#apartamentoPagoComision").val(apartamento);
							$("#montoPago_1").val(monto);
							$("#idPagoComision_1").val('');	
							getBancos('bancoCheque_1','');
							$("#noDeposito_1").val('');
							$("#observaciones_1").val('');
							$("#fechaPago_1").val('');
							$.each(response.detallePagoComision,function(i,e) {
								if(!document.getElementById("pago_"+e.noPago)){
									var ul="";
									var div ="";
									var noPago = 1 + parseInt(e.pagosRealizados);
									ul =ul + '<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pago_'+e.noPago+'">Pago '+e.noPago+'</a></li>';	
									div = div + '<div class="container tab-pane" id="pago_'+e.noPago+'"  >';
									div = div + '<div id="divAlertPago" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">';
									div = div + '</div>';
									div = div + '<input type="hidden" id="idPagoComision_'+e.noPago+'" name="idPagoComision_'+e.noPago+'">';
									div = div + '<div class="row" >';
									div = div + '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >';
									div = div + '<label class="nodpitext">Monto:</label>';
									div = div + '<input type="text" id="montoPago_'+e.noPago+'" name="montoPago_'+e.noPago+'" class="form-control">';
									div = div + '</div>';
									div = div + '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >';
									div = div + '<label class="nodpitext">Banco Cheque:</label>';
									div = div + '<select class="form-control" name="bancoCheque_'+e.noPago+'" id="bancoCheque_'+e.noPago+'"  onchange="">';
									div = div + '<option value="" >SELECCIONE</optinon>';
									div = div + '<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>';
									div = div + '<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>';
									div = div + '<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>';
									div = div + '<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>';
									div = div + '<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>';
									div = div + '<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>';
									div = div + '<option value="NO APLICA" >NO APLICA</optinon>';
									div = div + '</select>';
									div = div + '</div>';
									div = div + '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >';
									div = div + '<label class="nodpitext">No. Cheque:</label>';
									div = div + '<input type="text" id="noDeposito_'+e.noPago+'" name="noDeposito_'+e.noPago+'" class="form-control">';
									div = div + '</div>';
									div = div + '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >';
									div = div + '<label class="nodpitext">Fecha pago:</label>';
									div = div + '<input type="date" id="fechaPago_'+e.noPago+'" name="fechaPago_'+e.noPago+'" max="<?php echo date("Y-m-d") ?>" class="form-control">';
									div = div + '</div>';
									div = div + '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >';
									div = div + '<label class="nodpitext">Observaciones:</label>';
									div = div + '<textarea class="form-control" id="observaciones_'+e.noPago+'" name="observaciones_'+e.noPago+'" rows="2"></textarea>';
									div = div + '</div>';
									div = div + '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;" id="divBotones" name="divBotones">';
									div = div + '<br><br><button onclick="guardarPagoComision('+e.noPago+')" class="guardar" type="button">Guardar</button>';
									div = div + '<button disabled onclick="validarPagoComision('+e.noPago+')" class="guardar" type="button" id="btnValidar_'+e.noPago+'">Pendiente</button>';
									div = div + '</div>';
									div = div + '</div>';
									div = div + '</div>';
									$("#ulPagosComision").append(ul);
									$("#renderDatosComision").append(div);
									$("#montoPago_"+e.noPago).number( true, 2 );
								}	
							});
							$.each(response.detallePagoComision,function(i,e) {
								//Campos Enganche
								var button = "";
								$("#idPagoComision_"+e.noPago).val(e.idPagoComision);	
								getBancos('bancoCheque_'+e.noPago,e.bancoPago);
								$("#noDeposito_"+e.noPago).val(e.noCheque);
								$("#observaciones_"+e.noPago).val(e.observaciones);
								$("#fechaPago_"+e.noPago).val(e.fechaPago);
								$("#montoPago_"+e.noPago).val(e.monto);
								
								if(e.entregado==0){
									if($("#id_usuario").val()== 11 || $("#id_usuario").val()== 1){
										console.log("validar");
										document.getElementById("btnValidar_"+e.noPago).innerHTML = 'Entregar';
										document.getElementById("btnValidar_"+e.noPago).disabled = false;
									}else{
										document.getElementById("btnValidar_"+e.noPago).innerHTML = 'Pendiente';
										document.getElementById("btnValidar_"+e.noPago).disabled = true;
									}
								}else{
									document.getElementById("btnValidar_"+e.noPago).innerHTML = 'Entregado';
									document.getElementById("btnValidar_"+e.noPago).disabled = true;
								}
							});
							$("#modalAgregarPagoComision").modal({
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
				function nuevoPagoComision(){
					var formData = new FormData;
					formData.append("idEnganche", $("#idEngancheEstadoCuenta").val());
					formData.append("idFormaPagoComision", $("#idFormaPagoComision").val());

					$.ajax({
						url: "./cliente.php?get_no_pago_comision=true",
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
							
							$.each(response.detallePagoComision,function(i,e) {
								var ul="";
								var div ="";
								var noPago = 1 + parseInt(e.pagosRealizados);
								ul =ul + '<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pago_'+noPago+'">Pago '+noPago+'</a></li>';	
								div = div + '<div class="container tab-pane" id="pago_'+noPago+'"  >';
								div = div + '<div id="divAlertPago" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">';
								div = div + '</div>';
								div = div + '<input type="hidden" id="idPagoComision_'+noPago+'" name="idPagoComision_'+noPago+'">';
								div = div + '<div class="row" >';
								div = div + '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >';
								div = div + '<label class="nodpitext">Monto:</label>';
								div = div + '<input type="text" id="montoPago_'+noPago+'" name="montoPago_'+noPago+'" class="form-control">';
								div = div + '</div>';
								div = div + '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >';
								div = div + '<label class="nodpitext">Banco Cheque:</label>';
								div = div + '<select class="form-control" name="bancoCheque_'+noPago+'" id="bancoCheque_'+noPago+'"  onchange="">';
								div = div + '<option value="" >SELECCIONE</optinon>';
								div = div + '<option value="BANRURAL S.A." >BANRURAL S.A.</optinon>';
								div = div + '<option value="BANCO AGROMERCANTIL DE GUATEMALA, S.A." >BANCO AGROMERCANTIL DE GUATEMALA, S.A.</optinon>';
								div = div + '<option value="BANCO G AND T CONTINENTAL, S.A." >BANCO G AND T CONTINENTAL, S.A.</optinon>';
								div = div + '<option value="BANCO INDUSTRIAL, S.A." >BANCO INDUSTRIAL, S.A.</optinon>';
								div = div + '<option value="BANCO INMOBILIARIO, S.A." >BANCO INMOBILIARIO, S.A.</optinon>';
								div = div + '<option value="BANCO PROMERICA DE GUATEMALA, S.A." >BANCO PROMERICA DE GUATEMALA, S.A.</optinon>';
								div = div + '<option value="NO APLICA" >NO APLICA</optinon>';
								div = div + '</select>';
								div = div + '</div>';
								div = div + '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >';
								div = div + '<label class="nodpitext">No. Cheque:</label>';
								div = div + '<input type="text" id="noDeposito_'+noPago+'" name="noDeposito_'+noPago+'" class="form-control">';
								div = div + '</div>';
								div = div + '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >';
								div = div + '<label class="nodpitext">Fecha pago:</label>';
								div = div + '<input type="date" id="fechaPago_'+noPago+'" name="fechaPago_'+noPago+'" max="<?php echo date("Y-m-d") ?>" class="form-control">';
								div = div + '</div>';
								div = div + '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >';
								div = div + '<label class="nodpitext">Observaciones:</label>';
								div = div + '<textarea class="form-control" id="observaciones_'+noPago+'" name="observaciones_'+noPago+'" rows="2"></textarea>';
								div = div + '</div>';
								div = div + '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;" id="divBotones" name="divBotones">';
								div = div + '<br><br><button onclick="guardarPagoComision('+noPago+')" class="guardar" type="button">Guardar</button>';
								div = div + '<button disabled onclick="validarPagoComision('+noPago+')" class="guardar" type="button" id="btnValidar_'+noPago+'">Pendiente</button>';
								div = div + '</div>';
								div = div + '</div>';
								div = div + '</div>';
								$("#ulPagosComision").append(ul);
								$("#renderDatosComision").append(div);
								$("#montoPago_"+noPago).number( true, 2 );
								
								
							});
							
							
							
							// $("#idFormaPagoComision").val(idFormaPagoComision);	
							// $("#apartamentoPagoComision").val(apartamento);
							// $("#montoPago_1").val(monto);
							// $("#idPagoComision_1").val('');	
							// getBancos('bancoCheque_1','');
							// $("#noDeposito_1").val('');
							// $("#observaciones_1").val('');
							// $("#fechaPago_1").val('');
							// $.each(response.detallePagoComision,function(i,e) {
							// 	//Campos Enganche
							// 	var button = "";
							// 	$("#idPagoComision_"+e.noPago).val(e.idPagoComision);	
							// 	getBancos('bancoCheque_'+e.noPago,e.bancoPago);
							// 	$("#noDeposito_"+e.noPago).val(e.noCheque);
							// 	$("#observaciones_"+e.noPago).val(e.observaciones);
							// 	$("#fechaPago_"+e.noPago).val(e.fechaPago);
							// 	$("#montoPago_"+e.noPago).val(e.monto);
								
							// 	if(e.entregado==0){
							// 		if($("#id_usuario").val()== 11 || $("#id_usuario").val()== 1){
							// 			console.log("validar");
							// 			document.getElementById("btnValidar_"+e.noPago).innerHTML = 'Entregar';
							// 			document.getElementById("btnValidar_"+e.noPago).disabled = false;
							// 		}else{
							// 			document.getElementById("btnValidar_"+e.noPago).innerHTML = 'Pendiente';
							// 			document.getElementById("btnValidar_"+e.noPago).disabled = true;
							// 		}
							// 	}else{
							// 		document.getElementById("btnValidar_"+e.noPago).innerHTML = 'Entregado';
							// 		document.getElementById("btnValidar_"+e.noPago).disabled = true;
							// 	}
							// });
							$("#modalAgregarPagoComision").modal({
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
				function editarPagoFinal(idEnganche){
					var formData = new FormData;
					formData.append("idEnganche", idEnganche);
					$.ajax({
						url: "./cliente.php?get_pago_final_info=true",
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
								$("#idEngancheF").val(idEnganche);
								$.each(response.detallePagoFinal,function(i,e) {
								//Campos Enganche	
								$("#tipoPagoF").val(e.tipoPago);
								$("#bancoDepositoF").val(e.bancoDeposito);
								$("#noDepositoF").val(e.noDeposito);
								$("#observacionesF").val(e.noDeposito);
								$("#bancoChequeF").val(e.bancoCheque);
								$("#noChequeF").val(e.noCheque);
								$("#fechaPagoF").val(e.fechaPago);
								$("#tipoDesembolso").val(e.tipoDesembolso);
								if(e.pagado == 1){
									$("#montoPagoF").val(e.montoPagado);
								}else if(e.pagado == 0){
									$("#montoPagoF").val(e.monto);
								}
							});
							$("#modalAgregarPagoF").modal({
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
				function editarContra(idContra){
					var formData = new FormData;
					formData.append("idContra", idContra);
					$.ajax({
						url: "./cliente.php?get_contra_info=true",
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
							$("#idContra").val(idContra);
								$.each(response.detalleContra,function(i,e) {
								$("#idEngancheContra").val(e.idEnganche);
								//Campos Enganche	
								$("#accionContra").val(e.accion);
								$("#montoContra").val(e.monto);
								$("#observacionesContra").val(e.observaciones);
							});
							$("#modalAgregarContra").modal({
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
				function agregarContracargo(idEnganche){
					document.getElementById("frmAgregarFormalizarContra").reset();
					$("#idEngancheContra").val(idEnganche);
					$("#idContra").val(0);
					$("#modalAgregarContra").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
				}
				function agregarPagoExtra(idEnganche){
					document.getElementById("frmAgregarFormalizarPagoExtra").reset();
					$("#idEngancheContra").val(idEnganche);
					$("#idPagoExtra").val(0);
					$("#modalAgregarPagoExtra").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
				}
				function agregarPagoExtraEng(idEnganche){
					document.getElementById("frmAgregarFormalizarPagoExtraEng").reset();
					$("#idEngancheContra").val(idEnganche);
					$("#idPagoExtra").val(0);
					$("#modalAgregarPagoExtraEng").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
				}
				function guardarPago(){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarFormalizarPago"));
					formDataEnganche.append("idEnganche", $("#idEngancheEstadoCuenta").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_pago=true",
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
							$('#bodyAgregarPago').animate({scrollTop:0}, 'fast');
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err === true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');getEngancheDetalle('+response.idEnganche+')">Aceptar</div>');
							}				
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function guardarPagoExtraEng(){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarFormalizarPagoExtraEng"));
					formDataEnganche.append("idEnganche", $("#idEngancheEstadoCuenta").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_pago_extra_eng=true",
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
							$('#bodyAgregarPagoExtraEng').animate({scrollTop:0}, 'fast');
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err === true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');getEngancheDetalle('+response.idEnganche+')">Aceptar</div>');
							}				
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function guardarPagoComision(noPago){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarFormalizarPagoComision"));
					formDataEnganche.append("idFormaPagoComision", $("#idFormaPagoComision").val());
					formDataEnganche.append("apartamentoPagoComision", $("#apartamentoPagoComision").val());
					formDataEnganche.append("noPagoComision", noPago);
					formDataEnganche.append("idEnganche", $("#idEngancheEstadoCuenta").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_pago_comision=true",
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
							$('#bodyAgregarPagoComision').animate({scrollTop:0}, 'fast');
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err === true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');getEngancheDetalle('+$("#idEngancheEstadoCuenta").val()+')">Aceptar</div>');
							}				
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function validarPago(){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarFormalizarPago"));
					formDataEnganche.append("idEnganche", $("#idEngancheEstadoCuenta").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_pago_validar=true",
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
							$('#bodyAgregarPago').animate({scrollTop:0}, 'fast');
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err === true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');getEngancheDetalle('+response.idEnganche+')">Aceptar</div>');
							}				
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function validarPagoExtraEng(){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarFormalizarPagoExtraEng"));
					formDataEnganche.append("idEnganche", $("#idEngancheEstadoCuenta").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_pago_enganche_validar=true",
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
							$('#bodyAgregarPago').animate({scrollTop:0}, 'fast');
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err === true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');getEngancheDetalle('+response.idEnganche+')">Aceptar</div>');
							}				
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function validarPagoComision(){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarFormalizarPagoComision"));
					formDataEnganche.append("idEnganche", $("#idEngancheEstadoCuenta").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_pago_validar_comision=true",
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
							$('#bodyAgregarPago').animate({scrollTop:0}, 'fast');
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err === true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');getEngancheDetalle('+response.idEnganche+')">Aceptar</div>');
							}				
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function guardarContra(){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarFormalizarContra"));
					formDataEnganche.append("idEnganche", $("#idEngancheContra").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_contra=true",
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
							$('#bodyAgregarContra').animate({scrollTop:0}, 'fast');
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err === true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');getEngancheDetalle('+response.idEnganche+')">Aceptar</div>');
							}					
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function guardarPagoF(){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarFormalizarPagoF"));
					formDataEnganche.append("idEnganche", $("#idEngancheEstadoCuenta").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_pago_final=true",
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
							$('#bodyAgregarPagoF').animate({scrollTop:0}, 'fast');
							$("#modal_confirm").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							if (response.err === true) {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
							}
							else {
								$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');getEngancheDetalle('+response.idEnganche+')">Aceptar</div>');
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

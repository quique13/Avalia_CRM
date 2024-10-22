<?php
session_name("inmobiliaria");
session_start();
if (!isset($_SESSION['login']) or $_SESSION['login'] != 'si') {
    echo "<script>location.href = 'index.php'</script>";
}
$id_usuario = $_SESSION['id_usuario'];
$id_perfil = $_SESSION['id_perfil'];
$readOnly = '';
$super = 0;
$vendedor = 0;
if ($id_perfil != 3) {
    $readOnly = "readonly";
    $super = 1;
    $vendedor = 1;
}
if($id_perfil ==4){
	$bloqueado=1;
}else{
	$bloqueado=0;
}
$arrayProyectos = explode(",", $_SESSION['proyectos']);
$proyectos = '';
$countP = 0;
foreach ($arrayProyectos as $valor) {
    if ($valor == "Marabi") {
        $val = 1;
    }

    if ($valor == "Naos") {
        $val = 2;
    }

    $countP++;
    $proyectos .= '<option value="' . $val . '" >' . $valor . '</optinon>';
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
		<div class="wrapper">
			<div class="content-wrapper">
				<div class="">
					<section class="content">
						<div class="row">
							<div class="col-md-12">
								<div class="box box-warning">
									<div  class="box-header with-border">
										<div class="col-lg-12 col-md-12" style="text-align:center;margin-bottom:10px;margin-top:10px;" id="headerCatalogo">
                							<label class="apartamentosearchitittle"><img class="usersearchicon" src=""> Busqueda de Cotizaciones</label>
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
															<input type="hidden" id="esVendedor" name="esVendedor" value="<?php echo $vendedor; ?>"  >
															<input type="hidden" id="usuarioVendedor" name="usuarioVendedor" value="<?php echo $id_usuario; ?>"  >
															<input type="hidden" id="idPerfil" name="idPerfil" value="<?php echo $id_perfil; ?>"  >
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
														<div class="col-lg-1 col-md-1 col-xs-10" style="margin-bottom:10px;">
															<label  class="nodpitext"  style="color: white">_____</label>
															<button onclick="buscarCotizacion()" class="searchf" type="button">Buscar</button>
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
																		<th style="width:20%;">Proyecto</th>
																		<th style="width:25%;">Cliente</th>
																		<th style="width:25%;">Correo</th>
																		<th style="width:10%;">Télefono</th>
																		<th style="width:10%;">Apartamento</th>
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
												<input type="hidden" id="rateCot1" name="rateCot1">
												<input type="hidden" id="rateCot2" name="rateCot2">
												<input type="hidden" id="rateCot3" name="rateCot3">

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
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Aplica subsidio: </label>
																<div class=" form-check form-check-inline" >
																	<input class="form-check-input" type="radio" name="subsidio" id="subsidio_no" value="no" checked>
																	<label class="form-check-label" for="">No</label>
																</div>
																<div class="form-check form-check-inline" >
																	<input class="form-check-input" type="radio" name="subsidio" id="subsidio_si" value="si">
																	<label class="form-check-label">Si</label>
																</div>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext"><img class="infoselicon" src="../img/sale_info.png" alt=""> Información de venta</label>
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" onChange="setDescuentoCot('porcentaje',descuentoPorcentualCot.value)">
																<label class="nodpitext">Parqueos extra:</label>
																<input type="number" id="parqueosExtraCot" name="parqueosExtraCot" class="form-control">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" onChange="setDescuentoCot('porcentaje',descuentoPorcentualCot.value)">
																<label class="nodpitext">Parqueos extra Moto:</label>
																<input type="number" id="parqueosExtraMotoCot" name="parqueosExtraMotoCot" class="form-control">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" onChange="setDescuentoCot('porcentaje',descuentoPorcentualCot.value)">
																<label class="nodpitext">Bodegas extra:</label>
																<input type="number" id="bodegaExtraCot" name="bodegaExtraCot" class="form-control">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Tipo Cocina:</label>
																<select class="form-control" name="CocinaCot" id="CocinaCot" onChange="setDescuentoCot('porcentaje',descuentoPorcentualCot.value)">
																	<option value="Sin cocina" >Sin cocina</optinon>
																	<option value="cocinaTipoA" >Cocina Tipo A</optinon>
																	<option value="cocinaTipoB" >Cocina Tipo B</optinon>
																</select>
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Descuento (%):</label>
																<input <?php echo $readOnly ?>   type="text" id="descuentoPorcentualCot" name="descuentoPorcentualCot" class="form-control"  onChange="setDescuentoCot('porcentaje',this.value)">
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Descuento:</label>
																<input <?php echo $readOnly ?>  type="text" id="descuentoPorcentualMontoCot" name="descuentoPorcentualMontoCot" class="form-control" onChange="setDescuentoCot('monto',this.value)">
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
																	<option value="2" >2 años</optinon>
																	<option value="5" >5 años</optinon>
																	<option value="10" >10 años</optinon>
																	<option value="15" >15 años</optinon>
																	<option value="20" >20 años</optinon>
																	<option value="25" >25 años</optinon>
																</select>
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">No. Meses:</label>
																<input type="number" id="mesesEnganche" name="mesesEnganche" value="20" class="form-control" onChange="selectPagosEnganche(this.value,'pagosEngancheCot')">
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
																	<option value="15" >15 meses</optinon>f
																	<option value="16" >16 meses</optinon>
																	<option value="17" >17 meses</optinon>
																	<option value="18" >18 meses</optinon>
																	<option value="19" >19 meses</optinon>
																	<option value="20" >20 meses</optinon>
																</select>
															</div>
															<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Banco Financiamiento:</label>
																<select <?php echo $readOnly ?> id="bancoFinanciamientoCot" name="bancoFinanciamientoCot" class="form-control" onChange="">
																</select>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext"><img class="infoselicon" src="../img/client_icon.png" alt=""> Información Vendedor</label>
															</div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																<label class="nodpitext">Nombre Contacto:</label>
																<select <?php echo $readOnly ?>  class="form-control" name="nombreVendedorCot" id="nombreVendedorCot"  onchange="datosVendedor(this.value,1)">
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
																	<table id="resultadoEncabezado" class="table table-sm table-hover"  style="width:100%">
																		
																	</table>
																	<table id="resultadoCuota" class="table table-sm table-hover"  style="width:100%">
																		
																	</table>
																	<table id="resultadoEnganche" class="table table-sm table-hover"  style="width:100%">
																		<tr>
																			<th style="width:25%;">% Enganche</th>
																			<th style="width:25%;">Cuota Enganche</th>
																			<th style="width:25%;">No. Pagos</th>
																			<th style="width:25%;">Monto Reserva</th>
																		</tr>
																	</table>
																	<table id="resultadoEnganche2" class="table table-sm table-hover"  style="width:100%">
																		<tr>
																			<th style="width:25%;">Ingreso Familiar Requerido</th>
																			<th style="width:25%;">Pago Contra entrega</th>
																			<th style="width:25%;">Interes financiamiento</th>
																			<th style="width:25%;"></th>
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
					<!-- Modal -->
					<div class="modal fade" id="modalEnviarMail">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Enviar Email de Agradecimiento</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyEnviarMail" style="padding:5px 15px;" >
									<div class="secinfo" >
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmEnviarMail" name="frmEnviarMail" method="POST">
											<div class="row" >
												<input type="hidden" id="idCotizacion" name="idCotizacion">
												<div id="divAlertCliente" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Primer Nombre:</label>
															<input type="text" id="primerNombre" name="primerNombre" placeHolder="Primer Nombre" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Segundo Nombre:</label>
															<input type="text" id="segundoNombre" name="segundoNombre" placeHolder="Segundo Nombre" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Primer Apellido:</label>
															<input type="text" id="primerApellido" name="primerApellido" placeHolder="Primer apellido" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Segundo Apellido:</label>
															<input type="text" id="segundoApellido" name="segundoApellido" placeHolder="Segundo Apellido" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Tercer Nombre:</label>
															<input type="text" id="tercerNombre" name="tercerNombre" placeHolder="Tercer Nombre" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Apellido Casada:</label>
															<input type="text" id="apellidoCasada" name="apellidoCasada" placeHolder="Apellido Casada" class="form-control" >
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Correo electrónico:</label>
															<input type="text" id="correo" name="correo" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Télefono Fijo:</label>
															<input  type="text" id="telefonoFijo" name="telefonoFijo" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Télefono Celular:</label>
															<input  type="text" id="telefono" name="telefono" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Dirección:</label>
															<textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label class="nodpitext">Nit:</label>
															<input type="text" id="nitCl" name="nitCl" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Número de DPI:</label>
															<input type="text" id="numeroDpi" name="numeroDpi" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Departamento:</label>
															<select class="form-control" name="depto" id="depto" onchange="getMunicipios(this.value,'municipio','')">
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Municipio:</label>
															<select class="form-control" name="municipio" id="municipio" onchange="">
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label class="nodpitext">Fecha Emisión DPI:</label>
															<input type="date" id="fechaEmisionDpiCl" name="fechaEmisionDpiCl" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Fecha vencimiento DPI:</label>
															<input id="fechaVencimientoDpi" name="fechaVencimientoDpi" type="date" class="form-control">
															<input id="fechaHoy" name="fechaHoy" type="hidden" class="form-control" value="<?php echo date("d/m/Y") ?>">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label class="nodpitext">Nacionalidad:</label>
															<select class="form-control" name="nacionalidadCl" id="nacionalidadCl" onchange="">
															</select>
														</div>
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label class="nodpitext">Fecha de nacimiento:</label>
															<input type="date" id="fechaNacimientoCl" name="fechaNacimientoCl" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Estado Civil:</label>
															<select class="form-control" name="estadoCivilCl" id="estadoCivilCl" onchange="">
																<option value="" >Seleccione</optinon>
																<option value="Soltero" >Soltero(a)</optinon>
																<option value="Casado" >Casado(a)</optinon>
																<option value="Viudo" >Viudo(a)</optinon>
																<option value="Divorciado" >Divorciado(a)</optinon>
															</select>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Profesión:</label>
															<input type="text" id="profesionCl" name="profesionCl" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">No. de dependientes:</label>
															<input type="number" id="dependientesCl" name="dependientesCl" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Ha tenido tramite FHA:</label>
															<div class=" form-check form-check-inline"  style="">
																<input class="form-check-input" type="radio" name="fha" id="si" value="si">
																<label class="form-check-label" for="">Si</label>
															</div>
															<div class="form-check form-check-inline"  style="">
																<input class="form-check-input" type="radio" name="fha" id="no" value="no">
																<label class="form-check-label">No</label>
															</div>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Empresa donde labora:</label>
															<input type="text" id="empresaLaboraCl" name="empresaLaboraCl" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Dirección de empresa:</label>
															<textarea class="form-control" id="direccionEmpresaCl" name="direccionEmpresaCl" rows="2"></textarea>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Télefono de Referencia:</label>
															<input  type="text" id="telefonoReferencia" name="telefonoReferencia" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Puesto en Empresa:</label>
															<input type="text" id="puestoEmpresaCl" name="puestoEmpresaCl" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Salario mensual:</label>
															<input type="text" id="salarioMensualCl" name="salarioMensualCl" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Otros ingresos:</label>
															<input type="text" id="montoOtrosIngresosCl" name="montoOtrosIngresosCl" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Descripción Otros ingresos:</label>
															<input type="text" id="otrosIngresosCl" name="otrosIngresosCl" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="draganddroptexttitle" for="mail">DPI y Recibo de servicios:</label>
															<input class="draganddrop" type="file" id="fliesDpiRecibo[]" name="fliesDpiRecibo[]" placeholder="Arrastra y suelta aquí " accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" multiple>
														</div>
														<script type="text/javascript">
															$("#salarioMensualCl").number( true, 2 );
															$("#montoOtrosIngresosCl").number( true, 2 );
														</script>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
													<button onclick="enviarMail()" class="guardar" type="button">Guardar</button>
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
				</div>
			</div>
		</div>
			<script type="text/javascript">

function getBancosFin(input,valueInput){
				//console.log("funcion buscar niveles");
				var formData = new FormData;
				
				$.ajax({
					url: "./bancoFin.php?get_bancos=true",
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
						//output += ' <option value="">Seleccione</option>';
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
				function buscarCotizacion(){
					//console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarApartamento"));
					formData.append("proyectoBscTxt", document.getElementById("proyectoBsc").options[document.getElementById("proyectoBsc").selectedIndex].text);
					formData.append("torreBscTxt", document.getElementById("torreBsc").options[document.getElementById("torreBsc").selectedIndex].text);
					formData.append("nivelBscTxt", document.getElementById("nivelBsc").options[document.getElementById("nivelBsc").selectedIndex].text);
					formData.append("apartamentoBscTxt", document.getElementById("apartamentoBsc").options[document.getElementById("apartamentoBsc").selectedIndex].text);
					$.ajax({
						url: "./cliente.php?get_cotizaciones_lista=true",
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
								output += '<tr onCLick="verCotizacion(\''+e.idCotizacion+'\')"><td>'+e.proyecto+' </td><td>'+e.nombreCompleto+'</td><td>'+e.correo+'</td><td>'+e.telefono+'</td><td>'+e.apartamento+'</td><td><button onclick="verCotizacion(\''+e.idCotizacion+'\')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ></div><div class="col-lg-4 col-md-4 col-xs-10" style="" ></td></tr>';
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

				if($("#idPerfil").val()!=5){
					agregarCotizacion();
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
					if(proyecto==1){
						console.log("proyecto");
						document.getElementById("CocinaCot").disabled = true;
					}else{
						document.getElementById("CocinaCot").disabled = false;
					}

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
					var vendedor = $("#esVendedor").val();
					var usuarioVendedor = $("#usuarioVendedor").val();
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
									if(parseInt(vendedor) == 1){
										if(parseInt(usuarioVendedor) == e.id_usuario){
											select= 'selected="selected"';
											datosVendedor(e.id_usuario,1);
										}
									}
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
									$("#rateCot1").val(e.tarifa_1);
									$("#rateCot2").val(e.tarifa_2);
									$("#rateCot3").val(e.tarifa_3);
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
								//$("#divVerAdjuntos").html("</iframe> SE ESTA DESCARGANDO COTIZACIÓN...<iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/cotizacionNo"+response.idCotizacion+"?idCotizacion="+response.idCotizacion+"&cotizacionPdf=true#page=1&zoom=100'></iframe>");
								$("#divVerAdjuntos").html("</iframe> SE ESTA DESCARGANDO COTIZACIÓN...<iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./pruebaMpdf.php/cotizacionNo"+response.idCotizacion+"?idCotizacion="+response.idCotizacion+"&cotizacionPdf=true#page=1&zoom=100'></iframe>");
								buscarCotizacion();
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
				function verCotizacion(idCotizacion){
					$("#modalVerAdjuntos").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
				//$("#divVerAdjuntos").html("</iframe><iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/cotizacionNo"+idCotizacion+"?idCotizacion="+idCotizacion+"&cotizacionPdf=true#page=1&zoom=100'></iframe>");
				$("#divVerAdjuntos").html("</iframe><iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./pruebaMpdf.php/cotizacionNo"+idCotizacion+"?idCotizacion="+idCotizacion+"&cotizacionPdf=true#page=1&zoom=100'></iframe>");

				}
				function calculoCuotasCotizacion(){
					output='';
					outputEnc='';
					outputEng='';
					outputEng2='';
					var bodegaExtra = $("#bodegaExtraCot").val()!=''?parseInt($("#bodegaExtraCot").val()):0
					var parqueoExtra = $("#parqueosExtraCot").val()!=''?parseInt($("#parqueosExtraCot").val()):0
					var parqueoExtraMoto = $("#parqueosExtraMotoCot").val()!=''?parseInt($("#parqueosExtraMotoCot").val()):0
					var porcentajeEnganche = parseFloat($("#engancheMontoCot").val());
					var montoEnganche = parseFloat($("#engancheMontoCot").val());
					var montoDescuento = parseFloat($("#descuentoPorcentualMontoCot").val());
					var montoReserva = parseFloat($("#montoReservaCot").val());
					var mesesEnganche = parseFloat($("#pagosEngancheCot").val());
					var tasaInteres1 = parseFloat($("#rateCot1").val());
					var tasaInteres2 = parseFloat($("#rateCot2").val());
					var tasaInteres3 = parseFloat($("#rateCot3").val());
					var plazoFinanciamiento = parseFloat($("#plazoFinanciamientoCot").val());
					var facturacionPorcentaje = parseFloat($("#porcentajeFacturacionCot").val());
					var ventaAccion = 100-facturacionPorcentaje;
					var tasaIusi = parseFloat($("#iusiCot").val()) / 10;
					var tasaSeguro = parseFloat($("#seguroCot").val());
					var totalParqueo = parqueoExtra * parseFloat($("#parqueoExtraCot").val())
					var totalParqueoMoto = parqueoExtraMoto * parseFloat($("#parqueoExtraMotoCot").val())
					console.log( parseInt($("#parqueosExtraCot").val()) +'*'+ parseFloat($("#parqueoExtraCot").val()) )
					console.log( parseInt($("#parqueosExtraMotoCot").val()) +'*'+ parseFloat($("#parqueoExtraMotoCot").val()) )
					var totalBodega = bodegaExtra * parseFloat($("#bodegaPrecioCot").val())
					console.log( parseInt($("#bodegaExtraCot").val()) +'*'+ parseFloat($("#bodegaPrecioCot").val()) )
					if($("#CocinaCot").val()!='Sin cocina'){
						var cocina = parseFloat($("#"+$("#CocinaCot").val()+"Cot").val())
					}else{
						var cocina = 0;
					}
					var precioTotal = $("#precioTotalCot").val();
						precioTotal = parseFloat( precioTotal.replace(/[Q,]/g,'') );
						precioTotal = precioTotal + totalParqueo + totalParqueoMoto + totalBodega + cocina;
					var precioNeto = precioTotal - montoDescuento;
					var cuotaEnganche = (montoEnganche - montoReserva)/mesesEnganche;
					var pagoContraEntrega = parseFloat(precioNeto) - montoEnganche;
					if(document.getElementById('subsidio_si').checked) {
						//Si aplica subsidio, del año 0 al 4 se aplica tarifa1
						var im_1 = tasaInteres1/ 12 / 100;
						console.log(tasaInteres1+'/ 12 / 100')
						var im2_1 = Math.pow(	(1 + parseFloat(im_1)	), - (12 * plazoFinanciamiento ) );
						var cuotaCredito_1 = (pagoContraEntrega * parseFloat(im_1)) / (1- parseFloat(im2_1) ) ;
						console.log('('+pagoContraEntrega +'*'+ parseFloat(im_1) +') / (1-'+ parseFloat(im2_1)+' )');
						var cuotaSeguro_1 = ((precioNeto*tasaSeguro)/100)/12;
						var ventaPorcionFactura_1 = (precioNeto * facturacionPorcentaje)/100
						var cuotaIusi_1 = ((ventaPorcionFactura_1 * tasaIusi)/100)/12
						var cuotaMensual_1 = cuotaIusi_1 + cuotaSeguro_1 + cuotaCredito_1;
						var ingresoFamiliar_1 = cuotaMensual_1/0.35;

						//Si aplica subsidio, del año 4 al 7 se aplica tarifa2
						var im_2 = tasaInteres2/ 12 / 100;
						console.log(tasaInteres2+'/ 12 / 100')
						var im2_2 = Math.pow(	(1 + parseFloat(im_2)	), - (12 * plazoFinanciamiento ) );
						var cuotaCredito_2 = (pagoContraEntrega * parseFloat(im_2)) / (1- parseFloat(im2_2) ) ;
						console.log('('+pagoContraEntrega +'*'+ parseFloat(im_2) +') / (1-'+ parseFloat(im2_2)+' )');
						var cuotaSeguro_2 = ((precioNeto*tasaSeguro)/100)/12;
						var ventaPorcionFactura_2 = (precioNeto * facturacionPorcentaje)/100
						var cuotaIusi_2 = ((ventaPorcionFactura_2 * tasaIusi)/100)/12
						var cuotaMensual_2 = cuotaIusi_2 + cuotaSeguro_2 + cuotaCredito_2;
						var ingresoFamiliar_2 = cuotaMensual/0.35;

						//Si aplica subsidio, del año 7 en adelante se aplica tarifa3
						var im = tasaInteres3/ 12 / 100;
						console.log(tasaInteres3+'/ 12 / 100')
						var im2 = Math.pow(	(1 + parseFloat(im)	), - (12 * plazoFinanciamiento ) );
						var cuotaCredito = (pagoContraEntrega * parseFloat(im)) / (1- parseFloat(im2) ) ;
						console.log('('+pagoContraEntrega +'*'+ parseFloat(im) +') / (1-'+ parseFloat(im2)+' )');
						var cuotaSeguro = ((precioNeto*tasaSeguro)/100)/12;
						var ventaPorcionFactura = (precioNeto * facturacionPorcentaje)/100
						var cuotaIusi = ((ventaPorcionFactura * tasaIusi)/100)/12
						var cuotaMensual = cuotaIusi + cuotaSeguro + cuotaCredito;
						var ingresoFamiliar = cuotaMensual/0.35;
						
					}else if(document.getElementById('subsidio_no').checked) {
						//Si no aplica a subsidio se mantiene la misma tasa de 7.26
						console.log($("#bancoFinanciamientoCot").val()+'-banco');
						if($("#bancoFinanciamientoCot").val() == "CREDITO HIPOTECARIO NACIONAL" ){
							if(precioNeto <= 330747.69){
								tasaInteres3=7;	
							}else if (precioNeto >= 330747.69 && precioNeto <= 617880.30){
								tasaInteres3=7;
							}else{
								tasaInteres3=7;
							}
							
						}
						if($("#bancoFinanciamientoCot").val() == "CREDITO HIPOTECARIO NACIONAL 5.5" ){
							tasaInteres3=6.01;
						}
						var im = tasaInteres3/ 12 / 100;
						console.log(tasaInteres3+'/ 12 / 100')
						var im2 = Math.pow(	(1 + parseFloat(im)	), - (12 * plazoFinanciamiento ) );
						var cuotaCredito = (pagoContraEntrega * parseFloat(im)) / (1- parseFloat(im2) ) ;
						console.log('('+pagoContraEntrega +'*'+ parseFloat(im) +') / (1-'+ parseFloat(im2)+' )');
						var cuotaSeguro = ((precioNeto*tasaSeguro)/100)/12;
						var ventaPorcionFactura = (precioNeto * facturacionPorcentaje)/100
						var cuotaIusi = ((ventaPorcionFactura * tasaIusi)/100)/12
						var cuotaMensual = cuotaIusi + cuotaSeguro + cuotaCredito;
						var ingresoFamiliar = cuotaMensual/0.35;
					}
					var nuevoPorcentaje = (montoEnganche/precioNeto) * 100;
					outputEnc+='<tr>';

						if(document.getElementById('subsidio_si').checked) {
							outputEnc+='<tr>';
								outputEnc+='<th style="width:25%;">Precio Total</th>';
								outputEnc+='<th style="width:25%;">Monto Descuento</th>';
								outputEnc+='<th style="width:25%;">Precio Neto</th>';
								outputEnc+="<th style='width:25%;'>Cuota IUSI</th>";
								
							outputEnc+='</tr>';

							outputEnc+="<tr>";
								outputEnc+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(precioTotal.toFixed(2)))+"</td>";
								outputEnc+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(montoDescuento.toFixed(2)))+"</td>";
								outputEnc+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(precioNeto.toFixed(2)))+"</td>";
								outputEnc+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaIusi.toFixed(2)))+"</td>";
							outputEnc+="</tr>";

							output+="<tr>";
								output+="<th style='width:25%;'>Cuota seguro</th>";
								output+='<th style="width:25%;">Cuota Mensual (0 a 4 años)</th>';
								output+="<th style='width:25%;'>Cuota crédito(0 a 4 años)</th>";
								output+="<th style='width:25%;'>Cuota Mensual (4 a 7 años)</th>";
							output+="</tr>";
							output+="<tr>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaSeguro.toFixed(2)))+"</td>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaMensual_1.toFixed(2)))+"</td>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaCredito_1.toFixed(2)))+"</td>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaMensual_2.toFixed(2)))+"</td>";
							output+="</tr>";
							output+="<tr>";
								output+="<th style='width:25%;'>Cuota crédito(4 a 7 años)</th>";
								output+="<th style='width:25%;'>Cuota Mensual (7 años en adelante)</th>";
								output+="<th style='width:25%;'>Cuota crédito (7 años en adelante)</th>";
								output+="<th style='width:25%;'>Enganche</th>";
							output+="</tr>";
							output+="<tr>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaCredito_2.toFixed(2)))+"</td>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaMensual.toFixed(2)))+"</td>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaCredito.toFixed(2)))+"</td>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(montoEnganche.toFixed(2)- montoReserva.toFixed(2)))+"</td>";
							output+="</tr>";
						}
						else{
							outputEnc+='<tr>';
								outputEnc+='<th style="width:25%;">Precio Total</th>';
								outputEnc+='<th style="width:25%;">Monto Descuento</th>';
								outputEnc+='<th style="width:25%;">Precio Neto</th>';
								outputEnc+='<th style="width:25%;">Cuota Mensual</th>';
							outputEnc+='</tr>';
							outputEnc+="<tr>";
								outputEnc+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(precioTotal.toFixed(2)))+"</td>";
								outputEnc+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(montoDescuento.toFixed(2)))+"</td>";
								outputEnc+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(precioNeto.toFixed(2)))+"</td>";
								outputEnc+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaMensual.toFixed(2)))+"</td>";
							outputEnc+="</tr>";
							output+="<tr>";
								output+="<th style='width:25%;'>Cuota IUSI</th>";
								output+="<th style='width:25%;'>Cuota seguro</th>";
								output+="<th style='width:25%;'>Cuota crédito</th>";
								output+="<th style='width:25%;'>Enganche</th>";
							output+="</tr>";
							output+="<tr>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaIusi.toFixed(2)))+"</td>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaSeguro.toFixed(2)))+"</td>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(cuotaCredito.toFixed(2)))+"</td>";
								output+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(montoEnganche.toFixed(2)- montoReserva.toFixed(2)))+"</td>";
							output+="</tr>";
						}
					//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
					console.log(output);
					var tb = document.getElementById('resultadoCuota');
					while(tb.rows.length > 0) {
						tb.deleteRow(0);
					}
					outputEng+="<tr>";
					outputEng+="<td >"+nuevoPorcentaje.toFixed(2)+"%</td>";
					outputEng+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(cuotaEnganche.toFixed(2))+"</td>";
					outputEng+="<td >"+ mesesEnganche+"</td>";
					outputEng+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(montoReserva.toFixed(2)))+"</td>";
					outputEng+="</tr>";

					outputEng2+="<tr>";
					outputEng2+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(ingresoFamiliar.toFixed(2)))+"</td>";
					outputEng2+="<td >Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(Math.round(pagoContraEntrega.toFixed(2)))+"</td>";
					outputEng2+="<td >"+new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(tasaInteres3.toFixed(2))+"%</td>";
					outputEng2+="<td ></td>";
					outputEng2+="</tr>";
					//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
					//console.log(outputEng);
					var tb = document.getElementById('resultadoEnganche');
					while(tb.rows.length > 1) {
						tb.deleteRow(1);
					}
					var tb = document.getElementById('resultadoEncabezado');
					while(tb.rows.length > 0) {
						tb.deleteRow(0);
					}
					var tb = document.getElementById('resultadoEnganche2');
					while(tb.rows.length > 1) {
						tb.deleteRow(1);
					}
					$('#resultadoEncabezado').append(outputEnc);
					$('#resultadoCuota').append(output);
					$('#resultadoEnganche').append(outputEng);
					$('#resultadoEnganche2').append(outputEng2);
				}
				function agregarCotizacion(){
					$("#idOcaCotizacion").val($("#idOcaInfo").val())
					torres(0,'torreCot')
					niveles(0,'nivelCot');
					apartamentos(0,0,'apartamentoCot');
					getVendedor('nombreVendedorCot',0)
					getBancosFin('bancoFinanciamientoCot',0);	
					//getCotizacionDetalle(0);
					$("#ProyectoCot").val('');
					$("#engancheCot").val('');
					$("#idCotizacion").val(0);
					$("#engancheMontoCot").val('');
					$("#descuentoPorcentualCot").val('');
					$("#descuentoPorcentualMontoCot").val('');
					$("#parqueosExtraCot").val('');
					$("#parqueosExtraMotoCot").val('');
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
				var parqueoExtraMoto = $("#parqueosExtraMotoCot").val()!=''?parseInt($("#parqueosExtraMotoCot").val()):0
				var totalParqueo = parqueoExtra * parseFloat($("#parqueoExtraCot").val())
				var totalParqueoMoto = parqueoExtraMoto * parseFloat($("#parqueoExtraMotoCot").val())
				var totalBodega = bodegaExtra * parseFloat($("#bodegaPrecioCot").val())
				if($("#CocinaCot").val()!='Sin cocina'){
					var cocina = parseFloat($("#"+$("#CocinaCot").val()+"Cot").val())
				}else{
					var cocina = 0;
				}
				precioTotal = precio + totalBodega + totalParqueo + totalParqueoMoto + cocina;
				console.log(precio +'+'+ totalBodega +'+'+ totalParqueo+'+'+ cocina)
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
			function setEngancheCot(tipo,value,repite=0){
				var vendedor = $("#esVendedor").val();
				var precio =  $("#precioTotalCot").val()!='' ? $("#precioTotalCot").val():'0';
				var precio = parseFloat(precio.replace(/[Q,]/g,''));
				var descuento = $("#descuentoPorcentualMontoCot").val()!='' ? $("#descuentoPorcentualMontoCot").val():'0';
				//console.log("desucento: "+descuento);
				descuento = parseFloat(descuento.replace(/[Q,]/g,''));
				var bodegaExtra = $("#bodegaExtraCot").val()!=''?parseInt($("#bodegaExtraCot").val()):0
				var parqueoExtra = $("#parqueosExtraCot").val()!=''?parseInt($("#parqueosExtraCot").val()):0
				var parqueoExtraMoto = $("#parqueosExtraMotoCot").val()!=''?parseInt($("#parqueosExtraMotoCot").val()):0
				var totalParqueo = parqueoExtra * parseFloat($("#parqueoExtraCot").val())
				var totalParqueoMoto = parqueoExtraMoto * parseFloat($("#parqueoExtraMotoCot").val())
				var totalBodega = bodegaExtra * parseFloat($("#bodegaPrecioCot").val())
				if($("#CocinaCot").val()!='Sin cocina'){
					var cocina = parseFloat($("#"+$("#CocinaCot").val()+"Cot").val())
				}else{
					var cocina = 0;
				}
				var nuevoPrecio = precio + totalParqueo+ totalBodega + totalParqueoMoto + cocina - descuento;
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
				if(vendedor == 1 && repite == 0){
					if(parseFloat($("#engancheCot").val())< parseFloat($("#porcentajeEngacheCot").val())){
						$("#engancheCot").val($("#porcentajeEngacheCot").val());
						setEngancheCot("porcentaje",$("#porcentajeEngacheCot").val(),repite=1)
						
					}
				}
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
			</script>
    </body>
</html>

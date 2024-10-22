<?php
session_name("inmobiliaria");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>"; 
}
$id_usuario=$_SESSION['id_usuario'];
$id_perfil = $_SESSION['id_perfil'];
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
                							<label class="apartamentosearchitittle"><img class="usersearchicon" src=""> Validar Cotizaciones Finales</label>
											<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="divAlertPendiente" name="divAlertPendiente">
                								
											</div>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
										<div class="row">
											<div class="col-md-12" id="busquedaApartamentos">
												<form autocomplete="off"  enctype="multipart/form-data"  id="frmBuscarApartamento" name="frmBuscarApartamento" method="POST">	
													<div class="row">	
														<input id="id_perfil" name="id_perfil" type="hidden" value="<?php echo $id_perfil ?>" >
														<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Proyecto:</label>
															<select class="form-control" name="proyectoBsc" id="proyectoBsc"  onchange="torres(this.value,'torreBsc')">
																<option value="0" >Seleccione</optinon>
																<option value="1" >Marabi</optinon>
																<option value="2" >Naos</optinon>
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
                    				<h5 class="tittle" ><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" > Validar Cotización Final</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarEnganche"  style="padding:5px 15px;">
									<div class="secinfo">
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarEnganche" name="frmAgregarEnganche" method="POST">
											<div class="row" >
												<input type="hidden" id="idEnganche" name="idEnganche">
												<input type="hidden" id="proyectoEnganche" name="proyectoEnganche">
												<input type="hidden" id="idOcaEnganche" name="idOcaEnganche">		
												<input type="hidden" id="bodegaPrecioEng" name="bodegaPrecioEng">
												<input type="hidden" id="cocinaTipoAEng" name="cocinaTipoAEng">
												<input type="hidden" id="cocinaTipoBEng" name="cocinaTipoBEng">
												<input type="hidden" id="iusiEng" name="iusiEng">
												<input type="hidden" id="parqueoExtraEng" name="parqueoExtraEng">
												<input type="hidden" id="parqueoExtraMotoEng" name="parqueoExtraMotoEng">
												<input type="hidden" id="porcentajeEngacheEng" name="porcentajeEngacheEng">
												<input type="hidden" id="porcentajeFacturacionEng" name="porcentajeFacturacionEng">
												<input type="hidden" id="seguroEng" name="seguroEng">
												<input type="hidden" id="ReservaEng" name="ReservaEng">
												<input type="hidden" id="rateEng" name="rateEng">

												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
											
													<div class="row" >
														<div id="divAlertEnganche" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext"><img class="infoselicon" src="../img/apartment_info.png" alt=""> Información de apartamento</label>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Proyecto:</label>
															<select class="form-control" name="ProyectoEng" id="ProyectoEng"  onchange="torres(this.value,'torreEng')">
																<option value="0" >Seleccione</optinon>
																<option value="1" >Marabi</optinon>
																<option value="2" >Naos</optinon>
															</select>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Torre/Fase:</label>
															<select class="form-control" name="torreEng" id="torreEng" onchange="niveles(this.value,'nivelEng')">
															</select>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Nivel:</label>
															<select class="form-control" name="nivelEng" id="nivelEng"  onchange="apartamentos(0,this.value,'apartamentoEng')">
															</select>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Apartamento:</label>
															<select class="form-control" name="apartamentoEng" id="apartamentoEng" onchange="datosApartamento(this.value,0)">
															</select>
														</div>

														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Tamaño en mt2:</label>
															<input type="text" id="mt2Eng" name="mt2Eng" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Habitaciones:</label>
															<input type="text" id="habitacionesEng" name="habitacionesEng" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Bodega:</label>
															<input type="text" id="bodegaEng" name="bodegaEng" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Parqueo para moto</label>
															<input type="text" id="parqueoMotoEng" name="parqueoMotoEng" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Parqueo para carro</label>
															<input type="text" id="parqueoEng" name="parqueoEng" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Ärea Jardín mt2</label>
															<input type="text" id="jardinMt2Eng" name="jardinMt2Eng" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Precio Total (GtQ)</label>
															<input type="text" id="precioTotalEng" name="precioTotalEng" class="form-control" readonly>
														</div>

														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext"><img class="infoselicon" src="../img/client_icon.png" alt=""> Información Cliente</label>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Nombre del Cliente:</label>
															<input type="text" id="nombreClienteEng" name="nombreClienteEng" class="form-control" readonly>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Correo:</label>
															<input type="text" id="correoEng" name="correoEng" class="form-control" readonly>
														</div>
														<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Télefono:</label>
															<input type="text" id="telefonoEng" name="telefonoEng" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Nit:</label>
															<input type="text" id="nitEng" name="nitEng" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">DPI:</label>
															<input type="text" id="dpiEng" name="dpiEng" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Fecha Emisión DPI:</label>
															<input type="date" id="fechaEmisionDpiEng" name="fechaEmisionDpiEng" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Fecha de nacimiento:</label>
															<input type="date" id="fechaNacimientoEng" name="fechaNacimientoEng" class="form-control"  readonly>
														</div>
														<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Dirección Residencia:</label>
															<textarea class="form-control" id="direccionEng" name="direccionEng" rows="2" readonly></textarea>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Nacionalidad:</label>
															<input type="text" id="nacionalidadEng" name="nacionalidadEng" class="form-control" readonly>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Estado Civil:</label>
															<input type="text" id="estadoCivilEng" name="estadoCivilEng" class="form-control" readonly>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Empresa donde labora:</label>
															<input type="text" id="empresaLaboraEng" name="empresaLaboraEng" class="form-control" readonly>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Puesto en Empresa:</label>
															<input type="text" id="puestoEmpresaEng" name="puestoEmpresaEng" class="form-control" readonly>
														</div>
														<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Dirección de empresa:</label>
															<textarea class="form-control" id="direccionEmpresaEng" name="direccionEmpresaEng" rows="2" readonly></textarea>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Salario mensual:</label>
															<input type="text" id="salarioMensualEng" name="salarioMensualEng" class="form-control" readonly>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Otros ingresos:</label>
															<input type="text" id="otrosIngresosEng" name="otrosIngresosEng" class="form-control" readonly>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Monto otros ingresos:</label>
															<input type="text" id="montoOtrosIngresosEng" name="montoOtrosIngresosEng" class="form-control" readonly>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext"><img class="infoselicon" src="../img/sale_info.png" alt=""> Información de venta</label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" onChange="setDescuento('porcentaje',descuentoPorcentualEng.value)">
															<label class="nodpitext">Parqueos extra:</label>
															<input type="number" id="parqueosExtraEng" name="parqueosExtraEng" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" onChange="setDescuento('porcentaje',descuentoPorcentualEng.value)">
															<label class="nodpitext">Bodegas extra:</label>
															<input type="number" id="bodegaExtraEng" name="bodegaExtraEng" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Tipo Cocina:</label>
															<select class="form-control" name="CocinaEng" id="CocinaEng" onChange="setDescuentoCot('porcentaje',descuentoPorcentualCot.value)">
																<option value="Sin cocina" >Sin cocina</optinon>
																<option value="cocinaTipoA" >Cocina Tipo A</optinon>
																<option value="cocinaTipoB" >Cocina Tipo B</optinon>
															</select>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Descuento (%):</label>
															<input  type="text" id="descuentoPorcentualEng" name="descuentoPorcentualEng" class="form-control"  onChange="setDescuento('porcentaje',this.value)">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Descuento:</label>
															<input type="text" id="descuentoPorcentualMontoEng" name="descuentoPorcentualMontoEng" class="form-control" onChange="setDescuento('monto',this.value)">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Enganche(%):</label>
															<input type="text" id="engancheEng" name="engancheEng" class="form-control" onChange="setEnganche('porcentaje',this.value)">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Enganche:</label>
															<input type="text" id="engancheMontoEng" name="engancheMontoEng" class="form-control" onChange="setEnganche('monto',this.value)">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Monto Reserva:</label>
															<input type="text" id="montoReservaEng" name="montoReservaEng" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Fecha pago Reserva:</label>
															<input type="date" id="fechaPagoReservaEng" name="fechaPagoReservaEng" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Plazo Financiamiento:</label>
															<select class="form-control" name="plazoFinanciamientoEng" id="plazoFinanciamientoEng"  onchange="">
																<option value="" >SELECCIONE</optinon>
																<option value="5" >5 años</optinon>
																<option value="10" >10 años</optinon>
																<option value="15" >15 años</optinon>
																<option value="20" >20 años</optinon>
																<option value="25" >25 años</optinon>
															</select>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Fecha Primer Pago:</label>
															<input type="date" id="fechaPagoInicialEng" name="fechaPagoInicialEng" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">No. Meses:</label>
															<input type="number" id="mesesEnganche" name="mesesEnganche" value="20" class="form-control" onChange="selectPagosEnganche(this.value,'pagosEngancheEng')">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Pagos de Enganche:</label>
															<select class="form-control" name="pagosEngancheEng" id="pagosEngancheEng"  onchange="">
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
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Nombre Contacto:</label>
															<select class="form-control" name="nombreVendedorEng" id="nombreVendedorEng"  onchange="datosVendedor(this.value,0)">
															</select>
														</div>
														<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Télefono Contacto:</label>
															<input type="text" id="telefonoVendedorEng" name="telefonoVendedorEng" class="form-control" readonly>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Email Contacto:</label>
															<input type="text" id="correoVendedorEng" name="correoVendedorEng" class="form-control" readonly>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;" >
															<button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Agregar Adjuntos</button>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Observaciones:</label>
															<textarea class="form-control" id="observacionesEng" name="observacionesEng" rows="4"></textarea>
														</div>
														<!-- <div id="divCalculoCuota" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;text-align:right;display:none" >
															<br><br><button onclick="calculoCuotas()" class="cuotas" type="button">Calcular Cuotas</button>
														</div> -->
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
															<br><br><button onclick="guardarEngancheValidar()" class="guardar" type="button">Validar</button>
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
					<?php require_once("./documentos_adjuntos.php"); ?>				
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
					var id_perfil = $("#id_perfil").val();
					var formData = new FormData(document.getElementById("frmBuscarApartamento"));
					formData.append("proyectoBscTxt", document.getElementById("proyectoBsc").options[document.getElementById("proyectoBsc").selectedIndex].text);
					formData.append("torreBscTxt", document.getElementById("torreBsc").options[document.getElementById("torreBsc").selectedIndex].text);
					formData.append("nivelBscTxt", document.getElementById("nivelBsc").options[document.getElementById("nivelBsc").selectedIndex].text);
					formData.append("apartamentoBscTxt", document.getElementById("apartamentoBsc").options[document.getElementById("apartamentoBsc").selectedIndex].text);
					$.ajax({
						url: "./cliente.php?get_apartamento_lista_validar=true",
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
							var block;
							if(id_perfil == 1){
								block='disabled="disabled"';
							}
							$.each(response.cotizaciones,function(i,e) {
								//console.log(e.user_name);
								if(e.creado=='si'){
									var check='<i class="fa fa-check-square-o"></i>';
								}else
								{
									var check="";
								}
								output += '<tr onCLick=""><td>'+e.codigo+'</td><td>'+e.client_name+' </td><td>'+e.proyecto+'</td><td>'+e.apartamento+'</td><td><button '+block+' onclick="verEngancheValidar(\''+e.id+'\')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ><button onclick="verEnganche('+e.id+')" class="btn btn-xs" type="button"><img class="" src="../img/Engagement.png" alt="ver Recibo" ></button></td></tr>';
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
								checkDisabled = e.pagado==1?'disabled="disabled':'';
								output+="<tr>";
								output+="<td >"+e.noPago+"<input id=\"noPago_"+e.noPago+"\" name=\"noPago[]\" type=\"hidden\" value=\""+e.noPago+"\" readonly=\"readonly\" ></td>";
								output+="<td><input onChange=\"pagoEspecial("+e.montoEnganche+")\" id=\"chk_"+e.noPago+"\" name=\"chk[]\" type=\"checkbox\" class=\"form-check-input\" "+checkDisabled+"> <label class=\"form-check-label\" for=\"exampleCheck1\">Especial</label></td>";
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
								getBancos('bancoChequeReservaEng',e.bancoChequeReserva);		
								$("#formaPagoEng").val(e.formaPago);
								$("#noDepositoReservaEng").val(e.noDepositoReserva);
								$("#noReciboEng").val(e.noRecibo);
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
				
				
				function guardarEngancheValidar(){
					var formDataEnganche = new FormData(document.getElementById("frmAgregarEnganche"));
					formDataEnganche.append("idEnganche", $("#idEnganche").val());
					formDataEnganche.append("proyectoCliente",$("#proyectoEnganche").val() );
					formDataEnganche.append("idCliente", $("#idEnganche").val());
					formDataEnganche.append("idOcaCliente", $("#idOcaEnganche").val());
					formDataEnganche.append("txtProyecto", document.getElementById("ProyectoEng").options[document.getElementById("ProyectoEng").selectedIndex].text);
					formDataEnganche.append("txtTorre", document.getElementById("torreEng").options[document.getElementById("torreEng").selectedIndex].text);
					formDataEnganche.append("txtNivel", document.getElementById("nivelEng").options[document.getElementById("nivelEng").selectedIndex].text);
					formDataEnganche.append("txtApartamento", document.getElementById("apartamentoEng").options[document.getElementById("apartamentoEng").selectedIndex].text);

					$.ajax({
						url: "./cliente.php?agregar_editar_formalizar_enganche_validar=true",
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
				$("#divVerAdjuntosReserva").html("</iframe> SE DESCARGO EL ENGANCHE EXITOSAMENTE...<iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./generarPdf.php/engancheNo"+idEnganche+"?idEnganche="+idEnganche+"&enganchePdf=true#page=1&zoom=100'></iframe>");							
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
									//$("#engancheEng").val(e.porcentajeEnganche);
									//$("#montoReservaEng").val(e.montoReserva);
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
								if(descuento=='si'){
									$("#engancheEng").val(e.porcentajeEnganche);
									$("#montoReservaEng").val(e.montoReserva);
									console.log("si descuento set");
									setDescuento('porcentaje', 0,5);

								}

							});
							//setDescuento('porcentaje', 0);
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
				function verEngancheValidar(idEnganche){
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
								var descuentoPorcentual=e.descuento_porcentual;
							});
							$("#modalAgregarEnganche").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							setDescuento('porcentaje', descuentoPorcentual);	
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
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
									output+="<td><input onChange=\"pagoEspecial("+totalEnganche+")\" id=\"chk_"+no+"\" name=\"chk[]\" type=\"checkbox\" class=\"form-check-input\"> <label class=\"form-check-label\" for=\"exampleCheck1\">Especial</label></td>";
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
				console.log(precio + " Precio");
				precio = parseFloat(precio.replace(/[Q,]/g,''));
				
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
				precio =parseFloat(precio.replace(/[Q,]/g,''));
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
				console.log(precio + " Precio");
				precio = parseFloat(precio.replace(/[Q,]/g,''));
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
				precio = parseFloat(precio.replace(/[Q,]/g,''));
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
						montoPagosEspeciales += parseFloat($("#cuota_"+split[1]).val())
					}else{
					}
				});
				console.log(parseFloat(total) +"-"+ parseFloat(montoPagosEspeciales));
				var nuevoTotal = parseFloat(total) - parseFloat(montoPagosEspeciales);

				//console.log(nuevoTotal);
				$("#resultadoCuotas input[type='checkbox']").each(function () {
					
					var split = this.id.split('_');
					if ($(this).is(':checked')) {
					}else{
						checked++;
					}
				});
				var cuota = nuevoTotal/parseFloat(checked);
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
			// 	//console.log("Entrando a adjuntos");
			// 	var idCliente = $("#idOcaInfo").val();
			// 	var nombreCLiente = $("#nombreClienteInfo").val();
			// 	var formData = new FormData;
			// 	formData.append("idOcaCliente", idCliente);
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
			// 				output += '<tr><td style="width:90%;"><label class="nodpitext">' + e.nombre + '</label></td><td style="width:10%;"><button onclick="eliminarAdjuntos('+ e.id_adjuntosCliente +',\''+ e.ruta + '\')" class="btn btn-sm btn-danger"  type="button"><i class="fa fa-times"></i></button> </td></tr>';
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
			// 	$("#divVerAdjuntos").html("</iframe><iframe frameborder='0' type='application/pdf' style='width:100%; height:100%' align='right' src='./adjuntos.php/"+$("#nombreClienteInfo").val()+"?idCliente="+$("#idOcaInfo").val()+"&nombreCliente="+$("#nombreClienteInfo").val()+"#page=1&zoom=50'></iframe>");	
			// }
			// function eliminarAdjuntos(id,ruta)
            // {
            //     //console.log("funcion eliminar adjunto");
            //     var formData = new FormData;
            //     formData.append("idAdjunto",id);
            //     formData.append("ruta",ruta);
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

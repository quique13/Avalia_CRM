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
$readOnly='';
$super = 0;
$vendedor=0;
if($id_perfil!=3){
	$readOnly="readonly";
	$super=1;
	$vendedor=1;
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


				<!-- DOCUMETNOS ADJUNTOS FUNCIONES -->
				<script src="../js/documentos_adjuntosFha.js"></script>
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
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/usersearchicon.png"> Consultar Cliente</label>
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
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<input type="hidden" id="esVendedor" name="esVendedor" value="<?php echo $vendedor; ?>"  >
																	<input type="hidden" id="usuarioVendedor" name="usuarioVendedor" value="<?php echo $id_usuario; ?>"  >
																	<label class="nodpitext">Proyecto:</label>
																	<select class="form-control" name="proyectoBsc" id="proyectoBsc"  onchange="torres(this.value,'torreBsc')">
																		<option value="0" >Seleccione</optinon>
																		<?php echo $proyectos; ?> 
																	</select>
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
																
																<div class="col-lg-8 col-md-8 col-xs-10" style="text-align:left;margin-bottom:10px;">
																	<label class="nodpitext"></label>
																	<input class=" search form-control" type="" id="datoBuscar" name="datoBuscar" placeholder="Nombre, correo, DPI">	
																</div>
																<div class="col-lg-12 col-md-12 col-xs-10" style="text-align:center;margin-bottom:10px;">
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
																		<th style="width:10%;">Apartamento</th>
																		<th style="width:20%;">Estado</th>
																		<th style="width:20%;">Acciones</th> 
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

					<!-- Modal -->
					<div class="modal fade" id="modalAgregarCliente">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Agregar Cliente</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarCliente" style="padding:5px 15px;" >
									<div class="secinfo" >
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarCliente" name="frmAgregarCliente" method="POST">
											<div class="row" >
												<input type="hidden" id="idCliente" name="idCliente">
												<input type="hidden" id="proyectoCliente" name="proyectoCliente">
												<input type="hidden" id="idOcaCliente" name="idOcaCliente">			
												<div id="divAlertCliente" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px">
												</div>													
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Tipo Cliente</label>
															<select class="form-control" name="tipoCliente" id="tipoCliente" onchange="fncTipoCliente()">
																<option value="individual" >Cliente Individual</optinon>
																<option value="juridico" >Cliente Juridico</optinon>
															</select>
														</div>
														
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<div class="row" >
																<div class="col-lg-12 col-md-12 col-xs-10"  >
																	<div class="row" >
																	<div class="col-lg-6 col-md-6 col-xs-10"  >
																			<label class="nodpitext">Tipo Comision</label>
																			<select class="form-control" name="tipoComision" id="tipoComision" onchange="">
																			</select>
																		</div>
																		<div class="col-lg-6 col-md-6 col-xs-10"  >
																			<label class="nodpitext">Estado Cliente</label>
																			<select class="form-control" name="estadoCl" id="estadoCl" onchange="">
																				<option value=1 >Activo</optinon>
																				<option value=0 >Inactivo</optinon>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div id="nombreSaDiv" name="nombreSaDiv" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Nombre S.A.</label>	
															<input type="text" id="nombreSa" name="nombreSa" placeHolder="" class="form-control" >
														</div>
														<div id="rtuDiv" name="rtuDiv" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">RTU</label>	
															<input type="text" id="rtu" name="rtu" placeHolder="" class="form-control" >
														</div>
														<div id="representanteLegalDiv" name="representanteLegalDiv" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Representante Legal</label>	
															<input type="text" id="representanteLegal" name="representanteLegal" placeHolder="" class="form-control" >
														</div>
														<div id="patenteEmpresaDiv" name="patenteEmpresaDiv" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Patente de Empresa</label>	
															<input type="text" id="patenteEmpresa" name="patenteEmpresa" placeHolder="" class="form-control" >
														</div>
														<div id="patenteSociedadDiv" name="patenteSociedadDiv" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Patente de Sociedad</label>	
															<input type="text" id="patenteSociedad" name="patenteSociedad" placeHolder="" class="form-control" >
														</div>
														<div id="primerNombreDiv" name="primerNombreDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Primer Nombre:</label>
															<input type="text" id="primerNombre" name="primerNombre" placeHolder="Primer Nombre" class="form-control" >
														</div>
														<div id="segundoNombreDiv" name="segundoNombreDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Segundo Nombre:</label>	
															<input type="text" id="segundoNombre" name="segundoNombre" placeHolder="Segundo Nombre" class="form-control" >
														</div>
														<div id="primerApellidoDiv" name="primerApellidoDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Primer Apellido:</label>		
															<input type="text" id="primerApellido" name="primerApellido" placeHolder="Primer apellido" class="form-control" >
														</div>
														<div id="segundoApellidoDiv" name="segundoApellidoDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Segundo Apellido:</label>		
															<input type="text" id="segundoApellido" name="segundoApellido" placeHolder="Segundo Apellido" class="form-control" >
														</div>
														<div id="tercerNombreDiv" name="tercerNombreDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Tercer Nombre:</label>		
															<input type="text" id="tercerNombre" name="tercerNombre" placeHolder="Tercer Nombre" class="form-control" >
														</div>
														<div id="apellidoCasadaDiv" name="apellidoCasadaDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
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
															<label id="lblDireccion" name="lblDireccion" class="nodpitext">Dirección Residencia:</label>
															<textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label id="lblNit" name="lblNit" class="nodpitext">Nit:</label>
															<input type="text" id="nitCl" name="nitCl" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label id="lblNumeroDpi" name="lblNumeroDpi" class="nodpitext">Número de DPI:</label>
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
															<input type="date" id="fechaEmisionDpiCl" name="fechaEmisionDpiCl" class="form-control" onChange="fechaVencimiento()">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Fecha vencimiento DPI:</label>
															<input id="fechaVencimientoDpi" name="fechaVencimientoDpi" type="date" class="form-control">
															<input id="fechaHoy" name="fechaHoy" type="hidden" class="form-control" value="<?php echo date("d/m/Y") ?>">
														</div>
														
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div id="nacionalidadDiv" name="nacionalidadDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Nacionalidad:</label>
															<select class="form-control" name="nacionalidadCl" id="nacionalidadCl" onchange="">
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label id="lblFechaNacimiento" name="lblFechaNacimiento" class="nodpitext">Fecha de nacimiento:</label>
															<input type="date" id="fechaNacimientoCl" name="fechaNacimientoCl" class="form-control">
														</div>
														<div id="estadoCivilDiv" name="estadoCivilDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
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
															<label id="lblProfesion" name="lblProfesion" class="nodpitext">Profesión:</label>
															<input type="text" id="profesionCl" name="profesionCl" class="form-control">
														</div>
														<div id="dependientesDiv" name="dependientesDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">No. de dependientes:</label>
															<input type="number" id="dependientesCl" name="dependientesCl" class="form-control">
														</div>
														<div id="fhaDiv" name="fhaDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<div class="row" >
																<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																	<label class="nodpitext">Ha tenido tramite FHA:</label>
																</div>
																<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																	<div class=" form-check form-check-inline"  style="">
																		<input class="form-check-input" type="radio" name="fha" id="si" value="si">
																		<label class="form-check-label" for="">Si</label>
																	</div>
																	<div class="form-check form-check-inline"  style="">
																		<input class="form-check-input" type="radio" name="fha" id="no" value="no">
																		<label class="form-check-label">No</label>
																	</div>
																</div>
															</div>
														</div>
														<div id="empresaLaboraDiv" name="empresaLaboraDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Empresa donde labora:</label>
															<input type="text" id="empresaLaboraCl" name="empresaLaboraCl" class="form-control">
														</div>
														<div id="direccionEmpresaDiv" name="direccionEmpresaDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Dirección de empresa:</label>
															<textarea class="form-control" id="direccionEmpresaCl" name="direccionEmpresaCl" rows="2"></textarea>
														</div>
														<div id="telefonoReferenciaDiv" name="telefonoReferenciaDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Télefono de Referencia:</label>
															<input  type="text" id="telefonoReferencia" name="telefonoReferencia" class="form-control">
														</div>
														<div id="puestoEmpresaDiv" name="puestoEmpresaDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Puesto en Empresa:</label>
															<input type="text" id="puestoEmpresaCl" name="puestoEmpresaCl" class="form-control">
														</div>
														<div id="salarioDiv" name="salarioDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Salario mensual:</label>
															<input type="text" id="salarioMensualCl" name="salarioMensualCl" class="form-control">
														</div>
														<div id="montoIngresosDiv" name="montoIngresosDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Otros ingresos:</label>
															<input type="text" id="montoOtrosIngresosCl" name="montoOtrosIngresosCl" class="form-control">
														</div>
														<div id="otrosIngresosDiv" name="otrosIngresosDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Descripción Otros ingresos:</label>
															<input type="text" id="otrosIngresosCl" name="otrosIngresosCl" class="form-control">
														</div>
														<div id="observacionesDiv" name="observacionesDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Observaciones:</label>
															<textarea class="form-control" id="observacionesCl" name="observacionesCl" rows="2"></textarea>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Tramite FHA:</label>
															<select class="form-control" name="tramiteFHACl" id="tramiteFHACl" onchange="verBtnTramiteFHA(this.value)">
																<option value=0 >No</optinon>
																<option value=1 >Si</optinon>
																
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;margin-top:25px;">
															<label class="nodpitext"> </label>
															<button onclick="verTramiteFHA()" class="adjuntosFHA" type="button" id="btnTramiteFHA" name="btnTramiteFHA">Adjuntos FHA</button>
														</div>
														<script type="text/javascript">
															$("#salarioMensualCl").number( true, 2 );
															$("#montoOtrosIngresosCl").number( true, 2 );
														</script>
													</div>
													
													
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
													<button onclick="guardarCliente()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
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
					<!-- MODAL DOCUMENTOS ADJUNTOS -->
					<?php require_once("./documentos_adjuntos_fha.php"); ?>
				</div>
			</div>
		</div>
			<script type="text/javascript">
				//agregarCliente()
				function fncTipoCliente(){
					var radios = document.getElementsByName('tipoCliente');
					
					if($("#tipoCliente").val()=='juridico'){
						document.querySelector('#lblDireccion').innerText = 'Dirección Fiscal S.A:';
						document.querySelector('#lblNumeroDpi').innerText = 'Dpi Representante legal:';
						document.querySelector('#lblNit').innerText = 'Nit S.A:';
						document.querySelector('#lblFechaNacimiento').innerText = 'Fecha de constitución:';
						document.querySelector('#lblProfesion').innerText = 'Cargo:';
						document.getElementById("nombreSaDiv").style.display = "";
						document.getElementById("rtuDiv").style.display = "";
						document.getElementById("representanteLegalDiv").style.display = "";
						document.getElementById("patenteEmpresaDiv").style.display = "";
						document.getElementById("patenteSociedadDiv").style.display = "";
						document.getElementById("primerNombreDiv").style.display = "none";
						document.getElementById("segundoNombreDiv").style.display = "none";
						document.getElementById("tercerNombreDiv").style.display = "none";
						document.getElementById("primerApellidoDiv").style.display = "none";
						document.getElementById("segundoApellidoDiv").style.display = "none";
						document.getElementById("apellidoCasadaDiv").style.display = "none";

						document.getElementById("nacionalidadDiv").style.display = "none";
						document.getElementById("estadoCivilDiv").style.display = "none";
						document.getElementById("dependientesDiv").style.display = "none";
						document.getElementById("fhaDiv").style.display = "none";
						document.getElementById("empresaLaboraDiv").style.display = "none";
						document.getElementById("direccionEmpresaDiv").style.display = "none";
						document.getElementById("telefonoReferenciaDiv").style.display = "none";
						document.getElementById("puestoEmpresaDiv").style.display = "none";
						document.getElementById("salarioDiv").style.display = "none";
						document.getElementById("montoIngresosDiv").style.display = "none";
						document.getElementById("otrosIngresosDiv").style.display = "none";
					}else if($("#tipoCliente").val()=='individual'){
						document.querySelector('#lblDireccion').innerText = 'Dirección Residencia';
						document.querySelector('#lblNumeroDpi').innerText = 'Número de DPI :';
						document.querySelector('#lblNit').innerText = 'Nit:';
						document.querySelector('#lblFechaNacimiento').innerText = 'Fecha de nacimiento:';
						document.querySelector('#lblProfesion').innerText = 'Profesión:';
						document.getElementById("nombreSaDiv").style.display = "none";
						document.getElementById("rtuDiv").style.display = "none";
						document.getElementById("representanteLegalDiv").style.display = "none";
						document.getElementById("patenteEmpresaDiv").style.display = "none";
						document.getElementById("patenteSociedadDiv").style.display = "none";
						document.getElementById("primerNombreDiv").style.display = "";
						document.getElementById("segundoNombreDiv").style.display = "";
						document.getElementById("tercerNombreDiv").style.display = "";
						document.getElementById("primerApellidoDiv").style.display = "";
						document.getElementById("segundoApellidoDiv").style.display = "";
						document.getElementById("apellidoCasadaDiv").style.display = "";
						document.getElementById("nacionalidadDiv").style.display = "";
						document.getElementById("estadoCivilDiv").style.display = "";
						document.getElementById("dependientesDiv").style.display = "";
						document.getElementById("fhaDiv").style.display = "";
						document.getElementById("empresaLaboraDiv").style.display = "";
						document.getElementById("direccionEmpresaDiv").style.display = "";
						document.getElementById("telefonoReferenciaDiv").style.display = "";
						document.getElementById("puestoEmpresaDiv").style.display = "";
						document.getElementById("salarioDiv").style.display = "";
						document.getElementById("montoIngresosDiv").style.display = "";
						document.getElementById("otrosIngresosDiv").style.display = "";
					}
				}
				function buscarCliente(){
					//console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarCliente"));
					formData.append("proyectoBscTxt", document.getElementById("proyectoBsc").options[document.getElementById("proyectoBsc").selectedIndex].text);
					// formData.append("torreBscTxt", document.getElementById("torreBsc").options[document.getElementById("torreBsc").selectedIndex].text);
					// formData.append("nivelBscTxt", document.getElementById("nivelBsc").options[document.getElementById("nivelBsc").selectedIndex].text);
					// formData.append("apartamentoBscTxt", document.getElementById("apartamentoBsc").options[document.getElementById("apartamentoBsc").selectedIndex].text);
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
								if(e.estado==0){
									var estado = 'Desestimiento';
								}else{
									var estado = 'Activo';
								}
								output += '<tr onCLick=""><td>'+e.codigo+'</td><td>'+e.client_name+' '+check+'</td><td>'+e.apartamentoEnganche+' </td><td>'+estado+' </td><td><button onclick="buscarClienteUnico(\''+e.id+'\',\'\',\'\')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ></td></tr>';
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
				function verTramiteFHA(){
					if($("#idCliente").val()!=''){					
						verAdjuntos($("#idCliente").val(),0);
						getFiltroAdjuntos($("#idCliente").val(),0);
					}else{
						alert("El cliente aún no se ha guardado");
					}

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
				function getTipoComision(input,valueInput){
					//console.log("funcion buscar niveles");	
					var formData = new FormData;				
					$.ajax({
						url: "./cliente.php?get_tipo_comision=true",
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
							$.each(response.tipoComision,function(i,e) {
								if(valueInput==e.tipo){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.tipo+'">'+e.tipo+'</option>';
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
				function getNacionalidad(input,valueInput){
					//console.log("funcion buscar niveles");
					var formData = new FormData;
					$.ajax({
						url: "./cliente.php?get_nacionalidad=true",
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
							$.each(response.nacionalidades,function(i,e) {
								if(valueInput==e.id_pais){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.id_pais+'">'+e.pais+'</option>';
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
				function getMunicipios(depto,input,valueInput){
					//console.log("funcion buscar niveles");
					var formData = new FormData;
					formData.append("depto", depto);
					
					$.ajax({
						url: "./cliente.php?get_municipios=true",
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
							$.each(response.municipios,function(i,e) {
								if(valueInput==e.id_muni){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.id_muni+'">'+e.nombre_muni+'</option>';
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
				function guardarCliente(){
					//console.log("funcion guardar cliente");
					var error = 0;
					// var msjError ='Campos Obligatorios: <br>';
					// if($("#ProyectoCl").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Proyecto <br>'
					// }
					// if($("#nivelCl").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Nivel <br>'
					// }
					// if($("#apartamentoCl").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Apartamento <br>'
					// }
					// if($("#correo").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Correo eléctronico <br>'
					// }else{
					// 	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
					// 	if(!emailReg.test($("#correo").val())){
					// 		error++;
					// 		msjError =msjError+ '*Correo electrónico invalido <br>';
					// 		//return false;	
					// 	}
					// }
					// if($("#telefono").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Télefono <br>'
					// }
					// if($("#direccion").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Dirección <br>'
					// }
					// if($("#numeroDpi").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Número de DPI <br>'
					// }else{
					// 	var cui = $("#numeroDpi").val();
					// 	////console.log('CUI: '+ cui);
					// 	var cuiRegExp = /^[0-9]{4}\s?[0-9]{5}\s?[0-9]{4}$/;

					// 	if (!cuiRegExp.test(cui)) {
					// 		//console.log("CUI con formato inválido");
					// 		//callback('CUI con formato inválido');
					// 		error++;
					// 		msjError =msjError+ '*Número de DPI inválido <br>';
					// 		//return false;
					// 	}

					// 	cui = cui.replace(/\s/, '');
					// 	var depto = parseInt(cui.substring(9, 11), 10);
					// 	var muni = parseInt(cui.substring(11, 13));
					// 	var numero = cui.substring(0, 8);
					// 	var verificador = parseInt(cui.substring(8, 9));
					// 	// //console.log('depto: '+depto);
					// 	// //console.log('muni: '+muni);
					// 	// //console.log('numero: '+numero);
					// 	// //console.log('verificador: '+verificador);
					// 	// Se asume que la codificación de Municipios y
					// 	// departamentos es la misma que esta publicada en
					// 	// http://goo.gl/EsxN1a

					// 	// Listado de municipios actualizado segun:
					// 	// http://goo.gl/QLNglm

					// 	// Este listado contiene la cantidad de municipios
					// 	// existentes en cada departamento para poder
					// 	// determinar el código máximo aceptado por cada
					// 	// uno de los departamentos.
					// 	var munisPorDepto = [
					// 		/* 01 - Guatemala tiene:      */ 17 /* municipios. */,
					// 		/* 02 - El Progreso tiene:    */  8 /* municipios. */,
					// 		/* 03 - Sacatepéquez tiene:   */ 16 /* municipios. */,
					// 		/* 04 - Chimaltenango tiene:  */ 16 /* municipios. */,
					// 		/* 05 - Escuintla tiene:      */ 13 /* municipios. */,
					// 		/* 06 - Santa Rosa tiene:     */ 14 /* municipios. */,
					// 		/* 07 - Sololá tiene:         */ 19 /* municipios. */,
					// 		/* 08 - Totonicapán tiene:    */  8 /* municipios. */,
					// 		/* 09 - Quetzaltenango tiene: */ 24 /* municipios. */,
					// 		/* 10 - Suchitepéquez tiene:  */ 21 /* municipios. */,
					// 		/* 11 - Retalhuleu tiene:     */  9 /* municipios. */,
					// 		/* 12 - San Marcos tiene:     */ 30 /* municipios. */,
					// 		/* 13 - Huehuetenango tiene:  */ 32 /* municipios. */,
					// 		/* 14 - Quiché tiene:         */ 21 /* municipios. */,
					// 		/* 15 - Baja Verapaz tiene:   */  8 /* municipios. */,
					// 		/* 16 - Alta Verapaz tiene:   */ 17 /* municipios. */,
					// 		/* 17 - Petén tiene:          */ 14 /* municipios. */,
					// 		/* 18 - Izabal tiene:         */  5 /* municipios. */,
					// 		/* 19 - Zacapa tiene:         */ 11 /* municipios. */,
					// 		/* 20 - Chiquimula tiene:     */ 11 /* municipios. */,
					// 		/* 21 - Jalapa tiene:         */  7 /* municipios. */,
					// 		/* 22 - Jutiapa tiene:        */ 17 /* municipios. */
					// 	];

					// 	if (depto === 0 || muni === 0)
					// 	{
					// 		//console.log("CUI con código de municipio o departamento inválido.");
					// 		//callback("CUI con código de municipio o departamento inválido.");
					// 		error++;
					// 		msjError =msjError+ '*Número de DPI con código de municipio o departamento inválido. <br>';
					// 		//return false;
					// 	}

					// 	if (depto > munisPorDepto.length)
					// 	{
					// 		//console.log("CUI con código de departamento inválido.");
					// 		//callback("CUI con código de departamento inválido.");
					// 		error++;
					// 		msjError =msjError+ '*Número de DPI con código de departamento inválido. <br>';
					// 		//return false;
					// 	}

					// 	if (muni > munisPorDepto[depto -1])
					// 	{
					// 		//console.log("CUI con código de municipio inválido.");
					// 		//callback("CUI con código de municipio inválido.");
					// 		error++;
					// 		msjError =msjError+ '*Número de DPI con código de municipio inválido. <br>';
					// 		//return false;
					// 	}
						
					// 	// Se verifica el correlativo con base
					// 	// en el algoritmo del complemento 11.
					// 	var total = 0;

					// 	for (var i = 0; i < numero.length; i++)
					// 	{
					// 		total += numero[i] * (i + 2);
					// 	}
					// 	var modulo = (total % 11);
					// 	////console.log("CUI con módulo: " + modulo);
					// 	if(modulo === verificador){
					// 		////console.log(modulo +'==='+ verificador);
					// 	}else{
					// 		error++;
					// 		msjError =msjError+ '*Numeración no valida de DPI <br>';
					// 	}
					// 	if (/\s/.test(cui) || cui.includes("-")) {
					// 		//callback('No se aceptan espacios ni guiones.');
					// 		error++;
					// 		msjError =msjError+ '*Número de DPI No se aceptan espacios ni guiones. <br>';
					// 	}						
					// }
					// if($("#nitCl").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*NIT <br>'
					// }else{
					// 	var nit = $("#nitCl").val();
					// 	//console.log("NIT validación");
					// 	var nitRegExp = new RegExp('^[0-9]+(-?[0-9kK])?$');
					// 	if (!nitRegExp.test(nit)) {
					// 		////console.log("NIT inválido");
					// 		//callback('CUI con formato inválido');
					// 		error++;
					// 		msjError =msjError+ '*NIT inválido <br>';
					// 		//return false;
					// 	}
					// 	nit = nit.replace(/-/, '');
					// 	var lastChar = nit.length - 1;
					// 	var number = nit.substring(0, lastChar);
					// 	var expectedCheker = nit.substring(lastChar, lastChar + 1).toLowerCase();
					// 	var factor = number.length + 1;
					// 	var total = 0;

					// 	for (var i = 0; i < number.length; i++) {
					// 		var character = number.substring(i, i + 1);
					// 		var digit = parseInt(character, 10);

					// 		total += (digit * factor);
					// 		factor = factor - 1;
					// 	}
					// 	var modulus = (11 - (total % 11)) % 11;
					// 	var computedChecker = (modulus == 10 ? "k" : modulus.toString());

					// 	if(expectedCheker === computedChecker){
					// 		////console.log(expectedCheker+'==='+computedChecker) ;
					// 	}else{
					// 		error++;
					// 		msjError =msjError+ '*NIT inválido <br>';
					// 	}
					// }
	
					// if($("#fechaVencimientoDpi").val()==''){
					// 	error++;
					// 	msjError =msjError+ '*Fecha de Vencimiento DPI <br>'
					// }else{
						
					// 	var fecha_mayor = $("#fechaVencimientoDpi").val();
					// 	var partes_fecha_mayor = fecha_mayor.split('/');
					// 	var fecha_mayor_numero= new Date (partes_fecha_mayor[2]+'/'+partes_fecha_mayor[1]+'/'+partes_fecha_mayor[0]).setHours(0,0,0,0);
					// 	//console.log('fecha mayor' +fecha_mayor_numero);

					// 	var fecha_menor = $("#fechaHoy").val();
					// 	var partes_fecha_menor = fecha_menor.split('/');
					// 	var fecha_menor_numero= new Date (partes_fecha_menor[2]+'/'+partes_fecha_menor[1]+'/'+partes_fecha_menor[0]).setHours(0,0,0,0);
					// 	//console.log('fecha menor '+fecha_menor_numero);
					// 	if(fecha_mayor_numero.valueOf()<=fecha_menor_numero.valueOf()){
					// 		error++;
					// 		msjError =msjError+ '*Fecha de Vencimiento DPI No puede ser menor a fecha actual <br>'
					// 	}
					// }
					
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
								$("#modal_confirm").modal({
									backdrop: 'static',
									keyboard: false,
									show: true
								});
								if (response.err == true) {
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
								}
								else {
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarCliente();buscarClienteUnico('+response.id+',\'\',\'\');">Aceptar</div>');
								}	
								//buscarCliente();
								//buscarClienteUnico(response.id,response.proyecto,response.clientName);						
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarCliente').animate({scrollTop:0}, 'fast');
						$("#divAlertCliente").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertCliente").html('');
							},5000)
					}
					
				}
				function buscarClienteUnico(idCotizacion,proyecto,clientName){
					//console.log("funcion buscar cliente unico");
					//var formData = new FormData(document.getElementById("frmBuscarCliente"));
					var formData = new FormData;
					formData.append("idCotizacion", idCotizacion);
					formData.append("proyectoCliente", proyecto);
					formData.append("clientName", clientName);
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
							var ul='<ul class="nav nav-tabs" role="tablist">';
							var div='';
							var active = 'active';
							var count = 0;
							$.each(response.info,function(i,e) {
								//console.log(e.client_name);
								//Info Apartamento, Vendedor y CLiente
								
								//console.log("funcion buscar cliente");
								//var formData = new FormData(document.getElementById("frmBuscarCliente"));
								$("#idCliente").val(e.idCliente);
								$("#idOcaCliente").val(e.idCliente);
								getDepartamentos(1,'depto',e.departamento);
								getTipoComision('tipoComision',e.tipoComision);
								getMunicipios(e.departamento,'municipio',e.municipio);
								getNacionalidad('nacionalidadCl',e.Nacionalidad);

								var nombreCompleto=e.client_name;
								console.log(nombreCompleto);
								var nombreSeparado = nombreCompleto.split(" ");
								$("#primerNombre").val(e.primerNombre);
								$("#segundoNombre").val(e.segundoNombre);
								$("#primerApellido").val(e.primerApellido);
								$("#segundoApellido").val(e.segundoApellido);
								$("#tercerNombre").val(e.tercerNombre);
								$("#apellidoCasada").val(e.apellidoCasada);
								$("#correo").val(e.client_mail);
								$("#telefono").val(e.telefono);
								$("#direccion").val(e.direccion);
								$("#numeroDpi").val(e.numeroDpi);
								$("#fechaVencimientoDpi").val(e.fechaVencimientoDpi);
								$("#telefonoFijo").val(e.telefonoFijo);
								$("#nitCl").val(e.nit);
								$("#dpiClienteInfo").val(e.numeroDpi);
								$("#vencimientoDpiClienteInfo").val(e.fechaVencimientoDpi);
								$("#direccionClienteInfo").val(e.direccion);
								$("#fechaNacimientoCl").val(e.fechaNacimiento);
								$("#estadoCivilCl").val(e.estadoCivil);
								$("#profesionCl").val(e.profesion);
								$("#dependientesCl").val(e.noDependientes);
								$("#empresaLaboraCl").val(e.empresaLabora);
								$("#direccionEmpresaCl").val(e.direccionEmpresa);
								$("#observacionesCl").val(e.observaciones_cliente);
								$("#tramiteFHACl").val(e.trabajarFHA);
								if(e.trabajarFHA==1){
									document.getElementById("btnTramiteFHA").style.visibility = "visible"; // show
								}else if(e.trabajarFHA==0){
									document.getElementById("btnTramiteFHA").style.visibility = "hidden"; // hide
								}
								
								
								$("#telefonoReferencia").val(e.telefonoReferencia);
								$("#puestoEmpresaCl").val(e.puestoLabora);
								$("#salarioMensualCl").val(e.salarioMensual);
								$("#otrosIngresosCl").val(e.otrosIngresos);
								$("#montoOtrosIngresosCl").val(e.montoOtrosIngresos);
								$("#nombreSa").val(e.nombre_sa);
								$("#estadoCl").val(e.estado);
								$("#rtu").val(e.rtu);
								$("#representanteLegal").val(e.representanteLegal);
								$("#patenteEmpresa").val(e.patenteEmpresa);
								$("#patenteSociedad").val(e.patenteSociedad);
								$("#tipoCLiente").val(e.tipoCliente);
								
								if(e.creditoFHA=='si'){
									document.getElementById('si').checked = true;
									console.log("credito fha "+e.creditoFHA);
								}else if(e.creditoFHA=='no'){
									document.getElementById('no').checked = true;
									console.log("credito fha "+e.creditoFHA);
								}
								$("#fechaEmisionDpiCl").val(e.fechaEmisionDpi);	
							});
							//document.getElementById("btnEnganche").disabled = false;
							$("#modalAgregarCliente").modal({
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
				function verBtnTramiteFHA(val){
					console.log(val);
					if(val==1){
						document.getElementById("btnTramiteFHA").style.visibility = "visible"; // show
					}else if(val==0){
						document.getElementById("btnTramiteFHA").style.visibility = "hidden"; // hide
					}
				}
				function agregarCliente(){
					getDepartamentos(1,'depto',0);
					getTipoComision('tipoComision',0);
					getMunicipios(0,'municipio',0);
					getNacionalidad('nacionalidadCl',0)
					document.getElementById("frmAgregarCliente").reset();
					$("#modalAgregarCliente").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					//console.log("funcion buscar cliente");
					//var formData = new FormData(document.getElementById("frmBuscarCliente"));
					$("#proyectoCliente").val("");
					$("#idCliente").val(0);
					$("#idOcaCliente").val(0);			
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
			function fechaVencimiento(){
				var fecha_emision = $("#fechaEmisionDpiCl").val();
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
				$("#fechaVencimientoDpi").val(res);
			}
			</script>
    </body>
</html>

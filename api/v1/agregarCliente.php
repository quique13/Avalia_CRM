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

		<!-- DOCUMETNOS ADJUNTOS -->
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
																	<input type="hidden" id="idPerfil" name="idPerfil" value="<?php echo $id_perfil; ?>"  >
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
										<ul class="nav nav-tabs" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" data-toggle="tab" href="#cliente">Info. Cliente</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#fhaCliente">Info. General Cliente FHA</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#fhaRelacionDependencia">Info. de Ingresos Relación de Dependencia FHA</a>
											</li>
										</ul>
										<div class="tab-content" id="renderDatos" name="renderDatos">		
											<div id="cliente" class="tab-pane active">
												<div class="secinfo" >
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarCliente" name="frmAgregarCliente" method="POST">
														<input type="hidden" id="idCliente" name="idCliente">
														<input type="hidden" id="proyectoCliente" name="proyectoCliente">
														<input type="hidden" id="idOcaCliente" name="idOcaCliente">
														<div class="row" >	
															<div id="divAlertCliente" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px"></div>
															<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
																<div class="row" >
																	<div class="col-lg-12 col-md-12 col-xs-10">
																		<label class="nodpitext">Tipo Cliente:</label>			
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																		<div class="row" >
																			<div class="col-lg-12 col-md-12 col-xs-10">
																				<div class="row" >
																					<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
																						<div class=" form-check form-check-inline"  >
																							<input onClick="fncTipoCliente()" class="form-check-input" type="radio" name="tipoCliente" id="individual" value="individual" checked>
																							<label class="form-check-label" for="">Cliente Individual</label>
																						</div>
																						<div class="form-check form-check-inline">
																							<input onClick="fncTipoCliente()" class="form-check-input" type="radio" name="tipoCliente" id="juridico" value="juridico">
																							<label class="form-check-label">Cliente Juridico</label>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																		<div class="row" >
																			<div class="col-lg-12 col-md-12 col-xs-10" style="" >
																				<div class="row" >
																				<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																						<label class="nodpitext">Tipo Comision</label>
																						<select class="form-control" name="tipoComision" id="tipoComision" onchange="">
																						</select>
																					</div>
																					<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
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
																		<label class="nodpitext">Télefono Fijo Residencia:</label>
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
																		<label class="nodpitext">Télefono FIjo de Trabajo:</label>
																		<input  type="text" id="telefonoReferencia" name="telefonoReferencia" class="form-control">
																	</div>
																	<div id="puestoEmpresaDiv" name="puestoEmpresaDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
																		<label class="nodpitext">Puesto en Empresa:</label>
																		<input type="text" id="puestoEmpresaCl" name="puestoEmpresaCl" class="form-control">
																	</div>
																	<!-- <div id="salarioDiv" name="salarioDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
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
																	</div> -->
																	<div id="observacionesDiv" name="observacionesDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																		<label class="nodpitext">Observaciones:</label>
																		<textarea class="form-control" id="observacionesCl" name="observacionesCl" rows="2"></textarea>
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
											<div id="fhaCliente" class="tab-pane">
												<div class="secinfo" >
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarClienteFha" name="frmAgregarClienteFha" method="POST">
														<div class="row" >
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<div class = "row">
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Estatura(Cms):</label>
																		<input type="text" id="Estatura" name="Estatura" placeHolder="Estatura" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Peso(Lbs):</label>	
																		<input type="text" id="peso" name="peso" placeHolder="Peso" class="form-control" >
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Activo</label>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Caja:</label>
																		<input type="text" id="caja" name="caja" placeHolder="Caja" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Bancos:</label>
																		<input type="text" id="bancos" name="bancos" placeHolder="Bancos" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Ctas por Cobrar:</label>
																		<input type="text" id="cuentas_cobrar" name="cuentas_cobrar" placeHolder="Ctas por Cobrar" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Terrenos:</label>
																		<input type="text" id="terrenos" name="terrenos" placeHolder="Terrenos" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Viviendas:</label>
																		<input type="text" id="viviendas" name="viviendas" placeHolder="Viviendas" class="form-control"  readonly>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Vehículos:</label>
																		<input type="text" id="vehiculos" name="vehiculos" placeHolder="Vehículos" class="form-control" readonly>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Inversiones:</label>
																		<input type="text" id="inversiones" name="inversiones" placeHolder="Inversiones" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Bonos:</label>
																		<input type="text" id="bonos" name="bonos" placeHolder="Bonos" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Acciones:</label>
																		<input type="text" id="acciones" name="acciones" placeHolder="Acciones" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Muebles:</label>
																		<input type="text" id="muebles" name="muebles" placeHolder="Muebles" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Total Activos:</label>
																		<input type="text" id="total_activos" name="total_activos"  class="form-control" readonly>
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Pasivo</label>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Ctas por Pagar corto plazo:</label>
																		<input type="text" id="cuentas_pagar_corto_plazo" name="cuentas_pagar_corto_plazo" placeHolder="Ctas por Pagar corto Plazo" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Ctas por Pagar largo plazo:</label>
																		<input type="text" id="cuentas_pagar_largo_plazo" name="cuentas_pagar_largo_plazo" placeHolder="Ctas por Pagar largo Plazo" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Prestamos Hipotecarios:</label>
																		<input type="text" id="prestamos_hipotecarios" name="prestamos_hipotecarios" placeHolder="Prestamos Hipotecarios" class="form-control"  readonly>
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Gastos mensuales</label>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Sostenimiento del Hogar:</label>
																		<input type="text" id="sostenimiento_hogar" name="sostenimiento_hogar" placeHolder="Sostenimiento del Hogar" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Alquiler:</label>
																		<input type="text" id="alquiler" name="alquiler" placeHolder="Alquiler" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Préstamos:</label>
																		<input type="text" id="prestamos" name="prestamos" placeHolder="Préstamos" class="form-control" readonly>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Impuestos:</label>
																		<input type="text" id="impuestos" name="impuestos" placeHolder="Impuestos" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Extrafinanciamientos TC:</label>
																		<input type="text" id="extrafinanciamientos" name="extrafinanciamientos" placeHolder="Extrafinanciamientos TC" class="form-control" readonly>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Deudas Particulares:</label>
																		<input type="text" id="deudas_particulares" name="deudas_particulares" placeHolder="Deudas Particulares" class="form-control" onChange="sumaTotal()">
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Total Pasivos:</label>
																		<input type="text" id="total_pasivos" name="total_pasivos"  class="form-control" readonly>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Patrimonio:</label>
																		<input type="text" id="total_patrimonio" name="total_patrimonio"  class="form-control" readonly>
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Detalle Patrimonial</label>
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Bienes Inmuebles</label>
																	</div>
																	<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Dirección del Inmueble:</label>
																		<textarea class="form-control" id="direccion_inmueble_1" name="direccion_inmueble_1" rows="1"></textarea>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Valor inmueble:</label>
																		<input type="text" id="valor_inmueble_1" name="valor_inmueble_1" class="form-control" onchange="sumaValor()">
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Finca:</label>
																		<input type="text" id="finca_1" name="finca_1" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Folio:</label>
																		<input type="text" id="folio_1" name="folio_1" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Libro:</label>
																		<input type="text" id="libro_1" name="libro_1" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Departamento:</label>
																		<select class="form-control" name="departamento_1" id="departamento_1">
																		</select>
																	</div>
																	
																	<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Dirección del Inmueble:</label>
																		<textarea class="form-control" id="direccion_inmueble_2" name="direccion_inmueble_2" rows="1"></textarea>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Valor inmueble:</label>
																		<input type="text" id="valor_inmueble_2" name="valor_inmueble_2" class="form-control" onchange="sumaValor()">
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Finca:</label>
																		<input type="text" id="finca_2" name="finca_2" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Folio:</label>
																		<input type="text" id="folio_2" name="folio_2" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Libro:</label>
																		<input type="text" id="libro_2" name="libro_2" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Departamento:</label>
																		<select class="form-control" name="departamento_2" id="departamento_2">
																		</select>
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Vehículos</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Marca:</label>
																		<select class="form-control" name="marca_1" id="marca_1"  onchange="getTipo(this.value,'tipo_vehiculo_1')">
																			<option value="0" >Seleccione</optinon>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">tipo:</label>
																		<select class="form-control" name="tipo_vehiculo_1" id="tipo_vehiculo_1"  onchange="getModelo(1,this.value,'modelo_vehiculo_1')">
																			<option value="0" >Seleccione</optinon>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Modelo:</label>
																		<select class="form-control" name="modelo_vehiculo_1" id="modelo_vehiculo_1">
																			<option value="0" >Seleccione</optinon>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Valor Estimado:</label>
																		<input type="text" id="valor_estimado_1" name="valor_estimado_1" class="form-control" onchange="sumaValor()">
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Marca:</label>
																		<select class="form-control" name="marca_2" id="marca_2"  onchange="getTipo(this.value,'tipo_vehiculo_2')">
																			<option value="0" >Seleccione</optinon>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">tipo:</label>
																		<select class="form-control" name="tipo_vehiculo_2" id="tipo_vehiculo_2"  onchange="getModelo(2,this.value,'modelo_vehiculo_2')">
																			<option value="0" >Seleccione</optinon>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Modelo:</label>
																		<select class="form-control" name="modelo_vehiculo_2" id="modelo_vehiculo_2">
																			<option value="0" >Seleccione</optinon>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Valor Estimado:</label>
																		<input type="text" id="valor_estimado_2" name="valor_estimado_2" class="form-control" onchange="sumaValor()">
																	</div>
																</div>
																<script type="text/javascript">
																	$("#caja").number( true, 2 );
																	$("#bancos").number( true, 2 );
																	$("#cuentas_cobrar").number( true, 2 );
																	$("#terrenos").number( true, 2 );
																	$("#viviendas").number( true, 2 );
																	$("#vehiculos").number( true, 2 );
																	$("#inversiones").number( true, 2 );
																	$("#bonos").number( true, 2 );
																	$("#acciones").number( true, 2 );
																	$("#muebles").number( true, 2 );
																	$("#cuentas_pagar_corto_plazo").number( true, 2 );
																	$("#cuentas_pagar_largo_plazo").number( true, 2 );
																	$("#prestamos_hipotecarios").number( true, 2 );
																	$("#sostenimiento_hogar").number( true, 2 );
																	$("#alquiler").number( true, 2 );
																	$("#prestamos").number( true, 2 );
																	$("#impuestos").number( true, 2 );
																	$("#extrafinanciamientos").number( true, 2 );
																	$("#deudas_particulares").number( true, 2 );
																	$("#valor_inmueble_1").number( true, 2 );
																	$("#valor_inmueble_2").number( true, 2 );
																	$("#valor_estimado_1").number( true, 2 );
																	$("#valor_estimado_2").number( true, 2 );
																	$("#total_activos").number( true, 2 );
																	$("#total_pasivos").number( true, 2 );
																	$("#total_patrimonio").number( true, 2 );
																</script>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
																<button onclick="guardarClienteFHA()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
															</div>
														</div>
													</form>
												</div>		
											</div>
											<div id="fhaRelacionDependencia" class="tab-pane">
												<div class="secinfo" >
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarClienteFhaDependencia" name="frmAgregarClienteFhaDependencia" method="POST">
														<div class="row" >
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<div class = "row">
																	<div class="col-lg-6 col-md-6 col-xs-10">
																		<div class="row" >
																			<div class="col-lg-12 col-md-12 col-xs-10">
																				<label class="nodpitext">Tipo Contrato:</label>			
																			</div>
																			<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
																				<div class=" form-check form-check-inline"  >
																					<input class="form-check-input" type="radio" name="tipoContrato" id="indefinido" value="indefinido" checked>
																					<label class="form-check-label" for="">Indefinido</label>
																				</div>
																				<div class="form-check form-check-inline">
																					<input class="form-check-input" type="radio" name="tipoContrato" id="definido" value="definido">
																				<label class="form-check-label">Vigencia Definida</label>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
																		<label class="nodpitext">Vigencia Vence:</label>
																		<input type="date" id="vigencia_vence" name="vigencia_vence" class="form-control">
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Detalle de Ingresos Mensuales</label>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Salario Nominal:</label>
																		<input type="text" id="salario_nominal" name="salario_nominal" placeHolder="Salario Nominal" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Aguinaldo (1/12):</label>
																		<input type="text" id="bono_catorce" name="bono_catorce" placeHolder="Bono 14" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Aguinaldo (1/12):</label>
																		<input type="text" id="aguinaldo" name="aguinaldo" placeHolder="Aguinaldo" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Honorarios:</label>
																		<input type="text" id="honorarios" name="honorarios" placeHolder="Honorarios" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Otros:</label>
																		<input type="text" id="otros_ingresos_fha" name="otros_ingresos_fha" placeHolder="Otros" class="form-control" >
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Detalle de descuentos Mensuales</label>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">IGSS:</label>
																		<input type="text" id="igss" name="igss" placeHolder="Igss" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">ISR:</label>
																		<input type="text" id="isr" name="isr" placeHolder="Isr" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Plan de Pensiones:</label>
																		<input type="text" id="plan_pensiones" name="plan_pensiones" placeHolder="Plan Pensiones" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Judiciales:</label>
																		<input type="text" id="judiciales" name="judiciales" placeHolder="Judiciales" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Otros:</label>
																		<input type="text" id="otros_descuentos_fha" name="otros_descuentos_fha" placeHolder="Otros" class="form-control" >
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Detalle de horas extras, comisiones y Bonificaciones últimos 6 meses</label>
																	</div>
																	<!-- <div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">- Mes</label>
																	</div> -->
																	<!-- <div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Mes 1:</label>
																		<input type="text" id="mes_1" name="mes_1" placeHolder="Mes 1" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Mes 2:</label>
																		<input type="text" id="mes_2" name="mes_2" placeHolder="Mes 2" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Mes 3:</label>
																		<input type="text" id="mes_3" name="mes_3" placeHolder="Mes 3" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Mes 4:</label>
																		<input type="text" id="mes_4" name="mes_4" placeHolder="Mes 4" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Mes 5:</label>
																		<input type="text" id="mes_5" name="mes_5" placeHolder="Mes 5" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Mes 6:</label>
																		<input type="text" id="mes_6" name="mes_6" placeHolder="Mes 6" class="form-control" >
																	</div> -->
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">- Horas Extras</label>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Hora extra Mes 1:</label>
																		<input type="text" id="hora_extra_mes_1" name="hora_extra_mes_1" placeHolder="Mes 1" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Hora extra Mes 2:</label>
																		<input type="text" id="hora_extra_mes_2" name="hora_extra_mes_2" placeHolder="Mes 2" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Hora extra Mes 3:</label>
																		<input type="text" id="hora_extra_mes_3" name="hora_extra_mes_3" placeHolder="Mes 3" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Hora extra Mes 4:</label>
																		<input type="text" id="hora_extra_mes_4" name="hora_extra_mes_4" placeHolder="Mes 4" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Hora extra Mes 5:</label>
																		<input type="text" id="hora_extra_mes_5" name="hora_extra_mes_5" placeHolder="Mes 5" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Hora extra Mes 6:</label>
																		<input type="text" id="hora_extra_mes_6" name="hora_extra_mes_6" placeHolder="Mes 6" class="form-control" >
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">- Comisiones</label>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Comisiones Mes 1:</label>
																		<input type="text" id="comisiones_mes_1" name="comisiones_mes_1" placeHolder="Mes 1" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Comisiones Mes 2:</label>
																		<input type="text" id="comisiones_mes_2" name="comisiones_mes_2" placeHolder="Mes 2" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Comisiones Mes 3:</label>
																		<input type="text" id="comisiones_mes_3" name="comisiones_mes_3" placeHolder="Mes 3" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Comisiones Mes 4:</label>
																		<input type="text" id="comisiones_mes_4" name="comisiones_mes_4" placeHolder="Mes 4" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Comisiones Mes 5:</label>
																		<input type="text" id="comisiones_mes_5" name="comisiones_mes_5" placeHolder="Mes 5" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Comisiones Mes 6:</label>
																		<input type="text" id="comisiones_mes_6" name="comisiones_mes_6" placeHolder="Mes 6" class="form-control" >
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">- Bonificaciones</label>
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Bonificaciones Mes 1:</label>
																		<input type="text" id="bonificaciones_mes_1" name="bonificaciones_mes_1" placeHolder="Mes 1" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Bonificaciones Mes 2:</label>
																		<input type="text" id="bonificaciones_mes_2" name="bonificaciones_mes_2" placeHolder="Mes 2" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Bonificaciones Mes 3:</label>
																		<input type="text" id="bonificaciones_mes_3" name="bonificaciones_mes_3" placeHolder="Mes 3" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Bonificaciones Mes 4:</label>
																		<input type="text" id="bonificaciones_mes_4" name="bonificaciones_mes_4" placeHolder="Mes 4" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Bonificaciones Mes 5:</label>
																		<input type="text" id="bonificaciones_mes_5" name="bonificaciones_mes_5" placeHolder="Mes 5" class="form-control" >
																	</div>
																	<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Bonificaciones Mes 6:</label>
																		<input type="text" id="bonificaciones_mes_6" name="bonificaciones_mes_6" placeHolder="Mes 6" class="form-control" >
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">- Historial Laboral últimos dos años</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">Empresa</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">Cargo</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">Desde</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">Hasta</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="empresa_1" name="empresa_1" placeHolder="Nombre Empresa" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="cargo_1" name="cargo_1" placeHolder="Nombre Cargo" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="date" id="desde_1" name="desde_1" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="date" id="hasta_1" name="hasta_1" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="empresa_2" name="empresa_2" placeHolder="Nombre Empresa" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="cargo_2" name="cargo_2" placeHolder="Nombre Cargo" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="date" id="desde_2" name="desde_2" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="date" id="hasta_2" name="hasta_2" class="form-control" >
																	</div>

																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="empresa_3" name="empresa_3" placeHolder="Nombre Empresa" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="cargo_3" name="cargo_3" placeHolder="Nombre Cargo" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="date" id="desde_3" name="desde_3" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="date" id="hasta_3" name="hasta_3" class="form-control" >
																	</div>

																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="empresa_4" name="empresa_4" placeHolder="Nombre Empresa" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="cargo_4" name="cargo_4" placeHolder="Nombre Cargo" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="date" id="desde_4" name="desde_4" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="date" id="hasta_4" name="hasta_4" class="form-control" >
																	</div>

																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Referencias Familiares, Bancarias y Crediticias</label>
																	</div>
																	
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">Ref. Familiar 1</label>
																	</div>

																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Nombre:</label>
																		<input type="text" id="nombre_referencia_1" name="nombre_referencia_1" placeHolder="Nombre" class="form-control" >
																	</div>
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Parentesco:</label>
																		<select class="form-control" name="parentesco_referencia_1" id="parentesco_referencia_1" >
																			<option value="Seleccione" >Seleccione una opcion</optinon>
																			<option value="Abuelo(a)" >Abuelo(a)</optinon>
																			<option value="Bisnieto(a)" >Bisnieto(a)</optinon>
																			<option value="Hermano(a)" >Hermano(a)</optinon>
																			<option value="Hijo(a)" >Hijo(a)</optinon>
																			<option value="Mamá" >Mamá</optinon>
																			<option value="Nieto(a)" >Nieto(a)</optinon>
																			<option value="Suegro(a)" >Suegro(a)</optinon>
																			<option value="Papá" >Papá</optinon>
																			<option value="Primo(a)" >Primo(a)</optinon>
																			<option value="Sobrino(a)" >Sobrino(a)</optinon>
																			<option value="Tio(a)" >Tio(a)</optinon>
																			<option value="Yerno" >Yerno</optinon>
																			<option value="Nuera" >Nuera</optinon>
																			<option value="Cuñado(a)" >Cuñado(a)</optinon>
																		</select>
																	</div>
																	<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Domicilio:</label>
																		<textarea class="form-control" id="domicilio_1" name="domicilio_1" rows="1"></textarea>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Telefono:</label>
																		<input type="text" id="telefono_1" name="telefono_1"  class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Trabajo:</label>
																		<input type="text" id="trabajo_1" name="trabajo_1"  class="form-control" >
																	</div>
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Dirección Trabajo:</label>
																		<textarea class="form-control" id="trabajo_direccion_1" name="trabajo_direccion_1" rows="1"></textarea>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Telefono trabajo:</label>
																		<input type="text" id="trabajo_telefono_1" name="trabajo_telefono_1"  class="form-control" >
																	</div>

																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">Ref. Familiar 2</label>
																	</div>

																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Nombre:</label>
																		<input type="text" id="nombre_referencia_2" name="nombre_referencia_2" placeHolder="Nombre" class="form-control" >
																	</div>
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Parentesco:</label>
						
																		<select class="form-control" name="parentesco_referencia_2" id="parentesco_referencia_2" >
																			<option value="Seleccione" >Seleccione una opcion</optinon>
																			<option value="Abuelo(a)" >Abuelo(a)</optinon>
																			<option value="Bisnieto(a)" >Bisnieto(a)</optinon>
																			<option value="Hermano(a)" >Hermano(a)</optinon>
																			<option value="Hijo(a)" >Hijo(a)</optinon>
																			<option value="Mamá" >Mamá</optinon>
																			<option value="Nieto(a)" >Nieto(a)</optinon>
																			<option value="Suegro(a)" >Suegro(a)</optinon>
																			<option value="Papá" >Papá</optinon>
																			<option value="Primo(a)" >Primo(a)</optinon>
																			<option value="Sobrino(a)" >Sobrino(a)</optinon>
																			<option value="Tio(a)" >Tio(a)</optinon>
																			<option value="Yerno" >Yerno</optinon>
																			<option value="Nuera" >Nuera</optinon>
																			<option value="Cuñado(a)" >Cuñado(a)</optinon>
																		</select>
																	</div>
																	<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Domicilio:</label>
																		<textarea class="form-control" id="domicilio_2" name="domicilio_2" rows="1"></textarea>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Telefono:</label>
																		<input type="text" id="telefono_2" name="telefono_2"  class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Trabajo:</label>
																		<input type="text" id="trabajo_2" name="trabajo_2"  class="form-control" >
																	</div>
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Dirección Trabajo:</label>
																		<textarea class="form-control" id="trabajo_direccion_2" name="trabajo_direccion_2" rows="1"></textarea>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Telefono trabajo:</label>
																		<input type="text" id="trabajo_telefono_2" name="trabajo_telefono_2"  class="form-control" >
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Referencias Bancarias</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">Banco</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">Tipo de Cuenta</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">No. de Cuenta</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">Saldo Actual</label>
																	</div>

																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="banco_1" name="banco_1" placeHolder="Banco" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<select class="form-control" name="tipo_cuenta_1" id="tipo_cuenta_1" >
																			<option value="Monetaria" >Monetaria</optinon>
																			<option value="Ahorro" >Ahorro</optinon>
																			<option value="Plazo Fijo" >Plazo Fijo</optinon>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="no_cuenta_1" name="no_cuenta_1" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="saldo_actual_1" name="saldo_actual_1" class="form-control" onchange="sumaValor()">
																	</div>

																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="banco_2" name="banco_2" placeHolder="Banco" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<select class="form-control" name="tipo_cuenta_2" id="tipo_cuenta_2" >
																			<option value="Monetaria" >Monetaria</optinon>
																			<option value="Ahorro" >Ahorro</optinon>
																			<option value="Plazo Fijo" >Plazo Fijo</optinon>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="no_cuenta_2" name="no_cuenta_2" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<input type="text" id="saldo_actual_2" name="saldo_actual_2" class="form-control" onchange="sumaValor()">
																	</div>

																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext2">Referencias Crediticias</label>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Banco Prestamo:</label>
																		<input type="text" id="banco_prestamo_1" name="banco_prestamo_1" placeHolder="Banco" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Tipo de Préstamo:</label>
																		<select class="form-control" name="tipo_prestamo_1" id="tipo_prestamo_1" >
																			<option value="Fiduciario" >Fiduciario</optinon>
																			<option value="Hipotecario" >Hipotecario</optinon>
																			<option value="FHA" >FHA</optinon>
																			<option value="Prendario" >Prendario</optinon>
																			<option value="Tarjeta Credito" >Tarjeta Credito</optinon>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">No. Prestamo:</label>
																		<input type="text" id="no_prestamo_1" name="no_prestamo_1" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Monto:</label>
																		<input type="text" id="monto_1" name="monto_1" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Saldo Actual:</label>
																		<input type="text" id="saldo_actual_prestamo_1" name="saldo_actual_prestamo_1" class="form-control" onchange="sumaValor()">
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Pago Mensual:</label>
																		<input type="text" id="pago_mensual_prestamo_1" name="pago_mensual_prestamo_1" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Fecha Vencimiento:</label>
																		<input type="date" id="fecha_vencimiento_prestamo_1" name="fecha_vencimiento_prestamo_1" class="form-control" >
																	</div>
																	<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Banco Prestamo:</label>
																		<input type="text" id="banco_prestamo_2" name="banco_prestamo_2" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Tipo de Préstamo:</label>
																		<select class="form-control" name="tipo_prestamo_2" id="tipo_prestamo_2" >
																			<option value="Fiduciario" >Fiduciario</optinon>
																			<option value="Hipotecario" >Hipotecario</optinon>
																			<option value="FHA" >FHA</optinon>
																			<option value="Prendario" >Prendario</optinon>
																			<option value="Tarjeta Credito" >Tarjeta Credito</optinon>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">No. Prestamo:</label>
																		<input type="text" id="no_prestamo_2" name="no_prestamo_2" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Monto:</label>
																		<input type="text" id="monto_2" name="monto_2" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Saldo Actual:</label>
																		<input type="text" id="saldo_actual_prestamo_2" name="saldo_actual_prestamo_2" class="form-control" onchange="sumaValor()">
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Pago Mensual:</label>
																		<input type="text" id="pago_mensual_prestamo_2" name="pago_mensual_prestamo_2" class="form-control" >
																	</div>
																	<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
																		<label class="nodpitext">Fecha Vencimiento:</label>
																		<input type="date" id="fecha_vencimiento_prestamo_2" name="fecha_vencimiento_prestamo_2" class="form-control" >
																	</div>
																</div>
																<script type="text/javascript">
																	$("#salario_nominal").number( true, 2 );
																	$("#bono_catorce").number( true, 2 );
																	$("#aguinaldo").number( true, 2 );
																	$("#honorarios").number( true, 2 );
																	$("#otros_descuentos_fha").number( true, 2 );
																	$("#igss").number( true, 2 );
																	$("#isr").number( true, 2 );
																	$("#plan_pensiones").number( true, 2 );
																	$("#judiciales").number( true, 2 );
																	$("#otros_ingresos_fha").number( true, 2 );
																	$("#saldo_actual_1").number( true, 2 );
																	$("#saldo_actual_2").number( true, 2 );
																	$("#monto_1").number( true, 2 );
																	$("#saldo_actual_prestamo_1").number( true, 2 );
																	$("#pago_mensual_prestamo_1").number( true, 2 );
																	$("#monto_2").number( true, 2 );
																	$("#saldo_actual_prestamo_2").number( true, 2 );
																	$("#pago_mensual_prestamo_2").number( true, 2 );
																	
																</script>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
																<button onclick="guardarClienteFHADependencia()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
															</div>	
														</div>
													</form>
												</div>		
											</div>
										</div>
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
					<?php require_once("./documentos_adjuntos.php"); ?>
				</div>
			</div>
		</div>
			<script type="text/javascript">
				if($("#idPerfil").val()!=5){
					agregarCliente();
				}
	
				
				function fncTipoCliente(){
					var radios = document.getElementsByName('tipoCliente');
					for (var i = 0, length = radios.length; i < length; i++) {
						if (radios[i].checked) {
							if(radios[i].value=='juridico'){
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
								//document.getElementById("salarioDiv").style.display = "none";
								//document.getElementById("montoIngresosDiv").style.display = "none";
								//document.getElementById("otrosIngresosDiv").style.display = "none";
							}else if(radios[i].value=='individual'){
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
								// document.getElementById("salarioDiv").style.display = "";
								// document.getElementById("montoIngresosDiv").style.display = "";
								// document.getElementById("otrosIngresosDiv").style.display = "";
							}
							// do whatever you want with the checked radio
							//alert(radios[i].value);

							// only one radio can be logically checked, don't check the rest
							break;
						}
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
								output += '<tr onCLick=""><td>'+e.codigo+'</td><td>'+e.client_name+' '+check+'</td><td><button onclick="buscarClienteUnico(\''+e.id+'\',\'\',\'\')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ></td></tr>';
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
				function guardarClienteFHA(){
					var error = 0;
					if(error==0){
						var formDataCliente = new FormData(document.getElementById("frmAgregarClienteFha"));
						formDataCliente.append("idCliente", $("#idCliente").val());
						formDataCliente.append("proyectoCliente", $("#proyectoCliente").val());
						formDataCliente.append("idOcaCliente", $("#idOcaCliente").val());
						$.ajax({
							url: "./cliente.php?agregar_editar_cliente_info_fha=true",
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
				function guardarClienteFHADependencia(){
					var error = 0;
					if(error==0){
						var formDataCliente = new FormData(document.getElementById("frmAgregarClienteFhaDependencia"));
						formDataCliente.append("idCliente", $("#idCliente").val());
						formDataCliente.append("proyectoCliente", $("#proyectoCliente").val());
						formDataCliente.append("idOcaCliente", $("#idOcaCliente").val());
						$.ajax({
							url: "./cliente.php?agregar_editar_cliente_info_fha_dependencia=true",
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
					document.getElementById("frmAgregarCliente").reset();
					document.getElementById("frmAgregarClienteFha").reset();
					document.getElementById("frmAgregarClienteFhaDependencia").reset();
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
								$("#idCliente").val(e.idCliente);
								$("#idOcaCliente").val(e.idCliente);
								getDepartamentos(1,'depto',e.departamento);
								getTipoComision('tipoComision',e.tipoComision);
								getMunicipios(e.departamento,'municipio',e.municipio);
								getNacionalidad('nacionalidadCl',e.Nacionalidad);
								var nombreCompleto=e.client_name;
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
								$("#Estatura").val(e.estatura);
								$("#peso").val(e.peso);
								
								if(e.creditoFHA=='si'){
									document.getElementById('si').checked = true;
									console.log("credito fha "+e.creditoFHA);
								}else if(e.creditoFHA=='no'){
									document.getElementById('no').checked = true;
									console.log("credito fha "+e.creditoFHA);
								}
								if(e.tipoCliente=='individual'){
									document.getElementById('individual').checked = true;
									fncTipoCliente();
								}else if(e.tipoCliente=='juridico'){
									document.getElementById('juridico').checked = true;
									fncTipoCliente();
								}
								$("#fechaEmisionDpiCl").val(e.fechaEmisionDpi);	
							});
							$.each(response.infoPatrimonial,function(i,e) {
							$("#acciones").val(e.acciones);	
							$("#alquiler").val(e.alquiler);	
							$("#bancos").val(e.bancos);	
							$("#bonos").val(e.bonos);	
							$("#caja").val(e.caja);	
							$("#cuentas_cobrar").val(e.cuentas_cobrar);	
							$("#cuentas_pagar_corto_plazo").val(e.cuentas_pagar_corto_plazo);	
							$("#cuentas_pagar_largo_plazo").val(e.cuentas_pagar_largo_plazo);	
							$("#deudas_particulares").val(e.deudas_particulares);	
							$("#extrafinanciamientos").val(e.extrafinanciamientos);	
							$("#impuestos").val(e.impuestos);	
							$("#inversiones").val(e.inversiones);	
							$("#muebles").val(e.muebles);	
							$("#prestamos").val(e.prestamos);	
							$("#prestamos_hipotecarios").val(e.prestamos_hipotecarios);	
							$("#sostenimiento_hogar").val(e.sostenimiento_hogar);	
							$("#terrenos").val(e.terrenos);	
							$("#vehiculos").val(e.vehiculos);	
							$("#viviendas").val(e.viviendas);	
						});
						$.each(response.infoDetallePatrimonial,function(i,e) {
							$("#direccion_inmueble_1").val(e.direccion_inmueble_1);	
							$("#direccion_inmueble_2").val(e.direccion_inmueble_2);	
							$("#finca_1").val(e.finca_1);
							$("#folio_1").val(e.folio_1);
							$("#libro_1").val(e.libro_1);	
							$("#departamento_1").val(e.departamento_1);	
							$("#valor_inmueble_1").val(e.valor_inmueble_1);	
							$("#finca_2").val(e.finca_2);
							$("#folio_2").val(e.folio_2);
							$("#libro_2").val(e.libro_2);
							$("#departamento_2").val(e.departamento_2);
							$("#valor_inmueble_2").val(e.valor_inmueble_2);			
							$("#valor_estimado_1").val(e.valor_estimado_1);	
							$("#valor_estimado_2").val(e.valor_estimado_2);	
							$("#marca_1").val(e.marca_1);
							getTipo(e.marca_1,'tipo_vehiculo_1',e.tipo_vehiculo_1);
							getModelo(1,e.tipo_vehiculo_1,'modelo_vehiculo_1',e.modelo_vehiculo_1);
							$("#marca_2").val(e.marca_2);
							getTipo(e.marca_2,'tipo_vehiculo_2',e.tipo_vehiculo_2);
							getModelo(2,e.tipo_vehiculo_2,'modelo_vehiculo_2',e.modelo_vehiculo_2);	
							getDepartamentos(1,'departamento_1',e.departamento_1);
							getDepartamentos(1,'departamento_2',e.departamento_2);
						});
						$.each(response.infoIngresosEgresos,function(i,e) {
							$("#aguinaldo").val(e.aguinaldo);	
							$("#bono_catorce").val(e.bono_catorce);	
							$("#honorarios").val(e.honorarios);	
							$("#igss").val(e.igss);	
							$("#judiciales").val(e.judiciales);	
							$("#isr").val(e.isr);	
							$("#otros_descuentos_fha").val(e.otros_descuentos_fha);	
							$("#otros_ingresos_fha").val(e.otros_ingresos_fha);	
							$("#plan_pensiones").val(e.plan_pensiones);
							$("#salario_nominal").val(e.salario_nominal);
							$("#tipoContrato").val(e.tipoContrato);
							$("#vigencia_vence").val(e.vigencia_vence);	
						});
						$.each(response.infoDetalleComisiones,function(i,e) {
							$("#mes_1").val(e.mes_1);
							$("#mes_2").val(e.mes_2);
							$("#mes_3").val(e.mes_3);
							$("#mes_4").val(e.mes_4);
							$("#mes_5").val(e.mes_5);
							$("#mes_6").val(e.mes_6);
							$("#hora_extra_mes_1").val(e.hora_extra_mes_1);
							$("#hora_extra_mes_2").val(e.hora_extra_mes_2);
							$("#hora_extra_mes_3").val(e.hora_extra_mes_3);
							$("#hora_extra_mes_4").val(e.hora_extra_mes_4);
							$("#hora_extra_mes_5").val(e.hora_extra_mes_5);
							$("#hora_extra_mes_6").val(e.hora_extra_mes_6);
							$("#comisiones_mes_1").val(e.comisiones_mes_1);
							$("#comisiones_mes_2").val(e.comisiones_mes_2);
							$("#comisiones_mes_3").val(e.comisiones_mes_3);
							$("#comisiones_mes_4").val(e.comisiones_mes_4);
							$("#comisiones_mes_5").val(e.comisiones_mes_5);
							$("#comisiones_mes_6").val(e.comisiones_mes_6);
							$("#bonificaciones_mes_1").val(e.bonificaciones_mes_1);
							$("#bonificaciones_mes_2").val(e.bonificaciones_mes_2);
							$("#bonificaciones_mes_3").val(e.bonificaciones_mes_3);
							$("#bonificaciones_mes_4").val(e.bonificaciones_mes_4);
							$("#bonificaciones_mes_5").val(e.bonificaciones_mes_5);
							$("#bonificaciones_mes_6").val(e.bonificaciones_mes_6);
						});
						$.each(response.infoHistorialLaboral,function(i,e) {
							$("#empresa_1").val(e.empresa_1);	
							$("#cargo_1").val(e.cargo_1);	
							$("#desde_1").val(e.desde_1);	
							$("#hasta_1").val(e.hasta_1);	
							$("#empresa_2").val(e.empresa_2);	
							$("#cargo_2").val(e.cargo_2);	
							$("#desde_2").val(e.desde_2);	
							$("#hasta_2").val(e.hasta_2);
							$("#empresa_3").val(e.empresa_3);	
							$("#cargo_3").val(e.cargo_3);	
							$("#desde_3").val(e.desde_3);	
							$("#hasta_3").val(e.hasta_3);	
							$("#empresa_4").val(e.empresa_4);	
							$("#cargo_4").val(e.cargo_4);	
							$("#desde_4").val(e.desde_4);	
							$("#hasta_4").val(e.hasta_4);		
							
						});
						$.each(response.infoRefFamiliar,function(i,e) {
							$("#nombre_referencia_1").val(e.nombre_referencia_1);	
							$("#parentesco_referencia_1").val(e.parentesco_referencia_1);	
							$("#domicilio_1").val(e.domicilio_1);	
							$("#telefono_1").val(e.telefono_1);	
							$("#trabajo_1").val(e.trabajo_1);	
							$("#trabajo_direccion_1").val(e.trabajo_direccion_1);	
							$("#trabajo_telefono_1").val(e.trabajo_telefono_1);	
							$("#nombre_referencia_2").val(e.nombre_referencia_2);	
							$("#parentesco_referencia_2").val(e.parentesco_referencia_2);	
							$("#domicilio_2").val(e.domicilio_2);	
							$("#telefono_2").val(e.telefono_2);	
							$("#trabajo_2").val(e.trabajo_2);	
							$("#trabajo_direccion_2").val(e.trabajo_direccion_2);	
							$("#trabajo_telefono_2").val(e.trabajo_telefono_2);	
							
						});

						$.each(response.infoRefBancarias,function(i,e) {
							$("#banco_1").val(e.banco_1);	
							$("#no_cuenta_1").val(e.no_cuenta_1);	
							$("#tipo_cuenta_1").val(e.tipo_cuenta_1);	
							$("#saldo_actual_1").val(e.saldo_actual_1);	
							$("#banco_2").val(e.banco_2);	
							$("#no_cuenta_2").val(e.no_cuenta_2);	
							$("#tipo_cuenta_2").val(e.tipo_cuenta_2);	
							$("#saldo_actual_2").val(e.saldo_actual_2);	

						});

						
						$.each(response.infoRefCrediticias,function(i,e) {
							$("#banco_prestamo_1").val(e.banco_prestamo_1);	
							$("#tipo_prestamo_1").val(e.tipo_prestamo_1);
							$("#no_prestamo_1").val(e.no_prestamo_1);	
							$("#monto_1").val(e.monto_1);
							$("#saldo_actual_prestamo_1").val(e.saldo_actual_prestamo_1);
							$("#pago_mensual_prestamo_1").val(e.pago_mensual_prestamo_1);
							$("#fecha_vencimiento_prestamo_1").val(e.fecha_vencimiento_prestamo_1);	
							$("#banco_prestamo_2").val(e.banco_prestamo_2);	
							$("#tipo_prestamo_2").val(e.tipo_prestamo_2);	
							$("#no_prestamo_2").val(e.no_prestamo_2);
							$("#monto_2").val(e.monto_2);
							$("#saldo_actual_prestamo_2").val(e.saldo_actual_prestamo_2);
							$("#pago_mensual_prestamo_2").val(e.pago_mensual_prestamo_2);
							$("#fecha_vencimiento_prestamo_2").val(e.fecha_vencimiento_prestamo_2);	
							

						});
						sumaValor();

						
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
				function agregarCliente(){
					document.getElementById("frmAgregarCliente").reset();
					document.getElementById("frmAgregarClienteFha").reset();
					document.getElementById("frmAgregarClienteFhaDependencia").reset();
					getMarca('marca_1','');
					getMarca('marca_2','');
					getDepartamentos(1,'depto',0);
					getDepartamentos(1,'departamento_1',0);
					getDepartamentos(1,'departamento_2',0);
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
			function getMarca(input,valueInput){
					
					var formData = new FormData;
					$.ajax({
						url: "./vehiculo.php?get_lista_vehiculos_marca=true",
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
							$.each(response.listado_marcas,function(i,e) {
								if(valueInput==e.marca){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.marca+'">'+e.marca+'</option>';
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
			function getTipo(marca,input,valueInput){
					
				var formData = new FormData;
				formData.append("marca", marca);

				$.ajax({
					url: "./vehiculo.php?get_lista_vehiculos_tipo=true",
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
						$.each(response.listado_tipos,function(i,e) {
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
			function getModelo(no,tipo,input,valueInput){
					
				var formData = new FormData;
				var marca = $("#marca_"+no).val();
				formData.append("marca", marca);
				formData.append("tipo", tipo);

				
				$.ajax({
					url: "./vehiculo.php?get_lista_vehiculos_modelo=true",
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
						$.each(response.listado_modelos,function(i,e) {
							if(valueInput==e.modelo){
								select= 'selected="selected"';
							}else{
								select='';
							}
							output += ' <option '+select+' value="'+e.modelo+'">'+e.modelo+'</option>';
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
			function sumaValor(){
				var valor_inmueble_1 = $("#valor_inmueble_1").val() == '' ? 0 : $("#valor_inmueble_1").val();
				var valor_inmueble_2 = $("#valor_inmueble_2").val() == '' ? 0 : $("#valor_inmueble_2").val();
				var total_valor_inmueble = parseFloat(valor_inmueble_1) + parseFloat(valor_inmueble_2);

				var valor_estimado_1 = $("#valor_estimado_1").val() == '' ? 0 : $("#valor_estimado_1").val();
				var valor_estimado_2 = $("#valor_estimado_2").val() == '' ? 0 : $("#valor_estimado_2").val();
				var total_valor_estimado = parseFloat(valor_estimado_1) + parseFloat(valor_estimado_2);

				var saldo_actual_1 = $("#saldo_actual_1").val() == '' ? 0 : $("#saldo_actual_1").val();
				var saldo_actual_2 = $("#saldo_actual_2").val() == '' ? 0 : $("#saldo_actual_2").val();
				var total_saldo_actual = parseFloat(saldo_actual_1) + parseFloat(saldo_actual_2);

				$("#viviendas").val(total_valor_inmueble);
				$("#vehiculos").val(total_valor_estimado);
				$("#bancos").val(total_saldo_actual);
				var saldo_actual_prestamo_hipotecario_1 = 0;
				var saldo_actual_prestamo_hipotecario_2 = 0;
				var saldo_actual_prestamo_tc_1 = 0;
				var saldo_actual_prestamo_tc_2 = 0;
				var saldo_actual_prestamo_1 = 0;
				var saldo_actual_prestamo_2 = 0;

				if($("#tipo_prestamo_1").val() == 'Hipotecario'){
					var saldo_actual_prestamo_hipotecario_1 = $("#saldo_actual_prestamo_1").val() == '' ? 0 : $("#saldo_actual_prestamo_1").val();
				}else if ($("#tipo_prestamo_1").val() == 'Tarjeta Credito'){
					var saldo_actual_prestamo_tc_1 = $("#saldo_actual_prestamo_1").val() == '' ? 0 : $("#saldo_actual_prestamo_1").val();

				}else{
					var saldo_actual_prestamo_1 = $("#saldo_actual_prestamo_1").val() == '' ? 0 : $("#saldo_actual_prestamo_1").val();
				}

				if($("#tipo_prestamo_2").val() == 'Hipotecario'){
					var saldo_actual_prestamo_hipotecario_2 = $("#saldo_actual_prestamo_2").val() == '' ? 0 : $("#saldo_actual_prestamo_2").val();
				}else if ($("#tipo_prestamo_2").val() == 'Tarjeta Credito'){
					var saldo_actual_prestamo_tc_2 = $("#saldo_actual_prestamo_2").val() == '' ? 0 : $("#saldo_actual_prestamo_2").val();

				}else{
					var saldo_actual_prestamo_2 = $("#saldo_actual_prestamo_2").val() == '' ? 0 : $("#saldo_actual_prestamo_2").val();
				}
				var total_saldo_actual_prestamo_hipotecario = parseFloat(saldo_actual_prestamo_hipotecario_1) + parseFloat(saldo_actual_prestamo_hipotecario_2);
				var total_saldo_actual_prestamo_tc = parseFloat(saldo_actual_prestamo_tc_1) + parseFloat(saldo_actual_prestamo_tc_2);
				var total_saldo_actual_prestamo = parseFloat(saldo_actual_prestamo_1) + parseFloat(saldo_actual_prestamo_2);

				$("#prestamos_hipotecarios").val(total_saldo_actual_prestamo_hipotecario);
				$("#extrafinanciamientos").val(total_saldo_actual_prestamo_tc);
				$("#prestamos").val(total_saldo_actual_prestamo);


				sumaTotal();
			}
			function sumaTotal(){
				var caja = $("#caja").val() == '' ? 0 : $("#caja").val();
				var bancos = $("#bancos").val() == '' ? 0 : $("#bancos").val();
				var cuentas_cobrar = $("#cuentas_cobrar").val() == '' ? 0 : $("#cuentas_cobrar").val();
				var terrenos = $("#terrenos").val() == '' ? 0 : $("#terrenos").val();
				var viviendas = $("#viviendas").val() == '' ? 0 : $("#viviendas").val();
				var vehiculos = $("#vehiculos").val() == '' ? 0 : $("#vehiculos").val();
				var inversiones = $("#inversiones").val() == '' ? 0 : $("#inversiones").val();
				var bonos = $("#bonos").val() == '' ? 0 : $("#bonos").val();
				var acciones = $("#acciones").val() == '' ? 0 : $("#acciones").val();
				var muebles = $("#muebles").val() == '' ? 0 : $("#muebles").val();
				var total_activos = parseFloat(caja) + parseFloat(bancos)+parseFloat(cuentas_cobrar) + parseFloat(terrenos)+parseFloat(viviendas) + parseFloat(vehiculos)+parseFloat(inversiones) + parseFloat(bonos)+parseFloat(acciones) + parseFloat(muebles);

				var cuentas_pagar_corto_plazo = $("#cuentas_pagar_corto_plazo").val() == '' ? 0 : $("#cuentas_pagar_corto_plazo").val();
				var cuentas_pagar_largo_plazo = $("#cuentas_pagar_largo_plazo").val() == '' ? 0 : $("#cuentas_pagar_largo_plazo").val();
				var prestamos_hipotecarios = $("#prestamos_hipotecarios").val() == '' ? 0 : $("#prestamos_hipotecarios").val();
				var sostenimiento_hogar = $("#sostenimiento_hogar").val() == '' ? 0 : $("#sostenimiento_hogar").val();
				var alquiler = $("#alquiler").val() == '' ? 0 : $("#alquiler").val();
				var prestamos = $("#prestamos").val() == '' ? 0 : $("#prestamos").val();
				var impuestos = $("#impuestos").val() == '' ? 0 : $("#impuestos").val();
				var extrafinanciamientos = $("#extrafinanciamientos").val() == '' ? 0 : $("#extrafinanciamientos").val();
				var deudas_particulares = $("#deudas_particulares").val() == '' ? 0 : $("#deudas_particulares").val();
				var total_pasivos = parseFloat(cuentas_pagar_corto_plazo) + parseFloat(cuentas_pagar_largo_plazo)+parseFloat(prestamos_hipotecarios) + parseFloat(sostenimiento_hogar)+parseFloat(alquiler) + parseFloat(prestamos) + parseFloat(impuestos) + parseFloat(extrafinanciamientos) + parseFloat(deudas_particulares);

				$("#total_activos").val(total_activos);
				$("#total_pasivos").val(total_pasivos);
				$("#total_patrimonio").val(total_activos-total_pasivos);
			}
			</script>
    </body>
</html>

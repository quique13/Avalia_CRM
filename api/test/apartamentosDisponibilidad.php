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
		<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="../css/styles.css" rel="stylesheet">
		<link href="../css/stylesEnganche.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Archivo&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/font-awesome-4.7.0/css/font-awesome.css">
        <script src="../libs/cryptoJS/v3.1.2/rollups/aes.js"></script>
		<script src="../libs/cryptoJS/v3.1.2/rollups/md5.js"></script>
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
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/apartment_info.png"> Disponibilidad</label>
											<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="divAlertPendiente" name="divAlertPendiente">
                								
											</div>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
                                        <div class="row">
                                            <div class="col-md-12" id="">
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
												<input id="id_perfil" name="id_perfil" type="hidden" value="<?php echo $id_perfil ?>" >
													<label class="nodpitext">Proyecto:</label>
													<select class="form-control" name="proyectoApto" id="proyectoApto"  onchange="getTable(this.value)">
														<option value="0" >Todos</optinon>
														<? echo $proyectos ?>
													</select>
												</div>
                                                <div class="row"> 	
                                                    <div class="col-md-12 "> 
														<div id="divTableDisp" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
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
					<div class="modal fade" id="modalPrincial" name="modalPrincial" style="width:90%; margin-left:5%;  overflow-y: hidden;overflow-x: hidden">
						<div class="modal-dialog mw-100 w-auto " style="height:100%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
									<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" >Información: </h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyInfoCliente" style="padding:5px 15px;" >
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#cliente">Cliente</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#apartamento">Apartamento</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#estado_cuenta">Estado de cuenta</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#adjuntos">Adjuntos</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#adjuntosFha">Adjuntos FHA</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#pdf">PDF Resumen</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#pdfExtra">Planos</a>
										</li>
									</ul>
									<div class="tab-content" id="renderDatos" name="renderDatos">
										<div id="cliente" class="tab-pane active">
											<div class="row" >
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;margin-top:10px;">
													<h3 class="titleinf"><img class="usericon" src="../img/client_icon.png" alt="Cliente"> Información del Cliente</h3><h3 id="h3_codeudor_div" name="h3_codeudor_div" style="display:none;" class="titleinf"> (Cuenta con codeudor)</h3>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Tipo Cliente</label>
															<select class="form-control" name="tipoCliente" id="tipoCliente" disabled="disabled">
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
																			<select class="form-control" name="tipoComision" id="tipoComision" disabled="disabled">
																			</select>
																		</div>
																		<div class="col-lg-6 col-md-6 col-xs-10"  >
																			<label class="nodpitext">Estado Cliente</label>
																			<select class="form-control" name="estadoCl" id="estadoCl" disabled="disabled">
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
															<input type="text" id="nombreSa" name="nombreSa" placeHolder="" class="form-control" readonly>
														</div>
														<div id="rtuDiv" name="rtuDiv" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">RTU</label>	
															<input type="text" id="rtu" name="rtu" placeHolder="" class="form-control" readonly>
														</div>
														<div id="representanteLegalDiv" name="representanteLegalDiv" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Representante Legal</label>	
															<input type="text" id="representanteLegal" name="representanteLegal" placeHolder="" class="form-control" readonly>
														</div>
														<div id="patenteEmpresaDiv" name="patenteEmpresaDiv" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Patente de Empresa</label>	
															<input type="text" id="patenteEmpresa" name="patenteEmpresa" placeHolder="" class="form-control" readonly>
														</div>
														<div id="patenteSociedadDiv" name="patenteSociedadDiv" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Patente de Sociedad</label>	
															<input type="text" id="patenteSociedad" name="patenteSociedad" placeHolder="" class="form-control" readonly>
														</div>
														<div id="primerNombreDiv" name="primerNombreDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Primer Nombre:</label>
															<input type="text" id="primerNombre" name="primerNombre" placeHolder="" class="form-control" readonly>
														</div>
														<div id="segundoNombreDiv" name="segundoNombreDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Segundo Nombre:</label>	
															<input type="text" id="segundoNombre" name="segundoNombre" placeHolder="" class="form-control" readonly>
														</div>
														<div id="primerApellidoDiv" name="primerApellidoDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Primer Apellido:</label>		
															<input type="text" id="primerApellido" name="primerApellido" placeHolder="" class="form-control" readonly>
														</div>
														<div id="segundoApellidoDiv" name="segundoApellidoDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Segundo Apellido:</label>		
															<input type="text" id="segundoApellido" name="segundoApellido" placeHolder="" class="form-control" readonly>
														</div>
														<div id="tercerNombreDiv" name="tercerNombreDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Tercer Nombre:</label>		
															<input type="text" id="tercerNombre" name="tercerNombre" placeHolder="" class="form-control" readonly>
														</div>
														<div id="apellidoCasadaDiv" name="apellidoCasadaDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Apellido Casada:</label>		
															<input type="text" id="apellidoCasada" name="apellidoCasada" placeHolder="" class="form-control" readonly>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Correo electrónico:</label>
															<input type="text" id="correo" name="correo" class="form-control" readonly>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Télefono Fijo:</label>
															<input  type="text" id="telefonoFijo" name="telefonoFijo" class="form-control" readonly>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Télefono Celular:</label>
															<input  type="text" id="telefono" name="telefono" class="form-control" readonly>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label id="lblDireccion" name="lblDireccion" class="nodpitext">Dirección Residencia:</label>
															<textarea class="form-control" id="direccion" name="direccion" rows="2" readonly></textarea>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label id="lblNit" name="lblNit" class="nodpitext">Nit:</label>
															<input type="text" id="nitCl" name="nitCl" class="form-control" readonly>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label id="lblNumeroDpi" name="lblNumeroDpi" class="nodpitext">Número de DPI:</label>
															<input type="text" id="numeroDpi" name="numeroDpi" class="form-control" readonly>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Departamento:</label>
															<select class="form-control" name="depto" id="depto" disabled="disabled">
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Municipio:</label>
															<select class="form-control" name="municipio" id="municipio" disabled="disabled">
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label class="nodpitext">Fecha Emisión DPI:</label>
															<input type="date" id="fechaEmisionDpiCl" name="fechaEmisionDpiCl" class="form-control" readonly>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Fecha vencimiento DPI:</label>
															<input id="fechaVencimientoDpi" name="fechaVencimientoDpi" type="date" class="form-control" readonly>
															<input id="fechaHoy" name="fechaHoy" type="hidden" class="form-control" value="<?php echo date("d/m/Y") ?>">
														</div>
														
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div id="nacionalidadDiv" name="nacionalidadDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Nacionalidad:</label>
															<select class="form-control" name="nacionalidadCl" id="nacionalidadCl" disabled="disabled">
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label id="lblFechaNacimiento" name="lblFechaNacimiento" class="nodpitext">Fecha de nacimiento:</label>
															<input type="date" id="fechaNacimientoCl" name="fechaNacimientoCl" class="form-control" readonly>
														</div>
														<div id="estadoCivilDiv" name="estadoCivilDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Estado Civil:</label>
															<select class="form-control" name="estadoCivilCl" id="estadoCivilCl" disabled="disabled">
																<option value="" >Seleccione</optinon>
																<option value="Soltero" >Soltero(a)</optinon>
																<option value="Casado" >Casado(a)</optinon>
																<option value="Viudo" >Viudo(a)</optinon>
																<option value="Divorciado" >Divorciado(a)</optinon>
															</select>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label id="lblProfesion" name="lblProfesion" class="nodpitext">Profesión:</label>
															<input type="text" id="profesionCl" name="profesionCl" class="form-control" readonly>
														</div>
														<div id="dependientesDiv" name="dependientesDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">No. de dependientes:</label>
															<input type="number" id="dependientesCl" name="dependientesCl" class="form-control" readonly>
														</div>
														<div id="fhaDiv" name="fhaDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<div class="row" >
																<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																	<label class="nodpitext">Ha tenido tramite FHA:</label>
																</div>
																<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																	<div class=" form-check form-check-inline">
																		<input class="form-check-input" type="radio" name="fha" id="si" value="si" readonly>
																		<label class="form-check-label" for="">Si</label>
																	</div>
																	<div class="form-check form-check-inline">
																		<input class="form-check-input" type="radio" name="fha" id="no" value="no" readonly>
																		<label class="form-check-label">No</label>
																	</div>
																</div>
															</div>
														</div>
														<div id="empresaLaboraDiv" name="empresaLaboraDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Empresa donde labora:</label>
															<input type="text" id="empresaLaboraCl" name="empresaLaboraCl" class="form-control" readonly>
														</div>
														<div id="direccionEmpresaDiv" name="direccionEmpresaDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Dirección de empresa:</label>
															<textarea class="form-control" readonly id="direccionEmpresaCl" name="direccionEmpresaCl" rows="2"></textarea>
														</div>
														<div id="telefonoReferenciaDiv" name="telefonoReferenciaDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Télefono de Referencia:</label>
															<input  type="text" id="telefonoReferencia" name="telefonoReferencia" class="form-control" readonly>
														</div>
														<div id="puestoEmpresaDiv" name="puestoEmpresaDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Puesto en Empresa:</label>
															<input type="text" id="puestoEmpresaCl" name="puestoEmpresaCl" class="form-control" readonly>
														</div>
														<div id="salarioDiv" name="salarioDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Salario mensual:</label>
															<input type="text" id="salarioMensualCl" name="salarioMensualCl" class="form-control" readonly>
														</div>
														<div id="montoIngresosDiv" name="montoIngresosDiv" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Otros ingresos:</label>
															<input type="text" id="montoOtrosIngresosCl" name="montoOtrosIngresosCl" class="form-control" readonly>
														</div>
														<div id="otrosIngresosDiv" name="otrosIngresosDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Descripción Otros ingresos:</label>
															<input type="text" id="otrosIngresosCl" name="otrosIngresosCl" class="form-control" readonly>
														</div>
														<div id="observacionesDiv" name="observacionesDiv" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Observaciones:</label>
															<textarea class="form-control" id="observacionesCl" name="observacionesCl" rows="2" readonly></textarea>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Tramite FHA:</label>
															<select class="form-control" name="tramiteFHACl" id="tramiteFHACl" readonly>
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

												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<div class = "row">
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Estatura(Cms):</label>
															<input type="text" id="Estatura" name="Estatura"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Peso(Lbs):</label>	
															<input type="text" id="peso" name="peso" class="form-control" readonly >
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext2">Activo</label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Caja:</label>
															<input type="text" id="caja" name="caja"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Bancos:</label>
															<input type="text" id="bancos" name="bancos"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Ctas por Cobrar:</label>
															<input type="text" id="cuentas_cobrar" name="cuentas_cobrar"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Terrenos:</label>
															<input type="text" id="terrenos" name="terrenos"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Viviendas:</label>
															<input type="text" id="viviendas" name="viviendas"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Vehículos:</label>
															<input type="text" id="vehiculos" name="vehiculos"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Inversiones:</label>
															<input type="text" id="inversiones" name="inversiones"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Bonos:</label>
															<input type="text" id="bonos" name="bonos"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Acciones:</label>
															<input type="text" id="acciones" name="acciones"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Muebles:</label>
															<input type="text" id="muebles" name="muebles"  class="form-control" readonly >
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
															<input type="text" id="cuentas_pagar_corto_plazo" name="cuentas_pagar_corto_plazo"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Ctas por Pagar largo plazo:</label>
															<input type="text" id="cuentas_pagar_largo_plazo" name="cuentas_pagar_largo_plazo"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Prestamos Hipotecarios:</label>
															<input type="text" id="prestamos_hipotecarios" name="prestamos_hipotecarios"  class="form-control" readonly >
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext2">Gastos mensuales</label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Sostenimiento del Hogar:</label>
															<input type="text" id="sostenimiento_hogar" name="sostenimiento_hogar"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Alquiler:</label>
															<input type="text" id="alquiler" name="alquiler"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Préstamos:</label>
															<input type="text" id="prestamos" name="prestamos"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Impuestos:</label>
															<input type="text" id="impuestos" name="impuestos"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Extrafinanciamientos TC:</label>
															<input type="text" id="extrafinanciamientos" name="extrafinanciamientos"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Deudas Particulares:</label>
															<input type="text" id="deudas_particulares" name="deudas_particulares"  class="form-control" readonly >
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
															<textarea class="form-control" readonly id="direccion_inmueble_1" name="direccion_inmueble_1" rows="1"></textarea>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">valor inmueble:</label>
															<input type="text" id="valor_inmueble_1" name="valor_inmueble_1" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Finca:</label>
															<input type="text" id="finca_1" name="finca_1" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Folio:</label>
															<input type="text" id="folio_1" name="folio_1" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Libro:</label>
															<input type="text" id="libro_1" name="libro_1" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Departamento:</label>
															<input type="text" id="departamento_1" name="departamento_1" class="form-control" readonly>
														</div>
														<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Dirección del Inmueble:</label>
															<textarea class="form-control" readonly id="direccion_inmueble_2" name="direccion_inmueble_2" rows="1"></textarea>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">valor inmueble:</label>
															<input type="text" id="valor_inmueble_2" name="valor_inmueble_2" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Finca:</label>
															<input type="text" id="finca_2" name="finca_2" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Folio:</label>
															<input type="text" id="folio_2" name="folio_2" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Libro:</label>
															<input type="text" id="libro_2" name="libro_2" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Departamento:</label>
															<input type="text" id="departamento_2" name="departamento_2" class="form-control" readonly>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext2">Vehículos</label>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Marca:</label>
															<input type="text" id="marca_1" name="marca_1" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">tipo:</label>
															<input type="text" id="tipo_vehiculo_1" name="tipo_vehiculo_1" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Modelo:</label>
															<input type="text" id="modelo_vehiculo_1" name="modelo_vehiculo_1" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Valor Estimado:</label>
															<input type="text" id="valor_estimado_1" name="valor_estimado_1" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Marca:</label>
															<input type="text" id="marca_2" name="marca_2" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">tipo:</label>
															<input type="text" id="tipo_vehiculo_2" name="tipo_vehiculo_2" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Modelo:</label>
															<input type="text" id="modelo_vehiculo_2" name="modelo_vehiculo_2" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Valor Estimado:</label>
															<input type="text" id="valor_estimado_2" name="valor_estimado_2" class="form-control" readonly >
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

														<div class="col-lg-6 col-md-6 col-xs-10">
															<div class="row" >
																<div class="col-lg-12 col-md-12 col-xs-10">
																	<label class="nodpitext">Tipo Contrato:</label>			
																</div>
																<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
																	<div class=" form-check form-check-inline"  >
																		<input class="form-check-input" type="radio" name="tipoContrato" id="indefinido" value="indefinido" checked readonly>
																		<label class="form-check-label" for="">Indefinido</label>
																	</div>
																	<div class="form-check form-check-inline">
																		<input class="form-check-input" type="radio" name="tipoContrato" id="definido" value="definido" readonly>
																	<label class="form-check-label">Vigencia Definida</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label class="nodpitext">Vigencia Vence:</label>
															<input type="date" id="vigencia_vence" name="vigencia_vence" class="form-control" readonly>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext2">Detalle de Ingresos Mensuales</label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Salario Nominal:</label>
															<input type="text" id="salario_nominal" name="salario_nominal"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Aguinaldo (1/12):</label>
															<input type="text" id="bono_catorce" name="bono_catorce"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Aguinaldo (1/12):</label>
															<input type="text" id="aguinaldo" name="aguinaldo"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Honorarios:</label>
															<input type="text" id="honorarios" name="honorarios"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Otros:</label>
															<input type="text" id="otros_ingresos_fha" name="otros_ingresos_fha"  class="form-control" readonly >
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext2">Detalle de descuentos Mensuales</label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">IGSS:</label>
															<input type="text" id="igss" name="igss"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">ISR:</label>
															<input type="text" id="isr" name="isr"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Plan de Pensiones:</label>
															<input type="text" id="plan_pensiones" name="plan_pensiones"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Judiciales:</label>
															<input type="text" id="judiciales" name="judiciales"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Otros:</label>
															<input type="text" id="otros_descuentos_fha" name="otros_descuentos_fha"  class="form-control" readonly >
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext2">Detalle de horas extras, Comisiones y Bonificaciones últimos 6 meses</label>
														</div>
														<!-- <div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">- Mes</label>
														</div> -->
														<!-- <div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Mes 1:</label>
															<input type="text" id="mes_1" name="mes_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Mes 2:</label>
															<input type="text" id="mes_2" name="mes_2"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Mes 3:</label>
															<input type="text" id="mes_3" name="mes_3"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Mes 4:</label>
															<input type="text" id="mes_4" name="mes_4"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Mes 5:</label>
															<input type="text" id="mes_5" name="mes_5"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Mes 6:</label>
															<input type="text" id="mes_6" name="mes_6"  class="form-control" readonly >
														</div> -->
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">- Horas Extras</label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Hora extra Mes 1:</label>
															<input type="text" id="hora_extra_mes_1" name="hora_extra_mes_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Hora extra Mes 2:</label>
															<input type="text" id="hora_extra_mes_2" name="hora_extra_mes_2"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Hora extra Mes 3:</label>
															<input type="text" id="hora_extra_mes_3" name="hora_extra_mes_3"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Hora extra Mes 4:</label>
															<input type="text" id="hora_extra_mes_4" name="hora_extra_mes_4"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Hora extra Mes 5:</label>
															<input type="text" id="hora_extra_mes_5" name="hora_extra_mes_5"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Hora extra Mes 6:</label>
															<input type="text" id="hora_extra_mes_6" name="hora_extra_mes_6"  class="form-control" readonly >
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">- Comisiones</label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Comisiones Mes 1:</label>
															<input type="text" id="comisiones_mes_1" name="comisiones_mes_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Comisiones Mes 2:</label>
															<input type="text" id="comisiones_mes_2" name="comisiones_mes_2"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Comisiones Mes 3:</label>
															<input type="text" id="comisiones_mes_3" name="comisiones_mes_3"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Comisiones Mes 4:</label>
															<input type="text" id="comisiones_mes_4" name="comisiones_mes_4"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Comisiones Mes 5:</label>
															<input type="text" id="comisiones_mes_5" name="comisiones_mes_5"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Comisiones Mes 6:</label>
															<input type="text" id="comisiones_mes_6" name="comisiones_mes_6"  class="form-control" readonly >
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">- Bonificaciones</label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Bonificaciones Mes 1:</label>
															<input type="text" id="bonificaciones_mes_1" name="bonificaciones_mes_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Bonificaciones Mes 2:</label>
															<input type="text" id="bonificaciones_mes_2" name="bonificaciones_mes_2"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Bonificaciones Mes 3:</label>
															<input type="text" id="bonificaciones_mes_3" name="bonificaciones_mes_3"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Bonificaciones Mes 4:</label>
															<input type="text" id="bonificaciones_mes_4" name="bonificaciones_mes_4"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Bonificaciones Mes 5:</label>
															<input type="text" id="bonificaciones_mes_5" name="bonificaciones_mes_5"  class="form-control" readonly >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Bonificaciones Mes 6:</label>
															<input type="text" id="bonificaciones_mes_6" name="bonificaciones_mes_6"  class="form-control" readonly >
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
															<input type="text" id="empresa_1" name="empresa_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="cargo_1" name="cargo_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="date" id="desde_1" name="desde_1" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="date" id="hasta_1" name="hasta_1" class="form-control" readonly >
														</div>

														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="empresa_2" name="empresa_2"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="cargo_2" name="cargo_2"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="date" id="desde_2" name="desde_2" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="date" id="hasta_2" name="hasta_2" class="form-control" readonly >
														</div>

														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="empresa_3" name="empresa_3"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="cargo_3" name="cargo_3"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="date" id="desde_3" name="desde_3" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="date" id="hasta_3" name="hasta_3" class="form-control" readonly >
														</div>

														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="empresa_4" name="empresa_4"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="cargo_4" name="cargo_4"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="date" id="desde_4" name="desde_4" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="date" id="hasta_4" name="hasta_4" class="form-control" readonly >
														</div>

														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext2">Referencias Familiares, Bancarias y Crediticias</label>
														</div>
														
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Familiar</label>
														</div>

														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Nombre:</label>
															<input type="text" id="nombre_referencia_1" name="nombre_referencia_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Parentesco:</label>
															<input type="text" id="parentesco_referencia_1" name="parentesco_referencia_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Domicilio:</label>
															<textarea class="form-control" readonly id="domicilio_1" name="domicilio_1" rows="1"></textarea>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Telefono:</label>
															<input type="text" id="telefono_1" name="telefono_1"  class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Trabajo:</label>
															<input type="text" id="trabajo_1" name="trabajo_1"  class="form-control" readonly>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Dirección Trabajo:</label>
															<textarea class="form-control" readonly id="trabajo_direccion_1" name="trabajo_direccion_1" rows="1"></textarea>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Telefono trabajo:</label>
															<input type="text" id="trabajo_telefono_1" name="trabajo_telefono_1"  class="form-control" readonly>
														</div>

														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Familiar</label>
														</div>

														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Nombre:</label>
															<input type="text" id="nombre_referencia_2" name="nombre_referencia_2"  class="form-control" readonly >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Parentesco:</label>
															<input type="text" id="parentesco_referencia_2" name="parentesco_referencia_2"  class="form-control" readonly >
														</div>
														<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Domicilio:</label>
															<textarea class="form-control" readonly id="domicilio_2" name="domicilio_2" rows="1"></textarea>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Telefono:</label>
															<input type="text" id="telefono_2" name="telefono_2"  class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Trabajo:</label>
															<input type="text" id="trabajo_2" name="trabajo_2"  class="form-control" readonly>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Dirección Trabajo:</label>
															<textarea class="form-control" readonly id="trabajo_direccion_2" name="trabajo_direccion_2" rows="1"></textarea>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Telefono trabajo:</label>
															<input type="text" id="trabajo_telefono_2" name="trabajo_telefono_2"  class="form-control" readonly>
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
															<input type="text" id="banco_1" name="banco_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="tipo_cuenta_1" name="tipo_cuenta_1"  class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="no_cuenta_1" name="no_cuenta_1" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="saldo_actual_1" name="saldo_actual_1" class="form-control" readonly >
														</div>

														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="banco_2" name="banco_2"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="tipo_cuenta_2" name="tipo_cuenta_2"  class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="no_cuenta_2" name="no_cuenta_2" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="saldo_actual_2" name="saldo_actual_2" class="form-control" readonly >
														</div>

														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext2">Referencias Crediticias</label>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Banco Prestamo:</label>
															<input type="text" id="banco_prestamo_1" name="banco_prestamo_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Tipo de Préstamo:</label>
															<input type="text" id="tipo_prestamo_1" name="tipo_prestamo_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">No. de Préstamo:</label>
															<input type="text" id="no_prestamo_1" name="no_prestamo_1"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Monto:</label>
															<input type="text" id="monto_1" name="monto_1" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Saldo Actual:</label>
															<input type="text" id="saldo_actual_prestamo_1" name="saldo_actual_prestamo_1" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Pago Mensual:</label>
															<input type="text" id="pago_mensual_prestamo_1" name="pago_mensual_prestamo_1" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Fecha Vencimiento:</label>
															<input type="date" id="fecha_vencimiento_prestamo_1" name="fecha_vencimiento_prestamo_1" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Banco Prestamo:</label>
															<input type="text" id="banco_prestamo_2" name="banco_prestamo_2" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Tipo de Préstamo:</label>
															<input type="text" id="tipo_prestamo_2" name="tipo_prestamo_2" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">No. de Préstamo:</label>
															<input type="text" id="no_prestamo_2" name="no_prestamo_2"  class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Monto:</label>
															<input type="text" id="monto_2" name="monto_2" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Saldo Actual:</label>
															<input type="text" id="saldo_actual_prestamo_2" name="saldo_actual_prestamo_2" class="form-control" readonly >
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Pago Mensual:</label>
															<input type="text" id="pago_mensual_prestamo_2" name="pago_mensual_prestamo_2" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Fecha Vencimiento:</label>
															<input type="date" id="fecha_vencimiento_prestamo_2" name="fecha_vencimiento_prestamo_2" class="form-control" readonly >
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
												</div>
												<!-- info. Codeudor cuando tenga -->
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;margin-top:10px;" id="info_Codeudor_div" name="info_Codeudor_div">
														<h3 class="titleinf"><img class="usericon" src="../img/client_icon.png" alt="Cliente"> Información Codeudor</h3>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;margin-top:10px;" id="bodyAgregarClienteCoVer" ></div>
												<!-- -->
											</div>
										</div>
										<div id="apartamento" class="tab-pane ">
											<div class="secinfo">
												<div class="row" >
													<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;margin-top:10px;">
														<h3 class="infoventtittle">Información de venta</h3>
														<div style="text-align: right;">
															<button onclick="agregarParqueoExterno()" class="agregarPagoComision" type="button" >Agregar Parqueo</button>	
														</div>
														
														<div class="secinfo" id="renderList" name="renderList"></div>
													</div>
												</div>	
											</div>	
										</div>
										<div id="estado_cuenta" class="tab-pane ">
											<div class="secinfo">
												<div class="row" >
													<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;margin-top:10px;">
														<h3 class="infoventtittle"><img  class="engageicon" src="../img/handshake 1.png" alt="Italian Trulli" >Estado de Cuenta</h3>
													</div>
													<input type="hidden" id="idEnganche" name="idEnganche">
													<input type="hidden" id="pagosEngancheEng" name="pagosEngancheEng">
													<input type="hidden" id="idOcaInfo" name="idOcaInfo">
													<input type="hidden" id="nombreClienteInfo" name="nombreClienteInfo">
												
													<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:right;">
															<label id="pendPago" name="pendPago"></label>
															<input type="color" id="colorAlerta" name="colorAlerta" value="" disabled="disabled">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<div class="table-responsive">
																<table id="resultadoEncabezado" class="table table-sm table-hover"  style="width:100%">
																</table>
															</div>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<div class="row" >
																<div class="col-lg-10 col-md-10 col-xs-10" style="margin-bottom:10px;"></div>
																<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
																	<input type="hidden" id="idEngancheEstadoCuenta" name="idEngancheEstadoCuenta">
																	<button onclick="estadoCuentaPdf()" class="guardar" title="Generar PDF de estado de cuenta" type="button">Generar</button>
																</div>
															</div>
														</div>
														<ul class="nav nav-tabs" role="tablist">
															<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#estadoCuentaCliente">Estado de Cuenta Cliente</a></li>
															<li class="nav-item"><a class="nav-link " data-toggle="tab" href="#estadoCuentaComision">Estado de Cuenta Comisiones</a></li>
														</ul>
														<div class="col-lg-12 col-md-12 col-xs-12 tab-content" id="renderDatosEstadoCuenta" name="renderDatosEstadoCuenta">
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
										</div>
										<div id="adjuntos" class="tab-pane ">
											<div class="row" id="rows">
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;margin-top:10px;">
													<h3 class="infoventtittle">Adjuntos</h3>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" id="divListadoAdjuntos" style="padding:5px 15px;">
													<div class="col-lg-12 col-md-12 col-xs-10">
														<input type="hidden" id="idOcaInfo" name="idOcaInfo">
														<label class="nodpitext">Filtro:</label>
														<select class="form-control" name="filtro-adjuntos" id="filtro-adjuntos"
															placeholder="Seleccione un filtro" onchange="verAdjuntos()" readonly>
														</select>
													</div>
													<hr />
													<div class="table-responsive">
														<table id="resultadoAdjuntos" class="table table-sm table-hover" style="width:100%;">
														</table>
													</div>
												</div>
												<div class="col-lg-8 col-md-8 col-xs-10" id="divVerAdjuntos" style="height:65vh"></div>
											</div>
										</div>
										<div id="adjuntosFha" class="tab-pane ">
											<div class="row" id="rows">
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;margin-top:10px;">
													<h3 class="infoventtittle">Adjuntos Fha</h3>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" id="divListadoAdjuntos" style="padding:5px 15px;">
													<div class="col-lg-12 col-md-12 col-xs-10">
														<input type="hidden" id="idOcaInfo" name="idOcaInfo">
														<label class="nodpitext">Filtro:</label>
														<select class="form-control" name="filtro-adjuntos_0" id="filtro-adjuntos_0"
															placeholder="Seleccione un filtro" onchange="verAdjuntosFha()" readonly>
														</select>
													</div>
													<hr />
													<div class="table-responsive">
														<table id="resultadoAdjuntosFha" class="table table-sm table-hover" style="width:100%;">
														</table>
													</div>
												</div>
												<div class="col-lg-8 col-md-8 col-xs-10" id="divVerAdjuntos_fha" style="height:65vh"></div>
											</div>
										</div>

										<div id="pdf" class="tab-pane ">
											<div class="row" id="rows">
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;margin-top:10px;">
													<h3 class="infoventtittle">Doc. FHA</h3>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" id="divVerAdjuntosFha" style="height:65vh"></div>
											</div>
										</div>
										<div id="pdfExtra" class="tab-pane ">
											<div class="row" id="rows">
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;margin-top:10px;">
													<h3 class="infoventtittle">Info. Extra</h3>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" id="divListadoAdjuntos" style="padding:5px 15px;">
													<form autocomplete="off" enctype="multipart/form-data" id="frmListaAdjuntoExtra"
                            							name="frmListaAdjuntoExtra" method="POST">
														<div class="col-lg-12 col-md-12 col-xs-10">
															<input type="hidden" id="idOcaApartamento" name="idOcaApartamento">
															<label class="nodpitext">Filtro:</label>
															<select class="form-control" name="filtro-adjuntos-extra_0" id="filtro-adjuntos-extra_0"
																placeholder="Seleccione un filtro" onchange="verAdjuntosExtra()" readonly>
															</select>
														</div>
														<hr />
														<div class="table-responsive">
															<table id="resultadoAdjuntosExtra" class="table table-sm table-hover" style="width:100%;">
															</table>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10">
															<label class="draganddroptexttitle" for="mail">Subir archivos aquí:</label>
															<input class="draganddrop" type="file" id="fliesAdjuntos[]" name="fliesAdjuntos[]"
																placeholder="Arrastra y suelta aquí "
																accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" multiple>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10">
															<label class="nodpitext">Observaciones:</label>
															<textarea class="form-control" id="comentario" name="comentario" rows="2"></textarea>
														</div>
														
														<div class="col-lg-12 col-md-12 col-xs-10" style="text-align:center;padding:5px">
															<button onclick="guardarAdjuntosExtra()" class="guardar" type="button">Adjuntar</button>
															
														</div>
													</form>
												</div>
												<div class="col-lg-8 col-md-8 col-xs-10" id="divVerAdjuntos_extra" style="height:65vh"></div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="modal fade" id="modalParqueoExterno">
									<div class="modal-dialog mw-40">
										<div class="modal-content" >
											<div class="modal-header">
												<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Agregar Parqueos externos</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body" id="bodyParqueoExterno" style="padding:5px 15px;" >
												<div class="secinfo" >
													<form autocomplete="off"  enctype="multipart/form-data"  id="frmParqueoExterno" name="frmParqueoExterno" method="POST">
														<div class="row" >
															<div id="divAlertParqueoExterno" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															</div>													
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<div class="row" >
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
																		<label class="nodpitext">Cantidad de apartamentos:</label>
																	</div>
																	<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
																		<input  type="number" id="parqueo_externo" name="parqueo_externo" class="form-control">
																	</div>
																</div>
															</div>
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
																<button onclick="guardarParqueoExterno()" class="guardar" type="button">Guardar</button>
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

							</diV>
						</diV>
					</div>	
				</div>
			</div>
		</div>
		<script type="text/javascript">
			getTable(0);
			function getTable(proyecto){
				var formData = new FormData;
				formData.append("proyecto", proyecto);
				var id_perfil = $("#id_perfil").val();
				$.ajax({
					url: "./arrayDisponibilidad.php?get_tabla_disponibilidad=true",
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
						var output = '<div class="row">';
						var outputN =''; 
						var count =0;
						$.each(response.proyectos,function(i,e) {
							outputN ="";
							output += '	<div class="col-md-12 " style="text-align:center"><Label class="results">'+e.nombre+'</label></div>';
							
							$.each(e.torres,function(i,t) {
								
								output += '	<div class="col-md-6 " style="text-align:center"><Label class="results"></label>';
								output += '<div class="table-responsive">';
								output += '<table class="table table-bordered table-sm table-hover"  style="width:100%">';
								outputN =''; 
								output += '<tr>';
								var colspan = t.length + 1;
								if(e.nombre=='NAOS'){
									var parqueos_externos = ' (Parqueos externos: '+t.parqueo_externo_total+')';
								}else{
									var parqueos_externos = '';
								}
								output += '<th colspan="'+colspan +'" style="text-align:center">Torre/Fase '+t.name+parqueos_externos+' </th>';
								var parqueo_externo_total = 0;
								$.each(t.niveles,function(i,n) {
									count++;
									outputN += '<tr >';
									outputN += '<td style="width:20%;">Nivel '+n.name+'</td>';
									$.each(n.apartamentos,function(i,a) {
										parqueo_externo_total += parseFloat(a.parqueo_externo); 
										if(a.color == '#e64a19'){
											var cursor = "cursor:pointer";
										}else{
											var cursor = "";
										}
										if(id_perfil!=1 && id_perfil!=4){
											var onClick='abrirModal('+a.idCliente+','+a.idEnganche+',\''+a.color+'\',\''+a.name+'\');';
										}else {
											var onClick='';
										}
										outputN += '<td style= "'+cursor+'" onclick="'+onClick+'"  bgcolor= "'+a.color+'" >'+a.name+'</td>';
										count++;
									});
									for( var i = count; i<=t.length; i++){
										outputN += '<td >N/A</td>';
									}
									count = 0;
									outputN += '</tr >';
								});
								output += '</tr>';
								output += outputN;
								output += ' </table>';
								output += ' </div>';
								output += ' </div>';
							});
							

						});
						output += '</div>';
						$("#divTableDisp").html(output);
					},
					error:function (){
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
			}
			function agregarParqueoExterno(){
					document.getElementById("frmParqueoExterno").reset();
					$("#modalParqueoExterno").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});		
				}
			function guardarParqueoExterno(){
				console.log("funcion guardar torre");
				var error = 0;
				var msjError = 'Campos Obligatorios: <br>';
				if($("#parqueo_externo").val()==''){
					error++;
					msjError =msjError+ '*cantidad de parqueos <br>'
				}
				if(error==0){
					var formData = new FormData(document.getElementById("frmParqueoExterno"));
					formData.append("apartamentoNo", $("#apartamentoInfo").val());
					$.ajax({
						url: "./cliente.php?agregar_editar_parqueo_externo=true",
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
							$("#divAlertParqueoExterno").html('<div class="alert alert-success">'+response.mss+'</div>');
							setTimeout(function(){
								$("#modalParqueoExterno").modal("hide");
								$("#divAlertParqueoExterno").html('');
							},1000)
							abrirModal(response.idCliente,response.idEnganche,response.color,response.id);						
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}else{
					$('#bodyParqueoExterno').animate({scrollTop:0}, 'fast');
					$("#divAlertParqueoExterno").html('<div class="alert alert-danger">'+msjError+'</div>');
						setTimeout(function(){
							$("#divAlertParqueoExterno").html('');
						},5000)
				}
				
			}
			function abrirModal(idCliente,idEnganche,color,apartamento){
				if(color == '#e64a19'){
					$("#modalPrincial input").val("");
					$("#modalPrincial textarea").val("");
    				$("#modalPrincial select").val("");
					$("#idOcaInfo").val(idCliente);
					buscarClienteUnico(idCliente,color);
					verCodeudor(idCliente,idEnganche)
					getEngancheDetalle(idEnganche)
					verAdjuntosInicial(idCliente);
					verAdjuntosInicialFha(idCliente);
					verAdjuntosInicialExtra(apartamento);
					getFiltroAdjuntos();
					getFiltroAdjuntosFha();
					getFiltroAdjuntosExtra();
					verPDF(idCliente);


				$("#modalPrincial").modal({
					backdrop: 'static',
					keyboard: false,
					show: true
				});
				}
				

			}
			function verAdjuntosInicial(idCliente) {
				if(idCliente==undefined){
					var idCliente = $("#idOcaInfo").val();  
				}else{
					$("#idOcaInfo").val(idCliente);
				}
				// var nombreCLiente = $("#nombreClienteInfo").val();
				var id_tipo_documento = $("#filtro-adjuntos option:selected").val();
				var formData = new FormData;

				formData.append("idOcaCliente", idCliente);
				formData.append("id_tipo_documento", id_tipo_documento);

				$("#modalVerAdjuntos").modal({
					backdrop: 'static',
					keyboard: false,
					show: true
				});
				$.ajax({
					url: "./cliente.php?get_adjuntos_listado=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						var output = ''
						$.each(response.listado_adjuntos, function (i, e) {
							output += `	<tr>
												<td style="width:90%;">
													<label class="nodpitext">${e.nombre}</label>
												</td>
												<td style="width:10%;">
													<button
														onclick="eliminarAdjuntos(${e.id_archivo})"
														class="btn btn-sm btn-danger"
														type="button"
													>
														<i class="fa fa-times"></i>
													</button>
												</td>
											</tr>`;
						});
						var tb = document.getElementById('resultadoAdjuntos');
						while (tb.rows.length >= 1) {
							tb.deleteRow(0);
						}
						$('#resultadoAdjuntos').append(output);
					},
					error: function () {
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
				if(id_tipo_documento==undefined || id_tipo_documento==""){
					console.log(id_tipo_documento);
				}else{
					$("#divVerAdjuntos").html(`	</iframe>
					<iframe
						frameborder='0'
						type='application/pdf'
						style='width:100%; height:100%' align='right'
						
					>
					</iframe>`);
				}
			
			}
			function verAdjuntosInicialFha(idCliente) {
				if(idCliente==undefined){
					var idCliente = $("#idOcaInfo").val();  
				}else{
					$("#idOcaInfo").val(idCliente);
				}
				// var nombreCLiente = $("#nombreClienteInfo").val();
				var id_tipo_documento = $("#filtro-adjuntos_0 option:selected").val();
				var formData = new FormData;

				formData.append("idOcaCliente", idCliente);
				formData.append("id_tipo_documento", id_tipo_documento);

				$("#modalVerAdjuntos").modal({
					backdrop: 'static',
					keyboard: false,
					show: true
				});
				$.ajax({
					url: "./cliente.php?get_adjuntos_listado_fha=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						var output = ''
						$.each(response.listado_adjuntos, function (i, e) {
							output += `	<tr>
												<td style="width:90%;">
													<label class="nodpitext">${e.nombre}</label>
												</td>
												<td style="width:10%;">
													<button
														onclick="eliminarAdjuntos(${e.id_archivo})"
														class="btn btn-sm btn-danger"
														type="button"
													>
														<i class="fa fa-times"></i>
													</button>
												</td>
											</tr>`;
						});
						var tb = document.getElementById('resultadoAdjuntosFha');
						while (tb.rows.length >= 1) {
							tb.deleteRow(0);
						}
						$('#resultadoAdjuntosFha').append(output);
					},
					error: function () {
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
				if(id_tipo_documento==undefined || id_tipo_documento==""){
					console.log(id_tipo_documento);
				}else{
					$("#divVerAdjuntos_fha").html(`	</iframe>
					<iframe
						frameborder='0'
						type='application/pdf'
						style='width:100%; height:100%' align='right'
						
					>
					</iframe>`);
				}
			
			}

			

			function verAdjuntosInicialExtra(idCliente) {
				if(idCliente==undefined){
					var idCliente = $("#idOcaInfo").val();  
				}else{
					$("#idOcaInfo").val(idCliente);
				}
				// var nombreCLiente = $("#nombreClienteInfo").val();
				var id_tipo_documento = $("#filtro-adjuntos_0 option:selected").val();
				var formData = new FormData;

				formData.append("idOcaCliente", idCliente);
				formData.append("id_tipo_documento", id_tipo_documento);

				$("#modalVerAdjuntos").modal({
					backdrop: 'static',
					keyboard: false,
					show: true
				});
				$.ajax({
					url: "./cliente.php?get_adjuntos_listado_extra=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						var output = ''
						$.each(response.listado_adjuntos, function (i, e) {
							output += `	<tr>
												<td style="width:90%;">
													<label class="nodpitext">${e.nombre}</label>
												</td>
												<td style="width:10%;">
													<button
														onclick="eliminarAdjuntos(${e.id_archivo})"
														class="btn btn-sm btn-danger"
														type="button"
													>
														<i class="fa fa-times"></i>
													</button>
												</td>
											</tr>`;
						$("#comentario").val(e.comentario);
						});
						var tb = document.getElementById('resultadoAdjuntosExtra');
						while (tb.rows.length >= 1) {
							tb.deleteRow(0);
						}
						$('#resultadoAdjuntosExtra').append(output);
					},
					error: function () {
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
				if(id_tipo_documento==undefined || id_tipo_documento==""){
					console.log(id_tipo_documento);
				}else{
					$("#divVerAdjuntos_extra").html(`	</iframe>
					<iframe
						frameborder='0'
						type='application/pdf'
						style='width:100%; height:100%' align='right'
						
					>
					</iframe>`);
				}
			
			}
			function verAdjuntos(idCliente) {
				if(idCliente==undefined){
					var idCliente = $("#idOcaInfo").val();  
				}else{
					$("#idOcaInfo").val(idCliente);
				}
				// var nombreCLiente = $("#nombreClienteInfo").val();
				var id_tipo_documento = $("#filtro-adjuntos option:selected").val();
				var formData = new FormData;

				formData.append("idOcaCliente", idCliente);
				formData.append("id_tipo_documento", id_tipo_documento);

				$("#modalVerAdjuntos").modal({
					backdrop: 'static',
					keyboard: false,
					show: true
				});
				$.ajax({
					url: "./cliente.php?get_adjuntos_listado=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						var output = ''
						$.each(response.listado_adjuntos, function (i, e) {
							output += `	<tr>
												<td style="width:90%;">
													<label class="nodpitext">${e.nombre}</label>
												</td>
												<td style="width:10%;">
													<button
														onclick="eliminarAdjuntos(${e.id_archivo})"
														class="btn btn-sm btn-danger"
														type="button"
													>
														<i class="fa fa-times"></i>
													</button>
												</td>
											</tr>`;
						});
						var tb = document.getElementById('resultadoAdjuntos');
						while (tb.rows.length >= 1) {
							tb.deleteRow(0);
						}
						$('#resultadoAdjuntos').append(output);
					},
					error: function () {
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
				if(id_tipo_documento==undefined || id_tipo_documento==""){
					console.log(id_tipo_documento);
				}else{
					$("#divVerAdjuntos").html(`	</iframe>
					<iframe
						frameborder='0'
						type='application/pdf'
						style='width:100%; height:100%' align='right'
						src='./adjuntos.php/${$("#nombreClienteInfo").val()}?idCliente=${idCliente}&nombreCliente=${$("#nombreClienteInfo").val()}&id_tipo_documento=${id_tipo_documento}#page=1&zoom=50'
					>
					</iframe>`);
				}
			
			}
			function verAdjuntosFha(idCliente) {
				if(idCliente==undefined){
					var idCliente = $("#idOcaInfo").val();  
				}else{
					$("#idOcaInfo").val(idCliente);
				}
				// var nombreCLiente = $("#nombreClienteInfo").val();
				var id_tipo_documento = $("#filtro-adjuntos_0 option:selected").val();
				var formData = new FormData;

				formData.append("idOcaCliente", idCliente);
				formData.append("id_tipo_documento", id_tipo_documento);

				$("#modalVerAdjuntosFha").modal({
					backdrop: 'static',
					keyboard: false,
					show: true
				});
				$.ajax({
					url: "./cliente.php?get_adjuntos_listado_fha=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						var output = ''
						$.each(response.listado_adjuntos, function (i, e) {
							output += `	<tr>
												<td style="width:90%;">
													<label class="nodpitext">${e.nombre}</label>
												</td>
												<td style="width:10%;">
													<button
														onclick="eliminarAdjuntos(${e.id_archivo})"
														class="btn btn-sm btn-danger"
														type="button"
													>
														<i class="fa fa-times"></i>
													</button>
												</td>
											</tr>`;
						});
						var tb = document.getElementById('resultadoAdjuntosFha');
						while (tb.rows.length >= 1) {
							tb.deleteRow(0);
						}
						$('#resultadoAdjuntosFha').append(output);
					},
					error: function () {
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
				if(id_tipo_documento==undefined || id_tipo_documento==""){
					console.log(id_tipo_documento);
				}else{
					$("#divVerAdjuntos_fha").html(`	</iframe>
					<iframe
						frameborder='0'
						type='application/pdf'
						style='width:100%; height:100%' align='right'
						src='./adjuntosFha.php/${$("#nombreClienteInfo").val()}?idCliente=${idCliente}&nombreCliente=${$("#nombreClienteInfo").val()}&id_tipo_documento=${id_tipo_documento}#page=1&zoom=50'
					>
					</iframe>`);
				}
			
			}

			function verAdjuntosExtra(idCliente) {
				if(idCliente==undefined){
					var idCliente = $("#idOcaApartamento").val();  
				}else{
					$("#idOcaApartamento").val(idCliente);
				}
				// var nombreCLiente = $("#nombreClienteInfo").val();
				var id_tipo_documento = $("#filtro-adjuntos-extra_0 option:selected").val();
				var formData = new FormData;

				formData.append("idOcaCliente", idCliente);
				formData.append("id_tipo_documento", id_tipo_documento);

				$("#modalVerAdjuntosFha").modal({
					backdrop: 'static',
					keyboard: false,
					show: true
				});
				$.ajax({
					url: "./cliente.php?get_adjuntos_listado_extra=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						var output = ''
						$.each(response.listado_adjuntos, function (i, e) {
							output += `	<tr>
												<td style="width:90%;">
													<label class="nodpitext">${e.nombre}</label>
												</td>
												<td style="width:10%;">
													<button
														onclick="eliminarAdjuntos(${e.id_archivo})"
														class="btn btn-sm btn-danger"
														type="button"
													>
														<i class="fa fa-times"></i>
													</button>
												</td>
											</tr>`;
						});
						var tb = document.getElementById('resultadoAdjuntosExtra');
						while (tb.rows.length >= 1) {
							tb.deleteRow(0);
						}
						$('#resultadoAdjuntosExtra').append(output);
					},
					error: function () {
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
				if(id_tipo_documento==undefined || id_tipo_documento==""){
					console.log(id_tipo_documento);
				}else{
					$("#divVerAdjuntos_extra").html(`	</iframe>
					<iframe
						frameborder='0'
						type='application/pdf'
						style='width:100%; height:100%' align='right'
						src='./adjuntosExtra.php/${$("#nombreClienteInfo").val()}?idCliente=${idCliente}&nombreCliente=${$("#nombreClienteInfo").val()}&id_tipo_documento=${id_tipo_documento}#page=1&zoom=50'
					>
					</iframe>`);
				}
			
			}
			function verPDF(idCliente){
				if(idCliente==undefined){
					var idCliente = $("#idOcaInfo").val();  
				}else{
					$("#idOcaInfo").val(idCliente);
				}
				$("#divVerAdjuntosFha").html(`	</iframe>
					<iframe
						frameborder='0'
						type='application/pdf'
						style='width:100%; height:100%' align='right'
						src='./pruebaMpdf.php/ResumenFha?idCliente=${idCliente}&docFHAPdf=true#page=1&zoom=100'
					>
					</iframe>`);

			}
			function getFiltroAdjuntos() {

				var idCliente = $("#idOcaInfo").val();
				const option = document.getElementById("filtro-adjuntos");

				let formData = new FormData();
				formData.append("idOcaCliente", idCliente);

				$.ajax({
					url: "./cliente.php?get_filtros_adjuntos=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						var output;
						output += ' <option value="">Seleccionar Tipo de documento</option> <option value="0">Mostrar todos</option>';
						$.each(response.filtros_adjuntos, function (i, e) {
							output += `<option value="${e.id_tipo_documento}">${e.nombre}</option>`;
						});
						for (let i = option.options.length; i >= 0; i--) {
							option.remove(i);
						}
						$('#filtro-adjuntos').append(output);
					},
					error: function () {
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
			}
			
			function getFiltroAdjuntosFha() {

				var idCliente = $("#idOcaInfo").val();
				const option = document.getElementById("filtro-adjuntos_0");

				let formData = new FormData();
				formData.append("idOcaCliente", idCliente);

				$.ajax({
					url: "./cliente.php?get_filtros_adjuntos_fha=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						var output;
						output += ' <option value="">Seleccionar Tipo de documento</option> <option value="0">Mostrar todos</option>';
						$.each(response.filtros_adjuntos, function (i, e) {
							output += `<option value="${e.id_tipo_documento}">${e.nombre}</option>`;
						});
						for (let i = option.options.length; i >= 0; i--) {
							option.remove(i);
						}
						$('#filtro-adjuntos_0').append(output);
					},
					error: function () {
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
			}

			function getFiltroAdjuntosExtra() {

				var idCliente = $("#idOcaInfo").val();
				const option = document.getElementById("filtro-adjuntos-extra_0");

				let formData = new FormData();
				formData.append("idOcaCliente", idCliente);

				$.ajax({
					url: "./cliente.php?get_filtros_adjuntos_extra=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						var output;
						output += ' <option value="">Seleccionar Tipo de documento</option> <option value="0">Mostrar todos</option>';
						$.each(response.filtros_adjuntos, function (i, e) {
							output += `<option value="${e.id_tipo_documento}">${e.nombre}</option>`;
						});
						for (let i = option.options.length; i >= 0; i--) {
							option.remove(i);
						}
						$('#filtro-adjuntos-extra_0').append(output);
					},
					error: function () {
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
			}

			function buscarClienteUnico(idCotizacion,color){
				console.log("funcion buscar cliente unico");
				//var formData = new FormData(document.getElementById("frmBuscarCliente"));
				var formData = new FormData;
				formData.append("idCotizacion", idCotizacion);
				if(color == '#e64a19'){
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

							//Info Apartamento, Vendedor y CLiente
							$("#idCliente").val(e.idCliente);
								$("#idOcaCliente").val(e.idCliente);
								$("#idOcaApartamento").val(e.apartamento);
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
								$("#Estatura").val(e.estatura);
								$("#peso").val(e.peso);
								
								if(e.creditoFHA=='si'){
									document.getElementById('si').checked = true;
									console.log("credito fha "+e.creditoFHA);
								}else if(e.creditoFHA=='no'){
									document.getElementById('no').checked = true;
									console.log("credito fha "+e.creditoFHA);
								}
								$("#fechaEmisionDpiCl").val(e.fechaEmisionDpi);

							if(e.price!=''){
								$("#precioTotalInfo").val('Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.price));
							}
							var montoVenta = parseFloat(e.contracargo) + parseFloat(e.bodegaPrecioMonto) + parseFloat(e.parqueoExtraMonto) + parseFloat(e.price) - parseFloat(e.descuento_porcentual_monto) ;
							if(count>0){
									active='';
								}
								if(e.idEnganche<=0){
									ul='';
									div='';
								}else{
									ul =ul + '<li class="nav-item"><a class="nav-link '+active+'" data-toggle="tab" href="#'+e.apartamento+'">'+e.apartamento+'</a></li>';
									div += '<div id="'+e.apartamento+'" class="container tab-pane '+active+'">';
									div += '<div class="secinfo">';
									div += '<div class="row" >';
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;margin-top:10px;"><h3 style="" class="infoventtittle"><img class="saleicon" src="../img/apartment_info.png" alt="Cliente"> Información de apartamento</h3></div>';
									div += '<div class="col-lg-6 col-md-6 col-xs-10" >'
									div += '</div>'	
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;text-align:right;" ></div>';						
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;"><label class="niveltittle">Nivel:</label></div>';
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;"><input id="nivelInfo" value ="'+e.nivel+'" class="form-control" readonly></div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;"><label class="aptotittle">Apartamento:</label></div>';
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;"><input id="apartamentoInfo" value ="'+e.apartamento+'" class="form-control" readonly></div>';
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;"><label class="sizetittle">Tamaño en mt2:</label></div>'
									div +='<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;"><input id="tamanioInfo" value ="'+e.sqmts+'" class="form-control" readonly></div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="habtittle">Habitaciones:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="habitacionInfo"  value ="'+e.cuartos+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="areabodetittle">Área de bodega en mt2:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="areaBodegaInfo" value ="'+e.bodega_mts+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="parqmototittle">Parqueo de moto:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="parqueoMotoInfo" value ="'+e.parqueo_moto+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="parqcarrotittle">Parqueo de carro:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="parqueoCarroInfo" value ="'+e.parqueo+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="areajardintittle">Área de Jardin en mt2:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="areaJardinInfo" value ="'+e.jardin_mts+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="preciototaltittle">Precio total (GtQ):</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div +='<input id="precioTotalInfo" value ="'+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.price)+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="preciototaltittle">Parqueos Externos:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div +='<input id="parqueoExternoInfo" value ="'+e.parqueo_externo+'" class="form-control" readonly>'
									div += '</div>'
									div += '</div></div>';
									div += '<div class="secinfo">';
									div += '<div class="row" >';
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;;margin-top:10px;">'
									div += '<h3 style="padding-left:0px;" class="infsaletittle"><img class="saleicon" src="../img/sale_info.png" alt="Cliente"> Información de venta</h3>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="descperceptittle">Descuento porcentual %: </label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="descuentoInfo" value="'+e.descuento_porcentual+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="engagetittle">Enganche: </label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="engancheInfo" value="'+e.enganchePorc+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="engagepaymentstittle">Pagos de enganche: </label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="pagosEngancheInfo" value="'+e.pagosEnganche+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="plazofin">Plazo de financimiento: </label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="plazoFinanciamientoInfo" value="'+e.plazoFinanciamiento+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="parqueoextra">Parqueo extra:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="parqueoClienteInfo" value="'+e.parqueosExtras+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="bodegaextra">Bodegas extra:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="bodegaClienteInfo" value="'+e.bodegasExtras+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="montoreservatittle">Monto reserva:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="montoReservaClienteInfo" value="'+e.MontoReserva+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="montoreservatittle">Precio de Venta:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="montoPrecioVenta" value="'+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoVenta)+'" class="form-control" readonly>'
									div += '</div>'
									div += '</div></div>';
									div += '<div class="secinfo">';
									div += '<div class="row" >';
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;margin-top:10px;">'
									div += '<h3 style="padding-left:0px;" class="infovendtittle"><img class="iconselltittle" src="../img/client_icon.png" alt="Cliente"> Información del vendedor</h3>'
									div += '</div>'														
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="sellernametittle">Nombre del vendedor:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="vendedorInfo" value="'+e.nombreVendedor+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="sellermailtittle">Correo electronico:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="emailVendedorInfo" value="'+e.mailVendedor+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="sellerphonetittle">Teléfono:</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									div += '<input id="telefonoVendedorInfo" value="'+e.telefonoVendedor+'" class="form-control" readonly>'
									div += '</div>'							
									div += '</div></div>';
									div += '</div>';
									count ++;
								}
						});
						if(ul!=''){
							ul = ul + '</ul><div class="tab-content" id="renderDatosApartamento" name="renderDatosApartamento"></div>';
						}
						$("#renderList").html(ul);
						$("#renderDatosApartamento").html(div);
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

							
							var caja = e.caja == '' ? 0 : e.caja;
							var bancos = e.bancos == '' ? 0 : e.bancos;
							var cuentas_cobrar =e.cuentas_cobrar == '' ? 0 : e.cuentas_cobrar;
							var terrenos = e.terrenos == '' ? 0 : e.terrenos;
							var viviendas = e.viviendas == '' ? 0 : e.viviendas;
							var vehiculos = e.vehiculos == '' ? 0 : e.vehiculos;
							var inversiones = e.inversiones == '' ? 0 : e.inversiones;
							var bonos = e.bonos == '' ? 0 : e.bonos;
							var acciones = e.acciones == '' ? 0 : e.acciones;
							var muebles = e.muebles == '' ? 0 : e.muebles;
							var total_activos = parseFloat(caja) + parseFloat(bancos)+parseFloat(cuentas_cobrar) + parseFloat(terrenos)+parseFloat(viviendas) + parseFloat(vehiculos)+parseFloat(inversiones) + parseFloat(bonos)+parseFloat(acciones) + parseFloat(muebles);

							var cuentas_pagar_corto_plazo = e.cuentas_pagar_corto_plazo == '' ? 0 : e.cuentas_pagar_corto_plazo;
							var cuentas_pagar_largo_plazo = e.cuentas_pagar_largo_plazo == '' ? 0 : e.cuentas_pagar_largo_plazo;
							var prestamos_hipotecarios = e.prestamos_hipotecarios == '' ? 0 : e.prestamos_hipotecarios;
							var sostenimiento_hogar = e.sostenimiento_hogar == '' ? 0 : e.sostenimiento_hogar;
							var alquiler = e.alquiler == '' ? 0 : e.alquiler;
							var prestamos = e.prestamos == '' ? 0 : e.prestamos;
							var impuestos = e.impuestos == '' ? 0 : e.impuestos;
							var extrafinanciamientos = e.extrafinanciamientos == '' ? 0 : e.extrafinanciamientos;
							var deudas_particulares = e.deudas_particulares == '' ? 0 : e.deudas_particulares;
							var total_pasivos = parseFloat(cuentas_pagar_corto_plazo) + parseFloat(cuentas_pagar_largo_plazo)+parseFloat(prestamos_hipotecarios) + parseFloat(sostenimiento_hogar)+parseFloat(alquiler) + parseFloat(prestamos) + parseFloat(impuestos) + parseFloat(extrafinanciamientos) + parseFloat(deudas_particulares);

							$("#total_activos").val(total_activos);
							$("#total_pasivos").val(total_pasivos);
							$("#total_patrimonio").val(total_activos-total_pasivos);

						});
						$.each(response.infoDetallePatrimonial,function(i,e) {
							$("#direccion_inmueble_1").val(e.direccion_inmueble_1);	
							$("#valor_inmueble_1").val(e.valor_inmueble_1);	
							$("#direccion_inmueble_2").val(e.direccion_inmueble_2);	
							$("#valor_inmueble_2").val(e.valor_inmueble_2);	
							$("#finca_1").val(e.finca_1);
							$("#folio_1").val(e.folio_1);
							$("#libro_1").val(e.libro_1);
							$("#departamento_1").val(e.departamento_nombre_1);		
							$("#finca_2").val(e.finca_2);
							$("#folio_2").val(e.folio_2);
							$("#libro_2").val(e.libro_2);
							$("#departamento_2").val(e.departamento_nombre_2);	
							$("#valor_estimado_1").val(e.valor_estimado_1);	
							$("#valor_estimado_2").val(e.valor_estimado_2);
							$("#marca_1").val(e.marca_1);	
							$("#tipo_vehiculo_1").val(e.tipo_vehiculo_1);	
							$("#modelo_vehiculo_1").val(e.modelo_vehiculo_1);	
							$("#marca_2").val(e.marca_2);	
							$("#tipo_vehiculo_2").val(e.tipo_vehiculo_2);	
							$("#modelo_vehiculo_2").val(e.modelo_vehiculo_2);	
							
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
							
							
					},
					error:function (){
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
				}
				
			}
			function getEngancheDetalle(idEnganche){
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
							if(parseFloat(response.diasPago)<=0){
								$("#colorAlerta").val('#04FA13');
							}else{
								if(parseFloat(response.totalPagado)>=parseFloat(response.debePagar)){
									$("#colorAlerta").val( '#04FA13');
								}else{
									if(parseFloat(response.cuotasSinEspecial) >0 && parseFloat(response.cuotasSinEspecial)<parseFloat(response.cuotas)){
										pagosAtrasados = parseFloat(parseFloat(response.debePagarSinEspecial) - parseFloat(response.totalPagado)).toFixed(2);
										pagosAtrasados = Math.ceil(pagosAtrasados/parseFloat(response.cuotasSinEspecial));
									}else{
										pagosAtrasados = parseFloat(parseFloat(response.debePagar) - parseFloat(response.totalPagado)).toFixed(2);
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
							if(pagosAtrasados<=0){
								var montoPendiente = 0;
							}else{
								var montoPendiente = response.pagoPendiente
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
							output+='<td><button onclick="verReciboReserva('+idEnganche+')" class="btn btn-xs" type="button"><img class="" src="../img/Engagement.png" alt="ver Recibo" ></button></td>';
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
									var buttonRecibo='<button onclick="verReciboPago('+e.idDetalle+')" class="btn btn-xs" type="button"><img class="" src="../img/Engagement.png" alt="ver Recibo" ></button>';
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
								output+='<td>'+buttonRecibo+'</td>';
								output+="</tr>";
							});
							var pagoExtraEnganche=0
							if(response.detallePagosExtraEng.length>0){
								var pagadoTotal=0
								var pagoExtraEnganche=0
								$.each(response.detallePagosExtraEng,function(i,e) {
									pagadoTotal += parseFloat(e.montoPagado);
									count++;
									acumulado += parseFloat(e.montoPagado);
									pagoExtraEnganche  += parseFloat(e.montoPagado);
									var checkEng='<i class="fa fa-times"></i> Pendiente';
									var buttonRecibo='<button onclick="verReciboPagoExtraEng('+e.idCobro+')" class="btn btn-xs" type="button"><img class="" src="../img/Engagement.png" alt="ver Recibo" ></button>';
									output+="<tr >";
									output+="<td >"+e.noPago+"<input id=\"noPago_"+e.noPago+"\" name=\"noPago[]\" type=\"hidden\" value=\""+e.noPago+"\" readonly=\"readonly\" ></td>";
									output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.montoPagado)+"</td>";
									output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.montoPagado)+"</td>";
									output+="<td>"+e.fechaFormat+"</td>";
									output+="<td>"+checkEng+"</td>";
									output+="<td>"+e.fechaFormat+"</td>";
									output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoEngancheTotal - acumulado)+"</td>";
									output+='<td>'+buttonRecibo+'</td>';
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
									outputExtra+='<td>'+buttonRecibo+'</td>';
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
							output+='<th colspan=\"5\" style="width:45%;">Observaciones</th>'
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
							});
							
							output+="<td >Pago Final</td>";
							output+="<td>Q"+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(pagoFinal)+"</td>";
							output+="<td></td>";
							output+='<td>'+checkF+'</td>';
							output+="<td></td>";
							output+="<td>"+response.fechaPagoFinalFormat+"</td>";
							output+="<td></td>";
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
				function estadoCuentaPdf() {
					idEnganche =  $("#idEngancheEstadoCuenta").val();
					window.location.href = './generarPdf.php/EstadoCuenta'+idEnganche+'?idEnganche='+idEnganche+'&estadoCuentaPdf=true#page=1&zoom=100';
				}
				function verReciboReserva(idPago){
					window.location.href = './generarPdf.php/reciboNo'+idPago+'?idPago='+idPago+'&reservaPdf=true#page=1&zoom=100';							
				}
				function verReciboPago(idPago){
					window.location.href = './generarPdf.php/reciboNo'+idPago+'?idPago='+idPago+'&reciboPdf=true#page=1&zoom=100';							
				}
				function verReciboPagoExtra(idPago){ 
					window.location.href = './generarPdf.php/reciboNo'+idPago+'?idPago='+idPago+'&reciboExtraPdf=true#page=1&zoom=100';		
				}
				function verReciboPagoExtraEng(idPago){ 
					window.location.href = './generarPdf.php/reciboNo'+idPago+'?idPago='+idPago+'&reciboExtraEngPdf=true#page=1&zoom=100';								
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
function verCodeudor(idCliente,idEnganche){

//console.log("funcion buscar cliente");
$("#idClienteCo").val(idCliente);
$("#idEngancheCo").val(idEnganche);
var formData = new FormData;
formData.append("idClienteCo", idCliente);
formData.append("idEngancheCo", idEnganche);
document.getElementById("h3_codeudor_div").style.display = "none";
document.getElementById("info_Codeudor_div").style.display = "none";
$.ajax({
	url: "./cliente.php?get_codeudores=true",
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
		var html ='<div id="renderListCo" name="">';
		var ul='<ul class="nav nav-tabs" role="tablist">';
		var div='';
		var active = 'active';
		var count = 0;
		var count1 = 0;

		acciones = '';
		alquiler = '';	
		bancos = '';
		bonos = '';	
		caja = '';	
		cuentas_cobrar = '';	
		cuentas_pagar_corto_plazo = '';	
		cuentas_pagar_largo_plazo = '';	
		deudas_particulares = '';	
		extrafinanciamientos = '';	
		impuestos = '';	
		inversiones = '';	
		muebles = '';	
		prestamos = '';	
		prestamos_hipotecarios = '';	
		sostenimiento_hogar = '';	
		terrenos = '';	
		vehiculos = '';	
		viviendas = '';
		direccion_inmueble_1 = '';
		direccion_inmueble_2 = '';
		finca_1 = '';
		folio_1 = '';
		libro_1 = '';
		departamento_1 = '';
		finca_2 = '';
		folio_2 = '';
		libro_2 = '';
		departamento_2 = '';
		valor_estimado_1 = '';
		valor_estimado_2 = '';
		marca_1 = '';
		tipo_vehiculo_1 = '';
		modelo_vehiculo_1 = '';
		marca_2 = '';
		tipo_vehiculo_2 = '';
		modelo_vehiculo_2 = '';
		aguinaldo = '';	
		bono_catorce = '';	
		honorarios = '';	
		igss = '';	
		judiciales = '';	
		isr = '';	
		otros_descuentos_fha = '';	
		otros_ingresos_fha = '';	
		plan_pensiones = '';	
		salario_nominal = '';	
		tipoContrato  = '';	
		vigencia_vence  = '';	
		mes_1= '';
		mes_2= '';
		mes_3= '';
		mes_4= '';
		mes_5= '';
		mes_6= '';
		hora_extra_mes_1= '';
		hora_extra_mes_2= '';
		hora_extra_mes_3= '';
		hora_extra_mes_4= '';
		hora_extra_mes_5= '';
		hora_extra_mes_6= '';
		comisiones_mes_1= '';
		comisiones_mes_2= '';
		comisiones_mes_3= '';
		comisiones_mes_4= '';
		comisiones_mes_5= '';
		comisiones_mes_6= '';
		bonificaciones_mes_1= '';
		bonificaciones_mes_2= '';
		bonificaciones_mes_3= '';
		bonificaciones_mes_4= '';
		bonificaciones_mes_5= '';
		bonificaciones_mes_6= '';
		empresa_1 = '';	
		cargo_1 = '';	
		desde_1 = '';	
		hasta_1 = '';
		empresa_2 = '';	
		cargo_2 = '';	
		desde_2 = '';	
		hasta_2 = '';
		empresa_3 = '';	
		cargo_3 = '';	
		desde_3 = '';	
		hasta_3 = '';
		empresa_4 = '';	
		cargo_4 = '';	
		desde_4 = '';	
		hasta_4 = '';
		nombre_referencia_1 = '';	
		parentesco_referencia_1 = '';	
		domicilio_1 = '';
		telefono_1 = '';	
		trabajo_1 = '';
		trabajo_direccion_1 = '';
		trabajo_telefono_1 = '';

		nombre_referencia_2 = '';	
		parentesco_referencia_2 = '';	
		trabajo_2 = '';
		trabajo_direccion_2 = '';
		trabajo_telefono_2 = '';
		banco_1 = '';	
		no_cuenta_1 = '';
		tipo_cuenta_1 = '';	
		saldo_actual_1 = '';	
		banco_2 = '';	
		no_cuenta_2 = '';
		tipo_cuenta_2 = '';	
		saldo_actual_2 = '';
		
		banco_prestamo_1= '';	
		tipo_prestamo_1='';	
		monto_1='';
		saldo_actual_prestamo_1='';
		pago_mensual_prestamo_1='';
		fecha_vencimiento_prestamo_1='';

		banco_prestamo_2= '';	
		tipo_prestamo_2='';	
		monto_2='';
		saldo_actual_prestamo_2='';
		pago_mensual_prestamo_2='';
		fecha_vencimiento_prestamo_2='';
			
		estatura = '';
		peso = '';

		
		$.each(response.info,function(i,e) {
			document.getElementById("h3_codeudor_div").style.display = "";
			document.getElementById("info_Codeudor_div").style.display = "";
			var estatura = parseInt(e.estatura) >= 0 ? e.estatura:'';
			var peso = parseInt(e.peso) >= 0 ? e.peso:'';

			$.each(response.infoPatrimonial,function(i,e) {
				acciones = e.acciones;
				alquiler = e.alquiler;	
				bancos = e.bancos;
				bonos = e.bonos;	
				caja = e.caja;	
				cuentas_cobrar = e.cuentas_cobrar;	
				cuentas_pagar_corto_plazo = e.cuentas_pagar_corto_plazo;	
				cuentas_pagar_largo_plazo = e.cuentas_pagar_largo_plazo;	
				deudas_particulares = e.deudas_particulares;	
				extrafinanciamientos = e.extrafinanciamientos;	
				impuestos = e.impuestos;	
				inversiones = e.inversiones;	
				muebles = e.muebles;	
				prestamos = e.prestamos;	
				prestamos_hipotecarios = e.prestamos_hipotecarios;	
				sostenimiento_hogar = e.sostenimiento_hogar;	
				terrenos = e.terrenos;	
				vehiculos = e.vehiculos;	
				viviendas = e.viviendas;
			
				total_activos = parseFloat(caja) + parseFloat(bancos)+parseFloat(cuentas_cobrar) + parseFloat(terrenos)+parseFloat(viviendas) + parseFloat(vehiculos)+parseFloat(inversiones) + parseFloat(bonos)+parseFloat(acciones) + parseFloat(muebles);
				total_pasivos = parseFloat(cuentas_pagar_corto_plazo) + parseFloat(cuentas_pagar_largo_plazo)+parseFloat(prestamos_hipotecarios) + parseFloat(sostenimiento_hogar)+parseFloat(alquiler) + parseFloat(prestamos) + parseFloat(impuestos) + parseFloat(extrafinanciamientos) + parseFloat(deudas_particulares);
				total_patrimonio = total_activos-total_pasivos;	

				
				

			});
			$.each(response.infoDetallePatrimonial,function(i,e) {
				direccion_inmueble_1 = e.direccion_inmueble_1;
				valor_inmueble_1 = e.valor_inmueble_1;
				direccion_inmueble_2 = e.direccion_inmueble_2;
				valor_inmueble_2 = e.valor_inmueble_2;
				finca_1 = e.finca_1;
				folio_1 = e.folio_1;
				libro_1 = e.libro_1;
				departamento_1 = e.departamento_nombre_1;
				finca_2 = e.finca_2;
				folio_2 = e.folio_2;
				libro_2 = e.libro_2;
				departamento_2 = e.departamento_nombre_2;
				valor_estimado_1 = e.valor_estimado_1;
				valor_estimado_2 = e.valor_estimado_2;
				marca_1 = e.marca_1;
				tipo_vehiculo_1 = e.tipo_vehiculo_1;
				modelo_vehiculo_1 = e.modelo_vehiculo_1;
				marca_2 = e.marca_2;
				tipo_vehiculo_2 = e.tipo_vehiculo_2;
				modelo_vehiculo_2 = e.modelo_vehiculo_2;	
				
			});
			$.each(response.infoIngresosEgresos,function(i,e) {
				aguinaldo = e.aguinaldo;	
				bono_catorce = e.bono_catorce;	
				honorarios = e.honorarios;	
				igss = e.igss;	
				judiciales = e.judiciales;	
				isr = e.isr;	
				otros_descuentos_fha = e.otros_descuentos_fha;	
				otros_ingresos_fha = e.otros_ingresos_fha;	
				plan_pensiones = e.plan_pensiones;	
				salario_nominal = e.salario_nominal;	
				tipoContrato = e.tipoContrato;	
				vigencia_vence = e.vigencia_vence;	

			});
			$.each(response.infoDetalleComisiones,function(i,e) {
				mes_1=e.mes_1;
				mes_2=e.mes_2;
				mes_3=e.mes_3;
				mes_4=e.mes_4;
				mes_5=e.mes_5;
				mes_6=e.mes_6;
				hora_extra_mes_1=e.hora_extra_mes_1;
				hora_extra_mes_2=e.hora_extra_mes_2;
				hora_extra_mes_3=e.hora_extra_mes_3;
				hora_extra_mes_4=e.hora_extra_mes_4;
				hora_extra_mes_5=e.hora_extra_mes_5;
				hora_extra_mes_6=e.hora_extra_mes_6;
				comisiones_mes_1=e.comisiones_mes_1;
				comisiones_mes_2=e.comisiones_mes_2;
				comisiones_mes_3=e.comisiones_mes_3;
				comisiones_mes_4=e.comisiones_mes_4;
				comisiones_mes_5=e.comisiones_mes_5;
				comisiones_mes_6=e.comisiones_mes_6;
				bonificaciones_mes_1=e.bonificaciones_mes_1;
				bonificaciones_mes_2=e.bonificaciones_mes_2;
				bonificaciones_mes_3=e.bonificaciones_mes_3;
				bonificaciones_mes_4=e.bonificaciones_mes_4;
				bonificaciones_mes_5=e.bonificaciones_mes_5;
				bonificaciones_mes_6=e.bonificaciones_mes_6;
				
			});
			$.each(response.infoHistorialLaboral,function(i,e) {
				empresa_1 = e.empresa_1;	
				cargo_1 = e.cargo_1;	
				desde_1 = e.desde_1;	
				hasta_1 = e.hasta_1;
				empresa_2 = e.empresa_2;	
				cargo_2 = e.cargo_2;	
				desde_2 = e.desde_2;	
				hasta_2 = e.hasta_2;
				empresa_3 = e.empresa_3;	
				cargo_3 = e.cargo_3;	
				desde_3 = e.desde_3;	
				hasta_3 = e.hasta_3;
				empresa_4 = e.empresa_4;	
				cargo_4 = e.cargo_4;	
				desde_4 = e.desde_4;	
				hasta_4 = e.hasta_4;	
						
				
			});
			$.each(response.infoRefFamiliar,function(i,e) {
				nombre_referencia_1 = e.nombre_referencia_1;	
				parentesco_referencia_1 = e.parentesco_referencia_1;	
				domicilio_1 = e.domicilio_1;
				telefono_1 = e.telefono_1;	
				trabajo_1 = e.trabajo_1;
				trabajo_direccion_1 = e.trabajo_direccion_1;
				trabajo_telefono_1 = e.trabajo_telefono_1;

				nombre_referencia_2 = e.nombre_referencia_2;	
				parentesco_referencia_2 = e.parentesco_referencia_2;	
				domicilio_2 = e.domicilio_2;
				telefono_2 = e.telefono_2;	
				trabajo_2 = e.trabajo_2;
				trabajo_direccion_2 = e.trabajo_direccion_2;
				trabajo_telefono_2 = e.trabajo_telefono_2;
				
				
			});
			$.each(response.infoRefBancarias,function(i,e) {
				banco_1 = e.banco_1;	
				tipo_cuenta_1 = e.tipo_cuenta_1;
				no_cuenta_1 = e.no_cuenta_1;	
				saldo_actual_1 = e.saldo_actual_1;	
				banco_2 = e.banco_2;	
				tipo_cuenta_2 = e.tipo_cuenta_2;
				no_cuenta_2 = e.no_cuenta_2;	
				saldo_actual_2 = e.saldo_actual_2;	

			});
			$.each(response.infoRefCrediticias,function(i,e) {
				banco_prestamo_1= e.banco_prestamo_1;	
				tipo_prestamo_1=e.tipo_prestamo_1;	
				no_prestamo_1=e.no_prestamo_1;	
				monto_1=e.monto_1;
				saldo_actual_prestamo_1=e.saldo_actual_prestamo_1;
				pago_mensual_prestamo_1=e.pago_mensual_prestamo_1;
				fecha_vencimiento_prestamo_1=e.fecha_vencimiento_prestamo_1;
				
				banco_prestamo_2= e.banco_prestamo_2;	
				tipo_prestamo_2=e.tipo_prestamo_2;	
				no_prestamo_2=e.no_prestamo_2;	
				monto_2=e.monto_2;
				saldo_actual_prestamo_2=e.saldo_actual_prestamo_2;
				pago_mensual_prestamo_2=e.pago_mensual_prestamo_2;
				fecha_vencimiento_prestamo_2=e.fecha_vencimiento_prestamo_2;	
				
				

			});

			var selectedEstadoCivil = '';
			var selectedTipoCuenta1 = '';
			var selectedTipoCuenta2 = '';
			var selectedTipoPrestamo1 = '';
			var selectedTipoPrestamo2 = '';
			var selectedParentesco1 = '';
			var selectedParentesco2 = '';
			var checkedFha = '';
			if(count>0){
				active='';
			}
			if(e.idEnganche<=0){
				ul='';
				div='';
			}else{
				count ++;
				ul =ul + '<li class="nav-item"><a class="nav-link '+active+'" data-toggle="tab" href="#'+e.idCodeudor+'">Codeudor '+count+'</a></li>';
									div += '<div id="'+e.idCodeudor+'" class="container tab-pane '+active+'">';
									div += '<ul class="nav nav-tabs" role="tablist">'
									div += '<li class="nav-item">'
									div += '<a class="nav-link active" data-toggle="tab" href="#cliente">Info. Cliente</a>'
									div += '</li>'
									div += '<li class="nav-item">'
									div += '<a class="nav-link" data-toggle="tab" href="#fhaCliente">Info. General Cliente FHA</a>'
									div += '</li>'
									div += '<li class="nav-item">'
									div += '<a class="nav-link" data-toggle="tab" href="#fhaRelacionDependencia">Info. de Ingresos Relación de Dependencia FHA</a>'
									div += '</li>'
									div += '</ul>'
									div += '<div class="tab-content" id="renderDatosInfo" name="renderDatosInfo">'
									div += '<div id="cliente" class="tab-pane active">'
									div += '<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarClienteCo'+e.idCodeudor+'" name="frmAgregarClienteCo'+e.idCodeudor+'" method="POST">';
									div += '<div class="row" >';
									div += '<input type="hidden" id="idClienteCo" name="idClienteCo" value="'+e.idCliente+'">';
									div += '<input type="hidden" id="idEngancheCo" name="idEngancheCo" value="'+e.idEnganche+'">'
									div += '<input type="hidden" id="idCodeudor" name="idCodeudor" value="'+e.idCodeudor+'">	'
									div += '<div id="divAlertClienteCo" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '</div>'	
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Cliente:</label>'
									div += '<div class="row" >'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="primerNombreCo" name="primerNombreCo" placeHolder="Primer Nombre" value="'+e.primerNombre+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="segundoNombreCo" name="segundoNombreCo" placeHolder="Segundo Nombre" value="'+e.segundoNombre+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="primerApellidoCo" name="primerApellidoCo" placeHolder="Primer apellido" value="'+e.primerApellido+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="segundoApellidoCo" name="segundoApellidoCo" placeHolder="Segundo Apellido" value="'+e.segundoApellido+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="tercerNombreCo" name="tercerNombreCo" placeHolder="Tercer Nombre" value="'+e.tercerNombre+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="apellidoCasadaCo" name="apellidoCasadaCo" placeHolder="Apellido Casada" value="'+e.apellidoCasada+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Correo electrónico:</label>'
									div += '<input type="text" id="correoCo" name="correoCo" value="'+e.client_mail+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Télefono Fijo:</label>'
									div += '<input  type="text" id="telefonoFijoCo" name="telefonoFijoCo" value="'+e.telefonoFijo+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Télefono Celular:</label>'
									div += '<input  type="text" id="telefonoCo" name="telefonoCo" value="'+e.telefono+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección:</label>'
									div += '<textarea class="form-control" readonly id="direccionCo" name="direccionCo" rows="2">'+e.direccion+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Nit:</label>'
									div += '<input type="text" id="nitCo" name="nitCo" value="'+e.nit+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Número de DPI:</label>'
									div += '<input type="text" id="numeroDpiCo" name="numeroDpiCo" value="'+e.numeroDpi+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Departamento:</label>'
									div += '<select class="form-control" readonly name="deptoCo" id="deptoCo_'+count+'"   onchange="getMunicipios(this.value,\'municipioCo\',\'\')">'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Municipio:</label>'
									div += '<select class="form-control" readonly name="municipioCo" id="municipioCo_'+count+'"  onchange="">'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Fecha Emisión DPI:</label>'
									div += '<input type="date" id="fechaEmisionDpiCo" name="fechaEmisionDpiCo"  value="'+e.fechaEmisionDpi+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Fecha vencimiento DPI:</label>'
									div += '<input id="fechaVencimientoDpiCo" name="fechaVencimientoDpiCo" value="'+e.fechaVencimientoDpi+'" type="date" class="form-control" readonly>'
									div += '<input id="fechaHoyCo" name="fechaHoyCo" type="hidden" class="form-control" readonly value="<?php echo date("d/m/Y") ?>">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Nacionalidad:</label>'
									div += '<select class="form-control" readonly name="nacionalidadCo"   id="nacionalidadCo_'+count+'" onchange="">'
									div += '</select>'
									div += '</div>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">'
									div += '<div class="row" >'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Fecha de nacimiento:</label>'
									div += '<input type="date" id="fechaNacimientoCo" value="'+e.fechaNacimiento+'" name="fechaNacimientoCo" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Estado Civil:</label>'
									div += '<select class="form-control" readonly name="estadoCivilCo" id="estadoCivilCo" onchange="">'
									div += '<option value="" >Seleccione</optinon>'
									selectedEstadoCivil ="Soltero"== e.estadoCivil?"selected":""
									div += '<option value="Soltero" '+selectedEstadoCivil+' >Soltero(a)</optinon>'
									selectedEstadoCivil ="Casado"== e.estadoCivil?"selected":""
									div += '<option value="Casado" '+selectedEstadoCivil+'>Casado(a)</optinon>'
									selectedEstadoCivil ="Viudo"== e.estadoCivil?"selected":""
									div += '<option value="Viudo" '+selectedEstadoCivil+'>Viudo(a)</optinon>'
									selectedEstadoCivil ="Divorciado"== e.estadoCivil?"selected":""
									div += '<option value="Divorciado" '+selectedEstadoCivil+'>Divorciado(a)</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Profesión:</label>'
									div += '<input type="text" id="profesionCo" name="profesionCo" value="'+e.profesion+'"  class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">No. de dependientes:</label>'
									div += '<input type="number" id="dependientesCo" name="dependientesCo" value="'+e.noDependientes+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Ha tenido tramite FHA:</label>'
									div += '<div class=" form-check form-check-inline"  style="">'
									checkedFha =e.creditoFHA=="si"?"checked":""
									div += '<input class="form-check-input" type="radio" name="fhaCo" id="si" value="si" '+checkedFha+'>'
									div += '<label class="form-check-label" for="">Si</label>'
									div += '</div>'
									checkedFha =e.creditoFHA=="no"?"checked":""
									div += '<div class="form-check form-check-inline"  style="">'
									div += '<input class="form-check-input" type="radio" name="fhaCo" id="no" value="no" '+checkedFha+'>'
									div += '<label class="form-check-label">No</label>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Empresa donde labora:</label>'
									div += '<input type="text" id="empresaLaboraCo" name="empresaLaboraCo" value="'+e.empresaLabora+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Dirección de empresa:</label>'
									div += '<textarea class="form-control" readonly id="direccionEmpresaCo" name="direccionEmpresaCo" rows="2">'+e.direccionEmpresa+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Télefono de Referencia:</label>'
									div += '<input  type="text" id="telefonoReferenciaCo" name="telefonoReferenciaCo" value="'+e.telefonoReferencia+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Puesto en Empresa:</label>'
									div += '<input type="text" id="puestoEmpresaCo" name="puestoEmpresaCo" value="'+e.puestoLabora+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Salario mensual:</label>'
									div += '<input type="text" id="salarioMensualCo" name="salarioMensualCo"  value="'+e.salarioMensual+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Otros ingresos:</label>'
									div += '<input type="text" id="montoOtrosIngresosCo" name="montoOtrosIngresosCo" value="'+e.montoOtrosIngresos+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Descripción Otros ingresos:</label>'
									div += '<input type="text" id="otrosIngresosCo" name="otrosIngresosCo" value="'+e.otrosIngresos+'" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'

									div += '</div>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">'

									div += '</div>'
									div += '</div>'
									div += '</form>';
									div += '</div>';

									div += '<div id="fhaCliente" class="tab-pane">'
									div += '<div class="secinfo" >'
									
									div += '<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarClienteFha'+e.idCodeudor+'" name="frmAgregarClienteFha'+e.idCodeudor+'" method="POST">'
									div += '<input type="hidden" id="idClienteCo" name="idClienteCo" value="'+e.idCliente+'">';
									div += '<input type="hidden" id="idEngancheCo" name="idEngancheCo" value="'+e.idEnganche+'">'
									div += '<input type="hidden" id="idCodeudor" name="idCodeudor" value="'+e.idCodeudor+'">'	
									div += '<div class="row" >'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<div class = "row">'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Estatura(Cms):</label>'
									div += '<input type="text" id="Estatura" name="Estatura" value="'+e.estatura+'" placeHolder="Estatura" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Peso(Lbs):</label>	'
									div += '<input type="text" id="peso" name="peso" value="'+e.peso+'" placeHolder="Peso" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Activo</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Caja:</label>'
									div += '<input type="text" id="caja" name="caja" placeHolder="Caja" value="'+caja+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bancos:</label>'
									div += '<input type="text" id="bancos" name="bancos" value="'+bancos+'" placeHolder="Bancos" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Ctas por Cobrar:</label>'
									div += '<input type="text" id="cuentas_cobrar" name="cuentas_cobrar" value="'+cuentas_cobrar+'" placeHolder="Ctas por Cobrar" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Terrenos:</label>'
									div += '<input type="text" id="terrenos" name="terrenos" value="'+terrenos+'" placeHolder="Terrenos" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Viviendas:</label>'
									div += '<input type="text" id="viviendas" name="viviendas" value="'+viviendas+'" placeHolder="Viviendas" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Vehículos:</label>'
									div += '<input type="text" id="vehiculos" name="vehiculos" value="'+vehiculos+'" placeHolder="Vehículos" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Inversiones:</label>'
									div += '<input type="text" id="inversiones" name="inversiones" value="'+inversiones+'" placeHolder="Inversiones" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonos:</label>'
									div += '<input type="text" id="bonos" name="bonos" value="'+bonos+'" placeHolder="Bonos" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Acciones:</label>'
									div += '<input type="text" id="acciones" name="acciones" value="'+acciones+'" placeHolder="Acciones" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Muebles:</label>'
									div += '<input type="text" id="muebles" name="muebles" value="'+muebles+'" placeHolder="Muebles" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Total Activos:</label>'
									div += '<input type="text" id="total_activos" name="total_activos" value="'+total_activos+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Pasivo</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Ctas por Pagar corto plazo:</label>'
									div += '<input type="text" id="cuentas_pagar_corto_plazo" value="'+cuentas_pagar_corto_plazo+'" name="cuentas_pagar_corto_plazo" placeHolder="Ctas por Pagar corto Plazo" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Ctas por Pagar largo plazo:</label>'
									div += '<input type="text" id="cuentas_pagar_largo_plazo" value="'+cuentas_pagar_largo_plazo+'" name="cuentas_pagar_largo_plazo" placeHolder="Ctas por Pagar largo Plazo" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Prestamos Hipotecarios:</label>'
									div += '<input type="text" id="prestamos_hipotecarios" value="'+prestamos_hipotecarios+'" name="prestamos_hipotecarios" placeHolder="Prestamos Hipotecarios" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Gastos mensuales</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Sostenimiento del Hogar:</label>'
									div += '<input type="text" id="sostenimiento_hogar" value="'+sostenimiento_hogar+'" name="sostenimiento_hogar" placeHolder="Sostenimiento del Hogar" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Alquiler:</label>'
									div += '<input type="text" id="alquiler" name="alquiler" value="'+alquiler+'" placeHolder="Alquiler" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Préstamos:</label>'
									div += '<input type="text" id="prestamos" name="prestamos" value="'+prestamos+'" placeHolder="Préstamos" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Impuestos:</label>'
									div += '<input type="text" id="impuestos" name="impuestos" value="'+impuestos+'" placeHolder="Impuestos" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Extrafinanciamientos TC:</label>'
									div += '<input type="text" id="extrafinanciamientos" value="'+extrafinanciamientos+'" name="extrafinanciamientos" placeHolder="Extrafinanciamientos TC" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Deudas Particulares:</label>'
									div += '<input type="text" id="deudas_particulares" value="'+deudas_particulares+'" name="deudas_particulares" placeHolder="Deudas Particulares" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Total Pasivos:</label>'
									div += '<input type="text" id="total_pasivos" name="total_pasivos" value="'+total_pasivos+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Patrimonio:</label>'
									div += '<input type="text" id="total_patrimonio" name="total_patrimonio" value="'+total_patrimonio+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Detalle Patrimonial</label>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Bienes Inmuebles</label>'
									div += '</div>'
									div += '<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección del Inmueble:</label>'
									div += '<textarea class="form-control" readonly id="direccion_inmueble_1"  name="direccion_inmueble_1" rows="1">'+direccion_inmueble_1+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Valor Inmueble:</label>'
									div += '<input type="text" id="valor_inmueble_1" value="'+valor_inmueble_1+'" name="valor_inmueble_1" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Finca:</label>'
									div += '<input type="text" id="finca_1" value="'+finca_1+'" name="finca_1" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Folio:</label>'
									div += '<input type="text" id="folio_1" value="'+folio_1+'" name="folio_1" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Libro:</label>'
									div += '<input type="text" id="libro_1" value="'+libro_1+'" name="libro_1" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Departamento:</label>'
									div += '<input type="text" id="departamento_1" value="'+departamento_1+'" name="departamento_1" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección del Inmueble:</label>'
									div += '<textarea class="form-control" readonly id="direccion_inmueble_2" name="direccion_inmueble_2" rows="1">'+direccion_inmueble_2+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Valor Inmueble:</label>'
									div += '<input type="text" id="valor_inmueble_2" value="'+valor_inmueble_2+'" name="valor_inmueble_2" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Finca:</label>'
									div += '<input type="text" id="finca_2" value="'+finca_2+'" name="finca_2" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Folio:</label>'
									div += '<input type="text" id="folio_2" value="'+folio_2+'" name="folio_2" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Libro:</label>'
									div += '<input type="text" id="libro_2" value="'+libro_2+'" name="libro_2" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Departamento:</label>'
									div += '<input type="text" id="departamento_2" value="'+departamento_2+'" name="departamento_2" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Vehículos</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Marca:</label>'
									div += '<input type="text" id="marca_1" value="'+marca_1+'" name="marca_1" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">tipo:</label>'
									div += '<input type="text" id="tipo_vehiculo_1" value="'+tipo_vehiculo_1+'" name="tipo_vehiculo_1" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Modelo:</label>'
									div += '<input type="text" id="modelo_vehiculo_1" value="'+modelo_vehiculo_1+'" name="modelo_vehiculo_1" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Valor Estimado:</label>'
									div += '<input type="text" id="valor_estimado_1" value="'+valor_estimado_1+'" name="valor_estimado_1" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Marca:</label>'
									div += '<input type="text" id="marca_2" value="'+marca_2+'" name="marca_2" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">tipo:</label>'
									div += '<input type="text" id="tipo_vehiculo_2" value="'+tipo_vehiculo_2+'" name="tipo_vehiculo_2" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Modelo:</label>'
									div += '<input type="text" id="modelo_vehiculo_2" value="'+modelo_vehiculo_2+'" name="modelo_vehiculo_2" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Valor Estimado:</label>'
									div += '<input type="text" id="valor_estimado_2" value="'+valor_estimado_2+'" name="valor_estimado_2" class="form-control" readonly >'
									div += '</div>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">'

									div += '</div>'
									div += '</div>'
									div += '</form>'
									div += '</div>'
									div += '</div>'


									div += '<div id="fhaRelacionDependencia" class="tab-pane">'
									div += '<div class="secinfo">'

									div += '<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarClienteFhaDependencia'+e.idCodeudor+'" name="frmAgregarClienteFhaDependencia'+e.idCodeudor+'" method="POST">'
									div += '<input type="hidden" id="idClienteCo" name="idClienteCo" value="'+e.idCliente+'">';
									div += '<input type="hidden" id="idEngancheCo" name="idEngancheCo" value="'+e.idEnganche+'">'
									div += '<input type="hidden" id="idCodeudor" name="idCodeudor" value="'+e.idCodeudor+'">'
									div += '<div class="row" >'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<div class = "row">'
									div += '<div class="col-lg-6 col-md-6 col-xs-10">'
									div += '<div class="row" >'
									div += '<div class="col-lg-12 col-md-12 col-xs-10">'
									div += '<label class="nodpitext">Tipo Contrato:</label>'			
									div += '</div>'	
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'	
									div += '<div class=" form-check form-check-inline"  >'	
									div += '<input class="form-check-input" type="radio" name="tipoContrato" id="indefinido" value="indefinido" checked>'	
									div += '<label class="form-check-label" for="">Indefinido</label>'	
									div += '</div>'	
									div += '<div class="form-check form-check-inline">'	
									div += '<input class="form-check-input" type="radio" name="tipoContrato" id="definido" value="definido">'	
									div += '<label class="form-check-label">Vigencia Definida</label>'	
									div += '</div>'	
									div += '</div>'	
									div += '</div>'	
									div += '</div>'	
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'	
									div += '<label class="nodpitext">Vigencia Vence:</label>'	
									div += '<input type="date" id="vigencia_vence" value="'+vigencia_vence+'" name="vigencia_vence" class="form-control" readonly>'	
									div += '</div>'	
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'	
									div += '<label class="nodpitext2">Detalle de Ingresos Mensuales</label>'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Salario Nominal:</label>'	
									div += '<input type="text" id="salario_nominal" value="'+salario_nominal+'" name="salario_nominal" placeHolder="Salario Nominal" class="form-control" readonly >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Aguinaldo (1/12):</label>'	
									div += '<input type="text" id="bono_catorce" value="'+bono_catorce+'"   name="bono_catorce" placeHolder="Bono 14" class="form-control" readonly >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Aguinaldo (1/12):</label>'	
									div += '<input type="text" id="aguinaldo" value="'+aguinaldo+'" name="aguinaldo" placeHolder="Aguinaldo" class="form-control" readonly >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Honorarios:</label>'	
									div += '<input type="text" id="honorarios" value="'+honorarios+'"  name="honorarios" placeHolder="Honorarios" class="form-control" readonly >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Otros:</label>'	
									div += '<input type="text" id="otros_ingresos_fha" value="'+otros_ingresos_fha+'"  name="otros_ingresos_fha" placeHolder="Otros" class="form-control" readonly >'	
									div += '</div>'	
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'	
									div += '<label class="nodpitext2">Detalle de descuentos Mensuales</label>'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">IGSS:</label>'	
									div += '<input type="text" id="igss" name="igss" value="'+igss+'"  placeHolder="Igss" class="form-control" readonly >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">ISR:</label>'	
									div += '<input type="text" id="isr" name="isr" value="'+isr+'"  placeHolder="Isr" class="form-control" readonly >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Plan de Pensiones:</label>'	
									div += '<input type="text" id="plan_pensiones" value="'+plan_pensiones+'"  name="plan_pensiones" placeHolder="Plan Pensiones" class="form-control" readonly >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Judiciales:</label>'	
									div += '<input type="text" id="judiciales" value="'+judiciales+'"  name="judiciales" placeHolder="Judiciales" class="form-control" readonly >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Otros:</label>'	
									div += '<input type="text" id="otros_descuentos_fha" value="'+otros_descuentos_fha+'"  name="otros_descuentos_fha" placeHolder="Otros" class="form-control" readonly >'	
									div += '</div>'	
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'	
									div += '<label class="nodpitext2">Detalle de Horas extras, comisiones y Bonificaciones últimos 6 meses</label>'	
									div += '</div>'	
									// div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'	
									// div += '<label class="nodpitext">- Mes</label>'	
									// div += '</div>'	
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 1:</label>'
									// div += '<input type="text" id="mes_1" name="mes_1" value="'+mes_1+'" placeHolder="Mes 1" class="form-control" readonly >'
									// div += '</div>'
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 2:</label>'
									// div += '<input type="text" id="mes_2" name="mes_2" value="'+mes_2+'"  placeHolder="Mes 2" class="form-control" readonly >'
									// div += '</div>'
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 3:</label>'
									// div += '<input type="text" id="mes_3" name="mes_3"  value="'+mes_3+'" placeHolder="Mes 3" class="form-control" readonly >'
									// div += '</div>'
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 4:</label>'
									// div += '<input type="text" id="mes_4" name="mes_4"  value="'+mes_4+'" placeHolder="Mes 4" class="form-control" readonly >'
									// div += '</div>'
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 5:</label>'
									// div += '<input type="text" id="mes_5" name="mes_5"  value="'+mes_5+'" placeHolder="Mes 5" class="form-control" readonly >'
									// div += '</div>'
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 6:</label>'
									// div += '<input type="text" id="mes_6" name="mes_6"  value="'+mes_6+'" placeHolder="Mes 6" class="form-control" readonly >'
									// div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">- Horas Extras</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 1:</label>'
									div += '<input type="text" id="hora_extra_mes_1"  value="'+hora_extra_mes_1+'" name="hora_extra_mes_1" placeHolder="Mes 1" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 2:</label>'
									div += '<input type="text" id="hora_extra_mes_2" value="'+hora_extra_mes_2+'" name="hora_extra_mes_2" placeHolder="Mes 2" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 3:</label>'
									div += '<input type="text" id="hora_extra_mes_3" name="hora_extra_mes_3" value="'+hora_extra_mes_3+'" placeHolder="Mes 3" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 4:</label>'
									div += '<input type="text" id="hora_extra_mes_4" name="hora_extra_mes_4" value="'+hora_extra_mes_4+'" placeHolder="Mes 4" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 5:</label>'
									div += '<input type="text" id="hora_extra_mes_5" name="hora_extra_mes_5" value="'+hora_extra_mes_5+'" placeHolder="Mes 5" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 6:</label>'
									div += '<input type="text" id="hora_extra_mes_6" name="hora_extra_mes_6" value="'+hora_extra_mes_6+'" placeHolder="Mes 6" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">- Comisiones</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 1:</label>'
									div += '<input type="text" id="comisiones_mes_1" name="comisiones_mes_1" value="'+comisiones_mes_1+'" placeHolder="Mes 1" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 2:</label>'
									div += '<input type="text" id="comisiones_mes_2" name="comisiones_mes_2" value="'+comisiones_mes_2+'" placeHolder="Mes 2" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 3:</label>'
									div += '<input type="text" id="comisiones_mes_3" name="comisiones_mes_3" value="'+comisiones_mes_3+'" placeHolder="Mes 3" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 4:</label>'
									div += '<input type="text" id="comisiones_mes_4" name="comisiones_mes_4" value="'+comisiones_mes_4+'" placeHolder="Mes 4" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 5:</label>'
									div += '<input type="text" id="comisiones_mes_5" name="comisiones_mes_5" value="'+comisiones_mes_5+'" placeHolder="Mes 5" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 6:</label>'
									div += '<input type="text" id="comisiones_mes_6" name="comisiones_mes_6" value="'+comisiones_mes_6+'" placeHolder="Mes 6" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">- Bonificaciones</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 1:</label>'
									div += '<input type="text" id="bonificaciones_mes_1" name="bonificaciones_mes_1" value="'+bonificaciones_mes_1+'" placeHolder="Mes 1" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 2:</label>'
									div += '<input type="text" id="bonificaciones_mes_2" name="bonificaciones_mes_2" value="'+bonificaciones_mes_2+'" placeHolder="Mes 2" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 3:</label>'
									div += '<input type="text" id="bonificaciones_mes_3" name="bonificaciones_mes_3" value="'+bonificaciones_mes_3+'" placeHolder="Mes 3" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 4:</label>'
									div += '<input type="text" id="bonificaciones_mes_4" name="bonificaciones_mes_4" value="'+bonificaciones_mes_4+'" placeHolder="Mes 4" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 5:</label>'
									div += '<input type="text" id="bonificaciones_mes_5" name="bonificaciones_mes_5" value="'+bonificaciones_mes_5+'" placeHolder="Mes 5" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 6:</label>'
									div += '<input type="text" id="bonificaciones_mes_6" name="bonificaciones_mes_6" value="'+bonificaciones_mes_6+'" placeHolder="Mes 6" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">- Historial Laboral últimos dos años</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Empresa</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Cargo</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Desde</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Hasta</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="empresa_1" name="empresa_1"  value="'+empresa_1+'" placeHolder="Nombre Empresa" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="cargo_1" name="cargo_1" value="'+cargo_1+'" placeHolder="Nombre Cargo" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="desde_1" name="desde_1" value="'+desde_1+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="hasta_1" name="hasta_1" value="'+hasta_1+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="empresa_2" name="empresa_2" value="'+empresa_2+'" placeHolder="Nombre Empresa" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="cargo_2" name="cargo_2" value="'+cargo_2+'" placeHolder="Nombre Cargo" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="desde_2" name="desde_2" value="'+desde_2+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="hasta_2" name="hasta_2" value="'+hasta_2+'" class="form-control" readonly >'
									div += '</div>'

									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="empresa_3" name="empresa_3" value="'+empresa_3+'" placeHolder="Nombre Empresa" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="cargo_3" name="cargo_3" value="'+cargo_3+'" placeHolder="Nombre Cargo" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="desde_3" name="desde_3" value="'+desde_3+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="hasta_3" name="hasta_3" value="'+hasta_3+'" class="form-control" readonly >'
									div += '</div>'

									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="empresa_4" name="empresa_4" value="'+empresa_4+'" placeHolder="Nombre Empresa" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="cargo_4" name="cargo_4" value="'+cargo_4+'" placeHolder="Nombre Cargo" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="desde_4" name="desde_4" value="'+desde_4+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="hasta_4" name="hasta_4" value="'+hasta_4+'" class="form-control" readonly >'
									div += '</div>'

									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Referencias Familiares, Bancarias y Crediticias</label>'
									div += '</div>'

									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Ref. Familiar </label>'
									div += '</div>'

									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Nombre:</label>'
									div += '<input type="text" id="nombre_referencia_1" value="'+nombre_referencia_1+'"  name="nombre_referencia_1" placeHolder="Nombre" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Parentesco:</label>'
									div += '<input type="text" id="parentesco_referencia_1" value="'+parentesco_referencia_1+'" name="parentesco_referencia_1" placeHolder="Nombre" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Domicilio:</label>'
									div += '<textarea class="form-control" readonly id="domicilio_1" name="domicilio_1" rows="1">'+domicilio_1+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Telefono:</label>'
									div += '<input type="text" id="telefono_1" value="'+telefono_1+'" name="telefono_1" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Trabajo:</label>'
									div += '<input type="text" id="trabajo_1" value="'+trabajo_1+'" name="trabajo_1" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección:</label>'
									div += '<textarea class="form-control" readonly id="trabajo_direccion_1" name="trabajo_direccion_1" rows="1">'+trabajo_direccion_1+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Telefono:</label>'
									div += '<input type="text" id="trabajo_telefono_1" value="'+trabajo_telefono_1+'" name="trabajo_telefono_1" class="form-control" readonly>'
									div += '</div>'

									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Ref. Familiar</label>'
									div += '</div>'

									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Nombre:</label>'
									div += '<input type="text" id="nombre_referencia_2" value="'+nombre_referencia_2+'" name="nombre_referencia_2" placeHolder="Nombre" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Parentesco:</label>'
									div += '<input type="text" id="parentesco_referencia_2" value="'+parentesco_referencia_2+'" name="parentesco_referencia_2" placeHolder="Nombre" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Domicilio:</label>'
									div += '<textarea class="form-control" readonly id="domicilio_2" name="domicilio_2" rows="1">'+domicilio_2+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Telefono:</label>'
									div += '<input type="text" id="telefono_2" readonly value="'+telefono_2+'" name="telefono_2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Trabajo:</label>'
									div += '<input type="text" id="trabajo_2" readonly value="'+trabajo_2+'" name="trabajo_2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección:</label>'
									div += '<textarea class="form-control" readonly id="trabajo_direccion_2" name="trabajo_direccion_2" rows="1">'+trabajo_direccion_2+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Telefono:</label>'
									div += '<input type="text" id="trabajo_telefono_2" readonly value="'+trabajo_telefono_2+'" name="trabajo_telefono_2" class="form-control" >'
									div += '</div>'

									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Referencias Bancarias</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Banco</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Tipo y No. de Cuenta</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">No. de Cuenta</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Saldo Actual</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="banco_1" name="banco_1" value="'+banco_1+'" placeHolder="Banco" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<select class="form-control" readonly name="tipo_cuenta_1" id="tipo_cuenta_1">'
									div += '<option value="" >Seleccione</optinon>'
									selectedTipoCuenta1 ="Monetaria"== tipo_cuenta_1?"selected":""
									div += '<option value="Monetaria" '+selectedTipoCuenta1+' >Monetaria</optinon>'
									selectedTipoCuenta1 ="Ahorro"== tipo_cuenta_1?"selected":""
									div += '<option value="Ahorro" '+selectedTipoCuenta1+'>Ahorro</optinon>'
									selectedTipoCuenta1 ="Plazo fijo"== tipo_cuenta_1?"selected":""
									div += '<option value="Plazo fijo" '+selectedTipoCuenta1+'>Plazo fijo</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="no_cuenta_1" readonly value="'+no_cuenta_1+'" name="no_cuenta_1" placeHolder="Tipo No. Cuenta" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="saldo_actual_1" value="'+saldo_actual_1+'" name="saldo_actual_1" class="form-control" readonly >'
									div += '</div>'

									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="banco_2" name="banco_2" value="'+banco_2+'" placeHolder="Banco" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<select class="form-control" readonly name="tipo_cuenta_2" id="tipo_cuenta_2">'
									div += '<option value="" >Seleccione</optinon>'
									selectedTipoCuenta2 ="Monetaria"== tipo_cuenta_2?"selected":""
									div += '<option value="Monetaria" '+selectedTipoCuenta2+' >Monetaria</optinon>'
									selectedTipoCuenta2 ="Ahorro"== tipo_cuenta_2?"selected":""
									div += '<option value="Ahorro" '+selectedTipoCuenta2+'>Ahorro</optinon>'
									selectedTipoCuenta2 ="Plazo fijo"== tipo_cuenta_2?"selected":""
									div += '<option value="Plazo fijo" '+selectedTipoCuenta2+'>Plazo fijo</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="no_cuenta_2" readonly value="'+no_cuenta_2+'" name="no_cuenta_2" placeHolder="Tipo No. Cuenta" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="saldo_actual_2" value="'+saldo_actual_2+'"  name="saldo_actual_2" class="form-control" readonly >'
									div += '</div>'

									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Referencias Crediticias</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Banco Prestamo:</label>'
									div += '<input type="text" id="banco_prestamo_1" value="'+banco_prestamo_1+'" name="banco_prestamo_1" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Tipo de Préstamo:</label>'
									div += '<input type="text" id="tipo_prestamo_1" value="'+tipo_prestamo_1+'" name="tipo_prestamo_1" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">No. de Préstamo:</label>'
									div += '<input type="text" id="no_prestamo_1" value="'+no_prestamo_1+'" name="no_prestamo_1" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Monto:</label>'
									div += '<input type="text" id="monto_1" name="monto_1" value="'+monto_1+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Saldo Actual:</label>'
									div += '<input type="text" id="saldo_actual_prestamo_1" value="'+saldo_actual_prestamo_1+'" name="saldo_actual_prestamo_1" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Pago Mensual:</label>'
									div += '<input type="text" id="pago_mensual_prestamo_1" value="'+pago_mensual_prestamo_1+'" name="pago_mensual_prestamo_1" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Fecha Vencimiento:</label>'
									div += '<input type="date" id="fecha_vencimiento_prestamo_1" value="'+fecha_vencimiento_prestamo_1+'" name="fecha_vencimiento_prestamo_1" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Banco Prestamo:</label>'
									div += '<input type="text" id="banco_prestamo_2" value="'+banco_prestamo_2+'" name="banco_prestamo_2" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Tipo de Préstamo:</label>'
									div += '<input type="text" id="tipo_prestamo_2" value="'+tipo_prestamo_2+'" name="tipo_prestamo_2" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">No. de Préstamo:</label>'
									div += '<input type="text" id="no_prestamo_2" value="'+no_prestamo_2+'" name="no_prestamo_2" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Monto:</label>'
									div += '<input type="text" id="monto_2" name="monto_2" value="'+monto_2+'" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Saldo Actual:</label>'
									div += '<input type="text" id="saldo_actual_prestamo_2" value="'+saldo_actual_prestamo_2+'" name="saldo_actual_prestamo_2" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Pago Mensual:</label>'
									div += '<input type="text" id="pago_mensual_prestamo_2" value="'+pago_mensual_prestamo_2+'" name="pago_mensual_prestamo_2" class="form-control" readonly >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Fecha Vencimiento:</label>'
									div += '<input type="date" id="fecha_vencimiento_prestamo_2" value="'+fecha_vencimiento_prestamo_2+'" name="fecha_vencimiento_prestamo_2" class="form-control" readonly >'
									div += '</div>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">'

									div += '</div>'	
									div += '</div>'	
									

									div += '</form>'

									div += '</div>'



									div += '</div>'
									div += '</div>'	
									div += '</div>'
			}
				
		});
			$("#modalAgregarClienteCoVer").modal({
				backdrop: 'static',
				keyboard: false,
				show: true
			});
			if(ul!=''){
				ul = ul + '</ul><div class="tab-content" id="renderDatosCo" name="renderDatosCo"></div>';
			}
			//console.log(ul);
			html +='</div>';
			$("#bodyAgregarClienteCoVer").html(html);
			$("#renderListCo").html(ul);
			$("#renderDatosCo").html(div);
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


			$.each(response.info,function(i,e) {
				count1 ++;
				getDepartamentos(1,'deptoCo_'+count1+'',e.departamento);
				getMunicipios(e.departamento,'municipioCo_'+count1+'',e.municipio);
				getNacionalidad('nacionalidadCo_'+count1+'',e.Nacionalidad);
				//console.log('deptoCo '+e.departamento+' -municipioCo '+e.municipio+' -nacionalidadCo_'+count1+' '+e.Nacionalidad)		
			});
			$("#salarioMensualCo").number( true, 2 );
			$("#montoOtrosIngresosCo").number( true, 2 );
			//document.getElementById("btnEnganche").disabled = false;
	},
	error:function (){
		$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
	}
});

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
			
			function guardarAdjuntosExtra() {
				var idApartamento = $("#idOcaApartamento").val();
				var id_tipo_documento = $("#filtro-adjuntos-extra_0 option:selected").val();
				var formData = new FormData(document.getElementById("frmListaAdjuntoExtra"));
				// var adjuntos = $("#fliesAdjuntos").val();

				if (!id_tipo_documento || id_tipo_documento == 0 ) {
					$("#divAlertAdjuntos").html('<div class="alert alert-danger">Por favor seleccione un tipo de documento...</div>');
					setTimeout(function(){
						$("#divAlertAdjuntos").html('');
					},5000)
					return true;
				}

				formData.append("idOcaApartamento", idApartamento);
				formData.append("id_tipo_documento", id_tipo_documento);

				$.ajax({
					url: "./cliente.php?guardarAdjuntoExtra=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						$("#resultadoAdjuntosExtra").html('');
						$("#divVerAdjuntos_extra").html('');
						verAdjuntosExtra();
					},
					error: function () {
						$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
					}
				});
			}	
		</script>
    </body>
</html>

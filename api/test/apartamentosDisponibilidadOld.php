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
					<div class="modal fade" id="modalInfoCliente">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Info. Cliente</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyInfoCliente" style="padding:5px 15px;" >
									<div class="secinfo" >
										<div class="col-12 col-md-12" style="margin-bottom:10px;">
											<div class="row">
												<div class="col-12 col-md-12" style="margin-bottom:10px;">
													<input id="nombreCliente" class="usernametittle" placeholder="" >
												</div>
												<div class="secinfo">
													<div class="row" >
														<input type="hidden" id="idInfo" name="idInfo">
														<input type="hidden" id="proyectoInfo" name="proyectoInfo">
														<input type="hidden" id="idOcaInfo" name="idOcaInfo">
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;;margin-top:10px;">
															<h3 class="titleinf"><img class="usericon" src="../img/client_icon.png" alt="Cliente"> Información del Cliente</h3>
														</div>
														<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
															<label class="usernametittleinfo">Código: </label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input id="codigoInfo" class="form-control" value="" readonly>
														</div>
														<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
															<label class="usernametittleinfo">Nombre del Cliente: </label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input id="nombreClienteInfo" class="form-control" value="" readonly>
														</div>
														<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
															<label class="emailtittle">Correo  electronico: </label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input id="emailClienteInfo" class="form-control" readonly>
														</div>
														<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
															<label class="phonetittle">Teléfono: </label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input id="telefonoClienteInfo" class="form-control" readonly>
														</div>
														<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
															<label class="dpitittle">DPI: </label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input id="dpiClienteInfo" class="form-control" readonly>
														</div>
														<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
															<label class="vencimientodpitittle">Vencimiento DPI: </label>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input id="vencimientoDpiClienteInfo" class="form-control" readonly>
														</div>
														<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
															<label class="adresstittle">Dirección Residencia: </label>
														</div>
														<div class="col-lg-10 col-md-10 col-xs-10" style="margin-bottom:10px;">
															<input id="direccionClienteInfo" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" >
														</div>
														<div class="col-lg-8 col-md-8 col-xs-10" >
															<div class="row" >
																<!-- <div class="col-lg-6 col-md-6 col-xs-10" style="text-align:center;margin-bottom:10px;">
																	<button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Ver adjuntos</button>															
																</div>
																<div class="col-lg-6 col-md-6 col-xs-10" style="text-align:center;margin-bottom:10px;">
																	<button name="btnEnganche" onclick="agregarEnganche()" class="inf" type="button">Cotización final</button>	
																</div>														 -->
															</div>
														</div>
															
													</div>
												</div>
												<div class="secinfo" id="renderList" name="renderList">
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
					<!-- Modal -->
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
												<div id="divAlertCliente" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												</div>													
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
													<div class="col-lg-12 col-md-12 col-xs-10" style="" >
															<label class="nodpitext">Tipo Cliente:</label>			
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<div class="row" >
																<div class="col-lg-12 col-md-12 col-xs-10" style="" >
																	<div class="row" >
																		<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																			<div class=" form-check form-check-inline"  style="">
																				<input onClick="fncTipoCliente()" class="form-check-input" type="radio" name="tipoCliente" id="individual" value="individual" checked>
																				<label class="form-check-label" for="">Cliente Individual</label>
																			</div>
																			<div class="form-check form-check-inline"  style="">
																				<input onClick="fncTipoCliente()" class="form-check-input" type="radio" name="tipoCliente" id="juridico" value="juridico">
																				<label class="form-check-label">Cliente Juridico</label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div id="divNombreSa" name="divNombreSa" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Nombre S.A.</label>	
															<input type="text" id="nombreSa" name="nombreSa" placeHolder="" class="form-control" >
														</div>
														<div id="divRtu" name="divRtu" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">RTU</label>	
															<input type="text" id="rtu" name="rtu" placeHolder="" class="form-control" >
														</div>
														<div id="divRepresentanteLegal" name="divRepresentanteLegal" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Representante Legal</label>	
															<input type="text" id="representanteLegal" name="representanteLegal" placeHolder="" class="form-control" >
														</div>
														<div id="divPatenteEmpresa" name="divPatenteEmpresa" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Patente de Empresa</label>	
															<input type="text" id="patenteEmpresa" name="patenteEmpresa" placeHolder="" class="form-control" >
														</div>
														<div id="divPatenteSociedad" name="divPatenteSociedad" class="col-lg-12 col-md-12 col-xs-10" style="display: none; margin-bottom:10px; padding-right:0px">
															<label class="nodpitext">Patente de Sociedad</label>	
															<input type="text" id="patenteSociedad" name="patenteSociedad" placeHolder="" class="form-control" >
														</div>
														<div id="divPrimerNombre" name="divPrimerNombre" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Primer Nombre:</label>
															<input type="text" id="primerNombre" name="primerNombre" placeHolder="Primer Nombre" class="form-control" >
														</div>
														<div id="divSegundoNombre" name="divSegundoNombre" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Segundo Nombre:</label>	
															<input type="text" id="segundoNombre" name="segundoNombre" placeHolder="Segundo Nombre" class="form-control" >
														</div>
														<div id="divPrimerApellido" name="divPrimerApellido" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Primer Apellido:</label>		
															<input type="text" id="primerApellido" name="primerApellido" placeHolder="Primer apellido" class="form-control" >
														</div>
														<div id="divSegundoApellido" name="divSegundoApellido" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Segundo Apellido:</label>		
															<input type="text" id="segundoApellido" name="segundoApellido" placeHolder="Segundo Apellido" class="form-control" >
														</div>
														<div id="divTercerNombre" name="divTercerNombre" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Tercer Nombre:</label>		
															<input type="text" id="tercerNombre" name="tercerNombre" placeHolder="Tercer Nombre" class="form-control" >
														</div>
														<div id="divApellidoCasada" name="divApellidoCasada" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
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
															<label id="lblDireccion" name="lblDireccion" class="nodpitext">Dirección Residiencia:</label>
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
															<input type="date" id="fechaEmisionDpiCl" name="fechaEmisionDpiCl" class="form-control">
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
														<div id="divNacionalidad" name="divNacionalidad" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Nacionalidad:</label>
															<select class="form-control" name="nacionalidadCl" id="nacionalidadCl" onchange="">
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label id="lblFechaNacimiento" name="lblFechaNacimiento" class="nodpitext">Fecha de nacimiento:</label>
															<input type="date" id="fechaNacimientoCl" name="fechaNacimientoCl" class="form-control">
														</div>
														<div id="divEstadoCivil" name="divEstadoCivil" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
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
														<div id="divDependientes" name="divDependientes" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">No. de dependientes:</label>
															<input type="number" id="dependientesCl" name="dependientesCl" class="form-control">
														</div>
														<div id="divFha" name="divFha" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<div class="row" >
																<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																	<label class="nodpitext">Ha tenido tramite FHA:</label>
																</div>
																<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																	<div class=" form-check form-check-inline"  style="">
																		<input class="form-check-input" type="radio" name="fha" id="siCl" value="si">
																		<label class="form-check-label" for="">Si</label>
																	</div>
																	<div class="form-check form-check-inline"  style="">
																		<input class="form-check-input" type="radio" name="fha" id="noCl" value="no">
																		<label class="form-check-label">No</label>
																	</div>
																</div>
															</div>
														</div>
														<div id="divEmpresaLabora" name="divEmpresaLabora" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Empresa donde labora:</label>
															<input type="text" id="empresaLaboraCl" name="empresaLaboraCl" class="form-control">
														</div>
														<div id="divDireccionEmpresa" name="divDireccionEmpresa" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Dirección de empresa:</label>
															<textarea class="form-control" id="direccionEmpresaCl" name="direccionEmpresaCl" rows="2"></textarea>
														</div>
														<div id="divTelefonoReferencia" name="divTelefonoReferencia" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Télefono de Referencia:</label>
															<input  type="text" id="telefonoReferencia" name="telefonoReferencia" class="form-control">
														</div>
														<div id="divPuestoEmpresa" name="divPuestoEmpresa" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Puesto en Empresa:</label>
															<input type="text" id="puestoEmpresaCl" name="puestoEmpresaCl" class="form-control">
														</div>
														<div id="divSalario" name="divSalario" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Salario mensual:</label>
															<input type="text" id="salarioMensualCl" name="salarioMensualCl" class="form-control">
														</div>
														<div id="divMontoIngresos" name="divMontoIngresos" class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Otros ingresos:</label>
															<input type="text" id="montoOtrosIngresosCl" name="montoOtrosIngresosCl" class="form-control">
														</div>
														<div id="divOtrosIngresos" name="divOtrosIngresos" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Descripción Otros ingresos:</label>
															<input type="text" id="otrosIngresosCl" name="otrosIngresosCl" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															
														</div>

														<script type="text/javascript">
															$("#salarioMensualCl").number( true, 2 );
															$("#montoOtrosIngresosCl").number( true, 2 );
														</script>
													</div>
													
													
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
													<button onclick="guardarCliente()" class="guardar" type="button">Guardar</button>
													<button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Agregar Adjuntos</button>
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
									output += '<th colspan="'+colspan +'" style="text-align:center">Torre/Fase '+t.name+'</th>';
									
									$.each(t.niveles,function(i,n) {
										count++;
										outputN += '<tr >';
										outputN += '<td style="width:20%;">Nivel '+n.name+'</td>';
										$.each(n.apartamentos,function(i,a) {
											if(a.color == '#e64a19'){
												var cursor = "cursor:pointer";
											}else{
												var cursor = "";
											}
											if(id_perfil!=1 && id_perfil!=4){
												var onClick='buscarClienteUnico('+a.idCliente+',\''+e.nombre+'\',\''+a.color+'\');';
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
				function buscarClienteUnico(idCotizacion,proyecto,color){
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
								//console.log(e.client_name);
								//Info Apartamento, Vendedor y CLiente
								$("#nombreClienteInfo").val(e.client_name);
								$("#codigoInfo").val(e.codigo);
								$("#emailClienteInfo").val(e.client_mail);
								$("#nivelInfo").val(e.nivel);
								$("#apartamentoInfo").val(e.apartamento);
								$("#tamanioInfo").val(e.sqmts);
								$("#habitacionInfo").val(e.cuartos);
								$("#areaBodegaInfo").val(e.bodega_mts);
								$("#parqueoMotoInfo").val(e.parqueo_moto);
								$("#parqueoCarroInfo").val(e.parqueo);
								$("#areaJardinInfo").val(e.jardin_mts);
								if(e.price!=''){
									$("#precioTotalInfo").val('Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(e.price));
								}
								var montoVenta = parseFloat(e.contracargo) + parseFloat(e.bodegaPrecioMonto) + parseFloat(e.parqueoExtraMonto) + parseFloat(e.price) - parseFloat(e.descuento_porcentual_monto) ;
								$("#proyectoInfo").val(proyecto);
								//$("#idInfo").val(idCliente);
								$("#idOcaInfo").val(e.idCliente);
								$("#ProyectoCl").val(proyecto);
								niveles(proyecto,'nivelCl',e.nivel);
								apartamentos(proyecto,e.nivel,'apartamentoCl',e.apartamento);
								getDepartamentos(1,'depto',e.departamento);
								getMunicipios(e.departamento,'municipio',e.municipio);
								getNacionalidad('nacionalidadCl',e.Nacionalidad);
								$("#telefonoClienteInfo").val(e.telefono);
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
								$("#telefonoReferencia").val(e.telefonoReferencia);
								$("#puestoEmpresaCl").val(e.puestoLabora);
								$("#salarioMensualCl").val(e.salarioMensual);
								$("#otrosIngresosCl").val(e.otrosIngresos);
								$("#montoOtrosIngresosCl").val(e.montoOtrosIngresos);
								$("#nombreSa").val(e.nombre_sa);
								$("#rtu").val(e.rtu);
								$("#representanteLegal").val(e.representanteLegal);
								$("#patenteEmpresa").val(e.patenteEmpresa);
								$("#patenteSociedad").val(e.patenteSociedad);
								
								if(e.creditoFHA=='si'){
									document.getElementById('siCl').checked = true;
									console.log("credito fha "+e.creditoFHA);
								}else if(e.creditoFHA=='no'){
									document.getElementById('noCl').checked = true;
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
								//Informacioón Venta
								$("#descuentoInfo").val(e.descuento_porcentual);
								$("#engancheInfo").val(e.enganchePorc);
								$("#pagosEngancheInfo").val(e.pagosEnganche);
								$("#plazoFinanciamientoInfo").val(e.plazoFinanciamiento);
								$("#parqueoClienteInfo").val(e.parqueosExtras);
								$("#bodegaClienteInfo").val(e.bodegasExtras);
								$("#montoReservaClienteInfo").val(e.MontoReserva);

								// //Campos Enganche
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
								$("#empresaLaboraEng").val(e.empresaLabora);
								$("#puestoEmpresaEng").val(e.puestoLabora);
								$("#direccionEmpresaEng").val(e.direccionEmpresa);
								$("#salarioMensualEng").val(e.salarioMensual);
								$("#otrosIngresosEng").val(e.otrosIngresos);
								$("#montoOtrosIngresosEng").val(e.montoOtrosIngresos);

								// Campos Cotización
								$("#nombreClienteCot").val(e.client_name);
								$("#correoCot").val(e.client_mail);
								$("#telefonoCot").val(e.telefono);
								$("#nitCot").val(e.nit);
								$("#dpiCot").val(e.numeroDpi);
								$("#fechaEmisionDpiCot").val(e.fechaEmisionDpi);
								$("#direccionCot").val(e.direccion);
								$("#nacionalidadCot").val(e.NacionalidadNombre);
								$("#fechaNacimientoCot").val(e.fechaNacimiento);
								$("#estadoCivilCot").val(e.estadoCivil);
								$("#empresaLaboraCot").val(e.empresaLabora);
								$("#puestoEmpresaCot").val(e.puestoLabora);
								$("#direccionEmpresaCot").val(e.direccionEmpresa);
								$("#salarioMensualCot").val(e.salarioMensual);
								$("#otrosIngresosCot").val(e.otrosIngresos);
								$("#montoOtrosIngresosEng").val(e.montoOtrosIngresos);
								console.log(e.idEnganche);
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
									// div += '<div class="col-lg-6 col-md-6 col-xs-10" style="text-align:right;margin-bottom:10px;" >'
									// div += '	<div class="row" >'
									// div += '		<div class="col-lg-4 col-md-4 col-xs-10" style="text-align:right;margin-bottom:10px;">'
									// div += '		</div>'
									// div += '		<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									// div += '			<img class="addclient" src="../img/more.gif" alt="agregar cliente" onclick="agregarCodeudor('+e.idCliente+','+e.idEnganche+')" ><span onclick="agregarCodeudor('+e.idCliente+','+e.idEnganche+')" class="addclienttext" >Agregar Codeudor </span>'
									// div += '		</div>'
									// div += '		<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">'
									// div += '			<img class="addclient" src="../img/usersearchicon.png" alt="ver cliente" onclick="verCodeudor('+e.idCliente+','+e.idEnganche+')" ><span onclick="verCodeudor('+e.idCliente+','+e.idEnganche+')" class="addclienttext" >Ver Codeudor</span>'
									// div += '		</div>'
									// div += '	</div>'
									// div += '</div>'	
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
									div += '<div class="col-lg-12 col-md-12 col-xs-10" >'
									div += '	<div class="row" >'
									div += '		<div class="col-lg-12 col-md-12 col-xs-10" style="text-align:center;margin-bottom:10px;">'
									div += '			<button name="btnEnganche" onclick="verEnganche('+e.idEnganche+')" class="inf" type="button">Ver Cotización Final</button>'															
									div += '		</div>'
									div += '	</div>'
									div += '</div>'								
									div += '</div></div>';
									div += '</div>';
									count ++;
								}
									
							});
							if(ul!=''){
								ul = ul + '</ul><div class="tab-content" id="renderDatos" name="renderDatos"></div>';
							}
							console.log(div);
							$("#renderList").html(ul);
							$("#renderDatos").html(div);
							//document.getElementById("btnEnganche").disabled = false;
							$("#modalInfoCliente").modal({
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
					
				}
				function fncTipoCliente(){
					var radios = document.getElementsByName('tipoCliente');
					for (var i = 0, length = radios.length; i < length; i++) {
						if (radios[i].checked) {
							if(radios[i].value=='juridico'){
								document.querySelector('#lblDireccion').innerText = 'Dirección Fiscal S.A:';
								document.querySelector('#lblNumeroDpi').innerText = 'Dpi Representante legal:';
								document.querySelector('#lblNit').innerText = 'Nit S.A:';
								document.querySelector('#lblProfesion').innerText = 'Cargo:';
								document.querySelector('#lblFechaNacimiento').innerText = 'Fecha de Constitución:';
								document.getElementById("divNombreSa").style.display = "";
								document.getElementById("divRtu").style.display = "";
								document.getElementById("divRepresentanteLegal").style.display = "";
								document.getElementById("divPatenteEmpresa").style.display = "";
								document.getElementById("divPatenteSociedad").style.display = "";
								document.getElementById("divPrimerNombre").style.display = "none";
								document.getElementById("divSegundoNombre").style.display = "none";
								document.getElementById("divTercerNombre").style.display = "none";
								document.getElementById("divPrimerApellido").style.display = "none";
								document.getElementById("divSegundoApellido").style.display = "none";
								document.getElementById("divApellidoCasada").style.display = "none";

								document.getElementById("divNacionalidad").style.display = "none";
								document.getElementById("divDependientes").style.display = "none";
								document.getElementById("divFha").style.display = "none";
								document.getElementById("divEmpresaLabora").style.display = "none";
								document.getElementById("divTelefonoReferencia").style.display = "none";
								document.getElementById("divPuestoEmpresa").style.display = "none";
								document.getElementById("divSalario").style.display = "none";
								document.getElementById("divMontoIngresos").style.display = "none";
								document.getElementById("divOtrosIngresos").style.display = "none";
								document.getElementById("divEstadoCivil").style.display = "none";
								document.getElementById("divDireccionEmpresa").style.display = "none";


							}else if(radios[i].value=='individual'){
								document.querySelector('#lblDireccion').innerText = 'Dirección Residencia';
								document.querySelector('#lblNumeroDpi').innerText = 'Número de DPI :';
								document.querySelector('#lblNit').innerText = 'Nit:';
								document.querySelector('#lblProfesion').innerText = 'Profesión:';
								document.querySelector('#lblFechaNacimiento').innerText = 'Fecha de Nacimiento:';
								document.getElementById("divNombreSa").style.display = "none";
								document.getElementById("divRtu").style.display = "none";
								document.getElementById("divRepresentanteLegal").style.display = "none";
								document.getElementById("divPatenteEmpresa").style.display = "none";
								document.getElementById("divPatenteSociedad").style.display = "none";
								document.getElementById("divPrimerNombre").style.display = "";
								document.getElementById("divSegundoNombre").style.display = "";
								document.getElementById("divTercerNombre").style.display = "";
								document.getElementById("divPrimerApellido").style.display = "";
								document.getElementById("divSegundoApellido").style.display = "";
								document.getElementById("divApellidoCasada").style.display = "";

								document.getElementById("divNacionalidad").style.display = "";
								document.getElementById("divDependientes").style.display = "";
								document.getElementById("divFha").style.display = "";
								document.getElementById("divEmpresaLabora").style.display = "";
								document.getElementById("divTelefonoReferencia").style.display = "";
								document.getElementById("divPuestoEmpresa").style.display = "";
								document.getElementById("divSalario").style.display = "";
								document.getElementById("divMontoIngresos").style.display = "";
								document.getElementById("divOtrosIngresos").style.display = "";
								document.getElementById("divEstadoCivil").style.display = "";
								document.getElementById("divDireccionEmpresa").style.display = "";
							}
							// do whatever you want with the checked radio
							//alert(radios[i].value);

							// only one radio can be logically checked, don't check the rest
							break;
						}
					}
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
					////console.log(proyecto+" - "+input+" - "+valueInput);
					if(proyecto==1){
						console.log("proyecto");
						document.getElementById("CocinaEng").disabled = true;
					}else{
						document.getElementById("CocinaEng").disabled = false;
					}
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
				function datosApartamento(apartamento='',cot=0,descuento='no'){
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
							$.each(response.detallePagos,function(i,e) {
								montoEngancheTotal= e.montoEnganche;
								output+="<tr>";
								output+="<td >"+e.noPago+"<input id=\"noPago_"+e.noPago+"\" name=\"noPago[]\" type=\"hidden\" value=\""+e.noPago+"\" readonly=\"readonly\" ></td>";
								output+="<td><input onChange=\"pagoEspecial("+e.montoEnganche+")\" id=\"chk_"+e.noPago+"\" name=\"chk[]\" type=\"checkbox\" class=\"form-check-input\"> <label class=\"form-check-label\" for=\"exampleCheck1\">Especial</label></td>";
								output+="<td><input onkeyup=\"recalculoPagoEspecial("+e.montoEnganche+")\" id=\"cuota_"+e.noPago+"\" name=\"cuotas[]\" type=\"number\" value=\""+e.monto+"\" readonly=\"readonly\"></td>";
								output+="<td><input id=\"date_"+e.noPago+"\" name=\"date[]\" type=\"date\" value=\""+e.fechaPago+"\" readonly=\"readonly\" ></td>";
								output+="</tr>";
								//output += '<tr onCLick=""><td>'+e.client_name+' '+check+'</td><td>'+e.apartment+'</td><td>'+e.proyecto+'</td></tr>';
								//console.log(output);
							});
							//console.log(output);
							output+="<tr>";				
							output+="<td colspan=\"4\">"+"<h5 class=\"tittle\" >Total "+'Q'+ new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(montoEngancheTotal)+"</h5>"+ "<input id=\"totalEnganche\" name=\"totalEnganche\" type=\"hidden\" value=\""+montoEngancheTotal+"\"></td>";
							output+="</tr>";
							var tb = document.getElementById('resultadoCuotas');
							while(tb.rows.length > 1) {
								tb.deleteRow(1);
							}
							$('#resultadoCuotas').append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
			</script>
    </body>
</html>

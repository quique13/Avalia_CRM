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

		<script src="../libs/cryptoJS/v3.1.2/rollups/aes.js"></script>
		<script src="../libs/cryptoJS/v3.1.2/rollups/md5.js"></script>

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
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/usersearchicon.png"> Agregar Codeudor</label>
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
																	<input class=" search form-control" type="" id="datoBuscar" name="datoBuscar" placeholder="Nombre, correo, DPI">	
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
																		<th style="width:15%;">Proyecto</th>
																		<th style="width:25%;">Cotizaciones</th> 
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
															<h3 style="" class="titleinf"><img class="usericon" src="../img/client_icon.png" alt="Cliente"> Información del Cliente <button onclick="editarCliente()" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="agregar cliente" ></button></h3>
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
															<label class="adresstittle">Dirección: </label>
														</div>
														<div class="col-lg-10 col-md-10 col-xs-10" style="margin-bottom:10px;">
															<input id="direccionClienteInfo" class="form-control" readonly>
														</div>
														<div class="col-lg-3 col-md-3 col-xs-10" >
														</div>
														<div class="col-lg-8 col-md-8 col-xs-10" >
															<div class="row" >
																<div class="col-lg-6 col-md-6 col-xs-10" style="text-align:center;margin-bottom:10px;">
																	<button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Ver adjuntos</button>															
																</div>
																<div class="col-lg-6 col-md-6 col-xs-10" style="text-align:center;margin-bottom:10px;">
																	<button name="btnEnganche" onclick="agregarEnganche()" class="inf" type="button">Cotización final</button>	
																</div>
																<!-- <div class="col-lg-4 col-md-4 col-xs-10" style="text-align:center;margin-bottom:10px;">
																	<button name="btnCotizacion" onclick="agregarCotizacion()" class="inf" type="button">Realizar Cotización</button>	
																</div>															 -->
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
					<div class="modal fade" id="modalAgregarClienteCo">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Agregar Codeudor</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarClienteCo" style="padding:5px 15px;" >
									<div class="secinfo" >
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarClienteCo" name="frmAgregarClienteCo" method="POST">
											<div class="row" >
												<input type="hidden" id="idClienteCo" name="idClienteCo">
												<input type="hidden" id="idEngancheCo" name="idEngancheCo">
												<input type="hidden" id="idCodeudor" name="idCodeudor">	
												<div id="divAlertClienteCo" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												</div>													
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<label class="nodpitext">Cliente:</label>
													<div class="row" >
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="primerNombreCo" name="primerNombreCo" placeHolder="Primer Nombre" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="segundoNombreCo" name="segundoNombreCo" placeHolder="Segundo Nombre" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="primerApellidoCo" name="primerApellidoCo" placeHolder="Primer apellido" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="segundoApellidoCo" name="segundoApellidoCo" placeHolder="Segundo Apellido" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="tercerNombreCo" name="tercerNombreCo" placeHolder="Tercer Nombre" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<input type="text" id="apellidoCasadaCo" name="apellidoCasadaCo" placeHolder="Apellido Casada" class="form-control" >
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Correo electrónico:</label>
															<input type="text" id="correoCo" name="correoCo" class="form-control" >
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Télefono Fijo Residencia:</label>
															<input  type="text" id="telefonoFijoCo" name="telefonoFijoCo" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Télefono Celular:</label>
															<input  type="text" id="telefonoCo" name="telefonoCo" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Dirección:</label>
															<textarea class="form-control" id="direccionCo" name="direccionCo" rows="2"></textarea>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label class="nodpitext">Nit:</label>
															<input type="text" id="nitCo" name="nitCo" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Número de DPI:</label>
															<input type="text" id="numeroDpiCo" name="numeroDpiCo" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Departamento:</label>
															<select class="form-control" name="deptoCo" id="deptoCo" onchange="getMunicipios(this.value,'municipioCo','')">
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Municipio:</label>
															<select class="form-control" name="municipioCo" id="municipioCo" onchange="">
															</select>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label class="nodpitext">Fecha Emisión DPI:</label>
															<input type="date" id="fechaEmisionDpiCo" name="fechaEmisionDpiCo" class="form-control" onChange="fechaVencimiento()">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">
															<label class="nodpitext">Fecha vencimiento DPI:</label>
															<input id="fechaVencimientoDpiCo" name="fechaVencimientoDpiCo" type="date" class="form-control">
															<input id="fechaHoyCo" name="fechaHoyCo" type="hidden" class="form-control" value="<?php echo date("d/m/Y") ?>">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label class="nodpitext">Nacionalidad:</label>
															<select class="form-control" name="nacionalidadCo" id="nacionalidadCo" onchange="">
															</select>
														</div>
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >
															<label class="nodpitext">Fecha de nacimiento:</label>
															<input type="date" id="fechaNacimientoCo" name="fechaNacimientoCo" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Estado Civil:</label>
															<select class="form-control" name="estadoCivilCo" id="estadoCivilCo" onchange="">
																<option value="" >Seleccione</optinon>
																<option value="Soltero" >Soltero(a)</optinon>
																<option value="Casado" >Casado(a)</optinon>
																<option value="Viudo" >Viudo(a)</optinon>
																<option value="Divorciado" >Divorciado(a)</optinon>
															</select>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Profesión:</label>
															<input type="text" id="profesionCo" name="profesionCo" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">No. de dependientes:</label>
															<input type="number" id="dependientesCo" name="dependientesCo" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<div class="row" >
																<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																	<label class="nodpitext">Ha tenido tramite FHA:</label>
																</div>
																<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
																	<div class=" form-check form-check-inline"  style="">
																		<input class="form-check-input" type="radio" name="fhaCo" id="si" value="si">
																		<label class="form-check-label" for="">Si</label>
																	</div>
																	<div class="form-check form-check-inline"  style="">
																		<input class="form-check-input" type="radio" name="fhaCo" id="no" value="no">
																		<label class="form-check-label">No</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Empresa donde labora:</label>
															<input type="text" id="empresaLaboraCo" name="empresaLaboraCo" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Dirección de empresa:</label>
															<textarea class="form-control" id="direccionEmpresaCo" name="direccionEmpresaCo" rows="2"></textarea>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Télefono Fijo de Trabajo:</label>
															<input  type="text" id="telefonoReferenciaCo" name="telefonoReferenciaCo" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Puesto en Empresa:</label>
															<input type="text" id="puestoEmpresaCo" name="puestoEmpresaCo" class="form-control">
														</div>
														<!-- <div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Salario mensual:</label>
															<input type="text" id="salarioMensualCo" name="salarioMensualCo" class="form-control">
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Otros ingresos:</label>
															<input type="text" id="montoOtrosIngresosCo" name="montoOtrosIngresosCo" class="form-control">
														</div>
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >
															<label class="nodpitext">Descripción Otros ingresos:</label>
															<input type="text" id="otrosIngresosCo" name="otrosIngresosCo" class="form-control">
														</div> -->
														<!-- <div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<label class="draganddroptexttitle" for="mail">DPI y Recibo de servicios:</label>
															<input class="draganddrop" type="file" id="fliesDpiReciboCo[]" name="fliesDpiReciboCo[]" placeholder="Arrastra y suelta aquí " accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" multiple>
														</div> -->
														<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
															<button onclick="verAdjuntos(), getFiltroAdjuntos()" class="inf" type="button">Agregar Adjuntos</button>
														</div>
														<script type="text/javascript">
															$("#salarioMensualCo").number( true, 2 );
															$("#montoOtrosIngresosCo").number( true, 2 );
														</script>
													</div>
													
													
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
													<button onclick="guardarCodeudor()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>
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
					<!-- Modal -->
					<div class="modal fade" id="modalAgregarClienteCoVer">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Modificar Codeudor</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarClienteCoVer" style="padding:5px 15px;" >
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					
					<!-- /.modal -->

					<!-- MODAL DOCUMENTOS ADJUNTOS -->
					<?php require_once("./documentos_adjuntos.php"); ?>

					<!-- /.modal -->
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

					<!-- CAMBIO DE CONTRASEñA POR DEFECTO -->
					<div class="modal fade" id="modal-cambio-contrasenia">
						<div class="modal-dialog mw-50 w-25 " style="height:70%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > 	
										Cambio de contrase­ña
									</h5>									
									<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarClienteCoVer" style="padding:5px 15px;" >
									<div class="col-xs-12" id="loading-change"></div>
									<form action="javascript:;" method="post" name="form-login" id="form-login" class="login-form">
										<div class="form-group d-flex">
											<input type="password" class="form-control" placeholder="Contraseña Anterior"  name="old-password" id="old-password">
										</div>
										<div class="form-group d-flex">
											<input type="password" class="form-control" placeholder="Nueva Contraseña"  name="new-password" id="new-password">
										</div>
										<div class="form-group">
											<button type="submit" class="form-control btn btn-primary submit px-3" onclick="cambioContrasenia()">Cambiar contraseña</button>
										</div>
									</form>									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
			<script type="text/javascript">

			<?php
				if (intval($_SESSION['password_default']) == 1) {
					echo    "$('#modal-cambio-contrasenia').modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});"; 
				}
			?>

				function cambioContrasenia() {
					
					const old_password = $("#old-password").val();
					const new_password = $("#new-password").val();

					//console.log("old_password", old_password, "new_password", new_password)

					if(old_password == '' || new_password == '')
					{
						$("#loading-change").html('<div class="alert alert-danger">Todos los parametros son obligatorios</div>');
						setTimeout(function(){
							$("#loading-change").html('');
						},5000)
						return true;
					}
					
					const old_hast = CryptoJS.MD5(old_password);
					const new_hast = CryptoJS.MD5(new_password);

					let formData = new FormData;
					formData.append("old_password", old_hast);
					formData.append("new_password", new_hast);

					$.ajax({
						url: "./usuario.php?cambio_contrasenia=true",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						beforeSend: function () {
							$("#loading-change").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success: function (response) {
							if (response.err) {
								$("#loading-change").html('<div class="alert alert-danger"><b>Error</b> '+response.mss+'</div>');
								setTimeout(function () {
									$("#loading-change").html('');
								}, 4000);
							}
							else {
								location.reload();
							}
						},
						error: function () {
							$("#loading-change").html('<div class="alert alert-danger"><b>Error</b> Intente nuevamente</div>');
						}
					});					
				}

				
				function buscarCliente(){
					//console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarCliente"));
					formData.append("proyectoBscTxt", document.getElementById("proyectoBsc").options[document.getElementById("proyectoBsc").selectedIndex].text);
					formData.append("torreBscTxt", document.getElementById("torreBsc").options[document.getElementById("torreBsc").selectedIndex].text);
					formData.append("nivelBscTxt", document.getElementById("nivelBsc").options[document.getElementById("nivelBsc").selectedIndex].text);
					formData.append("apartamentoBscTxt", document.getElementById("apartamentoBsc").options[document.getElementById("apartamentoBsc").selectedIndex].text);
					$.ajax({
						url: "./cliente.php?get_concidencia_cliente_codeudor=true",
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
								output += '<tr onCLick=""><td>'+e.codigo+'</td><td>'+e.client_name+' '+check+'</td><td>'+e.proyecto+'</td><td>'+e.apartamentoEnganche+'</td><td><img class="addclient" src="../img/more.gif" alt="Agregar Codeudor" title="Agregar Codeudor" onclick="agregarCodeudor('+e.id+','+e.idEnganche+')" ><img class="addclient" src="../img/usersearchicon.png" alt="ver cliente" title="Ver Codeudores" onclick="verCodeudor('+e.id+','+e.idEnganche+')" ></td></tr>';
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
					console.log('pais '+pais);
					console.log('input '+input);
					console.log('valueInput '+valueInput);
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
				
				function Alertpendiente()
				{
					////console.log("dfdf");
					$("#divAlertPendiente").html('<div class="alert alert-danger">Función pendiente, se está trabajando</div>');
								setTimeout(function(){
									$("#divAlertPendiente").html('');
								},5000)
				}
				function guardarCodeudor(idCodeudor=''){
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					
					if(error==0){
						var formDataCliente = new FormData(document.getElementById("frmAgregarClienteCo"+idCodeudor+""));
						formDataCliente.append("idEngancheCo", $("#idEngancheCo").val());
						$.ajax({
							url: "./cliente.php?agregar_editar_codeudor=true",
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
								if (response.err === true) {
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center">' + '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\')">Cerrar</div>');
								}
								else {
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarCliente();verCodeudor('+response.idCliente+','+response.idEnganche+')">Aceptar</div>');
								}					
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarClienteCo').animate({scrollTop:0}, 'fast');
						$("#divAlertClienteCo").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertClienteCo").html('');
							},5000)
					}
					
				}
				function guardarClienteFHA(idCodeudor=''){
					var error = 0;
					if(error==0){
						var formDataCliente = new FormData(document.getElementById("frmAgregarClienteFha"+idCodeudor+""));
						formDataCliente.append("idEngancheCo", $("#idEngancheCo").val());
						$.ajax({
							url: "./cliente.php?agregar_editar_cliente_info_fha_co=true",
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
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarCliente();verCodeudor('+response.idCliente+','+response.idEnganche+');">Aceptar</div>');
								}						
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarClienteCo').animate({scrollTop:0}, 'fast');
						$("#divAlertClienteCo").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertClienteCo").html('');
							},5000)
					}
					
				}
				function guardarClienteFHADependencia(idCodeudor=''){
					var error = 0;
					if(error==0){
						var formDataCliente = new FormData(document.getElementById("frmAgregarClienteFhaDependencia"+idCodeudor+""));
						formDataCliente.append("idEngancheCo", $("#idEngancheCo").val());
						
						$.ajax({
							url: "./cliente.php?agregar_editar_cliente_info_fha_dependencia_co=true",
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
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarCliente();verCodeudor('+response.idCliente+','+response.idEnganche+');">Aceptar</div>');
								}						
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarClienteCo').animate({scrollTop:0}, 'fast');
						$("#divAlertClienteCo").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertClienteCo").html('');
							},5000)
					}
					
				}
				function agregarCodeudor(idCliente,idEnganche){
					getDepartamentos(1,'deptoCo',0);
					getMunicipios(0,'municipioCo',0);
					getNacionalidad('nacionalidadCo',0)
					document.getElementById("frmAgregarClienteCo").reset();
					$("#modalAgregarClienteCo").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					//console.log("funcion buscar cliente");
					$("#idClienteCo").val(idCliente);
					$("#idEngancheCo").val(idEnganche);
					$("#idCodeudor").val('');
				}
				function verCodeudor(idCliente,idEnganche){

					//console.log("funcion buscar cliente");
					$("#idClienteCo").val(idCliente);
					$("#idEngancheCo").val(idEnganche);
					var formData = new FormData;
					formData.append("idClienteCo", idCliente);
					formData.append("idEngancheCo", idEnganche);
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
							var html ='<div class="secinfo" id="renderListCo" name="">';
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
							valor_inmueble_1 = '';
							direccion_inmueble_2 = '';
							valor_inmueble_2 = '';
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
							domicilio_2 = '';
							telefono_1 = '';	
							trabajo_1 = '';
							trabajo_direccion_1 = '';
							trabajo_telefono_1 = '';

							nombre_referencia_2 = '';	
							parentesco_referencia_2 = '';	
							trabajo_2 = '';
							trabajo_direccion_2 = '';
							trabajo_telefono_2 = '';
							telefono_2='';
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
							no_prestamo_1='';	
							monto_1='';
							saldo_actual_prestamo_1='';
							pago_mensual_prestamo_1='';
							fecha_vencimiento_prestamo_1='';

							banco_prestamo_2= '';	
							tipo_prestamo_2='';	
							no_prestamo_2='';
							monto_2='';
							saldo_actual_prestamo_2='';
							pago_mensual_prestamo_2='';
							fecha_vencimiento_prestamo_2='';
							 
							estatura = '';
							peso = '';
								


							$.each(response.info,function(i,e) {
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
									
	
							});
							$.each(response.infoDetallePatrimonial,function(i,e) {
								direccion_inmueble_1 = e.direccion_inmueble_1;
								valor_inmueble_1 = e.valor_inmueble_1;
								direccion_inmueble_2 = e.direccion_inmueble_2;
								valor_inmueble_2 = e.valor_inmueble_2;
								finca_1 = e.finca_1;
								folio_1 = e.folio_1;
								libro_1 = e.libro_1;
								departamento_1 = e.departamento_1;
								finca_2 = e.finca_2;
								folio_2 = e.folio_2;
								libro_2 = e.libro_2;
								departamento_2 = e.departamento_2;
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
								no_prestamo_2=e.no_prestamo_2;	
								monto_1=e.monto_1;
								saldo_actual_prestamo_1=e.saldo_actual_prestamo_1;
								pago_mensual_prestamo_1=e.pago_mensual_prestamo_1;
								fecha_vencimiento_prestamo_1=e.fecha_vencimiento_prestamo_1;
								
								banco_prestamo_2= e.banco_prestamo_2;	
								tipo_prestamo_2=e.tipo_prestamo_2;	
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
									div += '<input type="text" id="primerNombreCo" name="primerNombreCo" placeHolder="Primer Nombre" value="'+e.primerNombre+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="segundoNombreCo" name="segundoNombreCo" placeHolder="Segundo Nombre" value="'+e.segundoNombre+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="primerApellidoCo" name="primerApellidoCo" placeHolder="Primer apellido" value="'+e.primerApellido+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="segundoApellidoCo" name="segundoApellidoCo" placeHolder="Segundo Apellido" value="'+e.segundoApellido+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="tercerNombreCo" name="tercerNombreCo" placeHolder="Tercer Nombre" value="'+e.tercerNombre+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="apellidoCasadaCo" name="apellidoCasadaCo" placeHolder="Apellido Casada" value="'+e.apellidoCasada+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Correo electrónico:</label>'
									div += '<input type="text" id="correoCo" name="correoCo" value="'+e.client_mail+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Télefono Fijo:</label>'
									div += '<input  type="text" id="telefonoFijoCo" name="telefonoFijoCo" value="'+e.telefonoFijo+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Télefono Celular:</label>'
									div += '<input  type="text" id="telefonoCo" name="telefonoCo" value="'+e.telefono+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección:</label>'
									div += '<textarea class="form-control" id="direccionCo" name="direccionCo" rows="2">'+e.direccion+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Nit:</label>'
									div += '<input type="text" id="nitCo" name="nitCo" value="'+e.nit+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Número de DPI:</label>'
									div += '<input type="text" id="numeroDpiCo" name="numeroDpiCo" value="'+e.numeroDpi+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Departamento:</label>'
									div += '<select class="form-control" name="deptoCo" id="deptoCo_'+count+'"   onchange="getMunicipios(this.value,\'municipioCo\',\'\')">'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Municipio:</label>'
									div += '<select class="form-control" name="municipioCo" id="municipioCo_'+count+'"  onchange="">'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Fecha Emisión DPI:</label>'
									div += '<input type="date" id="fechaEmisionDpiCo" name="fechaEmisionDpiCo"  value="'+e.fechaEmisionDpi+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Fecha vencimiento DPI:</label>'
									div += '<input id="fechaVencimientoDpiCo" name="fechaVencimientoDpiCo" value="'+e.fechaVencimientoDpi+'" type="date" class="form-control">'
									div += '<input id="fechaHoyCo" name="fechaHoyCo" type="hidden" class="form-control" value="<?php echo date("d/m/Y") ?>">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Nacionalidad:</label>'
									div += '<select class="form-control" name="nacionalidadCo"   id="nacionalidadCo_'+count+'" onchange="">'
									div += '</select>'
									div += '</div>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">'
									div += '<div class="row" >'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px" >'
									div += '<label class="nodpitext">Fecha de nacimiento:</label>'
									div += '<input type="date" id="fechaNacimientoCo" value="'+e.fechaNacimiento+'" name="fechaNacimientoCo" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Estado Civil:</label>'
									div += '<select class="form-control" name="estadoCivilCo" id="estadoCivilCo" onchange="">'
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
									div += '<input type="text" id="profesionCo" name="profesionCo" value="'+e.profesion+'"  class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">No. de dependientes:</label>'
									div += '<input type="number" id="dependientesCo" name="dependientesCo" value="'+e.noDependientes+'" class="form-control">'
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
									div += '<input type="text" id="empresaLaboraCo" name="empresaLaboraCo" value="'+e.empresaLabora+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Dirección de empresa:</label>'
									div += '<textarea class="form-control" id="direccionEmpresaCo" name="direccionEmpresaCo" rows="2">'+e.direccionEmpresa+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Télefono de Referencia:</label>'
									div += '<input  type="text" id="telefonoReferenciaCo" name="telefonoReferenciaCo" value="'+e.telefonoReferencia+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Puesto en Empresa:</label>'
									div += '<input type="text" id="puestoEmpresaCo" name="puestoEmpresaCo" value="'+e.puestoLabora+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Salario mensual:</label>'
									div += '<input type="text" id="salarioMensualCo" name="salarioMensualCo"  value="'+e.salarioMensual+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Otros ingresos:</label>'
									div += '<input type="text" id="montoOtrosIngresosCo" name="montoOtrosIngresosCo" value="'+e.montoOtrosIngresos+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;" >'
									div += '<label class="nodpitext">Descripción Otros ingresos:</label>'
									div += '<input type="text" id="otrosIngresosCo" name="otrosIngresosCo" value="'+e.otrosIngresos+'" class="form-control">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<button onclick="verAdjuntos('+idCliente+'), getFiltroAdjuntos()" class="inf" type="button">Agregar Adjuntos</button>'
									div += '</div>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">'
									div += '<button onclick="guardarCodeudor('+e.idCodeudor+')" class="guardar" type="button">Guardar</button>'
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
									div += '<input type="text" id="Estatura" name="Estatura" value="'+estatura+'" placeHolder="Estatura" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Peso(Lbs):</label>	'
									div += '<input type="text" id="peso" name="peso" value="'+peso+'" placeHolder="Peso" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Activo</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Caja:</label>'
									div += '<input type="text" id="caja" name="caja" placeHolder="Caja" value="'+caja+'" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bancos:</label>'
									div += '<input type="text" id="bancos" name="bancos" value="'+bancos+'" placeHolder="Bancos" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Ctas por Cobrar:</label>'
									div += '<input type="text" id="cuentas_cobrar" name="cuentas_cobrar" value="'+cuentas_cobrar+'" placeHolder="Ctas por Cobrar" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Terrenos:</label>'
									div += '<input type="text" id="terrenos" name="terrenos" value="'+terrenos+'" placeHolder="Terrenos" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Viviendas:</label>'
									div += '<input type="text" id="viviendas" name="viviendas" value="'+viviendas+'" placeHolder="Viviendas" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Vehículos:</label>'
									div += '<input type="text" id="vehiculos" name="vehiculos" value="'+vehiculos+'" placeHolder="Vehículos" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Inversiones:</label>'
									div += '<input type="text" id="inversiones" name="inversiones" value="'+inversiones+'" placeHolder="Inversiones" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonos:</label>'
									div += '<input type="text" id="bonos" name="bonos" value="'+bonos+'" placeHolder="Bonos" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Acciones:</label>'
									div += '<input type="text" id="acciones" name="acciones" value="'+acciones+'" placeHolder="Acciones" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Muebles:</label>'
									div += '<input type="text" id="muebles" name="muebles" value="'+muebles+'" placeHolder="Muebles" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Total Activos:</label>'
									div += '<input type="text" id="total_activos" name="total_activos" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Pasivo</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Ctas por Pagar corto plazo:</label>'
									div += '<input type="text" id="cuentas_pagar_corto_plazo" value="'+cuentas_pagar_corto_plazo+'" name="cuentas_pagar_corto_plazo" placeHolder="Ctas por Pagar corto Plazo" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Ctas por Pagar largo plazo:</label>'
									div += '<input type="text" id="cuentas_pagar_largo_plazo" value="'+cuentas_pagar_largo_plazo+'" name="cuentas_pagar_largo_plazo" placeHolder="Ctas por Pagar largo Plazo" class="form-control"onChange="sumaTotal()" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Prestamos Hipotecarios:</label>'
									div += '<input type="text" id="prestamos_hipotecarios" value="'+prestamos_hipotecarios+'" name="prestamos_hipotecarios" placeHolder="Prestamos Hipotecarios" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Gastos mensuales</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Sostenimiento del Hogar:</label>'
									div += '<input type="text" id="sostenimiento_hogar" value="'+sostenimiento_hogar+'" name="sostenimiento_hogar" placeHolder="Sostenimiento del Hogar" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Alquiler:</label>'
									div += '<input type="text" id="alquiler" name="alquiler" value="'+alquiler+'" placeHolder="Alquiler" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Préstamos:</label>'
									div += '<input type="text" id="prestamos" name="prestamos" value="'+prestamos+'" placeHolder="Préstamos" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Impuestos:</label>'
									div += '<input type="text" id="impuestos" name="impuestos" value="'+impuestos+'" placeHolder="Impuestos" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Extrafinanciamientos TC:</label>'
									div += '<input type="text" id="extrafinanciamientos" value="'+extrafinanciamientos+'" name="extrafinanciamientos" placeHolder="Extrafinanciamientos TC" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Deudas Particulares:</label>'
									div += '<input type="text" id="deudas_particulares" name="deudas_particulares" placeHolder="Deudas Particulares" class="form-control" onChange="sumaTotal()">'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Total Pasivos:</label>'
									div += '<input type="text" id="total_pasivos" name="total_pasivos" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Patrimonio:</label>'
									div += '<input type="text" id="total_patrimonio" name="total_patrimonio" placeHolder="Deudas Particulares" class="form-control" readonly>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Detalle Patrimonial</label>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Bienes Inmuebles</label>'
									div += '</div>'
									div += '<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección del Inmueble:</label>'
									div += '<textarea class="form-control" id="direccion_inmueble_1"  name="direccion_inmueble_1" rows="1">'+direccion_inmueble_1+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Valor inmueble:</label>'
									div += '<input type="text" id="valor_inmueble_1" value="'+valor_inmueble_1+'" name="valor_inmueble_1" class="form-control" onchange="sumaValor()">'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Finca:</label>'
									div += '<input type="text" id="finca_1" value="'+finca_1+'" name="finca_1" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Folio:</label>'
									div += '<input type="text" id="folio_1" value="'+folio_1+'" name="folio_1" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Libro:</label>'
									div += '<input type="text" id="libro_1" value="'+libro_1+'" name="libro_1" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Departamento:</label>'
									div += '<select class="form-control" name="departamento_1" id="departamento_1">'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección del Inmueble:</label>'
									div += '<textarea class="form-control" id="direccion_inmueble_2" name="direccion_inmueble_2" rows="1">'+direccion_inmueble_2+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Valor inmueble:</label>'
									div += '<input type="text" id="valor_inmueble_2" value="'+valor_inmueble_2+'" name="valor_inmueble_2" class="form-control" onchange="sumaValor()">'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Finca:</label>'
									div += '<input type="text" id="finca_2" value="'+finca_2+'" name="finca_2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Folio:</label>'
									div += '<input type="text" id="folio_2" value="'+folio_2+'" name="folio_2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Libro:</label>'
									div += '<input type="text" id="libro_2" value="'+libro_2+'" name="libro_2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Departamento:</label>'
									div += '<select class="form-control" name="departamento_2" id="departamento_2">'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Vehículos</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Marca:</label>'
									div += '<select class="form-control" name="marca_1" id="marca_1"  onchange="getTipo(this.value,\'tipo_vehiculo_1\')">'
									div += '<option value="0" >Seleccione</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">tipo:</label>'
									div += '<select class="form-control" name="tipo_vehiculo_1" id="tipo_vehiculo_1"  onchange="getModelo(1,this.value,\'modelo_vehiculo_1\')">'
									div += '<option value="0" >Seleccione</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Modelo:</label>'
									div += '<select class="form-control" name="modelo_vehiculo_1" id="modelo_vehiculo_1">'
									div += '<option value="0" >Seleccione</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Valor Estimado:</label>'
									div += '<input type="text" id="valor_estimado_1" value="'+valor_estimado_1+'" name="valor_estimado_1" class="form-control" onchange="sumaValor()">'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Marca:</label>'
									div += '<select class="form-control" name="marca_2" id="marca_2"  onchange="getTipo(this.value,\'tipo_vehiculo_2\')">'
									div += '<option value="0" >Seleccione</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">tipo:</label>'
									div += '<select class="form-control" name="tipo_vehiculo_2" id="tipo_vehiculo_2"  onchange="getModelo(2,this.value,\'modelo_vehiculo_2\')">'
									div += '<option value="0" >Seleccione</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Modelo:</label>'
									div += '<select class="form-control" name="modelo_vehiculo_2" id="modelo_vehiculo_2">'
									div += '<option value="0" >Seleccione</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Valor Estimado:</label>'
									div += '<input type="text" id="valor_estimado_2" value="'+valor_estimado_2+'" name="valor_estimado_2" class="form-control" onchange="sumaValor()">'
									div += '</div>'
									div += '</div>'
									
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">'
									div += '<button onclick="guardarClienteFHA('+e.idCodeudor+')" class="guardar" type="button">Guardar</button>'
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
									div += '<input type="date" id="vigencia_vence" value="'+vigencia_vence+'" name="vigencia_vence" class="form-control">'	
									div += '</div>'	
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'	
									div += '<label class="nodpitext2">Detalle de Ingresos Mensuales</label>'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Salario Nominal:</label>'	
									div += '<input type="text" id="salario_nominal" value="'+salario_nominal+'" name="salario_nominal" placeHolder="Salario Nominal" class="form-control" >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Aguinaldo (1/12):</label>'	
									div += '<input type="text" id="bono_catorce" value="'+bono_catorce+'"   name="bono_catorce" placeHolder="Bono 14" class="form-control" >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Aguinaldo (1/12):</label>'	
									div += '<input type="text" id="aguinaldo" value="'+aguinaldo+'" name="aguinaldo" placeHolder="Aguinaldo" class="form-control" >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Honorarios:</label>'	
									div += '<input type="text" id="honorarios" value="'+honorarios+'"  name="honorarios" placeHolder="Honorarios" class="form-control" >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Otros:</label>'	
									div += '<input type="text" id="otros_ingresos_fha" value="'+otros_ingresos_fha+'"  name="otros_ingresos_fha" placeHolder="Otros" class="form-control" >'	
									div += '</div>'	
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'	
									div += '<label class="nodpitext2">Detalle de descuentos Mensuales</label>'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">IGSS:</label>'	
									div += '<input type="text" id="igss" name="igss" value="'+igss+'"  placeHolder="Igss" class="form-control" >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">ISR:</label>'	
									div += '<input type="text" id="isr" name="isr" value="'+isr+'"  placeHolder="Isr" class="form-control" >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Plan de Pensiones:</label>'	
									div += '<input type="text" id="plan_pensiones" value="'+plan_pensiones+'"  name="plan_pensiones" placeHolder="Plan Pensiones" class="form-control" >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Judiciales:</label>'	
									div += '<input type="text" id="judiciales" value="'+judiciales+'"  name="judiciales" placeHolder="Judiciales" class="form-control" >'	
									div += '</div>'	
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'	
									div += '<label class="nodpitext">Otros:</label>'	
									div += '<input type="text" id="otros_descuentos_fha" value="'+otros_descuentos_fha+'"  name="otros_descuentos_fha" placeHolder="Otros" class="form-control" >'	
									div += '</div>'	
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'	
									div += '<label class="nodpitext2">Detalle de horas extras, comisiones y Bonificaciones últimos 6 meses</label>'	
									div += '</div>'	
									// div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'	
									// div += '<label class="nodpitext">- Mes</label>'	
									// div += '</div>'	
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 1:</label>'
									// div += '<input type="text" id="mes_1" name="mes_1" value="'+mes_1+'" placeHolder="Mes 1" class="form-control" >'
									// div += '</div>'
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 2:</label>'
									// div += '<input type="text" id="mes_2" name="mes_2" value="'+mes_2+'"  placeHolder="Mes 2" class="form-control" >'
									// div += '</div>'
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 3:</label>'
									// div += '<input type="text" id="mes_3" name="mes_3"  value="'+mes_3+'" placeHolder="Mes 3" class="form-control" >'
									// div += '</div>'
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 4:</label>'
									// div += '<input type="text" id="mes_4" name="mes_4"  value="'+mes_4+'" placeHolder="Mes 4" class="form-control" >'
									// div += '</div>'
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 5:</label>'
									// div += '<input type="text" id="mes_5" name="mes_5"  value="'+mes_5+'" placeHolder="Mes 5" class="form-control" >'
									// div += '</div>'
									// div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									// div += '<label class="nodpitext">Mes 6:</label>'
									// div += '<input type="text" id="mes_6" name="mes_6"  value="'+mes_6+'" placeHolder="Mes 6" class="form-control" >'
									// div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">- Horas Extras</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 1:</label>'
									div += '<input type="text" id="hora_extra_mes_1"  value="'+hora_extra_mes_1+'" name="hora_extra_mes_1" placeHolder="Mes 1" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 2:</label>'
									div += '<input type="text" id="hora_extra_mes_2" value="'+hora_extra_mes_2+'" name="hora_extra_mes_2" placeHolder="Mes 2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 3:</label>'
									div += '<input type="text" id="hora_extra_mes_3" name="hora_extra_mes_3" value="'+hora_extra_mes_3+'" placeHolder="Mes 3" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 4:</label>'
									div += '<input type="text" id="hora_extra_mes_4" name="hora_extra_mes_4" value="'+hora_extra_mes_4+'" placeHolder="Mes 4" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 5:</label>'
									div += '<input type="text" id="hora_extra_mes_5" name="hora_extra_mes_5" value="'+hora_extra_mes_5+'" placeHolder="Mes 5" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Hora extra Mes 6:</label>'
									div += '<input type="text" id="hora_extra_mes_6" name="hora_extra_mes_6" value="'+hora_extra_mes_6+'" placeHolder="Mes 6" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">- Comisiones</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 1:</label>'
									div += '<input type="text" id="comisiones_mes_1" name="comisiones_mes_1" value="'+comisiones_mes_1+'" placeHolder="Mes 1" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 2:</label>'
									div += '<input type="text" id="comisiones_mes_2" name="comisiones_mes_2" value="'+comisiones_mes_2+'" placeHolder="Mes 2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 3:</label>'
									div += '<input type="text" id="comisiones_mes_3" name="comisiones_mes_3" value="'+comisiones_mes_3+'" placeHolder="Mes 3" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 4:</label>'
									div += '<input type="text" id="comisiones_mes_4" name="comisiones_mes_4" value="'+comisiones_mes_4+'" placeHolder="Mes 4" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 5:</label>'
									div += '<input type="text" id="comisiones_mes_5" name="comisiones_mes_5" value="'+comisiones_mes_5+'" placeHolder="Mes 5" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Comisiones Mes 6:</label>'
									div += '<input type="text" id="comisiones_mes_6" name="comisiones_mes_6" value="'+comisiones_mes_6+'" placeHolder="Mes 6" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">- Bonificaciones</label>'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 1:</label>'
									div += '<input type="text" id="bonificaciones_mes_1" name="bonificaciones_mes_1" value="'+bonificaciones_mes_1+'" placeHolder="Mes 1" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 2:</label>'
									div += '<input type="text" id="bonificaciones_mes_2" name="bonificaciones_mes_2" value="'+bonificaciones_mes_2+'" placeHolder="Mes 2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 3:</label>'
									div += '<input type="text" id="bonificaciones_mes_3" name="bonificaciones_mes_3" value="'+bonificaciones_mes_3+'" placeHolder="Mes 3" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 4:</label>'
									div += '<input type="text" id="bonificaciones_mes_4" name="bonificaciones_mes_4" value="'+bonificaciones_mes_4+'" placeHolder="Mes 4" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 5:</label>'
									div += '<input type="text" id="bonificaciones_mes_5" name="bonificaciones_mes_5" value="'+bonificaciones_mes_5+'" placeHolder="Mes 5" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Bonificaciones Mes 6:</label>'
									div += '<input type="text" id="bonificaciones_mes_6" name="bonificaciones_mes_6" value="'+bonificaciones_mes_6+'" placeHolder="Mes 6" class="form-control" >'
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
									div += '<input type="text" id="empresa_1" name="empresa_1"  value="'+empresa_1+'" placeHolder="Nombre Empresa" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="cargo_1" name="cargo_1" value="'+cargo_1+'" placeHolder="Nombre Cargo" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="desde_1" name="desde_1" value="'+desde_1+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="hasta_1" name="hasta_1" value="'+hasta_1+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="empresa_2" name="empresa_2" value="'+empresa_2+'" placeHolder="Nombre Empresa" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="cargo_2" name="cargo_2" value="'+cargo_2+'" placeHolder="Nombre Cargo" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="desde_2" name="desde_2" value="'+desde_2+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="hasta_2" name="hasta_2" value="'+hasta_2+'" class="form-control" >'
									div += '</div>'

									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="empresa_3" name="empresa_3" value="'+empresa_3+'" placeHolder="Nombre Empresa" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="cargo_3" name="cargo_3" value="'+cargo_3+'" placeHolder="Nombre Cargo" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="desde_3" name="desde_3" value="'+desde_3+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="hasta_3" name="hasta_3" value="'+hasta_3+'" class="form-control" >'
									div += '</div>'

									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="empresa_4" name="empresa_4" value="'+empresa_4+'" placeHolder="Nombre Empresa" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="cargo_4" name="cargo_4" value="'+cargo_4+'" placeHolder="Nombre Cargo" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="desde_4" name="desde_4" value="'+desde_4+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="date" id="hasta_4" name="hasta_4" value="'+hasta_4+'" class="form-control" >'
									div += '</div>'

									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Referencias Familiares, Bancarias y Crediticias</label>'
									div += '</div>'

									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Ref. Familiar 1</label>'
									div += '</div>'

									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Nombre:</label>'
									div += '<input type="text" id="nombre_referencia_1" value="'+nombre_referencia_1+'"  name="nombre_referencia_1" placeHolder="Nombre" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Parentesco:</label>'
									div += '<select class="form-control" name="parentesco_referencia_1" id="parentesco_referencia_1">'
									div += '<option value="" >Seleccione</optinon>'
									selectedParentesco1 ="Abuelo(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Abuelo(a)" '+selectedParentesco1+' >Abuelo(a)</optinon>'
									selectedParentesco1 ="Bisnieto(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Bisnieto(a)" '+selectedParentesco1+' >Bisnieto(a)</optinon>'
									selectedParentesco1 ="Hermano(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Hermano(a)" '+selectedParentesco1+' >Hermano(a)</optinon>'
									selectedParentesco1 ="Hijo(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Hijo(a)" '+selectedParentesco1+' >Hijo(a)</optinon>'
									selectedParentesco1 ="Mamá"== parentesco_referencia_1?"selected":""
									div += '<option value="Mamá" '+selectedParentesco1+' >Mamá</optinon>'
									selectedParentesco1 ="Nieto(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Nieto(a)" '+selectedParentesco1+' >Nieto(a)</optinon>'
									selectedParentesco1 ="Suegro(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Suegro(a)" '+selectedParentesco1+'> Suegro(a)</optinon>'
									selectedParentesco1 ="Papá"== parentesco_referencia_1?"selected":""
									div += '<option value=Papá" '+selectedParentesco1+' >Papá</optinon>'
									selectedParentesco1 ="Primo(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Primo(a)" '+selectedParentesco1+' >Primo(a)</optinon>'
									selectedParentesco1 ="Sobrino(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Sobrino(a)" '+selectedParentesco1+' >Sobrino(a)</optinon>'
									selectedParentesco1 ="Tio(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Tio(a)" '+selectedParentesco1+' >Tio(a)</optinon>'
									selectedParentesco1 ="Esposo(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Esposo(a)" '+selectedParentesco1+' >Esposo(a)</optinon>'
									selectedParentesco1 ="Yerno"== parentesco_referencia_1?"selected":""
									div += '<option value="Yerno" '+selectedParentesco1+' >Yerno</optinon>'
									selectedParentesco1 ="Nuera"== parentesco_referencia_1?"selected":""
									div += '<option value="Nuera" '+selectedParentesco1+' >Nuera</optinon>'
									selectedParentesco1 ="Cuñado(a)"== parentesco_referencia_1?"selected":""
									div += '<option value="Cuñado(a)" '+selectedParentesco1+' >Cuñado(a)</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Domicilio:</label>'
									div += '<textarea class="form-control" id="domicilio_1" name="domicilio_1" rows="1">'+domicilio_1+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Telefono:</label>'
									div += '<input type="text" id="telefono_1" value="'+telefono_1+'" name="telefono_1" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Trabajo:</label>'
									div += '<input type="text" id="trabajo_1" value="'+trabajo_1+'" name="trabajo_1" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección:</label>'
									div += '<textarea class="form-control" id="trabajo_direccion_1" name="trabajo_direccion_1" rows="1">'+trabajo_direccion_1+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Telefono:</label>'
									div += '<input type="text" id="trabajo_telefono_1" value="'+trabajo_telefono_1+'" name="trabajo_telefono_1" class="form-control" >'
									div += '</div>'

									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Ref. Familiar 2</label>'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Nombre:</label>'
									div += '<input type="text" id="nombre_referencia_2" value="'+nombre_referencia_2+'" name="nombre_referencia_2" placeHolder="Nombre" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Parentesco:</label>'
									div += '<select class="form-control" name="parentesco_referencia_2" id="parentesco_referencia_2">'
									div += '<option value="" >Seleccione</optinon>'
									selectedParentesco2 ="Abuelo(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Abuelo(a)" '+selectedParentesco2+' >Abuelo(a)</optinon>'
									selectedParentesco2 ="Bisnieto(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Bisnieto(a)" '+selectedParentesco2+' >Bisnieto(a)</optinon>'
									selectedParentesco2 ="Hermano(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Hermano(a)" '+selectedParentesco2+' >Hermano(a)</optinon>'
									selectedParentesco2 ="Hijo(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Hijo(a)" '+selectedParentesco2+' >Hijo(a)</optinon>'
									selectedParentesco2 ="Mamá"== parentesco_referencia_2?"selected":""
									div += '<option value="Mamá" '+selectedParentesco2+' >Mamá</optinon>'
									selectedParentesco2 ="Nieto(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Nieto(a)" '+selectedParentesco2+' >Nieto(a)</optinon>'
									selectedParentesco2 ="Suegro(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Suegro(a)" '+selectedParentesco2+'> Suegro(a)</optinon>'
									selectedParentesco2 ="Papá"== parentesco_referencia_2?"selected":""
									div += '<option value=Papá" '+selectedParentesco2+' >Papá</optinon>'
									selectedParentesco2 ="Primo(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Primo(a)" '+selectedParentesco2+' >Primo(a)</optinon>'
									selectedParentesco2 ="Sobrino(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Sobrino(a)" '+selectedParentesco2+' >Sobrino(a)</optinon>'
									selectedParentesco2 ="Tio(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Tio(a)" '+selectedParentesco2+' >Tio(a)</optinon>'
									selectedParentesco2 ="Esposo(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Esposo(a)" '+selectedParentesco2+' >Esposo(a)</optinon>'
									selectedParentesco2 ="Yerno"== parentesco_referencia_2?"selected":""
									div += '<option value="Yerno" '+selectedParentesco2+' >Yerno</optinon>'
									selectedParentesco2 ="Nuera"== parentesco_referencia_2?"selected":""
									div += '<option value="Nuera" '+selectedParentesco2+' >Nuera</optinon>'
									selectedParentesco2 ="Cuñado(a)"== parentesco_referencia_2?"selected":""
									div += '<option value="Cuñado(a)" '+selectedParentesco2+' >Cuñado(a)</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-9 col-md-9 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Domicilio:</label>'
									div += '<textarea class="form-control" id="domicilio_2" name="domicilio_2" rows="1">'+domicilio_2+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Telefono:</label>'
									div += '<input type="text" id="telefono_2" value="'+telefono_2+'" name="telefono_2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Trabajo:</label>'
									div += '<input type="text" id="trabajo_2" value="'+trabajo_2+'" name="trabajo_2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Dirección:</label>'
									div += '<textarea class="form-control" id="trabajo_direccion_2" name="trabajo_direccion_2" rows="1">'+trabajo_direccion_2+'</textarea>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Telefono:</label>'
									div += '<input type="text" id="trabajo_telefono_2" value="'+trabajo_telefono_2+'" name="trabajo_telefono_2" class="form-control" >'
									div += '</div>'

									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Referencias Bancarias</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Banco</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Tipo de Cuenta</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">No. de Cuenta</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext">Saldo Actual</label>'
									div += '</div>'

									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="banco_1" name="banco_1" value="'+banco_1+'" placeHolder="Banco" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<select class="form-control" name="tipo_cuenta_1" id="tipo_cuenta_1">'
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
									div += '<input type="text" id="no_cuenta_1" name="no_cuenta_1" value="'+no_cuenta_1+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="saldo_actual_1" value="'+saldo_actual_1+'" name="saldo_actual_1" class="form-control" onchange="sumaValor()">'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="banco_2" name="banco_2" value="'+banco_2+'" placeHolder="Banco" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<select class="form-control" name="tipo_cuenta_2" id="tipo_cuenta_2">'
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
									div += '<input type="text" id="no_cuenta_2" value="'+no_cuenta_2+'" name="no_cuenta_2" placeHolder="Tipo No. Cuenta" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<input type="text" id="saldo_actual_2" value="'+saldo_actual_2+'"  name="saldo_actual_2" class="form-control" onchange="sumaValor()">'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">'
									div += '<label class="nodpitext2">Referenicias Crediticias</label>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Banco Prestamo:</label>'
									div += '<input type="text" id="banco_prestamo_1" value="'+banco_prestamo_1+'" name="banco_prestamo_1" placeHolder="Banco" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Tipo de Préstamo:</label>'
									div += '<select class="form-control" name="tipo_prestamo_1" id="tipo_prestamo_1">'
									div += '<option value="" >Seleccione</optinon>'
									selectedTipoPrestamo1 ="Fiduciario"== tipo_prestamo_1?"selected":""
									div += '<option value="Fiduciario" '+selectedTipoPrestamo1+' >Fiduciario</optinon>'
									selectedTipoPrestamo1 ="Hipotecario"== tipo_prestamo_1?"selected":""
									div += '<option value="Hipotecario" '+selectedTipoPrestamo1+'>Hipotecario</optinon>'
									selectedTipoPrestamo1 ="FHA"== tipo_prestamo_1?"selected":""
									div += '<option value="FHA" '+selectedTipoPrestamo1+'>FHA</optinon>'
									selectedTipoPrestamo1 ="Prendario"== tipo_prestamo_1?"selected":""
									div += '<option value="Prendario" '+selectedTipoPrestamo1+'>Prendario</optinon>'
									selectedTipoPrestamo1 ="Tarjeta Credito"== tipo_prestamo_1?"selected":""
									div += '<option value="Tarjeta Credito" '+selectedTipoPrestamo1+'>Tarjeta Credito</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">No. Prestamo:</label>'
									div += '<input type="text" id="no_prestamo_1" name="no_prestamo_1" value="'+no_prestamo_1+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Monto:</label>'
									div += '<input type="text" id="monto_1" name="monto_1" value="'+monto_1+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Saldo Actual:</label>'
									div += '<input type="text" id="saldo_actual_prestamo_1" value="'+saldo_actual_prestamo_1+'" name="saldo_actual_prestamo_1" class="form-control" onchange="sumaValor()">'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Pago Mensual:</label>'
									div += '<input type="text" id="pago_mensual_prestamo_1" value="'+pago_mensual_prestamo_1+'" name="pago_mensual_prestamo_1" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Fecha Vencimiento:</label>'
									div += '<input type="date" id="fecha_vencimiento_prestamo_1" value="'+fecha_vencimiento_prestamo_1+'" name="fecha_vencimiento_prestamo_1" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Banco Prestamo:</label>'
									div += '<input type="text" id="banco_prestamo_2" value="'+banco_prestamo_2+'" name="banco_prestamo_2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Tipo de Préstamo:</label>'
									div += '<select class="form-control" name="tipo_prestamo_2" id="tipo_prestamo_2">'
									div += '<option value="" >Seleccione</optinon>'
									selectedTipoPrestamo2 ="Fiduciario"== tipo_prestamo_2?"selected":""
									div += '<option value="Fiduciario" '+selectedTipoPrestamo2+' >Fiduciario</optinon>'
									selectedTipoPrestamo2 ="Hipotecario"== tipo_prestamo_2?"selected":""
									div += '<option value="Hipotecario" '+selectedTipoPrestamo2+'>Hipotecario</optinon>'
									selectedTipoPrestamo2 ="FHA"== tipo_prestamo_2?"selected":""
									div += '<option value="FHA" '+selectedTipoPrestamo2+'>FHA</optinon>'
									selectedTipoPrestamo2 ="Prendario"== tipo_prestamo_2?"selected":""
									div += '<option value="Prendario" '+selectedTipoPrestamo2+'>Prendario</optinon>'
									selectedTipoPrestamo2 ="Tarjeta Credito"== tipo_prestamo_2?"selected":""
									div += '<option value="Tarjeta Credito" '+selectedTipoPrestamo2+'>Tarjeta Credito</optinon>'
									div += '</select>'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">No. Prestamo:</label>'
									div += '<input type="text" id="no_prestamo_2" name="no_prestamo_2" value="'+no_prestamo_2+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Monto:</label>'
									div += '<input type="text" id="monto_2" name="monto_2" value="'+monto_2+'" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Saldo Actual:</label>'
									div += '<input type="text" id="saldo_actual_prestamo_2" value="'+saldo_actual_prestamo_2+'" name="saldo_actual_prestamo_2" class="form-control" onchange="sumaValor()">'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-4 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Pago Mensual:</label>'
									div += '<input type="text" id="pago_mensual_prestamo_2" value="'+pago_mensual_prestamo_2+'" name="pago_mensual_prestamo_2" class="form-control" >'
									div += '</div>'
									div += '<div class="col-lg-3 col-md-3 col-xs-10" style="margin-bottom:10px;padding-right:0px">'
									div += '<label class="nodpitext">Fecha Vencimiento:</label>'
									div += '<input type="date" id="fecha_vencimiento_prestamo_2" value="'+fecha_vencimiento_prestamo_2+'" name="fecha_vencimiento_prestamo_2" class="form-control" >'
									div += '</div>'
									div += '</div>'
									div += '</div>'
									div += '<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">'
									div += '<button onclick="guardarClienteFHADependencia('+e.idCodeudor+')" class="guardar" type="button" <?php echo $disabledGuardar ?>>Guardar</button>'
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
									getDepartamentos(1,'departamento_1',departamento_1);
									getDepartamentos(1,'departamento_2',departamento_2);
									getMunicipios(e.departamento,'municipioCo_'+count1+'',e.municipio);
									getNacionalidad('nacionalidadCo_'+count1+'',e.Nacionalidad);
									getMarca('marca_1',marca_1);
									getMarca('marca_2',marca_2);
									getTipo(marca_1,'tipo_vehiculo_1',tipo_vehiculo_1);
									getTipo(marca_2,'tipo_vehiculo_2',tipo_vehiculo_2);
									getModelo(1,tipo_vehiculo_1,'modelo_vehiculo_1',modelo_vehiculo_1,marca_1);
									getModelo(2,tipo_vehiculo_1,'modelo_vehiculo_2',modelo_vehiculo_2,marca_2);
									//console.log('deptoCo '+e.departamento+' -municipioCo '+e.municipio+' -nacionalidadCo_'+count1+' '+e.Nacionalidad)		
								});
								$("#salarioMensualCo").number( true, 2 );
								$("#montoOtrosIngresosCo").number( true, 2 );
								//document.getElementById("btnEnganche").disabled = false;
								sumaValor();
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				
				}
			function fechaVencimiento(){
				var fecha_emision = $("#fechaEmisionDpiCo").val();
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
				$("#fechaVencimientoDpiCo").val(res);
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
			function getModelo(no,tipo,input,valueInput,marca = 0){
					
				var formData = new FormData;
				if(marca == 0){
					var marca = $("#marca_"+no).val();
				}
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

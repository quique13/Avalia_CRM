<?php
session_name("inmobiliaria");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>"; 
}
$id_usuario=$_SESSION['id_usuario'];
$id_perfil = $_SESSION['id_perfil'];
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
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/usersearchicon.png"> Consultar Info. FHA de Cliente</label>
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
																		<th style="width:30%;">Cliente</th>
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

					<!-- Modal -->
					<div class="modal fade" id="modalAgregarCliente">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Info. FHA</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#clienteFHA">Información Cliente</a></li>
										<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#resguardoFHA">Información Resguardo</a></li>
										<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#inmuebleFHA">Información Inmueble</a></li>
										<li class="nav-item"><a class="nav-link " data-toggle="tab" href="#bancoFHA">Información Banco</a></li>
										<li class="nav-item"><a class="nav-link " data-toggle="tab" href="#ventaFHA">Información Venta</a></li> 
									</ul>
								<div class="modal-body tab-content" id="bodyAgregarCliente" style="padding:5px 15px;" >
									<input type="hidden" id="idCliente" name="idCliente">
									<input type="hidden" id="proyectoCliente" name="proyectoCliente">
									<input type="hidden" id="idOcaCliente" name="idOcaCliente">
									<input type="hidden" id="idEnganche" name="idEnganche">			
									<div id="divAlertCliente" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px">
									</div>										
									<div id="clienteFHA" class="col-lg-12 col-md-12 col-xs-12 container tab-pane active" style="padding:5px;height:80%">	
										<div class="col-12 col-md-12" style="margin-bottom:10px;">
											<div class="row" >
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Deudor: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="deudor" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Codeudor: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="codeudor" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Proyecto: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="proyecto" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Apartamento: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="apartamento" class="form-control" value="" readonly>
												</div>
											</div>	
										</div>
									</div>
									<div id="resguardoFHA" class="col-lg-12 col-md-12 col-xs-12 container tab-pane" style="padding:5px;height:80%">	
										<div class="col-12 col-md-12" style="margin-bottom:10px;">
											<div class="row" >
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<input type="hidden" id="deudor" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<input type="hidden"  id="codeudor" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<input type="hidden" id="proyecto" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<input type="hidden" id="apartamento" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Resguardo Asegurabilidad: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="resguardo" name="resguardo" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Fecha Emisión: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input type="date" id="fecha_emision" name="fecha_emision" class="form-control">
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Valor de Resguardo: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="valor_resguardo" name="valor_resguardo" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Fecha Caducidad: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input type="date" id="fecha_caducidad" name="fecha_caducidad" class="form-control">
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
													<button onclick="guardarInfoCliente()" class="guardar" type="button">Guardar</button>
												</div>
											</div>	
										</div>
										<script type="text/javascript">
											$("#valor_resguardo").number( true, 2 );
										</script>
									</div>
									<div id="inmuebleFHA" class="col-lg-12 col-md-12 col-xs-12 container tab-pane" style="padding:5px;height:80%">	
										<form action="javascript:;" id="formInmueble" name="formInmueble" method="POST">
											<div class="row" >
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"> </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Tipo: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Identificación: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Finca: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Folio: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Libro: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Propiedad 1: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<select id="tipo_1" name="tipo[]" class="form-control"  onchange="">
														<option value="" >SELECCIONE</optinon>
														<option value="Apartamento" >Apartamento</optinon>
														<option value="Parqueo Carro" >Parqueo Carro</optinon>
														<option value="Parqueo Moto" >Parqueo Moto</optinon>
														<option value="Bodega" >Bodega</optinon>
													</select>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="identificacion_1" name="identificacion[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="finca_1" name="finca[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="folio_1" name="folio[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="libro_1" name="libro[]" class="form-control" >
												</div>

												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"></label>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Dirección: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Área: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Valor: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"></label>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<textarea class="form-control" id="direccion_1" name="direccion[]" rows="2"></textarea>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="area_1" name="area[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="valor_1" name="valor[]" class="form-control" >
												</div>

												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Propiedad 2: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<select id="tipo_2" name="tipo[]" class="form-control"  onchange="">
														<option value="" >SELECCIONE</optinon>
														<option value="Apartamento" >Apartamento</optinon>
														<option value="Parqueo Carro" >Parqueo Carro</optinon>
														<option value="Parqueo Moto" >Parqueo Moto</optinon>
														<option value="Bodega" >Bodega</optinon>
													</select>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="identificacion_2" name="identificacion[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="finca_2" name="finca[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="folio_2" name="folio[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="libro_2" name="libro[]" class="form-control" >
												</div>

												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"></label>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Dirección: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Área: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Valor: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"></label>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<textarea class="form-control" id="direccion_2" name="direccion[]" rows="2"></textarea>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="area_2" name="area[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="valor_2" name="valor[]" class="form-control" >
												</div>

												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Propiedad 3: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<select id="tipo_3" name="tipo[]" class="form-control"  onchange="">
														<option value="" >SELECCIONE</optinon>
														<option value="Apartamento" >Apartamento</optinon>
														<option value="Parqueo Carro" >Parqueo Carro</optinon>
														<option value="Parqueo Moto" >Parqueo Moto</optinon>
														<option value="Bodega" >Bodega</optinon>
													</select>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="identificacion_3" name="identificacion[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="finca_3" name="finca[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="folio_3" name="folio[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="libro_3" name="libro[]" class="form-control" >
												</div>

												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"></label>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Dirección: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Área: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Valor: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"></label>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<textarea class="form-control" id="direccion_3" name="direccion[]" rows="2"></textarea>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="area_3" name="area[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="valor_3" name="valor[]" class="form-control" >
												</div>

												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Propiedad 4: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<select id="tipo_4" name="tipo[]" class="form-control"  onchange="">
														<option value="" >SELECCIONE</optinon>
														<option value="Apartamento" >Apartamento</optinon>
														<option value="Parqueo Carro" >Parqueo Carro</optinon>
														<option value="Parqueo Moto" >Parqueo Moto</optinon>
														<option value="Bodega" >Bodega</optinon>
													</select>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="identificacion_4" name="identificacion[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="finca_4" name="finca[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="folio_4" name="folio[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="libro_4" name="libro[]" class="form-control" >
												</div>

												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"></label>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Dirección: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Área: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Valor: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"></label>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<textarea class="form-control" id="direccion_4" name="direccion[]" rows="2"></textarea>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="area_4" name="area[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="valor_4" name="valor[]" class="form-control" >
												</div>
												
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Propiedad 5: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<select id="tipo_5" name="tipo[]" class="form-control"  onchange="">
														<option value="" >SELECCIONE</optinon>
														<option value="Apartamento" >Apartamento</optinon>
														<option value="Parqueo Carro" >Parqueo Carro</optinon>
														<option value="Parqueo Moto" >Parqueo Moto</optinon>
														<option value="Bodega" >Bodega</optinon>
													</select>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="identificacion_5" name="identificacion[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="finca_5" name="finca[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="folio_5" name="folio[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="libro_5" name="libro[]" class="form-control" >
												</div>

												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"></label>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Dirección: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Área: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Valor: </label>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo"></label>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
													<textarea class="form-control" id="direccion_5" name="direccion[]" rows="2"></textarea>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="area_5" name="area[]" class="form-control" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="valor_5" name="valor[]" class="form-control" >
												</div>

											</div>
											<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
												<button  onclick="guardarInfoInmueble()" class="guardar" type="button">Guardar</button>
											</div>
										</form>
									</div>
									<div id="bancoFHA" class="col-lg-12 col-md-12 col-xs-12 container tab-pane" style="padding:5px;height:80%">	
										<div class="col-12 col-md-12" style="margin-bottom:10px;">
											<div class="row" >
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Banco: </label>
													
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<select class="form-control" name="banco" id="banco"  onchange="tasaInteres(this.value)">
														<option value="" >SELECCIONE</optinon>
													</select>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">No.Resolución: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="no_resolucion" class="form-control" value="" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Fecha Resolución: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input type="date" id="fecha_resolucion" class="form-control" value="" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Tasa Interes: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="tasa_interes" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Plazo del Crédito: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input type="number" id="plazo_credito" name="plazo_credito" class="form-control" onchange="calculoCuota()" >
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Cuota: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input type="text" id="cuota" name="cuota" class="form-control" readonly>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
													<button onclick="guardarInfobanco()" class="guardar" type="button">Guardar</button>
												</div>
											</div>	
										</div>
									</div>
									<div id="ventaFHA" class="col-lg-12 col-md-12 col-xs-12 container tab-pane" style="padding:5px;height:80%">	
										<div class="col-12 col-md-12" style="margin-bottom:10px;">
											<div class="row" >
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Precio Venta: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="precio_venta" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Monto Asegura: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="monto_asegura" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;margin-top:10px;">
													<h3  class="infoventtittle"> Resumen valores de venta</h3>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Porción Facturar %: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="porcion_factura" class="form-control" value=""readonly >
												</div>
												
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Porción Acción %: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="porcion_accion" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">IVA %: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="iva" class="form-control" value=12 readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Timbres %: </label>
												</div>
												
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="timbres" class="form-control" value=3 readonly>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;margin-top:10px;">
													<h3  class="infoventtittle">Porción Facturada</h3>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Porción Facturar: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="porcion_factura_monto" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">IVA: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="iva_monto" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Porción Acción: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="porcion_accion_monto" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">TIMBRES: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="timbres_monto" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Validación: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="validacion_monto" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Total Impuestos: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="total_impuestos" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="padding-left:0px;margin-bottom:10px;margin-top:10px;">
													<h3  class="infoventtittle">Cobros FHA</h3>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Prima Seguro primer año: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="prima_primer" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Prima Seguro Desgravamen: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="prima_desgravamen" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Derecho Inspección: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="derecho_inspeccion" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Derecho Solicitud: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="derecho_solicitud" class="form-control" value="" readonly>
												</div>
												<div class="col-lg-2 col-md-2 col-xs-10" style="margin-bottom:10px;">
													<label class="usernametittleinfo">Total de gastos cierre FHA: </label>
												</div>
												<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
													<input id="total_gastos" class="form-control" value="" readonly>
												</div>
												
												
												<script type="text/javascript">
													$("#precio_venta").number( true, 2 );
													$("#monto_asegura").number( true, 2 );
													$("#porcion_factura").number( true, 2 );
													$("#porcion_accion").number( true, 2 );
													$("#porcion_factura_monto").number( true, 2 );
													$("#porcion_accion_monto").number( true, 2 );
													$("#iva").number( true, 2 );
													$("#timbres").number( true, 2 );
													$("#iva_monto").number( true, 2 );
													$("#timbres_monto").number( true, 2 );
													$("#total_impuestos").number( true, 2 );
													$("#total_gastos").number( true, 2 );
												</script>
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
				</div>
			</div>
		</div>
			<script type="text/javascript">
				
				function getBancos(input,valueInput){
				//console.log("funcion buscar niveles");
				var formData = new FormData;
				$.ajax({
					url: "./cliente.php?get_bancos_financiar=true",
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
			function tasaInteres(banco){
				//console.log("funcion buscar cliente unico");
					//var formData = new FormData(document.getElementById("frmBuscarCliente"));
					var formData = new FormData;
					formData.append("bancoInteres", banco);
					formData.append("idEnganche",$("#idEnganche").val() );
					$.ajax({
						url: "./cliente.php?get_informacion_banco=true",
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
								$("#tasa_interes").val('');
							$.each(response.bancos,function(i,e) {
								$("#tasa_interes").val(e.interes);
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
				function buscarCliente(){
					//console.log("funcion buscar cliente");
					var formData = new FormData(document.getElementById("frmBuscarCliente"));
					formData.append("proyectoBscTxt", document.getElementById("proyectoBsc").options[document.getElementById("proyectoBsc").selectedIndex].text);
					// formData.append("torreBscTxt", document.getElementById("torreBsc").options[document.getElementById("torreBsc").selectedIndex].text);
					// formData.append("nivelBscTxt", document.getElementById("nivelBsc").options[document.getElementById("nivelBsc").selectedIndex].text);
					// formData.append("apartamentoBscTxt", document.getElementById("apartamentoBsc").options[document.getElementById("apartamentoBsc").selectedIndex].text);
					$.ajax({
						url: "./cliente.php?get_concidencia_cliente_fha=true",
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
								output += '<tr onCLick=""><td>'+e.codigo+'</td><td>'+e.client_name+' '+check+'</td><td>'+e.apartamentoEnganche+' </td><td><button onclick="buscarClienteUnico(\''+e.id+'\',\'\',\'\')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="abrir cliente" ></td></tr>';
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
				function guardarInfoCliente(){
					//console.log("funcion guardar cliente");
					var error = 0;					
					if(error==0){
						var formDataInfoCliente = new FormData();
						formDataInfoCliente.append("idEnganche",$("#idEnganche").val() );
						formDataInfoCliente.append("resguardo", $("#resguardo").val());
						formDataInfoCliente.append("valor_resguardo", $("#valor_resguardo").val());
						formDataInfoCliente.append("fecha_emision", $("#fecha_emision").val());
						formDataInfoCliente.append("fecha_caducidad", $("#fecha_caducidad").val());
						formDataInfoCliente.append("idCliente", $("#idCliente").val());
						var id_cliente = $("#idCliente").val();
						
						$.ajax({
							url: "./cliente.php?agregar_editar_cliente_fha=true",
							type: "post",
							dataType: "json",
							data: formDataInfoCliente,
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
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarCliente();buscarClienteUnico('+id_cliente+',\'\',\'\');">Aceptar</div>');
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
				function calculoCuota(){
					var valor = $("#valor_resguardo").val();
					valor = valor.replace(/,/g, "");
					var plazo = $("#plazo_credito").val();
					var interes = $("#tasa_interes").val();
					console.log(valor+'-'+plazo+'-'+interes)
					var im = interes/ 12 / 100;
					var im2 = Math.pow(	(1 + parseFloat(im)	), - (plazo ) );
					var cuota = (valor * parseFloat(im)) / (1- parseFloat(im2) ) ;
					cuota = cuota.toFixed(2)
					if(isNaN(cuota)){
						cuota = 0;
					}
					cuota = new Intl.NumberFormat('en-CA',{ minimumFractionDigits: 2,}).format(cuota);
					$("#cuota").val(cuota);

				}
				function guardarInfobanco (){
					//console.log("funcion guardar cliente");
					var error = 0;					
					if(error==0){
						var formDataInfoCliente = new FormData();
						formDataInfoCliente.append("idEnganche",$("#idEnganche").val() );
						formDataInfoCliente.append("banco", $("#banco").val());
						formDataInfoCliente.append("plazo_credito", $("#plazo_credito").val());
						formDataInfoCliente.append("fecha_resolucion", $("#fecha_resolucion").val());
						formDataInfoCliente.append("no_resolucion", $("#no_resolucion").val());
						var id_cliente = $("#idCliente").val();
						
						$.ajax({
							url: "./cliente.php?agregar_editar_cliente_fha_banco=true",
							type: "post",
							dataType: "json",
							data: formDataInfoCliente,
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
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarCliente();buscarClienteUnico('+id_cliente+',\'\',\'\');">Aceptar</div>');
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
				function guardarInfoInmueble(){
					//console.log("funcion guardar cliente");
					var error = 0;					
					if(error==0){
						
						var formDataInfoCliente = new FormData(document.getElementById("formInmueble"));
						formDataInfoCliente.append("idEnganche",$("#idEnganche").val());
						var id_cliente = $("#idCliente").val();				
						$.ajax({
							url: "./cliente.php?agregar_editar_cliente_fha_inmueble=true",
							type: "post",
							dataType: "json",
							data: formDataInfoCliente,
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
									$("#body_confirm").html(response.mss + '<br><br><div style="text-align:center"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close" onclick="$(\'#modal_confirm\').modal(\'hide\');buscarCliente();buscarClienteUnico('+id_cliente+',\'\',\'\');">Aceptar</div>');
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
					document.getElementById("formInmueble").reset();
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
								getBancos('banco',0);
								$("#idCliente").val(e.idCliente);
								$("#idOcaCliente").val(e.idCliente);
								$("#idEnganche").val(e.idEnganche);
								$("#deudor").val(e.client_name);
								$("#proyecto").val(e.proyecto);
								$("#apartamento").val(e.apartamento);
								$("#codeudor").val(e.client_name_codeudor);
								$("#resguardo").val(e.resguardoAsegurabilidad);
								$("#valor_resguardo").val(e.valorResguardo);
								$("#monto_asegura").val(e.valorResguardo);
								$("#fecha_emision").val(e.fechaEmision);
								$("#fecha_caducidad").val(e.fechaCaducidad);
								$("#porcion_factura").val(e.porcentajeFacturacion);
								$("#porcion_accion").val((100 - e.porcentajeFacturacion).toFixed(2));;
								iformacionInmueble(e.idEnganche);
								setTimeout(function(){
									//iformacionInmueble(e.idEnganche);
									iformacionBanco(e.idEnganche);
									informacionVenta(e.idEnganche);
								}, 2000);	

								
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
				function iformacionInmueble(idEnganche){
					//console.log("funcion buscar cliente unico");
					//var formData = new FormData(document.getElementById("frmBuscarCliente"));
					var formData = new FormData;
					formData.append("idEnganche", idEnganche);
					$.ajax({
						url: "./cliente.php?get_informacion_inmueble=true",
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
							
							for(var i = 0; i <=5; i++){
								$("#tipo_"+i).val('');
								$("#identificacion_"+i).val('');
								$("#finca_"+i).val('');
								$("#folio_"+i).val('');
								$("#libro_"+i).val('');
								$("#valor_"+i).val('');
								$("#direccion_"+i).val('');
								$("#area_"+i).val('');
							}

							$.each(response.info,function(i,e) {

								$("#tipo_"+e.noInmueble).val(e.tipo);
								$("#identificacion_"+e.noInmueble).val(e.identificacion);
								$("#finca_"+e.noInmueble).val(e.finca);
								$("#folio_"+e.noInmueble).val(e.folio);
								$("#libro_"+e.noInmueble).val(e.libro);
								$("#valor_"+e.noInmueble).val(e.valor);
								$("#direccion_"+e.noInmueble).val(e.direccion);
								$("#area_"+e.noInmueble).val(e.area_mts);
							});
							//document.getElementById("btnEnganche").disabled = false;
							setTimeout(function(){
								$("#modalAgregarCliente").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							}, 2000);		
							
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});

				}
				function iformacionBanco(idEnganche){
					//console.log("funcion buscar cliente unico");
					//var formData = new FormData(document.getElementById("frmBuscarCliente"));
					var formData = new FormData;
					formData.append("idEnganche", idEnganche);
					$.ajax({
						url: "./cliente.php?get_informacion_banco_general=true",
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
							$("#fecha_resolucion").val('');
							$("#plazo_credito").val(0);
							$("#no_resolucion").val('');
							$("#tasa_interes").val(0);

							$.each(response.info,function(i,e) {
								getBancos('banco',e.banco);
								tasaInteres(e.banco);
								$("#fecha_resolucion").val(e.fechaResolucion);
								$("#plazo_credito").val(e.plazo);
								$("#no_resolucion").val(e.noResolucion);

							});
							setTimeout(function(){
								calculoCuota()
							}, 2000);					
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
					//getDepartamentos(1,'depto',0);
					//getTipoComision('tipoComision',0);
					//getMunicipios(0,'municipio',0);
					//getNacionalidad('nacionalidadCl',0)
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
			function informacionVenta(idEnganche){
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
							var montoEngancheTotal=0;
							var precioVentaSinImpuesto = parseFloat(response.precioComision);
							$.each(response.detallePagos,function(i,e) {
								montoEngancheTotal= parseFloat(e.montoEnganche);
							});							
							var pagoFinal = parseFloat(response.contracargo) + parseFloat(response.bodega) + parseFloat(response.parqueo) + parseFloat(response.precio) - parseFloat(response.descuento) - parseFloat(response.enganchePorcMonto) ;
							var totalApartamento = parseFloat(pagoFinal) + parseFloat(montoEngancheTotal) + parseFloat(response.reserva) + parseFloat(response.promesa);
							pagoFinal = parseFloat(pagoFinal) + parseFloat(response.contracargoEnganche); 
							$("#precio_venta").val(totalApartamento);
							var porcionFacturar_porc = $("#porcion_factura").val();
							var porcion_accion_porc = $("#porcion_accion").val();
							var porcionFacturar = ((parseFloat(porcionFacturar_porc)/100)* totalApartamento);
							$("#porcion_factura_monto").val(porcionFacturar.toFixed(2));
							var porcionAccion = ((parseFloat(porcion_accion_porc)/100)* totalApartamento);
							$("#porcion_accion_monto").val(porcionAccion.toFixed(2));
							var montoAsegura = parseFloat($("#monto_asegura").val());
							var primaPrimer = ((1/100)*montoAsegura);
							var primaDesgravamen = ((0.26/100)*montoAsegura);
							var derechoInspeccion = ((0.15/100)*montoAsegura);
							var derechoSolicitud = ((0.35/100)*montoAsegura);
							$("#prima_primer").val(primaPrimer.toFixed(2));
							$("#prima_desgravamen").val(primaDesgravamen.toFixed(2));
							$("#derecho_inspeccion").val(derechoInspeccion.toFixed(2));
							$("#derecho_solicitud").val(derechoSolicitud.toFixed(2));
							var totalGastosFha = primaPrimer + primaDesgravamen + derechoInspeccion + derechoSolicitud;
							$("#total_gastos").val(totalGastosFha.toFixed(2));
							var ivaMonto = (porcionFacturar/1.12);
							var iusiMonto = ((ivaMonto*(0.90/100))/12)*4;
							ivaMonto = porcionFacturar - ivaMonto;
							var timbresMonto = ((3/100) * porcionAccion);
							$("#iva_monto").val(ivaMonto.toFixed(2));
							$("#iusi_monto").val(iusiMonto.toFixed(2));
							$("#timbres_monto").val(timbresMonto.toFixed(2));
							var totalImpuestos = timbresMonto + ivaMonto;
							$("#total_impuestos").val(totalImpuestos.toFixed(2));
							var validacion = '';
							var validacionMonto = porcionAccion + porcionFacturar;
							if(validacionMonto.toFixed(2) == totalApartamento.toFixed(2)){
								var validacion = 'CORRECTO';
							}else{
								var validacion = 'INCORRECTO';
							}
							$("#validacion_monto").val(validacion);





						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
			</script>
    </body>
</html>

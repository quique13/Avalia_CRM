<?php
session_name("inmobiliaria");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>"; 
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
                							<label class="usersearchitittle"><img class="usersearchicon" src=""> Proyectos</label>
											<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="divAlertPendiente" name="divAlertPendiente">
                								
											</div>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
                                        <div class="row">
                                            <div class="col-md-12" id="">
                                                <div class="row">	
                                                    <div class="col-md-12 "> 
                                                        <div class="table-responsive">
                                                            <div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
                                                                <button onclick="agregarProyecto()" class="btn btn-xs" type="button"><img class="addclient" src="../img/more.gif" alt="agregar cliente" ></button>
                                                                <span class="addclienttext" >Agregar Proyecto</span>
                                                            </div>
                                                            <table width="100%" id="tableUsuarios" class="table table-sm table-hover"  style="width:100%">
                                                                <thead>
                                                                    <tr>
																		<th>NO.</th>
																		<th>PROYECTO</th>
																		<th>TARIFA</th>
																		<th>PARQUEO EXTRA</th>
																		<th>PARQUEOS DISPONIBLES</th>
																		<th>MONEDA</th>
																		<th>% ENGANCHE</th>
																		<th>BODEGAS DISP.</th>
																		<th>IUSI</th>
																		<th>SEGURO</th>
																		<th>$ FACTURACIÓN</th>
																		<th>MONTO RESERVA</th>
																		<th>OPCIONES</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
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
					<div class="modal fade" id="modalAgregarProyecto">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Agregar Proyecto</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarProyecto" style="padding:5px 15px;" >
									<div class="secinfo" >
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarProyecto" name="frmAgregarProyecto" method="POST">
											<div class="row" >
												<input type="hidden" id="idProyecto" name="idProyecto">	
												<div id="divAlertProyecto" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												</div>													
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
                                                        <div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Proyecto:</label>
															<input  type="text" id="proyecto" name="proyecto" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Tasa(%):</label>
															<input  type="text" id="tarifa" name="tarifa" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Parqueo Extra:</label>
															<input type="text" id="parqueoExtra" name="parqueoExtra" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Parqueos Disponible:</label>
															<input type="text" id="parqueoDisponible" name="parqueoDisponible" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Tipo de cambio:</label>
															<input type="number" id="cambioDolar" name="cambioDolar" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">% Enganche:</label>
															<input type="text" id="porcentajeEnganche" name="porcentajeEnganche" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Bodegas disponibles:</label>
															<input type="number" id="deposito" name="deposito" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Tasa iusi:</label>
															<input type="number" id="iusi" name="iusi" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Tasa Seguro:</label>
															<input type="number" id="seguro" name="seguro" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">$ Facturación:</label>
															<input type="number" id="porcentajeFacturacion" name="porcentajeFacturacion" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Monto Reserva:</label>
															<input type="number" id="montoReserva" name="montoReserva" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Fecha Proyecto:</label>
															<input type="date" id="fechaProyecto" name="fechaProyecto" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Cocina Tipo A:</label>
															<input type="number" id="cocinaTipoA" name="cocinaTipoA" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Cocina Tipo B:</label>
															<input type="number" id="cocinaTipoB" name="cocinaTipoB" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Parqueo Extra Moto:</label>
															<input type="number" id="parqueoExtraMoto" name="parqueoExtraMoto" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Estado:</label>
															<select id="estado" name="estado" class="form-control" >
																<option value=0 selected>Activo</option>
																<option value=1>Inactivo</option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
													<button onclick="guardarProyecto()" class="guardar" type="button">Guardar</button>
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
            proyectosList();
                function agregarProyecto(){
					document.getElementById("frmAgregarProyecto").reset();
					$("#modalAgregarProyecto").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					console.log("funcion ingresar proyecto");
					$("#idProyecto").val(0);		
				}
                function guardarProyecto(){
					console.log("funcion guardar usuario");
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					if($("#proyecto").val()==''){
						error++;
						msjError =msjError+ '*Proyecto <br>'
					}
					if($("#tarifa").val()==''){
						error++;
						msjError =msjError+ '*Tarifa <br>'
					}
					if($("#parqueoExtra").val()==''){
						error++;
						msjError =msjError+ '*Parqueo Extra <br>'
					}
					if($("#parqueoDisponible").val()==''){
						error++;
						msjError =msjError+ '*Parqueo Disponible <br>'
					}
					if($("#cambioDolar").val()==''){
						error++;
						msjError =msjError+ '*Cambio Dolar <br>'
					}
					if($("#porcentajeEnganche").val()==''){
						error++;
						msjError =msjError+ '*porcentajeEnganche <br>'
					}
					if($("#deposito").val()==''){
						error++;
						msjError =msjError+ '*deposito <br>'
					}
					if($("#cambiiusioDolar").val()==''){
						error++;
						msjError =msjError+ '*iusi <br>'
					}
					if($("#seguro").val()==''){
						error++;
						msjError =msjError+ '*seguro <br>'
					}
					if($("#porcentajeFacturacion").val()==''){
						error++;
						msjError =msjError+ '*porcentajeFacturacion <br>'
					}
					if($("#montoReserva").val()==''){
						error++;
						msjError =msjError+ '*montoReserva <br>'
					}
					if($("#fechaProyecto").val()==''){
						error++;
						msjError =msjError+ '*fechaProyecto <br>'
					}
					if(error==0){
						var formDataProyecto = new FormData(document.getElementById("frmAgregarProyecto"));
						$.ajax({
							url: "./proyecto.php?agregar_editar_proyecto=true",
							type: "post",
							dataType: "json",
							data: formDataProyecto,
							cache: false,
							contentType: false,
							processData: false,
							beforeSend:function (){
								$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
							},
							success:function (response){
								$("#divAlertProyecto").html('<div class="alert alert-success">'+response.mss+'</div>');
								setTimeout(function(){
									$("#modalAgregarProyecto").modal("hide");
									$("#divAlertProyecto").html('');
								},1000)
								proyectosList();						
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarProyecto').animate({scrollTop:0}, 'fast');
						$("#divAlertProyecto").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertProyecto").html('');
							},5000)
					}
					
				}
                function proyectosList(){
					console.log("funcion lista_usuarios");
					var formData = new FormData(document.getElementById("frmAgregarProyecto"));
					$.ajax({
						url: "./proyecto.php?get_lista_proyectos=true",
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
                            var count =0;
							$.each(response.listado_proyectos,function(i,e) {
                                count++;
								if(e.creado=='si'){
									var check='<i class="fa fa-check-square-o"></i>';
								}else
								{
									var check="";
								}
								output += '<tr onCLick=""><td>'+count+'</td><td>'+e.proyecto+'</td><td>'+e.rate+'</td><td>'+ e.parqueoExtra +'</td><td>'+ e.parqueosDisponibles +'</td><td>'+ e.cambioDolar +'</td><td>'+ e.porcentajeEnganche +'</td><td>'+ e.deposito +'</td><td>'+ e.iusi +'</td><td>'+ e.seguro +'</td><td>'+ e.porcentajeFacturacion +'</td><td>'+ e.montoReserva +'</td><td><button onclick="editarProyecto('+e.idGlobal+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="Editar Proyecto" ></button></td></tr>';
							});
							//console.log(output);
							var tb = document.getElementById('tableUsuarios');
							while(tb.rows.length > 1) {
								tb.deleteRow(1);
							}
							$('#tableUsuarios').append(output);
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function editarProyecto(id){
					$("#modalAgregarProyecto").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					$("#idProyecto").val(0);	
					var formData = new FormData;
					formData.append("idProyecto", id);
					$.ajax({
						url: "./proyecto.php?get_proyecto=true",
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
							$.each(response.proyecto,function(i,e) {
								console.log(e.usuario);
								//Info Apartamento, Vendedor y CLiente
								$("#proyecto").val(e.proyecto);
								$("#tarifa").val(e.rate);
								$("#parqueoExtra").val(e.parqueoExtra);
								$("#parqueoDisponible").val(e.parqueosDisponibles);
								$("#cambioDolar").val(e.cambioDolar);
								$("#porcentajeEnganche").val(e.porcentajeEnganche);
								$("#deposito").val(e.deposito);
								$("#iusi").val(e.iusi);
								$("#seguro").val(e.seguro);
								$("#porcentajeFacturacion").val(e.porcentajeFacturacion);
								$("#montoReserva").val(e.montoReserva);
								$("#fechaProyecto").val(e.fechaProyecto);
								$("#cocinaTipoA").val(e.cocinaTipoA);
								$("#cocinaTipoB").val(e.cocinaTipoB);
								$("#parqueoExtraMoto").val(e.parqueoExtraMoto);
								$("#estado").val(e.suspendido);
								$("#idProyecto").val(e.idGlobal);

							});							
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
			</script>
    </body>
</html>

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
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/apartment_info.png"> Proceso FHA</label>
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
					<div class="modal fade" id="modalProceso">
						<div class="modal-dialog mw-40">
							<div class="modal-content" >
								<div class="modal-header">
									<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Estados en proceso de FHA</h5>
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
															<label class="nodpitext">Estado Proceso FHA:</label>
														</div>
														<div class="col-lg-6 col-md-6 col-xs-10" style="margin-bottom:10px;">
														<input type="hidden" id="idEnganche" name="idEnganche">
														<input type="hidden" id="idCliente" name="idCliente">
														<select class="form-control" name="procesoFha" id="procesoFha" >
																<option value="Pendiente" >Pendiente</optinon>
																<option value="En Proceso" >En Proceso</optinon>
																<option value="Credito aprobado" >Crédito aprobado</optinon>
																<option value="Suspendido" >Suspendido</optinon>
																<option value="Tecnica" >Técnica</optinon>
																<option value="Resguardo" >Resguardo</optinon>
															</select>
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
					url: "./arrayDisponibilidadFha.php?get_tabla_disponibilidad=true",
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
								var parqueo_externo_total = 0;
								$.each(t.niveles,function(i,n) {
									count++;
									outputN += '<tr >';
									outputN += '<td style="width:20%;">Nivel '+n.name+'</td>';
									$.each(n.apartamentos,function(i,a) {
										var color = '#929292';
										if(a.idEnganche!=null){
											color = a.color;
											
											var cursor = "cursor:pointer";
											
											if(id_perfil!=1 && id_perfil!=4){
												var onClick='abrirModal('+a.idCliente+','+a.idEnganche+',\''+a.procesoFha+'\',\''+a.color+'\');';
											}else {
												var onClick='';
											}
										}
										outputN += '<td style= "'+cursor+'" onclick="'+onClick+'"  bgcolor= "'+color+'" >'+a.name+'</td>';
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
			
			function guardarParqueoExterno(){
				var error = 0;
				var msjError = 'Campos Obligatorios: <br>';
				if($("#procesoFha").val()==''){
					error++;
					msjError =msjError+ '*Elegir estado <br>'
				}
				if(error==0){
					var formData = new FormData(document.getElementById("frmParqueoExterno"));
					$.ajax({
						url: "./cliente.php?agregar_editar_proceso_fha=true",
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
								$("#modalProceso").modal("hide");
								$("#divAlertParqueoExterno").html('');
							},1000)
							getTable(0);					
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
			function abrirModal(idCliente,idEnganche,procesoFha,color){
				if(color != '#929292'){
					$("#modalProceso input").val("");
					$("#idEnganche").val(idEnganche);
					$("#idCliente").val(idCliente);
					$("#procesoFha").val(procesoFha);
					$("#modalProceso").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
				}
			}
				
		</script>
    </body>
</html>

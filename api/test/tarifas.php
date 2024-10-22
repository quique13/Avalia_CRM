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
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/client_icon.png"> Tarifas</label>
											<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="divAlertPendiente" name="divAlertPendiente">
                								
											</div>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
                                        <div class="row">
                                            <div class="col-md-12" id="">
                                                <div class="row">
                                                <div class="col-md-1 col-md-offset-1"> 
                                                </div>   	
                                                    <div class="col-md-10 col-md-offset-2"> 
                                                        <div class="table-responsive">
                                                            <div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
                                                                <button onclick="agregarTarifa()" class="btn btn-xs" type="button"><img class="addclient" src="../img/more.gif" alt="agregar Banco" ></button>
                                                                <span class="addclienttext" >Agregar Tarifa</span>
                                                            </div>
                                                            <table width="100%" id="tableUsuarios" class="table table-sm table-hover"  style="width:100%">
                                                                <thead>
                                                                    <tr >
                                                                        <th>NO.</th>
																		<th>ESTADO</th>
                                                                        <th>TARIFA 1 (0-4 AÑOS)</th>
																		<th>TARIFA 2 (4-7 AÑOS)</th>
																		<th>TARIFA 3 (7 O MÁS AÑOS)</th>
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
					<div class="modal fade" id="modalAgregarTarifa">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Agregar Tarifa</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarTarifa" style="padding:5px 15px;" >
									<div class="secinfo" >
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarTarifa" name="frmAgregarTarifa" method="POST">
											<div class="row" >
												<input type="hidden" id="idTarifa" name="idTarifa">	
												<div id="divAlertTarifa" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												</div>													
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Tarifa 1 ( 0 - 4 años):</label>
															</div>
															<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																<input  type="text" id="tarifa_1" name="tarifa_1" class="form-control">
															</div>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Tarifa 2 ( 4 - 7 años):</label>
															</div>
															<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																<input  type="text" id="tarifa_2" name="tarifa_2" class="form-control">
															</div>	
														</div>
														<div class="col-lg- col-md-4 col-xs-10" style="margin-bottom:10px;">
															<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
																<label class="nodpitext">Tarifa 3 ( 7 años en adelante):</label>
															</div>
															<div class="col-lg-8 col-md-8 col-xs-10" style="margin-bottom:10px;">
																<input  type="text" id="tarifa_3" name="tarifa_3" class="form-control">
															</div>	
														</div>
													</div>
													<script type="text/javascript">
														$("#tarifa_1").number( true, 2 );
														$("#tarifa_2").number( true, 2 );
														$("#tarifa_3").number( true, 2 );
													</script>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
													<button onclick="guardarTarifa()" class="guardar" type="button">Guardar</button>
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
            tarifaList();
			function tarifaList(){
					console.log("funcion lista_usuarios");
                    var formData = new FormData(document.getElementById("frmAgregarTarifa"));
					$.ajax({
						url: "./tarifa.php?get_lista_tarifas=true",
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
							$.each(response.listado_tarifas,function(i,e) {
                                count++;
								if(e.creado=='si'){
									var check='<i class="fa fa-check-square-o"></i>';
								}else
								{
									var check="";
								}
								output += '<tr onCLick=""><td>'+count+'</td><td>'+e.estado+'</td><td>'+e.tarifa_1+'</td><td>'+e.tarifa_2+'</td><td>'+e.tarifa_3+'</td><td><button onclick="editarTarifa('+e.idTarifa+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="Editar Tarifa" ></button></td></tr>';
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
			function agregarTarifa(){
				document.getElementById("frmAgregarTarifa").reset();
				$("#modalAgregarTarifa").modal({
							backdrop: 'static',
							keyboard: false,
							show: true
						});
				$("#idBanco").val(0);		
			}
                function guardarTarifa(){
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					if($("#tarifa_1").val()==''){
						error++;
						msjError =msjError+ '*Tarifa 1 <br>'
					}
					if($("#tarifa_2").val()==''){
						error++;
						msjError =msjError+ '*Tarifa 2 <br>'
					}
					if($("#tarifa_3").val()==''){
						error++;
						msjError =msjError+ '*Tarifa 3 <br>'
					}
					if(error==0){
						var formData = new FormData(document.getElementById("frmAgregarTarifa"));
                        formData.append("pass", $("#idTarifa").val());
						$.ajax({
							url: "./tarifa.php?agregar_editar_tarifa=true",
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
								$("#divAlertUsuario").html('<div class="alert alert-success">'+response.mss+'</div>');
								setTimeout(function(){
									$("#modalAgregarTarifa").modal("hide");
									$("#divAlertTarifa").html('');
								},1000)
								tarifaList();						
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarTarifa').animate({scrollTop:0}, 'fast');
						$("#divAlertTarifa").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertTarifa").html('');
							},5000)
					}
					
				}
                
				function editarTarifa(id){
					$("#modalAgregarTarifa").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					$("#idTarifa").val(0);	
					var formData = new FormData;
					formData.append("idTarifa", id);
					$.ajax({
						url: "./tarifa.php?get_tarifa=true",
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
							$.each(response.tarifa,function(i,e) {
								console.log(e.idTarifa);
								//Info Apartamento, Vendedor y CLiente
								$("#estado").val(e.suspendido);
								$("#idTarifa").val(e.idTarifa);
								$("#tarifa_1").val(e.tarifa_1);
								$("#tarifa_2").val(e.tarifa_2);
								$("#tarifa_3").val(e.tarifa_3);

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

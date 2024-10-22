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
<?php
include "menu.php";
?>
    <body>
		<script src="../dist/jquery/dist/jquery.js"></script>
		<script src="../dist/jquery/dist/jquery.min.js"></script>
		<script src="../dist/jquery/dist/jquery.min.map"></script>
		<script src="../js/jquery.number.js "></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

		<div class="wrapper">	
			<div class="content-wrapper">
				<div class="">
					<section class="content">
						<div class="row">
							<div class="col-md-12">
								<div class="box box-warning">
									<div  class="box-header with-border">
										<div class="col-lg-12 col-md-12" style="text-align:center;margin-bottom:10px;margin-top:10px;" id="headerCatalogo">
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/client_icon.png"> Perfil de Comisión</label>
											<div class="col-lg-12 col-md-12" style="text-align:center;;margin-bottom:10px;margin-top:10px;" id="divAlertPendiente" name="divAlertPendiente">
                								
											</div>
										</div>
									</div>
									<div class="box-body" id="listCatalogo">
                                        <div class="row">
                                            <div class="col-md-12" id="">
                                                <div class="row">
                                                <div class="col-md-2 col-md-offset-4"> 
                                                </div>   	
                                                    <div class="col-md-8 col-md-offset-2"> 
                                                        <div class="table-responsive">
                                                            <div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
                                                                <button onclick="agregarPerfilComision()" class="btn btn-xs" type="button"><img class="addclient" src="../img/more.gif" alt="agregar Torre" ></button>
                                                                <span class="addclienttext" >Agregar Perfil Comisión</span>
                                                            </div>
                                                            <table width="100%" id="tableUsuarios" class="table table-sm table-hover"  style="width:100%">
                                                                <thead>
                                                                    <tr >
                                                                        <th>NO.</th>
																		<th>ESTADO</th>
                                                                        <th>PROYECTO</th>
                                                                        <th>TIPO</th>
																		<th>PERFIL</th>
																		<th>PORCENTAJE</th>
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
					<div class="modal fade" id="modalAgregarPerfilComision">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Agregar Perfil Comisión</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarPerfilComision" style="padding:5px 15px;" >
									<div class="secinfo" >
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarPerfilComision" name="frmAgregarPerfilComision" method="POST">
											<div class="row" >
												<input type="hidden" id="idPerfilComision" name="idPerfilComision">	
												<div id="divAlertTorre" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												</div>													
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Proyecto:</label>
															<select id="proyecto" name="proyecto" class="form-control" onChange="getTipos(this.value,'tipo',0)">
																<option value=0 selected>Seleccionar Proyecto</option>
															</select>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Tipo:</label>
															<select id="tipo" name="tipo" class="form-control" >
																<option value=0 selected>Seleccionar Tipo</option>
															</select>
														</div>
                                                        <div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Perfil:</label>
															<input  type="text" id="perfil" name="perfil" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Porcentaje Perfil:</label>
															<input  type="number" id="porcentaje_perfil" name="porcentaje_perfil" class="form-control">
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
													<button onclick="guardarPerfilComision()" class="guardar" type="button">Guardar</button>
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
			getProyectos('proyecto',0);
            perfilComisionList();
			getProyectos
			function getProyectos(input,valueInput){
					console.log("funcion buscar proyectos");
					var formData = new FormData;
					$.ajax({
						url: "./perfilComisionFn.php?get_proyecto=true",
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
							$.each(response.proyectos,function(i,e) {
								if(valueInput==e.idGlobal){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.idGlobal+'">'+e.proyecto+'</option>';
							});
							console.log(output);
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
                function agregarPerfilComision(){
					getProyectos('proyecto',0);
					document.getElementById("frmAgregarPerfilComision").reset();
					$("#modalAgregarPerfilComision").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					$("#idPerfilComision").val(0);		
				}
                function guardarPerfilComision(){
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					if($("#proyecto").val()==''){
						error++;
						msjError =msjError+ '*proyecto <br>'
					}
					if($("#tipo").val()==''){
						error++;
						msjError =msjError+ '*Tipo(Descripción) <br>'
					}
					if($("#perfil").val()==''){
						error++;
						msjError =msjError+ '*Perfil <br>'
					}
					if(error==0){
						var formData = new FormData(document.getElementById("frmAgregarPerfilComision"));
                        formData.append("pass", $("#idTorre").val());
						$.ajax({
							url: "./perfilComisionFn.php?agregar_editar_perfil_comision=true",
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
									$("#modalAgregarPerfilComision").modal("hide");
									$("#divAlertTorre").html('');
								},1000)
								perfilComisionList();						
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarTorre').animate({scrollTop:0}, 'fast');
						$("#divAlertTorre").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertTorre").html('');
							},5000)
					}
					
				}
                function perfilComisionList(){
                    var formData = new FormData(document.getElementById("frmAgregarPerfilComision"));
					$.ajax({
						url: "./perfilComisionFn.php?get_lista_perfil_comision=true",
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
							$.each(response.listado_perfil_comision,function(i,e) {
                                count++;
								if(e.creado=='si'){
									var check='<i class="fa fa-check-square-o"></i>';
								}else
								{
									var check="";
								}
								output += '<tr onCLick=""><td>'+count+'</td><td>'+e.estado+'</td><td>'+e.proyecto+'</td><td>'+ e.tipo +'</td><td>'+ e.descripcion +'</td><td>'+ e.porcentajeComision +'</td><td><button onclick="editarPerfilComision('+e.idPagaComision+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="Editar Usuario" ></button></td></tr>';
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
				function editarPerfilComision(id){
					$("#modalAgregarPerfilComision").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					$("#idPerfilComision").val(0);	
					var formData = new FormData;
					formData.append("idPerfilComision", id);
					$.ajax({
						url: "./perfilComisionFn.php?get_perfil_comision=true",
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
							$.each(response.perfilComision,function(i,e) {
								//Info Apartamento, Vendedor y CLiente
								$("#proyecto").val(e.proyecto);
								//$("#tipo").val(e.tipo);
								getTipos(e.proyecto,'tipo',e.tipo)
								$("#perfil").val(e.descripcion);
								$("#porcentaje_perfil").val(e.porcentajeComision);
								$("#estado").val(e.suspendido);
								$("#idPerfilComision").val(e.idPagaComision);

							});							
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function getTipos(proyecto,input,valueInput){
					var formData = new FormData;
					formData.append("proyecto", proyecto);
					$.ajax({
						url: "./perfilComisionFn.php?get_tipo_comision=true",
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
								if(valueInput==e.idTipoComision){
									select= 'selected="selected"';
								}else{
									select='';
								}
								output += ' <option '+select+' value="'+e.idTipoComision+'">'+e.descripcion+'</option>';
							});
							console.log(output);
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

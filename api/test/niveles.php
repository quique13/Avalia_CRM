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
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/client_icon.png"> Niveles</label>
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
                                                                <button onclick="agregarNivel()" class="btn btn-xs" type="button"><img class="addclient" src="../img/more.gif" alt="agregar Nivel" ></button>
                                                                <span class="addclienttext" >Agregar Nivel</span>
                                                            </div>
                                                            <table width="100%" id="tableUsuarios" class="table table-sm table-hover"  style="width:100%">
                                                                <thead>
                                                                    <tr >
                                                                        <th>NO.</th>
																		<th>ESTADO</th>
                                                                        <th>PROYECTO</th>
                                                                        <th>TORRE</th>
																		<th>NIVEL</th>
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
					<div class="modal fade" id="modalAgregarNivel">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Agregar Nivel</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarNivel" style="padding:5px 15px;" >
									<div class="secinfo" >
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarNivel" name="frmAgregarNivel" method="POST">
											<div class="row" >
												<input type="hidden" id="idNivel" name="idNivel">	
												<div id="divAlertNivel" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												</div>													
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<div class="row" >
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Proyecto:</label>
															<select id="proyecto" name="proyecto" class="form-control"  onChange="getTorres(this.value,'torre',0)">
																<option selected>Seleccionar Proyecto</option>
															</select>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Torre:</label>
															<select id="torre" name="torre" class="form-control" >
																<option value="">Seleccionar Torre</option>
															</select>
														</div>
                                                        <div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Nivel:</label>
															<input  type="number" id="nivel" name="nivel" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Fecha Entrega:</label>
															<input  type="date" id="fechaEntrega" name="fechaEntrega" class="form-control">
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
													<button onclick="guardarNivel()" class="guardar" type="button">Guardar</button>
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
            nivelesList();
			function getProyectos(input,valueInput){
					console.log("funcion buscar proyectos");
					var formData = new FormData;
					$.ajax({
						url: "./torre.php?get_proyecto=true",
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
				function getTorres(proyecto,input,valueInput){
					console.log(proyecto+' '+input+' '+valueInput+'funcion buscar proyectos');
					var formData = new FormData;
					formData.append("proyecto", proyecto);
					$.ajax({
						url: "./nivel.php?get_torres=true",
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
								output += ' <option '+select+' value="'+e.idTorre+'">'+e.noTorre+'</option>';
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
                function agregarNivel(){
					getProyectos('proyecto',0);
					document.getElementById("frmAgregarNivel").reset();
					$("#modalAgregarNivel").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					console.log("funcion buscar usuario");
					$("#idNivel").val(0);		
				}
                function guardarNivel(){
					console.log("funcion guardar torre");
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					if($("#proyecto").val()==''){
						error++;
						msjError =msjError+ '*proyecto <br>'
					}
					if($("#torre").val()==''){
						error++;
						msjError =msjError+ '*Torre <br>'
					}
					if($("#nivel").val()==''){
						error++;
						msjError =msjError+ '*Nivel <br>'
					}
					if($("#fechaEntrega").val()==''){
						error++;
						msjError =msjError+ '*Fecha entrega <br>'
					}
					if(error==0){
						var formData = new FormData(document.getElementById("frmAgregarNivel"));
                        formData.append("pass", $("#idNivel").val());
						$.ajax({
							url: "./nivel.php?agregar_editar_nivel=true",
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
								$("#divAlertNivel").html('<div class="alert alert-success">'+response.mss+'</div>');
								setTimeout(function(){
									$("#modalAgregarNivel").modal("hide");
									$("#divAlertNivel").html('');
								},1000)
								nivelesList();						
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarNivel').animate({scrollTop:0}, 'fast');
						$("#divAlertNivel").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertNivel").html('');
							},5000)
					}
					
				}
                function nivelesList(){
					console.log("funcion lista_usuarios");
                    var formData = new FormData(document.getElementById("frmAgregarNivel"));
					$.ajax({
						url: "./nivel.php?get_lista_niveles=true",
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
							$.each(response.listado_niveles,function(i,e) {
                                count++;
								if(e.creado=='si'){
									var check='<i class="fa fa-check-square-o"></i>';
								}else
								{
									var check="";
								}
								output += '<tr onCLick=""><td>'+count+'</td><td>'+e.estado+'</td><td>'+e.proyecto+'</td><td>'+ e.noTorre +'</td> <td>'+ e.noNivel +'</td><td><button onclick="editarNivel('+e.idNivel+')" class="btn btn-xs" type="button"><img class="" src="../img/edit_button.png" alt="Editar Usuario" ></button></td></tr>';
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
				function editarNivel(id){
					$("#modalAgregarNivel").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					$("#idNivel").val(0);	
					var formData = new FormData;
					formData.append("idNivel", id);
					$.ajax({
						url: "./nivel.php?get_nivel=true",
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
							$.each(response.nivel,function(i,e) {
								console.log(e.idNivel);
								//Info Apartamento, Vendedor y CLiente
								$("#proyecto").val(e.proyecto);
								//$("#torre").val(e.IdTorre);
								getTorres(e.proyecto,'torre',e.idTorre)
								$("#fechaEntrega").val(e.fechaEntrega);
								$("#estado").val(e.suspendido);
								$("#idNivel").val(e.idNivel);
								$("#nivel").val(e.noNivel);

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

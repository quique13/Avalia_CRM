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

		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.14/js/bootstrap-multiselect.min.js" integrity="sha512-ByDbyutg5bTK+6f4Ke7Fgcg2I2fMUnOdzwC+F/ECFpgde2o2QHRVTFa//oeAHrJuAGX61DPNeKA80KA6V+ca7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                							<label class="usersearchitittle"><img class="usersearchicon" src="../img/client_icon.png"> Usuarios</label>
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
                                                                <button onclick="agregarUsuario()" class="btn btn-xs" type="button"><img class="addclient" src="../img/more.gif" alt="agregar cliente" ></button>
                                                                <span class="addclienttext" >Agregar Usuario</span>
                                                            </div>
                                                            <table width="100%" id="tableUsuarios" class="table table-sm table-hover"  style="width:100%">
                                                                <thead>
                                                                    <tr >
                                                                        <th>NO.</th>
                                                                        <th>ESTADO</th>
                                                                        <th>NOMBRE</th>
                                                                        <th>USUARIO</th>
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
					<div class="modal fade" id="modalAgregarUsuario">
						<div class="modal-dialog mw-100 w-75 " style="height:80%; overflow-y: auto;overflow-x: hidden">
							<div class="modal-content" >
								<div class="modal-header">
                    				<h5 class="tittle" ><img  class="adduser" src="../img/add-friend 1.png" alt="Italian Trulli" > Agregar Usuario</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="bodyAgregarUsuario" style="padding:5px 15px;" >
									<div class="secinfo" >
										<form autocomplete="off"  enctype="multipart/form-data"  id="frmAgregarUsuario" name="frmAgregarUsuario" method="POST">
											<div class="row" >
												<input type="hidden" id="idUsuario" name="idUsuario">
                                                <input type="hidden" id="pass" name="pass" value="avalia<?php echo date("Y") ?>">		
												<div id="divAlertUsuario" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
												</div>													
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;">
													<label class="nodpitext">Usuario:</label>
													<div class="row" >
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input type="text" id="primerNombre" name="primerNombre" placeHolder="Primer Nombre" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input type="text" id="segundoNombre" name="segundoNombre" placeHolder="Segundo Nombre" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input type="text" id="primerApellido" name="primerApellido" placeHolder="Primer apellido" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input type="text" id="segundoApellido" name="segundoApellido" placeHolder="Segundo Apellido" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input type="text" id="tercerNombre" name="tercerNombre" placeHolder="Tercer Nombre" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<input type="text" id="apellidoCasada" name="apellidoCasada" placeHolder="Apellido Casada" class="form-control" >
														</div>
                                                        <div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Usuario:</label>
															<input  type="text" id="usuario" name="usuario" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Télefono Celular:</label>
															<input  type="text" id="telefono" name="telefono" class="form-control">
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Correo electrónico:</label>
															<input type="text" id="correo" name="correo" class="form-control" >
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Estado:</label>
															<select id="estado" name="estado" class="form-control" >
																<option value=0 selected>Activo</option>
																<option value=1>Inactivo</option>
															</select>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Perfil:</label>
															<select id="sel_usurio_perfil" name="sel_usurio_perfil" class="form-control" >
															</select>
														</div>
														<div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Proyecto:</label>
															<select id="sel_usurio_proyecto" name="sel_usurio_proyecto" class="form-control" >
															</select>
														</div>
														<!-- <div class="col-lg-4 col-md-4 col-xs-10" style="margin-bottom:10px;">
															<label class="nodpitext">Proyectos:</label>
															<select  id="sel_usurio_datos_globales" name="sel_usurio_datos_globales" class="form-control">
															</select>
														</div> -->
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px;text-align:center;">
													<button onclick="guardarUsuario()" class="guardar" type="button">Guardar</button>
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
			

				usuariosList();
				obtenerPerfiles();
				obtenerProyectos();
				// obtenerDatosGlobales();
				
                function agregarUsuario(){
					document.getElementById("frmAgregarUsuario").reset();
					$("#modalAgregarUsuario").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					console.log("funcion buscar usuario");
					$("#idUsuario").val(0);		
				}
                function guardarUsuario(){
					console.log("funcion guardar usuario");
					var error = 0;
					var msjError = 'Campos Obligatorios: <br>';
					if($("#primerNombre").val()==''){
						error++;
						msjError =msjError+ '*Primer Nombre <br>'
					}
					if($("#primerApellido").val()==''){
						error++;
						msjError =msjError+ '*Primer Apellido <br>'
					}
					// if($("#correo").val()==''){
					// 	var correo = $("#correo").val().trim();
					// 	error++;
					// 	msjError =msjError+ '*Correo eléctronico <br>'
					// }else{
					// 	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
					// 	if(!emailReg.test(correo)){
					// 		error++;
					// 		msjError =msjError+ '*Correo electrónico invalido <br>';
					// 		//return false;	
					// 	}
					// }
					if($("#telefono").val()==''){
						error++;
						msjError =msjError+ '*Télefono <br>'
					}

					if($("#sel_usurio_perfil option:selected").val() == '') {
						error++;
						msjError =msjError+ '*Perfil <br>'
					}
					if($("#sel_usurio_proyecto option:selected").val() == '') {
						error++;
						msjError =msjError+ '*Proyecto <br>'
					}

					// if($("#sel_usurio_datos_globales option:selected").val() == '') {
					// 	error++;
					// 	msjError =msjError+ '*Proyecto <br>'
					// }

					if(error==0){
						var formDataUsuario = new FormData(document.getElementById("frmAgregarUsuario"));
                        var hash = CryptoJS.MD5($("#pass").val());
				        formDataUsuario.append("password", hash);
                        formDataUsuario.append("pass", $("#pass").val());
						formDataUsuario.append("id_perfil", $("#sel_usurio_perfil option:selected").val());
						formDataUsuario.append("proyectos", $("#sel_usurio_proyecto option:selected").val());

						$.ajax({
							url: "./usuario.php?agregar_editar_usuario=true",
							type: "post",
							dataType: "json",
							data: formDataUsuario,
							cache: false,
							contentType: false,
							processData: false,
							beforeSend:function (){
								$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
							},
							success:function (response){
								$("#divAlertUsuario").html('<div class="alert alert-success">'+response.mss+'</div>');
								setTimeout(function(){
									$("#modalAgregarUsuario").modal("hide");
									$("#divAlertUsuario").html('');
								},1000)
								usuariosList();						
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}else{
						$('#bodyAgregarusuario').animate({scrollTop:0}, 'fast');
						$("#divAlertUsuario").html('<div class="alert alert-danger">'+msjError+'</div>');
							setTimeout(function(){
								$("#divAlertUsuario").html('');
							},5000)
					}
					
				}
                function usuariosList(){
					
                    var formData = new FormData(document.getElementById("frmAgregarUsuario"));
					$.ajax({
						url: "./usuario.php?get_lista_usuarios=true",
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
							$.each(response.listado_usuarios,function(i,e) {
                                count++;
								if(e.creado=='si'){
									var check='<i class="fa fa-check-square-o"></i>';
								}else
								{
									var check="";
								}
								if(e.estado =='Inactivo'){
									var color = "red";
								}else{
									var color = "black";
								}
								output += `	<tr>
												<td><font color = "${color}">${count}</font></td>
												<td><font color = "${color}">${e.estado}</font></td>
												<td><font color = "${color}">${e.nombre_completo}</font></td>
												<td><font color = "${color}">${e.usuario}</font></td>
												<td>
													<button title="Editar usuario" onclick="editarUsuario(${e.id_usuario})" class="btn btn-xs" type="button">
														<img class="" src="../img/edit_button.png" alt="Editar Usuario" >
													</button>													
													<button ${(e.password_default === "1" && e.estado === "Activo") ? "disabled" : "false"} onclick="restablecerContraseniaDefault(${e.id_usuario}, '${e.usuario}')" class="btn btn-xs" type="button" title="Restablecer contraseña por defecto">
														<img class="" src="../img/restablecer.png" height="15" alt="Editar Usuario" >
													</button>
												</td>
											</tr>`;
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

				function restablecerContraseniaDefault(id_usuario, usuario) {
					const pass = $("#pass").val();
					const hash = CryptoJS.MD5(pass);

					let formData = new FormData();
					formData.append("password", hash);
					formData.append("idUsuario", id_usuario);

					var opcion = confirm(`Al precionar "Aceptar" se restablecera la contraseña del usuario: ${usuario}, a una por defecto: ${pass}`);
					if (opcion == true) {
						$.ajax({
							url: "./usuario.php?restablecer_contrasenia=true",
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
								if (response.err) {
									$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
								} else {
									usuariosList();		
								}				
							},
							error:function (){
								$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
							}
						});
					}
				}

				function editarUsuario(id){
					$("#modalAgregarUsuario").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
					$("#idUsuario").val(0);	
					var formData = new FormData;
					formData.append("idUsuario", id);
					$.ajax({
						url: "./usuario.php?get_usuario=true",
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
							$.each(response.usuario,function(i,e) {
								console.log(e.usuario);
								//Info Apartamento, Vendedor y CLiente
								$("#primerNombre").val(e.primer_nombre);
								$("#segundoNombre").val(e.segundo_nombre);
								$("#primerApellido").val(e.primer_apellido);
								$("#segundoApellido").val(e.segundo_apellido);
								$("#apellidoCasada").val(e.apellido_casada);
								$("#tercerNombre").val(e.tercer_nombre);
								$("#usuario").val(e.usuario);
								$("#telefono").val(e.telefono);
								$("#correo").val(e.mail);
								$("#estado").val(e.suspendido);
								$("#idUsuario").val(e.id_usuario);
								$("#sel_usurio_perfil").val(e.id_perfil);
								$("#sel_usurio_proyecto").val(e.proyectos);
							});							
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}

				function obtenerPerfiles(){
					const option = document.getElementById("sel_usurio_perfil");
					$.ajax({
						url: "./usuario.php?get_perfiles=true",
						type: "get",
						dataType: "json",
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							let output;
							output += ' <option value="">Seleccione una opcion</option>';
							$.each(response.perfiles, function (i, e) {
								output += `<option value="${e.id}">${e.nombre}</option>`;
							});
							for (let i = option.options.length; i >= 0; i--) {
								option.remove(i);
							}
							$('#sel_usurio_perfil').append(output);		
							
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
				function obtenerProyectos(){
					const option = document.getElementById("sel_usurio_proyecto");
					$.ajax({
						url: "./usuario.php?get_proyectos=true",
						type: "get",
						dataType: "json",
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							let output;
							output += ' <option value="">Seleccione una opcion</option>';
							$.each(response.proyectos, function (i, e) {
								output += `<option value="${e.proyecto}">${e.todos}</option>`;
							});
							for (let i = option.options.length; i >= 0; i--) {
								option.remove(i);
							}
							$('#sel_usurio_proyecto').append(output);		
							
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}

				function obtenerDatosGlobales(){
					const option = document.getElementById("sel_usurio_datos_globales");
					$.ajax({
						url: "./usuario.php?get_datos_globales=true",
						type: "get",
						dataType: "json",
						cache: false,
						contentType: false,
						processData: false,
						beforeSend:function (){
							$("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
						},
						success:function (response){
							let output;
							output += ' <option value="">Seleccione una opcion</option>';
							$.each(response.datos_globales, function (i, e) {
								output += `<option value="${e.idGlobal}">${e.proyecto}</option>`;
							});
							for (let i = option.options.length; i >= 0; i--) {
								option.remove(i);
							}
							$('#sel_usurio_datos_globales').append(output);												
						},
						error:function (){
							$("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
						}
					});
				}
			</script>
    </body>
</html>

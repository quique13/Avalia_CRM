
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login</title>
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
		<link rel="stylesheet" href="../css/stylesIndex.css">
	</head>
	<body style="background-color:rgba(54, 80, 120, 0.5);">
		<script src="../dist/jquery/dist/jquery.js"></script>
		<script src="../dist/jquery/dist/jquery.min.js"></script>
		<script src="../dist/jquery/dist/jquery.min.map"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
		      			<h3 class="text-center mb-4">¡Bienvenido/a!</h3>
						<form action="javascript:;" method="post" name="form-login" id="form-login" class="login-form">
		      				<div class="form-group">
		      					<input type="text" class="form-control" placeholder="Usuario" name="user" id="user">
		      				</div>
	            			<div class="form-group d-flex">
	              				<input type="password" class="form-control" placeholder="Contraseña"  name="pass" id="pass">
	            			</div>
	            			<div class="form-group">
	            				<button type="submit" class="form-control btn btn-primary submit px-3" onclick="login()">Iniciar sesión</button>
	            			</div>
							<img width="100%" height="100%" src="../img/logo_avalia.png" alt="" />
							<div class="col-xs-12" id="loading">
							</div>
	            			<!-- <div class="form-group d-md-flex">
	            				<div class="w-50">
	            					<label class="text-center">Recordar contraseña
									  <input type="checkbox" checked>
									  <span class="checkmark"></span>
									</label>
								</div>
								<div class="text-center">
									<a href="#">¿Olvidaste la contraseña?</a>
								</div>
	            			</div> -->
	          			</form>
	        		</div>
				</div>
			</div>
		</div>
	</section>
	<script type="text/javascript">
		function login() {
			var error=0;
			var msjError ='';
				
			if($("#user").val()=='' || $("#pass").val()=='')
			{
				error++;
				msjError =msjError+' Usuario y/o ontraseña vacios';

			}
			if(error==0){
				var formData = new FormData;
				formData.append("usuario", $("#user").val());
				var hash = CryptoJS.MD5($("#pass").val());
				formData.append("password", hash);
				$.ajax({
					url: "./usuario.php?login=true",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$("#loading").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
					},
					success: function (response) {
						if (response.mss != '') {
							$("#loading").html('<div class="alert alert-danger"><b>Error</b> '+response.mss+'</div>');
							setTimeout(function () {
								$("#loading").html('');
							}, 4000);
						}
						else {
							location.href = "consulta.php";
						}
					},
					error: function () {
						$("#loading").html('<div class="alert alert-danger"><b>Error</b> Intente nuevamente</div>');
					}
				});
			}else{
				$("#loading").html('<div class="alert alert-danger">'+msjError+'</div>');
									setTimeout(function(){
										$("#loading").html('');
									},5000)
			}	
		}
		</script>
	</body>
</html>


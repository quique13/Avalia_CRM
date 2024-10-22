<!DOCTYPE html>
<html>
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
</head>
<body class="hold-transition login-page">
	<script src="../dist/jquery/dist/jquery.js"></script>
	<script src="../dist/jquery/dist/jquery.min.js"></script>
	<script src="../dist/jquery/dist/jquery.min.map"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<div class="login-box">
  <!-- /.login-logo -->
  <div class="login-box-body">
  	<div style="text-align:center;">
    	<img style="display:inline" class="img-responsive" width="100%" src="" alt="">
    </div>
    <br>
    <form action="javascript:;" method="post" name="form-login" id="form-login">
      <div class="form-group has-feedback">
        <input value="" autofocus type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input value="" type="password" class="form-control" name="password" id="password" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
      	<div class="col-xs-12" id="loading">
        </div>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-cafe btn-block btn-flat" style="background:#FDDD0E; color:#000;" onclick="login()">Aceptar</button>
        </div>
        <!-- <div class="col-xs-12">
          <button type="submit" class="btn btn-cafe btn-block btn-flat" style="background:#FDDD0E; color:#000;" onClick="recuperar_login()">Aceptar</button>
        </div>-->
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<script type="text/javascript">
function login() {
	var error=0;
	var msjError ='';
		
	if($("#usuario").val()=='' || $("#password").val()=='')
	{
		error++;
		msjError =msjError+' Usuario y/o ontraseña vacios';

	}
	if(error==0){
		var formData = new FormData;
		formData.append("usuario", $("#usuario").val());
		var hash = CryptoJS.MD5($("#password").val());
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
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
        <title>Tabla Adjunto</title>    
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="../../css/styles.css" rel="stylesheet">
		<link href="../../css/stylesEnganche.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Archivo&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.css">
</head>
    <body>
		<script src="../../dist/jquery/dist/jquery.js"></script>
		<script src="../../dist/jquery/dist/jquery.min.js"></script>
		<script src="../../dist/jquery/dist/jquery.min.map"></script>
		<script src="../../js/jquery.number.js "></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


        <div class="col-12 col-md-12" style="margin-bottom:10px;"  >
            <div class="row">
                <Label class="results">Nombre</label>
                <div class="table-responsive">
                    <table id="resultadoAdjuntos" class="table table-sm table-hover"  style="width:100%">
                        <?php 
                            include_once "../class/dbClassMysql.php";
                            
                            $conn = new dbClassMysql();
                            
                            $strQuery = "SELECT * 
                                        FROM  adjuntosCliente
                                        WHERE idCliente ={$_GET['idCliente']}
                                        ORDER by fechaCreacion DESC";

                            //echo $strQuery;
                            $qTmp = $conn ->db_query($strQuery);
                            while ($rTmp = $conn->db_fetch_object($qTmp)){
                                $nombreAdjunto = $rTmp->nombre;
                                $idAdjunto = $rTmp->id_adjuntosCliente;
                                $ruta = $rTmp->ruta;
                                echo '<tr><td style="width:90%;"><label class="nodpitext">' . $nombreAdjunto . '</label></td><td style="width:10%;"><button onclick="eliminarAdjuntos('. $idAdjunto .',\''. $ruta .'\')" class="btn btn-sm btn-danger"  type="button"><i class="fa fa-times"></i></button> </td></tr>';
                            } 
                        ?>
                    </table>
                </div>
            </div>	
        </div>
		<script type="text/javascript">
            function eliminarAdjuntos(id,ruta)
            {
                console.log("funcion eliminar adjunto");
                var formData = new FormData;
                formData.append("idAdjunto",id);
                formData.append("ruta",ruta);
                $.ajax({
                    url: "./cliente.php?deleteAdjunto=true",
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
                        //verAdjuntos();
                    },
                    error:function (){
                        $("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
                    }
                });
            }
            //function listadoAdjuntos(idCliente){}
        </script>										
    </body>
</html>

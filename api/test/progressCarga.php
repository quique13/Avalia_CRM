
<?php
function progress_carga($cliente)
{
    if ($cliente == 'avalia') {
        $color1 = '0,33,97';
        $color2 = '159,255,17';
        /*$color1='0,93,171';
        $color2='235,28,36';*/
    }
    $progres .= "
<script language=\"javascript\">
function callprogress(vValor,vValor2,total,tipo_progress,class1,class2){
	document.getElementById(\"getprogress2\").innerHTML = vValor2;
 	document.getElementById(\"total\").innerHTML = total;
	document.getElementById(\"tipoProgress\").innerHTML = \"&nbsp;\"+tipo_progress+\"&nbsp;\";
 	$('#progress').width(vValor+'%');
 	$('#progress').html(vValor+'%');
	$(\"#progress\").removeClass(class1);
	$(\"#progress\").addClass(class2);
}
</script>";

    $progres .= '
<br>
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<div class="progress">
	  		<div id="progress" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
	  		style=" text-align:right; width:0%" >
	  		</div>
		</div>
	<center>
	<div class="ProgressBarText2" ><span id="getprogress2"></span><span id="tipoProgress">&nbsp;registros procesados de: </span><span id="total"></span></div>
	</center>
	</div>
	';
    return ($progres);

}

?>
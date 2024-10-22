<?php

include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";
require('../class/phpmailer/class.phpmailer.php');
require('../class/phpmailer/class.smtp.php');

$conn = new dbClassMysql();
$func = new Functions();

$func->getHeaders();
$res = array(
    "err"=> true,
    "mss"=> "Error 404",
    "mssError" =>""
);
$intIdPago= isset($_POST['idPago']) ? trim($_POST['idPago']):'';
if(isset($_GET['get_send_mail_reserva'])){
	$strQueryPdf = "SELECT c.*,IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
					IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as nombreCompleto, ag.correoElectronico
					FROM  enganche c
					INNER JOIN  apartamentos a ON c.apartamento = a.apartamento
					INNER JOIN  agregarCliente ag ON c.idCliente= ag.idCliente
					WHERE c.idEnganche={$intIdPago};";

	$qTmp = $conn ->db_query($strQueryPdf);
	$rTmp = $conn->db_fetch_object($qTmp);
	if($rTmp->proyecto=='Marabi'){
		$subject='Recibo de pago';
		$rutaImage="../img/marabi_gracias.jpg";
		$nombreImage="marabiGracias";
		$formatoImage="marabi_gracias.jpg";
	}else if($rTmp->proyecto=='Naos'){
		$subject='Recibo de pago';
		$rutaImage="../img/naos_gracias.png";
		$nombreImage="naosGracias";
		$formatoImage="naos_gracias.png";
		
	}
	$_path = "../public/";
    $adjuntosdir= $_path."reciboReserva_".$intIdPago.".pdf";
	$mail = new PHPMailer();
	$from='info@avalia.com.gt';
	$nombreCliente=$rTmp->nombreCompleto;

		$body='Gracias por haber realizado su pago de Reserva';
	
	$mail->AddEmbeddedImage($rutaImage, $nombreImage, $formatoImage);
	$mail->Body='<div>
					<div style="margin:0px auto;max-width:600px">
						<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%">
							<tbody>
								<tr>
									<td style="direction:ltr;font-size:0px;padding:0px;text-align:center">
										<div class="m_9121124062102632978mj-column-per-100" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%">
											<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top" width="100%">
												<tbody>
													<tr>
														<td align="center" style="font-size:0px;padding:0px;word-break:break-word">
															<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px">
																<tbody>
																<tr>
																	<td style="width:600px"> <img height="auto" src="cid:'.$nombreImage.'" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px" width="600" class="CToWUd a6T" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: 844px; top: 566px;"><div id=":1cq" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0" aria-label="Descargar el archivo adjunto " data-tooltip-class="a1V" data-tooltip="Descargar"><div class="akn"><div class="aSK J-J5-Ji aYr"></div></div></div></div> </td>
																</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div> 
					<div style="background:#f7e1dc;background-color:#f7e1dc;margin:0px auto;max-width:600px">
						<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#f7e1dc;background-color:#f7e1dc;width:100%">
							<tbody>
								<tr>
									<td style="direction:ltr;font-size:0px;padding:20px 0;padding-left:15px;padding-top:30px;text-align:center">
										<div class="m_9121124062102632978mj-column-per-100" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%">
											<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top" width="100%">
												<tbody>
													<tr>
														<td align="left" class="m_9121124062102632978title-text" style="font-size:0px;padding:10px 25px;word-break:break-word">
															<div style="font-family:\'Helvetica\';font-size:28px;font-weight:500;line-height:1;text-align:left;color:#ff764f">'.$nombreCliente.'</div>
														</td>
													</tr>
													<tr>
														<td align="left" style="font-size:0px;padding:10px 25px;padding-bottom:40px;word-break:break-word">
															<div style="font-family:\'Helvetica\';font-size:18px;font-weight:100;line-height:25px;text-align:left;color:#0b2647">'.$body.'</div>
														</td>
													</tr>
												</tbody>
											</table>
										</div>	
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>';
	$mail->IsSMTP();
	$mail->SMTPSecure = "ssl";
	$mail->Host = "mail.ocacall.com"; // SMTP server
	$mail->Port = 465;
	$mail->SMTPAuth = true;
	$mail->From = $from;
	$mail->FromName = 'Información Avalia';
	$mail->Subject = $subject;
	$mail->AddAddress($rTmp->correoElectronico);
	$mail->IsHTML(true);
	$mail->Username = "helpdesk@ocacall.com";
	$mail->Password = "Ge@FAG8C8km-QC9fNAb9x@XT5b!ytHRk3nK7FN4hq=9dQ";
	$mail->CharSet = 'UTF-8';
	$mail->addAttachment($adjuntosdir,'Recibo_'.date("YmdHis").'.pdf');
	if(!$mail->Send()) {
		$res = array(
			"err" => true,
			"mss" => $mail->ErrorInfo,
		);
	}
	else
	{
		$res = array(
			"err"=> false,
			"mss"=> "Correo Enviado",
		);
	}
}
if(isset($_GET['get_send_mail_recibo'])){
	$strQueryPdf = "SELECT ped.idUsuarioPago, e.idVendedor,e.idCliente, idDetalle,fechaPago,montoPagado,noDeposito,bancoDeposito,noPago,e.apartamento,e.proyecto,
	IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),
	IFNULL(CONCAT(primerApellido,' '),''),IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as nombreCliente,ag.correoElectronico 
	FROM  prograEngancheDetalle ped
	INNER JOIN  enganche e ON ped.idEnganche = e.idEnganche
	INNER JOIN  agregarCliente ag ON e.idCliente = ag.idCliente   
	where idDetalle ={$intIdPago};";

	$qTmp = $conn ->db_query($strQueryPdf);
	$rTmp = $conn->db_fetch_object($qTmp);
	if($rTmp->proyecto=='Marabi'){
		$subject='Recibo de pago';
		$rutaImage="../img/marabi_gracias.jpg";
		$nombreImage="marabiGracias";
		$formatoImage="marabi_gracias.jpg";
	}else if($rTmp->proyecto=='Naos'){
		$subject='Recibo de pago';
		$rutaImage="../img/naos_gracias.png";
		$nombreImage="naosGracias";
		$formatoImage="naos_gracias.png";
		
	}
	$_path = "../public/";
    $adjuntosdir= $_path."recibo_".$intIdPago.".pdf";
	$mail = new PHPMailer();
	$from='info@avalia.com.gt';
	$nombreCliente=$rTmp->nombreCompleto;

		$body='Gracias por haber realizado su pago corresponiente a su cuota de enganche';
	
	$mail->AddEmbeddedImage($rutaImage, $nombreImage, $formatoImage);
	$mail->Body='<div>
					<div style="margin:0px auto;max-width:600px">
						<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%">
							<tbody>
								<tr>
									<td style="direction:ltr;font-size:0px;padding:0px;text-align:center">
										<div class="m_9121124062102632978mj-column-per-100" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%">
											<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top" width="100%">
												<tbody>
													<tr>
														<td align="center" style="font-size:0px;padding:0px;word-break:break-word">
															<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px">
																<tbody>
																<tr>
																	<td style="width:600px"> <img height="auto" src="cid:'.$nombreImage.'" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px" width="600" class="CToWUd a6T" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: 844px; top: 566px;"><div id=":1cq" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0" aria-label="Descargar el archivo adjunto " data-tooltip-class="a1V" data-tooltip="Descargar"><div class="akn"><div class="aSK J-J5-Ji aYr"></div></div></div></div> </td>
																</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div> 
					<div style="background:#f7e1dc;background-color:#f7e1dc;margin:0px auto;max-width:600px">
						<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#f7e1dc;background-color:#f7e1dc;width:100%">
							<tbody>
								<tr>
									<td style="direction:ltr;font-size:0px;padding:20px 0;padding-left:15px;padding-top:30px;text-align:center">
										<div class="m_9121124062102632978mj-column-per-100" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%">
											<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top" width="100%">
												<tbody>
													<tr>
														<td align="left" class="m_9121124062102632978title-text" style="font-size:0px;padding:10px 25px;word-break:break-word">
															<div style="font-family:\'Helvetica\';font-size:28px;font-weight:500;line-height:1;text-align:left;color:#ff764f">'.$nombreCliente.'</div>
														</td>
													</tr>
													<tr>
														<td align="left" style="font-size:0px;padding:10px 25px;padding-bottom:40px;word-break:break-word">
															<div style="font-family:\'Helvetica\';font-size:18px;font-weight:100;line-height:25px;text-align:left;color:#0b2647">'.$body.'</div>
														</td>
													</tr>
												</tbody>
											</table>
										</div>	
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>';
	$mail->IsSMTP();
	$mail->SMTPSecure = "ssl";
	$mail->Host = "mail.ocacall.com"; // SMTP server
	$mail->Port = 465;
	$mail->SMTPAuth = true;
	$mail->From = $from;
	$mail->FromName = 'Información Avalia';
	$mail->Subject = $subject;
	$mail->AddAddress($rTmp->correoElectronico);
	$mail->IsHTML(true);
	$mail->Username = "helpdesk@ocacall.com";
	$mail->Password = "Ge@FAG8C8km-QC9fNAb9x@XT5b!ytHRk3nK7FN4hq=9dQ";
	$mail->CharSet = 'UTF-8';
	$mail->addAttachment($adjuntosdir,'Recibo_'.date("YmdHis").'.pdf');
	if(!$mail->Send()) {
		$res = array(
			"err" => true,
			"mss" => $mail->ErrorInfo,
		);
	}
	else
	{
		$res = array(
			"err"=> false,
			"mss"=> "Correo Enviado",
		);
	}
}
$conn->db_close();
echo json_encode($res);
?>
<?php
include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";
require "../class/autoload.php";
use Mailjet\Resources;
use Mailjet\Client;

$conn = new dbClassMysql();
$func = new Functions();

$strNombreCliente= isset($_POST['nombreCliente']) ? trim($_POST['nombreCliente']):'';
$strCorreo= isset($_POST['correo']) ? trim($_POST['correo']):'';
$strProyecto= isset($_POST['proyecto']) ? trim($_POST['proyecto']):'';
$strPrefijo= isset($_POST['prefijo']) ? trim($_POST['prefijo']):'';
$strTipoContacto= isset($_POST['tipoContacto']) ? trim($_POST['tipoContacto']):'';
if(isset($_GET['get_send_mail'])){
	if($strProyecto=='1'){
		$subject='¡Gracias por conocer MARABI!';
		$rutaImage="../img/marabi_gracias.jpg";
		$nombreImage="marabiGracias";
		$formatoImage="marabi_gracias.jpg";
		$proyecto="MARABI";
	}else if($strProyecto=='2'){
		$subject='¡Gracias por conocer NAOS!';
		$rutaImage="../img/naos_gracias.png";
		$nombreImage="naosGracias";
		$formatoImage="naos_gracias.png";
		$proyecto='NAOS';
		
	}
	//$mail = new PHPMailer();
	$from='info@avalia.com.gt';
	$nombreCliente=$strPrefijo.' '.$strNombreCliente;

	if($strTipoContacto=='visita_showroom'){
		$body='Gracias por visitar nuestro apartamento modelo, esperamos que la experiencia de Avalia Desarrollos haya sido satisfactoria y que '.$proyecto.' se convierta pronto en su nuevo hogar.';
	}else if($strTipoContacto=='visita_virtual'){
		$body='Gracias por conocer nuestro recorrido virtual, esperamos que la experiencia de Avalia Desarrollos haya sido satisfactoria y que '.$proyecto.' se convierta pronto en su nuevo hogar.';
	}else if($strTipoContacto=='visita_oficina'){
		$body='Gracias por permitirnos visitarle, esperamos que la experiencia de Avalia Desarrollos haya sido satisfactoria y que '.$proyecto.' se convierta pronto en su nuevo hogar.';
	}
	try
    {
        $mj = new \Mailjet\Client('3c34a972822d24cea190560125246b13', '24309a488849fc85e4b34eac7fcff13e', true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "info@avalia.gt",
                        'Name' => "Información Avalia"
                    ],
                    'To' => [
                        [
                            'Email' => $strCorreo,
                            'Name' => $strNombreCliente
                        ]
                    ],
                    'Subject' => $subject,
                    'HTMLPart' => '<div>
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
																	<td style="width:600px"> <img height="auto" src="cid:saludo" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px" width="600" class="CToWUd a6T" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: 844px; top: 566px;"><div id=":1cq" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0" aria-label="Descargar el archivo adjunto " data-tooltip-class="a1V" data-tooltip="Descargar"><div class="akn"><div class="aSK J-J5-Ji aYr"></div></div></div></div> </td>
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
					</div>',
					'InlinedAttachments' => [
						[
							'ContentType' => "image/png",
							'Filename' => "$formatoImage",
							'ContentID' => "saludo",
							'Base64Content' => fcnBase64($rutaImage)
						]
					],
                	'CustomID' => "AvaliaInfo"
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        if ($response->success())
        {
			$res = array(
				"err" => true,
				"mss" => 'Correo Enviado',
			);
			echo json_encode($res);
            return true;
        } else
        {
			$res = array(
				"err"=> false,
				"mss"=> "Error en correo",
			);
			echo json_encode($res);
            return false;
        }
    }
    catch (Exception $e)
    {
        return false;
    }
}
function fcnBase64($imagen){
    $imagenBase64 = base64_encode(file_get_contents($imagen));
    return $imagenBase64;

}
$conn->db_close();

?>
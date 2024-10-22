<?php

include_once "../class/dbClassMysql.php";
include_once "../class/functions.php";
date_default_timezone_set('America/Guatemala');

$conn = new dbClassMysql();
$func = new Functions();

$func->getHeaders();
$res = array(
    "err"=> true,
    "mss"=> "Error 404",
    "mssError" =>""
);
session_name("inmobiliaria");
session_start();
if(!isset($_SESSION['login']) or $_SESSION['login']!='si')
{
	echo "<script>location.href = 'index.php'</script>"; 
}
$id_usuario=$_SESSION['id_usuario'];
$id_perfil=$_SESSION['id_perfil'];
$arrayProyectos = explode(",",$_SESSION['proyectos']);
$proyectos = '';
$countP=0;
foreach($arrayProyectos as $valor)
{
    if($countP==0)
        $coma='';
    else
        $coma=',';
    $proyectos .= $coma."'".$valor."'";
    $countP++;
}
$countP=0;
//Buscar
$intEstadoDes= isset($_POST['desestimiento']) ? trim($_POST['desestimiento']):"0";
$strDatoBuscar= isset($_POST['datoBuscar']) ? trim($_POST['datoBuscar']):'';
$strFechaInicial = isset($_POST['fechaInicial']) && $_POST['fechaInicial']!='' ? date('Y-m-d',strtotime($_POST['fechaInicial'])):'';
$strFechafinal = isset($_POST['fechaFinal']) && $_POST['fechaFinal']!='' ? date('Y-m-d',strtotime($_POST['fechaFinal'])):'';
$strProyectoBuscar= isset($_POST['proyectoBscTxt']) ? trim($_POST['proyectoBscTxt']):'';
$strTorreBuscar= isset($_POST['torreBscTxt']) ? trim($_POST['torreBscTxt']):'';
$strApartamentoBuscar= isset($_POST['apartamentoBscTxt']) ? trim($_POST['apartamentoBscTxt']):'';
$strNivelBuscar= isset($_POST['nivelBscTxt']) ? trim($_POST['nivelBscTxt']):'';

//RESERVA
$intIdReserva = isset($_POST['idReserva']) ? intval($_POST['idReserva']):0;
$intMontoReserva = isset($_POST['montoReserva']) ? floatval(str_replace(',','',$_POST['montoReserva'])):0;
$strNombreCompleto = isset($_POST['nombreCompleto']) ? trim($_POST['nombreCompleto']):'';
$strNumeroDpi = isset($_POST['numeroDpi']) ? trim($_POST['numeroDpi']):'';

$intIdCotizacion = isset($_POST['idCotizacion']) ? intval($_POST['idCotizacion']):0;
$strProyecto = isset($_POST['proyectoCliente']) ? trim($_POST['proyectoCliente']):'';
$strProyectoEng = isset($_POST['txtProyecto']) ? trim($_POST['txtProyecto']):'';
$strTorreEng = isset($_POST['txtTorre']) ? trim($_POST['txtTorre']):'';
$strNivelEng = isset($_POST['txtNivel']) ? trim($_POST['txtNivel']):'';
$strApartamentoEng = isset($_POST['txtApartamento']) ? trim($_POST['txtApartamento']):'';
$intNivel = isset($_POST['nivelEng']) ? intval($_POST['nivelEng']):0;
$intAptoSelect = isset($_POST['apartamentoSelect']) ? intval($_POST['apartamentoSelect']):0;
$intTorre = isset($_POST['torreEng']) ? intval($_POST['torreEng']):0;
$intNivelSelect = isset($_POST['nivelSelect']) ? intval($_POST['nivelSelect']):0;
$intDepto = isset($_POST['depto']) ? intval($_POST['depto']):0;
$intVendedor = isset($_POST['idVendedor']) ? intval($_POST['idVendedor']):0;
$strApartamento = isset($_POST['apartamentoEng']) ? trim($_POST['apartamentoEng']):'';
$strClientName = isset($_POST['clientName']) ? trim($_POST['clientName']):'';

//Guardar Cliente
$intIdCliente = isset($_POST['idCliente']) ? intval($_POST['idCliente']):0;
$intIdOcaCliente = isset($_POST['idOcaCliente']) ? intval($_POST['idOcaCliente']):0;
$intEstadoCliente = isset($_POST['estadoCl']) ? intval($_POST['estadoCl']):0;
$intTrabajarFHA = isset($_POST['tramiteFHACl']) ? intval($_POST['tramiteFHACl']):0;
$strProyectoCl = isset($_POST['ProyectoCl']) ? trim($_POST['ProyectoCl']):'';
$strTipoCliente = isset($_POST['tipoCliente']) ? trim($_POST['tipoCliente']):'';
$strNombreSa = isset($_POST['nombreSa']) ? trim($_POST['nombreSa']):'';
$strTipoComision = isset($_POST['tipoComision']) ? trim($_POST['tipoComision']):'';
$strRtu = isset($_POST['rtu']) ? trim($_POST['rtu']):'';
$strRepresentanteLegal = isset($_POST['representanteLegal']) ? trim($_POST['representanteLegal']):'';
$strPatenteEmpresa = isset($_POST['patenteEmpresa']) ? trim($_POST['patenteEmpresa']):'';
$strPatenteSociedad = isset($_POST['patenteSociedad']) ? trim($_POST['patenteSociedad']):'';
$strPrimerNombre = isset($_POST['primerNombre']) ? trim($_POST['primerNombre']):'';
$strSegundoNombre = isset($_POST['segundoNombre']) ? trim($_POST['segundoNombre']):'';
$strPrimerApellido = isset($_POST['primerApellido']) ? trim($_POST['primerApellido']):'';
$strSegundoApellido = isset($_POST['segundoApellido']) ? trim($_POST['segundoApellido']):'';
$strApellidoCasada = isset($_POST['apellidoCasada']) ? trim($_POST['apellidoCasada']):'';
$strTercerNombre = isset($_POST['tercerNombre']) ? trim($_POST['tercerNombre']):'';
$strtelefonoFijo = isset($_POST['telefonoFijo']) ? trim($_POST['telefonoFijo']):'';
$strtelefonoReferencia = isset($_POST['telefonoReferencia']) ? trim($_POST['telefonoReferencia']):'';
$intDependientes = isset($_POST['dependientesCl']) ? intval($_POST['dependientesCl']):0;
$intMunicipio= isset($_POST['municipio']) ? intVal($_POST['municipio']):0;
$strCorreo = isset($_POST['correo']) ? trim($_POST['correo']):'';
$strfha = isset($_POST['fha']) ? trim($_POST['fha']):'';
$strSubsidio = isset($_POST['subsidio']) ? trim($_POST['subsidio']):'No';
$strBancoFin = isset($_POST['bancoFinanciamientoCot']) ? trim($_POST['bancoFinanciamientoCot']):'';
$strTelefono = isset($_POST['telefono']) ? trim($_POST['telefono']):'';
$strDireccion = isset($_POST['direccion']) ? trim($_POST['direccion']):'';
$strprofesion = isset($_POST['profesionCl']) ? trim($_POST['profesionCl']):'';
$strDpi = isset($_POST['numeroDpi']) ? trim($_POST['numeroDpi']):'';
$strFechaVencimientoDpi = isset($_POST['fechaVencimientoDpi']) ? trim($_POST['fechaVencimientoDpi']):'';
$strImgDpi = isset($_POST['imgDpi']) ? trim($_POST['imgDpi']):'';
$intNivelCl = isset($_POST['nivelCl']) ? intval($_POST['nivelCl']):0;
$strApartamentoCl = isset($_POST['apartamentoCl']) ? trim($_POST['apartamentoCl']):'';
$strnit = isset($_POST['nitCl']) ? trim($_POST['nitCl']):'';
$strfechaEmisionDpi = isset($_POST['fechaEmisionDpiCl']) ? trim($_POST['fechaEmisionDpiCl']):'';
$strnacionalidad = isset($_POST['nacionalidadCl']) ? trim($_POST['nacionalidadCl']):'';
$strfechaNacimiento = isset($_POST['fechaNacimientoCl']) ? trim($_POST['fechaNacimientoCl']):'';
$strestadoCivil = isset($_POST['estadoCivilCl']) ? trim($_POST['estadoCivilCl']):'';
$strempresaLabora = isset($_POST['empresaLaboraCl']) ? trim($_POST['empresaLaboraCl']):'';
$strpuestoEmpresa = isset($_POST['puestoEmpresaCl']) ? trim($_POST['puestoEmpresaCl']):'';
$strdireccionEmpresa = isset($_POST['direccionEmpresaCl']) ? trim($_POST['direccionEmpresaCl']):'';
$strobservacionesCl = isset($_POST['observacionesCl']) ? trim($_POST['observacionesCl']):'';
$intsalarioMensual = isset($_POST['salarioMensualCl']) ? floatval(str_replace(',','',$_POST['salarioMensualCl'])):0;
$strotrosIngresos = isset($_POST['otrosIngresosCl']) ? trim($_POST['otrosIngresosCl']):'';
$intmontoOtrosIngresos= isset($_POST['montoOtrosIngresosCl']) ? floatval(str_replace(',','',$_POST['montoOtrosIngresosCl'])):0;

//Guardar Codeudor
$intIdCodeudor = isset($_POST['idCodeudor']) ? intval($_POST['idCodeudor']):0;
$intIdEngancheCo = isset($_POST['idEngancheCo']) ? intval($_POST['idEngancheCo']):0;
$intIdClienteCo = isset($_POST['idClienteCo']) ? intval($_POST['idClienteCo']):0;
$strPrimerNombreCo = isset($_POST['primerNombreCo']) ? trim($_POST['primerNombreCo']):'';
$strSegundoNombreCo = isset($_POST['segundoNombreCo']) ? trim($_POST['segundoNombreCo']):'';
$strPrimerApellidoCo = isset($_POST['primerApellidoCo']) ? trim($_POST['primerApellidoCo']):'';
$strSegundoApellidoCo = isset($_POST['segundoApellidoCo']) ? trim($_POST['segundoApellidoCo']):'';
$strApellidoCasadaCo = isset($_POST['apellidoCasadaCo']) ? trim($_POST['apellidoCasadaCo']):'';
$strTercerNombreCo = isset($_POST['tercerNombreCo']) ? trim($_POST['tercerNombreCo']):'';
$strtelefonoFijoCo = isset($_POST['telefonoFijoCo']) ? trim($_POST['telefonoFijoCo']):'';
$strtelefonoReferenciaCo = isset($_POST['telefonoReferenciaCo']) ? trim($_POST['telefonoReferenciaCo']):'';
$intDependientesCo = isset($_POST['dependientesCo']) ? intval($_POST['dependientesCo']):0;
$intMunicipioCo= isset($_POST['municipioCo']) ? intVal($_POST['municipioCo']):0;
$intDeptoCo = isset($_POST['deptoCo']) ? intval($_POST['deptoCo']):0;
$strCorreoCo = isset($_POST['correoCo']) ? trim($_POST['correoCo']):'';
$strfhaCo = isset($_POST['fhaCo']) ? trim($_POST['fhaCo']):'';
$strTelefonoCo = isset($_POST['telefonoCo']) ? trim($_POST['telefonoCo']):'';
$strDireccionCo = isset($_POST['direccionCo']) ? trim($_POST['direccionCo']):'';
$strprofesionCo = isset($_POST['profesionCo']) ? trim($_POST['profesionCo']):'';
$strDpiCo = isset($_POST['numeroDpiCo']) ? trim($_POST['numeroDpiCo']):'';
$strFechaVencimientoDpiCo = isset($_POST['fechaVencimientoDpiCo']) ? trim($_POST['fechaVencimientoDpiCo']):'';
$strImgDpiCo = isset($_POST['imgDpiCo']) ? trim($_POST['imgDpiCo']):'';
$strnitCo = isset($_POST['nitCo']) ? trim($_POST['nitCo']):'';
$strfechaEmisionDpiCo = isset($_POST['fechaEmisionDpiCo']) ? trim($_POST['fechaEmisionDpiCo']):'';
$strnacionalidadCo = isset($_POST['nacionalidadCo']) ? trim($_POST['nacionalidadCo']):'';
$strfechaNacimientoCo = isset($_POST['fechaNacimientoCo']) ? trim($_POST['fechaNacimientoCo']):'';
$strestadoCivilCo = isset($_POST['estadoCivilCo']) ? trim($_POST['estadoCivilCo']):'';
$strempresaLaboraCo = isset($_POST['empresaLaboraCo']) ? trim($_POST['empresaLaboraCo']):'';
$strpuestoEmpresaCo = isset($_POST['puestoEmpresaCo']) ? trim($_POST['puestoEmpresaCo']):'';
$strdireccionEmpresaCo = isset($_POST['direccionEmpresaCo']) ? trim($_POST['direccionEmpresaCo']):'';
$intsalarioMensualCo = isset($_POST['salarioMensualCo']) ? floatval(str_replace(',','',$_POST['salarioMensualCo'])):0;
$strotrosIngresosCo = isset($_POST['otrosIngresosCo']) ? trim($_POST['otrosIngresosCo']):'';
$intmontoOtrosIngresosCo = isset($_POST['montoOtrosIngresosCo']) ? floatval(str_replace(',','',$_POST['montoOtrosIngresosCo'])):0;

//guardar Enganche
$intIdEnganche = isset($_POST['idEnganche']) ? intval($_POST['idEnganche']):0;
$intIdDetalle = isset($_POST['idDetalle']) ? intval($_POST['idDetalle']):0;
$intdescuentoPorcentual = isset($_POST['descuentoPorcentualEng']) ? floatval($_POST['descuentoPorcentualEng']):0;
$intenganche = isset($_POST['engancheEng']) ? floatval($_POST['engancheEng']):0;
$intdescuentoPorcentualMonto = isset($_POST['descuentoPorcentualMontoEng']) ? floatval(str_replace(',','',$_POST['descuentoPorcentualMontoEng'])):0;
$intengancheMonto = isset($_POST['engancheMontoEng']) ? floatval(str_replace(',','',$_POST['engancheMontoEng'])):0;
$intengancheMontoTotal = isset($_POST['totalEnganche']) ? floatval(str_replace(',','',$_POST['totalEnganche'])):0;
$intparqueosExtra = isset($_POST['parqueosExtraEng']) ? intVal($_POST['parqueosExtraEng']):0;
$intparqueosExtraMoto = isset($_POST['parqueosExtraMotoEng']) ? intVal($_POST['parqueosExtraMotoEng']):0;
$intbodegaExtra = isset($_POST['bodegaExtraEng']) ? intVal($_POST['bodegaExtraEng']):0;
$intmontoReserva = isset($_POST['montoReservaEng']) ? floatval(str_replace(',','',$_POST['montoReservaEng'])):0;
$strfechaPagoReserva = isset($_POST['fechaPagoReservaEng']) ? trim($_POST['fechaPagoReservaEng']):'';
$intplazoFinanciamiento = isset($_POST['plazoFinanciamientoEng']) ? floatval($_POST['plazoFinanciamientoEng']):0;
$strfechaPagoInicial = isset($_POST['fechaPagoInicialEng']) ? trim($_POST['fechaPagoInicialEng']):'';
$intpagosEnganche = isset($_POST['pagosEngancheEng']) ? intVal($_POST['pagosEngancheEng']):0;
$intpagoPromesa = isset($_POST['pagoPromesaEng']) ? floatval(str_replace(',','',$_POST['pagoPromesaEng'])):0;
$intdescuento = isset($_POST['descuentoEng']) ? floatval($_POST['descuentoEng']):0;
$strformaPago= isset($_POST['formaPagoEng']) ? trim($_POST['formaPagoEng']):'';
$strnoDepositoReserva= isset($_POST['noDepositoReservaEng']) ? trim($_POST['noDepositoReservaEng']):'';
$strbancoChequeReserva= isset($_POST['bancoChequeReservaEng']) ? trim($_POST['bancoChequeReservaEng']):'';
$strbancoDepositoReserva= isset($_POST['bancoDepositoReservaEng']) ? trim($_POST['bancoDepositoReservaEng']):'';
$strnoChequeReserva= isset($_POST['noChequeReservaEng']) ? trim($_POST['noChequeReservaEng']):'';
$strobservaciones= isset($_POST['observacionesEng']) ? trim($_POST['observacionesEng']):'';
$strNoReciboEng= isset($_POST['noReciboEng']) ? trim($_POST['noReciboEng']):'';
$strObservacionesForm= isset($_POST['observacionesForm']) ? trim($_POST['observacionesForm']):'';
$intIdVendedor = isset($_POST['nombreVendedorEng']) ? intVal($_POST['nombreVendedorEng']):0; 
$strCocinaEng = isset($_POST['CocinaEng']) ? trim($_POST['CocinaEng']):'Sin cocina';

//guardar Cotizacion
$strProyectoCot = isset($_POST['txtProyecto']) ? trim($_POST['txtProyecto']):'';
$strTorreCot = isset($_POST['txtTorre']) ? trim($_POST['txtTorre']):'';
$strNivelCot = isset($_POST['txtNivel']) ? trim($_POST['txtNivel']):'';
$strApartamentoCot = isset($_POST['txtApartamento']) ? trim($_POST['txtApartamento']):'';
$strNombreCompletoCot = isset($_POST['nombreClienteCot']) ? trim($_POST['nombreClienteCot']):'';
$strCorreoCot = isset($_POST['correoCot']) ? trim($_POST['correoCot']):'';
$strTelefonoCot = isset($_POST['telefonoCot']) ? trim($_POST['telefonoCot']):'';
$intIdCotizacion = isset($_POST['idCotizacion']) ? intval($_POST['idCotizacion']):0;
$intdescuentoPorcentualCot = isset($_POST['descuentoPorcentualCot']) ? floatval($_POST['descuentoPorcentualCot']):0;
$intengancheCot = isset($_POST['engancheCot']) ? floatval($_POST['engancheCot']):0;
$intdescuentoPorcentualMontoCot = isset($_POST['descuentoPorcentualMontoCot']) ? floatval(str_replace(',','',$_POST['descuentoPorcentualMontoCot'])):0;
$intengancheMontoCot = isset($_POST['engancheMontoCot']) ? floatval(str_replace(',','',$_POST['engancheMontoCot'])):0;
$intengancheMontoTotalCot = isset($_POST['totalEnganche']) ? floatval(str_replace(',','',$_POST['totalEnganche'])):0;
$intparqueosExtraCot = isset($_POST['parqueosExtraCot']) ? intVal($_POST['parqueosExtraCot']):0;
$intparqueosExtraMotoCot = isset($_POST['parqueosExtraMotoCot']) ? intVal($_POST['parqueosExtraMotoCot']):0;
$intbodegaExtraCot = isset($_POST['bodegaExtraCot']) ? intVal($_POST['bodegaExtraCot']):0;
$intmontoReservaCot = isset($_POST['montoReservaCot']) ? floatval(str_replace(',','',$_POST['montoReservaCot'])):0;
$intplazoFinanciamientoCot = isset($_POST['plazoFinanciamientoCot']) ? floatval($_POST['plazoFinanciamientoCot']):0;
$intpagosEngancheCot = isset($_POST['pagosEngancheCot']) ? intVal($_POST['pagosEngancheCot']):0;
$intdescuentoCot = isset($_POST['descuentoCot']) ? floatval($_POST['descuentoCot']):0;
$intIdVendedorCot = isset($_POST['nombreVendedorCot']) ? intVal($_POST['nombreVendedorCot']):0; 
$strCocinaCot = isset($_POST['CocinaCot']) ? trim($_POST['CocinaCot']):'Sin cocina';


//pago comisión
$intIdFormaPagoComision =  isset($_POST['idFormaPagoComision']) ? intval($_POST['idFormaPagoComision']):0;
$strPpartamentoPagoComision = isset($_POST['apartamentoPagoComision']) ? trim($_POST['apartamentoPagoComision']):'';


//GUARDAE Pago
$intIdPago = isset($_POST['idPago']) ? intval($_POST['idPago']):0;
$intMonto = isset($_POST['montoPago']) ? floatval(str_replace(',','',$_POST['montoPago'])):0;
$strTipoPago= isset($_POST['tipoPago']) ? trim($_POST['tipoPago']):'';
$strObservaciones= isset($_POST['observaciones']) ? trim($_POST['observaciones']):'';
$strnoDeposito= isset($_POST['noDeposito']) ? trim($_POST['noDeposito']):'';
$strbancoCheque= isset($_POST['bancoCheque']) ? trim($_POST['bancoCheque']):'';
$strbancoDeposito= isset($_POST['bancoDeposito']) ? trim($_POST['bancoDeposito']):'';
$strnoCheque= isset($_POST['noCheque']) ? trim($_POST['noCheque']):'';
$strNoRecibo= isset($_POST['noRecibo']) ? trim($_POST['noRecibo']):'';
$strFechaPago= isset($_POST['fechaPago']) ? trim($_POST['fechaPago']):'';

//GUARDAE Pago Comision
$intNoPagoComision = isset($_POST['noPagoComision']) ? intval($_POST['noPagoComision']):0;
$intIdPagoCom = isset($_POST['idPagoComision_'.$intNoPagoComision]) ? intval($_POST['idPagoComision_'.$intNoPagoComision]):0;
$intMontoCom = isset($_POST['montoPago_'.$intNoPagoComision]) ? floatval(str_replace(',','',$_POST['montoPago_'.$intNoPagoComision])):0;
$strObservacionesCom= isset($_POST['observaciones_'.$intNoPagoComision]) ? trim($_POST['observaciones_'.$intNoPagoComision]):'';
$strnoDepositoCom= isset($_POST['noDeposito_'.$intNoPagoComision]) ? trim($_POST['noDeposito_'.$intNoPagoComision]):'';
$strbancoChequeCom= isset($_POST['bancoCheque_'.$intNoPagoComision]) ? trim($_POST['bancoCheque_'.$intNoPagoComision]):'';
$strFechaPagoCom= isset($_POST['fechaPago_'.$intNoPagoComision]) ? trim($_POST['fechaPago_'.$intNoPagoComision]):'';

//GUARDAE Pago Extra
$intIdPagoExtra = isset($_POST['idPagoExtra']) ? intval($_POST['idPagoExtra']):0;
$strTipoPagoExtra= isset($_POST['tipoPagoExtra']) ? trim($_POST['tipoPagoExtra']):'';
$intMontoExtra = isset($_POST['montoPagoExtra']) ? floatval(str_replace(',','',$_POST['montoPagoExtra'])):0;
$strObservacionesExtra= isset($_POST['observacionesExtra']) ? trim($_POST['observacionesExtra']):'';
$strnoDepositoExtra= isset($_POST['noDepositoExtra']) ? trim($_POST['noDepositoExtra']):'';
$strbancoChequeExtra= isset($_POST['bancoChequeExtra']) ? trim($_POST['bancoChequeExtra']):'';
$strbancoDepositoExtra= isset($_POST['bancoDepositoExtra']) ? trim($_POST['bancoDepositoExtra']):'';
$strFechaPagoExtra= isset($_POST['fechaPagoExtra']) ? trim($_POST['fechaPagoExtra']):'';
$strTipoCobroExtra= isset($_POST['tipoCobroExtra']) ? trim($_POST['tipoCobroExtra']):'';
$strNoReciboExtra= isset($_POST['noReciboExtra']) ? trim($_POST['noReciboExtra']):'';
$intIdCobro = isset($_POST['idCobro']) ? intval($_POST['idCobro']):0;

//GUARDAR Pago Extra Enganche
$intIdPagoExtraEng = isset($_POST['idPagoExtraEng']) ? intval($_POST['idPagoExtraEng']):0;
$strTipoPagoExtraEng= isset($_POST['tipoPagoExtraEng']) ? trim($_POST['tipoPagoExtraEng']):'';
$intMontoExtraEng = isset($_POST['montoPagoExtraEng']) ? floatval(str_replace(',','',$_POST['montoPagoExtraEng'])):0;
$strObservacionesExtraEng= isset($_POST['observacionesExtraEng']) ? trim($_POST['observacionesExtraEng']):'';
$strnoDepositoExtraEng= isset($_POST['noDepositoExtraEng']) ? trim($_POST['noDepositoExtraEng']):'';
$strbancoChequeExtraEng= isset($_POST['bancoChequeExtraEng']) ? trim($_POST['bancoChequeExtraEng']):'';
$strbancoDepositoExtraEng= isset($_POST['bancoDepositoExtraEng']) ? trim($_POST['bancoDepositoExtraEng']):'';
$strFechaPagoExtraEng= isset($_POST['fechaPagoExtraEng']) ? trim($_POST['fechaPagoExtraEng']):'';
$strTipoCobroExtraEng= isset($_POST['tipoCobroExtraEng']) ? trim($_POST['tipoCobroExtraEng']):'';
$strNoReciboExtraEng= isset($_POST['noReciboExtraEng']) ? trim($_POST['noReciboExtraEng']):'';

//GUARDAE Pago FINAL
$intMontoF = isset($_POST['montoPagoF']) ? floatval(str_replace(',','',$_POST['montoPagoF'])):0;
$strTipoPagoF= isset($_POST['tipoPagoF']) ? trim($_POST['tipoPagoF']):'';
$strnoDepositoF= isset($_POST['noDepositoF']) ? trim($_POST['noDepositoF']):'';
$strbancoChequeF= isset($_POST['bancoChequeF']) ? trim($_POST['bancoChequeF']):'';
$strbancoDepositoF= isset($_POST['bancoDepositoF']) ? trim($_POST['bancoDepositoF']):'';
$strTipoDesembolso= isset($_POST['tipoDesembolsoF']) ? trim($_POST['tipoDesembolsoF']):'';
$strnoChequeF= isset($_POST['noChequeF']) ? trim($_POST['noChequeF']):'';
$strFechaPagoF= isset($_POST['fechaPagoF']) ? trim($_POST['fechaPagoF']):'';

//GUARDAR CONTRACARGO
$intIdContra = isset($_POST['idContra']) ? intval($_POST['idContra']):0;
$strAccion= isset($_POST['accionContra']) ? trim($_POST['accionContra']):'';
$strObsContra= isset($_POST['observacionesContra']) ? trim($_POST['observacionesContra']):'';
$intMontoContra = isset($_POST['montoContra']) ? floatval(str_replace(',','',$_POST['montoContra'])):0;
$intIdReserva = isset($_POST['idReserva']) ? $_POST['idReserva']:0;

//GUARDAR INFORMACION DE CLIENTE FHA 
$intIdEngancheRes = isset($_POST['idEnganche']) ? intval($_POST['idEnganche']):0;
$strResguardo= isset($_POST['resguardo']) ? trim($_POST['resguardo']):'';
$intValorResguardo = isset($_POST['valor_resguardo']) ? floatval(str_replace(',','',$_POST['valor_resguardo'])):0;
$strFechaEmision= isset($_POST['fecha_emision']) ? trim($_POST['fecha_emision']):'';
$strFechaCaducidad= isset($_POST['fecha_caducidad']) ? trim($_POST['fecha_caducidad']):'';
$strBancoInteres = isset($_POST['bancoInteres']) ? trim($_POST['bancoInteres']):'';
$strBancoResolucion = isset($_POST['banco']) ? trim($_POST['banco']):'';
$intPlazoCredito = isset($_POST['plazo_credito']) ? intval($_POST['plazo_credito']):0;
$strFechaResolucion= isset($_POST['fecha_resolucion']) ? trim($_POST['fecha_resolucion']):'';
$intNoResolucion = isset($_POST['no_resolucion']) ? intval($_POST['no_resolucion']):0;

//Guardar info General Cliente FHA
$strEstatura= isset($_POST['Estatura']) ? trim($_POST['Estatura']):'';
$strPeso= isset($_POST['peso']) ? trim($_POST['peso']):'';

$intCaja= isset($_POST['caja']) ? floatval(str_replace(',','',$_POST['caja'])):0;
$intBancos= isset($_POST['bancos']) ? floatval(str_replace(',','',$_POST['bancos'])):0;
$intCuentasCobrar= isset($_POST['cuentas_cobrar']) ? floatval(str_replace(',','',$_POST['cuentas_cobrar'])):0;
$intTerrenos= isset($_POST['terrenos']) ? floatval(str_replace(',','',$_POST['terrenos'])):0;
$intViviendas= isset($_POST['viviendas']) ? floatval(str_replace(',','',$_POST['viviendas'])):0;
$intVehiculos= isset($_POST['vehiculos']) ? floatval(str_replace(',','',$_POST['vehiculos'])):0;
$intInversiones= isset($_POST['inversiones']) ? floatval(str_replace(',','',$_POST['inversiones'])):0;
$intBonos= isset($_POST['bonos']) ? floatval(str_replace(',','',$_POST['bonos'])):0;
$intAcciones= isset($_POST['acciones']) ? floatval(str_replace(',','',$_POST['acciones'])):0;
$intMuebles= isset($_POST['muebles']) ? floatval(str_replace(',','',$_POST['muebles'])):0;

$intCuentasPagarCortoPlazo= isset($_POST['cuentas_pagar_corto_plazo']) ? floatval(str_replace(',','',$_POST['cuentas_pagar_corto_plazo'])):0;
$intCuentasPagarLargoPlazo= isset($_POST['cuentas_pagar_largo_plazo']) ? floatval(str_replace(',','',$_POST['cuentas_pagar_largo_plazo'])):0;
$intPrestamosHipotecarios= isset($_POST['prestamos_hipotecarios']) ? floatval(str_replace(',','',$_POST['prestamos_hipotecarios'])):0;
$intSostenimientoHogar= isset($_POST['sostenimiento_hogar']) ? floatval(str_replace(',','',$_POST['sostenimiento_hogar'])):0;
$intAlquiler= isset($_POST['alquiler']) ? floatval(str_replace(',','',$_POST['alquiler'])):0;
$intPrestamos= isset($_POST['prestamos']) ? floatval(str_replace(',','',$_POST['prestamos'])):0;
$intImpuestos= isset($_POST['impuestos']) ? floatval(str_replace(',','',$_POST['impuestos'])):0;
$intExtrafinanciamientos= isset($_POST['extrafinanciamientos']) ? floatval(str_replace(',','',$_POST['extrafinanciamientos'])):0;
$intDeudasParticulares= isset($_POST['deudas_particulares']) ? floatval(str_replace(',','',$_POST['deudas_particulares'])):0;

$strDireccionInmueble1= isset($_POST['direccion_inmueble_1']) ? trim($_POST['direccion_inmueble_1']):'';
$strDireccionInmueble2= isset($_POST['direccion_inmueble_2']) ? trim($_POST['direccion_inmueble_2']):'';
$strFinca1= isset($_POST['finca_1']) ? trim($_POST['finca_1']):'';
$strFolio1= isset($_POST['folio_1']) ? trim($_POST['folio_1']):'';
$strLibro1= isset($_POST['libro_1']) ? trim($_POST['libro_1']):'';
$strDepartamento1= isset($_POST['departamento_1']) ? trim($_POST['departamento_1']):'';
$intValorinmueble1= isset($_POST['valor_inmueble_1']) ? floatval(str_replace(',','',$_POST['valor_inmueble_1'])):0;
$strFinca2= isset($_POST['finca_2']) ? trim($_POST['finca_2']):'';
$strFolio2= isset($_POST['folio_2']) ? trim($_POST['folio_2']):'';
$strLibro2= isset($_POST['libro_2']) ? trim($_POST['libro_2']):'';
$strDepartamento2= isset($_POST['departamento_2']) ? trim($_POST['departamento_2']):'';
$intValorinmueble2= isset($_POST['valor_inmueble_2']) ? floatval(str_replace(',','',$_POST['valor_inmueble_2'])):0;
$strMarca1= isset($_POST['marca_1']) ? trim($_POST['marca_1']):'';
$strTipo1= isset($_POST['tipo_vehiculo_1']) ? trim($_POST['tipo_vehiculo_1']):'';
$strModelo1= isset($_POST['modelo_vehiculo_1']) ? trim($_POST['modelo_vehiculo_1']):'';
$strMarca2= isset($_POST['marca_2']) ? trim($_POST['marca_2']):'';
$strTipo2= isset($_POST['tipo_vehiculo_2']) ? trim($_POST['tipo_vehiculo_2']):'';
$strModelo2= isset($_POST['modelo_vehiculo_2']) ? trim($_POST['modelo_vehiculo_2']):'';
$intValorEstimado1= isset($_POST['valor_estimado_1']) ? floatval(str_replace(',','',$_POST['valor_estimado_1'])):0;
$intValorEstimado2= isset($_POST['valor_estimado_2']) ? floatval(str_replace(',','',$_POST['valor_estimado_2'])):0;

//Guardar info General Cliente FHA Dependencia
$strTipoContrato= isset($_POST['tipoContrato']) ? trim($_POST['tipoContrato']):'';
$strVigenciaVence= isset($_POST['vigencia_vence']) ? trim($_POST['vigencia_vence']):'';
$intSalarioNominal= isset($_POST['salario_nominal']) ? floatval(str_replace(',','',$_POST['salario_nominal'])):0;
$intBonoCatorce= isset($_POST['bono_catorce']) ? floatval(str_replace(',','',$_POST['bono_catorce'])):0;
$intAguinaldo= isset($_POST['aguinaldo']) ? floatval(str_replace(',','',$_POST['aguinaldo'])):0;
$intHonorarios= isset($_POST['honorarios']) ? floatval(str_replace(',','',$_POST['honorarios'])):0;
$intOtrosIngresosFha= isset($_POST['otros_ingresos_fha']) ? floatval(str_replace(',','',$_POST['otros_ingresos_fha'])):0;
$intIgss= isset($_POST['igss']) ? floatval(str_replace(',','',$_POST['igss'])):0;
$intIsr= isset($_POST['isr']) ? floatval(str_replace(',','',$_POST['isr'])):0;
$intPlanPensiones= isset($_POST['plan_pensiones']) ? floatval(str_replace(',','',$_POST['plan_pensiones'])):0;
$intJudiciales= isset($_POST['judiciales']) ? floatval(str_replace(',','',$_POST['judiciales'])):0;
$intOtrosDescuentosFha= isset($_POST['otros_descuentos_fha']) ? floatval(str_replace(',','',$_POST['otros_descuentos_fha'])):0;

$strMes1= isset($_POST['mes_1']) ? trim($_POST['mes_1']):'';
$intHoraExtraMes1= isset($_POST['hora_extra_mes_1']) ? floatval($_POST['hora_extra_mes_1']):0;
$intComisionesMes1= isset($_POST['comisiones_mes_1']) ? floatval($_POST['comisiones_mes_1']):0;
$intBonificacionesMes1= isset($_POST['bonificaciones_mes_1']) ? floatval($_POST['bonificaciones_mes_1']):0;

$strMes2= isset($_POST['mes_2']) ? trim($_POST['mes_2']):'';
$intHoraExtraMes2= isset($_POST['hora_extra_mes_2']) ? floatval($_POST['hora_extra_mes_2']):0;
$intComisionesMes2= isset($_POST['comisiones_mes_2']) ? floatval($_POST['comisiones_mes_2']):0;
$intBonificacionesMes2= isset($_POST['bonificaciones_mes_2']) ? floatval($_POST['bonificaciones_mes_2']):0;

$strMes3= isset($_POST['mes_3']) ? trim($_POST['mes_3']):'';
$intHoraExtraMes3= isset($_POST['hora_extra_mes_3']) ? floatval($_POST['hora_extra_mes_3']):0;
$intComisionesMes3= isset($_POST['comisiones_mes_3']) ? floatval($_POST['comisiones_mes_3']):0;
$intBonificacionesMes3= isset($_POST['bonificaciones_mes_3']) ? floatval($_POST['bonificaciones_mes_3']):0;

$strMes4= isset($_POST['mes_4']) ? trim($_POST['mes_4']):'';
$intHoraExtraMes4= isset($_POST['hora_extra_mes_4']) ? floatval($_POST['hora_extra_mes_4']):0;
$intComisionesMes4= isset($_POST['comisiones_mes_4']) ? floatval($_POST['comisiones_mes_4']):0;
$intBonificacionesMes4= isset($_POST['bonificaciones_mes_4']) ? floatval($_POST['bonificaciones_mes_4']):0;

$strMes5= isset($_POST['mes_5']) ? trim($_POST['mes_5']):'';
$intHoraExtraMes5= isset($_POST['hora_extra_mes_5']) ? floatval($_POST['hora_extra_mes_5']):0;
$intComisionesMes5= isset($_POST['comisiones_mes_5']) ? floatval($_POST['comisiones_mes_5']):0;
$intBonificacionesMes5= isset($_POST['bonificaciones_mes_5']) ? floatval($_POST['bonificaciones_mes_5']):0;

$strMes6= isset($_POST['mes_6']) ? trim($_POST['mes_6']):'';
$intHoraExtraMes6= isset($_POST['hora_extra_mes_6']) ? floatval($_POST['hora_extra_mes_6']):0;
$intComisionesMes6= isset($_POST['comisiones_mes_6']) ? floatval($_POST['comisiones_mes_6']):0;
$intBonificacionesMes6= isset($_POST['bonificaciones_mes_6']) ? floatval($_POST['bonificaciones_mes_6']):0;

$strEmpresa1= isset($_POST['empresa_1']) ? trim($_POST['empresa_1']):'';
$strCargo1= isset($_POST['cargo_1']) ? trim($_POST['cargo_1']):'';
$strDesde1= isset($_POST['desde_1']) ? trim($_POST['desde_1']):'';
$strHasta1= isset($_POST['hasta_1']) ? trim($_POST['hasta_1']):'';
$strEmpresa2= isset($_POST['empresa_2']) ? trim($_POST['empresa_2']):'';
$strCargo2= isset($_POST['cargo_2']) ? trim($_POST['cargo_2']):'';
$strDesde2= isset($_POST['desde_2']) ? trim($_POST['desde_2']):'';
$strHasta2= isset($_POST['hasta_2']) ? trim($_POST['hasta_2']):'';
$strEmpresa3= isset($_POST['empresa_3']) ? trim($_POST['empresa_3']):'';
$strCargo3= isset($_POST['cargo_3']) ? trim($_POST['cargo_3']):'';
$strDesde3= isset($_POST['desde_3']) ? trim($_POST['desde_3']):'';
$strHasta3= isset($_POST['hasta_3']) ? trim($_POST['hasta_3']):'';
$strEmpresa4= isset($_POST['empresa_4']) ? trim($_POST['empresa_4']):'';
$strCargo4= isset($_POST['cargo_4']) ? trim($_POST['cargo_4']):'';
$strDesde4= isset($_POST['desde_4']) ? trim($_POST['desde_4']):'';
$strHasta4= isset($_POST['hasta_4']) ? trim($_POST['hasta_4']):'';

$strNombreReferencia1= isset($_POST['nombre_referencia_1']) ? trim($_POST['nombre_referencia_1']):'';
$strParentescoReferencia1= isset($_POST['parentesco_referencia_1']) ? trim($_POST['parentesco_referencia_1']):'';
$strDomicilio1= isset($_POST['domicilio_1']) ? trim($_POST['domicilio_1']):'';
$strTelefono1= isset($_POST['telefono_1']) ? trim($_POST['telefono_1']):'';
$strTrabajo1= isset($_POST['trabajo_1']) ? trim($_POST['trabajo_1']):'';
$strTrabajoDireccion1= isset($_POST['trabajo_direccion_1']) ? trim($_POST['trabajo_direccion_1']):'';
$strTrabajoTelefono1= isset($_POST['trabajo_telefono_1']) ? trim($_POST['trabajo_telefono_1']):'';
$strNombreReferencia2= isset($_POST['nombre_referencia_2']) ? trim($_POST['nombre_referencia_2']):'';
$strParentescoReferencia2= isset($_POST['parentesco_referencia_2']) ? trim($_POST['parentesco_referencia_2']):'';
$strDomicilio2= isset($_POST['domicilio_2']) ? trim($_POST['domicilio_2']):'';
$strTelefono2= isset($_POST['telefono_2']) ? trim($_POST['telefono_2']):'';
$strTrabajo2= isset($_POST['trabajo_2']) ? trim($_POST['trabajo_2']):'';
$strTrabajoDireccion2= isset($_POST['trabajo_direccion_2']) ? trim($_POST['trabajo_direccion_2']):'';
$strTrabajoTelefono2= isset($_POST['trabajo_telefono_2']) ? trim($_POST['trabajo_telefono_2']):'';

$strBanco1= isset($_POST['banco_1']) ? trim($_POST['banco_1']):'';
$strTipoCuenta1= isset($_POST['tipo_cuenta_1']) ? trim($_POST['tipo_cuenta_1']):'';
$strNoCuenta1= isset($_POST['no_cuenta_1']) ? trim($_POST['no_cuenta_1']):'';
$intSaldoActual1= isset($_POST['saldo_actual_1']) ? floatval(str_replace(',','',$_POST['saldo_actual_1'])):0;
$strBanco2= isset($_POST['banco_2']) ? trim($_POST['banco_2']):'';
$strTipoCuenta2= isset($_POST['tipo_cuenta_2']) ? trim($_POST['tipo_cuenta_2']):'';
$strNoCuenta2= isset($_POST['no_cuenta_2']) ? trim($_POST['no_cuenta_2']):'';
$intSaldoActual2= isset($_POST['saldo_actual_2']) ? floatval(str_replace(',','',$_POST['saldo_actual_2'])):0;

$strBancoPrestamo1= isset($_POST['banco_prestamo_1']) ? trim($_POST['banco_prestamo_1']):'';
$strTipoPrestamo1= isset($_POST['tipo_prestamo_1']) ? trim($_POST['tipo_prestamo_1']):'';
$strNoPrestamo1= isset($_POST['no_prestamo_1']) ? trim($_POST['no_prestamo_1']):'';
$intmonto1= isset($_POST['monto_1']) ? floatval(str_replace(',','',$_POST['monto_1'])):0;
$intSaldoActualPrestamo1= isset($_POST['saldo_actual_prestamo_1']) ? floatval(str_replace(',','',$_POST['saldo_actual_prestamo_1'])):0;
$intPagoMensualPrestamo1= isset($_POST['pago_mensual_prestamo_1']) ? floatval(str_replace(',','',$_POST['pago_mensual_prestamo_1'])):0;
$strFechaVencimientoPrestamo1= isset($_POST['fecha_vencimiento_prestamo_1']) ? trim($_POST['fecha_vencimiento_prestamo_1']):'';
$strBancoPrestamo2= isset($_POST['banco_prestamo_2']) ? trim($_POST['banco_prestamo_2']):'';
$strTipoPrestamo2= isset($_POST['tipo_prestamo_2']) ? trim($_POST['tipo_prestamo_2']):'';
$strNoPrestamo2= isset($_POST['no_prestamo_2']) ? trim($_POST['no_prestamo_2']):'';
$intmonto2= isset($_POST['monto_2']) ? floatval(str_replace(',','',$_POST['monto_2'])):0;
$intSaldoActualPrestamo2= isset($_POST['saldo_actual_prestamo_2']) ? floatval(str_replace(',','',$_POST['saldo_actual_prestamo_2'])):0;
$intPagoMensualPrestamo2= isset($_POST['pago_mensual_prestamo_2']) ? floatval(str_replace(',','',$_POST['pago_mensual_prestamo_2'])):0;
$strFechaVencimientoPrestamo2= isset($_POST['fecha_vencimiento_prestamo_2']) ? trim($_POST['fecha_vencimiento_prestamo_2']):'';

$intParqueoExterno = isset($_POST['parqueo_externo']) ? intval($_POST['parqueo_externo']):0;
$strApartamentoParqueoExtra = isset($_POST['apartamentoNo']) ? trim($_POST['apartamentoNo']):'';

$strIdInspeccion = isset($_POST['idInspeccion']) ? trim($_POST['idInspeccion']):0;

//INSPECCION
$intIdEnganche = isset($_POST['idEnganche']) ? intval($_POST['idEnganche']):0;
$intPrecioFha = isset($_POST['precioFha']) ? floatval(str_replace(',','',$_POST['precioFha'])):0;
$intResguardo = isset($_POST['resguardo']) ? floatval(str_replace(',','',$_POST['resguardo'])):0;
$strInspeccion1 = isset($_POST['inspeccion_1']) ? trim($_POST['inspeccion_1']):'';
$strInspeccion2 = isset($_POST['inspeccion_2']) ? trim($_POST['inspeccion_2']):'';
$strInspeccion3 = isset($_POST['inspeccion_3']) ? trim($_POST['inspeccion_3']):'';
$strIngresoExpediente = isset($_POST['ingreso_expediente']) ? trim($_POST['ingreso_expediente']):'';

$intInspeccion1Monto = isset($_POST['inspeccion_1_monto']) ? floatval(str_replace(',','',$_POST['inspeccion_1_monto'])):0;
$intInspeccion2Monto = isset($_POST['inspeccion_2_monto']) ? floatval(str_replace(',','',$_POST['inspeccion_2_monto'])):0;
$intInspeccion3Monto = isset($_POST['inspeccion_3_monto']) ? floatval(str_replace(',','',$_POST['inspeccion_3_monto'])):0;
$intIngresoExpedienteMonto = isset($_POST['ingreso_expediente_monto']) ? floatval(str_replace(',','',$_POST['ingreso_expediente_monto'])):0;



$strProcesoFha = isset($_POST['procesoFha']) ? trim($_POST['procesoFha']):'';




if(isset($_GET['get_concidencia_cliente'])){
    $strFechaConsulta='';
    $strFechaConsultaRa='';
    if($strFechaInicial!=''){
        $strFechaConsulta =" AND ag.fechaCreacion >='{$strFechaInicial} 00:00:00' ";
    }
    if($strFechafinal!=''){
        $strFechaConsulta .=" AND ag.fechaCreacion <='{$strFechafinal} 23:59:59'";
    }
    if($strProyectoBuscar!='' && $strProyectoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.proyecto ='{$strProyectoBuscar}' ";
        $strFechaConsultaRa.= " AND proyecto ='{$strProyectoBuscar}' ";
    }
    if($strTorreBuscar!='' && $strTorreBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.torres ='{$strTorreBuscar}' ";
        $strFechaConsultaRa.= " AND torre ='{$strTorreBuscar}' ";
    }
    if($strNivelBuscar!='' && $strNivelBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.nivel ='{$strNivelBuscar}' ";
        $strFechaConsultaRa.= " AND nivel ='{$strNivelBuscar}' ";
    }
    if($strApartamentoBuscar!='' && $strApartamentoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.apartamento ='{$strApartamentoBuscar}' ";
        $strFechaConsultaRa.= " AND apartamento ='{$strApartamentoBuscar}' ";
    }
    $vendedorWhere='';
    if($id_perfil==1){
        $vendedorWhere= " AND (idVendedor = '{$id_usuario}' OR ag.idUsuarioCreado = '{$id_usuario}') ";
        $vendedorWhereRa= " AND idVendedor = '{$id_usuario}' ";
    }
    if($intEstadoDes!='1'){
        $union= "UNION 
                Select 0 as id, 'no' as creado,nombreCompleto as client_name,'' as apartamentoCotizacion,apartamento as apartamentoEnganche,'Sin Codigo' as codigo,proyecto,0,torre as torres,nivel
                FROM reservaApartamento
                WHERE desistido = 1
                AND (
                            nombreCompleto like '%{$strDatoBuscar}%' OR
                            numeroDpi like '%{$strDatoBuscar}%'
                    )
                    {$strFechaConsultaRa}
                    {$vendedorWhereRa}";
    }
    $strQuery = "SELECT distinct ag.idCliente as id, 'si' as creado, 
                    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,
                    '' as apartamentoCotizacion,
                    (select IFNULL(group_concat(apartamento),'') FROM enganche e WHERE e.idCliente = ag.idCliente and e.proyecto = en.proyecto) as apartamentoEnganche,
                    ag.codigo,IFNULL(proyecto,'') as proyecto,ag.estado,en.torres,nivel
                    FROM agregarCliente ag
                    LEFT JOIN enganche en ON ag.idCliente = en.idCLiente
                    WHERE ag.estado in ({$intEstadoDes}) 
                    AND( 
                            nombre_sa like '%{$strDatoBuscar}%' OR
                            primerNombre like '%{$strDatoBuscar}%' OR
                            segundoNombre like '%{$strDatoBuscar}%' OR
                            primerApellido like '%{$strDatoBuscar}%' OR
                            segundoApellido like '%{$strDatoBuscar}%' OR
                            correoElectronico like '%{$strDatoBuscar}%' OR
                            numeroDpi like '%{$strDatoBuscar}%' OR
                            codigo like '%{$strDatoBuscar}%'
                        )
                        {$strFechaConsulta}
                        {$vendedorWhere}
                        {$union}
                        ORDER BY proyecto,torres,nivel,apartamentoEnganche,client_name";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => $qTmp,
        "cotizaciones" => $arr
    );
}
if(isset($_GET['get_concidencia_cliente_fha'])){
    if($strFechaInicial!=''){
        $strFechaConsulta =" AND ag.fechaCreacion >='{$strFechaInicial} 00:00:00' ";
    }
    if($strFechafinal!=''){
        $strFechaConsulta .=" AND ag.fechaCreacion <='{$strFechafinal} 23:59:59'";
    }
    if($strProyectoBuscar!='' && $strProyectoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.proyecto ='{$strProyectoBuscar}' ";
    }
    if($strTorreBuscar!='' && $strTorreBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.torres ='{$strTorreBuscar}' ";
    }
    if($strNivelBuscar!='' && $strNivelBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.nivel ='{$strNivelBuscar}' ";
    }
    if($strApartamentoBuscar!='' && $strApartamentoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.apartamento ='{$strApartamentoBuscar}' ";
    }
    $vendedorWhere='';
    if($id_perfil==1){
        $vendedorWhere= " AND idVendedor = '{$id_usuario}' ";
    }
    $strQuery = "SELECT 	ag.idCliente as id,en.idEnganche, 'si' as creado, 
                    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,
                    en.apartamento as apartamentoEnganche,
                    ag.codigo,IFNULL(proyecto,'') as proyecto
                    FROM enganche en
                    INNER JOIN agregarCliente ag ON en.idCliente = ag.idCLiente
                    WHERE ag.estado in ({$intEstadoDes}) 
                    AND( 
                            nombre_sa like '%{$strDatoBuscar}%' OR
                            primerNombre like '%{$strDatoBuscar}%' OR
                            segundoNombre like '%{$strDatoBuscar}%' OR
                            primerApellido like '%{$strDatoBuscar}%' OR
                            segundoApellido like '%{$strDatoBuscar}%' OR
                            correoElectronico like '%{$strDatoBuscar}%' OR
                            numeroDpi like '%{$strDatoBuscar}%' OR
                            codigo like '%{$strDatoBuscar}%'
                        )
                        {$strFechaConsulta}
                        {$vendedorWhere}
                    ORDER BY en.proyecto,en.torres,en.nivel,en.apartamento,client_name";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => $qTmp,
        "cotizaciones" => $arr
    );
}
if(isset($_GET['get_concidencia_cliente_codeudor'])){
    if($strFechaInicial!=''){
        $strFechaConsulta =" AND ag.fechaCreacion >='{$strFechaInicial} 00:00:00' ";
    }
    if($strFechafinal!=''){
        $strFechaConsulta .=" AND ag.fechaCreacion <='{$strFechafinal} 23:59:59'";
    }
    if($strProyectoBuscar!='' && $strProyectoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.proyecto ='{$strProyectoBuscar}' ";
    }
    if($strTorreBuscar!='' && $strTorreBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.torres ='{$strTorreBuscar}' ";
    }
    if($strNivelBuscar!='' && $strNivelBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.nivel ='{$strNivelBuscar}' ";
    }
    if($strApartamentoBuscar!='' && $strApartamentoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND en.apartamento ='{$strApartamentoBuscar}' ";
    }
    $vendedorWhere='';
    if($id_perfil==1){
        $vendedorWhere= " AND idVendedor = '{$id_usuario}' ";
    }
    $strQuery = "SELECT distinct ag.idCliente as id,
                    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,
                    '' as apartamentoCotizacion,en.idEnganche,
                    en.apartamento as apartamentoEnganche,
                    ag.codigo,IFNULL(proyecto,'') as proyecto
                    FROM agregarCliente ag
                    INNER JOIN enganche en ON ag.idCliente = en.idCLiente
                    WHERE proyecto in ({$proyectos}) 
                    AND ag.estado = 1
                    AND( 
                            nombre_sa like '%{$strDatoBuscar}%' OR
                            primerNombre like '%{$strDatoBuscar}%' OR
                            segundoNombre like '%{$strDatoBuscar}%' OR
                            primerApellido like '%{$strDatoBuscar}%' OR
                            segundoApellido like '%{$strDatoBuscar}%' OR
                            correoElectronico like '%{$strDatoBuscar}%' OR
                            numeroDpi like '%{$strDatoBuscar}%' OR
                            codigo like '%{$strDatoBuscar}%'
                        )
                        {$strFechaConsulta}
                        {$vendedorWhere}
                    ORDER BY en.proyecto,en.torres,en.nivel,en.apartamento, client_name";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => $qTmp,
        "cotizaciones" => $arr
    );
}
if(isset($_GET['get_apartamento_lista'])){
    if($strFechaInicial!=''){
        $strFechaConsulta =" AND e.fechaCreacion >='{$strFechaInicial} 00:00:00' ";
    }
    if($strFechafinal!=''){
        $strFechaConsulta .=" AND e.fechaCreacion <='{$strFechafinal} 23:59:59' ";
    }
    if($strProyectoBuscar!='' && $strProyectoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND proyecto ='{$strProyectoBuscar}' ";
    }
    if($strTorreBuscar!='' && $strTorreBuscar!='Seleccione'){
        $strFechaConsulta.= " AND torres ='{$strTorreBuscar}' ";
    }
    if($strNivelBuscar!='' && $strNivelBuscar!='Seleccione'){
        $strFechaConsulta.= " AND nivel ='{$strNivelBuscar}' ";
    }
    if($strApartamentoBuscar!='' && $strApartamentoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND apartamento ='{$strApartamentoBuscar}' ";
    }
    $strQuery = "SELECT ac.codigo,e.apartamento,e.proyecto,                     
                    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,
                    e.apartamento,e.idEnganche as id
                    FROM enganche e
                    INNER JOIN agregarCliente ac ON e.idCliente = ac.idCLiente AND ac.estado in ({$intEstadoDes})
                    WHERE proyecto in ({$proyectos}) 
                    AND
                    ( 
                            nombre_sa like '%{$strDatoBuscar}%' OR
                            primerNombre like '%{$strDatoBuscar}%' OR
                            segundoNombre like '%{$strDatoBuscar}%' OR
                            primerApellido like '%{$strDatoBuscar}%' OR
                            segundoApellido like '%{$strDatoBuscar}%' OR
                            correoElectronico like '%{$strDatoBuscar}%' OR
                            numeroDpi like '%{$strDatoBuscar}%' OR
                            apartamento like '%{$strDatoBuscar}%'
                        )
                     AND validado = 1
                        {$strFechaConsulta}
                    ORDER BY proyecto,torres,nivel, apartamento, client_name ASC";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => $qTmp,
        "cotizaciones" => $arr
    );
}
if(isset($_GET['get_apartamento_lista_reserva'])){
   
    if($strProyectoBuscar!='' && $strProyectoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND proyecto ='{$strProyectoBuscar}' ";
    }
    if($strTorreBuscar!='' && $strTorreBuscar!='Seleccione'){
        $strFechaConsulta.= " AND torre ='{$strTorreBuscar}' ";
    }
    if($strNivelBuscar!='' && $strNivelBuscar!='Seleccione'){
        $strFechaConsulta.= " AND nivel ='{$strNivelBuscar}' ";
    }
    if($strApartamentoBuscar!='' && $strApartamentoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND apartamento ='{$strApartamentoBuscar}' ";
    }
    $vendedorWhere='';
    if($id_perfil==1){
        $vendedorWhere= " AND idVendedor = '{$id_usuario}' ";
    }
    $strQuery = "SELECT ra.*, date_format(fechaPagoReserva, '%d-%m-%Y') as fechaPagoReservaFormat,
                    CASE
                        WHEN validado = 0 then 'Pendiente de Validar'
                        else 'Validado'
                    END  as estado
                    FROM reservaApartamento ra
                    WHERE proyecto in ({$proyectos}) 
                    AND
                    ( 
                            nombreCompleto like '%{$strDatoBuscar}%' OR
                            apartamento like '%{$strDatoBuscar}%'
                        )
                     AND estado = 1
                     AND desistido = 0
                        {$strFechaConsulta}
                        {$vendedorWhere}
                    ORDER BY validado ASC, proyecto,torre,nivel, apartamento, nombreCompleto ASC";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => $qTmp,
        "cotizaciones" => $arr
    );
}
if(isset($_GET['get_apartamento_lista_validar'])){
    if($strFechaInicial!=''){
        $strFechaConsulta =" AND e.fechaCreacion >='{$strFechaInicial} 00:00:00' ";
    }
    if($strFechafinal!=''){
        $strFechaConsulta .=" AND e.fechaCreacion <='{$strFechafinal} 23:59:59' ";
    }
    if($strProyectoBuscar!='' && $strProyectoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND proyecto ='{$strProyectoBuscar}' ";
    }
    if($strTorreBuscar!='' && $strTorreBuscar!='Seleccione'){
        $strFechaConsulta.= " AND torres ='{$strTorreBuscar}' ";
    }
    if($strNivelBuscar!='' && $strNivelBuscar!='Seleccione'){
        $strFechaConsulta.= " AND nivel ='{$strNivelBuscar}' ";
    }
    if($strApartamentoBuscar!='' && $strApartamentoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND apartamento ='{$strApartamentoBuscar}' ";
    }
    $strQuery = "SELECT ac.codigo,e.apartamento,e.proyecto,                     
                    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,
                    e.apartamento,e.idEnganche as id
                    FROM enganche e
                    INNER JOIN agregarCliente ac ON e.idCliente = ac.idCLiente AND ac.estado = 1
                    WHERE proyecto in ({$proyectos}) 
                    AND( 
                            nombre_sa like '%{$strDatoBuscar}%' OR
                            primerNombre like '%{$strDatoBuscar}%' OR
                            segundoNombre like '%{$strDatoBuscar}%' OR
                            primerApellido like '%{$strDatoBuscar}%' OR
                            segundoApellido like '%{$strDatoBuscar}%' OR
                            correoElectronico like '%{$strDatoBuscar}%' OR
                            numeroDpi like '%{$strDatoBuscar}%' OR
                            apartamento like '%{$strDatoBuscar}%'
                        )
                    AND validado = 0
                        {$strFechaConsulta}
                    ORDER BY apartamento,client_name ASC";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => $qTmp,
        "cotizaciones" => $arr
    );
}
if(isset($_GET['get_cotizaciones_lista'])){
    if($strFechaInicial!=''){
        $strFechaConsulta =" AND fechaCreacion >='{$strFechaInicial} 00:00:00' ";
    }
    if($strFechafinal!=''){
        $strFechaConsulta .=" AND fechaCreacion <='{$strFechafinal} 23:59:59' ";
    }
    if($strProyectoBuscar!='' && $strProyectoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND proyecto ='{$strProyectoBuscar}' ";
    }
    if($strTorreBuscar!='' && $strTorreBuscar!='Seleccione'){
        $strFechaConsulta.= " AND torres ='{$strTorreBuscar}' ";
    }
    if($strNivelBuscar!='' && $strNivelBuscar!='Seleccione'){
        $strFechaConsulta.= " AND nivel ='{$strNivelBuscar}' ";
    }
    if($strApartamentoBuscar!='' && $strApartamentoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND apartamento ='{$strApartamentoBuscar}' ";
    }
    $vendedorWhere='';
    if($id_perfil==1){
        $vendedorWhere= " AND idVendedor = '{$id_usuario}' ";
    }
    $strQuery = "SELECT idCotizacion,nombreCompleto,correo,telefono,apartamento,proyecto
                    FROM cotizacion c
                    WHERE proyecto in ({$proyectos}) 
                    AND
                    ( 
                            nombreCompleto like '%{$strDatoBuscar}%' OR
                            correo like '%{$strDatoBuscar}%' OR
                            telefono like '%{$strDatoBuscar}%' OR
                            apartamento like '%{$strDatoBuscar}%'
                        )
                        {$strFechaConsulta}
                        {$vendedorWhere}
                    ORDER BY proyecto,torres,nivel, apartamento asc,nombreCompleto ASC,proyecto ASC, idCotizacion ASC";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => $qTmp,
        "cotizaciones" => $arr
    );
}
if(isset($_GET['get_apartamento_lista_estado_cuenta'])){
    if($strFechaInicial!=''){
        $strFechaConsulta =" AND e.fechaCreacion >='{$strFechaInicial}' 00:00:00 ";
    }
    if($strFechafinal!=''){
        $strFechaConsulta .=" AND e.fechaCreacion <='{$strFechafinal}'23:59:59 ";
    }
    if($strProyectoBuscar!='' && $strProyectoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND e.proyecto ='{$strProyectoBuscar}' ";
    }
    if($strTorreBuscar!='' && $strTorreBuscar!='Seleccione'){
        $strFechaConsulta.= " AND e.torres ='{$strTorreBuscar}' ";
    }
    if($strNivelBuscar!='' && $strNivelBuscar!='Seleccione'){
        $strFechaConsulta.= " AND e.nivel ='{$strNivelBuscar}' ";
    }
    if($strApartamentoBuscar!='' && $strApartamentoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND e.apartamento ='{$strApartamentoBuscar}' ";
    }
    //$hoy=date('Y-m-d');
    $hoy = date("d-m-Y");
    //resta 5 dias
    $hoy = date("Y-m-d",strtotime($hoy."- 0 days")); 
    $vendedorWhere='';
    if($id_perfil==1){
        $vendedorWhere= " AND e.idVendedor = '{$id_usuario}' ";
    }
    $strQuery = "   SELECT ac.codigo,e.proyecto, ac.estado,
                    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,
                    e.apartamento,e.idEnganche as id,
                    (SELECT ped.fechaPago FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 OR validado=0) ORDER BY ped.fechaPago ASC LIMIT 1) as fechaParaPago,
                    (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 OR validado=0) AND fechaPago <'{$hoy}') as pagosAtrasados,
                    (SELECT
                        GROUP_CONCAT(montoReal)
                        FROM prograEngancheDetalle ped
                    where
                        ped.idEnganche = e.idEnganche
                        and(pagado = 0
                            OR validado = 0)
                        AND fechaPago<'{$hoy}')as pagosAtrasados_2,
                    (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}') as pagosVencidos,
                    (SELECT DATEDIFF(NOW(),fechaPago) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 OR validado=0) ORDER BY ped.fechaPago ASC LIMIT 1) as dias,
                    ((SELECT if(e.apartamento='4D',(6102.99 + IFNULL(SUM(montoPagado),0)),IFNULL(SUM(montoPagado),0) ) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND validado = 1)) totalPagado,                    
                    e.enganchePorcMonto,
                    e.pagosEnganche,
                    ( ROUND(((e.enganchePorcMonto - e.MontoReserva)/e.pagosEnganche),2) ) cuotas,
                    ( ROUND(((e.enganchePorcMonto- e.MontoReserva)/e.pagosEnganche),2) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}')) as debePagar,
                    ((SELECT SUM(ped.montoReal)FROM prograEngancheDetalle ped where ped.idEnganche=e.idEnganche AND fechaPago<'{$hoy}'))as debePagar_2,
                    ( ( ((e.enganchePorcMonto - (SELECT SUM(monto) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) ) - e.MontoReserva)/(e.pagosEnganche - (SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) )) ) cuotasSinEspecial,
                    ( ((e.enganchePorcMonto  - (SELECT SUM(monto) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1)- e.MontoReserva)/(e.pagosEnganche -  (SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1)) ) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}')) as debePagarSinEspecial,
                    (case 
                    when 
                    (SELECT DATEDIFF(NOW(),fechaPago) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 OR validado=0) ORDER BY ped.fechaPago ASC LIMIT 1) <= 0 then 0
                    when (SELECT if(e.apartamento='4D',(6102.99 + IFNULL(SUM(montoPagado),0)),IFNULL(SUM(montoPagado),0) ) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND validado=1) >= ((e.enganchePorcMonto- e.MontoReserva)/e.pagosEnganche) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}') then 0
                    else  
                    if(( ( ((e.enganchePorcMonto - (SELECT SUM(monto) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) ) - e.MontoReserva)/(e.pagosEnganche - (SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) )) ) >0 AND ( ( ((e.enganchePorcMonto - (SELECT SUM(monto) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) ) - e.MontoReserva)/(e.pagosEnganche - (SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) )) ) < ( ((e.enganchePorcMonto - e.MontoReserva)/e.pagosEnganche) ) ,

                        (
                            ( 
                                (
                                    (
                                        e.enganchePorcMonto  - (SELECT IFNULL(SUM(monto),0) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1)- e.MontoReserva
                                    )/
                                    (
                                        e.pagosEnganche -  
                                        (
                                            SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1
                                        )
                                    ) 
                                ) * 
                                (
                                    SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}'
                                )
                            )-
                            (
                                SELECT if(e.apartamento='4D',(6102.99 + IFNULL(SUM(montoPagado),0)),IFNULL(SUM(montoPagado),0) ) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1
                            )
                        )/
                        ( 
                            ( 
                                (
                                    (
                                        e.enganchePorcMonto - 
                                        (
                                            SELECT IFNULL(SUM(monto),0) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1
                                        ) 
                                    ) - 
                                    e.MontoReserva
                                )/
                                (
                                    e.pagosEnganche - 
                                    (
                                        SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1
                                    ) 
                                )
                            ) 
                        ),
                        (
                            (
                                (
                                    (
                                        e.enganchePorcMonto- e.MontoReserva
                                    )/
                                    e.pagosEnganche
                                ) * 
                                (
                                    SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}'
                                ) - 
                                (
                                    SELECT if(e.apartamento='4D',(6102.99 + IFNULL(SUM(montoPagado),0)),IFNULL(SUM(montoPagado),0) ) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1
                                )
                            ) / 
                            (
                                (
                                    e.enganchePorcMonto - e.MontoReserva
                                )/e.pagosEnganche
                            )
                        )
                    )
                    end) as orden,
                    (
                        CASE
                            WHEN ac.codigo in('2021-11','2021-9','2021-33') then 1
                            else 0
                        END
                    )as ordenDes 
                    FROM enganche e
                    INNER JOIN agregarCliente ac ON e.idCliente = ac.idCLiente AND ac.estado in ({$intEstadoDes})  
                    INNER JOIN prograEnganche pe ON e.idEnganche = pe.idEnganche
                    WHERE proyecto in ({$proyectos}) 
                    AND( 
                            primerNombre like '%{$strDatoBuscar}%' OR
                            segundoNombre like '%{$strDatoBuscar}%' OR
                            primerApellido like '%{$strDatoBuscar}%' OR
                            segundoApellido like '%{$strDatoBuscar}%' OR
                            correoElectronico like '%{$strDatoBuscar}%' OR
                            numeroDpi like '%{$strDatoBuscar}%' OR
                            apartamento like '%{$strDatoBuscar}%'
                        )
                        {$strFechaConsulta}
                        {$vendedorWhere}
                        ORDER BY ordenDes DESC, orden DESC,proyecto,apartamento ";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    $aux = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $pendientePagar = $rTmp->debePagar_2 - $rTmp->totalPagado;
        $pendienteTmp = 0;
        $pagosAtrasados = 0;
        $arrPagosPendientes = explode(",",$rTmp->pagosAtrasados_2);
        $countArrPagosPendientes = count($arrPagosPendientes);
        if($pendientePagar>0){
            $pendienteTmp = $pendientePagar;
            while($pendienteTmp > 0 && $countArrPagosPendientes!=0){
                $pendienteTmp -= $arrPagosPendientes[$pagosAtrasados];
                $pagosAtrasados++;
                $countArrPagosPendientes--;
            } 
        }
        $rTmp->pagosAtrasadosReales = $pagosAtrasados;
        $arr[] = $rTmp;
    }
    foreach ($arr as $key => $row) {
        $aux[$key] = $row->pagosAtrasadosReales;
    }
    array_multisort($aux, SORT_DESC, $arr);
    
    $res = array(
        "err" => false,
        "mss" => $qTmp,
        "cotizaciones" => $arr
    );
}
if(isset($_GET['get_apartamento_lista_inspecciones'])){

    if($strProyectoBuscar!='' && $strProyectoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND d.proyecto ='{$strProyectoBuscar}' ";
    }
    if($strTorreBuscar!='' && $strTorreBuscar!='Seleccione'){
        $strFechaConsulta.= " AND a.torres ='{$strTorreBuscar}' ";
    }
    if($strNivelBuscar!='' && $strNivelBuscar!='Seleccione'){
        $strFechaConsulta.= " AND a.nivel ='{$strNivelBuscar}' ";
    }
    if($strApartamentoBuscar!='' && $strApartamentoBuscar!='Seleccione'){
        $strFechaConsulta.= " AND a.apartamento ='{$strApartamentoBuscar}' ";
    }
    //$hoy=date('Y-m-d');
    $hoy = date("d-m-Y");
    //resta 5 dias
    $hoy = date("Y-m-d",strtotime($hoy."- 0 days")); 
    $vendedorWhere='';
    if($id_perfil==1){
        $vendedorWhere= " AND e.idVendedor = '{$id_usuario}' ";
    }
    $strQuery = "   SELECT a.apartamento, IFNULL(a.precioFha,0) as precioFha ,IFNULL(a.resguardo,0) as resguardo, IFNULL(e.descuento_porcentual_monto,0) as descuento_porcentual_monto,
                    IFNULL((parqueosExtras * d.parqueoExtra),0) as montoParqueo ,
                    IFNULL((parqueosExtrasMoto * d.parqueoExtraMoto),0) as montoParqueoMoto ,
                    IFNULL((bodegasExtras * a.bodega_precio),0) as montoBodega ,
                    a.precio, inspeccion1, inspeccion2, inspeccion3, ingresoExpediente, inspeccion1_monto, inspeccion2_monto, inspeccion3_monto, ingresoExpediente_monto
                    FROM apartamentos a 
                    LEFT JOIN enganche e ON a.apartamento = e.apartamento AND e.enganchePorc <= 20 AND e.idCliente = (SELECT ag.idCliente FROM agregarCliente ag WHERE ag.estado = 1 AND ag.idCliente = e.idCliente)
                    INNER JOIN datosGlobales d ON a.idProyecto = d.idGlobal
                    WHERE d.proyecto in ({$proyectos}) 
                    {$strFechaConsulta}
                    ORDER BY d.proyecto, a.apartamento ASC ";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => $qTmp,
        "cotizaciones" => $arr
    );
}

if(isset($_GET['get_concidencia_cliente_unico'])){

    $strSelect='';
    $strFrom='';
    $arrPatrimonial = array();
    $arrDetallePatrimonial = array();
    $arrIngresosEgresos = array();
    $arrDetalleComisiones = array();
    $arrHistorialLaboral = array();
    $arrRefFamiliar = array();
    $arrRefBancarias = array();
    $arrRefCrediticias = array();
    
    $strQuery = "SELECT ag.*, a.*,e.*,(dg.cambioDolar * precio) as price,ag.*,cp.pais as NacionalidadNombre, 
                    IF(tipoCliente='individual', 
                     CONCAT(if(ag.primerNombre='','',IFNULL(CONCAT(ag.primerNombre,' '),'')),if(ag.segundoNombre='','',IFNULL(CONCAT(ag.segundoNombre,' '),'')),if(ag.tercerNombre='','',IFNULL(CONCAT(ag.tercerNombre,' '),'')),if(ag.primerApellido='','',IFNULL(CONCAT(ag.primerApellido,' '),'')),
                    if(ag.segundoApellido='','',IFNULL(CONCAT(ag.segundoApellido,' '),'')),if(ag.apellidoCasada='','',IFNULL(CONCAT(ag.apellidoCasada,' '),''))), nombre_sa)  as client_name,
                ag.correoElectronico as client_mail,ag.observaciones as observaciones_cliente,
                u.mail as mailVendedor ,u.telefono as telefonoVendedor,
                CONCAT(IFNULL(CONCAT(u.primer_nombre,' '),''),IFNULL(CONCAT(u.segundo_nombre,' '),''),IFNULL(CONCAT(u.tercer_nombre,' '),''),IFNULL(CONCAT(u.primer_apellido,' '),''),
                IFNULL(CONCAT(u.segundo_apellido,' '),''),IFNULL(CONCAT(u.apellido_casada,' '),'')) as nombreVendedor,
                    0 as contracargo,
                    0 as contracargoEnganche,
                    ((dg.cambioDolar * dg.parqueoExtra)*e.parqueosExtras) as parqueoExtraMonto,
                    ((dg.cambioDolar * a.bodega_precio) * bodegasExtras) as bodegaPrecioMonto,
                    CONCAT(IFNULL(CONCAT(c.primerNombre,' '),''),IFNULL(CONCAT(c.segundoNombre,' '),''),IFNULL(CONCAT(c.tercerNombre,' '),''),IFNULL(CONCAT(c.primerApellido,' '),''),
                    IFNULL(CONCAT(c.segundoApellido,' '),''),IFNULL(CONCAT(c.apellidoCasada,' '),''))  as client_name_codeudor,dg.iusi,dg.porcentajeFacturacion
                    
                FROM agregarCliente ag
                LEFT JOIN enganche e ON ag.idCliente = e.idCliente 
                LEFT JOIN apartamentos a ON e.apartamento = a.apartamento
                LEFT JOIN catPais cp ON ag.Nacionalidad = cp.id_pais
                LEFT JOIN datosGlobales dg ON e.proyecto = dg.proyecto
                LEFT JOIN usuarios u ON e.idVendedor = u.id_usuario
                LEFT JOIN codeudor c ON e.idEnganche = c.idEnganche
                WHERE  ag.idCliente = {$intIdCotizacion}";
    
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $strQueryPatrimonial = " SELECT * FROM infoPatrimonial
                            WHERE  idCliente = {$intIdCotizacion}";
    
    //echo $strQuery;
    $qTmpPatrimonial = $conn ->db_query($strQueryPatrimonial);
    while ($rTmpPatrimonial = $conn->db_fetch_object($qTmpPatrimonial)){
        $arrPatrimonial[] = $rTmpPatrimonial;
    }

    $strQueryDetallePatrimonial = " SELECT dp.*,cd1.nombre_depto as departamento_nombre_1,cd2.nombre_depto as departamento_nombre_2 FROM detallePatrimonial dp
                                    LEFT JOIN catDepartamento cd1 ON dp.departamento_1 = cd1.id_depto
                                    LEFT JOIN catDepartamento cd2 ON dp.departamento_2 = cd2.id_depto
                            WHERE  idCliente = {$intIdCotizacion}";
    
    //echo $strQuery;
    $qTmpDetallePatrimonial = $conn ->db_query($strQueryDetallePatrimonial);
    while ($rTmpDetallePatrimonial = $conn->db_fetch_object($qTmpDetallePatrimonial)){
        $arrDetallePatrimonial[] = $rTmpDetallePatrimonial;
    }
    $strQueryIngresosEgresos = " SELECT * FROM detalleIngresosDescuentosMensuales
                            WHERE  idCliente = {$intIdCotizacion}";
    
    //echo $strQuery;
    $qTmpIngresosEgresos = $conn ->db_query($strQueryIngresosEgresos);
    while ($rTmpIngresosEgresos = $conn->db_fetch_object($qTmpIngresosEgresos)){
        $arrIngresosEgresos[] = $rTmpIngresosEgresos;
    } 
    $strQueryDetalleComisiones = " SELECT * FROM detalleComisiones
                            WHERE  idCliente = {$intIdCotizacion}";
    
    //echo $strQuery;
    $qTmpDetalleComisiones = $conn ->db_query($strQueryDetalleComisiones);
    while ($rTmpDetalleComisiones = $conn->db_fetch_object($qTmpDetalleComisiones)){
        $arrDetalleComisiones[] = $rTmpDetalleComisiones;
    }
    
    $strQueryHistorialLaboral = " SELECT * FROM historialLaboral
                            WHERE  idCliente = {$intIdCotizacion}";
    
    //echo $strQuery;
    $qTmpHistorialLaboral = $conn ->db_query($strQueryHistorialLaboral);
    while ($rTmpHistorialLaboral = $conn->db_fetch_object($qTmpHistorialLaboral)){
        $arrHistorialLaboral[] = $rTmpHistorialLaboral;
    }

    $strQueryRefFamiliar = " SELECT * FROM refFamiliar
                            WHERE  idCliente = {$intIdCotizacion}";
    
    //echo $strQuery;
    $qTmpRefFamiliar = $conn ->db_query($strQueryRefFamiliar);
    while ($rTmpRefFamiliar = $conn->db_fetch_object($qTmpRefFamiliar)){
        $arrRefFamiliar[] = $rTmpRefFamiliar;
    }

    $strQueryRefBancarias = " SELECT * FROM refbancarias
    WHERE  idCliente = {$intIdCotizacion}";

    //echo $strQuery;
    $qTmpRefBancarias = $conn ->db_query($strQueryRefBancarias);
    while ($rTmpRefBancarias = $conn->db_fetch_object($qTmpRefBancarias)){
        $arrRefBancarias[] = $rTmpRefBancarias;
    }

    $strQueryRefCrediticias = " SELECT * FROM refCrediticias
    WHERE  idCliente = {$intIdCotizacion}";

    //echo $strQuery;
    $qTmpRefCrediticias = $conn ->db_query($strQueryRefCrediticias);
    while ($rTmpRefCrediticias = $conn->db_fetch_object($qTmpRefCrediticias)){
        $arrRefCrediticias[] = $rTmpRefCrediticias;
    }

    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "info" => $arr,
        "infoPatrimonial" => $arrPatrimonial,
        "infoDetallePatrimonial" => $arrDetallePatrimonial,
        "infoIngresosEgresos" => $arrIngresosEgresos,
        "infoDetalleComisiones" => $arrDetalleComisiones,
        "infoHistorialLaboral" => $arrHistorialLaboral,
        "infoRefFamiliar" => $arrRefFamiliar,
        "infoRefBancarias" => $arrRefBancarias,
        "infoRefCrediticias" => $arrRefCrediticias

    );
}
if(isset($_GET['get_codeudores'])){

    $arrPatrimonial = array();
    $arrDetallePatrimonial = array();
    $arrIngresosEgresos = array();
    $arrDetalleComisiones = array();
    $arrHistorialLaboral = array();
    $arrRefFamiliar = array();
    $arrRefBancarias = array();
    $arrRefCrediticias = array();

    $strSelect='';
    $strFrom='';
    $arr = array();
    $strQuery = "SELECT ag.*,cp.pais as NacionalidadNombre, 
                        CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),''))  as client_name,
                ag.correoElectronico as client_mail
                FROM codeudor ag
                LEFT JOIN catPais cp ON ag.Nacionalidad = cp.id_pais
                WHERE ag.idCliente = {$intIdClienteCo}
                AND ag.idEnganche = {$intIdEngancheCo}";
    
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
        $idCodeudor = $rTmp->idCodeudor;
    }   
    
    $strQueryPatrimonial = " SELECT * FROM infoPatrimonialCo
                            WHERE  idCliente = {$idCodeudor}";
    
    //echo $strQuery;
    $qTmpPatrimonial = $conn ->db_query($strQueryPatrimonial);
    while ($rTmpPatrimonial = $conn->db_fetch_object($qTmpPatrimonial)){
        $arrPatrimonial[] = $rTmpPatrimonial;
    }

    $strQueryDetallePatrimonial = " SELECT dp.*,cd1.nombre_depto as departamento_nombre_1,cd2.nombre_depto as departamento_nombre_2 FROM detallePatrimonialCo dp
                                    LEFT JOIN catDepartamento cd1 ON dp.departamento_1 = cd1.id_depto
                                    LEFT JOIN catDepartamento cd2 ON dp.departamento_2 = cd2.id_depto
                            WHERE  idCliente = {$idCodeudor}";
    
    //echo $strQuery;
    $qTmpDetallePatrimonial = $conn ->db_query($strQueryDetallePatrimonial);
    while ($rTmpDetallePatrimonial = $conn->db_fetch_object($qTmpDetallePatrimonial)){
        $arrDetallePatrimonial[] = $rTmpDetallePatrimonial;
    }
        $strQueryIngresosEgresos = " SELECT * FROM detalleIngresosDescuentosMensualesCo
                            WHERE  idCliente = {$idCodeudor}";
    
    //echo $strQuery;
    $qTmpIngresosEgresos = $conn ->db_query($strQueryIngresosEgresos);
    while ($rTmpIngresosEgresos = $conn->db_fetch_object($qTmpIngresosEgresos)){
        $arrIngresosEgresos[] = $rTmpIngresosEgresos;
    } 
    $strQueryDetalleComisiones = " SELECT * FROM detalleComisionesCo
                            WHERE  idCliente = {$idCodeudor}";
    
    //echo $strQuery;
    $qTmpDetalleComisiones = $conn ->db_query($strQueryDetalleComisiones);
    while ($rTmpDetalleComisiones = $conn->db_fetch_object($qTmpDetalleComisiones)){
        $arrDetalleComisiones[] = $rTmpDetalleComisiones;
    }
    
    $strQueryHistorialLaboral = " SELECT * FROM historialLaboralCo
                            WHERE  idCliente = {$idCodeudor}";
    
    //echo $strQuery;
    $qTmpHistorialLaboral = $conn ->db_query($strQueryHistorialLaboral);
    while ($rTmpHistorialLaboral = $conn->db_fetch_object($qTmpHistorialLaboral)){
        $arrHistorialLaboral[] = $rTmpHistorialLaboral;
    }

    $strQueryRefFamiliar = " SELECT * FROM refFamiliarCo
                            WHERE  idCliente = {$idCodeudor}";
    
    //echo $strQuery;
    $qTmpRefFamiliar = $conn ->db_query($strQueryRefFamiliar);
    while ($rTmpRefFamiliar = $conn->db_fetch_object($qTmpRefFamiliar)){
        $arrRefFamiliar[] = $rTmpRefFamiliar;
    }

    $strQueryRefBancarias = " SELECT * FROM refbancariasCo
    WHERE  idCliente = {$idCodeudor}";

    //echo $strQuery;
    $qTmpRefBancarias = $conn ->db_query($strQueryRefBancarias);
    while ($rTmpRefBancarias = $conn->db_fetch_object($qTmpRefBancarias)){
        $arrRefBancarias[] = $rTmpRefBancarias;
    }

    $strQueryRefCrediticias = " SELECT * FROM refCrediticiasCo
    WHERE  idCliente = {$idCodeudor}";

    //echo $strQuery;
    $qTmpRefCrediticias = $conn ->db_query($strQueryRefCrediticias);
    while ($rTmpRefCrediticias = $conn->db_fetch_object($qTmpRefCrediticias)){
        $arrRefCrediticias[] = $rTmpRefCrediticias;
    }
    
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "info" => $arr,
        "infoPatrimonial" => $arrPatrimonial,
        "infoDetallePatrimonial" => $arrDetallePatrimonial,
        "infoIngresosEgresos" => $arrIngresosEgresos,
        "infoDetalleComisiones" => $arrDetalleComisiones,
        "infoHistorialLaboral" => $arrHistorialLaboral,
        "infoRefFamiliar" => $arrRefFamiliar,
        "infoRefBancarias" => $arrRefBancarias,
        "infoRefCrediticias" => $arrRefCrediticias
    );
}
if(isset($_GET['get_vendedores'])){

    $strQuery = "SELECT id_usuario,CONCAT(IFNULL(CONCAT(primer_nombre,' '),''),IFNULL(CONCAT(segundo_nombre,' '),''),IFNULL(CONCAT(tercer_nombre,' '),''),IFNULL(CONCAT(primer_apellido,' '),''),
                    IFNULL(CONCAT(segundo_apellido,' '),''),IFNULL(CONCAT(apellido_casada,' '),'')) as nombreVendedor 
                    FROM usuarios 
                    where -- id_perfil = 1 AND 
                    suspendido = 0
                    ORDER by id_usuario DESC;";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "listado_usuarios" => $arr
    );
}
if(isset($_GET['get_enganche'])){

    $strSelect='';
    $strFrom='';
    $arr = array();
    $strQuery = "SELECT e.*,dg.idGlobal,t.idTorre,n.idNivel,a.idApartamento,cp.pais as NacionalidadNombre,
                    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,
                    ag.correoElectronico as client_mail,ag.*
                    FROM enganche e 
                    INNER JOIN datosGlobales dg ON e.proyecto = dg.proyecto
                    INNER JOIN torres t ON dg.idGlobal = t.proyecto AND t.noTorre = e.torres
                    INNER JOIN niveles n ON t.idTorre = n.idTorre AND n.noNivel = e.nivel
                    INNER JOIN apartamentos a ON n.idNivel= a.idNivel AND a.apartamento = e.apartamento
                    INNER JOIN agregarCliente ag ON e.idCliente = ag.idCliente AND ag.estado = 1
                    LEFT JOIN catPais cp ON ag.Nacionalidad = cp.id_pais
                    WHERE e.idEnganche = {$intIdEnganche }";
    
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }    
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "info" => $arr
    );
}
if(isset($_GET['get_formalizar_enganche'])){

    $strSelect='';
    $strFrom='';
    $arr = array();
    $strQuery = "SELECT e.*,IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name
                    FROM enganche e 
                    INNER JOIN agregarCliente ac ON e.idCliente = ac.idCliente AND ac.estado = 1
                    WHERE e.idEnganche = {$intIdEnganche }";
    
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }    
    $strQueryEn = "SELECT ac.codigo,e.apartamento, e.pagoPromesa,date_format(fechaPagoReserva, '%d-%m-%Y') as fechaPagoReservaFormat,e.MontoReserva,e.descuento_porcentual_monto,(dg.cambioDolar * a.precio) as precio,
                    ((dg.cambioDolar * dg.parqueoExtra)*e.parqueosExtras) as parqueoExtra,((dg.cambioDolar * a.bodega_precio) * bodegasExtras) as bodegaPrecio,e.enganchePorcMonto,
                    ifnull(date_format(pf.fechaPago, '%d-%m-%Y'),'') as fechaPagoFinalFormat,
                    (SELECT ifnull((SUM(case when accion ='adicionar' then monto else 0 end) -  SUM(case when accion ='deducir' then monto else 0 end)),0) as contracargo FROM contrapagos cp where cp.idEnganche = e.idEnganche AND enganche = 0  ) contracargo,

                    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name
                FROM enganche e
                INNER JOIN apartamentos a ON e.apartamento = a.apartamento
                INNER JOIN datosGlobales dg ON a.idProyecto = dg.idGlobal
                LEFT JOIN pagoFinal pf ON e.idEnganche = pf.idEnganche
                INNER JOIN agregarCliente ac ON e.idCliente = ac.idCliente AND ac.estado = 1
                WHERE e.idEnganche = {$intIdEnganche}
                limit 1 ;";

        //echo $strQuery;
        $qTmp = $conn ->db_query($strQueryEn);
        $rTmp = $conn->db_fetch_object($qTmp);
        $reserva = $rTmp->MontoReserva;
        $contracargo = $rTmp->contracargo;
        $promesa = $rTmp->pagoPromesa;
        $fechaPagoReservaFormat = $rTmp->fechaPagoReservaFormat;
        $fechaPagoReservaFormat = $rTmp->fechaPagoReservaFormat;
        $fechaPagoFinalFormat = $rTmp->fechaPagoFinalFormat;
        $descuento = $rTmp->descuento_porcentual_monto;
        $precio = $rTmp->precio;
        $parqueo = $rTmp->parqueoExtra;
        $bodega = $rTmp->bodegaPrecio;
        $enganchePorcMonto = $rTmp->enganchePorcMonto;
        $codigo = $rTmp->codigo;
        $apartamento = $rTmp->apartamento;
        $nombreCliente = $rTmp->client_name;
    $res = array(
        "err" => false,
        "reserva" => $reserva ,
        "contracargo" => $contracargo ,
        "descuento" => $descuento  ,
        "precio" => $precio ,
        "parqueo" => $parqueo  ,
        "bodega" => $bodega  ,
        "enganchePorcMonto" => $enganchePorcMonto,
        "codigo" => $codigo,
        "apartamento" => $apartamento,
        "nombreCliente" => $nombreCliente,
        "mss" => $strQuery,
        "info" => $arr
    );
}
if(isset($_GET['get_reserva_apartamento'])){

    $strSelect='';
    $strFrom='';
    $arr = array();
    $strQuery = "SELECT idGlobal as idProyecto,t.idTorre,n.idNivel,a.idApartamento, r.*,date_format(fechaPagoReserva, '%d-%m-%Y') as fechaPagoReservaFormat
                    FROM reservaApartamento  r
                    INNER JOIN datosGlobales dg ON r.proyecto = dg.proyecto
                    INNER JOIN torres t ON dg.idGlobal = t.proyecto and r.torre = t.noTorre
                    INNER JOIN niveles n ON t.idTorre = n.idTorre and r.nivel = n.noNivel
                    INNER JOIN apartamentos a ON n.idNivel = a.idNivel and r.apartamento = a.apartamento
                    WHERE idReserva = {$intIdReserva }";
    
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }    
    

        //echo $strQuery;
       
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "info" => $arr
    );
}
if (isset($_GET['agregar_editar_cliente'])) {  
    $intEstadoViejo ='';
    if($intIdOcaCliente > 0){
        $strQuery = " SELECT estado FROM agregarCliente 
        WHERE idCliente =  {$intIdOcaCliente}";
        $qTmp = $conn ->db_query($strQuery);  
        $rTmp = $conn->db_fetch_object($qTmp);
        $intEstadoViejo = $rTmp->estado;
    }
  
    $errorMsj = "";
    $anio=date("Y");
    $fechaHoy=date("Y-m-d");
    $strQuery = "   INSERT agregarCliente (idCliente,anio,correlativo,codigo,tipoCliente,nombre_sa,representanteLegal,patenteEmpresa,patenteSociedad,rtu, primerNombre, 
                                                        segundoNombre, primerApellido, segundoApellido, apellidoCasada, tercerNombre, correoElectronico, 
                                                        telefono, telefonoFijo, direccion, numeroDpi, departamento, municipio, 
                                                        fechaVencimientoDpi, nit, fechaEmisionDpi, Nacionalidad, fechaNacimiento, profesion, 
                                                        estadoCivil, noDependientes, empresaLabora, telefonoReferencia, puestoLabora, direccionEmpresa,observaciones, 
                                                        salarioMensual, otrosIngresos, montoOtrosIngresos, creditoFHA, idUsuarioCreado,fechaCreacion,tipoComision,trabajarFHA)
                    SELECT {$intIdOcaCliente},{$anio},IFNULL(MAX(correlativo),0)+1,CONCAT('{$anio}-',IFNULL(MAX(correlativo),0)+1),'{$strTipoCliente}','{$strNombreSa}','{$strRepresentanteLegal}','{$strPatenteEmpresa}',
                            '{$strPatenteSociedad}','{$strRtu}','{$strPrimerNombre}','{$strSegundoNombre}',
                            '{$strPrimerApellido}', '{$strSegundoApellido}','{$strApellidoCasada}','{$strTercerNombre}','{$strCorreo}',
                            '{$strTelefono}','{$strtelefonoFijo}','{$strDireccion}','{$strDpi}',{$intDepto},{$intMunicipio}, 
                            '{$strFechaVencimientoDpi}','{$strnit}','{$strfechaEmisionDpi}','{$strnacionalidad}','{$strfechaNacimiento}','{$strprofesion}',
                            '{$strestadoCivil}',{$intDependientes},'{$strempresaLabora}','{$strtelefonoReferencia}','{$strpuestoEmpresa}','{$strdireccionEmpresa}','{$strobservacionesCl}',
                            {$intsalarioMensual},'{$strotrosIngresos}',{$intmontoOtrosIngresos},'{$strfha}',{$id_usuario},'{$fechaHoy}','{$strTipoComision}',{$intTrabajarFHA}
                            FROM agregarCliente
                            WHERE anio = {$anio}
                    ON DUPLICATE KEY UPDATE
                    primerNombre = '{$strPrimerNombre}',
                    segundoNombre = '{$strSegundoNombre}',
                    primerApellido = '{$strPrimerApellido}',
                    segundoApellido = '{$strSegundoApellido}',
                    apellidoCasada = '{$strApellidoCasada}',
                    correoElectronico ='{$strCorreo}',
                    telefono = '{$strTelefono}',
                    direccion = '{$strDireccion}',
                    numeroDpi = '{$strDpi}',
                    fechaVencimientoDpi = '{$strFechaVencimientoDpi}',
                    nit = '{$strnit}',
                    fechaEmisionDpi='{$strfechaEmisionDpi}' ,
                    Nacionalidad = '{$strnacionalidad}' ,
                    fechaNacimiento = '{$strfechaNacimiento}' ,
                    estadoCivil = '{$strestadoCivil}',
                    empresaLabora = '{$strempresaLabora}' ,
                    puestoLabora = '{$strpuestoEmpresa}' ,
                    direccionEmpresa = '{$strdireccionEmpresa}' ,
                    observaciones = '{$strobservacionesCl}' ,
                    salarioMensual = {$intsalarioMensual} ,
                    otrosIngresos = '{$strotrosIngresos}' ,
                    montoOtrosIngresos = {$intmontoOtrosIngresos},
                    departamento = {$intDepto},
                    municipio = {$intMunicipio},
                    profesion = '{$strprofesion}',
                    noDependientes = {$intDependientes},
                    telefonoReferencia = '{$strtelefonoReferencia}' ,
                    telefonoFijo = '{$strtelefonoFijo}' ,
                    tercerNombre = '{$strTercerNombre}',
                    creditoFHA =  '{$strfha}',
                    tipoCliente =  '{$strTipoCliente}',
                    representanteLegal =  '{$strRepresentanteLegal}',
                    patenteEmpresa =  '{$strPatenteEmpresa}',
                    patenteSociedad =  '{$strPatenteSociedad}',
                    rtu =  '{$strRtu}',
                    nombre_sa = '{$strNombreSa}',
                    tipoComision = '{$strTipoComision}',
                    trabajarFHA = {$intTrabajarFHA}  ";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        if($intEstadoCliente==0 && $intEstadoViejo!=0){
            $strQuery = " UPDATE agregarCliente SET estado = 0
                            WHERE idCliente = {$intIdOcaCliente}";
            $qTmp = $conn ->db_query($strQuery);
            $comentarioBitacora='Se ha dado de baja cliente con DPI '.trim($strDpi);
            insertBitacora($id_usuario,$comentarioBitacora,$conn);
            $strQuery = " SELECT proyecto,apartamento FROM enganche 
            WHERE idCliente =  {$intIdOcaCliente}";
            $qTmp = $conn ->db_query($strQuery);
            while($rTmp = $conn->db_fetch_object($qTmp)){
                $strQuery = " UPDATE apartamentos SET estado = 1
                WHERE apartamento = '{$rTmp->apartamento}'";
                $conn->db_query($strQuery);
                $comentarioBitacora='agregar_editar_cliente Se ha liberado apartamento '.$rTmp->apartamento;
                insertBitacora($id_usuario,$comentarioBitacora,$conn);
            }     
        }
        $title = $intIdOcaCliente > 0 ? " Editado" : " Guardado";
       
        
        $res = array(
            'err' => false,
            "mss" => "Cliente {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $intIdOcaCliente,
            "proyecto" => $strProyectoCl,
            "clientName" => $strClientName
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
    $comentarioBitacora=$intIdOcaCliente > 0?'Se ha editado cliente con DPI '.$strDpi:' Se ha creado cliente con DPI '.$strDpi;
    insertBitacora($id_usuario,$comentarioBitacora,$conn);
}
if (isset($_GET['agregar_editar_cliente_info_fha'])) {  
    $intEstadoViejo ='';
    $errorMsj = "";
    $anio=date("Y");
    $fechaHoy=date("Y-m-d H:i:s");
    $strQuery = " UPDATE agregarCliente SET estatura = '{$strEstatura}', peso = '{$strPeso}'
                    WHERE idCliente = {$intIdOcaCliente}";
    $conn->db_query($strQuery);

    $strQuery = "INSERT infoPatrimonial (idCliente,caja,
                    bancos,
                    cuentas_cobrar,
                    terrenos,
                    viviendas,
                    vehiculos,
                    inversiones,
                    bonos,
                    acciones,
                    muebles,
                    cuentas_pagar_corto_plazo,
                    cuentas_pagar_largo_plazo,
                    prestamos_hipotecarios,
                    sostenimiento_hogar,
                    alquiler,
                    prestamos,
                    impuestos,
                    extrafinanciamientos,
                    deudas_particulares,
                    idUsuarioCreacion,
                    fechaCreacion)
                    VALUES({$intIdOcaCliente},
                        {$intCaja},{$intBancos},{$intCuentasCobrar},{$intTerrenos},{$intViviendas},
                        {$intVehiculos},{$intInversiones},{$intBonos},{$intAcciones},{$intMuebles},
                        {$intCuentasPagarCortoPlazo},{$intCuentasPagarLargoPlazo},{$intPrestamosHipotecarios},{$intSostenimientoHogar},{$intAlquiler},
                        {$intPrestamos},{$intImpuestos},{$intExtrafinanciamientos},{$intDeudasParticulares},{$id_usuario},'{$fechaHoy}'
                    )
                    ON DUPLICATE KEY UPDATE
                    caja = values(caja),
                    bancos = values(bancos),
                    cuentas_cobrar = values(cuentas_cobrar),
                    terrenos = values(terrenos),
                    viviendas = values(viviendas),
                    vehiculos = values(vehiculos),
                    inversiones = values(inversiones),
                    bonos = values(bonos),
                    acciones = values(acciones),
                    muebles = values(muebles),
                    cuentas_pagar_corto_plazo = values(cuentas_pagar_corto_plazo),
                    cuentas_pagar_largo_plazo = values(cuentas_pagar_largo_plazo),
                    prestamos_hipotecarios = values(prestamos_hipotecarios),
                    sostenimiento_hogar= values(sostenimiento_hogar),
                    alquiler = values(alquiler),
                    prestamos = values(prestamos),
                    impuestos = values(impuestos),
                    extrafinanciamientos = values(extrafinanciamientos),
                    deudas_particulares = values(deudas_particulares) ";
    //echo $strQuery; 
    $conn->db_query($strQuery);

    $strQuery = "INSERT detallePatrimonial (idCliente,
                    direccion_inmueble_1,
                    direccion_inmueble_2,
                    finca_1,
                    folio_1,
                    libro_1,
                    departamento_1,
                    valor_inmueble_1,
                    finca_2,
                    folio_2,
                    libro_2,
                    departamento_2,
                    valor_inmueble_2,
                    marca_1,
                    tipo_vehiculo_1,
                    modelo_vehiculo_1,
                    marca_2,
                    tipo_vehiculo_2,
                    modelo_vehiculo_2,
                    valor_estimado_1,
                    valor_estimado_2,
                    idUsuarioCreacion,
                    fechaCreacion)
                    VALUES({$intIdOcaCliente},
                        '{$strDireccionInmueble1}','{$strDireccionInmueble2}','{$strFinca1}','{$strFolio1}','{$strLibro1}','{$strDepartamento1}','{$intValorinmueble1}',
                        '{$strFinca2}','{$strFolio2}','{$strLibro2}','{$strDepartamento2}','{$intValorinmueble2}',
                        '{$strMarca1}','{$strTipo1}','{$strModelo1}',
                        '{$strMarca2}','{$strTipo2}','{$strModelo2}',
                        {$intValorEstimado1},{$intValorEstimado2},{$id_usuario},'{$fechaHoy}'
                    )
                    ON DUPLICATE KEY UPDATE
                    direccion_inmueble_1 = values(direccion_inmueble_1),
                    direccion_inmueble_2 = values(direccion_inmueble_2),
                    finca_1 = values(finca_1),
                    valor_inmueble_1 = values(valor_inmueble_1),
                    folio_1 = values(folio_1),
                    libro_1 = values(libro_1),
                    departamento_1 = values(departamento_1),
                    finca_2 = values(finca_2),
                    folio_2 = values(folio_2),
                    libro_2 = values(libro_2),
                    departamento_2 = values(departamento_2),
                    valor_inmueble_2 = values(valor_inmueble_2),
                    marca_1 = values(marca_1),
                    tipo_vehiculo_1 = values(tipo_vehiculo_1),
                    modelo_vehiculo_1 = values(modelo_vehiculo_1),
                    marca_2 = values(marca_2),
                    tipo_vehiculo_2 = values(tipo_vehiculo_2),
                    modelo_vehiculo_2 = values(modelo_vehiculo_2),
                    valor_estimado_1 = values(valor_estimado_1),
                    valor_estimado_2 = values(valor_estimado_2)";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        $title = $intIdOcaCliente > 0 ? " Editado" : " Guardado";
       
        
        $res = array(
            'err' => false,
            "mss" => "Cliente Info FHA {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $intIdOcaCliente,
            "clientName" => $strClientName
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
    $comentarioBitacora=$intIdOcaCliente > 0?'Se ha editado cliente con DPI '.$strDpi:' Se ha creado cliente con DPI '.$strDpi;
    insertBitacora($id_usuario,$comentarioBitacora,$conn);
}
if (isset($_GET['agregar_editar_cliente_info_fha_co'])) {  
    $intEstadoViejo ='';
    $errorMsj = "";
    $anio=date("Y");
    $fechaHoy=date("Y-m-d H:i:s");
    $strQuery = " UPDATE codeudor SET estatura = '{$strEstatura}', peso = '{$strPeso}'
                    WHERE idCodeudor = {$intIdCodeudor}";
    $conn->db_query($strQuery);

    $strQuery = "INSERT infoPatrimonialCo (idCliente,caja,
                    bancos,
                    cuentas_cobrar,
                    terrenos,
                    viviendas,
                    vehiculos,
                    inversiones,
                    bonos,
                    acciones,
                    muebles,
                    cuentas_pagar_corto_plazo,
                    cuentas_pagar_largo_plazo,
                    prestamos_hipotecarios,
                    sostenimiento_hogar,
                    alquiler,
                    prestamos,
                    impuestos,
                    extrafinanciamientos,
                    deudas_particulares,
                    idUsuarioCreacion,
                    fechaCreacion)
                    VALUES({$intIdCodeudor},
                        {$intCaja},{$intBancos},{$intCuentasCobrar},{$intTerrenos},{$intViviendas},
                        {$intVehiculos},{$intInversiones},{$intBonos},{$intAcciones},{$intMuebles},
                        {$intCuentasPagarCortoPlazo},{$intCuentasPagarLargoPlazo},{$intPrestamosHipotecarios},{$intSostenimientoHogar},{$intAlquiler},
                        {$intPrestamos},{$intImpuestos},{$intExtrafinanciamientos},{$intDeudasParticulares},{$id_usuario},'{$fechaHoy}'
                    )
                    ON DUPLICATE KEY UPDATE
                    caja = values(caja),
                    bancos = values(bancos),
                    cuentas_cobrar = values(cuentas_cobrar),
                    terrenos = values(terrenos),
                    viviendas = values(viviendas),
                    vehiculos = values(vehiculos),
                    inversiones = values(inversiones),
                    bonos = values(bonos),
                    acciones = values(acciones),
                    muebles = values(muebles),
                    cuentas_pagar_corto_plazo = values(cuentas_pagar_corto_plazo),
                    cuentas_pagar_largo_plazo = values(cuentas_pagar_largo_plazo),
                    prestamos_hipotecarios = values(prestamos_hipotecarios),
                    sostenimiento_hogar= values(sostenimiento_hogar),
                    alquiler = values(alquiler),
                    prestamos = values(prestamos),
                    impuestos = values(impuestos),
                    extrafinanciamientos = values(extrafinanciamientos),
                    deudas_particulares = values(deudas_particulares) ";
    //echo $strQuery; 
    $conn->db_query($strQuery);

    $strQuery = "INSERT detallePatrimonialCo (idCliente,
                    direccion_inmueble_1,
                    direccion_inmueble_2,
                    finca_1,
                    folio_1,
                    libro_1,
                    departamento_1,
                    valor_inmueble_1,
                    finca_2,
                    folio_2,
                    libro_2,
                    departamento_2,
                    valor_inmueble_2,
                    marca_1,
                    tipo_vehiculo_1,
                    modelo_vehiculo_1,
                    marca_2,
                    tipo_vehiculo_2,
                    modelo_vehiculo_2,
                    valor_estimado_1,
                    valor_estimado_2,
                    idUsuarioCreacion,
                    fechaCreacion)
                    VALUES({$intIdCodeudor},
                        '{$strDireccionInmueble1}','{$strDireccionInmueble2}','{$strFinca1}','{$strFolio1}','{$strLibro1}','{$strDepartamento1}','{$intValorinmueble1}',
                        '{$strFinca2}','{$strFolio2}','{$strLibro2}','{$strDepartamento2}','{$intValorinmueble2}',
                        '{$strMarca1}','{$strTipo1}','{$strModelo1}',
                        '{$strMarca2}','{$strTipo2}','{$strModelo2}',
                        {$intValorEstimado1},{$intValorEstimado2},{$id_usuario},'{$fechaHoy}'
                    )
                    ON DUPLICATE KEY UPDATE
                    direccion_inmueble_1 = values(direccion_inmueble_1),
                    direccion_inmueble_2 = values(direccion_inmueble_2),
                    finca_1 = values(finca_1),
                    valor_inmueble_1 = values(valor_inmueble_1),
                    folio_1 = values(folio_1),
                    libro_1 = values(libro_1),
                    departamento_1 = values(departamento_1),
                    finca_2 = values(finca_2),
                    folio_2 = values(folio_2),
                    libro_2 = values(libro_2),
                    departamento_2 = values(departamento_2),
                    valor_inmueble_2 = values(valor_inmueble_2),
                    marca_1 = values(marca_1),
                    tipo_vehiculo_1 = values(tipo_vehiculo_1),
                    modelo_vehiculo_1 = values(modelo_vehiculo_1),
                    marca_2 = values(marca_2),
                    tipo_vehiculo_2 = values(tipo_vehiculo_2),
                    modelo_vehiculo_2 = values(modelo_vehiculo_2),
                    valor_estimado_1 = values(valor_estimado_1),
                    valor_estimado_2 = values(valor_estimado_2)";
    //echo $strQuery; 
    if ($conn->db_query($strQuery)) {
        $title = $intIdOcaCliente > 0 ? " Editado" : " Guardado";
       
        
        $res = array(
            'err' => false,
            "mss" => "Cliente Info FHA {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $intIdCodeudor,
            "idEnganche" => $intIdEngancheCo,
            "idCliente" => $intIdClienteCo,
            "clientName" => $strClientName
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
    $comentarioBitacora=$intIdCodeudor > 0?'Se ha editado codeudor cliente con DPI '.$strDpi:' Se ha creado codeudor cliente con DPI '.$strDpi;
    insertBitacora($id_usuario,$comentarioBitacora,$conn);
}
if (isset($_GET['agregar_editar_cliente_info_fha_dependencia_co'])) {  
    $intEstadoViejo ='';
    $errorMsj = "";
    $anio=date("Y");
    $fechaHoy=date("Y-m-d H:i:s");

    $strQuery = "INSERT detalleIngresosDescuentosMensualesCo (idCliente,
                    tipoContrato,
                    vigencia_vence,
                    salario_nominal,
                    bono_catorce,
                    aguinaldo,
                    honorarios,
                    otros_ingresos_fha,
                    igss,
                    isr,
                    plan_pensiones,
                    judiciales,
                    otros_descuentos_fha,
                    idUsuarioCreacion,
                    fechaCreacion)
                    VALUES({$intIdCodeudor},'{$strTipoContrato}',
                        '{$strVigenciaVence}',{$intSalarioNominal},{$intBonoCatorce},{$intAguinaldo},{$intHonorarios},
                        {$intOtrosIngresosFha},{$intIgss},{$intIsr},{$intPlanPensiones},{$intJudiciales},
                        {$intOtrosDescuentosFha},{$id_usuario},'{$fechaHoy}'
                    )
                    ON DUPLICATE KEY UPDATE
                    tipoContrato = values(tipoContrato),
                    vigencia_vence = values(vigencia_vence),
                    salario_nominal = values(salario_nominal),
                    bono_catorce = values(bono_catorce),
                    aguinaldo = values(aguinaldo),
                    honorarios = values(honorarios),
                    otros_ingresos_fha = values(otros_ingresos_fha),
                    igss = values(igss),
                    isr = values(isr),
                    plan_pensiones = values(plan_pensiones),
                    judiciales = values(judiciales),
                    otros_descuentos_fha = values(otros_descuentos_fha)";
    //echo $strQuery;
    $conn->db_query($strQuery);

    $strQuery = "INSERT historialLaboralCo (idCliente,
                    empresa_1,
                    cargo_1,
                    desde_1,
                    hasta_1,
                    empresa_2,
                    cargo_2,
                    desde_2,
                    hasta_2,
                    empresa_3,
                    cargo_3,
                    desde_3,
                    hasta_3,
                    empresa_4,
                    cargo_4,
                    desde_4,
                    hasta_4,
                    idUsuarioCreacion,
                    fechaCreacion)
                    VALUES({$intIdCodeudor},'{$strEmpresa1}','{$strCargo1}','{$strDesde1}','{$strHasta1}',
                    '{$strEmpresa2}','{$strCargo2}','{$strDesde2}','{$strHasta2}',
                    '{$strEmpresa3}','{$strCargo3}','{$strDesde3}','{$strHasta3}',
                    '{$strEmpresa4}','{$strCargo4}','{$strDesde4}','{$strHasta4}',
                    {$id_usuario},'{$fechaHoy}'
                    )
                    ON DUPLICATE KEY UPDATE
                    empresa_1 = values(empresa_1),
                    cargo_1 = values(cargo_1),
                    desde_1 = values(desde_1),
                    hasta_1 = values(hasta_1),
                    empresa_2 = values(empresa_2),
                    cargo_2 = values(cargo_2),
                    desde_2 = values(desde_2),
                    hasta_2 = values(hasta_2),
                    empresa_3 = values(empresa_3),
                    cargo_3 = values(cargo_3),
                    desde_3 = values(desde_3),
                    hasta_3 = values(hasta_3),
                    empresa_4 = values(empresa_4),
                    cargo_4 = values(cargo_4),
                    desde_4 = values(desde_4),
                    hasta_4 = values(hasta_4)";
    //echo $strQuery;
    $conn->db_query($strQuery);

    $strQuery = "INSERT refFamiliarCo (idCliente,
    nombre_referencia_1,
    parentesco_referencia_1,
    domicilio_1,
    telefono_1,
    trabajo_1,
    trabajo_direccion_1,
    trabajo_telefono_1,
    nombre_referencia_2,
    parentesco_referencia_2,
    domicilio_2,
    telefono_2,
    trabajo_2,
    trabajo_direccion_2,
    trabajo_telefono_2,
    idUsuarioCreacion,
    fechaCreacion)
    VALUES({$intIdCodeudor},'{$strNombreReferencia1}','{$strParentescoReferencia1}',
    '{$strDomicilio1}','{$strTelefono1}','{$strTrabajo1}','{$strTrabajoDireccion1}','{$strTrabajoTelefono1}',
    '{$strNombreReferencia2}','{$strParentescoReferencia2}',
    '{$strDomicilio2}','{$strTelefono2}','{$strTrabajo2}','{$strTrabajoDireccion2}','{$strTrabajoTelefono2}',
    {$id_usuario},'{$fechaHoy}'
    )
    ON DUPLICATE KEY UPDATE
    nombre_referencia_1 = values(nombre_referencia_1),
    parentesco_referencia_1 = values(parentesco_referencia_1),
    domicilio_1 = values(domicilio_1),
    telefono_1 = values(telefono_1),
    trabajo_1 = values(trabajo_1),
    trabajo_direccion_1 = values(trabajo_direccion_1),
    trabajo_telefono_1 = values(trabajo_telefono_1),
    nombre_referencia_2 = values(nombre_referencia_2),
    parentesco_referencia_2 = values(parentesco_referencia_2),
    domicilio_2 = values(domicilio_2),
    telefono_2 = values(telefono_2),
    trabajo_2 = values(trabajo_2),
    trabajo_direccion_2 = values(trabajo_direccion_2),
    trabajo_telefono_2 = values(trabajo_telefono_2)";
//echo $strQuery;
$conn->db_query($strQuery);

    $strQuery = "INSERT refbancariasCo (idCliente,
    banco_1,
    no_cuenta_1,
    tipo_cuenta_1,
    saldo_actual_1,
    banco_2,
    no_cuenta_2,
    tipo_cuenta_2,
    saldo_actual_2,
    idUsuarioCreacion,
    fechaCreacion)
    VALUES({$intIdCodeudor},'{$strBanco1}','{$strNoCuenta1}','{$strTipoCuenta1}',
    {$intSaldoActual1},
    '{$strBanco2}','{$strNoCuenta2}','{$strTipoCuenta2}',{$intSaldoActual2},
    {$id_usuario},'{$fechaHoy}'
    )
    ON DUPLICATE KEY UPDATE
    banco_1 = values(banco_1),
    no_cuenta_1 = values(no_cuenta_1),
    tipo_cuenta_1 = values(tipo_cuenta_1),
    saldo_actual_1 = values(saldo_actual_1),
    banco_2 = values(banco_2),
    no_cuenta_2 = values(no_cuenta_2),
    tipo_cuenta_2 = values(tipo_cuenta_2),
    saldo_actual_2 = values(saldo_actual_2)";
//echo $strQuery;
$conn->db_query($strQuery);

    $strQuery = "INSERT refCrediticiasCo (idCliente,
    banco_prestamo_1,
    tipo_prestamo_1,
    no_prestamo_1,
    monto_1,
    saldo_actual_prestamo_1,
    pago_mensual_prestamo_1,
    fecha_vencimiento_prestamo_1,
    banco_prestamo_2,
    tipo_prestamo_2,
    no_prestamo_2,
    monto_2,
    saldo_actual_prestamo_2,
    pago_mensual_prestamo_2,
    fecha_vencimiento_prestamo_2,
    idUsuarioCreacion,
    fechaCreacion)
    VALUES({$intIdCodeudor},'{$strBancoPrestamo1}','{$strTipoPrestamo1}','{$strNoPrestamo1}',{$intmonto1},{$intSaldoActualPrestamo1},{$intPagoMensualPrestamo1},'{$strFechaVencimientoPrestamo1}',
    '{$strBancoPrestamo2}','{$strTipoPrestamo2}','{$strNoPrestamo2}',{$intmonto2},{$intSaldoActualPrestamo2},{$intPagoMensualPrestamo2},'{$strFechaVencimientoPrestamo2}',
    {$id_usuario},'{$fechaHoy}'
    )
    ON DUPLICATE KEY UPDATE
    banco_prestamo_1 = values(banco_prestamo_1),
    tipo_prestamo_1 = values(tipo_prestamo_1),
    no_prestamo_1 = values(no_prestamo_1),
    monto_1 = values(monto_1),
    saldo_actual_prestamo_1 = values(saldo_actual_prestamo_1),
    pago_mensual_prestamo_1 = values(pago_mensual_prestamo_1),
    fecha_vencimiento_prestamo_1 = values(fecha_vencimiento_prestamo_1),
    banco_prestamo_2 = values(banco_prestamo_2),
    tipo_prestamo_2 = values(tipo_prestamo_2),
    no_prestamo_2 = values(no_prestamo_2),
    monto_2 = values(monto_2),
    saldo_actual_prestamo_2 = values(saldo_actual_prestamo_2),
    pago_mensual_prestamo_2 = values(pago_mensual_prestamo_2),
    fecha_vencimiento_prestamo_2 = values(fecha_vencimiento_prestamo_2)";
//echo $strQuery;
$conn->db_query($strQuery);

    $strQuery = "INSERT detalleComisionesCo (idCliente,
            mes_1,
            mes_2,
            mes_3,
            mes_4,
            mes_5,
            mes_6,
            hora_extra_mes_1,
            hora_extra_mes_2,
            hora_extra_mes_3,
            hora_extra_mes_4,
            hora_extra_mes_5,
            hora_extra_mes_6,
            comisiones_mes_1,
            comisiones_mes_2,
            comisiones_mes_3,
            comisiones_mes_4,
            comisiones_mes_5,
            comisiones_mes_6,
            bonificaciones_mes_1,
            bonificaciones_mes_2,
            bonificaciones_mes_3,
            bonificaciones_mes_4,
            bonificaciones_mes_5,
            bonificaciones_mes_6,
            idUsuarioCreacion,
            fechaCreacion)
            VALUES({$intIdCodeudor},
            '{$strMes1}','{$strMes2}','{$strMes3}','{$strMes4}','{$strMes5}','{$strMes6}',
            {$intHoraExtraMes1},{$intHoraExtraMes2},{$intHoraExtraMes3},{$intHoraExtraMes4},{$intHoraExtraMes5},{$intHoraExtraMes6},
            {$intComisionesMes1},{$intComisionesMes2},{$intComisionesMes3},{$intComisionesMes4},{$intComisionesMes5},{$intComisionesMes6},
            {$intBonificacionesMes1},{$intBonificacionesMes2},{$intBonificacionesMes3},{$intBonificacionesMes4},{$intBonificacionesMes5},{$intBonificacionesMes6},
            {$id_usuario},'{$fechaHoy}'
            )
            ON DUPLICATE KEY UPDATE
            mes_1= values(mes_1),
            mes_2= values(mes_2),
            mes_3= values(mes_3),
            mes_4= values(mes_4),
            mes_5= values(mes_5),
            mes_6= values(mes_6),
            hora_extra_mes_1= values(hora_extra_mes_1),
            hora_extra_mes_2= values(hora_extra_mes_2),
            hora_extra_mes_3= values(hora_extra_mes_3),
            hora_extra_mes_4= values(hora_extra_mes_4),
            hora_extra_mes_5= values(hora_extra_mes_5),
            hora_extra_mes_6= values(hora_extra_mes_6),
            comisiones_mes_1= values(comisiones_mes_1),
            comisiones_mes_2= values(comisiones_mes_2),
            comisiones_mes_3= values(comisiones_mes_3),
            comisiones_mes_4= values(comisiones_mes_4),
            comisiones_mes_5= values(comisiones_mes_5),
            comisiones_mes_6= values(comisiones_mes_6),
            bonificaciones_mes_1= values(bonificaciones_mes_1),
            bonificaciones_mes_2= values(bonificaciones_mes_2),
            bonificaciones_mes_3= values(bonificaciones_mes_3),
            bonificaciones_mes_4= values(bonificaciones_mes_4),
            bonificaciones_mes_5= values(bonificaciones_mes_5),
            bonificaciones_mes_6= values(bonificaciones_mes_6)";
        //echo $strQuery;

    if ($conn->db_query($strQuery)) {
        $title = $intIdCodeudor > 0 ? " Editado" : " Guardado";
       
        
        $res = array(
            'err' => false,
            "mss" => "Cliente Info FHA {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $intIdCodeudor,
            "idCliente" => $intIdClienteCo,
            "idEnganche" => $intIdEngancheCo,
            "clientName" => $strClientName
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
    $comentarioBitacora=$intIdOcaCliente > 0?'Se ha editado cliente con DPI '.$strDpi:' Se ha creado cliente con DPI '.$strDpi;
    insertBitacora($id_usuario,$comentarioBitacora,$conn);
}
if (isset($_GET['agregar_editar_cliente_info_fha_dependencia'])) {  
    $intEstadoViejo ='';
    $errorMsj = "";
    $anio=date("Y");
    $fechaHoy=date("Y-m-d H:i:s");

    $strQuery = "INSERT detalleIngresosDescuentosMensuales (idCliente,
                    tipoContrato,
                    vigencia_vence,
                    salario_nominal,
                    bono_catorce,
                    aguinaldo,
                    honorarios,
                    otros_ingresos_fha,
                    igss,
                    isr,
                    plan_pensiones,
                    judiciales,
                    otros_descuentos_fha,
                    idUsuarioCreacion,
                    fechaCreacion)
                    VALUES({$intIdOcaCliente},'{$strTipoContrato}',
                        '{$strVigenciaVence}',{$intSalarioNominal},{$intBonoCatorce},{$intAguinaldo},{$intHonorarios},
                        {$intOtrosIngresosFha},{$intIgss},{$intIsr},{$intPlanPensiones},{$intJudiciales},
                        {$intOtrosDescuentosFha},{$id_usuario},'{$fechaHoy}'
                    )
                    ON DUPLICATE KEY UPDATE
                    tipoContrato = values(tipoContrato),
                    vigencia_vence = values(vigencia_vence),
                    salario_nominal = values(salario_nominal),
                    bono_catorce = values(bono_catorce),
                    aguinaldo = values(aguinaldo),
                    honorarios = values(honorarios),
                    otros_ingresos_fha = values(otros_ingresos_fha),
                    igss = values(igss),
                    isr = values(isr),
                    plan_pensiones = values(plan_pensiones),
                    judiciales = values(judiciales),
                    otros_descuentos_fha = values(otros_descuentos_fha)";
    //echo $strQuery;
    $conn->db_query($strQuery);

    $strQuery = "INSERT historialLaboral (idCliente,
                    empresa_1,
                    cargo_1,
                    desde_1,
                    hasta_1,
                    empresa_2,
                    cargo_2,
                    desde_2,
                    hasta_2,
                    empresa_3,
                    cargo_3,
                    desde_3,
                    hasta_3,
                    empresa_4,
                    cargo_4,
                    desde_4,
                    hasta_4,
                    idUsuarioCreacion,
                    fechaCreacion)
                    VALUES({$intIdOcaCliente},'{$strEmpresa1}','{$strCargo1}','{$strDesde1}','{$strHasta1}',
                    '{$strEmpresa2}','{$strCargo2}','{$strDesde2}','{$strHasta2}',
                    '{$strEmpresa3}','{$strCargo3}','{$strDesde3}','{$strHasta3}',
                    '{$strEmpresa4}','{$strCargo4}','{$strDesde4}','{$strHasta4}',
                    {$id_usuario},'{$fechaHoy}'
                    )
                    ON DUPLICATE KEY UPDATE
                    empresa_1 = values(empresa_1),
                    cargo_1 = values(cargo_1),
                    desde_1 = values(desde_1),
                    hasta_1 = values(hasta_1),
                    empresa_2 = values(empresa_2),
                    cargo_2 = values(cargo_2),
                    desde_2 = values(desde_2),
                    hasta_2 = values(hasta_2),
                    empresa_3 = values(empresa_3),
                    cargo_3 = values(cargo_3),
                    desde_3 = values(desde_3),
                    hasta_3 = values(hasta_3),
                    empresa_4 = values(empresa_4),
                    cargo_4 = values(cargo_4),
                    desde_4 = values(desde_4),
                    hasta_4 = values(hasta_4)";
    //echo $strQuery;
    $conn->db_query($strQuery);

    $strQuery = "INSERT refFamiliar (idCliente,
    nombre_referencia_1,
    parentesco_referencia_1,
    domicilio_1,
    telefono_1,
    trabajo_1,
    trabajo_direccion_1,
    trabajo_telefono_1,
    nombre_referencia_2,
    parentesco_referencia_2,
    domicilio_2,
    telefono_2,
    trabajo_2,
    trabajo_direccion_2,
    trabajo_telefono_2,
    idUsuarioCreacion,
    fechaCreacion)
    VALUES({$intIdOcaCliente},'{$strNombreReferencia1}','{$strParentescoReferencia1}',
    '{$strDomicilio1}','{$strTelefono1}','{$strTrabajo1}','{$strTrabajoDireccion1}','{$strTrabajoTelefono1}',
    '{$strNombreReferencia2}','{$strParentescoReferencia2}',
    '{$strDomicilio2}','{$strTelefono2}','{$strTrabajo2}','{$strTrabajoDireccion2}','{$strTrabajoTelefono2}',
    {$id_usuario},'{$fechaHoy}'
    )
    ON DUPLICATE KEY UPDATE
    nombre_referencia_1 = values(nombre_referencia_1),
    parentesco_referencia_1 = values(parentesco_referencia_1),
    domicilio_1 = values(domicilio_1),
    telefono_1 = values(telefono_1),
    trabajo_1 = values(trabajo_1),
    trabajo_direccion_1 = values(trabajo_direccion_1),
    trabajo_telefono_1 = values(trabajo_telefono_1),
    nombre_referencia_2 = values(nombre_referencia_2),
    parentesco_referencia_2 = values(parentesco_referencia_2),
    domicilio_2 = values(domicilio_2),
    telefono_2 = values(telefono_2),
    trabajo_2 = values(trabajo_2),
    trabajo_direccion_2 = values(trabajo_direccion_2),
    trabajo_telefono_2 = values(trabajo_telefono_2)";
//echo $strQuery;
$conn->db_query($strQuery);

    $strQuery = "INSERT refbancarias (idCliente,
    banco_1,
    no_cuenta_1,
    tipo_cuenta_1,
    saldo_actual_1,
    banco_2,
    no_cuenta_2,
    tipo_cuenta_2,
    saldo_actual_2,
    idUsuarioCreacion,
    fechaCreacion)
    VALUES({$intIdOcaCliente},'{$strBanco1}','{$strNoCuenta1}','{$strTipoCuenta1}',
    {$intSaldoActual1},
    '{$strBanco2}','{$strNoCuenta2}','{$strTipoCuenta2}',{$intSaldoActual2},
    {$id_usuario},'{$fechaHoy}'
    )
    ON DUPLICATE KEY UPDATE
    banco_1 = values(banco_1),
    no_cuenta_1 = values(no_cuenta_1),
    tipo_cuenta_1 = values(tipo_cuenta_1),
    saldo_actual_1 = values(saldo_actual_1),
    banco_2 = values(banco_2),
    no_cuenta_2 = values(no_cuenta_2),
    tipo_cuenta_2 = values(tipo_cuenta_2),
    saldo_actual_2 = values(saldo_actual_2)";
//echo $strQuery;
$conn->db_query($strQuery);

    $strQuery = "INSERT refCrediticias (idCliente,
    banco_prestamo_1,
    tipo_prestamo_1,
    no_prestamo_1,
    monto_1,
    saldo_actual_prestamo_1,
    pago_mensual_prestamo_1,
    fecha_vencimiento_prestamo_1,
    banco_prestamo_2,
    tipo_prestamo_2,
    no_prestamo_2,
    monto_2,
    saldo_actual_prestamo_2,
    pago_mensual_prestamo_2,
    fecha_vencimiento_prestamo_2,
    idUsuarioCreacion,
    fechaCreacion)
    VALUES({$intIdOcaCliente},'{$strBancoPrestamo1}','{$strTipoPrestamo1}','{$strNoPrestamo1}',{$intmonto1},{$intSaldoActualPrestamo1},{$intPagoMensualPrestamo1},'{$strFechaVencimientoPrestamo1}',
    '{$strBancoPrestamo2}','{$strTipoPrestamo2}','{$strNoPrestamo2}',{$intmonto2},{$intSaldoActualPrestamo2},{$intPagoMensualPrestamo2},'{$strFechaVencimientoPrestamo2}',
    {$id_usuario},'{$fechaHoy}'
    )
    ON DUPLICATE KEY UPDATE
    banco_prestamo_1 = values(banco_prestamo_1),
    tipo_prestamo_1 = values(tipo_prestamo_1),
    no_prestamo_1 = values(no_prestamo_1),
    monto_1 = values(monto_1),
    saldo_actual_prestamo_1 = values(saldo_actual_prestamo_1),
    pago_mensual_prestamo_1 = values(pago_mensual_prestamo_1),
    fecha_vencimiento_prestamo_1 = values(fecha_vencimiento_prestamo_1),
    banco_prestamo_2 = values(banco_prestamo_2),
    tipo_prestamo_2 = values(tipo_prestamo_2),
    no_prestamo_2 = values(no_prestamo_2),
    monto_2 = values(monto_2),
    saldo_actual_prestamo_2 = values(saldo_actual_prestamo_2),
    pago_mensual_prestamo_2 = values(pago_mensual_prestamo_2),
    fecha_vencimiento_prestamo_2 = values(fecha_vencimiento_prestamo_2)";
//echo $strQuery;
$conn->db_query($strQuery);

    $strQuery = "INSERT detalleComisiones (idCliente,
            mes_1,
            mes_2,
            mes_3,
            mes_4,
            mes_5,
            mes_6,
            hora_extra_mes_1,
            hora_extra_mes_2,
            hora_extra_mes_3,
            hora_extra_mes_4,
            hora_extra_mes_5,
            hora_extra_mes_6,
            comisiones_mes_1,
            comisiones_mes_2,
            comisiones_mes_3,
            comisiones_mes_4,
            comisiones_mes_5,
            comisiones_mes_6,
            bonificaciones_mes_1,
            bonificaciones_mes_2,
            bonificaciones_mes_3,
            bonificaciones_mes_4,
            bonificaciones_mes_5,
            bonificaciones_mes_6,
            idUsuarioCreacion,
            fechaCreacion)
            VALUES({$intIdOcaCliente},
            '{$strMes1}','{$strMes2}','{$strMes3}','{$strMes4}','{$strMes5}','{$strMes6}',
            {$intHoraExtraMes1},{$intHoraExtraMes2},{$intHoraExtraMes3},{$intHoraExtraMes4},{$intHoraExtraMes5},{$intHoraExtraMes6},
            {$intComisionesMes1},{$intComisionesMes2},{$intComisionesMes3},{$intComisionesMes4},{$intComisionesMes5},{$intComisionesMes6},
            {$intBonificacionesMes1},{$intBonificacionesMes2},{$intBonificacionesMes3},{$intBonificacionesMes4},{$intBonificacionesMes5},{$intBonificacionesMes6},
            {$id_usuario},'{$fechaHoy}'
            )
            ON DUPLICATE KEY UPDATE
            mes_1= values(mes_1),
            mes_2= values(mes_2),
            mes_3= values(mes_3),
            mes_4= values(mes_4),
            mes_5= values(mes_5),
            mes_6= values(mes_6),
            hora_extra_mes_1= values(hora_extra_mes_1),
            hora_extra_mes_2= values(hora_extra_mes_2),
            hora_extra_mes_3= values(hora_extra_mes_3),
            hora_extra_mes_4= values(hora_extra_mes_4),
            hora_extra_mes_5= values(hora_extra_mes_5),
            hora_extra_mes_6= values(hora_extra_mes_6),
            comisiones_mes_1= values(comisiones_mes_1),
            comisiones_mes_2= values(comisiones_mes_2),
            comisiones_mes_3= values(comisiones_mes_3),
            comisiones_mes_4= values(comisiones_mes_4),
            comisiones_mes_5= values(comisiones_mes_5),
            comisiones_mes_6= values(comisiones_mes_6),
            bonificaciones_mes_1= values(bonificaciones_mes_1),
            bonificaciones_mes_2= values(bonificaciones_mes_2),
            bonificaciones_mes_3= values(bonificaciones_mes_3),
            bonificaciones_mes_4= values(bonificaciones_mes_4),
            bonificaciones_mes_5= values(bonificaciones_mes_5),
            bonificaciones_mes_6= values(bonificaciones_mes_6)";
        //echo $strQuery;

    if ($conn->db_query($strQuery)) {
        $title = $intIdOcaCliente > 0 ? " Editado" : " Guardado";
       
        
        $res = array(
            'err' => false,
            "mss" => "Cliente Info FHA {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $intIdOcaCliente,
            "clientName" => $strClientName
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
    $comentarioBitacora=$intIdOcaCliente > 0?'Se ha editado cliente con DPI '.$strDpi:' Se ha creado cliente con DPI '.$strDpi;
    insertBitacora($id_usuario,$comentarioBitacora,$conn);
}
if (isset($_GET['agregar_editar_cliente_fha'])) {          
    $errorMsj = "";
    $anio=date("Y");
    $fechaHoy=date("Y-m-d");

    if($intIdEngancheRes>0){
        $strQuery = " UPDATE enganche SET resguardoAsegurabilidad = '{$strResguardo}', 
                        fechaEmision = '{$strFechaEmision}', 
                        fechaCaducidad = '{$strFechaCaducidad}',
                        valorResguardo = {$intValorResguardo}
                        WHERE idEnganche = {$intIdEngancheRes}";
        $qTmp = $conn ->db_query($strQuery);
        $title = $intIdEngancheRes > 0 ? "Datos guardados" : "Datos guardados";
       
        
        $res = array(
            'err' => false,
            "mss" => " {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $intIdEngancheRes,
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_proceso_fha'])) {          


    if($strProcesoFha!=''){
        $strQuery = " UPDATE agregarCliente SET procesoFha = '{$strProcesoFha}' 
                        WHERE idCliente = '{$intIdCliente}'";
        $qTmp = $conn ->db_query($strQuery);
        $title = $strProcesoFha != '' ? "Datos guardados" : "Datos guardados";
       
       
        $res = array(
            'err' => false,
            "mss" => " {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $strProcesoFha,
            "color" => '#e64a19',
            "idCliente" => $intIdCliente,
            "idEnganche" => $intIdEnganche
            
            

        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_parqueo_externo'])) {          


    if($strApartamentoParqueoExtra!=''){
        $strQuery = " UPDATE apartamentos SET parqueo_externo = {$intParqueoExterno} 
        WHERE apartamento = '{$strApartamentoParqueoExtra}'";
        $qTmp = $conn ->db_query($strQuery);
        $title = $strApartamentoParqueoExtra != '' ? "Datos guardados" : "Datos guardados";
       
        $strQueryEn = "SELECT idEnganche,idCliente 
            FROM enganche
            where apartamento =  '{$strApartamentoParqueoExtra}'
            order by idEnganche desc limit 1 ;";

        //echo $strQuery;
        $qTmp = $conn ->db_query($strQueryEn);
        $rTmp = $conn->db_fetch_object($qTmp);
        $intIdEnganche = $rTmp->idEnganche;
        $intIdCliente = $rTmp->idCliente;
        $res = array(
            'err' => false,
            "mss" => " {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $strApartamentoParqueoExtra,
            "color" => '#e64a19',
            "idCliente" => $intIdCliente,
            "idEnganche" => $intIdEnganche
            
            

        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_cliente_fha_banco'])) {          
    $errorMsj = "";
    $anio=date("Y");
    $fechaHoy=date("Y-m-d");


    if($intIdEngancheRes>0){
        $strQuery = "  INSERT INTO infoBanco (idEnganche,banco,noResolucion,fechaResolucion,plazo)
                VALUES ('{$intIdEngancheRes}','{$strBancoResolucion}','{$intNoResolucion }','{$strFechaResolucion}','{$intPlazoCredito}')
                ON DUPLICATE KEY UPDATE
                banco=  '{$strBancoResolucion}',
                noResolucion=  '{$intNoResolucion}',
                fechaResolucion=  '{$strFechaResolucion}',
                plazo=  '{$intPlazoCredito}'";
                //echo $strQuery;
                $qTmp = $conn ->db_query($strQuery);
        $qTmp = $conn ->db_query($strQuery);
        $title = $intIdEngancheRes > 0 ? "Datos guardados" : "Datos guardados";
       
        
        $res = array(
            'err' => false,
            "mss" => " {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $intIdEngancheRes,
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_cliente_fha_inmueble'])) {          
    ;
    $errorMsj = "";
    $anio=date("Y");
    $fechaHoy=date("Y-m-d");
    if($intIdEnganche!=0){

        foreach($_POST['tipo'] as $idReg =>$idValReg)
		{
			$noInmueble=$idReg+1;
            $tipo=$_POST['tipo'][$idReg];
            $finca=$_POST['finca'][$idReg];
            $folio=$_POST['folio'][$idReg];
            $identificacion=$_POST['identificacion'][$idReg];
            $libro=$_POST['libro'][$idReg];
            $direccion=$_POST['direccion'][$idReg];
            $valor=$_POST['valor'][$idReg];
            $area=$_POST['area'][$idReg];
			if($idValReg!='' and $tipo!='')
			{
                $strQuery = "  INSERT INTO infoInmuebles (idEnganche,noInmueble,tipo,identificacion,finca,folio,libro,direccion,valor,area_mts,departamento)
                VALUES ('{$intIdEnganche}','{$noInmueble}','{$tipo}','{$identificacion}','{$finca}','{$folio}','{$libro}','{$direccion}',{$valor},{$area},'Propiedad Horizontal de Guatemala')
                ON DUPLICATE KEY UPDATE
                tipo=  '{$tipo}',
                identificacion=  '{$identificacion}',
                finca=  '{$finca}',
                folio=  '{$folio}',
                libro=  '{$libro}',
                direccion=  '{$direccion}',
                valor=  {$valor},
                area_mts=  {$area}               
                ";
                //echo $strQuery;
                $qTmp = $conn ->db_query($strQuery);
               
			}
			else
			{
                $res['mss'] = "propiedad vacia...";
			}
		}
        $title = $intIdEnganche > 0 ? "Datos guardados" : "Datos guardados";
        $res = array(
            'err' => false,
            "mss" => " {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $intIdEnganche,
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_codeudor'])) {          
    $errorMsj = "";
    $strQuery = "   INSERT codeudor (idCodeudor,idCliente,idEnganche, primerNombre, 
                                                        segundoNombre, primerApellido, segundoApellido, apellidoCasada, tercerNombre, correoElectronico, 
                                                        telefono, telefonoFijo, direccion, numeroDpi, departamento, municipio, 
                                                        fechaVencimientoDpi, nit, fechaEmisionDpi, Nacionalidad, fechaNacimiento, profesion, 
                                                        estadoCivil, noDependientes, empresaLabora, telefonoReferencia, puestoLabora, direccionEmpresa, 
                                                        salarioMensual, otrosIngresos, montoOtrosIngresos, creditoFHA, idUsuarioCreado)
                    VALUES ({$intIdCodeudor},{$intIdClienteCo},{$intIdEngancheCo},'{$strPrimerNombreCo}','{$strSegundoNombreCo}',
                            '{$strPrimerApellidoCo}', '{$strSegundoApellidoCo}','{$strApellidoCasadaCo}','{$strTercerNombreCo}','{$strCorreoCo}',
                            '{$strTelefonoCo}','{$strtelefonoFijoCo}','{$strDireccionCo}','{$strDpiCo}',{$intDeptoCo},{$intMunicipioCo}, 
                            '{$strFechaVencimientoDpiCo}','{$strnitCo}','{$strfechaEmisionDpiCo}','{$strnacionalidadCo}','{$strfechaNacimientoCo}','{$strprofesionCo}',
                            '{$strestadoCivilCo}',{$intDependientesCo},'{$strempresaLaboraCo}','{$strtelefonoReferenciaCo}','{$strpuestoEmpresaCo}','{$strdireccionEmpresaCo}',
                            {$intsalarioMensualCo},'{$strotrosIngresosCo}',{$intmontoOtrosIngresosCo},'{$strfhaCo}',{$id_usuario})
                    ON DUPLICATE KEY UPDATE
                    primerNombre = '{$strPrimerNombreCo}',
                    segundoNombre = '{$strSegundoNombreCo}',
                    primerApellido = '{$strPrimerApellidoCo}',
                    segundoApellido = '{$strSegundoApellidoCo}',
                    apellidoCasada = '{$strApellidoCasadaCo}',
                    correoElectronico ='{$strCorreoCo}',
                    telefono = '{$strTelefonoCo}',
                    direccion = '{$strDireccionCo}',
                    numeroDpi = '{$strDpiCo}',
                    fechaVencimientoDpi = '{$strFechaVencimientoDpiCo}',
                    nit = '{$strnitCo}',
                    fechaEmisionDpi='{$strfechaEmisionDpiCo}' ,
                    Nacionalidad = '{$strnacionalidadCo}' ,
                    fechaNacimiento = '{$strfechaNacimientoCo}' ,
                    estadoCivil = '{$strestadoCivilCo}',
                    empresaLabora = '{$strempresaLaboraCo}' ,
                    puestoLabora = '{$strpuestoEmpresaCo}' ,
                    direccionEmpresa = '{$strdireccionEmpresaCo}' ,
                    salarioMensual = {$intsalarioMensualCo} ,
                    otrosIngresos = '{$strotrosIngresosCo}' ,
                    montoOtrosIngresos = {$intmontoOtrosIngresosCo},
                    departamento = {$intDeptoCo},
                    municipio = {$intMunicipioCo},
                    profesion = '{$strprofesionCo}',
                    noDependientes = {$intDependientesCo},
                    telefonoReferencia = '{$strtelefonoReferenciaCo}' ,
                    telefonoFijo = '{$strtelefonoFijoCo}' ,
                    tercerNombre = '{$strTercerNombreCo}',
                    creditoFHA =  '{$strfhaCo}' ";
                    
    if ($conn->db_query($strQuery)) {
        $title = $intIdCodeudor > 0 ? " Editado" : " Guardado";
        $id_codeudor = $intIdCodeudor > 0 ? $intIdCodeudor : $conn->db_last_id();
        // $_path = "../public/";      
        // foreach($_FILES["fliesDpiReciboCo"]['tmp_name'] as $key => $tmp_name)
        // {
        //     if( $_FILES['archivo']['size'] > 5000000 ) {
        //         $errorMsj=" No se pueden subir archivos con pesos mayores a 5MB";
        //     } else {
        //         $filename = $_FILES["fliesDpiReciboCo"]["name"][$key];
        //         $arr = explode(".", $filename);
        //         $tipo = $arr[sizeof($arr) - 1];
        //         $codigo = str_replace(" ", "", microtime()) . "." . $tipo;
                
        //         $strQuery = "   SELECT *
        //                         FROM archivo 
        //                         WHERE id_cliente = {$intIdClienteCo}
        //                         AND id_codeudor = {$id_codeudor}
        //                         AND estado = 1
        //                         AND nombre = '{$filename}'";
        //         $qTmp = $conn->db_query($strQuery);
        //         if ($conn->db_num_rows($qTmp) <= 0) {
        //             if(move_uploaded_file($_FILES["fliesDpiReciboCo"]["tmp_name"][$key], $_path . $codigo)) {
        //                 $strQuery = "   INSERT INTO archivo (id_cliente, id_codeudor, codigo, tipo, nombre)
        //                                 VALUES ({$intIdClienteCo}, {$id_codeudor}, '{$codigo}', '{$tipo}', '{$filename}')";
        //                 $conn->db_query($strQuery);
        //             }
        //         }
        //     }            
        // }
        $res = array(
            'err' => false,
            "mss" => "Codeudor {$title} exitosamente...",
            "mssEror" => $errorMsj,
            "id" => $id_codeudor,
            "idCliente" => $intIdClienteCo,
            "idEnganche" => $intIdEngancheCo
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_enganche'])) {
    $strQuery = "   INSERT INTO enganche (idEnganche, idCliente,torres,nivel, apartamento, proyecto, adjEnganche, descuento_porcentual,descuento_porcentual_monto, enganchePorc,enganchePorcMonto, 
                                                        parqueosExtras,parqueosExtrasMoto, bodegasExtras, cocina, MontoReserva, fechaPagoReserva, plazoFinanciamiento, 
                                                        fechaPagoInicial, pagosEnganche, pagoPromesa, descuentoPorc, observaciones, 
                                                        idUsuarioCreacion,idVendedor)
                    VALUES ({$intIdEnganche},'{$intIdOcaCliente}',{$strTorreEng},{$strNivelEng},'{$strApartamentoEng}','{$strProyectoEng}','',{$intdescuentoPorcentual},{$intdescuentoPorcentualMonto},{$intenganche},{$intengancheMonto},
                            {$intparqueosExtra},{$intparqueosExtraMoto},{$intbodegaExtra},'{$strCocinaEng}',{$intmontoReserva},'{$strfechaPagoReserva}',{$intplazoFinanciamiento},'{$strfechaPagoInicial}',
                            {$intpagosEnganche},{$intpagoPromesa},{$intdescuento},'{$strobservaciones}' ,{$id_usuario},{$intIdVendedor})
                    ON DUPLICATE KEY UPDATE
                    apartamento= '{$strApartamentoEng}',
                    proyecto='{$strProyectoEng}',
                    nivel= '{$strNivelEng}',
                    descuento_porcentual= {$intdescuentoPorcentual},
                    enganchePorc= {$intenganche}, 
                    descuento_porcentual_monto= {$intdescuentoPorcentualMonto},
                    enganchePorcMonto= {$intengancheMonto}, 
                    parqueosExtras= {$intparqueosExtra}, 
                    parqueosExtrasMoto= {$intparqueosExtraMoto}, 
                    bodegasExtras= {$intbodegaExtra}, 
                    MontoReserva= {$intmontoReserva}, 
                    fechaPagoReserva= '{$strfechaPagoReserva}', 
                    plazoFinanciamiento= {$intplazoFinanciamiento}, 
                    fechaPagoInicial= '{$strfechaPagoInicial}', 
                    pagosEnganche= {$intpagosEnganche}, 
                    pagoPromesa= {$intpagoPromesa}, 
                    descuentoPorc= {$intdescuento}, 
                    torres = {$strTorreEng}, 
                    cocina = '{$strCocinaEng}',
                    idVendedor = {$intIdVendedor},
                    observaciones = '{$strobservaciones}'";
    //echo $strQuery;

    $conn->db_query($strQuery);
    if($intIdEnganche==0){
        $strQueryEn = "SELECT idEnganche 
        FROM enganche
        where apartamento =  '{$strApartamento}'
        and idCliente = $intIdOcaCliente
        order by idEnganche desc limit 1 ;";

        //echo $strQuery;
        $qTmp = $conn ->db_query($strQueryEn);
        $rTmp = $conn->db_fetch_object($qTmp);
        $intIdEnganche = $rTmp->idEnganche;
    }
        // if(isset($_POST['noPago']))
        // {
        //     $strQueryDelete = " DELETE FROM prograEnganche where idEnganche = {$intIdEnganche}";
        //     $conn->db_query($strQueryDelete);
        //     $strQueryDelete = " DELETE FROM prograEngancheDetalle where idEnganche = {$intIdEnganche}";
        //     $conn->db_query($strQueryDelete);
        //     $strQueryPE = " INSERT INTO prograEnganche (idEnganche,montoEnganche,NoPagos,idUsuarioCreacion) 
        //                     VALUES ({$intIdEnganche},{$intengancheMontoTotal},{$intpagosEnganche},1)";
        //     if ($conn->db_query($strQueryPE)){
        //         $strQueryIdPe = "SELECT idPrograEnganche 
        //         FROM prograEnganche
        //         where idEnganche =  {$intIdEnganche}
        //         and montoEnganche = {$intengancheMontoTotal}
        //         and NoPagos = {$intpagosEnganche}
        //         order by fechaCreacion desc limit 1 ;";

        //         //echo $strQuery;
        //         $qTmp = $conn ->db_query($strQueryIdPe);
        //         $rTmp = $conn->db_fetch_object($qTmp);
        //         $intIdPrograEnganche = $rTmp->idPrograEnganche;
        //         foreach($_POST['noPago'] as $idReg =>$idValReg)
        //         {
        //             $cuota=str_replace(',','',$_POST['cuotas'][$idReg]);
        //             $fecha=$_POST['date'][$idReg];
        //             $especial = isset($_POST['chk'][$idReg])?1:0;
                    
        //             $strQueryPED = " INSERT INTO prograEngancheDetalle (idEnganche,idPrograEnganche,noPago,monto,montoPagado,fechaPago,pagoEspecial) 
        //             VALUES ({$intIdEnganche},{$intIdPrograEnganche},{$idValReg},{$cuota},0,'{$fecha}',{$especial})";
        //             $conn->db_query($strQueryPED);
        //         }

        //     }
        // }  
        
    //echo $strQuery;
    
    if ($conn->db_query($strQuery)) {
        $title = $intIdOcaCliente > 0 ? " Editado" : " Guardado";
        $res = array(
            'err' => false,
            "mss" => "Enganche {$title} exitosamente...",
            "id" => $intIdOcaCliente,
            "proyecto" => $strProyecto,
            "clientName" => $strClientName,
            "idEnganche" => $intIdEnganche,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_inspeccion'])) {
    $strQuery = "   UPDATE apartamentos SET 
        precioFha= '{$intPrecioFha}', 
        resguardo= '{$intResguardo}', 
        inspeccion1= '{$strInspeccion1}', 
        inspeccion2= '{$strInspeccion2}', 
        inspeccion3= '{$strInspeccion3}',
        inspeccion1_monto= '{$intInspeccion1Monto}',
        inspeccion2_monto= '{$intInspeccion2Monto}',
        inspeccion3_monto= '{$intInspeccion3Monto}',
        ingresoExpediente= '{$strIngresoExpediente}',
        ingresoExpediente_monto= '{$intIngresoExpedienteMonto}'
    WHERE apartamento = '{$strIdInspeccion}'";

    if ($conn->db_query($strQuery)) {
        $title = $intIdEnganche > 0 ? " Editada" : " Guardada";
        $res = array(
            'err' => false,
            "mss" => "Inspeccion {$title} exitosamente...",
            "idEnganche" => $intIdEnganche,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_cotizacion'])) {
    $subsidio=$strSubsidio='Si'?1:0;
    $strQuery = "   INSERT cotizacion (idCotizacion, proyecto, torres, nivel, nombreCompleto, 
                                                    correo, telefono, apartamento, descuento_porcentual, descuento_porcentual_monto, 
                                                    enganchePorc, enganchePorcMonto, parqueosExtras,parqueosExtrasMoto, bodegasExtras,cocina, 
                                                    MontoReserva, plazoFinanciamiento, pagosEnganche, idUsuarioCreacion, idVendedor,aplica_subsidio,bancoFin)
                    VALUES ({$intIdCotizacion},'{$strProyectoCot}',{$strTorreCot},{$strNivelCot},'{$strNombreCompletoCot}',
                    '{$strCorreoCot}','{$strTelefonoCot}','{$strApartamentoCot}',{$intdescuentoPorcentualCot},{$intdescuentoPorcentualMontoCot},
                            {$intenganche},{$intengancheMontoCot},{$intparqueosExtraCot},{$intparqueosExtraMotoCot},{$intbodegaExtraCot},'{$strCocinaCot}',
                            {$intmontoReservaCot},{$intplazoFinanciamientoCot},{$intpagosEngancheCot},{$id_usuario},{$intIdVendedorCot},{$subsidio},'{$strBancoFin}')
                    ON DUPLICATE KEY UPDATE
                    apartamento= '{$strApartamentoCot}',
                    proyecto='{$strProyectoCot}',
                    nivel= '{$strNivelCot}',
                    descuento_porcentual= {$intdescuentoPorcentualCot},
                    enganchePorc= {$intengancheCot}, 
                    descuento_porcentual_monto= {$intdescuentoPorcentualMontoCot},
                    enganchePorcMonto= {$intengancheMontoCot}, 
                    parqueosExtras= {$intparqueosExtraCot}, 
                    parqueosExtrasMoto= {$intparqueosExtraMotoCot}, 
                    bodegasExtras= {$intbodegaExtraCot}, 
                    MontoReserva= {$intmontoReservaCot}, 
                    plazoFinanciamiento= {$intplazoFinanciamientoCot}, 
                    pagosEnganche= {$intpagosEngancheCot}, 
                    torres = {$strTorreCot}, 
                    idVendedor = {$intIdVendedorCot},
                    aplica_subsidio = {$subsidio},
                    bancoFin = '{$strBancoFin}'";
    //echo $strQuery;

    $conn->db_query($strQuery);
    if($intIdCotizacion==0){
        $strQueryEn = "SELECT idCotizacion 
        FROM cotizacion
        where nombreCompleto =  '{$strNombreCompletoCot}'
        and correo = '{$strCorreoCot}'
        and telefono = '{$strTelefonoCot}'
        order by idCotizacion desc limit 1 ;";

        //echo $strQueryEn;
        $qTmp = $conn ->db_query($strQueryEn);
        $rTmp = $conn->db_fetch_object($qTmp);
        $intIdCotizacion = $rTmp->idCotizacion;
        //echo $intIdCotizacion.'-'.$rTmp->idCotizacion;
    }    
    if ($conn->db_query($strQuery)) {
        $title = $intIdCotizacion > 0 ? " Editado" : " Guardado";
        $res = array(
            'err' => false,
            "mss" => "Cotización {$title} exitosamente...",
            "id" => $intIdCliente,
            "proyecto" => $strProyecto,
            "clientName" => $strClientName,
            "idCotizacion" => $intIdCotizacion,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_formalizar_enganche'])) {
    $strQuery = "   UPDATE enganche SET 
                    formaPago= '{$strformaPago}', 
                    noDepositoReserva= '{$strnoDepositoReserva}', 
                    bancoChequeReserva= '{$strbancoChequeReserva}', 
                    bancoDepositoReserva= '{$strbancoDepositoReserva}', 
                    noChequeReserva= '{$strnoChequeReserva}',
                    observacionesForm='{$strObservacionesForm}',
                    noReciboReserva='{$strNoReciboEng}'
                    
                    WHERE idEnganche = {$intIdEnganche}";
    //echo $strQuery;
    if($conn->db_query($strQuery)){
        $strQueryCom = "SELECT e.idEnganche, idGlobal,e.apartamento,e.descuento_porcentual_monto,(dg.cambioDolar * a.precio) as precio,
                    ((dg.cambioDolar * dg.parqueoExtra)*e.parqueosExtras) as parqueoExtra,((dg.cambioDolar * a.bodega_precio) * bodegasExtras) as bodegaPrecio
                    FROM enganche e
                    INNER JOIN apartamentos a ON e.apartamento = a.apartamento
                    INNER JOIN datosGlobales dg ON a.idProyecto = dg.idGlobal
                    LEFT JOIN pagoFinal pf ON e.idEnganche = pf.idEnganche
                    INNER JOIN agregarCliente ac ON e.idCliente = ac.idCliente
                    where e.idEnganche = {$intIdEnganche}";
        $qTmpC = $conn ->db_query($strQueryCom);
        while($rTmp = $conn->db_fetch_object($qTmpC)){

            $totalApartamentoCom = $rTmp->precio + $rTmp->parqueoExtra + $rTmp->bodegaPrecio - $rTmp->descuento_porcentual_monto;
            $totalComisionApartamentoCom = round($totalApartamentoCom * 0.91491245);

            $strQueryCom = " UPDATE apartamentoComisiones SET estado = 0
            WHERE apartamento = '{$rTmp->apartamento}' ";
            $conn->db_query($strQueryCom);
            $strQueryCom = " INSERT INTO apartamentoComisiones (proyecto,apartamento,precioVenta,precioIva,precioTimbres,precioComision,
            iva,timbres,constante,idEnganche) 
            VALUES({$rTmp->idGlobal},'{$rTmp->apartamento}',{$totalApartamentoCom},0,0,{$totalComisionApartamentoCom},12,3,0.91491245,{$intIdEnganche})";
            $conn->db_query($strQueryCom);
        }
        $strQueryApto = "SELECT apartamento 
        FROM enganche
        where idEnganche = {$intIdEnganche};";
    
        //echo $strQueryEn;
        $qTmp = $conn ->db_query($strQueryApto);
        $rTmp = $conn->db_fetch_object($qTmp);
        $apartamento = $rTmp->apartamento;
        $strQueryUpdate = "   UPDATE apartamentos SET 
                    estado= 3
                    WHERE apartamento = '{$apartamento}'";
    //echo $strQuery;
        $conn->db_query($strQueryUpdate);
        $comentarioBitacora='Se ha vendido apartamento '.$apartamento;
        insertBitacora($id_usuario,$comentarioBitacora,$conn);
        
        $strQueryInsertReserva = " INSERT INTO reservaApartamento ( proyecto,torre,nivel,apartamento,noPagoReserva,montoReserva,
        fechaPagoReserva,noReciboReserva,formaPago,bacoDepositoReserva,noDepositoReserva,bacoChequeReserva,noChequeReserva,
        observaciones,idVendedor,idUsuarioCreacion,nombreCompleto)
        SELECT e.proyecto,e.torres,e.nivel,e.apartamento,1,e.MontoReserva,e.fechaPagoReserva,e.noReciboReserva,e.formaPago,e.bancoDepositoReserva,
        e.noDepositoReserva,e.bancoChequeReserva,e.noChequeReserva,e.observaciones,e.idVendedor,e.idUsuarioCreacion,
        IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name
        FROM enganche e
        inner join agregarCliente a on e.idCliente = a.idCliente AND a.estado = 1
        WHERE e.idEnganche = {$intIdEnganche} ";
        //echo $strQueryInsertReserva;
        $conn->db_query($strQueryInsertReserva);
        
        if(isset($_POST['noPago']))
        {
            $strQueryDelete = " DELETE FROM prograEnganche where idEnganche = {$intIdEnganche}";
            $conn->db_query($strQueryDelete);
            $strQueryDelete = " DELETE FROM prograEngancheDetalle where idEnganche = {$intIdEnganche} AND pagado=0";
            $conn->db_query($strQueryDelete);
                $strQueryEn = "SELECT (SELECT count(*) FROM prograEngancheDetalle ped where pagado =1 and ped.idEnganche = e.idEnganche) as pagosRealizados, 
                (SELECT sum(montoPagado) FROM prograEngancheDetalle ped where pagado =1 and ped.idEnganche = e.idEnganche) as pagado, 
                (e.enganchePorcMonto -e.MontoReserva) as enganchePorcMonto,e.pagosEnganche
                FROM enganche e
                where idEnganche = {$intIdEnganche}";

                //echo $strQueryEn;
                $qTmp = $conn ->db_query($strQueryEn);
                $rTmp = $conn->db_fetch_object($qTmp);
                $intengancheMontoTotal = $rTmp->enganchePorcMonto;
                $pagado = $rTmp->pagado;
                $intpagosEnganche = $rTmp->pagosEnganche;
                $pagosRealizados = $rTmp->pagosRealizados;
                $montoRestante = $enganchePorcMonto - $pagado;
                $pagosRestante = $intpagosEnganche - $pagosRealizados;
                
            $strQueryPE = " INSERT INTO prograEnganche (idEnganche,montoEnganche,NoPagos,idUsuarioCreacion) 
                            VALUES ({$intIdEnganche},{$intengancheMontoTotal},{$intpagosEnganche},{$id_usuario})";
            if ($conn->db_query($strQueryPE)){
                $strQueryIdPe = "SELECT idPrograEnganche 
                FROM prograEnganche
                where idEnganche =  {$intIdEnganche}
                and montoEnganche = {$intengancheMontoTotal}
                and NoPagos = {$intpagosEnganche}
                order by fechaCreacion desc limit 1 ;";

                //echo $strQuery;
                $qTmp = $conn ->db_query($strQueryIdPe);
                $rTmp = $conn->db_fetch_object($qTmp);
                $intIdPrograEnganche = $rTmp->idPrograEnganche;
                foreach($_POST['noPago'] as $idReg =>$idValReg)
                {
                    $fecha=$_POST['date'][$idReg];
                    $especial = $_POST['chk'][$idReg]=='on'?1:0;
                    if($idValReg>$pagosRealizados){
                       $cuota=str_replace(',','',$_POST['cuotas'][$idReg]);
                        
                        $strQueryPED = " INSERT INTO prograEngancheDetalle (idEnganche,idPrograEnganche,noPago,monto,montoReal,montoPagado,fechaPago,pagoEspecial) 
                        VALUES ({$intIdEnganche},{$intIdPrograEnganche},{$idValReg},{$cuota},{$cuota},0,'{$fecha}',{$especial})";

                        //echo $strQueryPED;
                        $conn->db_query($strQueryPED);
                        $montoRestante=$montoRestante - $cuota;
                        $pagosRestante= $pagosRestante - 1;
                    }
                    

                }

            }
        }
    }
      
        
    //echo $strQuery;
    
    if ($conn->db_query($strQuery)) {
        $title = $intIdOcaCliente > 0 ? " Editado" : " Guardado";
        $res = array(
            'err' => false,
            "mss" => "Enganche {$title} exitosamente...",
            "id" => $intIdCliente,
            "proyecto" => $strProyecto,
            "clientName" => $strClientName,
            "idEnganche" => $intIdEnganche,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_monto_reserva'])) {

    $strQuery = "SELECT SUM(ra.montoReserva) as total, a.limiteReserva 
                    FROM `reservaApartamento` ra
                    INNER JOIN apartamentos a ON ra.apartamento = a.apartamento
                    WHERE ra.apartamento = '{$strApartamentoCot}' 
                    AND ra.desistido = 0 
                    AND ra.estado = 1";
    $qTmp = $conn ->db_query($strQuery);
    $rTmp = $conn->db_fetch_object($qTmp);
    $total = $rTmp->total;
    $limiteReserva = $rTmp->limiteReserva;

    if(intval($total)<=intval($limiteReserva)){
        
        $strQuery = "   INSERT INTO reservaApartamento (idReserva, proyecto,torre,nivel,apartamento,noPagoReserva,montoReserva,
        fechaPagoReserva,noReciboReserva,formaPago,bacoDepositoReserva,noDepositoReserva,bacoChequeReserva,noChequeReserva,
        observaciones,idVendedor,idUsuarioCreacion,nombreCompleto,numeroDpi)
            VALUES ({$intIdReserva},'{$strProyectoCot}','{$strTorreCot}','{$strNivelCot}','{$strApartamentoCot}',
            (SELECT  IFNULL(MAX(noPagoReserva),0)+1 FROM reservaApartamento ra WHERE ra.proyecto = '{$strProyectoCot}' AND ra.torre ='{$strTorreCot}' AND ra.nivel = '{$strNivelCot}' AND ra.apartamento =  '{$strApartamentoCot}' ),
            {$intMontoReserva},'{$strFechaPago}','{$strNoReciboEng}','{$strformaPago}','{$strbancoDepositoReserva}','{$strnoDepositoReserva}',
            '{$strbancoChequeReserva}','{$strnoChequeReserva}','{$strObservacionesForm}',$intIdVendedorCot,{$id_usuario},'{$strNombreCompleto}','{$strNumeroDpi}' )
            ON DUPLICATE KEY UPDATE
            montoReserva = values(montoReserva),
            fechaPagoReserva = values(fechaPagoReserva),
            noReciboReserva = values(noReciboReserva),
            formaPago = values(formaPago),
            bacoDepositoReserva = values(bacoDepositoReserva),
            noDepositoReserva = values(noDepositoReserva),
            bacoChequeReserva = values(bacoChequeReserva),
            noChequeReserva = values(noChequeReserva),
            observaciones = values(observaciones),
            idVendedor = values(idVendedor),
            nombreCompleto = values(nombreCompleto),
            numeroDpi = values(numeroDpi)";
        //echo $strQuery;
        if($conn->db_query($strQuery)){
        $strQueryUpdate = "UPDATE apartamentos SET 
                    estado= 2
                    WHERE apartamento = '{$strApartamentoCot}' and estado !=3";
        $conn->db_query($strQueryUpdate);
        $comentarioBitacora='agregar_editar_monto_reserva Se ha reservado apartamento '.$strApartamentoCot;
        insertBitacora($id_usuario,$comentarioBitacora,$conn);

        if($intIdReserva==0){
        $strQueryEn = "SELECT idReserva 
        FROM reservaApartamento
        where apartamento =  '{$strApartamentoCot}'
        order by idReserva desc limit 1 ;";

        //echo $strQuery;
        $qTmp = $conn ->db_query($strQueryEn);
        $rTmp = $conn->db_fetch_object($qTmp);
        $intIdReserva = $rTmp->idReserva;

        }
        $title = $intIdReserva > 0 ? " Editado" : " Guardado";
        $res = array(
        'err' => false,
        "mss" => "Reserva {$title} exitosamente...",
        "id" => $intIdReserva,
        "proyecto" => $strProyectoCot,
        "clientName" => $strNombreCompleto,
        "idReserva" => $intIdReserva,
        "query" => $strQuery." - ".$strQueryU
        );
        } else {
            $res['mss'] = "Ha ocurrido un error...";
        }

    } else {
        $res['mss'] = "No se puede guardar el pago, porque sobrepasa el limite de reserva del apartamento";
    }


}
if (isset($_GET['eliminar_monto_reserva'])) {
    $strQueryMR = "SELECT proyecto,apartamento 
            FROM reservaApartamento
            where idReserva =  '{$intIdReserva}'
            order by idReserva desc limit 1 ;";
    
            //echo $strQuery;
            $qTmp = $conn ->db_query($strQueryMR);
            $rTmp = $conn->db_fetch_object($qTmp);
            $apartamento = $rTmp->apartamento;
            $proyecto = $rTmp->proyecto;
    //echo $strQuery;
    if($conn->db_query($strQueryMR)){
        $strQueryUpdate = "UPDATE reservaApartamento SET 
                            estado= 0
                            WHERE idReserva = '{$intIdReserva}'";
        $conn->db_query($strQueryUpdate);
        
            $strQueryEn = "SELECT IFNULL(SUM(montoReserva),0) as totalReserva
            FROM reservaApartamento
            where apartamento =  '{$apartamento}'
            AND proyecto = '{$proyecto}'
            AND estado = 1 AND desistido =0 ; ;";
    
            //echo $strQuery;
            $qTmp = $conn ->db_query($strQueryEn);
            $rTmp = $conn->db_fetch_object($qTmp);
            $totalReserva = $rTmp->totalReserva;    
            if($totalReserva<=0){
                $strQueryUpdate = "UPDATE apartamentos SET 
                estado= 1
                WHERE apartamento = '{$apartamento}'";
                $conn->db_query($strQueryUpdate);
                $comentarioBitacora='eliminar_monto_reserva Se ha reservado apartamento '.$apartamento;
                insertBitacora($id_usuario,$comentarioBitacora,$conn);
            }       
        $title = $intIdReserva > 0 ? " Editado" : " Guardado";
        $res = array(
            'err' => false,
            "mss" => "Reserva {$title} exitosamente...",
            "id" => $intIdReserva,
            "proyecto" => $strProyectoCot,
            "clientName" => $strNombreCompleto,
            "idReserva" => $intIdReserva,
            "query" => $strQuery." - ".$strQueryU
        );
    }else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_formalizar_enganche_validar'])) {
    $strQuery = "   UPDATE enganche SET
                    apartamento= '{$strApartamentoEng}',
                    proyecto='{$strProyectoEng}',
                    nivel= '{$strNivelEng}',
                    descuento_porcentual= {$intdescuentoPorcentual},
                    enganchePorc= {$intenganche}, 
                    descuento_porcentual_monto= {$intdescuentoPorcentualMonto},
                    enganchePorcMonto= {$intengancheMonto}, 
                    parqueosExtras= {$intparqueosExtra}, 
                    bodegasExtras= {$intbodegaExtra}, 
                    MontoReserva= {$intmontoReserva}, 
                    fechaPagoReserva= '{$strfechaPagoReserva}', 
                    plazoFinanciamiento= {$intplazoFinanciamiento}, 
                    fechaPagoInicial= '{$strfechaPagoInicial}', 
                    pagosEnganche= {$intpagosEnganche}, 
                    pagoPromesa= {$intpagoPromesa}, 
                    descuentoPorc= {$intdescuento}, 
                    torres = {$strTorreEng}, 
                    cocina = '{$strCocinaEng}',
                    idVendedor = {$intIdVendedor},
                    observaciones = '{$strobservaciones}',
                    validado = 1
                    WHERE idEnganche = {$intIdEnganche}";
    //echo $strQuery;

    $conn->db_query($strQuery);
    if($intIdEnganche==0){
        $strQueryEn = "SELECT idEnganche 
        FROM enganche
        where apartamento =  '{$strApartamento}'
        and idCliente = $intIdOcaCliente
        order by idEnganche desc limit 1 ;";

        //echo $strQuery;
        $qTmp = $conn ->db_query($strQueryEn);
        $rTmp = $conn->db_fetch_object($qTmp);
        $intIdEnganche = $rTmp->idEnganche;
    }
    if ($conn->db_query($strQuery)) {
        $title = $intIdOcaCliente > 0 ? " Editado" : " Guardado";
        $res = array(
            'err' => false,
            "mss" => "Enganche {$title} exitosamente...",
            "id" => $intIdOcaCliente,
            "proyecto" => $strProyecto,
            "clientName" => $strClientName,
            "idEnganche" => $intIdEnganche,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_pago'])) {
    $strQueryEnganche = "SELECT pe.*,ped.monto,ped.montoPagado FROM prograEnganche pe
    INNER JOIN prograEngancheDetalle ped ON pe.idEnganche = ped.idEnganche
    WHERE idDetalle = {$intIdPago};";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQueryEnganche);
    $rTmp = $conn->db_fetch_object($qTmp);
    $intIdEnganche = $rTmp->idEnganche;
    $montoAnterior = $rTmp->monto;
    $montoPagadoAnterior = $rTmp->montoPagado;
    $strQueryEn = "SELECT * FROM prograEngancheDetalle ped 
        WHERE idDetalle = {$intIdPago};";

        //echo strQueryEn;
        $qTmp = $conn ->db_query($strQueryEn);
        $rTmp = $conn->db_fetch_object($qTmp);
        $idPrograEnganche = $rTmp->idPrograEnganche;

        $strQueryPen = "SELECT pe.*,SUM(case when pagado =1 then monto else 0 end) as pagado,
                        (montoEnganche - SUM(case when pagado =1 then monto else 0 end)) as pendiente,SUM(case when pagado =0 then 1 else 0 end) as pagosPendientes
                        FROM prograEnganche pe
                        INNER JOIN prograEngancheDetalle ped ON pe.idEnganche = ped.idEnganche
                        WHERE pe.idEnganche={$intIdEnganche};";

        //echo $strQuery;
        $qTmp = $conn ->db_query($strQueryPen);
        $rTmp = $conn->db_fetch_object($qTmp);
        $pendienteAntes = $rTmp->pendiente;
    $fechaReal = date("Y-m-d H:i:s");
    $strQuery = "   UPDATE prograEngancheDetalle SET 
                    fechaPagoRealizado= '{$strFechaPago}', 
                    tipoPago= '{$strTipoPago}', 
                    bancoDeposito= '{$strbancoDeposito}', 
                    noDeposito= '{$strnoDeposito}', 
                    bancoCheque= '{$strbancoCheque}',
                    noCheque = '{$strnoCheque}',
                    fechaPagoRealizadoReal = '{$fechaReal}',
                    idUsuarioPago = {$id_usuario},
                    pagado = 1,
                    montoPagado =  '{$intMonto}',
                    monto =  '{$intMonto}',
                    observaciones = '{$strObservaciones}',
                    noRecibo = '{$strNoRecibo}'
                    WHERE idDetalle = {$intIdPago}";
    //echo $strQuery;

    $conn->db_query($strQuery);
  
        
    //echo $strQuery;
    
    if ($conn->db_query($strQuery)) {
        $comentarioBitacora='agregar_editar_pago Se ha modificado pago '.$intIdPago;
        insertBitacora($id_usuario,$comentarioBitacora,$conn);
        $strQueryEn = "SELECT * FROM prograEngancheDetalle ped 
        WHERE idDetalle = {$intIdPago};";

        //echo strQueryEn;
        $qTmp = $conn ->db_query($strQueryEn);
        $rTmp = $conn->db_fetch_object($qTmp);
        $idPrograEnganche = $rTmp->idPrograEnganche;

        $strQueryPen = "SELECT pe.*,SUM(case when pagado =1 then monto else 0 end) as pagado,
                        (montoEnganche - SUM(case when pagado =1 AND pagoEspecial = 0 then monto else 0 end) - SUM(case when pagoEspecial =1 then monto else 0 end)) as pendiente,
                        SUM(case when pagado =0 AND pagoEspecial = 0 then 1 else 0 end) as pagosPendientes
                        FROM prograEnganche pe
                        INNER JOIN prograEngancheDetalle ped ON pe.idEnganche = ped.idEnganche
                        WHERE pe.idEnganche={$intIdEnganche};";

        //echo $strQueryPen;
        $qTmp = $conn ->db_query($strQueryPen);
        $rTmp = $conn->db_fetch_object($qTmp);
        $pendiente = $rTmp->pendiente;
        $pagosPendientes = $rTmp->pagosPendientes;
        $montoEnganche = $rTmp->montoEnganche;
        $pagado = $rTmp->pagado;
        if($pendiente>=0){
            if($pagosPendientes > 0){
                $cuotaNueva = $pendiente / $pagosPendientes;
            }else
             {
                $cuotaNueva = 0; 
             }
            $strQuery = "   UPDATE prograEngancheDetalle SET 
            monto = {$cuotaNueva}
            WHERE idEnganche = {$intIdEnganche}
            AND pagado=0
            AND pagoEspecial = 0";
            $conn->db_query($strQuery);
        }else{
            // if($pendienteAntes<0){
            //     $montoAccion = $intMonto;
            // }else{
            //     $montoAccion=abs($pendiente);
            // }
            
            // $fechaReal = date("Y-m-d H:i:s");
            // $strQueryC = "INSERT INTO contrapagos (idEnganche, accion, monto, observaciones, fechaCreacion, idUsuario,enganche)
            //                 VALUES ({$intIdEnganche},'restar',{$montoAccion },'Pago sobrepasa el monto de enganche','{$fechaReal}',{$id_usuario},1)";
            // $conn->db_query($strQueryC);
            // $strQuery = "   UPDATE prograEngancheDetalle SET 
            // monto = 0
            // WHERE idEnganche = {$intIdEnganche}
            // AND pagado = 0";
            // $conn->db_query($strQuery);
        }  
        $title = $intIdOcaCliente > 0 ? " Editado" : " Guardado";
        $res = array(
            'err' => false,
            "mss" => "Pago {$title} exitosamente...",
            "idEnganche" => $intIdEnganche,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}



if (isset($_GET['agregar_editar_pago_comision'])) {
    $fechaReal = date("Y-m-d H:i:s");
    $strQuery = "INSERT INTO pagosComision (idPagoComision,noPago,idFormaPagoComisiones,apartamento,monto,
                                    noCheque,bancoPago,fechaPago,fechaRealPago,idUsuarioPago,observaciones,idEnganche)
                    VALUES ({$intIdPagoCom},{$intNoPagoComision},{$intIdFormaPagoComision},'{$strPpartamentoPagoComision}',{$intMontoCom},
                            '{$strnoDepositoCom}','{$strbancoChequeCom}','{$strFechaPagoCom}','{$fechaReal}',{$id_usuario},'{$strObservacionesCom}',{$intIdEnganche} )
                    ON DUPLICATE KEY UPDATE
                    fechaPago= '{$strFechaPagoCom}', 
                    bancoPago= '{$strbancoChequeCom}', 
                    noCheque= '{$strnoDepositoCom}', 
                    fechaRealPago = '{$fechaReal}',
                    idUsuarioPago = {$id_usuario},
                    monto =  '{$intMontoCom}',
                    observaciones = '{$strObservacionesCom}'";
                    //echo $strQuery ;
    if ($conn->db_query($strQuery)) {
        $title = $intIdPago > 0 ? " Editado" : " Guardado";
        $id_pago_comision = $intIdPago > 0 ? $intIdPago : $conn->db_last_id();
        $res = array(
            'err' => false,
            "mss" => "Pago Comisión ".$title ." exitosamente...",
            "idPagoComision" => $id_pago_comision,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}

if (isset($_GET['agregar_editar_pago_extra'])) {
    $fechaReal = date("Y-m-d H:i:s");
    $strQuery = "INSERT INTO cobrosExtras (idCobro,noPago,montoPagado,tipoPago,bancoDeposito,
                                    noDeposito,bancoCheque,fechaPagoRealizado,fechaPagoRealizadoReal,idUsuarioPago,
                                    observaciones,idEnganche,tipoCobroExtra,noRecibo, validado)
                    VALUES ({$intIdPagoExtra},(SELECT IFNULL(count(noPago),0)+1 FROM cobrosExtras ce WHERE ce.idEnganche = {$intIdEnganche} ORDER BY noPago DESC ),{$intMontoExtra},'{$strTipoPagoExtra}','{$strbancoDepositoExtra}',
                    '{$strnoDepositoExtra}','{$strbancoChequeExtra}','{$strFechaPagoExtra}','{$fechaReal}',{$id_usuario},
                    '{$strObservacionesExtra}',{$intIdEnganche},'{$strTipoCobroExtra}','{$strNoReciboExtra}',1 )
                    ON DUPLICATE KEY UPDATE
                    fechaPagoRealizado= '{$strFechaPagoExtra}', 
                    bancoCheque= '{$strbancoChequeExtra}', 
                    bancoDeposito= '{$strbancoDepositoExtra}', 
                    noDeposito= '{$strnoDepositoExtra}',
                    noRecibo= '{$strNoReciboExtra}', 
                    tipoPago= '{$strTipoPagoExtra}', 
                    fechaPagoRealizadoReal = '{$fechaReal}',
                    idUsuarioPago = {$id_usuario},
                    montoPagado =  '{$intMontoExtra}',
                    observaciones = '{$strObservacionesExtra}',
                    tipoCobroExtra = '{$strTipoCobroExtra}'";
                    //echo $strQuery ;
    if ($conn->db_query($strQuery)) {
        $title = $intIdCobro > 0 ? " Editado" : " Guardado";
        $id_pago_extra = $intIdCobro > 0 ? $intIdCobro : $conn->db_last_id();
        $res = array(
            'err' => false,
            "mss" => "Pago extra ".$title ." exitosamente...",
            "idEnganche" => $intIdEnganche,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_pago_extra_eng'])) {
    $fechaReal = date("Y-m-d H:i:s");
    $strQuery = "INSERT INTO cobrosExtras (idCobro,noPago,montoPagado,tipoPago,bancoDeposito,
                                    noDeposito,bancoCheque,fechaPagoRealizado,fechaPagoRealizadoReal,idUsuarioPago,
                                    observaciones,idEnganche,tipoCobroExtra,noRecibo, validado)
                    VALUES ({$intIdPagoExtraEng},( SELECT IF(MAX(ce.noPago)>0,max(ce.noPago),(select MAX(ped.noPago) as no_pago from prograEngancheDetalle ped where ped.idEnganche = {$intIdEnganche} ORDER BY ped.noPago DESC ))+1 FROM cobrosExtras ce WHERE ce.idEnganche = {$intIdEnganche} and tipoCobroExtra ='enganche' ORDER BY noPago DESC ),{$intMontoExtraEng},'{$strTipoPagoExtraEng}','{$strbancoDepositoExtraEng}',
                    '{$strnoDepositoExtraEng}','{$strbancoChequeExtraEng}','{$strFechaPagoExtraEng}','{$fechaReal}',{$id_usuario},
                    '{$strObservacionesExtraEng}',{$intIdEnganche},'{$strTipoCobroExtraEng}','{$strNoReciboExtraEng}',0 )
                    ON DUPLICATE KEY UPDATE
                    fechaPagoRealizado= '{$strFechaPagoExtraEng}', 
                    bancoCheque= '{$strbancoChequeExtraEng}', 
                    bancoDeposito= '{$strbancoDepositoExtraEng}', 
                    noDeposito= '{$strnoDepositoExtraEng}',
                    noRecibo= '{$strNoReciboExtraEng}', 
                    tipoPago= '{$strTipoPagoExtraEng}', 
                    fechaPagoRealizadoReal = '{$fechaReal}',
                    idUsuarioPago = {$id_usuario},
                    montoPagado =  '{$intMontoExtraEng}',
                    observaciones = '{$strObservacionesExtraEng}',
                    tipoCobroExtra = '{$strTipoCobroExtraEng}'";
                    //echo $strQuery ;
    if ($conn->db_query($strQuery)) {
        $title = $intIdCobro > 0 ? " Editado" : " Guardado";
        $id_pago_extra = $intIdCobro > 0 ? $intIdCobro : $conn->db_last_id();
        $res = array(
            'err' => false,
            "mss" => "Pago extra ".$title ." exitosamente...",
            "idEnganche" => $intIdEnganche,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}


if (isset($_GET['agregar_editar_pago_validar'])) {

    $strQueryEn = "SELECT idDetalle FROM `prograEngancheDetalle` where idEnganche ={$intIdEnganche}
    and validado = 0 and pagado=1
    order by noPago ASC
    limit 1";
    $qTmp = $conn ->db_query($strQueryEn);
    $rTmp = $conn->db_fetch_object($qTmp);
    $idDetallePendienteValidar = $rTmp->idDetalle;

    if($idDetallePendienteValidar == $intIdPago){
        $strQuery = "   UPDATE prograEngancheDetalle SET 
        validado = 1
        WHERE idDetalle = {$intIdPago}";
        if($conn->db_query($strQuery)){

            $title = "validado";
            $res = array(
                'err' => false,
                "mss" => "Pago {$title} exitosamente...",
                "idEnganche" => $intIdEnganche,
                "query" => $strQuery." - ".$strQueryU
            );
        } else {
            $res['mss'] = "Ha ocurrido un error...";
        }
    }else{
        $res['mss'] = "Pago no se puede validar ya que hay un pago anterior que aún no se ha validado";
    }
}
if (isset($_GET['agregar_editar_pago_enganche_validar'])) {

    $strQueryEn = "SELECT idCobro FROM `cobrosExtras` where idEnganche ={$intIdEnganche}
    and validado = 0 AND tipoCobroExtra = 'enganche'
    order by noPago ASC
    limit 1";
    $qTmp = $conn ->db_query($strQueryEn);
    $rTmp = $conn->db_fetch_object($qTmp);
    $idDetallePendienteValidar = $rTmp->idCobro;

    if($idDetallePendienteValidar == $intIdPagoExtraEng){
        $strQuery = "   UPDATE cobrosExtras SET 
        validado = 1
        WHERE idCobro = {$intIdPagoExtraEng}";
        if($conn->db_query($strQuery)){

            $title = "validado";
            $res = array(
                'err' => false,
                "mss" => "Pago {$title} exitosamente...",
                "idEnganche" => $intIdEnganche,
                "query" => $strQuery." - ".$strQueryU
            );
        } else {
            $res['mss'] = "Ha ocurrido un error...";
        }
    }else{
        $res['mss'] = "Pago no se puede validar ya que hay un pago anterior que aún no se ha validado";
    }
}
if (isset($_GET['agregar_editar_pago_validar_reserva'])) {

    $strQuery = "   UPDATE reservaApartamento SET 
    validado = 1
    WHERE idReserva = {$intIdReserva}";
    if($conn->db_query($strQuery)){
        $comentarioBitacora='agregar_editar_pago_validar_reserva Se ha validado reserva apartamento '.$rTmp->apartamento;
        insertBitacora($id_usuario,$comentarioBitacora,$conn);

        $title = "validado";
        $res = array(
            'err' => false,
            "mss" => "Pago {$title} exitosamente...",
            "idReserva" => $intIdReserva,
            "query" => $strQuery
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
    
}
if (isset($_GET['agregar_editar_pago_validar_comision'])) {

    $strQuery = "   UPDATE pagosComision SET 
    entregado = 1
    WHERE idPagoComision = {$intIdPagoCom}";
    if($conn->db_query($strQuery)){

        $title = "validado";
        $res = array(
            'err' => false,
            "mss" => "Pago {$title} exitosamente...",
            "idEnganche" => $intIdEnganche,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_pago_final'])) {
    $fechaReal = date("Y-m-d H:i:s");
    $strQuery = "INSERT pagoFinal (idEnganche, monto, fechaPago, tipoPago, bancoDeposito, 
                                    noDeposito, bancoCheque, noCheque, fechaPagoReal, idUsuario)
                    VALUES ({$intIdEnganche},{$intMontoF},'{$strFechaPagoF}','{$strTipoPagoF}','{$strbancoDepositoF}','{$strTipoDesembolso}'
                            '{$strnoDepositoF}', '{$strbancoChequeF}','{$strnoChequeF}','{$fechaReal}',{$id_usuario} )
                    ON DUPLICATE KEY UPDATE
                    fechaPago= '{$strFechaPagoF}', 
                    tipoPago= '{$strTipoPagoF}', 
                    bancoDeposito= '{$strbancoDepositoF}', 
                    noDeposito= '{$strnoDepositoF}', 
                    bancoCheque= '{$strbancoChequeF}',
                    tipoDesembolso='{$strTipoDesembolso}',
                    noCheque = '{$strnoChequeF}',
                    fechaPagoReal = '{$fechaReal}',
                    idUsuario = {$id_usuario},
                    monto =  '{$intMontoF}'";
    //echo $strQuery;

    $conn->db_query($strQuery);
  
        
    //echo $strQuery;
    
    if ($conn->db_query($strQuery)) {
        $res = array(
            'err' => false,
            "mss" => "Pago Final agregado exitosamente...",
            "idEnganche" => $intIdEnganche,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if (isset($_GET['agregar_editar_contra'])) {
    $fechaReal = date("Y-m-d H:i:s");
    $strQuery = "INSERT contrapagos (idContraPago, idEnganche, accion, monto, observaciones, fechaCreacion, idUsuario)
                    VALUES ({$intIdContra},{$intIdEnganche},'{$strAccion}',{$intMontoContra},'{$strObsContra}','{$fechaReal}',{$id_usuario})
                    ON DUPLICATE KEY UPDATE
                    accion= '{$strAccion}', 
                    monto= '{$intMontoContra}', 
                    observaciones= '{$strObsContra}'";
    //echo $strQuery;

    $title = $intIdContra > 0 ? " Editado" : " Guardado";
    if ($conn->db_query($strQuery)) {
        $res = array(
            'err' => false,
            "mss" => "Contracargo {$title} exitosamente...",
            "idEnganche" => $intIdEnganche,
            "query" => $strQuery." - ".$strQueryU
        );
    } else {
        $res['mss'] = "Ha ocurrido un error...";
    }
}
if(isset($_GET['get_niveles_proyecto'])){
    
    $strQuery = "SELECT n.idNivel, n.noNivel as level
                    FROM niveles n
                    WHERE n.idTorre ={$intTorre}
                    and ((SELECT count(apartamento) FROM apartamentos a WHERE a.estado in(1,2) and a.idNivel = n.idNivel)>=1 or
                     n.idNivel = {$intNivelSelect})
                    ORDER BY n.noNivel";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "levels" => $arr
    );
}
if(isset($_GET['get_niveles_proyecto_com'])){
    
    $strQuery = "SELECT n.idNivel, n.noNivel as level
                    FROM niveles n
                    WHERE n.idTorre ={$intTorre}
                    ORDER BY n.noNivel";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "levels" => $arr
    );
}
if(isset($_GET['get_torres_proyecto'])){

    $strQuery = "SELECT idTorre,noTorre as torre
                    FROM torres t 
                    INNER JOIN datosGlobales dg ON t.proyecto = dg.idGlobal
                    WHERE t.proyecto = '{$strProyecto}'
                    AND dg.proyecto in ({$proyectos})
                    ORDER BY noTorre";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "torres" => $arr
    );
}
if(isset($_GET['get_torres_proyecto_com'])){

    $strQuery = "SELECT idTorre,noTorre as torre
                    FROM torres t 
                    INNER JOIN datosGlobales dg ON t.proyecto = dg.idGlobal
                    WHERE t.proyecto = '{$strProyecto}'
                    AND dg.proyecto in ({$proyectos})
                    ORDER BY noTorre";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "torres" => $arr
    );
}
if(isset($_GET['get_departamentos'])){

    $strQuery = "SELECT * 
                    FROM catDepartamento
                    where id_pais=1
                    ORDER BY nombre_depto";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "departamentos" => $arr
    );
}
if(isset($_GET['get_tipo_comision'])){

    $strQuery = "SELECT distinct(descripcion) as tipo
                    FROM catTipoComision
                    where suspendido=0
                    ORDER BY descripcion";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "tipoComision" => $arr
    );
}

if(isset($_GET['get_bancos'])){

    $strQuery = "SELECT * 
                    FROM catBanco
                    where suspendido = 0
                    ORDER BY banco";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "bancos" => $arr
    );
}
if(isset($_GET['get_bancos_financiar'])){

    $strQuery = "SELECT * 
                    FROM catBanco
                    where suspendido = 0
                    AND bancosFinanciar = 1
                    ORDER BY banco";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "bancos" => $arr
    );
}
if(isset($_GET['get_informacion_banco'])){
    $strQuery = "SELECT proyecto 
                FROM enganche
                where idEnganche={$intIdEngancheRes}";

    $qTmp = $conn ->db_query($strQuery);
    $rTmp = $conn->db_fetch_object($qTmp);
    $proyecto = $rTmp->proyecto;
    $interes = '*';
    if($proyecto=='Marabi'){
        $interes = 'interes_marabi as interes';
    }else if($proyecto=='Naos'){
        $interes = 'interes_naos as interes';
    }

    $strQuery = "SELECT {$interes} 
                    FROM catBanco
                    where suspendido = 0
                    AND bancosFinanciar = 1
                    AND banco='{$strBancoInteres}'
                    ORDER BY banco";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "bancos" => $arr
    );
}
if(isset($_GET['get_progra_detalle'])){

    $strQuery = "SELECT * 
                    FROM prograEnganche pe
                    INNER JOIN prograEngancheDetalle ped on pe.idEnganche = ped.idEnganche
                    where pe.idEnganche={$intIdEnganche}
                    ORDER BY ped.noPago";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $strQueryEn = "SELECT MontoReserva, fechaPagoReserva 
                    FROM enganche pe
                    WHERE pe.idEnganche={$intIdEnganche}";

//echo $strQuery;
$qTmp = $conn ->db_query($strQueryEn);
$rTmp = $conn->db_fetch_object($qTmp);
$reserva = $rTmp->MontoReserva;
$fechaReserva = $rTmp->fechaPagoReserva;
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "detallePagos" => $arr,
        "reserva" => $reserva,
        "fechaReserva" => $fechaReserva
    );
}
if(isset($_GET['get_pago_info'])){

    $strQuery = "SELECT * 
                    FROM prograEngancheDetalle ped
                    where ped.idDetalle={$intIdDetalle}
                    ORDER BY ped.noPago";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "detallePago" => $arr
    );
}
if(isset($_GET['get_inspeccion_info'])){

    $strQuery = "SELECT * 
                    FROM apartamentos e
                    where e.apartamento='{$strIdInspeccion}'
                    ORDER BY e.apartamento";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "detalleInspeccion" => $arr
    );
}
if(isset($_GET['get_pago_extra_info'])){

    $strQuery = "SELECT * 
                    FROM cobrosExtras ce
                    where ce.idCobro={$intIdCobro}
                    ORDER BY ce.noPago";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "detallePago" => $arr
    );
}
if(isset($_GET['get_pago_info_comision'])){

    $strQuery = "SELECT * 
                    FROM pagosComision ped
                    where ped.idFormaPagoComisiones={$intIdFormaPagoComision}
                    AND   ped.idEnganche = {$intIdEnganche}
                    ORDER BY ped.noPago ASC";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "detallePagoComision" => $arr
    );
}
if(isset($_GET['get_no_pago_comision'])){

    $strQuery = "SELECT count(noPago) as pagosRealizados , SUM(monto) as pagado
                    FROM pagosComision ped
                    where ped.idFormaPagoComisiones={$intIdFormaPagoComision}
                    AND   ped.idEnganche = {$intIdEnganche}
                    ORDER BY ped.noPago ASC";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "detallePagoComision" => $arr
    );
}
if(isset($_GET['get_contra_info'])){

    $strQuery = "SELECT * 
                    FROM contrapagos cp
                    where cp.idContraPago={$intIdContra}";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        //$arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "detalleContra" => $arr
    );
}
if(isset($_GET['get_pago_final_info'])){

    $strQuery = "SELECT * 
                    FROM pagoFinal pf
                    where pf.idEnganche={$intIdEnganche}";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "detallePagoFinal" => $arr
    );
}
if(isset($_GET['get_progra_detalle_estado'])){
        //$hoy=date('Y-m-d');
        $hoy = date("d-m-Y");
        //resta 5 dias
        $hoy = date("Y-m-d",strtotime($hoy."- 0 days")); 

    $strQuery = "SELECT ped.*,pe.montoEnganche,date_format(fechaPago, '%d-%m-%Y') as fechaPagoFormat,ifnull(date_format(fechaPagoRealizado, '%d-%m-%Y'),'') as fechaPagoRealizadoFormat,pe.noPagos 
                    FROM prograEnganche pe
                    INNER JOIN prograEngancheDetalle ped on pe.idEnganche = ped.idEnganche
                    where pe.idEnganche={$intIdEnganche}
                    ORDER BY ped.noPago";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $strQueryC = "SELECT * FROM contrapagos WHERE idEnganche={$intIdEnganche}";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQueryC);
    $arrC = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        //$arrC[] = $rTmp;
    }

    $strQueryEn = "SELECT e.pagosEnganche, CURDATE(), (SELECT ped.fechaPago FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) ORDER BY ped.fechaPago ASC LIMIT 1) as fechaParaPago,
    ac.codigo,e.apartamento, e.pagoPromesa,date_format(fechaPagoReserva, '%d-%m-%Y') as fechaPagoReservaFormat,e.MontoReserva,e.descuento_porcentual_monto,(dg.cambioDolar * a.precio) as precio,
                    ((dg.cambioDolar * dg.parqueoExtra)*e.parqueosExtras) as parqueoExtra,((dg.cambioDolar * a.bodega_precio) * bodegasExtras) as bodegaPrecio,e.enganchePorcMonto,
                    ifnull(date_format(pf.fechaPago, '%d-%m-%Y'),'') as fechaPagoFinalFormat,
                    (SELECT ifnull((SUM(case when accion ='adicionar' AND enganche=0 then monto else 0 end) -  SUM(case when accion ='descontar' AND enganche=0 then monto else 0 end)),0) as contracargo FROM contrapagos cp where cp.idEnganche = e.idEnganche  ) contracargo,
					--(SELECT ifnull((SUM(case when accion ='sumar' AND enganche=1 then monto else 0 end) -  SUM(case when accion ='restar' AND enganche=1 then monto else 0 end)),0) as contracargo FROM contrapagos cp where cp.idEnganche = e.idEnganche  ) contracargoEnganche,
                    -- as  contracargo,
					0 as  contracargoEnganche,
                    IF(tipoCliente='individual', CONCAT(IFNULL(CONCAT(primerNombre,' '),''),IFNULL(CONCAT(segundoNombre,' '),''),IFNULL(CONCAT(tercerNombre,' '),''),IFNULL(CONCAT(primerApellido,' '),''),
                    IFNULL(CONCAT(segundoApellido,' '),''),IFNULL(CONCAT(apellidoCasada,' '),'')), nombre_sa)  as client_name,e.idCliente,
                    (SELECT IFNULL(SUM(monto),0) FROM prograEngancheDetalle ped WHERE ped.idEnganche = e.idEnganche) as totalEnganche,
                    idVendedor, 
                    CONCAT(IFNULL(CONCAT(primer_nombre,' '),''),IFNULL(CONCAT(segundo_nombre,' '),''),IFNULL(CONCAT(tercer_nombre,' '),''),IFNULL(CONCAT(primer_apellido,' '),''),
                    IFNULL(CONCAT(segundo_apellido,' '),''),IFNULL(CONCAT(apellido_casada,' '),''))  as vendedor,
                    (case 
                    when 
                    (SELECT DATEDIFF(NOW(),fechaPago) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) ORDER BY ped.fechaPago ASC LIMIT 1) <= 0 then 0
                    when (SELECT if(e.apartamento='4D',(6102.99 + IFNULL(SUM(montoPagado),0)),IFNULL(SUM(montoPagado),0) ) 
                        FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1) >= ((e.enganchePorcMonto- e.MontoReserva)/e.pagosEnganche) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped 
                        WHERE ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}') then 0
                    else  ((ROUND(((e.enganchePorcMonto- e.MontoReserva)/e.pagosEnganche),2) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}') - (SELECT if(e.apartamento='4D',(6102.99 + IFNULL(SUM(montoPagado),0)),IFNULL(SUM(montoPagado),0) ) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1)))
                    end) as pagoPendiente,
                    (case 
                    when 
                    (SELECT DATEDIFF(NOW(),fechaPago) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) ORDER BY ped.fechaPago ASC LIMIT 1) <= 0 then 0
                    when (SELECT if(e.apartamento='4D',(6102.99 + IFNULL(SUM(montoPagado),0)),IFNULL(SUM(montoPagado),0) ) 
                        FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1) >= ((e.enganchePorcMonto- e.MontoReserva)/e.pagosEnganche) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped 
                        WHERE ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}') then 0
                    else  (( (SELECT SUM(ped.montoReal) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}') - (SELECT if(e.apartamento='4D',(6102.99 + IFNULL(SUM(montoPagado),0)),IFNULL(SUM(montoPagado),0) ) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1)))
                    end) as pagoPendiente_2,
                    (SELECT DATEDIFF(NOW(),fechaPago) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and (pagado=0 || validado = 0) ORDER BY ped.fechaPago ASC LIMIT 1) as dias,
                    ((SELECT if(e.apartamento='4D',(6102.99 + IFNULL(SUM(montoPagado),0)),IFNULL(SUM(montoPagado),0) ) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and validado = 1)) totalPagado,
                    ( ROUND(((e.enganchePorcMonto - e.MontoReserva)/e.pagosEnganche),2) ) cuotas,
                    ( ROUND(((e.enganchePorcMonto- e.MontoReserva)/e.pagosEnganche),2) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}')) as debePagar,
                    ((SELECT SUM(ped.montoReal)FROM prograEngancheDetalle ped where ped.idEnganche=e.idEnganche AND fechaPago<'{$hoy}'))as debePagar_2,
                    ( ( ((e.enganchePorcMonto - (SELECT IFNULL(SUM(monto),0) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) ) - e.MontoReserva)/(e.pagosEnganche - (SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1) )) ) cuotasSinEspecial,
                    ( ((e.enganchePorcMonto  - (SELECT IFNULL(SUM(monto),0) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1)- e.MontoReserva)/(e.pagosEnganche - (SELECT count(pagoEspecial) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche and pagoEspecial = 1)) ) * (SELECT count(ped.idDetalle) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche AND fechaPago <'{$hoy}')) as debePagarSinEspecial,ctc.descripcion as tipoComision,
                    apc.precioComision,a.idProyecto
                FROM enganche e
                INNER JOIN apartamentos a ON e.apartamento = a.apartamento
                INNER JOIN datosGlobales dg ON a.idProyecto = dg.idGlobal
                LEFT JOIN pagoFinal pf ON e.idEnganche = pf.idEnganche
                INNER JOIN agregarCliente ac ON e.idCliente = ac.idCliente 
                LEFT JOIN catTipoComision ctc ON LTRIM(ac.tipoComision) = ctc.descripcion
                LEFT JOIN apartamentoComisiones apc ON e.apartamento = apc.apartamento and apc.estado = 1
                LEFT JOIN usuarios u ON e.idVendedor = u.id_usuario 
                WHERE e.idEnganche = {$intIdEnganche}
                limit 1 ;";

        //echo $strQueryEn;
        $qTmp = $conn ->db_query($strQueryEn);
        $rTmp = $conn->db_fetch_object($qTmp);
        $fechaInicial = $rTmp->fechaParaPago;
        $fechaActual = date('Y-m-d'); // la fecha del ordenador
        
        //echo "<p>Diferencia entre la fecha ".$fechaActual." la fecha ".$fechaInicial."</p>";
        
        // Obtenemos la diferencia en milisegundos
       // echo "<p>".$diff = strtotime($fechaActual) - strtotime($fechaInicial);

        
        $dias = (strtotime($fechaActual)-strtotime($fechaInicial))/86400;
        //$dias = abs($dias); 
        //$dias = floor($dias);

        $pagosEnganche = $rTmp->pagosEnganche;
        $totalPagado = $rTmp->totalPagado;
        $cuotas = $rTmp->cuotas;
        $debePagar = $rTmp->debePagar;
        $debePagar_2 = $rTmp->debePagar_2;
        $cuotasSinEspecial = $rTmp->cuotasSinEspecial;
        $debePagarSinEspecial = $rTmp->debePagarSinEspecial;

        $diasPago = $dias;
        $reserva = $rTmp->MontoReserva;
        $contracargo = $rTmp->contracargo;
        $contracargoEnganche = $rTmp->contracargoEnganche;
        $promesa = $rTmp->pagoPromesa;
        $fechaPagoReservaFormat = $rTmp->fechaPagoReservaFormat;
        $fechaPagoReservaFormat = $rTmp->fechaPagoReservaFormat;
        $fechaPagoFinalFormat = $rTmp->fechaPagoFinalFormat;
        $descuento = $rTmp->descuento_porcentual_monto;
        $precio = $rTmp->precio;
        $parqueo = $rTmp->parqueoExtra;
        $bodega = $rTmp->bodegaPrecio;
        $enganchePorcMonto = $rTmp->enganchePorcMonto;
        $pagoPendiente = $rTmp->pagoPendiente;
        $pagoPendiente_2 = $rTmp->pagoPendiente_2;
        $totalEnganche = $rTmp->totalEnganche;
        $codigo = $rTmp->codigo;
        $apartamento = $rTmp->apartamento;
        $nombreCliente = $rTmp->client_name;
        $tipoComision = $rTmp->tipoComision;
        $precioComision = $rTmp->precioComision;
        $idProyecto = $rTmp->idProyecto;
        
        $vendedor = $rTmp->vendedor;
        $id_vendedor = $rTmp->idVendedor;
        $idCliente = $rTmp->idCliente;

        
    $strQuery = "SELECT a.idFormaPagoComisiones, a.noPago,a.porcentajePago,b.descripcion,b.porcentajeComision,
                    ifnull(GROUP_CONCAT(bancoPago SEPARATOR ' / '),'') as bancoPago,
                    ifnull(GROUP_CONCAT(date_format(fechaPago, '%d-%m-%Y') SEPARATOR ' / '),'') as fechaPago,
                    ifnull(SUM(monto),0) as monto,ifnull(GROUP_CONCAT(noCheque SEPARATOR ' / '),'') as noCheque,
                    CASE
                        WHEN a.noPago = 1 THEN 'Cumplido'
                        WHEN a.noPago = 2 AND ( SELECT ROUND(100 *(((SELECT sum(montoPagado) FROM prograEngancheDetalle ped where ped.idEnganche = e.idEnganche GROUP BY idEnganche ) + e.MontoReserva)/enganchePorcMonto),2) as porcentaje
                        	FROM `enganche` e
                        	where idEnganche = {$intIdEnganche} )>=50 THEN 'Cumplido'
                        ELSE 'Sin cumplir'
                    END as estado  
                    FROM catFormaPagoComisiones a 
                    INNER JOIN catPagaComision b ON a.idPagaComision = b.idPagaComision
                    INNER JOIN catTipoComision c on b.idTipoComision = c.idTipoComision
                    LEFT JOIN pagosComision e ON a.idFormaPagoComisiones = e.idFormaPagoComisiones AND idEnganche = {$intIdEnganche}
                    WHERE c.descripcion = '{$tipoComision}'
                    and c.proyecto = '{$idProyecto}'
                    and b.porcentajeComision >0
                    group by a.idFormaPagoComisiones";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arrCom = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arrCom[] = $rTmp;
    }
    $strQueryExtra = "SELECT ce.*,date_format(fechaPagoRealizado, '%d-%m-%Y') as fechaFormat FROM cobrosExtras ce
                    WHERE idEnganche = {$intIdEnganche} and tipoCobroExtra !='enganche'
                    ORDER BY noPago ASC";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQueryExtra);
    $arrExtra = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arrExtra[] = $rTmp;
    }
    $strQueryExtraEng = "SELECT ce.*,date_format(fechaPagoRealizado, '%d-%m-%Y') as fechaFormat FROM cobrosExtras ce
    WHERE idEnganche = {$intIdEnganche} and tipoCobroExtra ='enganche'
    ORDER BY noPago ASC";

//echo $strQuery;
$qTmp = $conn ->db_query($strQueryExtraEng);
$arrExtraEng = array();
while ($rTmp = $conn->db_fetch_object($qTmp)){
$arrExtraEng[] = $rTmp;
}
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "reserva" => $reserva ,
        "pagosEnganche" => $pagosEnganche,
        "contracargo" => $contracargo ,
        "contracargoEnganche" => $contracargoEnganche ,
        "promesa" => $promesa ,
        "fechaPagoReservaFormat" => $fechaPagoReservaFormat  ,
        "fechaPagoReservaFormat" => $fechaPagoReservaFormat  ,
        "fechaPagoFinalFormat" => $fechaPagoFinalFormat  ,
        "descuento" => $descuento  ,
        "precio" => $precio ,
        "parqueo" => $parqueo  ,
        "bodega" => $bodega  ,
        "enganchePorcMonto" => $enganchePorcMonto,
        "pagoPendiente" => $pagoPendiente,
        "pagoPendiente_2" => $pagoPendiente_2,
        "totalEnganche" => $totalEnganche,
        "codigo" => $codigo,
        "apartamento" => $apartamento,
        "nombreCliente" => $nombreCliente,
        "tipoComision" => $tipoComision,
        "precioComision" => $precioComision,
        "vendedor" => $vendedor,
        "id_vendedor" => $id_vendedor,
        "detallePagos" => $arr,
        "detalleContra" => $arrC,
        "detalleComisiones" => $arrCom,
        "detallePagosExtra" => $arrExtra,
        "detallePagosExtraEng" => $arrExtraEng,
        "diasPago" =>$diasPago,
        "idCliente" =>$idCliente,
        "totalPagado" => $totalPagado,
        "cuotas" => $cuotas,
        "debePagar" => $debePagar,
        "debePagar_2" => $debePagar_2,
        "cuotasSinEspecial" => $cuotasSinEspecial,
        "debePagarSinEspecial" => $debePagarSinEspecial,
    );
}
if(isset($_GET['get_nacionalidad'])){

    $strQuery = "SELECT * 
                    FROM catPais
                    ORDER BY pais";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "nacionalidades" => $arr
    );
}
if(isset($_GET['get_municipios'])){

    $strQuery = "SELECT * 
                    FROM catMunicipios
                    where id_pais=1
                    and id_depto={$intDepto}
                    ORDER BY nombre_muni";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "municipios" => $arr
    );
}
if(isset($_GET['get_apartamentos_proyecto'])){

    $strQuery = "SELECT a.idApartamento,a.apartamento 
                    FROM apartamentos a
                    where idNivel = {$intNivel}
                    AND (
                            estado in (1) or 
                                            (
                                                estado in (2) 
                                                AND (SELECT ra.apartamento FROM reservaApartamento ra where ra.apartamento = a.apartamento AND estado = 1 AND idVendedor ={$id_usuario} LIMIT 1) = a.apartamento 
                                            )
                        )
                UNION 
                SELECT a.idApartamento,a.apartamento 
                    FROM apartamentos a
                    where idNivel = {$intNivel}
                    AND idApartamento = '{$intAptoSelect}' 
                    ORDER BY apartamento";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => "",
        "apartamentos" => $arr
    );
}
if(isset($_GET['get_apartamentos_proyecto_com'])){

    $strQuery = "SELECT idApartamento,apartamento 
                    FROM apartamentos
                    where idNivel = {$intNivel}
                    ORDER BY apartamento";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => "",
        "apartamentos" => $arr
    );
}
if(isset($_GET['get_apartamento_detalle'])){

    $strQuery = "SELECT a.*,(dg.cambioDolar * a.precio) as price,ct.tarifa_1,ct.tarifa_2,ct.tarifa_3,
    (dg.cambioDolar * dg.parqueoExtra) as parqueoExtra,dg.porcentajeEnganche,dg.iusi,dg.seguro,
                    dg.montoReserva,dg.porcentajeFacturacion,dg.cocinaTipoA,dg.cocinaTipob,(dg.cambioDolar * dg.parqueoExtraMoto) as parqueoExtraMoto 
                    FROM apartamentos a
                    INNER JOIN datosGlobales dg ON a.idProyecto = dg.idGlobal
                    INNER JOIN catTarifas ct ON ct.idTarifa = 1
                    where apartamento = '{$strApartamento}'
        
                    ORDER BY apartamento";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "detalle" => $arr
    );
}
if(isset($_GET['get_vendedor_detalle'])){

    $strQuery = "SELECT mail,telefono,
                    CONCAT(IFNULL(CONCAT(primer_nombre,' '),''),IFNULL(CONCAT(segundo_nombre,' '),''),IFNULL(CONCAT(tercer_nombre,' '),''),IFNULL(CONCAT(primer_apellido,' '),''),
                    IFNULL(CONCAT(segundo_apellido,' '),''),IFNULL(CONCAT(apellido_casada,' '),'')) as nombreVendedor
                    FROM usuarios
                    where id_usuario = '{$intVendedor}'
        
                    ORDER BY id_usuario";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "detalle" => $arr
    );
}
if(isset($_GET['cuotas_info'])){
    $strQuery = "SELECT e.pagosEnganche,(a.precio*cambioDolar) as precioQ,(e.parqueosExtras * dg.parqueoExtra * cambioDolar) as totalParqueo,
                        descuento_porcentual,enganchePorc, e.fechaPagoInicial ,e.enganchePorcMonto,
                        e.montoReserva,e.pagoPromesa
                FROM enganche e
                INNER JOIN datosGlobales dg ON e.proyecto = dg.proyecto
                INNER JOIN apartamentos a ON e.apartamento = a.apartamento
                    WHERE idEnganche = {$intIdEnganche}";
    //echo  $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "datosEnganche" => $arr
    );
}
if(isset($_GET['get_listado_adjuntos'])){
    $strQuery = "SELECT * 
                    FROM adjuntosCliente
                    WHERE idCliente ={$intIdOcaCliente}";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "adjuntos" => $arr
    );
}
if(isset($_GET['get_informacion_inmueble'])){
    $strQuery = "SELECT * 
                    FROM infoInmuebles
                    WHERE idEnganche ={$intIdEnganche}";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "info" => $arr
    );
}
if(isset($_GET['get_informacion_banco_general'])){
    $strQuery = "SELECT * 
                    FROM infoBanco
                    WHERE idEnganche ={$intIdEnganche}";

    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "info" => $arr
    );
}
if(isset($_GET['deleteAdjunto'])){
    $strQuery = "   UPDATE archivo
                    SET estado = 0
                    WHERE id_archivo = {$_POST['id_archivo']}";
    $conn->db_query($strQuery);
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "adjuntos" => $arr
    );
}
if(isset($_GET['desistirApto'])){
    $strQuery = "   UPDATE reservaApartamento
                    SET desistido = 1
                    WHERE idReserva = {$_POST['idReserva']}";
    $conn->db_query($strQuery);
    $strQuery = " SELECT apartamento FROM reservaApartamento 
    WHERE idReserva =  {$_POST['idReserva']}";
    $qTmp = $conn ->db_query($strQuery);
    while($rTmp = $conn->db_fetch_object($qTmp)){
        $strQuery = " UPDATE apartamentos SET estado = 1
        WHERE apartamento = '{$rTmp->apartamento}'";
        $conn->db_query($strQuery);
        $comentarioBitacora='desistirApto Se ha liberado apartamento '.$rTmp->apartamento;
        insertBitacora($id_usuario,$comentarioBitacora,$conn);
    } 
    $conn->db_query($strQuery);
    $res = array(
        "err" => false,
        "mss" => "Apartamento ha sido liberado",
    );
}
if(isset($_GET['deleteAdjunto_fha'])){
    $strQuery = "   UPDATE archivo_fha
                    SET estado = 0
                    WHERE id_archivo = {$_POST['id_archivo']}";
    $conn->db_query($strQuery);
    $res = array(
        "err" => false,
        "mss" => $strQuery,
        "adjuntos" => $arr
    );
}
if(isset($_GET['guardarAdjunto'])){

    $id_tipo_documento = isset($_POST['id_tipo_documento']) ? intval($_POST['id_tipo_documento']) : 0;

    $_path = "../public/";
    foreach($_FILES["fliesAdjuntos"]['tmp_name'] as $key => $tmp_name)
    {
        if( $_FILES['archivo']['size'] > 5000000 ) {
            $errorMsj=" No se pueden subir archivos con pesos mayores a 5MB";
        } else {
            $filename = $_FILES["fliesAdjuntos"]["name"][$key];
            $arr = explode(".", $filename);
            $tipo = $arr[sizeof($arr) - 1];
            $codigo =   date('YmdHis').$id_usuario. "." . $tipo;
            
            $strQuery = "   SELECT *
                            FROM archivo 
                            WHERE id_cliente = {$intIdOcaCliente}
                            AND estado = 1
                            AND nombre = '{$filename}'";
            $qTmp = $conn->db_query($strQuery);
            if ($conn->db_num_rows($qTmp) <= 0) {
                if(move_uploaded_file($_FILES["fliesAdjuntos"]["tmp_name"][$key], $_path . $codigo)) {
                    $strQuery = "   INSERT INTO archivo (id_cliente, id_tipo_documento, codigo, tipo, nombre)
                                    VALUES ({$intIdOcaCliente}, {$id_tipo_documento}, '{$codigo}', '{$tipo}', '{$filename}')";
                    $conn->db_query($strQuery);
                }
            }
        }
    }
    $res = array(
        "err" => false,
        "mss" =>  "",
        "listado_adjuntos" => $arr
    );
}

if(isset($_GET['guardarAdjuntoExtra'])){

    $id_tipo_documento = isset($_POST['id_tipo_documento']) ? intval($_POST['id_tipo_documento']) : 0;
    $intIdOcaCliente = isset($_POST['idOcaApartamento']) ? trim($_POST['idOcaApartamento']):'';
    $strComentario = isset($_POST['comentario']) ? trim($_POST['comentario']):'';
    

    $_path = "../public/";
    foreach($_FILES["fliesAdjuntos"]['tmp_name'] as $key => $tmp_name)
    {
        if( $_FILES['archivo']['size'] > 5000000 ) {
            $errorMsj=" No se pueden subir archivos con pesos mayores a 5MB";
        } else {
            $filename = $_FILES["fliesAdjuntos"]["name"][$key];
            $arr = explode(".", $filename);
            $tipo = $arr[sizeof($arr) - 1];
            $codigo =   date('YmdHis').$id_usuario. "." . $tipo;
            
            $strQuery = "   SELECT *
                            FROM archivo_extra 
                            WHERE id_cliente = '{$intIdOcaCliente}'
                            AND estado = 1
                            AND nombre = '{$filename}'";
            $qTmp = $conn->db_query($strQuery);
            if ($conn->db_num_rows($qTmp) <= 0) {
                if(move_uploaded_file($_FILES["fliesAdjuntos"]["tmp_name"][$key], $_path . $codigo)) {
                    $strQuery = "   INSERT INTO archivo_extra (id_cliente, id_tipo_documento, codigo, tipo, nombre, comentario)
                                    VALUES ('{$intIdOcaCliente}', {$id_tipo_documento}, '{$codigo}', '{$tipo}', '{$filename}', '{$strComentario}')";
                    $conn->db_query($strQuery);
                }
            }
        }
    }
    $res = array(
        "err" => false,
        "mss" =>  "",
        "listado_adjuntos" => $arr
    );
}
if(isset($_GET['guardarAdjunto_fha'])){

    $id_tipo_documento = isset($_POST['id_tipo_documento']) ? intval($_POST['id_tipo_documento']) : 0;

    $_path = "../public/";
    foreach($_FILES["fliesAdjuntos_0"]['tmp_name'] as $key => $tmp_name)
    {
        if( $_FILES['archivo']['size'] > 5000000 ) {
            $errorMsj=" No se pueden subir archivos con pesos mayores a 5MB";
        } else {
            $filename = $_FILES["fliesAdjuntos_0"]["name"][$key];
            $arr = explode(".", $filename);
            $tipo = $arr[sizeof($arr) - 1];
            $codigo =   date('YmdHis').$id_usuario. "." . $tipo;
            
            $strQuery = "   SELECT *
                            FROM archivo 
                            WHERE id_cliente = {$intIdOcaCliente}
                            AND estado = 1
                            AND nombre = '{$filename}'";
            $qTmp = $conn->db_query($strQuery);
            if ($conn->db_num_rows($qTmp) <= 0) {
                if(move_uploaded_file($_FILES["fliesAdjuntos_0"]["tmp_name"][$key], $_path . $codigo)) {
                    $strQuery = "   INSERT INTO archivo_fha (id_cliente, id_tipo_documento, codigo, tipo, nombre)
                                    VALUES ({$intIdOcaCliente}, {$id_tipo_documento}, '{$codigo}', '{$tipo}', '{$filename}')";
                    $conn->db_query($strQuery);
                }
            }
        }
    }
    $res = array(
        "err" => false,
        "mss" =>  "",
        "listado_adjuntos" => $arr
    );
}

if(isset($_GET['get_adjuntos_listado'])){
    $id_tipo_documento = isset($_POST['id_tipo_documento']) ? intval($_POST['id_tipo_documento']) : 0;
    $_AND = $id_tipo_documento > 0 ? "AND a.id_tipo_documento = {$id_tipo_documento}" : "";

    $strQuery = "   SELECT a.* 
                    FROM archivo a
                    INNER JOIN tipo_documento td ON a.id_tipo_documento = td.id_tipo_documento
                    WHERE a.id_cliente ='{$intIdOcaCliente}' 
                    AND a.estado = 1
                    {$_AND}
                    ORDER by td.orden, nombre";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => "",
        "listado_adjuntos" => $arr
    );
}
if(isset($_GET['get_adjuntos_listado_fha'])){
    $id_tipo_documento = isset($_POST['id_tipo_documento']) ? intval($_POST['id_tipo_documento']) : 0;
    $id_fase = isset($_POST['id_fase']) ? intval($_POST['id_fase']) : 0;
    $_AND = $id_tipo_documento > 0 ? " AND a.id_tipo_documento = {$id_tipo_documento}" : "";
    $_AND .= $id_fase > 0 ? "AND a.fase = {$id_fase}" : "";
    
    $strQuery = "   SELECT a.* 
                    FROM archivo_fha a
                    INNER JOIN tipo_documento_fha td ON a.id_tipo_documento = td.id_tipo_documento
                    WHERE a.id_cliente ='{$intIdOcaCliente}' 
                    AND a.estado = 1
                    {$_AND}
                    ORDER by td.orden, nombre";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => "",
        "listado_adjuntos" => $arr
    );
}
if(isset($_GET['get_adjuntos_listado_extra'])){
    $id_tipo_documento = isset($_POST['id_tipo_documento']) ? intval($_POST['id_tipo_documento']) : 0;
    $_AND = $id_tipo_documento > 0 ? " AND a.id_tipo_documento = {$id_tipo_documento}" : "";
    $intIdOcaCliente = isset($_POST['idOcaCliente']) ? trim($_POST['idOcaCliente']):'';
    
    $strQuery = "   SELECT a.* 
                    FROM archivo_extra a
                    INNER JOIN tipo_documento_extra td ON a.id_tipo_documento = td.id_tipo_documento
                    WHERE a.id_cliente ='{$intIdOcaCliente}' 
                    AND a.estado = 1
                    {$_AND}
                    ORDER by nombre";
    //echo $strQuery;
    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $comentario = $rTmp->comentario; 
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => "",
        "listado_adjuntos" => $arr,
        "comentario " => $comentario
    );
}
if(isset($_GET['get_adjuntos_listado_fha_json'])){
    $id_tipo_documento = isset($_POST['id_tipo_documento']) ? intval($_POST['id_tipo_documento']) : 0;
    $id_fase = isset($_POST['id_fase']) ? intval($_POST['id_fase']) : 0;
    $_AND = $id_tipo_documento > 0 ? " AND a.id_tipo_documento = {$id_tipo_documento}" : "";
    $_AND .= $id_fase > 0 ? "AND a.fase = {$id_fase}" : "";
    
    $strQuery = "   SELECT a.* 
                    FROM archivo_fha a
                    INNER JOIN tipo_documento_fha td ON a.id_tipo_documento = td.id_tipo_documento
                    WHERE a.id_cliente ='{$intIdOcaCliente}' 
                    AND a.estado = 1
                    {$_AND}
                    ORDER by td.orden, nombre";

    $qTmp = $conn ->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)){
        $arr[] = $rTmp;
    }

    $res = array(
        "err" => false,
        "mss" => "",
        "listado_adjuntos" => "$arr"
    );
}

if (isset($_GET['get_filtros_adjuntos'])) {
    $strQuery = "   SELECT id_tipo_documento, nombre 
                    FROM tipo_documento
                    WHERE estado = 1
                    ORDER BY orden,nombre";
        
    $qTmp = $conn->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)) {
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => "",
        "filtros_adjuntos" => $arr,
    );
}
if (isset($_GET['get_filtros_adjuntos_fha'])) {
    $id_fase = isset($_POST['fase']) ? intval($_POST['fase']) : 0;
    $_AND = $id_fase >= 0 ? "AND fase = {$id_fase}" : "";
    $strQuery = "   SELECT id_tipo_documento, nombre 
                    FROM tipo_documento_fha
                    WHERE estado = 1
                    ORDER BY orden,nombre";
        
    $qTmp = $conn->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)) {
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => "",
        "filtros_adjuntos" => $arr,
    );
}
if (isset($_GET['get_filtros_adjuntos_extra'])) {
    $strQuery = "   SELECT id_tipo_documento, nombre 
                    FROM tipo_documento_extra
                    WHERE estado = 1
                    ORDER BY id_tipo_documento, nombre";
        
    $qTmp = $conn->db_query($strQuery);
    $arr = array();
    while ($rTmp = $conn->db_fetch_object($qTmp)) {
        $arr[] = $rTmp;
    }
    $res = array(
        "err" => false,
        "mss" => "",
        "filtros_adjuntos" => $arr,
    );
}
function insertBitacora($idUsuario, $comentario, $conn)
{
$ip = getRealIP();
    $query = "INSERT INTO bitacora
	(id_bitacora,
	id_usuario,
	fecha,
	comentario,
	ip)
	VALUES
	(NULL,
	'" . $idUsuario . "',
	'" . date('Y-m-d H:i:s') . "',
	'" . $comentario . "',
	'" . $ip . "')"; 
    $conn->db_query($query);
}
function getRealIP()
{
    if (isset($_SERVER["HTTP_CLIENT_IP"])) {
        return $_SERVER["HTTP_CLIENT_IP"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
        return $_SERVER["HTTP_X_FORWARDED"];
    } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
        return $_SERVER["HTTP_FORWARDED"];
    } else {
        return $_SERVER["REMOTE_ADDR"];
    }
}
$conn->db_close();
echo json_encode($res);
?>
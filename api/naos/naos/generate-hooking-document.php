<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 1);
error_reporting(-1);
require_once __DIR__ . '/vendor/autoload.php';
require "bootstrap.php";
require "models/Apartment.php";
require "models/Hooking.php";
require "models/Globale.php";
require "models/ReservationClient.php";
require "calculation.php";
session_start();

if (empty($_SESSION["userIdNaos"])) {
    header("Location: login.php");
    exit();
}

function getMonthName($m)
{
    switch ($m) {
        case 1:
            return 'Enero';
        case 2:
            return 'Febrero';
        case 3:
            return 'Marzo';
        case 4:
            return 'Abril';
        case 5:
            return 'Mayo';
        case 6:
            return 'Junio';
        case 7:
            return 'Julio';
        case 8:
            return 'Agosto';
        case 9:
            return 'Septiembre';
        case 10:
            return 'Octubre';
        case 11:
            return 'Noviembre';
        case 12:
            return 'Diciembre';
    }
}

function formatNumber($value)
{
    return number_format($value, 2, '.', ',');
}

function formatPhone($value)
{
    $string = is_numeric($value) ? str_split(strval($value)) : str_split($value);
    $counter = 1;
    $outputString = '';
    foreach ($string as $char) {
        if ($counter == 4) {
            $outputString = $outputString . $char . '-';
        } else {
            $outputString = $outputString . $char;
        }
        $counter++;
    }
    return $outputString;
}

function saveImage($img, $name)
{
    //if (preg_match('/^data:image\/(\w+);base64,/', $img)) {
        $data = substr($img, strpos($img, ',') + 1);
        $data = base64_decode($data);
        file_put_contents('documents/'.$name, $data);
    //}
}

function getImageName($base64)
{
    $pos = strpos($base64, ';');
    $extension = explode(':', substr($base64, 0, $pos))[1];
    return bin2hex(random_bytes(8)) . '.' . explode("/", $extension)[1];
}

function getFilledTemplate($index, $data, $clientTemplate1, $clientTemplate2)
{
    $currentTemplate = $index === 0 ? $clientTemplate1 : $clientTemplate2;

    $classYesActive = 'has-credits-yes-a-' . $index . '-button';
    $classYesInActive = 'has-credits-yes-i-' . $index . '-button';

    $classNoActive = 'has-credits-no-a-' . $index . '-button';
    $classNoInActive = 'has-credits-no-i-' . $index . '-button';

    $currentTemplate = str_replace('{{full_name_' . $index . '}}', $data->name, $currentTemplate);
    $currentTemplate = str_replace('{{nit_' . $index . '}}', $data->nit, $currentTemplate);
    $currentTemplate = str_replace('{{dpi_' . $index . '}}', $data->dpi, $currentTemplate);
    $currentTemplate = str_replace('{{dpi_creation_' . $index . '}}', date('d/m/Y', strtotime($data->dpiCreation)), $currentTemplate);
    $currentTemplate = str_replace('{{home_address_' . $index . '}}', $data->address, $currentTemplate);
    $currentTemplate = str_replace('{{line_phone_' . $index . '}}', $data->linePhone, $currentTemplate);
   // $currentTemplate = str_replace('{{cell_phone_' . $index . '}}', $data->cellPhone, $currentTemplate);
    $currentTemplate = str_replace('{{nationality_' . $index . '}}', $data->nationality, $currentTemplate);
    $currentTemplate = str_replace('{{birthday_' . $index . '}}', date('d/m/Y', strtotime($data->birthDay)), $currentTemplate);
    $currentTemplate = str_replace('{{civil_status_' . $index . '}}', $data->civilStatus, $currentTemplate);
    $currentTemplate = str_replace('{{email_' . $index . '}}', $data->email, $currentTemplate);
    $currentTemplate = str_replace('{{work_place_' . $index . '}}', $data->company, $currentTemplate);
    $currentTemplate = str_replace('{{job_title_' . $index . '}}', $data->jobTitle, $currentTemplate);
    $currentTemplate = str_replace('{{work_place_address_' . $index . '}}', $data->companyAddress, $currentTemplate);
    $currentTemplate = str_replace('{{monthly_salary_' . $index . '}}', 'Q. ' . formatNumber($data->monthlySalary), $currentTemplate);
    $currentTemplate = str_replace('{{other_income_amount_' . $index . '}}', 'Q. ' . formatNumber($data->otherIncomeAmount), $currentTemplate);
    $currentTemplate = str_replace('{{other_income_' . $index . '}}', $data->otherIncome, $currentTemplate);

    $selectedClassYes = $data->hasFHACredit ? $classYesActive : $classYesInActive;
    $selectedClassNo = !$data->hasFHACredit ? $classNoActive : $classNoInActive;

    $currentTemplate = str_replace('{{yes_class}}', $selectedClassYes, $currentTemplate);
    $currentTemplate = str_replace('{{no_class}}', $selectedClassNo, $currentTemplate);

    $currentTemplate = str_replace('{{ref_number_' . $index . '}}', $data->refNumber, $currentTemplate);

    return $currentTemplate;
}

function getPDF($apartment,
                $extraParking,
                $years,
                $globalData,
                $discountPercentage,
                $clients,
                $config,
                $paymentSchedule,
                $reservation,
                $reservationDate,
                $conditions,
                $promisePayment)
{
    $extraParkingCostGTQ = $globalData->extra_parking_price * $globalData->dollar_exchange;
    $extraParkingTotal = $globalData->extra_parking_price * $extraParking * $globalData->dollar_exchange;
    $extraParkingTotal = $globalData->extra_parking_price * $extraParking * $globalData->dollar_exchange;
    $extraWarehouseTotal = $globalData->dollar_exchange * $apartment->warehouse_price * $config['extraWarehouse'];
    $priceGTQ = ($apartment->price * $globalData->dollar_exchange)
        + $extraParkingTotal
        + $extraWarehouseTotal;

    $discount = $config['hasNetDiscount'] == true ? $config['netDiscount'] : round(round($priceGTQ * ($discountPercentage / 100)));

    $netPrice = round(round($priceGTQ - $discount));
    $hooking = round(($netPrice * (doubleval($config['hookingPercentage']) / 100)));
    $hookingWithoutReservation = $hooking - $config['reservationAmount'];
    $initialBalance = round(round($netPrice - ($hooking)));
    $iusiFee = round(calculateIUSIAmount($netPrice, $globalData->invoicing_percentage, $globalData->iusi_rate));
    $insuranceFee = round(calculateInsurance($netPrice, $globalData->insurance_rate, $years));
    $creditFee = round(calculateMonthlyPayment($years, $initialBalance, $globalData->rate));
    $monthlyFee = round(round($creditFee
        + $iusiFee
        + $insuranceFee));
    $hookingPayments = $config['hookingPayments'];
    $hookingFee = round($hooking / $hookingPayments, 2);

    $netPriceTemplate = $discountPercentage > 0 ? '
        <p class="net-price-text">Precio neto</p>
        <p class="net-price-value">Q ' . formatNumber($netPrice) . '</p>
        <div class="net-price-field"></div>
    ' : '';

    $properties = $netPrice * 0.7173;
    $action = $netPrice - $properties;

    $propertiesTax = $properties * 0.12;
    $actionTax = $action * 0.03;
    $totalTax = $propertiesTax + $actionTax;
    $propertiesWithNoTax = $properties - $propertiesTax;
    $actionWithNoTax = $action - $actionTax;
    $priceWithNoTax = $netPrice - $totalTax;

    //PDF config

    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new  \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'fontDir' => array_merge($fontDirs, [
            __DIR__ . '/assets/fonts',
        ]),
        'fontdata' => $fontData + ['effra' => ['R' => 'Effra_Std_Lt.ttf', 'B' => 'Effra_Std_Rg.ttf']],
        'default_font' => 'effra',
        'tempDir' => '/tmp'
    ]);

    //templates

    $clientTemplate1 = file_get_contents('client-template-1.html');
    $clientTemplate2 = file_get_contents('client-template-2.html');

    $hookingPage1 = file_get_contents('hooking-document-1.html');
    $hookingDynamicPage = file_get_contents('hooking-dynamic-page.html');
    $hookingPage2 = file_get_contents('hooking-document-2.html');
    $hookingPage3 = file_get_contents('hooking-document-3.html');
    $hookingPage4 = file_get_contents('hooking-document-4.html');
    $hookingPage5 = file_get_contents('hooking-document-5.html');


    $stylesheet_hooking_page_1 = file_get_contents('style_hooking_page_1.css');
    $stylesheet_hooking_page_2 = file_get_contents('style_hooking_page_2.css');
    $stylesheet_hooking_dynamic_page = file_get_contents('style_hooking_dynamic_page.css');
    $stylesheet_hooking_page_3 = file_get_contents('style_hooking_page_3.css');
    $stylesheet_hooking_page_4 = file_get_contents('style_hooking_page_4.css');
    $stylesheet_hooking_page_5 = file_get_contents('style_hooking_page_5.css');


    $hookingPage1 = str_replace('{{date}}', date("d/m/Y"), $hookingPage1);
    $hookingPage1 = str_replace('{{codification}}', $apartment->codification, $hookingPage1);
    $hookingPage1 = str_replace('{{rooms}}', $apartment->rooms, $hookingPage1);


    $clientsListSize = count($clients);

    $dynamicPagesList = array();
    $tmpDynamicTemplate = $hookingPage1;


    for ($i = 1; $i <= $clientsListSize; $i++)
    {
        $isPair = (fmod($i, 2) == 0);
        $tmpDynamicTemplate = $tmpDynamicTemplate . getFilledTemplate($isPair ? 1 : 0, $clients[$i - 1], $clientTemplate1, $clientTemplate2);

        if ($isPair || $clientsListSize === 1 || $i === $clientsListSize)
        {
            $tmpDynamicTemplate = str_replace('{{date}}',  date("d/m/Y"), $tmpDynamicTemplate);
            $tmpDynamicTemplate = str_replace('{{codification}}',  $apartment->codification, $tmpDynamicTemplate);
            array_push($dynamicPagesList, $tmpDynamicTemplate);
            $tmpDynamicTemplate = $hookingDynamicPage;
        }
    }

    $tmpDynamicTemplate = '';

    $hookingPage2 = str_replace('{{date}}', date("d/m/Y"), $hookingPage2);
    $hookingPage2 = str_replace('{{codification}}', $apartment->codification, $hookingPage2);
    $hookingPage2 = str_replace('{{payment_type}}', $config['paymentType'], $hookingPage2);
    $hookingPage2 = str_replace('{{module}}', $apartment->tower, $hookingPage2);
    $hookingPage2 = str_replace('{{bank}}', $config['bank'], $hookingPage2);
    $hookingPage2 = str_replace('{{deposit_number}}', $config['depositNumber'], $hookingPage2);
    $hookingPage2 = str_replace('{{bank_check}}', $config['bankCheck'], $hookingPage2);
    $hookingPage2 = str_replace('{{reservation_check}}', $config['checkNumber'], $hookingPage2);
    $hookingPage2 = str_replace('{{deposit_number}}', $config['depositNumber'], $hookingPage2);
    $hookingPage2 = str_replace('{{total_with_tax}}', 'Q '.formatNumber($netPrice), $hookingPage2);
    $hookingPage2 = str_replace('{{hooking}}', 'Q ' . formatNumber($hooking), $hookingPage2);
    $hookingPage2 = str_replace('{{reservation_amount}}', 'Q ' . formatNumber($config['reservationAmount']), $hookingPage2);
    $hookingPage2 = str_replace('{{hooking_without_reservation}}', 'Q ' . formatNumber($hookingWithoutReservation), $hookingPage2);
    $hookingPage2 = str_replace('{{amount_to_pay}}', 'Q ' . formatNumber($initialBalance), $hookingPage2);

    $releaseDate = new DateTime('NOW');

    date_add($releaseDate, date_interval_create_from_date_string("20 months"));

    $hookingPage2 = str_replace('{{release_date}}', date('d/m/Y', $releaseDate->getTimestamp()), $hookingPage2);


    $hookingPage3 = str_replace('{{codification}}', $apartment->codification, $hookingPage3);
    $hookingPage3 = str_replace('{{reservation_amount}}', 'Q. ' . formatNumber($reservation), $hookingPage3);
    $hookingPage3 = str_replace('{{last_payment}}', 'Q. ' . formatNumber($netPrice - $hooking), $hookingPage3);
    $hookingPage3 = str_replace('{{hooking}}', 'Q. ' . formatNumber($hooking - $reservation - $promisePayment), $hookingPage3);
    $hookingPage3 = str_replace('{{hooking_amount}}', formatNumber($hooking), $hookingPage3);
    $hookingPage3 = str_replace('{{reservation_day}}', date('d/m/Y', strtotime($reservationDate)), $hookingPage3);
    $hookingPage3 = str_replace('{{reservation_month}}', getMonthName(date('m', strtotime($reservationDate))), $hookingPage3);
    $hookingPage3 = str_replace('{{reservation_year}}', '20' . date('y', strtotime($reservationDate)), $hookingPage3);
    $hookingPage3 = str_replace('{{hooking_percentage}}', $config['hookingPercentage'], $hookingPage3);


    $feeList = '';

    $scheduleSize = count($paymentSchedule);

    for ($i = 1; $i <= $scheduleSize; $i++) {
        $currentPayment = $paymentSchedule[$i - 1];
        $feeList = $feeList .
            '<p class="element-column-1-row-' . ($i + 2) . '">
              ' . $i . '
             </p>

             <p class="element-column-2-row-' . ($i + 2) . '">
               Q ' . formatNumber($currentPayment->payment) . '
             </p>

             <p class="element-column-3-row-' . ($i + 2) . '">
               ' . date('d', strtotime($currentPayment->date)) . '
             </p>

             <p class="element-column-4-row-' . ($i + 2) . '">
                 ' . getMonthName(date('m', strtotime($currentPayment->date))) . '
             </p>

             <p class="element-column-5-row-' . ($i + 2) . '">
                 20' . date('y', strtotime($currentPayment->date)) . '
             </p>
             <div class="element-separator-' . ($i + 2) . '">

             </div>';
        if ($i == $scheduleSize) {
            $feeList = $feeList . '<div class="element-separator-' . ($i + 3) . '"></div>';
        }
    }

    $hookingPage3 = str_replace('{{fee_list}}', $feeList, $hookingPage3);

    $hookingPage4 = str_replace('{{date}}',date('d/m/Y'), $hookingPage4);
    $hookingPage4 = str_replace('{{codification}}', $apartment->codification, $hookingPage4);


    $hookingPage5 = str_replace('{{date}}',date('d/m/Y'), $hookingPage5);
    $hookingPage5 = str_replace('{{codification}}', $apartment->codification, $hookingPage5);
    $hookingPage5 = str_replace('{{conditions_text}}', $conditions, $hookingPage5);

    $validDate = new DateTime('NOW');

    date_add($validDate, date_interval_create_from_date_string("15 days"));

    $hookingPage5 = str_replace('{{valid_offer_date}}', date('d/m/Y', $validDate->getTimestamp()), $hookingPage5);

    $filename = 'hookings/ENGANCHE_' . strtoupper(date("d-m-Y")) . '_' . strtoupper($clients[0]->name) . '_' . strtoupper($apartment->codification) . '.pdf';
    //$filename = '/tmp/test.pdf';

    $sessionUser = User::find($_SESSION["userIdNaos"]);

    $hooking = Hooking::create([
        'date' => date('Y-m-d H:i:s'),
        'user_name' => $sessionUser->fullname,
        'email' => $sessionUser->email,
        'apartment' => $apartment->codification,
        'client_name' => $clients[0]->name,
        'client_mail' => $clients[0]->email,
        'document_name' => $filename

    ]);


    foreach($clients as $client)
    {
        $dpiImageName = getImageName($client->dpiPicture);
        $receiptImageName = getImageName($client->receiptPicture);

        saveImage($client->dpiPicture, $dpiImageName);
        saveImage($client->receiptPicture, $receiptImageName);

        $newReservationClient = ReservationClient::create([
            'name' => $client->name,
            'email' => $client->email,
            'nit' => $client->nit,
            'dpi' => $client->dpi,
            'dpiCreation' => $client->dpiCreation,
            'address' => $client->address,
            'linePhone' => $client->linePhone,
            'cellPhone' => $client->cellPhone,
            'nationality' => $client->nationality,
            'birthday' => $client->birthDay,
            'civilStatus' => $client->civilStatus,
            'company' => $client->company,
            'jobTitle' => $client->jobTitle,
            'companyAddress' => $client->companyAddress,
            'monthlySalary' => $client->monthlySalary,
            'otherIncomeAmount' => $client->otherIncomeAmount,
            'otherIncome' => $client->otherIncome,
            'hasFHACredit' => $client->hasFHACredit,
            'refNumber' => $client->refNumber,
            'dpiPictureName' => $dpiImageName,
            'receiptPictureName' => $receiptImageName,
            'hookingId' => $hooking->id
        ]);
    }

    $dynamicPagesListSize = count($dynamicPagesList);

    $totalPageNumber = $dynamicPagesListSize + 4;

    $correlative = 'NAOS-'.$apartment->tower.'-'.date("Y").'-'.sprintf("%07s", $hooking->id);

    for ($i = 1; $i <= $dynamicPagesListSize; $i++) {
        $pageNumberText = 'Página '.$i.' de '. $totalPageNumber;
        if($i === 1) { $dynamicPagesList[$i - 1] = str_replace('{{correlative_number}}', $correlative, $dynamicPagesList[$i - 1]); }

        $dynamicPagesList[$i - 1] = str_replace('{{page_number}}', $pageNumberText, $dynamicPagesList[$i - 1]);

        $mpdf->WriteHTML($i === 1 ? $stylesheet_hooking_page_1 : $stylesheet_hooking_dynamic_page, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($dynamicPagesList[$i - 1], \Mpdf\HTMLParserMode::HTML_BODY);

        //if(!$i === $dynamicPagesListSize || $dynamicPagesListSize === 1){
            $mpdf->addPage();
        //}
    }

    $hookingPage2 = str_replace('{{page_number}}', 'Página '.($dynamicPagesListSize + 1).' de '. $totalPageNumber, $hookingPage2);
    $mpdf->WriteHTML($stylesheet_hooking_page_2, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($hookingPage2, \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->addPage();

    $hookingPage3 = str_replace('{{page_number}}', 'Página '.($dynamicPagesListSize + 2).' de '. $totalPageNumber, $hookingPage3);
    $mpdf->WriteHTML($stylesheet_hooking_page_3, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($hookingPage3, \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->addPage();

    $hookingPage4 = str_replace('{{page_number}}', 'Página '.($dynamicPagesListSize + 3).' de '. $totalPageNumber, $hookingPage4);
    $mpdf->WriteHTML($stylesheet_hooking_page_4, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($hookingPage4, \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->addPage();

    $hookingPage5 = str_replace('{{page_number}}', 'Página '.($dynamicPagesListSize + 4).' de '. $totalPageNumber, $hookingPage5);
    $mpdf->WriteHTML($stylesheet_hooking_page_5, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($hookingPage5, \Mpdf\HTMLParserMode::HTML_BODY);

    try {
        $mpdf->Output($filename, \Mpdf\Output\Destination::FILE);
        echo $filename;
    } catch (Exception $e) {
        echo $e;
    }
    $mpdf->addPage();
}

$data = json_decode(file_get_contents("php://input"));

$apartmentId = $data->apartmentId;
$extraParking = $data->extraParking ? ($data->extraParking >= 0 ? $data->extraParking : 0) : 0;
$years = $data->years;
$discount = $data->discount;
$hookingPayments = $data->hookingPayments;
$reservationAmount = $data->reservationAmount;
$reservationDate = $data->reservationDate;
$conditions = $data->conditions;
$promisePayment = $data->promisePayment;

$apartment = Apartment::where('id', '=', $apartmentId)->first();
$globalData = Globale::all()[0];

if ($data->reservationAmount < $globalData->reservation_ammount) {
    echo 'No puede ingresar un monto de reserva menor al pre-establecido';
    http_response_code(400);
    die();
}


$clients = $data->clients;



$config = [
    'hookingPayments' => $hookingPayments,
    'hookingPercentage' => $data->hookingPercentage,
    'extraWarehouse' => $data->extraWarehouse,
    'reservationAmount' => $data->reservationAmount,
    'bank' => $data->bank,
    'depositNumber' => $data->depositNumber,
    'bankCheck' => $data->bankCheck,
    'checkNumber' => $data->checkNumber,
    'paymentType' => $data->paymentType,
    'hasNetDiscount' => $data->hasNetDiscount,
    'netDiscount' => $data->netDiscount,
];

$paymentSchedule = $data->paymentSchedule;
getPDF($apartment,
    $extraParking,
    $years,
    $globalData,
    $discount,
    $clients,
    $config,
    $paymentSchedule,
    $reservationAmount,
    $reservationDate,
    $conditions,
    $promisePayment);

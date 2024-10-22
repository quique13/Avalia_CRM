<?php
require "bootstrap.php";
require "models/Apartment.php";
require "models/Globale.php";
require "models/VirtualTour.php";
require "calculation.php";
require "mailer.php";

function formatNumber($value)
{
    return number_format($value, 2, '.', ',');
}

$data = json_decode(file_get_contents("php://input"));

$template = file_get_contents('email-templates/pre-quotation-template.html');

$apartment = null;
$globalData = Globale::all()[0];

$maxLevel = 0;
$minLevel = 0;

if ($data->hasGarden) {
    $apartment = Apartment::where('garden_mts', '>', 0)->orderBy('price', 'ASC')->first();
    $maxLevel = Apartment::where('garden_mts', '>', 0)->max('level');
    $minLevel = Apartment::where('garden_mts', '>', 0)->min('level');
} else {
    $apartment = Apartment::where([
        ['rooms', '=', $data->rooms],
        ['level', '>=', $data->range->min],
        ['level', '<=', $data->range->max]
    ])->orderBy('price', 'ASC')->first();
    $maxLevel = Apartment::where([
        ['rooms', '=', $data->rooms],
        ['level', '>=', $data->range->min],
        ['level', '<=', $data->range->max]
    ])->max('level');
    $minLevel = Apartment::where([
        ['rooms', '=', $data->rooms],
        ['level', '>=', $data->range->min],
        ['level', '<=', $data->range->max]
    ])->min('level');
}

$plants = [
    'A' => 'PLANTA-A.png',
    'B' => 'PLANTA-B.png',
    'C' => 'PLANTA-C.png',
    'D' => 'PLANTA-D.png',
    'E' => 'PLANTA-E.png'
];

$transparentPlants = [
    'A' => 'PLANTA-A-T.png',
    'B' => 'PLANTA-B-T.png',
    'C' => 'PLANTA-C-T.png',
    'D' => 'PLANTA-D-T.png',
    'E' => 'PLANTA-E-T.png'
];

function get_month_diff($start, $end = FALSE)
{
    $end OR $end = time();

    $start = new DateTime("@$start");
    $end   = new DateTime("@$end");
    $diff  = $start->diff($end);

    return $diff->format('%y') * 12 + $diff->format('%m');
}
//calculations

$gtqPrice = round(round($apartment->price * $globalData->dollar_exchange), -2);
$hooking = round(round(($globalData->hooking_percentage/100) * $gtqPrice), -3);

$fiveYearsFee = round(round(calculateMonthlyPayment(5, $gtqPrice, $globalData->rate)), -2);
$tenYearsFee = round(round(calculateMonthlyPayment(10, $gtqPrice, $globalData->rate)), -2);
$fifteenYearsFee = round(round(calculateMonthlyPayment(15, $gtqPrice, $globalData->rate)), -2);
$twentyYearsFee = round(round(calculateMonthlyPayment(20, $gtqPrice, $globalData->rate)), -2);
$twentyFiveYearsFee = round(round(calculateMonthlyPayment(25, $gtqPrice, $globalData->rate)), -2);


$template = str_replace('{{rooms_text}}',  $data->rooms == 1 ? 'con jardín' : ($data->rooms == 2 ? 'de 2 habitaciones' : 'de 3 habitaciones'), $template);
$template = str_replace('{{level_range}}', $minLevel.' - '.$maxLevel, $template);

$template = str_replace('{{monthly_fee}}','Q ' . formatNumber($twentyFiveYearsFee), $template);
$template = str_replace('{{hooking_percentage}}', $globalData->hooking_percentage, $template);
$template = str_replace('{{hooking}}', formatNumber($hooking), $template);
$template = str_replace('{{five_years_fee}}', formatNumber($fiveYearsFee), $template);
$template = str_replace('{{ten_years_fee}}', formatNumber($tenYearsFee), $template);
$template = str_replace('{{fifteen_years_fee}}', formatNumber($fifteenYearsFee), $template);
$template = str_replace('{{twenty_years_fee}}', formatNumber($twentyYearsFee), $template);
$template = str_replace('{{twenty_five_years_fee}}', formatNumber($twentyFiveYearsFee), $template);
$template = str_replace('{{apartment_letter}}', substr($apartment->codification, -1), $template);

$nowDate = new DateTime();
$projectDate = new DateTime($globalData->projectdate);
$difference = get_month_diff($nowDate->getTimestamp(), $projectDate->getTimestamp());
$template = str_replace('{{remaining_months}}', ($difference > 1 ? $difference . ' meses ' : $difference . ' mes '), $template);
//$template = str_replace('{{remaining_months}}', $globalData->projectdate, $template);

$n=5;
function getPassword($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

$medium = $data->source ? $data->source : 'Website';
$source = $data->medium ? $data->medium : 'Orgánico';

$leadTemplate = file_get_contents('email-templates/lead-template.html');
$leadTemplate = str_replace('{{userName}}', $data->userData->user, $leadTemplate);
$leadTemplate = str_replace('{{userEmail}}', $data->userData->email, $leadTemplate);
$leadTemplate = str_replace('{{userPhone}}', $data->userData->phone, $leadTemplate);
$leadTemplate = str_replace('{{date}}', date("d/m/Y"), $leadTemplate);
$leadTemplate = str_replace('{{message}}', 'Generado desde pre-cotización', $leadTemplate);
$leadTemplate = str_replace('{{source}}', $source, $leadTemplate);
$leadTemplate = str_replace('{{medium}}', $medium, $leadTemplate);

$arrayName = explode(" ", $data->userData->user);

$userName = strtolower(count($arrayName) >= 2 ? substr($arrayName[0], 0, 3).substr($arrayName[1], 0, 3).strval(rand(1, 100)) :  substr($arrayName[0], 0, 3).strval(rand(1, 200)));
$password = strtolower(getPassword($n));

$template = str_replace('{{user}}', $userName, $template);
$template = str_replace('{{password}}', $password, $template);
try {
    $newVirtualTour = VirtualTour::create([
        'username' => $userName,
        'password' => $password,
        'sessions' => 0
    ]);

} catch (Exception $e) {
    echo $e;
}

sendMail('Pre-Cotización Avalia', $data->userData->email, $data->userData->user, $template);

//sendMail('Tienes un contacto nuevo asignado en iniciativas: ' . $data->userData->user, 'ventas@avalia1.odoo.com', $_POST['name'], $leadTemplate);

//ventas@avalia1.odoo.com


//echo json_encode($apartment);


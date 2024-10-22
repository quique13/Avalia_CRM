<?php
require_once __DIR__ . '/vendor/autoload.php';
require "bootstrap.php";
require "models/Apartment.php";
require "models/Globale.php";
require "calculation.php";
session_start();
if (empty($_SESSION["userIdNaos"])) {
    header("Location: login.php");
    exit();
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
    foreach ($string as $char)
    {
        if ($counter == 4)
        {
            $outputString = $outputString . $char . '-';
        }
        else
        {
            $outputString = $outputString . $char;
        }
        $counter++;
    }
    return $outputString;
}

function getCalculation($apartment, $extraParking, $years, $globalData, $discountPercentage, $userData, $config)
{
    //general calculations

    $extraParkingCostGTQ = $globalData->extra_parking_price * $globalData->dollar_exchange;
    $extraParkingTotal = $globalData->extra_parking_price * $extraParking * $globalData->dollar_exchange;
    $extraWarehouseTotal = $globalData->dollar_exchange * $apartment->warehouse_price * $config['extraWarehouse'];
    $priceGTQ = ($apartment->price * $globalData->dollar_exchange)
        + $extraParkingTotal
        + $extraWarehouseTotal;
        
    $apLetter = $apartment->codification[1];    
        
    $kitchenPrice = $config['hasKitchen'] ? (($apLetter === 'A' || $apLetter === 'C') ? $globalData->kitchen_price_a : $globalData->kitchen_price_b) : 0; 
    
    $priceGTQ = $priceGTQ + $kitchenPrice;
    
    $discount = $config['hasNetDiscount'] == true ? $config['netDiscount'] : round(round($priceGTQ * ($discountPercentage / 100)));

    $netPrice = round(round($priceGTQ - $discount));
    $hooking = round(round($netPrice * ($config['hookingPercentage'] / 100)));
    $initialBalance = round(round($netPrice - $hooking));
    $iusiFee = round(calculateIUSIAmount($netPrice, $globalData->invoicing_percentage, $globalData->iusi_rate));
    $insuranceFee = round(calculateInsurance($netPrice, $globalData->insurance_rate, $years));
    $creditFee = round(calculateMonthlyPayment($years, $initialBalance, $globalData->rate));
    $monthlyFee = round(round($creditFee
        + $iusiFee
        + $insuranceFee));
    $requiredIncome = round(round((100 / 35) * $monthlyFee));
    $hookingPayments = $config['hookingPayments'];
    $hookingFee = $hooking / $hookingPayments;

    echo json_encode(array(
        "monthlyFee" => $monthlyFee,
        "creditFee" => $creditFee,
        "requiredIncome" => $requiredIncome,
        "hookingFee" => $hookingFee,
        "priceGTQ" => $netPrice,
        "hooking" => $hooking,
        "insuranceFee" => $insuranceFee,
        "iusiFee" => $iusiFee,
        "lastPayment" => $netPrice - $hooking,
        "hookingFee" => $hookingFee
    ));

}


$data = json_decode(file_get_contents("php://input"));

//echo json_encode($data);

$apartmentId = $data->apartmentId;
$extraParking = $data->extraParking ? ($data->extraParking >= 0 ? $data->extraParking : 0) : 0;
$years = $data->years;
$discount = $data->discount;
$userName = $data->userName;
$userEmail = $data->userEmail;
$userPhone = $data->userPhone;
$hookingPayments = $data->hookingPayments;
//$contact = $data->contact;
//$contactPhone = $data->contactPhone;
//$contactEmail = $data->contactEmail;



$apartment = Apartment::where('id', '=', $apartmentId)->first();
$globalData = Globale::all()[0];

if($data->reservationAmount < $globalData->reservation_ammount) {
    echo 'No puede ingresar un monto de reserva menor al pre-establecido';
    http_response_code(400);
    die();
}

$user = [
    'name' => $userName,
    'email' => $userEmail,
    'phone' => $userPhone
];

$config = [
    'hookingPayments' => $hookingPayments,
    'hookingPercentage' => $data->hookingPercentage,
    'contact' => $data->contact,
    'contactPhone' => $data->contactPhone,
    'contactEmail' => $data->contactEmail,
    'extraWarehouse' => 0, //$data->extraWarehouse,
    'reservationAmount' => $data->reservationAmount,
    'hasNetDiscount' => $data->hasNetDiscount,
    'netDiscount' => $data->netDiscount,
    'hasKitchen' => $data->hasKitchen
];
getCalculation($apartment, $extraParking, $years, $globalData, $discount, $user, $config);
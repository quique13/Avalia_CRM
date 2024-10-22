<?php
require "bootstrap.php";
require "models/Apartment.php";
require "models/Globale.php";
require "calculation.php";
require "middleware.php";

/*session_start();
if (empty($_SESSION["userIdNaos"])) {
    header("Location: login.php");
    exit();
}*/


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
$contact = $data->contact;
$contactPhone = $data->contactPhone;
$contactEmail = $data->contactEmail;

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
    'contact' => $contact,
    'contactPhone' => $contactPhone,
    'contactEmail' => $contactEmail,
   // 'extraWarehouse' => $data->extraWarehouse,
    'reservationAmount' => $data->reservationAmount,
    'hasNetDiscount' => $data->hasNetDiscount,
    'netDiscount' => $data->netDiscount,
    'hasKitchen' => $data->hasKitchen
];
getPdfQuotation($apartment, $extraParking, $years, $globalData, $discount, $user, $config);




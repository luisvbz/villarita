<?php



include 'api_client/Sms.php';


$sms = new api();

$parameters = array();
$parameters['cuenta_token'] 	= '5efdf4ab22b5eae853c6304cde484f6b2cac3fa5';
$parameters['aplicacion_token'] = 'c7c974fb3bffba1197ca6abe614b133db31c9c6a';

// true:  Si deseas mostrar la respuesta en formato JSON que retorna la solicitud
// false: Si no deseas mostrar  la respuesta en formato JSON que retorna la solicitud
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sms->detalles($parameters, true);


?>

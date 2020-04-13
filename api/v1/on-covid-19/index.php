<?php

include '../../../src/classes/Covidestimator.php';

$start = microtime(true);



$postBody = file_get_contents("php://input"); 

$method = $_SERVER['REQUEST_METHOD'];

$path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$data = json_decode($postBody, 1);
$case1 = new Covidestimator($data);

$resultArray = $case1->getOutput();

// $resultArray['data'] = json_decode($postBody);


$time_elapsed_secs = microtime(true) - $start;
// log the request which basically means writing to a text file log file....
$timeinmilliseconds = round($time_elapsed_secs * 1000, 2);
$logMessage = $method ."\t ". $path_only . "\t ". "200" . "\t ". $timeinmilliseconds ."ms";
$case1->CreateLog($logMessage);



if($path_only == '/api/v1/on-covid-19/xml')
{

    $testarr = ['name'=>'mike','age'=> 23];
    $xmlresponse =  $case1->array2xml($resultArray);
    header('Content-Type: application/xml');
    echo $xmlresponse;
} 
else {
    header('Content-Type: application/json');
    echo json_encode($resultArray);
}


?>
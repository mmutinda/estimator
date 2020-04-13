<?php

include '../../../src/classes/Covidestimator.php';

$start = microtime(true);



$postBody = file_get_contents("php://input"); 



$method = $_SERVER['REQUEST_METHOD'];

$path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$case1 = new Covidestimator($data);

$resultArray = $case1->getOutput();

$resultArray['data'] = json_decode($postBody);


$time_elapsed_secs = microtime(true) - $start;
// log the request which basically means writing to a text file log file....

echo json_encode($resultArray);


?>
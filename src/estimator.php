<?php

include 'classes/Covidestimator.php';



function covid19ImpactEstimator($data)
{
  $case1 = new Covidestimator($data);
  return $case1->getOutput();
}



// $jsonStr2 = '{
//   "region": {
//     "name": "Africa",
//     "avgAge": 19.7,
//     "avgDailyIncomeInUSD": 4,
//     "avgDailyIncomePopulation": 0.73
//   },
//   "periodType": "days",
//   "timeToElapse": 38,
//   "reportedCases": 2747,
//   "population": 92931687,
//   "totalHospitalBeds": 678874
// }';



// var_dump(json_decode($jsonStr,1));

// echo json_encode(covid19ImpactEstimator(json_decode($jsonStr,1)));

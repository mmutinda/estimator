<?php

include 'classes/Covidestimator.php';



function covid19ImpactEstimator($data)
{
  $case1 = new Covidestimator($data);
  return $case1->getOutput();
}



// $jsonStr = '{
//   "region": {
//     "name": "Africa",
//     "avgAge": 19.7,
//     "avgDailyIncomeInUSD": 5,
//     "avgDailyIncomePopulation": 0.71
//   },
//   "periodType": "days",
//   "timeToElapse": 58,
//   "reportedCases": 674,
//   "population": 66622705,
//   "totalHospitalBeds": 1380614
// }';

// var_dump(json_decode($jsonStr,1));

// covid19ImpactEstimator(json_decode($jsonStr,1));

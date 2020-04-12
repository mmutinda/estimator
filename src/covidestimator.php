<?php


class CovidEstimator
{


    public $inputdata;
    public $response = [];

    function __construct($inputDataStr)
    {

        $decodedData = json_decode($inputDataStr);

        $this->inputdata = $decodedData;

        $currentlyInfectedUnderImpact = 10 * $decodedData->reportedCases;
        $currentlyInfectedUnderSevere = 50 * $decodedData->reportedCases;
        $this->response['impact']['currentlyInfected'] = $currentlyInfectedUnderImpact;
        $this->response['severeImpact']['currentlyInfected'] =  $currentlyInfectedUnderSevere;

        //confirm...keys
        $impactinfectionsByRequestedTime = $this->infectionsByRequestedTime($currentlyInfectedUnderImpact, $decodedData->timeToElapse, $decodedData->periodType);
        $severeinfectionsByRequestedTime = $this->infectionsByRequestedTime( $currentlyInfectedUnderSevere, $decodedData->timeToElapse,  $decodedData->periodType);

        $this->response['impact']['infectionsByRequestedTime'] = $impactinfectionsByRequestedTime;
        $this->response['severeImpact']['infectionsByRequestedTime'] = $severeinfectionsByRequestedTime;

    
        $this->response['impact']['severeCasesByRequestedTime'] = 0.15 * $impactinfectionsByRequestedTime;
        $this->response['severeImpact']['severeCasesByRequestedTime'] = 0.15 * $severeinfectionsByRequestedTime;

        //challenge 2

        $this->response['impact']['hospitalBedsByRequestedTime'] = 0.35 * $decodedData->totalHospitalBeds;
        $this->response['severeImpact']['hospitalBedsByRequestedTime'] = 0.35 * $decodedData->totalHospitalBeds;

        //challenge 3
        
        $this->response['impact']['casesForICUByRequestedTime'] = 0.05 * $impactinfectionsByRequestedTime;
        $this->response['severeImpact']['casesForICUByRequestedTime'] = 0.05 * $severeinfectionsByRequestedTime;

         
        $this->response['impact']['casesForVentilatorsByRequestedTime'] = 0.02 * $impactinfectionsByRequestedTime;
        $this->response['severeImpact']['casesForVentilatorsByRequestedTime'] = 0.02 * $severeinfectionsByRequestedTime;

        $region = $decodedData->region;
        $avgDailyIncomeInUSD = $region->avgDailyIncomeInUSD;
        $avgDailyIncomePopulation = $region->avgDailyIncomePopulation;

        $this->response['impact']['dollarsInFlight'] = $impactinfectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD * $decodedData->timeToElapse;
        $this->response['severeImpact']['dollarsInFlight'] = $severeinfectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD * $decodedData->timeToElapse;


    }





    public function infectionsByRequestedTime($currentlyInfected, $count, $periodType)
    {

        $localDays = 0;
        if ($periodType == 'days')
        {
            $localDays = $count;
        } else if ($periodType == 'weeks')
        {
            $localDays = $count * 7;

        } else if ($periodType == 'months')
        {
            $localDays = $count * 30;
        } else {
            $localDays = $count;
        }

        $res =  floor($localDays / 3);

        return $currentlyInfected * pow(2, $res);
        
    }

    public function getOutput() 
    {
        $this->response['data'] = $this->inputdata;
        return json_encode($this->response);
    }



}

$jsonStr = '{
    "region": {
      "name": "Africa",
      "avgAge": 19.7,
      "avgDailyIncomeInUSD": 5,
      "avgDailyIncomePopulation": 0.71
    },
    "periodType": "days",
    "timeToElapse": 58,
    "reportedCases": 674,
    "population": 66622705,
    "totalHospitalBeds": 1380614
  }';

$case1 = new CovidEstimator($jsonStr);
echo $case1->getOutput();

<?php


class Covidestimator
{


    public $inputdata;
    public $response = [];

    function __construct($inputDataStr)
    {

        $decodedData = $inputDataStr;

        $this->inputdata = $decodedData;

        $currentlyInfectedUnderImpact = 10 * $decodedData['reportedCases'];
        $currentlyInfectedUnderSevere = 50 * $decodedData['reportedCases'];
        $this->response['impact']['currentlyInfected'] = $currentlyInfectedUnderImpact;
        $this->response['severeImpact']['currentlyInfected'] =  $currentlyInfectedUnderSevere;

        //confirm...keys
        $impactinfectionsByRequestedTime = $this->infectionsByRequestedTime($currentlyInfectedUnderImpact, $decodedData['timeToElapse'], $decodedData['periodType']);
        $severeinfectionsByRequestedTime = $this->infectionsByRequestedTime( $currentlyInfectedUnderSevere, $decodedData['timeToElapse'],  $decodedData['periodType']);

        $this->response['impact']['infectionsByRequestedTime'] = $impactinfectionsByRequestedTime;
        $this->response['severeImpact']['infectionsByRequestedTime'] = $severeinfectionsByRequestedTime;

    
        $this->response['impact']['severeCasesByRequestedTime'] = floor(0.15 * $impactinfectionsByRequestedTime);
        $this->response['severeImpact']['severeCasesByRequestedTime'] = floor(0.15 * $severeinfectionsByRequestedTime);

        //challenge 2

        $this->response['impact']['hospitalBedsByRequestedTime'] = floor((0.35 * $decodedData['totalHospitalBeds']) - $impactinfectionsByRequestedTime);
        $this->response['severeImpact']['hospitalBedsByRequestedTime'] = floor((0.35 * $decodedData['totalHospitalBeds']) - $severeinfectionsByRequestedTime);

        //challenge 3
        
        $this->response['impact']['casesForICUByRequestedTime'] = floor(0.05 * $impactinfectionsByRequestedTime);
        $this->response['severeImpact']['casesForICUByRequestedTime'] = floor(0.05 * $severeinfectionsByRequestedTime);

         
        $this->response['impact']['casesForVentilatorsByRequestedTime'] = floor(0.02 * $impactinfectionsByRequestedTime);
        $this->response['severeImpact']['casesForVentilatorsByRequestedTime'] = floor(0.02 * $severeinfectionsByRequestedTime);

        $region = $decodedData['region'];
        $avgDailyIncomeInUSD = $region['avgDailyIncomeInUSD'];
        $avgDailyIncomePopulation = $region['avgDailyIncomePopulation'];

        $this->response['impact']['dollarsInFlight'] = floor($impactinfectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD * $decodedData['timeToElapse']);
        $this->response['severeImpact']['dollarsInFlight'] = floor($severeinfectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD * $decodedData['timeToElapse']);


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

        return floor($currentlyInfected * pow(2, $res));
        
    }

    public function getOutput() 
    {
        $this->response['data'] = $this->inputdata;
        var_dump($this->response);
        return $this->response;
    }



}

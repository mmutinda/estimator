<?php


class Covidestimator
{


    public $inputdata;
    public $response = [];

    function __construct($inputDataStr)
    {

        // print_r(json_encode($inputDataStr));
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

    
        $impactsevereCasesByRequestedTime = intval (0.15 * $impactinfectionsByRequestedTime);
        $severeImpactsevereCasesByRequestedTime = intval(0.15 * $severeinfectionsByRequestedTime);


        $this->response['impact']['severeCasesByRequestedTime'] = $impactsevereCasesByRequestedTime;
        $this->response['severeImpact']['severeCasesByRequestedTime'] = $severeImpactsevereCasesByRequestedTime;

        //challenge 2

        $this->response['impact']['hospitalBedsByRequestedTime'] = intval((0.35 * $decodedData['totalHospitalBeds']) - $impactsevereCasesByRequestedTime);
        $this->response['severeImpact']['hospitalBedsByRequestedTime'] = intval((0.35 * $decodedData['totalHospitalBeds']) - $severeImpactsevereCasesByRequestedTime);

        // var_dump( $this->response['impact']['hospitalBedsByRequestedTime'] );
        // var_dump(  $this->response['severeImpact']['hospitalBedsByRequestedTime'] );
        // die();
        //challenge 3
        
        $this->response['impact']['casesForICUByRequestedTime'] = intval(0.05 * $impactinfectionsByRequestedTime);
        $this->response['severeImpact']['casesForICUByRequestedTime'] = intval(0.05 * $severeinfectionsByRequestedTime);

         
        $this->response['impact']['casesForVentilatorsByRequestedTime'] = intval(0.02 * $impactinfectionsByRequestedTime);
        $this->response['severeImpact']['casesForVentilatorsByRequestedTime'] = intval(0.02 * $severeinfectionsByRequestedTime);

        $region = $decodedData['region'];
        $avgDailyIncomeInUSD = $region['avgDailyIncomeInUSD'];
        $avgDailyIncomePopulation = $region['avgDailyIncomePopulation'];

        $this->response['impact']['dollarsInFlight'] = $this->getDollarsInflight($impactinfectionsByRequestedTime , $avgDailyIncomePopulation, $avgDailyIncomeInUSD,$decodedData['periodType'], $decodedData['timeToElapse']) ;
        $this->response['severeImpact']['dollarsInFlight'] = $this->getDollarsInflight($severeinfectionsByRequestedTime , $avgDailyIncomePopulation, $avgDailyIncomeInUSD,$decodedData['periodType'], $decodedData['timeToElapse']) ; 
        


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

        $res =  intval($localDays / 3);

        return intval($currentlyInfected * pow(2, $res));
        
    }

    public function getDollarsInflight($infectionsByRequestedTime, $avgDailyIncomePopulation, $avgDailyIncomeInUSD, $periodType, $count)
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


        $result = ($infectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD) / $localDays;


        return intval($result);
        
    }

    public function getOutput() 
    {
        $this->response['data'] = $this->inputdata;
        // echo json_encode($this->response);
        // die();
        return $this->response;
    }


    public function CreateLog($message, $file = 'log.txt')
    {
        date_default_timezone_set("Africa/Nairobi");

        $destinationPath = '../../../api/v1/on-covid-19/logs'; 


        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        // using the FILE_APPEND flag to append the content to the end of the file
        // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
        file_put_contents($destinationPath . '/' . $file, $message, FILE_APPEND | LOCK_EX);
        file_put_contents($destinationPath . '/' . $file, "\n", FILE_APPEND | LOCK_EX);
    }

    public function array2xml($array, $xml = false){

        if($xml === false){
            $xml = new SimpleXMLElement('<result/>');
        }
    
        foreach($array as $key => $value){
            if(is_array($value)){
                $this->array2xml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }
    
        return $xml->asXML();
    }



}

<?php


class CovidEstimator
{

    public $periodType = "days";
    public $timeToElapse = 58;
    public $reportedCases = 674;
    public $population = 66622705;
    public $totalHospitalBeds = 1380614;

    public $inputdata;
    public $response = [];

    function __construct($inputDataStr)
    {

        $decodedData = json_decode($inputDataStr);

        $this->inputdata = $decodedData;

        $this->response['impact']['currentlyInfected'] = 10 * $decodedData->reportedCases;
        $this->response['severeImpact']['currentlyInfected'] = 50 * $decodedData->reportedCases;

    }





    function currentInfected()
    {
        echo "TODAYS REPORTED CASES:" . "<br>";
        return $this->reportedCases . "<br>";
    }

    function impact()
    {
        echo "The impact of unknown infected people:" . "<br>";
        return $this->reportedCases * 10 . "<br>";
    }




    function severeimpact()

    {
        $date = date('Y-m-d') . "<br>";
        echo "Todays reported cases date :" . $date;
        $create = date('Y-m-d', strtotime("+28 days"));


        echo "In 28 days to come which will be on this date:" . $create . "<br>";
        echo "The infected people will be" . "<br>";
        return $this->reportedCases * 512 . " people.";
    }
}
// $case1 = new CovidData(3);
// echo $case1->currentInfected();
// echo $case1->impact();
// echo $case1->severeimpact();

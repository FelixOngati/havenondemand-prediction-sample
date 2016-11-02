<?php
include 'vendor/havenondemand/havenondemand/lib/hodclient.php';
$hodClient = new HODClient('d9c00207-31b3-43f7-83fe-aa025aa67cd7');

$before = microtime(true);

function requestCompletedWithContent($response) {
    $array = json_decode(json_encode($response), True);

    print_r($array);
};



function requestCompletedWithContent2($response) {
    $i = 0;
    foreach ($response as $row){

        if ($i == 1){
            $split_strings = preg_split('/[\ \n\,]+/', $row);
//            unset($split_strings[4]);
            array_splice($split_strings,4,1);
//            array_values($split_strings);
            for ($x = 0; $x < sizeof($split_strings); $x=$x+5){
                echo $split_strings[$x].", ".$split_strings[$x+1].", ".$split_strings[$x+2].", ".$split_strings[$x+3].", ".$split_strings[$x+4];
                echo "<br/>";
            }
            //print_r($split_strings);
        }
        $i++;

//        print_r(explode(",",$row));
    }

}


function requestCompletedWithJobId($response) {
    $jobID = $response;
//    echo $jobID;
}

$serviceName = 'carsService';
$predictionField = 'color';
$filePathTrainPredictor = 'data_sets/train_predictor.csv';
$jobID = '';
$dataTrainPredictor = array(
    'file' => $filePathTrainPredictor,
    'prediction_field' => $predictionField,
    'service_name' => $serviceName
);

$hodClient->PostRequest($dataTrainPredictor, HODApps::TRAIN_PREDICTOR, REQ_MODE::ASYNC, 'requestCompletedWithJobId');

$hodClient->GetJobStatus($jobID, 'requestCompletedWithContent');


$filePathPredict = 'data_sets/predict.csv';
$format = 'csv';
$dataPredict = array(
    'file' => $filePathPredict,
    'service_name' => $serviceName,
    'format' => $format
);

$hodClient->PostRequest($dataPredict, HODApps::PREDICT, REQ_MODE::SYNC, 'requestCompletedWithContent2');

$after = microtime(true);

//echo ($after-$before);
<?php
include 'vendor/havenondemand/havenondemand/lib/hodclient.php';
$hodClient = new HODClient('d9c00207-31b3-43f7-83fe-aa025aa67cd7');

$before = microtime(true);

function requestCompletedWithContent($response) {
    print_r($response);
}

function requestCompletedWithContent2($response) {
    foreach ($response as $row){
        echo "<br/>";
        print_r($row);
    }

}


function requestCompletedWithJobId($response) {
    $jobID = $response;
    echo $jobID;
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

//$hodClient->PostRequest($dataTrainPredictor, HODApps::TRAIN_PREDICTOR, REQ_MODE::ASYNC, 'requestCompletedWithJobId');

$hodClient->GetJobStatus("w-eu_1472153d-0e2f-45c7-9b35-0e6a0ff15197", 'requestCompletedWithContent');


$filePathPredict = 'data_sets/predict.csv';
$format = 'csv';
$dataPredict = array(
    'file' => $filePathPredict,
    'service_name' => $serviceName,
    'format' => $format
);

$hodClient->PostRequest($dataPredict, HODApps::PREDICT, REQ_MODE::SYNC, 'requestCompletedWithContent');

$after = microtime(true);

echo ($after-$before);
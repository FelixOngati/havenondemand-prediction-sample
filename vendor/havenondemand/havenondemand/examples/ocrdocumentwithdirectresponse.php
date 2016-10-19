<?php
include "libs/hodclient.php";


$hodClient = new HODClient("34a54d30-ddaa-4294-8e45-ebe07eefe55e");

$paramArr = array(
    'file' => "0005r005.gif",
    'mode' => "document_photo"
);

$jobID = $hodClient->PostRequest($paramArr, HODApps::OCR_DOCUMENT, REQ_MODE::ASYNC);

if ($jobID == null)
{
    $errors = $hodClient->getLastError();
    $err = $errors[0];
    echo ($err->error." / " . $err->reason . " / " . $err->detail);
} else {
    $response = $hodClient->GetJobResult($jobID);
    if ($response == null)
    {
        $errors = $hodClient->getLastError();
        $err = $errors[0];
        echo ("Error code: " . $err->error."</br>Error reason: " . $err->reason . "</br>Error detail: " .  $err->detail . "JobID: " . $err->jobID);
    } else {
        $result = "";
        $textBlocks = $response->text_block;
        for ($i = 0; $i < count($textBlocks); $i++) {
            $block = $textBlocks[$i];
            $result .= "<html><body><p>";
            $result .= preg_replace("/\n+/", "</br>", $block->text);
            $result .= "</p></body></html>";
        }
        echo "RECOGNIZED TEXT: " . $result;
    }
}
?>
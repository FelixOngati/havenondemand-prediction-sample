# HODClient Library for PHP. V2.0

----
## Overview
HODClient for PHP is a utility class, which helps you easily integrate your .php project with HP Haven OnDemand Services.

HODClient V2.0 supports bulk input (source inputs can be an array) where an HOD API is capable of doing so.

HODClient class exposes source code so you can modify it as you wish.

----
## Integrate HODClient into php project
1. Download the HODClient library for PHP.
2. Unzip and copy the hodclient.php under the lib folder to your project folder.
3. Include the hodclient.php file in your php file. 

----
## API References
**Constructor**

    HODClient($apiKey, $version = "v1")

*Description:* 
* Creates and initializes a HODClient object.

*Parameters:*
* $apiKey: your developer apikey.
* $version: Haven OnDemand API version. Currently it only supports version 1. Thus, the default value is "v1".

*Example code:*
    include "hodclient.php"
    $hodClient = new HODClient("your-api-key");

----
**Function GetRequest**

    GetRequest($paramArr, $hodApp, $mode, $callback)

*Description:* 
* Sends a HTTP GET request to call an Haven OnDemand API.

*Parameters:*
* $paramArr: an array() containing key/value pair parameters to be sent to a Haven OnDemand API, where the keys are the parameters of that Haven OnDemand API.

>Note: 

>In the case of a parameter type is an array<>, the value must be defined as an array() or [].
>E.g.:
``` 
$sources = array();
array_push($sources, "http://www.cnn.com");
array_push($sources, "http://www.bbc.com");
$paramArr = array(
    'url' => $sources,
    'entity_type' => ["people_eng","places_eng","companies_eng"]
);
```

* $hodApp: a string to identify a Haven OnDemand API. E.g. "extractentities". Current supported APIs are listed in the HODApps interface.
* $mode [REQ_MODE::SYNC | REQ_MODE::ASYNC]: specifies API call as Asynchronous or Synchronous.
* $callback: the name of a callback function, which the HODClient will call back and pass the response from server. If the $callback is omitted, or is an empty string "", this function will return a response.

*Response:* 
* Response from the server will be returned via the provided $callback function

*Example code:*
## 
    // Call the Entity Extraction API synchronously to find people, places and companies from CNN website.
    $paramArr = array(
        'url' => "http://www.cnn.com",
        'entity_type' => ["people_eng","places_eng","companies_eng"]
    );
    $hodClient->GetRequest($paramArr, HODApps::ENTITY_EXTRACTION, REQ_MODE::SYNC, 'requestCompleted');
    // callback function
    function requestCompleted($response) {
        echo $response;
    }
    // Direct response mode
    $response = GetRequest($paramArr, HODApps::ENTITY_EXTRACTION, REQ_MODE::SYNC);
    echo $response;
----
**Function PostRequest**
 
    PostRequest($paramArr, $hodApp, $mode, $callback)

*Description:* 
* Sends a HTTP POST request to call a Haven OnDemand API.

*Parameters:*
* $paramArr: an array() containing key/value pair parameters to be sent to a Haven OnDemand API, where the keys are the parameters of that Haven OnDemand API. 

>Note: 

>In the case of a parameter type is an array<>, the value must be defined as an array() or [].
>E.g.:
``` 
$sources = array();
array_push($sources, "http://www.cnn.com");
array_push($sources, "http://www.bbc.com");
$paramArr = array(
    'url' => $sources,
    'entity_type' => ["people_eng","places_eng","companies_eng"]
);
```

* $hodApp: a string to identify an IDOL OnDemand API. E.g. "ocrdocument". Current supported apps are listed in the IODApps class.
* $mode [REQ_MODE::SYNC | REQ_MODE::ASYNC]: specifies API call as Asynchronous or Synchronous.
* $callback: the name of a callback function, which the HODClient will call back and pass the response from server. If the $callback is omitted, or is an empty string "", this function will return a response.

*Response:* 
* Response from the server will be returned via the provided $callback function

*Example code:*
## 
    // Call the OCR Document API asynchronously to scan text from an image file.
    $paramArr = array(
        'file' => "full/path/filename.jpg",
        'mode' => "document_photo")
    );
    // Callback mode
    $hodClient->PostRequest($paramArr, HODApps::OCR_DOCUMENT, REQ_MODE::ASYNC, 'requestCompletedWithJobID');
    
    // callback function
    function requestCompletedWithJobID($jobID) {
        // use $jobID with GetJobResult() or GetJobStatus() function;
    }

	// Direct response mode
    $jobID = $hodClient->PostRequest($paramArr, HODApps::OCR_DOCUMENT, REQ_MODE::ASYNC);
    // use $jobID with GetJobResult() or GetJobStatus() function;
----
**Function GetJobResult**

    GetJobResult($jobID, $callback)

*Description:*
* Sends a request to Haven OnDemand to retrieve content identified by a job ID.

*Parameter:*
* $jobID: the job ID returned from an Haven OnDemand API upon an asynchronous call.
* $callback: the name of a callback function, which the HODClient will call back and pass the response from server. If the $callback is omitted, or is an empty string "", this function will return a response.

*Response:* 
* Response from the server will be returned via the provided callback function

*Example code:*
Parse a JSON string contained a jobID and call the function to get content from Haven OnDemand server.

## 

    func asyncRequestCompleted($jobID) {
        $hodClient->GetJobResult($jobID, 'requestCompletedWithContent');     
    }
    function requestCompletedWithContent($response) {
        // parse $response object
        ...
    }

----
**Function GetJobStatus**

    GetJobStatus($jobID, $callback)

*Description:*
* Sends a request to Haven OnDemand to retrieve the status of a job identified by a job ID. If the job is completed, the response will be the result of that job. Otherwise, the response will be null and the current status of the job will be held in the error object.

*Parameter:*
* $jobID: the job ID returned from an Haven OnDemand API upon an asynchronous call.
* $callback: the name of a callback function, which the HODClient will call back and pass the response from server. If the $callback is omitted, or is an empty string "", this function will return a response.

*Response:* 
* Response from the server will be returned via the provided callback function
*Example code:*
Parse a JSON string contained a jobID and call the function to get content from Haven OnDemand server.

## 

    func asyncRequestCompleted($jobID) {
        $hodClient->GetJobStatus($jobID, 'requestCompletedWithContent');  
    }

    function requestCompletedWithContent($response) {
        
        // parse $response object
        ...
    }
----

## Define and implement callback functions

# 
When you call the GetRequest() or PostRequest() with the ASYNC mode, the response in a callback function will be a JSON string containing a jobID.

    func requestCompletedWithJobId($jobID)
    { 
        // use the jobID with GetJobStatus() or GetJobResult()
    }
# 
When you call the GetRequest() or PostRequest() with the SYNC mode or call the GetJobResult(), the response in a callback function will be a JSON string containing the actual result of the service.

    func requestCompletedWithContent($response)
    { 
        // parse the response to get content values
    }

----
## Demo code 1: 

**Call the Entity Extraction API to extract people and places from cnn.com website with a synchronous GET request**

    <?php
    include "hodclient.php";
    function  getPeopleAndPlaces() {
        $hodClient = new HODClient("YOUR-API-KEY");
        $paramArr = array(
            'url' => "http://www.cnn.com",
            'entity_type' => ["people_eng","places_eng"]
        );
        $hodClient->GetRequest($paramArr, HODApps::ENTITY_EXTRACTION, REQ_MODE::SYNC, 'requestCompletedWithContent');
        
    }

    // implement callback function
    function requestCompletedWithContent($response) {
        $jsonStr = stripslashes($response);
        $respObj = json_decode($jsonStr);
       	$people = "";
        $places = "";
        $entities = $respObj->entities;
        for ($i = 0; $i < count($entities); $i++) {
            $entity = $entities[$i];
            if ($entity->type == "people_eng") {
                $people .= $entity->normalized_text . "; ";
                // parse any other interested information about this person ...
            } else if ($entity->type == "places_eng") {
                $places .= $entity->normalized_text . "; ";
		        // parse any other interested information about this place ...
            }
        }
	echo "PEOPLE: " . $people;
	echo "</br>
	echo "PLACES: " . $places;
    }
    getPeopleAndPlaces();
    ?>
----

## Demo code 2:
 
**Call the OCR Document API to scan text from an image with an asynchronous POST request**

    <?php
    include "hodclient.php";
    
    // implement callback function
    function requestCompletedWithJobId($jobID) {
        global $hodClient;
        if ($jobID == null)
        {
            $errors = $hodClient->getLastError();
            $err = $errors[0];
            echo ("Error code: " . $err->error."</br>Error reason: " . $err->reason . "</br>Error detail: " .  $err->detail);
        } else {
            $hodClient->GetJobResult($jobID, 'requestCompletedWithContent');
        }
    }
    // implement callback function
    function requestCompletedWithContent($response) {
        if ($response == null)
        {
            $errors = $hodClient->getLastError();
            $err = $errors[0];
            if ($err->error == ErrorCode::QUEUED) {
                sleep(2);
                $hodClient->GetJobStatus($err->jobID, 'requestCompletedWithContent');
            } else if ($err->error == ErrorCode::IN_PROGRESS) {
                sleep(5);
                $hodClient->GetJobStatus($err->jobID, 'requestCompletedWithContent');
            } else {
                echo ("Error code: " . $err->error."</br>Error reason: " . $err->reason . "</br>Error detail: " .  $err->detail . "JobID: " . $err->jobID);
            }
        }
        else {
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
    $hodClient = new HODClient("YOUR-API-KEY");
    $paramArr = array(
        'url' => "https://www.idolondemand.com/sample-content/images/speccoll.jpg",
        'mode' => "document_photo"
    );
    $hodClient->PostRequest($paramArr, HODApps::OCR_DOCUMENT, REQ_MODE::ASYNC, 'requestCompletedWithJobId');
    
    ?>

----
## License
Licensed under the MIT License.
<?php
include "libs/hodclient.php";

$hodClient = new HODClient("34a54d30-ddaa-4294-8e45-ebe07eefe55e");

$paramArr = array(
    'url' => ["http://www.bbc.com","http://www.cnn.com"],
    "entity_type" => ["people_eng", "places_eng", "companies_eng"],
    "unique_entities" => "true"
);
$response = $hodClient->GetRequest($paramArr, HODApps::ENTITY_EXTRACTION, REQ_MODE::SYNC);

if ($response == null)
{
    $errors = $hodClient->getLastError();
    $err = $errors[0];
    echo ("Error code: " . $err->error."</br>Error reason: " . $err->reason . "</br>Error detail: " .  $err->detail);
} else {
    $people = "";
    $places = "";
    $companies = "";
    $entities = $response->entities;
    for ($i = 0; $i < count($entities); $i++) {
        $entity = $entities[$i];
        if ($entity->type == "people_eng") {
            $people .= $entity->normalized_text . "; ";
            // parse any other interested information about this person ...
        } else if ($entity->type == "places_eng") {
            $places .= $entity->normalized_text . "; ";
            // parse any other interested information about this place ...
        } else if ($entity->type == "companies_eng") {
            $companies .= $entity->normalized_text . "; ";
            // parse any other interested information about this place ...
        }
    }
    echo "PEOPLE: " . $people;
    echo "</br>";
    echo "PLACES: " . $places;
    echo "</br>";
    echo "COMPANIES: " . $companies;
}
?>
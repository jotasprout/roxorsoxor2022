<?php

// array
$array = Array (
    "0" => Array (
        "id" => "MMZ301",
        "name" => "Michael Bruce",
        "designation" => "System Architect"
    ),
    "1" => Array (
        "id" => "MMZ385",
        "name" => "Jennifer Winters",
        "designation" => "Senior Programmer"
    ),
    "2" => Array (
        "id" => "MMZ593",
        "name" => "Donna Fox",
        "designation" => "Office Manager"
    )
);

// encode array to json
$json = json_encode(array('data' => $array));

//write json to file
if (file_put_contents("data.json", $json))
    echo "JSON file created successfully...";
else 
    echo "Oops! Error creating json file...";

// data.json

// {"data":[{"id":"MMZ301","name":"Michael Bruce","designation":"System Architect"},{"id":"MMZ385","name":"Jennifer Winters","designation":"Senior Programmer"},{"id":"MMZ593","name":"Donna Fox","designation":"Office Manager"}]}

?>
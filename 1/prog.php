<?php

$name = $_POST["name"];
$surname = $_POST["surname"];

$data = array(
    "name" => $name,
    "surname" => $surname
);

$jsonString = json_encode($data);

//CURL WORK
$ch = curl_init("https://reqbin.com/echo/post/json");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
curl_close($ch);
$errmsg  = curl_error($ch);

echo "<p>ANSWER: $result</p>";
echo "<p>ERROR: $errmsg</p>";
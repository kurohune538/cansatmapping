<?php

//ローカルのテスト用
$link = mysql_connect('http://ns.photon01.co.jp/~cansat', 'kazuya', 'osqeq5BqLpqycXqV');
if (!$link) {
    die('error'.mysql_error());
}



$fileName = 'cansatmapping';
$filePath = $fileName . 'kml';

$jsonArray = array();

$documentArray = array(
    "id"=>"document",
    "name"=>$fileName,
    "version"=>"1.0",
);

array_push($jsonArray, $documentArray);


$billboard = array(
	"horizontalOrigin" => "CENTER",
	"image" => "http://cansatmapping.com/code/dist/images/favicon.png",
      "scale" => 0.35,
      "show" => "true",
      "verticalOrigin" => "CENTER"
);

$point = [
	130.266907,
  33.284693,
	2200
];

$position = array(
	"cartographicDegrees" => $point,
);

$polyline = array(
	"width" => 1,
	"positions" => $polylinePosition,
	"material" => $polyLineMaterial,
	"positions" => $polylinePosition,
);

$placemarkArray = array(
	"id" => "1",
	"name" => "名前",
	"description" => "説明",
	"billboard" => $billboard,
	"position" => $position,
);

array_push($jsonArray, $placemarkArray);



$json = json_encode($jsonArray,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
var_dump ($json);

file_put_contents('../czml/' . $fileName . '.czml', $json);
?>
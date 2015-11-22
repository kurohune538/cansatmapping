<?php

$fileName = 'weathernews_report_new';
$filePath = $fileName . 'kml';

$file = file_get_contents($Originalfile);
$xml = simplexml_load_string($file);

$id = 1;

$jsonArray = array();

$documentArray = array(
    "id"=>"document",
    "name"=>$fileName,
    "version"=>"1.0",
);

array_push($jsonArray, $documentArray);

foreach ($xml->Document->Folder as $folder) {
	foreach ($folder->Placemark as $placemark) {
	
		$begin = (string)($placemark->TimeSpan->begin);
		$end = (string)($placemark->TimeSpan->end);
		$availability = $begin . '/' . $end;
		
		$iconColorHex = (string)($placemark->Style->IconStyle->color);
		$iconColorRgba = array(
			hexdec(substr($iconColorHex,6,2)),
			hexdec(substr($iconColorHex,4,2)),
			hexdec(substr($iconColorHex,2,2)),
			hexdec(substr($iconColorHex,0,2)),
		);
		$iconColor = array(
			"rgba" => $iconColorRgba,
		);
	
		$placemarkId = (string)($placemark->attributes()->id);
		$billboardName = (string)($placemark->name);
		$iframeContent = (string)($placemark->description);
	
		$iconPath = 'http://typhoon.mapping.jp/icon/weather_icon.png';
		$point = explode(',',$placemark->Point->coordinates);
	
		foreach ($point as &$value){
		  $value = (float)$value;
		}
		unset($value);
	
		$polylinePoint = array(
			$point[0],
			$point[1],
			$point[2],
			$point[0],
			$point[1],
			0,		
		);
	
		$polylinePosition = array(
			"cartographicDegrees" => $polylinePoint,
		);

		$polylineRgba = array(
			255,255,255,32
		);
	
		$polylineColor = array(
			"rgba" => $polylineRgba,
		);

		$polylineSolidColor = array(
			"color" => $polylineColor,
		);
	
		$polyLineMaterial = array(
			"solidColor" => $polylineSolidColor,
		);

		$billboard = array(
			"horizontalOrigin" => "CENTER",
			"image" => $iconPath,
	        "scale" => 0.35,
	        "show" => "true",
	        "verticalOrigin" => "CENTER",
			"color" => $iconColor,
		);
	
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
			"id" => $placemarkId,
			"availability" => $availability,
			"name" => $billboardName,
			"description" => $iframeContent,
			"billboard" => $billboard,
			"position" => $position,
			"polyline" => $polyline,
		);

		array_push($jsonArray, $placemarkArray);
	}
}

$json = json_encode($jsonArray,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
var_dump ($json);

file_put_contents($fileName . '.czml', $json);
?>
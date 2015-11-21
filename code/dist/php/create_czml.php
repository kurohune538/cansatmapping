<?php
header("Content-Type:text/html; charset=UTF-8");
require_once('config.php');

echo "readed!";

//MySQLに接続
try {
    $dbh = new PDO(
        'mysql:host='.HOST_NAME.';dbname='.DB_NAME.';charset=utf8',
        USER_NAME,
        PASSWORD,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        )
    );
} catch (PDOException $e) {
    $error = $e->getMessage();
    echo 'Could not connect: ' .$error;
}

//データを一旦postArrayに格納
$postsArray = array();

$stmt = $dbh->query("SELECT * FROM posts");
while($post = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    $rowArray= array(
        "id"=>$post['id'],
        "lat"=>$post['latitude'],
        "lon"=>$post['longitude'],
        "team_photo"=>$post['team_photo'],
        "team_name"=>$post['team_name'],
        "cansat_photo"=>$post['cansat_photo'],
        "cansat_name"=>$post['cansat_name'],
        "launch_date"=>$post['launch_date'],
        "launch_site"=>$post['launch_site'],
        "mission_overview"=>$post['mission_overview'],
        "youtube"=>$post['youtube_url'],
        "pdf"=>$post['mission_detail_pdf'],
    );
    array_push($postsArray, $rowArray);
    EOF;
}


//czmlを作成
$fileName = 'cansat';
$baseUrl = 'http://cansat.archiving.jp/';

$jsonArray = array();
$documentArray = array(
    "id"=>"document",
    "name"=>$fileName,
    "version"=>"1.0",
);

array_push($jsonArray, $documentArray);

if (!empty($postsArray)) {
    foreach ($postsArray as $post) {
        
        //descriptionの中身をつくりたいけどなんかうまくいかない
        // $description = htmlspecialchars('<div>');
        // $description .= htmlspecialchars('<p>'.$post['cansat_name'].'</p>');
        // $description .= htmlspecialchars('</div>');

        $description = "ディスクリプション";
        
        $billboard = array(
            "horizontalOrigin" => "CENTER",
            "image" => '..//img/small/'. $post['team_photo'],
            "scale" => 0.35,
            "show" => "true",
            "verticalOrigin" => "CENTER"
        );

        $point = [
            $post['lon'],
            $post['lat'],
            2200
        ];

        $position = array(
            "cartographicDegrees" => $point,
        );

        $placemarkArray = array(
            "id" => $post['id'],
            "name" => $post['team_name'],
            "description" => $description,
            "billboard" => $billboard,
            "position" => $position,
        );
        array_push($jsonArray, $placemarkArray);
    }
}

$json = json_encode($jsonArray,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
var_dump ($json);

file_put_contents('../czml/' . $fileName . '.czml', $json);
?>





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


//czml用配列
$czmlArray = array();
$czmlFileName = 'cansat';

//ポリライン用配列
$polylineArray = array();
$polylineFileName = 'polyline';

//czmlの先頭にくるやつ
$documentArray = array(
    "id"=>"document",
    "name"=>$czmlFileName,
    "version"=>"1.0",
);
array_push($czmlArray, $documentArray);

//投稿数だけまわす
if (!empty($postsArray)) {
    foreach ($postsArray as $post) {
        
        //動画がある場合追加
        if (preg_match('/www.youtube.com/', $post['youtube'])) {
            //youtubeの動画IDを取得
            $featur = substr($post['youtube'], -6);
            if ($featur == 'featur') {
                $videoID = substr($post['youtube'], -18, 11);
            }
            else {
                $videoID = substr($post['youtube'], -11);    
            }
            $description = '<iframe src="http://www.youtube.com/embed/'.$videoID.'" frameborder="0" allowfullscreen=""></iframe>';
        }else {
            //動画がない場合は画像
            $description = '<img class="commingsoon" src="images/comingsoon.png" alt="">';
        }
        $description .= '<div class="bottom">';
        $description .= '<img class="cansat_photo" src="assets/img/medium/'.$post['cansat_photo'].'" alt="cansat photo">'; 
        $description .= '<div class="data_box">';
        $description .= '<p class="cansat_name">'.$post['cansat_name'].'</p>';
        $description .= '<p class="mission_overview">'.$post['mission_overview'].'</p>';
        if ($post['pdf']) {
            $description .= '<a class="pdf" href="http://cansat.archiving.jp/assets/pdf/'.$post['pdf'].'" target="_blank">Abstract</a>';
        }
        else {
            
        }
        $description .= '<p class="unisec">University Space Engineering Consortium</p>';
        $description .= '</div>';
        $description .= '</div>';
        
        $billboard = array(
            "horizontalOrigin" => "CENTER",
            "image" => '../assets/img/small/'. $post['team_photo'],
            "scale" => 0.5,
            "show" => "true",
            "verticalOrigin" => "CENTER"
        );

        $point = [
            $post['lon'],
            $post['lat'],
            50000
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

        //ビルボードを追加
        array_push($czmlArray, $placemarkArray);


        // ポリライン作成
        $polylinePosition = array(
            //UNISEC本部に集まる線
            "ghaterLine" => [
                "139.7589766",
                "35.7108592",
                "0.0",
                $post['lon'],
                $post['lat'],
                "0"],
            //アイコン下の線
            "underLine" => [
                $post['lon'],
                $post['lat'],
                "0.0",
                $post['lon'],
                $post['lat'],
                "50000"]
            );
        //ポリラインを追加
        array_push($polylineArray, $polylinePosition);        
    }
}

//czml作成
$json = json_encode($czmlArray,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
var_dump ($json);
file_put_contents('../czml/' . $czmlFileName . '.czml', $json);

//ポリラインのJSON作成
$polylineJSON = json_encode($polylineArray,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
file_put_contents('../czml/polyline.json', $polylineJSON);
// var_dump($polylineJSON)


?>





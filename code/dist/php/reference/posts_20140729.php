<?php

require_once '../application/configs/config.php';
require_once 'Post.php';

$searchkey = array();

$objPost = new Post();
$posts = $objPost->getPosts($searchkey);


$kml[] = '<?xml version="1.0" encoding="UTF-8"?>';
$kml[] = '<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">';
$kml[] = '<Document>';
$kml[] = "\t" . '<name>satellite.kml</name>';

if (!empty($posts)) {
    foreach ($posts as $p) {
        $kml[] = "\t" . '<StyleMap id="balloon' . sprintf('%04d', $p['id']) . '">';
        $kml[] = "\t\t" . '<Pair>';
        $kml[] = "\t\t\t" . '<key>normal</key>';
        $kml[] = "\t\t\t" . '<styleUrl>#style_normal' . sprintf('%04d', $p['id']) . '</styleUrl>';
        $kml[] = "\t\t" . '</Pair>';
        $kml[] = "\t\t" . '<Pair>';
        $kml[] = "\t\t\t" . '<key>highlight</key>';
        $kml[] = "\t\t\t" . '<styleUrl>#style_highlight' . sprintf('%04d', $p['id']) . '</styleUrl>';
        $kml[] = "\t\t" . '</Pair>';
        $kml[] = "\t" . '</StyleMap>';
        $kml[] = "\t" . '<Style id="style_normal' . sprintf('%04d', $p['id']) . '">';
        $kml[] = "\t\t" . '<IconStyle>';
        $kml[] = "\t\t\t" . '<scale>1.5</scale>';
        $kml[] = "\t\t\t" . '<Icon>';
        $kml[] = "\t\t\t\t" . '<href>' . (!empty($p['team_photo']) ? BASE_URL . '/assets/img/medium/' . e($p['team_photo']) : null) . '</href>';
        $kml[] = "\t\t\t" . '</Icon>';
        $kml[] = "\t\t\t" . '<hotSpot x="0.5" y="-0.3" xunits="fraction" yunits="fraction"/>';
        $kml[] = "\t\t" . '</IconStyle>';
        $kml[] = "\t\t" . '<LabelStyle>';
        $kml[] = "\t\t\t" . '<color>88ffffff</color>';
        $kml[] = "\t\t\t" . '<scale>0.5</scale>';
        $kml[] = "\t\t" . '</LabelStyle>';
        $kml[] = "\t\t" . '<BalloonStyle>';
        $kml[] = "\t\t\t" . '<text><![CDATA[';
        $kml[] = "\t\t\t" . '<div class="balloon">';
        $kml[] = "\t\t\t\t" . '<p class="name">$[name]</p>';
        $kml[] = "\t\t\t\t" . '<div class="video">';
        $kml[] = "\t\t\t\t\t" . '$[youtube]';
        $kml[] = "\t\t\t\t" . '</div>';
        $kml[] = "\t\t\t" . '</div>';
        $kml[] = "\t\t\t" . ']]></text>';
        $kml[] = "\t\t\t" . '<textColor>aaaaaaaa</textColor>';
        $kml[] = "\t\t\t" . '<bgColor>00000000</bgColor>';
        $kml[] = "\t\t" . '</BalloonStyle>';
        $kml[] = "\t\t" . '<LineStyle>';
        $kml[] = "\t\t\t" . '<color>b2ffcfd3</color>';
        $kml[] = "\t\t" . '</LineStyle>';
        $kml[] = "\t" . '</Style>';
        $kml[] = "\t" . '<Style id="style_highlight' . sprintf('%04d', $p['id']) . '">';
        $kml[] = "\t\t" . '<IconStyle>';
        $kml[] = "\t\t\t" . '<scale>2</scale>';
        $kml[] = "\t\t\t" . '<Icon>';
        $kml[] = "\t\t\t\t" . '<href>' . (!empty($p['team_photo']) ? BASE_URL . '/assets/img/medium/' . e($p['team_photo']) : null) . '</href>';
        $kml[] = "\t\t\t" . '</Icon>';
        $kml[] = "\t\t\t" . '<hotSpot x="0.5" y="-0.3" xunits="fraction" yunits="fraction"/>';
        $kml[] = "\t\t" . '</IconStyle>';
        $kml[] = "\t\t" . '<LabelStyle>';
        $kml[] = "\t\t\t" . '<color>88ffffff</color>';
        $kml[] = "\t\t\t" . '<scale>0.8</scale>';
        $kml[] = "\t\t" . '</LabelStyle>';
        $kml[] = "\t\t" . '<BalloonStyle>';
        $kml[] = "\t\t\t" . '<text><![CDATA[';
        $kml[] = "\t\t\t" . '<div class="balloon">';
        $kml[] = "\t\t\t\t" . '<p class="name">$[name]</p>';
        $kml[] = "\t\t\t\t" . '<div class="video">';
        $kml[] = "\t\t\t\t\t" . '$[youtube]';
        $kml[] = "\t\t\t\t" . '</div>';
        $kml[] = "\t\t\t" . '</div>';
        $kml[] = "\t\t\t" . ']]></text>';
        $kml[] = "\t\t\t" . '<textColor>aaaaaaaa</textColor>';
        $kml[] = "\t\t\t" . '<bgColor>00000000</bgColor>';
        $kml[] = "\t\t" . '</BalloonStyle>';
        $kml[] = "\t\t" . '<LineStyle>';
        $kml[] = "\t\t\t" . '<color>b2ffcfd3</color>';
        $kml[] = "\t\t" . '</LineStyle>';
        $kml[] = "\t" . '</Style>';
		$kml[] = "\t" . '<Style id="line_highlight">';
        $kml[] = "\t\t" . '<LineStyle>';
        $kml[] = "\t\t\t" . '<color>cce3ff00</color>';
        $kml[] = "\t\t\t" . '<width>1.5</width>';
        $kml[] = "\t\t" . '</LineStyle>';
        $kml[] = "\t" . '</Style>';
        $kml[] = "\t" . '<StyleMap id="lines">';
        $kml[] = "\t\t" . '<Pair>';
        $kml[] = "\t\t\t" . '<key>normal</key>';
        $kml[] = "\t\t\t" . '<styleUrl>#line_normal</styleUrl>';
        $kml[] = "\t\t" . '</Pair>';
        $kml[] = "\t\t" . '<Pair>';
        $kml[] = "\t\t\t" . '<key>highlight</key>';
        $kml[] = "\t\t\t" . '<styleUrl>#line_highlight</styleUrl>';
        $kml[] = "\t\t" . '</Pair>';
        $kml[] = "\t" . '</StyleMap>';
        $kml[] = "\t" . '<Style id="line_normal">';
        $kml[] = "\t\t" . '<LineStyle>';
        $kml[] = "\t\t\t" . '<color>cce3ff00</color>';
        $kml[] = "\t\t\t" . '<width>1.5</width>';
        $kml[] = "\t\t" . '</LineStyle>';
        $kml[] = "\t" . '</Style>';
    }
}

$kml[] = "\t" . '<Folder>';
$kml[] = "\t\t" . '<name>list</name>';

if (!empty($posts)) {
    foreach ($posts as $p) {
        $kml[] = "\t\t" . '<Placemark>';
        $kml[] = "\t\t\t" . '<name>' . e($p['team_name']) . '</name>';
        $kml[] = "\t\t\t" . '<styleUrl>#balloon' . sprintf('%04d', $p['id']) . '</styleUrl>';
        $kml[] = "\t\t\t" . '<ExtendedData>';
        $kml[] = "\t\t\t\t" . '<Data name="youtube">';
        $kml[] = "\t\t\t\t\t" . '<value><![CDATA[<div class="movie">';
        
        if (!preg_match('#^https?://#', $p['youtube_url'])) {
            $youtube = '<img src="http://cansat.mapping.jp/comingsoon_cansat.png" class="comingsoon">';
        } else {
            $url = preg_replace('#https?://www\.youtube\.com/watch\?v=#', '', $p['youtube_url']);
            $url = preg_replace('#https?://youtu\.be/#', '', $url);
            $url = preg_replace('#https?://www\.youtube\.com/v/#', '', $url);
            $youtube = '<object width="560" height="315"><param name="movie" value="//www.youtube.com/v/' . $url . '?hl=ja_JP&amp;version=3&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="//www.youtube.com/v/' . $url . '?hl=ja_JP&amp;version=3&amp;rel=0" type="application/x-shockwave-flash" width="560" height="315" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
        }
        $kml[] = "\t\t\t\t\t\t" . $youtube;
        $kml[] = "\t\t\t\t\t" . '</div>';
        $kml[] = "\t\t\t\t\t" . '<div class="description">';
        $kml[] = "\t\t\t\t\t\t" . '<p class="photograph">' . (!empty($p['cansat_photo']) ? '<img class="photo" src="' . BASE_URL . '/assets/img/medium/' . e($p['cansat_photo']) . '">' : null) . '</p>';
        $kml[] = "\t\t\t\t\t\t" . '<p class="cansat_name">' . e($p['cansat_name']) . '</p>';
        $kml[] = "\t\t\t\t\t\t" . '<p class="cansat_stage">' . e($p['launch_site']) . '</p>';
        $kml[] = "\t\t\t\t\t\t" . '<p class="cansat_mission">' . e($p['mission_overview']) . '</p>';
        $kml[] = "\t\t\t\t\t\t" . '<p class="cansat_pdf">' . (!empty($p['mission_detail_pdf']) ? '<a href="' . BASE_URL . '/assets/pdf/' . e($p['mission_detail_pdf']) . '" target="_blank">Abstract</a>' : null) . '</p>';
		$kml[] = "\t\t\t\t\t\t" . '<p class="description">University Space Engineering Consortium</p>';
		$kml[] = "\t\t\t\t\t\t" . '</div>';
        $kml[] = "\t\t\t\t\t" . ']]></value>';
        $kml[] = "\t\t\t\t" . '</Data>';
        $kml[] = "\t\t\t" . '</ExtendedData>';
        $kml[] = "\t\t\t" . '<Point>';
        $kml[] = "\t\t\t\t" . '<extrude>1</extrude>';
        $kml[] = "\t\t\t\t" . '<altitudeMode>relativeToGround</altitudeMode>';
        $kml[] = "\t\t\t\t" . '<coordinates>' . e($p['longitude'] . ',' . $p['latitude']) . ',30000</coordinates>';
        $kml[] = "\t\t\t" . '</Point>';
        $kml[] = "\t\t\t" . '<TimeStamp>';
        $kml[] = "\t\t\t\t" . '<when>' . e($p['launch_date']) . '</when>';
        $kml[] = "\t\t\t" . '</TimeStamp>';
        $kml[] = "\t\t" . '</Placemark>';
		
		$kml[] = "\t\t" . '<Placemark>';
		$kml[] = "\t\t\t" . '<styleUrl>#lines</styleUrl>';
        $kml[] = "\t\t\t" . '<LineString>';
        $kml[] = "\t\t\t\t" . '<tessellate>1</tessellate>';
        $kml[] = "\t\t\t\t" . '<coordinates>' . e($p['longitude'] . ',' . $p['latitude']) . ',0 139.764235,35.714415,0</coordinates>';
        $kml[] = "\t\t\t\t" . '<TimeStamp>';
        $kml[] = "\t\t\t\t\t" . '<when>' . e($p['launch_date']) . '</when>';
        $kml[] = "\t\t\t\t" . '</TimeStamp>';
        $kml[] = "\t\t\t" . '</LineString>';
        $kml[] = "\t\t" . '</Placemark>';		
    }
}

$kml[] = "\t" . '</Folder>';
$kml[] = '</Document>';
$kml[] = '</kml>';

header('Content-type: application/vnd.google-earth.kml+xml');
header('Content-Disposition: attachment; filename=posts.kml');

$output = join("\n", $kml);
echo $output;

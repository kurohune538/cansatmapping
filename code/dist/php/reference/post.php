<?php

require_once '../application/configs/config.php';
require_once 'Post.php';

$objPost = new Post();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST;
    
    if (($res = isValid($data)) !== true) {
        $error = $res;
        require_once APPLICATION_PATH . '/templates/public/post.phtml';
        exit;
    }
    
    $prefix = date('YmdHis') . '_';
    
    $data['team_photo'] = uploadImage($_FILES['team_photo'], $prefix);
    $data['cansat_photo'] = uploadImage($_FILES['cansat_photo'], $prefix);
    $data['mission_detail_pdf'] = uploadPdf($_FILES['mission_detail_pdf'], $prefix);
    
    $objPost->save($data);
    header('Location:' . BASE_URL . '/post.php?act=complete');
    exit;
}

require_once APPLICATION_PATH . '/templates/public/post.phtml';


function uploadImage($file, $prefix)
{
    if ($file['error'] !== 0) {
        return false;
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid($prefix, true) . '.' . $ext;
    $originalPath = HTTP_PATH . '/assets/img/original/' . $filename;
    $smallPath    = HTTP_PATH . '/assets/img/small/' . $filename;
    $mediumPath   = HTTP_PATH . '/assets/img/medium/' . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $originalPath)) {
        return false;
    }
    
    $objSmallImage = new ResizeImage($originalPath, 96, 96);
    $objSmallImage->save($smallPath);
    chmod($smallPath, 0644);
    
    $objMediumImage = new ResizeImage($originalPath, 250, 250);
    $objMediumImage->save($mediumPath);
    chmod($mediumPath, 0644);
    
    return $filename;
}

function uploadPdf($file, $prefix)
{
    if ($file['error'] !== 0) {
        return false;
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid($prefix, true) . '.' . $ext;
    $filepath = HTTP_PATH . '/assets/pdf/' . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return false;
    }
    
    return $filename;
}

function isValid($data)
{
    $return = array();
    
    if (empty($data['latitude']) || empty($data['longitude'])) {
        $return['latlng'] = 'Please enter your latitude and longitude.';
    }
    
    if (empty($data['team_name'])) {
        $return['team_name'] = 'Please enter a team name.';
    }
    
    if (empty($data['cansat_name'])) {
        $return['cansat_name'] = 'Please enter the name of the CanSat.';
    }
    
    if (empty($data['launch_date'])) {
        $return['launch_date'] = 'Please enter the date of launch.';
    }
    
    if (empty($data['launch_site'])) {
        $return['launch_site'] = 'Please enter the Launch Site.';
    }
    
    if (empty($data['mission_overview'])) {
        $return['mission_overview'] = 'Please enter the Mission Overview of CanSat.';
    }
    
    if (empty($data['youtube_url'])) {
        $return['youtube_url'] = 'Please enter the URL of the Youtube.';
    }
    
    if (empty($return)) {
        return true;
    }
    
    return $return;
}
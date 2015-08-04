<?php

define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));
define('HTTP_PATH', realpath(APPLICATION_PATH . '/../') . '/public_html');
define('BASE_URL', 'http://ns.photon01.co.jp/~cansat');
define('ADMIN_ID', 'admin');
define('ADMIN_PASSWORD', 'a805316451df5d96f7214085884ba08019484a511f048b158df1249e6e99991a');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../') . '/library',
    APPLICATION_PATH . '/models',
    get_include_path()
)));

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/db.ini');

$db = Zend_Db::factory($config->db);
Zend_Registry::set('db', $db);


function e($value) {
    $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
    return preg_replace('/&(?!amp;)/', '&amp;', $value);
}

function adminAuth() {
    require_once 'Zend/Auth.php';
    require_once 'Zend/Auth/Storage/Session.php';
    $auth = Zend_Auth::getInstance();
    $auth->setStorage(new Zend_Auth_Storage_Session('Admin_Auth'));
    if ($auth->hasIdentity()) {
        return true;
    }

    header('Location: ./login.php');
    exit;
}

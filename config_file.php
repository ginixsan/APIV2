<?php
require 'basedatos.php';
define('PRODUCTION', false);
define('HOME',true);


if(PRODUCTION){
	//aquest sera el de produccio
    $public = file_get_contents('parking.pub');
    $private = file_get_contents('../../../parking.pem');
    DB1::$user = 'payklever_prod';
    DB1::$password = 'Barc3l0na1973_'; 
    DB1::$dbName = 'payklever_main';

} else
{
    if(HOME)
    {
		//aquest sera el de casa teva
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        $public = file_get_contents('parking.pub');
        $private = file_get_contents('../../../parking.pem');
        DB1::$user = 'root';
        DB1::$password = 'root';
        DB1::$dbName = 'payklever_main';
    }
    else
    {
		//aquest sera el de kleverdev
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $public = file_get_contents('parking.pub');
        $private = file_get_contents('../../../../parking.pem');
        DB1::$user = 'test_payklever';
        DB1::$password = 'Barc3l0na1973_';
        DB1::$dbName = 'payklever_test';
    }

   
}
?>
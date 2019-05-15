<?php
// error_reporting(0);

require_once './class/Base.php';
$oBase = new Base();
$oBase->Init();

require './class/Home.php';
$oBase->AddRoute([
	'/'					=> 'Home@Index',
    '/first'       		=> 'Home@firstAction',
    '/first/:any'  		=> 'Home@secondAction',
    '/list'				=> 'Home@ListEntries',
    '/list_add'			=> 'Home@ListAdd',
    '/list_edit/:any'	=> 'Home@ListAdd',
    '/list_delete/:any'	=> 'Home@ListDelete',

    '/list_mark/:any'	=> 'Home@ListMark',
    '/list_unmark/:any'	=> 'Home@ListUnmark',
]);

$oBase->Process();


// $tmp_server_path=explode(":",dirname(__FILE__));
// count($tmp_server_path)==1 ? $_SERVER_PATH=$tmp_server_path[0] : $_SERVER_PATH=str_replace("\\","/",$tmp_server_path[1]);
// define(SERVER_PATH,$_SERVER_PATH);



// $oDb->AutoExecute('test',array(
// 	'name'=>'cccc',
// ));


// // for autoload with operator new
// function SystemAutoload($sClass) {
//         if (is_file(SERVER_PATH.'/class/'.$sClass.'.php')) require_once(SERVER_PATH.'/class/'.$sClass.'.php');
// }
// spl_autoload_register('SystemAutoload');

?>
<?php

session_start();
error_reporting(0);

require_once './class/Base.php';
$oBase = new Base();
$oBase->Init();

// Base::$oDb->debug=true;

require './class/Home.php';
require './class/List.php';
require './class/User.php';
$oBase->AddRoute([
	'/'					=> 'Home@Index',
  
    '/list'				=> 'ListTodo@ListEntries',
    '/list_add'			=> 'ListTodo@ListAdd',
    '/list_add_sub/:any'=> 'ListTodo@ListAddSub',
    '/list_edit/:any'	=> 'ListTodo@ListAdd',
    '/list_delete/:any'	=> 'ListTodo@ListDelete',

    '/list_mark/:any'	=> 'ListTodo@ListMark',
    '/list_unmark/:any'	=> 'ListTodo@ListUnmark',

    '/user_registration'=> 'User@UserRegistration',
    '/user_login'		=> 'User@UserLogin',
    '/user_logout'		=> 'User@UserLogOut',
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
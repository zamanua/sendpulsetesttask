<?php
error_reporting(0);

session_start();

require_once './class/Base.php';
$oBase = new Base();
$oBase->Init();

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
?>
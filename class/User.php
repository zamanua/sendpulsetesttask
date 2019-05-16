<?php

// namespace class;

class User
{
    public static function Index() {
        Base::$sContent.="user index function";
    }


    public static function UserRegistration() {
        if(isset($_REQUEST['is_post'])) {
            $aUser=Base::CreateUser($_REQUEST['data']['login'],$_REQUEST['data']['password'],$_REQUEST['data']['name']);
            
            if($aUser) Base::Redirect('/list');
        }

        Base::$sContent.=Base::$oTpl->fetch('user/user_registration.tpl');
    }

    public static function UserLogin() {
        if($_REQUEST['is_post']) {
            $aUser=Base::Login($_REQUEST['login'], $_REQUEST['password']);
        }
        Base::Redirect("/");
    }

    public static function UserLogOut() {
        Base::Logout();
        Base::Redirect("/");
    }
}

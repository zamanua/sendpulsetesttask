<?php

// namespace class;

class Base
{
    static $oDb;
    static $oTpl;
    static $oRouter;
    static $sContent;
    static $aPage;

    public function __construct()
    {

    }

    public static function Init() {

        include('./vendor/adodb/adodb-php/adodb.inc.php');
        self::$oDb = &ADONewConnection('mysqlt', 'transaction : pear');
        self::$oDb->Connect('127.0.0.1:3306','root','123456','test');
        self::$oDb->_Execute("/*!40101 SET NAMES 'utf8' */");
        self::$oDb->SetFetchMode(ADODB_FETCH_ASSOC);
        date_default_timezone_set('Europe/Kiev');
        self::$oDb->_Execute("SET `time_zone`='".date('P')."'");


        require './vendor/smarty/smarty/libs/Smarty.class.php';
        self::$oTpl = new Smarty;
        //self::$oTpl->force_compile = true;
        self::$oTpl->debugging = false;
        self::$oTpl->template_dir = './template/';
        self::$oTpl->compile_dir = './template/templates_c/';



        require './vendor/bit55/litero/src/Bit55/Litero/Router.php';
        self::$oRouter = Bit55\Litero\Router::fromGlobals();
    }

    public static function AddRoute($aRoute) {
        self::$oRouter->add($aRoute);
    }

    public static function Redirect($sUrl) {
        Header ( "HTTP/1.1 301 Moved Permanently" );
        header ( 'Location: ' . $sUrl );
        die ();
    }
    
    public static function Process() {
        
        // Start route processing
        if (self::$oRouter->isFound()) {
            self::$oRouter->executeHandler(
                self::$oRouter->getRequestHandler(),
                self::$oRouter->getParams()
            );
        } 
        else {
            // Simple "Not found" handler
            self::$oRouter->executeHandler(function () {
                http_response_code(404);
                echo '404 Not found';
            });
        }
        
        $_REQUEST['action']=substr($_SERVER['REQUEST_URI'], 0, strpos(substr($_SERVER['REQUEST_URI'], 1), '/'));
        if(!$_REQUEST['action']){
            $_REQUEST['action']=$_SERVER['REQUEST_URI'];
        }

        self::$oTpl->assign('aPage',self::$aPage);
        self::$oTpl->assign('sContent',self::$sContent);
        self::$oTpl->display('index.tpl');

    }
    
    public static function Logout()
    {
        setcookie("user_auth_signature", "",time()+60*60*24*$this->iRememberDays,'/');
        $_COOKIE['user_auth_signature']="";
        setcookie("user_auth_session", "",time()+60*60*24*$this->iRememberDays,'/');
        $_COOKIE['user_auth_session']="";
        $_SESSION['user']="";
        $_SESSION['user']=array();
        if ($_SESSION['user']['login']) {
            Base::$db->Execute("update user set cookie='' where login='".$_SESSION['user']['login']."'");
        }
    }
    
    public static function IsAuth()
    {
        if (($_SESSION['user'] && $_SESSION['user']['isUser']) || Auth::IsValidCookie($_COOKIE['user_auth_signature'])) {
            Auth::$aUser=Auth::GetUserProfile($_SESSION['user']['id'],$_SESSION['user']['type_']);
            Auth::$sWhere=" and id_user='".Auth::$aUser[id]."'";
            return true;
        }
        return false;
    }
    
    function IsValidCookie($sCookie)
    {
        if (Auth::$iRememberDays<=1) Auth::$iRememberDays=90;
    
        if (empty($sCookie)) return false;
    
        $sQuery="select * from user where cookie='$sCookie' and visible='1'";
        $aRow=Db::GetRow($sQuery);
    
        if($sCookie!==md5(Base::$sProjectName.$aRow['login'].$aRow['password'].$aRow['id']))
            //if($sCookie!==md5(Base::$sProjectName.$aRow['login'].$aRow['id']))
                return false;
    
            if ($aRow) {
                Auth::UpdateLastVisit($aRow);
                $aUser=Auth::GetUserProfile($aRow['id'],$aRow['type_']);
    
                //			$oForum = new Forum();
                //			$oForum->LoginForum($aUser);
            }
    
            if ($aUser['id']) {
                Auth::RefreshSession($aUser);
                Auth::RefreshCookie($aUser['login'],$aUser['password'],$aUser['id']);
                return true;
            }
            return false;
    }
    
    function RefreshSession($aUser)
    {
        $_SESSION['user']['isUser'.Base::$sProjectName]=true;
        $_SESSION['user']['isGuest'.Base::$sProjectName]=false;
        foreach ($aUser as $key => $value) $_SESSION['user'][$key]=$value;
        setcookie("user_auth_session", "1",time()+60*60*24*Auth::$iRememberDays);
    }
    //---------------------------------------------------------------------------------
    function RefreshCookie($sLogin,$sPassword,$iIdCustomer)
    {
        if (Auth::$iRememberDays<=10) Auth::$iRememberDays=90;
    
        $sNewCookieValue=md5(Base::$sProjectName.$sLogin.$sPassword.$iIdCustomer);
        //$sNewCookieValue=md5(Base::$sProjectName.$sLogin.$iIdCustomer);
        setcookie("user_auth_signature", $sNewCookieValue,time()+60*60*24*Auth::$iRememberDays,'/');
        $_COOKIE[user_auth_signature] = $sNewCookieValue;
        return $sNewCookieValue;
    }
    
}

<?php

// namespace class;

class Base
{
    static $oDb;
    static $oTpl;
    static $oRouter;
    static $sContent;
    static $aPage;

    static $iRememberDays = 30;
    static $aUser;
    static $sWhere;

    public function __construct()
    {

    }

    public static function Init() {

        include('./vendor/adodb/adodb-php/adodb.inc.php');
        self::$oDb = &ADONewConnection('mysqlt', 'transaction : pear');
        self::$oDb->Connect('127.0.0.1:3306','root','','test');
        self::$oDb->_Execute("/*!40101 SET NAMES 'utf8' */");
        self::$oDb->SetFetchMode(ADODB_FETCH_ASSOC);
        date_default_timezone_set('Europe/Kiev');
        self::$oDb->_Execute("SET `time_zone`='".date('P')."'");

        // self::$oDb->debug=true;


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
        setcookie("user_Base_signature", "",time()+60*60*24*$this->iRememberDays,'/');
        $_COOKIE['user_Base_signature']="";
        setcookie("user_Base_session", "",time()+60*60*24*$this->iRememberDays,'/');
        $_COOKIE['user_Base_session']="";
        $_SESSION['user']="";
        $_SESSION['user']=array();
        if ($_SESSION['user']['login']) {
            Base::$oDb->Execute("update user set cookie='' where login='".$_SESSION['user']['login']."'");
        }
    }
    
    public static function IsAuth()
    {
        if (($_SESSION['user'] && $_SESSION['user']['isUser']) || Base::IsValidCookie($_COOKIE['user_Base_signature'])) {
            Base::$aUser=Base::GetUserProfile($_SESSION['user']['id']);
            Base::$sWhere=" and id_user='".Base::$aUser[id]."'";
            return true;
        }
        return false;
    }
    
    public static function IsValidCookie($sCookie)
    {
        if (Base::$iRememberDays<=1) Base::$iRememberDays=90;
    
        if (empty($sCookie)) return false;
    
        $sQuery="select * from user where cookie='$sCookie' and visible='1'";
        $aRow=Base::$oDb->GetRow($sQuery);
    
        if($sCookie!==md5($aRow['login'].$aRow['password'].$aRow['id']))
                return false;
    
            if ($aRow) {
                $aUser=Base::GetUserProfile($aRow['id']);
            }
    
            if ($aUser['id']) {
                Base::RefreshSession($aUser);
                Base::RefreshCookie($aUser['login'],$aUser['password'],$aUser['id']);
                return true;
            }
            return false;
    }
    
    public static function RefreshSession($aUser)
    {
        $_SESSION['user']['isUser'.Base::$sProjectName]=true;
        $_SESSION['user']['isGuest'.Base::$sProjectName]=false;
        foreach ($aUser as $key => $value) $_SESSION['user'][$key]=$value;
        setcookie("user_Base_session", "1",time()+60*60*24*Base::$iRememberDays);
    }
    //---------------------------------------------------------------------------------
    public static function RefreshCookie($sLogin,$sPassword,$iIdCustomer)
    {
        if (Base::$iRememberDays<=10) Base::$iRememberDays=90;
    
        $sNewCookieValue=md5($sLogin.$sPassword.$iIdCustomer);
        setcookie("user_Base_signature", $sNewCookieValue,time()+60*60*24*Base::$iRememberDays,'/');
        $_COOKIE[user_Base_signature] = $sNewCookieValue;
        return $sNewCookieValue;
    }

    public static function GetUserProfile($iId) {
        return Base::$oDb->GetRow("select * from user where id='".$iId."' ");
    }

    public static function Login($sLogin,$sPassword)
    {
        $aUser=Base::IsUser($sLogin,$sPassword,$bIgnoreVisible,$bSalt);
       
        if (!$aUser['id']) {
            $this->Redirect("/?action=user_login&error_login=1");
        }
        if ($aUser['id'])
        {
            Base::RefreshSession($aUser);
            if (Base::$aRequest['remember_me']) {
                $sCookie=Base::RefreshCookie($sLogin,$sPassword,$_SESSION[user][id]);
                $sQuery="update user set cookie='$sCookie' where login='$sLogin'";
                Base::$db->Execute($sQuery);
            }
            else {
                if (!Base::$bIgnoreCookie) {
                    setcookie("user_auth_signature", "",time()+60*60*24*Base::$iRememberDays);
                    $_COOKIE[user_auth_signature]="";
                    $sQuery="update user set cookie='' where login='$sLogin'";
                    Base::$db->Execute($sQuery);
                }
            }
        }
        if (!$_SESSION['user']['isUser']) {
            $this->Redirect("/?action=user_login&error_login=1");
        }
        return $aUser;
    }

    public static function AutoCreateUser()
    {
        if (!$sLogin) $sLogin=Auth::GenerateLogin();
        $bCheckedLogin=false;
        //if (!Db::GetOne("select count(*) from user where login='$sLogin'")) $bCheckedLogin=true;
        if (Auth::CheckLogin($sLogin)) $bCheckedLogin=true;
        if (!$bCheckedLogin) for ($i=0;$i<=100;$i++) {
            $sLogin=Auth::GenerateLogin();
            if (Auth::CheckLogin($sLogin)) {
                $bCheckedLogin=true;
                break;
            }
        }
        if ($bCheckedLogin) {
            $oUser= new User();
            Base::$aRequest['login']=$sLogin;
            Base::$aRequest['password']=Auth::GeneratePassword();
            if (Base::$aRequest['mobile']) {
                Base::$aRequest['phone']=Base::$aRequest['operator'].Base::$aRequest['mobile'];
                Base::$aRequest['data']['phone']=Base::$aRequest['operator'].Base::$aRequest['mobile'];
            }
            $oUser->DoNewAccount(true);
            return Db::GetRow(Base::GetSql('Customer',array('login'=>$sLogin)));
        }
        return false;
    }
    
}

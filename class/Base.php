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
        self::$oDb->Connect('127.0.0.1:3306','root','','test');
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

        self::$oTpl->assign('aPage',self::$aPage);
        self::$oTpl->assign('sContent',self::$sContent);
        self::$oTpl->display('index.tpl');

    }
    
}

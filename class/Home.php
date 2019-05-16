<?php

// namespace class;

class Home
{
    public static function Index() {


        Base::$oTpl->assign("Name", "Fred Irving Johnathan Bradley Peppergill", true);

        Base::$sContent.=Base::$oTpl->fetch('home/index.tpl');
    }
    /**
     * First Action
     */
    public static function firstAction()
    {
        echo 'Hello aaaa from '.__METHOD__.'!';
        //echo '<br>Request URI: ';
    }
    
    /**
     * Second Action
     */
    public static function secondAction($var)
    {
        echo 'Hello from '.__METHOD__.'( "'.$var.'" )!';
    }

    public static function ListEntries() {
        Base::$aPage['title']='list todo';

        $aData = Base::$oDb->GetAll("select * from test order by post_date asc");


        Base::$oTpl->assign('aDataForTable',$aData);
        Base::$sContent.=Base::$oTpl->fetch('home/list.tpl');
    }

    public static function ListAdd($iId=0) {
        if(isset($_REQUEST['is_post'])) {
            
            //check current date
            $iDateInputed=strtotime($_REQUEST['data']['post_date']);
            $iDateNow=time();
            if($iDateInputed<$iDateNow) {
                $_REQUEST['data']['post_date']=date("Y-m-d H:i:s",$iDateNow);
            }
            

            if($_REQUEST['id']) {
                Base::$oDb->AutoExecute('test',$_REQUEST['data'],'UPDATE', " id='".$_REQUEST['id']."' ");
            } else {
                Base::$oDb->AutoExecute('test',$_REQUEST['data']);
            }
            
            Base::Redirect('/list');
        }

        if ($iId) {
            Base::$aPage['title']='edit todo';
            $aExist=Base::$oDb->GetRow("select * from test where id='".$iId."' ");
            $aExist['post_date']=str_replace(" ", 'T', $aExist['post_date']);
            Base::$oTpl->assign('aExist',$aExist);
        } else {
            Base::$aPage['title']='add new todo';
            Base::$oTpl->assign('aExist',array(
                'id'=>0,
                'name'=>'',
                'description'=>'',
                'post_date'=>''
            ));
        }

        Base::$sContent.=Base::$oTpl->fetch('home/list_add.tpl');
    }

    public static function ListDelete($iId) {
        Base::$oDb->Execute("delete from test where id='".$iId."' ");
        Base::Redirect('/list');
    }

    public static function ListMark($iId) {
        Base::$oDb->Execute("update test 
            set checked='1'
            where id='".$iId."' ");
        Base::Redirect('/list');
    }

    public static function ListUnmark($iId) {
        Base::$oDb->Execute("update test 
            set checked='0'
            where id='".$iId."' ");
        Base::Redirect('/list');
    }

    public static function UserRegistration() {
        print_r($_REQUEST);
        
        if(isset($_REQUEST['is_post'])) {
            $aUser=Base::CreateUser($_REQUEST['data']['login'],$_REQUEST['data']['password'],$_REQUEST['data']['name']);
            
            //if($aUser) Base::Redirect('/list');
        }

        Base::$sContent.=Base::$oTpl->fetch('home/user_registration.tpl');
    }

    public static function UserLogin() {
        print_r($_REQUEST);

        if($_REQUEST['is_post']) {
            $aUser=Base::Login($_REQUEST['login'], $_REQUEST['password']);

            print_r($aUser);
        }
       // Base::Redirect("/");
    }
}

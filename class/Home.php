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

        $aData = Base::$oDb->GetAll("select * from test ");


        Base::$oTpl->assign('aDataForTable',$aData);
        Base::$sContent.=Base::$oTpl->fetch('home/list.tpl');
    }

    public static function ListAdd($iId=0) {
        if(isset($_REQUEST['is_post'])) {

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
            Base::$oTpl->assign('aExist',$aExist);
        } else {
            Base::$aPage['title']='add new todo';
            Base::$oTpl->assign('aExist',array(
                'id'=>0,
                'name'=>'',
                'description'=>''
            ));
        }

        Base::$sContent.=Base::$oTpl->fetch('home/list_add.tpl');
    }

    public static function ListDelete($iId) {
        Base::$oDb->Execute("delete from test where id='".$iId."' ");
        Base::Redirect('/list');
    }
}

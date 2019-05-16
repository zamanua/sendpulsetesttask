<?php

// namespace class;

class ListTodo
{
    public static function Index() {
        Base::$sContent.="list index function";
    }

    public static function ListEntries() {
        Base::NeedAuth();

        Base::$aPage['title']='list todo';

        $aData = Base::$oDb->GetAll("select * from test 
            where id_user='".Base::$aUser['id']."' 
            order by id_parent asc, post_date asc
        ");

        if($aData) {
            $aDataForTable=array();
            foreach ($aData as $key => $aValue) {
                if($aValue['id_parent']==0) {
                    $aDataForTable[$aValue['id']]=$aValue;
                } else {
                    $aDataForTable[$aValue['id_parent']]['childs'][]=$aValue;
                }
            }
        }

        Base::$oTpl->assign('aDataForTable',$aDataForTable);
        Base::$sContent.=Base::$oTpl->fetch('list/list.tpl');
    }

    public static function ListAdd($iId=0) {
        Base::NeedAuth();

        if(isset($_REQUEST['is_post'])) {
            $_REQUEST['data']['id_user']=Base::$aUser['id'];
            
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
            $aExist=Base::$oDb->GetRow("select * from test where id='".$iId."' and id_user='".Base::$aUser['id']."' ");
            if($aExist) {
                $aExist['post_date']=str_replace(" ", 'T', $aExist['post_date']);
                Base::$aPage['title']='edit todo';
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
        } else {
            Base::$aPage['title']='add new todo';
            Base::$oTpl->assign('aExist',array(
                'id'=>0,
                'name'=>'',
                'description'=>'',
                'post_date'=>''
            ));
        }

        Base::$sContent.=Base::$oTpl->fetch('list/list_add.tpl');
    }

    public static function ListAddSub($iIdParent=0) {
        Base::NeedAuth();

        Base::$oTpl->assign('iIdParent',$iIdParent);
        ListTodo::ListAdd(0);
    }

    public static function ListDelete($iId) {
        Base::NeedAuth();

        Base::$oDb->Execute("delete from test where id='".$iId."' and id_user='".Base::$aUser['id']."' ");
        Base::Redirect('/list');
    }

    public static function ListMark($iId) {
        Base::NeedAuth();

        $aCurrentLine=Base::$oDb->GetRow("select * from test where id='".$iId."' and id_user='".Base::$aUser['id']."' ");
        if($aCurrentLine) {
            Base::$oDb->Execute("update test 
                set checked='1'
                where id='".$iId."' and id_user='".Base::$aUser['id']."' ");

            if($aCurrentLine['id_parent']==0) {
                Base::$oDb->Execute("update test 
                    set checked='1'
                    where id_parent='".$iId."' and id_user='".Base::$aUser['id']."' ");
            } else {
                $iParentCount=Base::$oDb->GetOne("select count(*) from test where id_parent='".$aCurrentLine['id_parent']."' ");
                $iCheckedCount=Base::$oDb->GetOne("select count(*) from test where id_parent='".$aCurrentLine['id_parent']."' and checked='1' ");
                if($iParentCount==$iCheckedCount) {
                    Base::$oDb->Execute("update test 
                    set checked='1'
                    where id='".$aCurrentLine['id_parent']."' and id_user='".Base::$aUser['id']."' ");
                }
            }
        }

        Base::Redirect('/list');
    }

    public static function ListUnmark($iId) {
        Base::NeedAuth();

        $aCurrentLine=Base::$oDb->GetRow("select * from test where id='".$iId."' and id_user='".Base::$aUser['id']."' ");
        if($aCurrentLine) {
            Base::$oDb->Execute("update test 
                set checked='0'
                where id='".$iId."' and id_user='".Base::$aUser['id']."' ");

            if($aCurrentLine['id_parent']==0) {
                Base::$oDb->Execute("update test 
                    set checked='0'
                    where id_parent='".$iId."' and id_user='".Base::$aUser['id']."' ");
            } else {
                Base::$oDb->Execute("update test 
                    set checked='0'
                    where id='".$aCurrentLine['id_parent']."' and id_user='".Base::$aUser['id']."' ");
            }
        }
        
        Base::Redirect('/list');
    }
}

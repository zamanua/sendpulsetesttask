<?php

// namespace class;

class Home
{
    public static function Index() {
        if(Base::IsAuth()) {
            Base::Redirect("/list");
            return;
        }

        Base::$sContent.=Base::$oTpl->fetch('home/index.tpl');
    }
}
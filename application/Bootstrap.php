<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initPlugins()
    {

        $layoutModulePlugin = new App_Plugins_Module();
        $layoutModulePlugin->registerModuleLayout(
                'default',
                realpath(APPLICATION_PATH . "/modules/default/layouts"),
                'layout'
        );
        $layoutModulePlugin->registerModuleLayout(
                'admin',
                realpath(APPLICATION_PATH . "/modules/admin/layouts"),
                'layout'
        );
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin($layoutModulePlugin);
    }

    protected function _initForm()
    {
        Zend_Registry::set('config_forms', new Zend_Config_Ini(APPLICATION_PATH . "/configs/forms.ini"));
    }

//    protected function _initDb()
//    {
////$this->bootstrap('db');
//        $dbAdapter = $this->getResource('db');
////Debug::dump("1");
//
//    }

}


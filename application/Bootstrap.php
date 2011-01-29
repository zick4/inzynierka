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

}


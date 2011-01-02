<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle('My First Zend Framework Application');

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                'ViewRenderer'
        );
        $viewRenderer->setView($view);

        // Return it, so that it can be stored by the bootstrap
        return $view;
    }

    protected function _initPlugins()
    {
       
        $layoutModulePlugin = new App_Plugins_Module();
        $layoutModulePlugin->registerModuleLayout(
            'frontend',
             realpath(APPLICATION_PATH . "/modules/frontend/layouts"),
            'layout'
        );


        $front = Zend_Controller_Front::getInstance();
        $front->setDefaultModule('frontend');
        $front->registerPlugin($layoutModulePlugin);
//        var_dump($front->getModuleDirectory('frontend'));
//        exit();
    }

}


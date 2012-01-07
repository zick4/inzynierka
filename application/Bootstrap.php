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

    protected function _initView()
    {
        $this->bootstrap("layout");
        $layout = $this->getResource("layout");
        $view = $layout->getView();
        $view->headScript()->appendFile("http://html5shiv.googlecode.com/svn/trunk/html5.js", 'text/javascript', array('conditional' => 'lt IE 9'));
        $view->headScript()->appendFile("/jquery/js/jquery-1.6.2.min.js", 'text/javascript');
        $view->headScript()->appendFile("/jquery/js/jquery-ui-1.8.14.custom.min.js", 'text/javascript');
        $view->headScript()->appendFile("/jquery/js/jquery.notify.js", 'text/javascript');
        $view->headScript()->appendFile("/jquery/js/jquery.lightbox-0.5.js", 'text/javascript');
        $view->headLink()->headLink(array('rel' => 'favicon', 'href' => '/favicon.ico'));

        $view->headLink()->appendStylesheet($view->baseUrl("/css/reset.css"), "screen");
        $view->headLink()->appendStylesheet($view->baseUrl("/jquery/css/ui.notify.css"), "screen");
        $view->headLink()->appendStylesheet($view->baseUrl("/jquery/css/jquery.lightbox-0.5.css"), "screen");
        
        $view->headMeta()->appendHttpEquiv('Content-Type','text/html; charset=UTF-8');
        $view->headMeta()->appendHttpEquiv('Content-Language','pl-PL');
    }

    protected function _initFlashMessenger()
    {
        /** @var $flashMessenger Zend_Controller_Action_Helper_FlashMessenger */
//        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
//        if ($flashMessenger->hasMessages()) {
//            $view = $this->getResource('view');
//            $view->messages = $flashMessenger->getMessages();
//        }
    }

    protected function _initForm()
    {
        Zend_Registry::set('config_forms', new Zend_Config_Ini(APPLICATION_PATH . "/configs/forms.ini"));
    }

    protected function _initDoctrine()
    {
        $this->getApplication()
             ->getAutoloader()
             ->pushAutoloader(array('Doctrine_Core', 'autoload'))
        ;
//        spl_autoload_register(array('Doctrine', 'modelsAutoload'));
        
        $config = $this->getOption('doctrine');
        $manager = Doctrine_Manager::getInstance();
//        Doctrine::loadModels($config['models_path']);
        foreach ($config as $key => $value)
        {
            if ($key != 'connection')
                $manager->setAttribute($key, $value);
        }

        if (isset($config['cache']) && $config['cache'] == true)
        {
            $cacheDriver = new Doctrine_Cache_Apc();
            $manager->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $cacheDriver);
        }

        $oConnection = $manager->openConnection($config['connection'], 'doctrine');
        $oConnection->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);
        $oConnection->setCharset('utf8');
        Zend_Registry::set('connection', $oConnection);
    }

}


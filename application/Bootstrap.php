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
        $view->headMeta()->appendHttpEquiv('Content-Type','text/html; charset=UTF-8');
        $view->headMeta()->appendHttpEquiv('Content-Language','pl-PL');
    }

    protected function _initForm()
    {
        Zend_Registry::set('config_forms', new Zend_Config_Ini(APPLICATION_PATH . "/configs/forms.ini"));
    }

    protected function _initDoctrine()
    {
        $this->getApplication()->
                getAutoloader()->
                pushAutoloader(array('Doctrine_Core', 'autoload'));
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

        $connection = $manager->openConnection($config['connection'], 'doctrine');
        $connection->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);
        $connection->setCharset('utf8');

        return $connection;
    }

}


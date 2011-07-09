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

        $config = $this->getOption('doctrine');
        $manager = Doctrine_Manager::getInstance();
        foreach ($config as $key => $value)
        {
            if ($key != 'connection')
                $manager->setAttribute($key, $value);
        }

//        $manager->setAttribute(Doctrine_Core::ATTR_MODEL_CLASS_PREFIX, 'Model_');
//        $manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_PEAR);
//        $manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
//        $manager->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);
//        $manager->setAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS, true);
//        $manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
//        $manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, true);

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


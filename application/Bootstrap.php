<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initPlugins()
    {

        $layoutModulePlugin = new App_Plugins_Module();
        $layoutModulePlugin->registerModuleLayout(
                'default', realpath(APPLICATION_PATH . "/modules/default/layouts"), 'layout'
        );
        $layoutModulePlugin->registerModuleLayout(
                'admin', realpath(APPLICATION_PATH . "/modules/admin/layouts"), 'layout'
        );
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin($layoutModulePlugin);
    }

    protected function _initAcl()
    {
        $oAcl = new Zend_Acl();

        $roleAll = new Zend_Acl_Role('all');
        $oAcl->addRole($roleAll);
        $oAcl->addRole(new Zend_Acl_Role('member'), $roleAll);
        $oAcl->addRole(new Zend_Acl_Role('guest'), $roleAll);
        $oAcl->addRole(new Zend_Acl_Role('admin'));

        $oAcl->addResource('user');
        $oAcl->addResource('album');

        $oAcl->allow('all', array('album'), array('list', 'show'));
        $oAcl->allow('member', array('album'), array('save', 'delete', 'add', 'link in menu'));
        $oAcl->allow('member', array('user'), array('logout', 'account'));
        $oAcl->allow('guest', array('user'), array('login'));
        $oAcl->deny('admin', array('user'), array('logout'));

        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($oAcl);
    }

    protected function _initLocale()
    {
        // define locale
        $locale = new Zend_Locale('pl');

        // register it so that it can be used all over the website
        Zend_Registry::set('Zend_Locale', $locale);
    }

    protected function _initTranslate()
    {
        // Get Locale
        $locale = Zend_Registry::get('Zend_Locale');

        // Set up and load the translations (there are my custom translations for my app)
        $translate = new Zend_Translate(
                        array(
                            'adapter' => 'array',
                            'content' => APPLICATION_PATH . '/languages/' . $locale . '.php',
                            'locale' => $locale)
        );

        Zend_Form::setDefaultTranslator($translate);

        // Save it for later
        Zend_Registry::set('Zend_Translate', $translate);
    }

    protected function _initView()
    {
        $this->bootstrap("layout");
        $layout = $this->getResource("layout");
        $view = $layout->getView();

        $config = require APPLICATION_PATH . '/configs/navigation.php';
        $navigation = new Zend_Navigation($config);
        $view->navigation($navigation);


        $view->headScript()->appendFile("http://html5shiv.googlecode.com/svn/trunk/html5.js", 'text/javascript', array('conditional' => 'lt IE 9'));
        $view->headScript()->appendFile("/jquery/js/jquery-1.6.2.min.js", 'text/javascript');
        $view->headScript()->appendFile("/jquery/js/jquery-ui-1.8.14.custom.min.js", 'text/javascript');
        $view->headScript()->appendFile("/jquery/js/jquery.notify.js", 'text/javascript');
        $view->headScript()->appendFile("/jquery/js/jquery.lightbox-0.5.js", 'text/javascript');
        $view->headScript()->appendFile("/jquery/js/jquery.mousewheel-3.0.4.pack.js", 'text/javascript');
        $view->headScript()->appendFile("/jquery/js/jquery.fancybox-1.3.4.pack.js", 'text/javascript');
        $view->headScript()->appendFile("/jquery/js/jquery.imgareaselect.pack.js", 'text/javascript');
//        $view->headScript()->appendFile("/jquery/js/custom.js", 'text/javascript');
        $view->headLink()->headLink(array('rel' => 'favicon', 'href' => '/favicon.ico'));

        $view->headLink()->appendStylesheet($view->baseUrl("/css/reset.css"), "screen");
        $view->headLink()->appendStylesheet($view->baseUrl("http://fonts.googleapis.com/css?family=Lato:400,700,900,400italic,700italic,900italic"), "screen");
        $view->headLink()->appendStylesheet($view->baseUrl("/jquery/css/ui.notify.css"), "screen");
        $view->headLink()->appendStylesheet($view->baseUrl("/jquery/css/imgareaselect-default.css"), "screen");
        $view->headLink()->appendStylesheet($view->baseUrl("/jquery/css/jquery.lightbox-0.5.css"), "screen");
        $view->headLink()->appendStylesheet($view->baseUrl("/jquery/css/jquery.fancybox-1.3.4.css"), "screen");
        $view->headLink()->appendStylesheet($view->baseUrl("/css/main.css"), "screen");

        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $view->headMeta()->appendHttpEquiv('Content-Language', 'pl-PL');
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

        $config = $this->getOption('doctrine');
        $manager = Doctrine_Manager::getInstance();

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

        if (Zend_Auth::getInstance()->hasIdentity())
        {
            Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole(Zend_Auth::getInstance()->getIdentity()->role);
        }
        else
        {
            Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole('guest');
        }
    }

}


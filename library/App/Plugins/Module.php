<?php

class App_Plugins_Module extends Zend_Controller_Plugin_Abstract
{

    protected $_moduleLayouts;

    /**
     * registration of module layout
     *
     * @param $module
     * @param $layoutPath
     * @param $layout
     */
    public function registerModuleLayout($module, $layoutPath, $layout=null)
    {
        $this->_moduleLayouts[$module] = array(
            'layoutPath' => $layoutPath,
            'layout' => $layout
        );
    }

    /**
     * Predispatch function for changing layout by requested module
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        
        if (isset($this->_moduleLayouts[$request->getModuleName()]))
        {
            $config = $this->_moduleLayouts[$request->getModuleName()];
            $layout = Zend_Layout::getMvcInstance();
            if ($layout->getMvcEnabled())
            {
                $layout->setLayoutPath($config['layoutPath']);
                if ($config['layout'] !== null)
                {
                    $layout->setLayout($config['layout']);
                    
                }
            }
        }
    }

}
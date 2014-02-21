<?php
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    /**
     * @var Zend_Application
    */
    protected $application;
    static $i = 1;

    public function setUp()
    {
        $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
    }

    protected function getFlashMessenger()
    {
        return $this->getMock(
            'Zend_Controller_Action_Helper_FlashMessenger',
            array('addMessage'),
            array(),
            'NewAction'.self::$i++.'_Test_FlashMessenger',
            false
        );
    }

    public function appBootstrap()
    {
        $this->application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        $this->application->bootstrap();
        $appBootstrap = $this->application->getBootstrap();
        $front = $appBootstrap->getResource('FrontController');
        $front->setParam('bootstrap', $appBootstrap);

    }
    protected function doLogin()
    {
        // create a fake identity
        $oAdapter = new App_Auth();
        $oAdapter->setCredential(User::getHashedPassword('kogut41'));
        $oAdapter->setIdentity('zick4@wp.pl');

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($oAdapter);

    }
    protected function doLogout()
    {
        Zend_Auth::getInstance()->clearIdentity();

    }
}

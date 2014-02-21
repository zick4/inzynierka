<?php
class UserControllerTest extends ControllerTestCase
{
    public function testRegistrationSuccessful()
    {
        $this->request->setMethod('POST')
             ->setPost(array(
                  'email' => 'zick5@wp.pl',
                  'password' => 'kogut41',
                  'repassword' => 'kogut41',
                  'birthday' => '1986-06-04',
                  'sex' => 'men'
             ));
        $this->dispatch('/user/registration');
        $this->assertRedirectTo('/user/profile');
    }

    public function testRegistrationFailure()
    {
        $this->request->setMethod('POST')
             ->setPost(array(
                  'password' => 'kogut41',
                  'repassword' => 'kogut41',
                  'birthday' => '1986-06-04',
                  'sex' => 'men'
             ));
        $this->dispatch('/user/registration');
        $this->assertNotRedirect();
    }

    public function testLoginSuccessful()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger->expects($this->once())
                      ->method('addMessage')
                      ->with(array("message" => "Witaj!", "status" => "ok"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('POST')
              ->setPost(array(
                  'email' => 'zick4@wp.pl',
                  'password' => 'kogut41',
              ));
        $this->dispatch('/user/login');
        $this->assertRedirectTo('/album/list');

    }

    public function testLoginFailure()
    {
        $this->request->setMethod('POST')
              ->setPost(array(
                  'username' => 'dasdasd.pl',
                  'password' => 'dasd'
              ));
        $this->dispatch('/user/login');
        $this->assertNotRedirect();
        $this->assertRoute('login');

    }

    public function testLogoutSuccessful()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger->expects($this->once())
                      ->method('addMessage')
                      ->with(array("message" => "Wylogowałeś się z systemu", "status" => "ok"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->doLogin();
        $this->dispatch('/user/logout');
        $this->assertRedirectTo('/user/login');
        $this->assertFalse(Zend_Auth::getInstance()->hasIdentity());
    }

    public function testProfileNotLoggedIn()
    {
        $this->dispatch('/user/profile');
        $this->assertRedirectTo('/user/login');
    }

    public function testProfile()
    {
        $this->doLogin();
        $this->dispatch('/user/profile');
        $this->assertNotRedirect();
        $this->assertQueryContentContains('h2', 'Profil');
    }

    public function testProfileUpdateSuccessful()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger->expects($this->once())
                      ->method('addMessage')
                      ->with(array(
                          "message" => "Profil został zaktualizowany",
                          "status" => "ok"
                      ));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('POST')
              ->setPost(array(
                  'oldpassword' => 'kogut41',
                  'password' => 'kogut42',
                  'repassword' => 'kogut42',
                  'birthday' => '1986-06-05',
                  'sex' => 'women'
              ));
        $this->doLogin();
        $this->dispatch('/user/profile');
        $this->assertNotRedirect();
    }
//
    public function testProfileUpdateFailure()
    {

        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger->expects($this->once())
                      ->method('addMessage')
                      ->with(array(
                          "message" => "Wystąpiły błedy przy aktualizowaniu formularza",
                          "status" => "error"
                      ));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);


        $this->request->setMethod('POST')
              ->setPost(array(
                  'username' => 'dasdasd.pl',
                  'password' => 'dasd'
              ));
        $this->doLogin();
        $this->dispatch('/user/profile');
        $this->assertNotRedirect();
        
    }
}

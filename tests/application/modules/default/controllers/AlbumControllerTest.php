<?php
class AlbumControllerTest extends ControllerTestCase
{
    public function testAddAlbumSuccessful()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Album został‚ dodany", "status"  => "ok"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('POST')
             ->setPost(array(
                  'name' => 'tmp'
             ));
        $this->doLogin();
        $this->dispatch('/album/add');
        $this->assertRedirectTo('/album/list');
    }

    public function testAddAlbumFailure()
    {
        $this->request->setMethod('POST')
             ->setPost(array(
             ));
        $this->doLogin();
        $this->dispatch('/album/add');
        $this->assertNotRedirect();
    }
    
    public function testDeleteAlbumSuccessful()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Album został‚ usunięty", "status"  => "ok"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('POST')
             ->setPost(array(
                  'album_id' => 3
             ));
        $this->doLogin();
        $this->dispatch('/album/delete');
        $this->assertRedirectTo('/album/list');
    }

    public function testDeleteNoAlbumFailure()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('POST')
             ->setPost(array(
             ));
        $this->doLogin();
        $this->dispatch('/album/delete');
        $this->assertRedirectTo('/album/list');
    }

    public function testDeleteNoOwnAlbumFailure()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('POST')
             ->setPost(array(
                 "album_id" => 4
             ));
        $this->doLogin();
        $this->dispatch('/album/delete');
        $this->assertRedirectTo('/album/list');
    }
    public function testEditAlbumSuccessful()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Album został zmieniony", "status"  => "ok"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('POST')
             ->setPost(array(
                  'name' => 'tmp',
                  'album_id' => 1
             ));
        $this->doLogin();
        $this->dispatch('/album/edit');
        $this->assertRedirectTo('/album/list');
    }

    public function testEditNoAlbumFailure()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('POST')
             ->setPost(array(
             ));
        $this->doLogin();
        $this->dispatch('/album/edit');
        $this->assertRedirectTo('/album/list');
    }

    public function testEditNoOwnAlbumFailure()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('POST')
             ->setPost(array(
                 'album_id' => 4
             ));
        $this->doLogin();
        $this->dispatch('/album/edit');
        $this->assertRedirectTo('/album/list');
    }

    public function testListOwnSuccessfull()
    {
        $this->doLogin();
        $this->dispatch('/album/list');
        $this->assertNotRedirect();
    }

    public function testListOtherRedirectFailure()
    {
        $this->request->setMethod('GET')
             ->setParams(array(
                  'user_id' => 1,
             ));
        $this->dispatch('/album/list');
        $this->assertRedirectTo('/user/login');
    }

    public function testListFailure()
    {
        $this->dispatch('/album/list');
        $this->assertRedirectTo('/user/login');
    }

    public function testPublicSuccessful()
    {
        $this->request->setMethod('GET')
             ->setParams(array(
                  'album_id' => 2,
             ));
        $this->dispatch('/album/public');
        $this->assertNotRedirect();
    }

    public function testPublicFailure()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('GET')
             ->setParams(array(
                  'album_id' => 1,
             ));
        $this->dispatch('/album/public');
        $this->assertRedirectTo('/');
    }

    public function testHideSuccessful()
    {
        $this->doLogin();

        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Album został‚ ukryty", "status"  => "ok"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('GET')
             ->setParams(array(
                  'album_id' => 1,
             ));
        $this->dispatch('/album/hide');
        $this->assertRedirectTo('/album/list');
    }

    public function testHideNoAlbumFailure()
    {
        $this->doLogin();

        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('GET')
             ->setParams(array(
                  'album_id' => 99,
             ));
        $this->dispatch('/album/hide');
        $this->assertRedirectTo('/album/list');
    }

    public function testHideNoLoggedInFailure()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Musisz być zalogowany, by przegladać tę część", "status"  => "warning"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('GET')
             ->setParams(array(
                  'album_id' => 1,
             ));
        $this->dispatch('/album/hide');
        $this->assertRedirectTo('/user/login');
    }

    public function testShareSuccessful()
    {
        $this->doLogin();

        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Album został‚ udostępniony wszystkim", "status"  => "ok"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('GET')
             ->setParams(array(
                  'album_id' => 1,
             ));
        $this->dispatch('/album/share');
        $this->assertRedirectTo('/album/list');
    }

    public function testShareNoAlbumFailure()
    {
        $this->doLogin();

        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('GET')
             ->setParams(array(
                  'album_id' => 99,
             ));
        $this->dispatch('/album/share');
        $this->assertRedirectTo('/album/list');
    }

    public function testShareNoLoggedInFailure()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array("message" => "Musisz być zalogowany, by przegladać tę część", "status"  => "warning"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('GET')
             ->setParams(array(
                  'album_id' => 1,
             ));
        $this->dispatch('/album/share');
        $this->assertRedirectTo('/user/login');
    }

    public function testShowNoAlbumFailure()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array('message' => "Nie istnieje taki albumń", "status"  => "error"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('GET')
             ->setParams(array(
                  'album_id' => 99,
             ));
        $this->dispatch('/album/show');
        $this->assertRedirectTo('/');
    }

    public function testShowNoLogin()
    {
        $mockMessenger = $this->getFlashMessenger();
        $mockMessenger
            ->expects($this->once())
            ->method('addMessage')
            ->with(array('message' => "Musisz być zalogowany, by przegladać tę część", "status"  => "warning"));
        Zend_Controller_Action_HelperBroker::addHelper($mockMessenger);

        $this->request->setMethod('GET')
             ->setParams(array(
                  'album_id' => 1,
             ));
        $this->dispatch('/album/show');
        $this->assertRedirectTo('/user/login');
    }
    

}

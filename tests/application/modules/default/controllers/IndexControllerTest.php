<?php
class IndexControllerTest extends ControllerTestCase
{
    public function testHasIdentity()
    {
        $this->doLogin();
        $this->dispatch('/');
        $this->assertQueryContentContains('h2', 'Profil');
    }
    
    public function testHasNotIdentity()
    {
        $this->dispatch('/');
        $this->assertQueryContentContains('h2', 'Login');
    }
}

<?php

class UserController extends Zend_Controller_Action
{

    public function profilAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity())
        {
            $oUser = Zend_Auth::getInstance()->getIdentity();

            $this->view->oAlbums = $oUser->Albums;
        }
        else
        {
            $this->_helper->redirector('login'); // back to login page
        }
    }

    /**
     * Logowanie użytkownika
     */
    public function loginAction()
    {
        $oConfig = Zend_Registry::get('config_forms');
        $oForm = new Zend_Form($oConfig->login);
        $oRequest = $this->getRequest();
        if ($oRequest->isPost())
        {
            if ($oForm->isValid($oRequest->getPost()))
            {

                $aValues = $oForm->getValues();
                $oAdapter = new App_Auth();
                $oAdapter->setCredential(User::getHashedPassword($aValues['password']));
                $oAdapter->setIdentity($aValues['email']);

                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($oAdapter);
                if ($result->isValid())
                {
                    $this->_helper->redirector('profil');
                }
                else
                {
                    $oForm->addError($result->getMessages());
                }
            }
        }
        $this->view->oForm = $oForm;
    }

    /**
     * Wylogowanie użytkownkika
     */
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login'); // back to login page
    }

    /**
     * Rejestrowanie uzytkownika
     */
    public function registrationAction()
    {
        $oConfig = Zend_Registry::get('config_forms');
        $oForm = new Zend_Form($oConfig->register);
        if ($this->getRequest()->isPost() && $oForm->isValid($this->getRequest()->getParams()))
        {
            $aValues = $oForm->getValues();

            $oUser = new User();
            $oUser->password = $aValues['password'];
            $oUser->email = $aValues['email'];
            $oUser->birthday = $aValues['birthday'];
            $oUser->save();
            $this->_helper->redirector('profil'); 
        }
        $this->view->oForm = $oForm;
    }

}
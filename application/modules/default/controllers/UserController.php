<?php

class UserController extends App_Controller
{

    public function profilAction()
    {
        $this->_checkIdentity();
        $iUserId = $this->getRequest()->getParam("user_id");
        if ($iUserId)
        {
            $oUser = Doctrine_Core::getTable('User')->find($iUserId);
        }
        else
        {
            $oUser = Zend_Auth::getInstance()->getIdentity();
        }
        $this->view->oUser = $oUser;
        
        $oConfig = Zend_Registry::get('config_forms');
        $this->view->oForm = new Zend_Form($oConfig->profil);
        $oRequest = $this->getRequest();
        if ($oRequest->isPost())
        {
            if ($this->view->oForm->isValid($oRequest->getPost()))
            {
                $aValues = $this->view->oForm->getValues();
                $oUser = new User();
                $oUser->password = $aValues['password'];
                $oUser->birthday = $aValues['birthday'];
                $oUser->save();
                $this->_helper->flashMessenger->addMessage(array(
                    "message" => "Profil został zaktualizowany", 
                    "status" => "ok"
                ));
            }
            else
            {
                $this->_helper->flashMessenger->addMessage(array(
                    "message" => "Wystąpiły błedy przy aktualizowaniu formularza", 
                    "status" => "error"
                ));
            }
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
        if ($oRequest->isPost() && $oForm->isValid($oRequest->getPost()))
        {
            $aValues = $oForm->getValues();
            $oAdapter = new App_Auth();
            $oAdapter->setCredential(User::getHashedPassword($aValues['password']));
            $oAdapter->setIdentity($aValues['email']);

            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($oAdapter);
            if ($result->isValid())
            {
                $this->_helper->flashMessenger->addMessage(array("message" => "Witaj!", "status" => "ok"));
                $this->_helper->_redirector->setGotoRoute(array(), 'album_list');
                return;
            }
            else
            {
                $mErrors = $result->getMessages();
                foreach ($mErrors as $sError)
                {
                    $this->_helper->flashMessenger->addMessage(array("message" => $sError, "status" => "error"));
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
        $this->_helper->flashMessenger->addMessage(array("message" => "Wylogowałeś się z systemu", "status" => "ok"));
        $this->_helper->_redirector->setGotoRoute(array(), 'login');
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
            $this->_helper->flashMessenger->addMessage(array("message" => "Rejestracja zakończona powodzeniem", "status" => "ok"));
            $oAdapter = new App_Auth();
            $oAdapter->setCredential(User::getHashedPassword($aValues['password']));
            $oAdapter->setIdentity($aValues['email']);

            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($oAdapter);
            
            $this->_helper->redirector->gotoSimple('profil', 'user');
            
        }
        else
        {
            $this->view->oForm = $oForm;
        }
    }

}
<?php

class UserController extends Zend_Controller_Action
{

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
                $oAdapter->setCredential(sha1($aValues['password'] . User::getSalt()));
                $oAdapter->setIdentity($aValues['email']);
//                App_Auth::getInstance()->process($oForm->getValues());

//                $this->_helper->redirector('index', 'index');
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($oAdapter);
                if ($result->isValid())
                {
                    exit("zalogowany :)");
                    $this->_redirect("index"); //Redirect to index with success...
                }
                else
                {
                    Debug::dump($result);
                    exit("niezalogowany :(");
                }
//              $oForm->addError($e->getMessage());
            }
        }
        $this->view->oForm = $oForm;
    }

    /**
     * Wylogowanie użytkownkika
     */
    public function logoutAction()
    {
        App_Auth::getInstance()->clearIdentity();
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
        }
        $this->view->oForm = $oForm;
        $this->_helper->redirector('login'); // back to login page
    }

}
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
        if ($this->getRequest()->isPost())
        {
            if ($oForm->isValid($oRequest->getPost()))
            {
                try {
                    App_Auth::getInstance()->process($oForm->getValues());
                    $this->_helper->redirector('index', 'index');
                }
                catch (App_Auth_Exception $e) {
                    $oForm->addError($e->getMessage());
                   
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
        App_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login'); // back to login page
    }

    
    /**
     * Rejestrowanie uzytkownika
     */
    public function registerAction()
    {
        
    }

}
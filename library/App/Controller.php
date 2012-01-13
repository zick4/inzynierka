<?php

class App_Controller extends Zend_Controller_Action
{

    /**
     * Sprawdza czy użytkownik jest zalogowany.
     * Ustawia przekierowanie na stronę logowania.
     * 
     * @return boolean
     */
    protected function _checkIdentity()
    {
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Musisz być zalogowany, by przegladać tę część", "status"  => "warning"));
            $this->_helper->_redirector->setGotoRoute(array(), 'login');
            return false;
        }
        return true;
    }

    /**
     * Zwraca zalogowanego użytkownika lub null
     * 
     * @return mixed
     */
    protected function _getIdentity()
    {
        if ($this->_checkIdentity())
        {
            return Zend_Auth::getInstance()->getIdentity();
        }
        else
        {
            return null;
        }
    }

}
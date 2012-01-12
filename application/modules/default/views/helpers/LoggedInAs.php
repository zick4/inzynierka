<?php

class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract
{

    public function loggedInAs()
    {
        if (Zend_Auth::getInstance()->hasIdentity())
        {
            $username = Zend_Auth::getInstance()->getIdentity()->email;
            return 'Welcome ' . $username . '! ';
        }
    }

}


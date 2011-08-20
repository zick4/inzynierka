<?php
class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract
{
    public function loggedInAs ()
    {

        $loginLink        = '<a href="'.$this->view->url(array(), 'login').'">login</a>';
        $logoutLink       = '<a href="'.$this->view->url(array(), 'logout').'">logout</a>';
        $registrationLink = '<a href="'.$this->view->url(array(), 'registration').'">registration</a>';

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $username = Zend_Auth::getInstance()->getIdentity()->email;
            return 'Welcome ' . $username .  '! '. $logoutLink;
        }

        return $loginLink.' or '.$registrationLink;
    }
}


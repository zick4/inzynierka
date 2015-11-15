<?php

class IndexController extends App_Controller
{

    public function indexAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_forward('profile', 'user');
        } else {
            $this->_forward('login', 'user');
        }
    }


}


<?php

class AlbumController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $this->_forward("list");
    }
    
    public function listAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->redirector("login", "user");
        }

        $oUser = Zend_Auth::getInstance()->getIdentity();

        $this->view->oUser = $oUser;
    }

    public function addAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->redirector("login", "user");
        }
        $oUser = Zend_Auth::getInstance()->getIdentity();
        $oConfig = Zend_Registry::get('config_forms');
        $oForm = new Zend_Form($oConfig->album);
        $oRequest = $this->getRequest();
        if ($oRequest->isPost())
        {
            if ($oForm->isValid($oRequest->getPost()))
            {
                $aValues = $oForm->getValues();
                $oUser->Albums[]->name = $aValues['name'];
                $oUser->save();
                $this->_helper->redirector('index');
            }
        }
        $this->view->oForm = $oForm;
    }

    public function editAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->redirector("login", "user");
        }
        $oForm = new Zend_Form(Zend_Registry::get('config_forms')->album);
        $oAlbum = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
        if (empty($oAlbum->user_id) || $oAlbum->user_id !=  $oUser = Zend_Auth::getInstance()->getIdentity()->id)
        {
             $oForm->getElement("name")->addError("Nie istnieje taki album, lub nie masz odpowiednich prawnień");
        }
        $oRequest = $this->getRequest();
        if ($oRequest->isPost())
        {
            if ($oForm->isValid($oRequest->getPost()))
            {
                $oAlbum->name = $oForm->getValue("name");
                $oAlbum->save();
                $this->_helper->redirector('index');
            }
        }
        $oForm->populate($oAlbum->toArray());
        $this->view->oForm = $oForm;
    }

    public function deleteAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->redirector("login", "user");
        }
        $oAlbum = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
        if (empty($oAlbum->user_id) || $oAlbum->user_id !=  $oUser = Zend_Auth::getInstance()->getIdentity()->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich prawnień", "status" => "error"));
        }
        $oAlbum->delete();
        $this->_helper->redirector('index');
    }

    public function showAction()
    {
        
    }

}


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
            $this->_helper->_redirector->setGotoRoute(array(), 'login');
        }

        $oUser = Zend_Auth::getInstance()->getIdentity();

        $this->view->oUser = $oUser;
    }

    public function addAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->_redirector->setGotoRoute(array(), 'login');
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
                $this->_forward("list");
            }
        }
        $this->view->oForm = $oForm;
    }

    public function editAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->_redirector->setGotoRoute(array(), 'login');
        }
        $oForm = new Zend_Form(Zend_Registry::get('config_forms')->album);
        $oAlbum = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
        if (empty($oAlbum) || $oAlbum->user_id != Zend_Auth::getInstance()->getIdentity()->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_helper->redirector("list");

        }
        else
        {

            $oRequest = $this->getRequest();
            if ($oRequest->isPost())
            {
                if ($oForm->isValid($oRequest->getPost()))
                {
                    $oAlbum->name = $oForm->getValue("name");
                    $oAlbum->save();
                    $this->_forward("list");
                }
            }
            $oForm->populate($oAlbum->toArray());
            $this->view->oForm = $oForm;
        }
    }

    public function deleteAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->_redirector->setGotoRoute(array(), 'login');
        }
        $oAlbum = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
        if (empty($oAlbum) || $oAlbum->user_id != Zend_Auth::getInstance()->getIdentity()->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status" => "error"));
        }
        else
        {

            $oAlbum->delete();
        }
        $this->_forward("list");
    }

    public function showAction()
    {
        $this->view->Album = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
    }

}

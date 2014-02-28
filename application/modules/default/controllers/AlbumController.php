<?php

class AlbumController extends App_Controller
{

    public function indexAction()
    {
        $this->_forward("list");
    }

    public function listAction()
    {
        $iUserId = $this->getRequest()->getParam("user_id");
        
        if ($iUserId)
        {
            $this->view->oUser = Doctrine_Core::getTable('User')->find($iUserId);
        }
        else
        {
            $oUser = $this->_getIdentity();
        }
    }

    /**
     * Wyświetlanie udostępnionego albumu
     */
    public function publicAction()
    {
        $oAlbum = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
        if (empty($oAlbum) || $oAlbum->is_shared == false)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
            $this->_helper->_redirector->setGotoRoute(array(), 'main_page', true);
        }
        else
        {
            $this->view->Album = $oAlbum;
        }
    }
    /**
     * Udostępnianie albumów dla wszystkich
     */
    public function shareAction()
    {
        $oUser = $this->_getIdentity();
        if ($oUser)
        {
            $oAlbum = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
            if (empty($oAlbum) || $oAlbum->user_id != $oUser->id)
            {
                $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
            }
            else
            {
                $oAlbum->is_shared = true;
                $oAlbum->save();
                $this->_helper->flashMessenger->addMessage(array("message" => "Album został‚ udostępniony wszystkim", "status"  => "ok"));
            }
            $this->_helper->_redirector->setGotoRoute(array(), 'album_list', true);
        }
    }
    /**
     * Ukrywanie albumów
     */
    public function hideAction()
    {
        $oUser = $this->_getIdentity();
        if ($oUser)
        {
            $oAlbum = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
            if (empty($oAlbum) || $oAlbum->user_id != $oUser->id)
            {
                $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
            }
            else
            {
                $oAlbum->is_shared = false;
                $oAlbum->save();
                $this->_helper->flashMessenger->addMessage(array("message" => "Album został‚ ukryty", "status"  => "ok"));
            }
            $this->_helper->_redirector->setGotoRoute(array(), 'album_list', true);
        }
    }

    public function addAction()
    {
        $oUser = $this->_getIdentity();
        $oConfig  = Zend_Registry::get('config_forms');
        $oForm    = new Zend_Form($oConfig->album);
        $oRequest = $this->getRequest();
        if ($oRequest->isPost() && $oForm->isValid($oRequest->getPost()))
        {
            $aValues = $oForm->getValues();
            $oUser->Albums[]->name = $aValues['name'];
            $oUser->save();
            $this->_helper->flashMessenger->addMessage(array("message" => "Album został‚ dodany", "status"  => "ok"));
            $this->_helper->_redirector->setGotoRoute(array(), 'album_list', true);
        }
        else
        {
            $this->view->oForm = $oForm;
        }
    }

    public function editAction()
    {
        $oUser = $this->_getIdentity();
        $oForm  = new Zend_Form(Zend_Registry::get('config_forms')->album);
        $oAlbum = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
        
        if (empty($oAlbum) || $oAlbum->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
            $this->_helper->_redirector->setGotoRoute(array(), 'album_list', true);
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
 
                    $this->_helper->flashMessenger->addMessage(array("message" => "Album został zmieniony", "status"  => "ok"));
                    $this->_helper->_redirector->setGotoRoute(array(), 'album_list', true);
                }
            }
            $oForm->populate($oAlbum->toArray());
            $this->view->oForm = $oForm;
        }
    }

    public function deleteAction()
    {
        $oUser = $this->_getIdentity();
        $oAlbum = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));

        if (empty($oAlbum) || $oAlbum->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
        }
        else
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Album został‚ usunięty", "status"  => "ok"));
            $oAlbum->delete();
        }
        $this->_helper->_redirector->setGotoRoute(array(), 'album_list', true);
    }

    /**
     * Pokazywanie zawartości albumu
     */
    public function showAction()
    {
        $oAlbum = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
        if (empty($oAlbum) ||  $oAlbum->is_shared == false)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status"  => "error"));
            $this->_helper->_redirector-> gotoUrl("/");
            return;
        }
        else
        {
            $this->view->Album = Doctrine_Core::getTable('Album')->find($this->getRequest()->getParam("album_id"));
        }
    }

}


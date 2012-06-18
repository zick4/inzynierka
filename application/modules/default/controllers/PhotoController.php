<?php

class PhotoController extends App_Controller
{
    /**
     * Usuwanie obrazka
     */
    public function deleteAction()
    {
        $oUser = $this->_getIdentity();
        $iPhotoId = (int) $this->getRequest()->getParam("photo_id");
        $oPhoto = Doctrine_Core::getTable('Photo')->find($iPhotoId);

        // sprawdzenie uprawnień
        if (empty($oPhoto) || $oPhoto->Album->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki obrazek, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_helper->_redirector->setGotoRoute(array(), 'profil');
        }
        else
        {
            $oPhoto->delete();
            $this->_helper->flashMessenger->addMessage(array("message" => "Zdjęcie zostało usunięte", "status" => "ok"));
            $this->_helper->_redirector->setGotoRoute(array('album_id'=>$oPhoto->Album->id), 'album_show');
        }
    }

    public function croppingAction()
    {
        $oUser = $this->_getIdentity();
        $iPhotoId = (int) $this->getRequest()->getParam("photo_id");
        $oPhoto = Doctrine_Core::getTable('Photo')->find($iPhotoId);

        // sprawdzenie uprawnień
        if (empty($oPhoto) || $oPhoto->Album->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki obrazek, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_helper->_redirector->setGotoRoute(array(), 'profil');
        }
        else
        {
            $oConfig = Zend_Registry::get('config_forms');
            $oForm = new Zend_Form($oConfig->photo_cropping);
            $oRequest = $this->getRequest();

            if ($oRequest->isPost() && $oForm->isValid($oRequest->getPost()))
            {
                try {
                    $oPhoto->crop($oForm->getValues());
                    $oPhoto->makeMiniature();
                    $this->_helper->flashMessenger->addMessage(array("message" => "Operacja zakończona!", "status" => "ok"));
                }
                catch (Exception $e) {
                    $this->_helper->flashMessenger->addMessage(array("message" => $e->getMessage(), "status" => "error"));
                }
                $this->_helper->_redirector->setGotoRoute(array('album_id'=>$oPhoto->Album->id), 'album_show');

            }
            else
            {
                $this->view->oForm = $oForm;
                $this->view->oPhoto = $oPhoto;
            }
        }
    }
    /**
     * Obracanie w pionie
     */
    public function flipAction()
    {
        $oUser = $this->_getIdentity();
        $iPhotoId = (int) $this->getRequest()->getParam("photo_id");
        $oPhoto = Doctrine_Core::getTable('Photo')->find($iPhotoId);

        // sprawdzenie uprawnień
        if (empty($oPhoto) || $oPhoto->Album->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki obrazek, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_helper->_redirector->setGotoRoute(array(), 'profil');
        }
        else
        {
            $oPhoto->flip();
            $oPhoto->makeMiniature();
            $this->_helper->flashMessenger->addMessage(array("message" => "Operacja zakończona!", "status" => "ok"));
            $this->_helper->_redirector->setGotoRoute(array('album_id'=>$oPhoto->Album->id), 'album_show');
        }
    }

    /**
     * Obracanie w poziomie
     */
    public function flopAction()
    {
        $oUser = $this->_getIdentity();
        $iPhotoId = (int) $this->getRequest()->getParam("photo_id");
        $oPhoto = Doctrine_Core::getTable('Photo')->find($iPhotoId);

        // sprawdzenie uprawnień
        if (empty($oPhoto) || $oPhoto->Album->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki obrazek, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_helper->_redirector->setGotoRoute(array(), 'profil');
        }
        else
        {
            $oPhoto->flop();
            $oPhoto->makeMiniature();
            $this->_helper->flashMessenger->addMessage(array("message" => "Operacja zakończona!", "status" => "ok"));
            $this->_helper->_redirector->setGotoRoute(array('album_id'=>$oPhoto->Album->id), 'album_show');
        }
    }

    /**
     * Rysunek węglem
     */
    public function charcoalAction()
    {
        $oUser = $this->_getIdentity();
        $iPhotoId = (int) $this->getRequest()->getParam("photo_id");
        $oPhoto = Doctrine_Core::getTable('Photo')->find($iPhotoId);

        // sprawdzenie uprawnień
        if (empty($oPhoto) || $oPhoto->Album->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki obrazek, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_helper->_redirector->setGotoRoute(array(), 'profil');
        }
        else
        {
            $oPhoto->charcoal();
            $oPhoto->makeMiniature();
            $this->_helper->flashMessenger->addMessage(array("message" => "Operacja zakończona!", "status" => "ok"));
            $this->_helper->_redirector->setGotoRoute(array('album_id'=>$oPhoto->Album->id), 'album_show');
        }
    }

    /**
     * Rozmycie farbą olejną
     */
    public function oilpaintAction()
    {
        $oUser = $this->_getIdentity();
        $iPhotoId = (int) $this->getRequest()->getParam("photo_id");
        $oPhoto = Doctrine_Core::getTable('Photo')->find($iPhotoId);

        // sprawdzenie uprawnień
        if (empty($oPhoto) || $oPhoto->Album->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki obrazek, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_helper->_redirector->setGotoRoute(array(), 'profil');
        }
        else
        {
            $oPhoto->oilPaint();
            $oPhoto->makeMiniature();
            $this->_helper->flashMessenger->addMessage(array("message" => "Operacja zakończona!", "status" => "ok"));
            $this->_helper->_redirector->setGotoRoute(array('album_id'=>$oPhoto->Album->id), 'album_show');
        }
    }

    /**
     * Dodawanie obrazka do albumu
     */
    public function addAction()
    {
        $oUser = $this->_getIdentity();
        $iAlbumId = (int) $this->getRequest()->getParam("album_id");
        $oAlbum = Doctrine_Core::getTable('Album')->find($iAlbumId);
       
        // sprawdzenie uprawnień
        if (empty($oAlbum) || $oAlbum->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki obrazek, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_helper->_redirector->setGotoRoute(array('album_id'=>$oPhoto->Album->id), 'album_show');
        }
        else
        {
            $oConfig = Zend_Registry::get('config_forms');
            $oForm = new Zend_Form($oConfig->photo);
            $oRequest = $this->getRequest();

            if ($oRequest->isPost() && $oForm->isValid($oRequest->getPost()))
            {

                $oConnection = Zend_Registry::get('connection');
                $oConnection->beginTransaction();
                try {

                    // zapisanie nowego rekordu fotki
                    $oPhoto = new Photo();
                    $oPhoto->description = $oForm->getValue('description');
                    $oPhoto->album_id = $oAlbum->id;

                    $sAlbumDir = $oPhoto->getPath();

                    if (file_exists($sAlbumDir) || mkdir($sAlbumDir, 0777, true))
                    {
                        // upload fotki
                        $oUpload = new Zend_File_Transfer_Adapter_Http();
                        $oUpload->setDestination($sAlbumDir);
                        $oUpload->receive();


                        // zmiana nazwy pliku z oryginalnej na id fotki
                        $aInfo = $oUpload->getFileInfo();
                        $oPhoto->extension = Photo::findExtension($aInfo['photo']['name']);
                        $oPhoto->save();
                        $oFilterFileRename = new Zend_Filter_File_Rename(array('target' => $sAlbumDir . $oPhoto->getPhotoFileName(), 'overwrite' => true));
                        $oFilterFileRename->filter($oUpload->getFileName());

                        // tworzenie miniaturki
                        $oPhoto->makeMiniature();

                        $oConnection->commit();
                        $this->_helper->flashMessenger->addMessage(array("message" => "Zdjęcie zostało dodane", "status" => "ok"));
                        $this->_helper->_redirector->setGotoRoute(array('album_id' => $oAlbum->id), 'album_show');
                    }
                    else
                    {
                        $oConnection->rollback();
                        $this->_helper->flashMessenger->addMessage(array("message" => "Nie udało się utworzyć katalogu dla zdjęć", "status" => "error"));
                    }
                }
                catch (PhotoException $e) {
                    $oConnection->rollback();
                    $this->_helper->flashMessenger->addMessage(array("message" => $e->getMessage(), "status" => "error"));
                }
                catch (Zend_File_Transfer_Exception $e) {
                    $oConnection->rollback();
                    $this->_helper->flashMessenger->addMessage(array("message" => $e->getMessage(), "status" => "error"));
                }
            }
            $this->view->oForm = $oForm;
        }
    }

}
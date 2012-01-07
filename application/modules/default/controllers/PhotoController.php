<?php

class PhotoController extends Zend_Controller_Action
{
    public function deleteAction()
    {
        // uzytkownik musi być zalogowany
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Musisz być zalogowany, by przegladać tę część", "status" => "warning"));
            $this->_helper->_redirector->setGotoRoute(array(), 'login');
        }
        $oUser = Zend_Auth::getInstance()->getIdentity();
        $iPhotoId = (int) $this->getRequest()->getParam("photo_id");
        $oPhoto = Doctrine_Core::getTable('Photo')->find($iPhotoId);

        // sprawdzenie uprawnień
        if (empty($oPhoto) || $oPhoto->Album->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_helper->_redirector->setGotoRoute(array(), 'profil');
        }
        $oPhoto->delete();
        $this->_helper->flashMessenger->addMessage(array("message" => "Zdjęcie zostało usunięte", "status" => "ok"));
        $this->_helper->_redirector->setGotoRoute(array('album_id'=>$oPhoto->Album->id), 'album_show');
    }

    public function addAction()
    {
        // uzytkownik musi być zalogowany
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Musisz być zalogowany, by przegladać tę część", "status" => "warning"));
            $this->_helper->_redirector->setGotoRoute(array(), 'login');
        }
        $oUser = Zend_Auth::getInstance()->getIdentity();
        $iAlbumId = (int) $this->getRequest()->getParam("album_id");
        $oAlbum = Doctrine_Core::getTable('Album')->find($iAlbumId);

        // sprawdzenie uprawnień
        if (empty($oAlbum) || $oAlbum->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_helper->_redirector->setGotoRoute(array('album_id'=>$oPhoto->Album->id), 'album_show');
        }
        $oConfig = Zend_Registry::get('config_forms');
        $oForm = new Zend_Form($oConfig->photo);
        $oRequest = $this->getRequest();

        if ($oRequest->isPost())
        {
            if ($oForm->isValid($oRequest->getPost()))
            {

                $oConnection = Zend_Registry::get('connection');
                $oConnection->beginTransaction();
                try {

                    // zapisanie nowego rekordu fotki
                    $oPhoto = new Photo();
                    $oPhoto->description = $oForm->getValue('description');
                    $oPhoto->album_id = $oAlbum->id;

                    $sAlbumDir = PUBLIC_DIR.$oPhoto->Album->getDir();

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
        }
        $this->view->oForm = $oForm;
    }

}
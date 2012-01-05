<?php

class PhotoController extends Zend_Controller_Action
{

    public function addAction()
    {
        // uzytkownik musi być zalogowany
        if (!Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_helper->_redirector->setGotoRoute(array(), 'login');
        }
        $oUser = Zend_Auth::getInstance()->getIdentity();
        $iAlbumId = (int) $this->getRequest()->getParam("album_id");
        $oAlbum = Doctrine_Core::getTable('Album')->find($iAlbumId);

        // sprawdzenie uprawnień
        if (empty($oAlbum) || $oAlbum->user_id != $oUser->id)
        {
            $this->_helper->flashMessenger->addMessage(array("message" => "Nie istnieje taki album, lub nie masz odpowiednich uprawnień", "status" => "error"));
            $this->_forward('show', 'album', 'default', array('album_id'=>$oAlbum->id));
        }
        $oConfig = Zend_Registry::get('config_forms');
        $oForm = new Zend_Form($oConfig->photo);
        $oRequest = $this->getRequest();

        if ($oRequest->isPost())
        {
            if ($oForm->isValid($oRequest->getPost()))
            {

                try {

                    $oConnection = Zend_Registry::get('connection');
                    $oConnection->beginTransaction();
                    
                    // zapisanie nowego rekordu fotki
                    $oPhoto = new Photo();
                    $oPhoto->description = $oForm->getValue('description');
                    $oPhoto->album_id = $oAlbum->id;
                    $oPhoto->save();

                    $sPhotoDir = APPLICATION_PATH . "/../public/users_files/$oUser->id/$oAlbum->id";

                    if (file_exists($sPhotoDir) || mkdir($sPhotoDir, 0777, true))
                    {
                        // upload fotki
                        $oUpload = new Zend_File_Transfer_Adapter_Http();
                        $oUpload->setDestination($sPhotoDir);
                        $oUpload->receive();
     
                        // zmiana nazwy pliku z oryginalnej na id fotki
                        $aInfo = $oUpload->getFileInfo();
                        $oFilterFileRename = new Zend_Filter_File_Rename(array('target' => "$sPhotoDir/{$oPhoto->id}.".$this->_findExtension($aInfo['photo']['name']), 'overwrite' => true));
                        $oFilterFileRename->filter($oUpload->getFileName());

                        $oConnection->commit();

                        $this->_helper->_redirector->setGotoRoute(array('album_id'=>$oAlbum->id), 'album_show');
                    }
                    else
                    {
                        $oConnection->rollback();
                        $this->_helper->flashMessenger->addMessage(array("message" => "Nie udało się utworzyć katalogu dla zdjęć", "status" => "error"));
                    }
                } catch (Zend_File_Transfer_Exception $e) {
                    $oConnection->rollback();
                    $this->_helper->flashMessenger->addMessage(array("message" => $e->getMessage(), "status" => "error"));
                }
            }
        }
        $this->view->oForm = $oForm;
    }

    protected function _findExtension($sFilename)
    {
        $sFilename = strtolower($sFilename);
        $tmp = preg_split("[/\\.]", $sFilename);
        $n = count($tmp) - 1;
        $sExtension = $tmp[$n];
        return $sExtension;
    }

}
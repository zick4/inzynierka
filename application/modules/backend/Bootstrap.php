<?php
class Backend_Bootstrap extends Zend_Application_Module_Bootstrap
{

   protected function _initDoctype() {
      $this->bootstrap( 'view' );
      $view = $this->getResource( 'view' );

      $view->headTitle( 'Module Backend' );
//      $view->headLink()->appendStylesheet('/css/clear.css');
//      $view->headLink()->appendStylesheet('/css/main.css');
//      $view->headScript()->appendFile('/js/jquery.js');
      $view->doctype( 'XHTML1_STRICT' );
      //$view->navigation = $this->buildMenu();
   }
}
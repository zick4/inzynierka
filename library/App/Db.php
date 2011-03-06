<?php
class App_Db extends Zend_Db
{
   static public function factory($adapterName, $config = array())
   {
      $adapter = parent::factory($adapterName, $config);
      //$adapter->query("SET CLIENT_ENCODING TO 'utf8'");

      return $adapter;
   }
}
?>
<?php

/**
 * Adapter
 * 
 * @author Paweł Grzeszczak
 */
class App_Auth extends Zend_Auth
{
    /**
     * Returns an instance of Zend_Auth
     *
     * Singleton pattern implementation
     *
     * @return Zend_Auth Provides a fluent interface
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Dokonuje autoryzacji użytkownika
     *
     * @param array $aValues
     * @return true|throw new AutenticationException
     */
    public function process($aValues)
    {
        // Get our authentication adapter and check credentials
        $oAdapter = $this->_getAuthAdapter();
        $oAdapter->setIdentity($aValues['email']);
        $oAdapter->setCredential($aValues['password']);

//        $oAuth = Zend_Auth::getInstance();
        $result = $this->authenticate($oAdapter);
        if ($result->isValid())
        {

            $oUser = $oAdapter->getResultRowObject();
            $this->getStorage()->write($oUser);

            return true;
        }

        $aErrors = $result->getMessages();
        throw new AutenticationException($aErrors[0]);
    }
    /**
     * Zwraca adapter autoryzacyjny
     *
     * @return Zend_Auth_Adapter_DbTable
     */
    protected  function _getAuthAdapter()
    {

        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('users')
                ->setIdentityColumn('email')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('md5(CONCAT(?,salt))');


        return $authAdapter;
    }

}
class App_Auth_Exception extends Exception {}

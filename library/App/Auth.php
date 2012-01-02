<?php

/**
 * Adapter
 * 
 * @author Paweł Grzeszczak
 */
class App_Auth implements Zend_Auth_Adapter_Interface  
{
    /**
     * @var Doctrine_Table
     */
    private $_table;
    /**
     * @var string
     */
    private $_tableName = "User";

    /**
     * The field name which will be the identifier (username...)
     *
     * @var string
     */
    private $_identityCol = "email";

    /**
     * The field name which will be used for credentials (password...)
     *
     * @var string
     */
    private $_credentialCol = "password";

    /**
     * Actual identity value (my_all_known_username)
     *
     * @var string
     */
    private $_identity;

    /**
     * Actual credential value (my_secret_password)
     *
     * @var string
     */
    private $_credential;

    public function  __construct()
    {

        $this->_table = Doctrine_Manager::connection()->getTable($this->_tableName);
        $columnList = $this->_table->getColumnNames();
        //Check if the identity and credential are one of the column names...
        if (!in_array($this->_identityCol, $columnList) || !in_array($this->_credentialCol, $columnList)) {
            throw new Zend_Auth_Adapter_Exception("Invalid Column names are given as '{$this->_credentialCol}' and '{$this->_credentialCol}'");
        }
 
    }

    /**
     * @param string $i
     */
    public function setIdentity($i)
    {
        $this->_identity = $i;
    }

    /**
     * @param string $c
     */
    public function setCredential($c)
    {
        $this->_credential = $c;
    }

    /**
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        //FIXME: Check if this querying actually works or not...
        $result = $this->_table
            ->createQuery("dctrn_find")
            ->where("{$this->_credentialCol} = ?", $this->_credential)
            ->andWhere("{$this->_identityCol} = ?", $this->_identity)
            ->execute(array());
        // ok
        if (!empty($result[0]->id))
        {
            $oRes = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $result[0]);
        }
        // failure
        else
        {
            $oRes = new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null, array("Niepoprawny login lub hasło"));
        }
        return $oRes;
    }

}

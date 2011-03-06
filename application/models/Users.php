<?php
class Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';
    protected $_primary = 'user_id';
    protected $_sequence = true;

    public function init(){
	$this->loginSession = new Zend_Session_Namespace('login');
    }

    public function primary(){
    	return $this->_primary;
    }

    public function createUser($email, $password){


	if(!$email) return 0;
	if(!$password) return 0;

	// Algorytm SHA1, chyba, że ktoś ma lepszy pomysł
	$row = array(
			    'user_id'			=>  NULL,
			    'email'				=>	$email,
			    'password'		=>	sha1($password)
	);

	return $this->insert($row);
    }

    public function setLoginData($row){
		$this->loginSession->logged = true;
		$this->loginSession->userEmail = $row->email;
		$this->loginSession->uid = $row->user_id;
    }

    public function loginUser($email, $password, $checkPassword=-1){

		if($checkPassword === -1) {
			$checkPassword = $password;
		}
		
		$goodData = false;
	
		$authAdapter = new App_Auth_Adapter_DbTable(Zend_Registry::get('db'));

		$authAdapter
			->setTableName('users')
			->setIdentityColumn('email')
			->setCredentialColumn('password')
			->setCredentialTreatment('SHA1(?)')
			->setIdentity($email)
			->setCredential($password);

		$auth = Zend_Auth::getInstance();

		$result = $auth->authenticate($authAdapter);

		if ($result->isValid())
		{
			$user_session = $authAdapter->getResultRowObject();
			$auth->getStorage()->write($user_session);
			$this->setLoginData($user_session);
			return true;
		}

		return false;
    }

    public function getUserType($user_id){
        if (!is_int($user_id) || $user_id==0) {
            throw new Exception("parametr powinien być liczbą całkowitą większą od zera");
        }
        $db = Zend_Registry::get('db_offers');
        $select = $db->select()
                ->from(array('up'=>'user_type'), array('user_type_id', 'name'))
                ->join(array('u'=>'users'), 'up.user_type_id=u.user_type_id', array())
                ->where('u.user_id='.$user_id);

    	
    	$result = $db->fetchRow($select);
    	if($result) {
    		return $result;
    	}
    	else {
    		return false;
    	}
    }

    public function logoutUser(){
		$aktowka = new Utils_Aktowka();
		if (!empty($aktowka)) {
			$aktowka->przepiszNaSesje();
		}
		Zend_Auth::getInstance()->clearIdentity();
		$sessionLogin = new Zend_Session_Namespace('login');
		$sessionLogin->unsetAll();
		unset($sessionLogin);
	    unset($this->loginSession);
    }
    
    public function getUserByEmail($email) {
    	$select = $this->select();
    	
    	$select->where('email = ?', $email);
    	$result = $this->fetchRow($select);
    	if($result) {
    		return $result->toArray();
    	}
    	else {
    		return -1;
    	}
    }
}
?>

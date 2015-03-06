<?php
class Core_Model_User implements Zend_Acl_Role_Interface, Zend_Acl_Resource_Interface {
	
	/**
	 * Identifiant Utilisateur
	 *
	 * @var integer
	 */
	private $id;
	
	/**
	 * Identifiant Linkedin Utilisateur
	 *
	 * @var string
	 */
	private $linkedinId = null;
	
	/**
	 * Identifiant Viadeo Utilisateur
	 *
	 * @var string
	 */
	private $viadeoId = null;
	
	/**
	 * Login Utilisateur
	 *
	 * @var string
	 */
	private $login;
	
	/**
	 * Email Utilisateur
	 *
	 * @var string
	 */
	private $email;
	
	/**
	 * Password Utilisateur
	 *
	 * @var string
	 */
	private $password;
	
	/**
	 * Created Date
	 *
	 * @var string
	 */
	private $created;
	
	/**
	 * Returns the string identifier of the Role
	 *
	 * @return string
	 */
	public function getRoleId() {
		if ($this->getId () === null) {
			return 'guest';
		}
		if ($this->getId () == 1) {
			return 'root';
		}
		return 'other';
	}
	
	/**
	 * Returns the string identifier of the Resource
	 *
	 * @return string
	 */
	public function getResourceId() {
		return 'user';
	}
	
	/**
	 *
	 * @param integer $id        	
	 * @return Core_Model_User
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getLinkedinId() {
		return $this->linkedinId;
	}
	
	/**
	 *
	 * @param string $linkedinId
	 * @return Core_Model_User
	 */
	public function setLinkedinId($linkedinId) {
		$this->linkedinId = $linkedinId;
		return $this;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getViadeoId() {
		return $this->viadeoId;
	}
	
	/**
	 *
	 * @param string $viadeoId
	 * @return Core_Model_User
	 */
	public function setViadeoId($viadeoId) {
		$this->viadeoId = $viadeoId;
		return $this;
	}
	
	/**
	 *
	 * @param string $login        	
	 * @return Core_Model_User
	 */
	public function setLogin($login) {
		$this->login = $login;
		return $this;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getLogin() {
		return $this->login;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 *
	 * @param string $email        	
	 * @return Core_Model_User
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}
	
	/**
	 *
	 * @param string $password        	
	 * @return Core_Model_User
	 */
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * Set date created user
	 * @param $created 
	 * @return Core_Model_User
	 */
	public function setCreated($created = null) {
		
		if($created === null){
			$created = Zend_Date::now();
		} else {
			$created = new Zend_Date($created);
		}
		
		$this->created = $created;
		return $this;
	}
	
	/**
	 * @return Zend_Date
	 */
	public function getCreated() {
		return new $this->created;
	}
	
	/**
	 *
	 * @return array
	 */
	public function toArray() {
		return array (
				'id' => $this->getId (),
				'login' => $this->getLogin (),
				'email' => $this->getEmail (),
				'password' => $this->getPassword (),
				'created' => $this->getCreated()
		);
	}
}
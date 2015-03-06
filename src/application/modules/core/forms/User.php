<?php
class Core_Form_User extends Ip_Form {
	
	public function populate(Core_Model_User $user){
		
		$values = array(
				'username' => $user->getLogin(),
				'email' => $user->getEmail(),
				'password' => $user->getPassword()
		);
		
		parent::populate($values);
	}
	
	public function init() {
		$this->addElement ( 'text', 'username', array (
				'label' => 'Username',
				'validators' => array(
						array(
								'Db_NoRecordExists',
								false,
								array(
										'messages' => 'Account not autorized',	
										'table' => Core_Model_DbTable_User::TABLE_NAME,
										'field' => Core_Model_DbTable_User::COL_LOGIN
								)
						)
				),
				'required' => true 
		) );
		
		$this->addElement ( 'email', 'email', array (
				'label' => 'email',
				'required' => true 
		) );
		
		$this->addElement ( 'password', 'password', array (
				'label' => 'Password',
				'required' => true 
		) );
		
		$this->addElement ( 'password', 'repassword', array (
				'label' => 'Confirm password',
				'validators' => array (
						array (
								'Identical',
								false,
								array (
										'messages' => "Password not identical",
										'token' => 'password' 
								) 
						) 
				),
				'required' => true 
		) );
		
		$this->addElement ( 'submit', 'send', array (
				'label' => 'Create an account' 
		) );
	}
}
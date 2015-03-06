<?php

class Core_Form_Login extends Zend_Form
{
	public function init(){
		$this->addElement('text', 'username', array(
				'label' => 'Login',
				'required' => true
		));
		
		$this->addElement('password', 'password', array(
				'label' => 'Password',
				'required' => true
		));
		
		$this->addElement('submit', 'send', array(
				'label' => 'Sign in'
		));
	}
}
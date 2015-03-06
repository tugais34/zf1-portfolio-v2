<?php

class Core_Form_Contact extends Ip_Form
{
	public function init() {
		
		$this->addElement('text', 'name', array(
				'placeholder' => 'Nom & Prénom',
				'required' => true
		));
		
		$this->addElement('email', 'email', array(
				'placeholder' => 'Email',
				'required' => true
		));
		
		$this->addElement('textarea', 'message', array(
				'placeholder' => 'Votre message',
				'required' => true
		));
		
		$this->addElement('submit', 'send', array(
				'label' => 'Envoyer'
		));
	}
}
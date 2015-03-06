<?php

class Ip_Form_Element_Email extends Zend_Form_Element_Xhtml
{
	/**
	 * Default form view helper to use for rendering
	 * @var string
	 */
	public $helper = 'formEmail';
	
	public function __construct($spec, $options){
		
		$this->addValidator('EmailAddress');
		
		parent::__construct($spec, $options);
	}
}
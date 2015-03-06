<?php

class SitemapController extends Zend_Controller_Action
{
	
	public function init()
	{
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->addActionContext('index', 'xml')
									->initContext('xml');
	}
	
	
	public function indexAction(){
		
	}
}
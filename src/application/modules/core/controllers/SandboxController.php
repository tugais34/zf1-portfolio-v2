<?php

class SandboxController extends Zend_Controller_Action
{
	
	public function init(){
		
	}
	
	public function indexAction(){

	}
	
	public function listAction(){
		
	}
	
	public function restTestAction() {
		$this->_helper->viewRenderer->setNoRender();
		
		
// 		$client = new Zend_Rest_Client("http://www.project.dev/api/foo");
// 		$result = $client->get();
		
// 		if ($result->isSuccess()) {
// 			echo $result; // "Hello Davey, Good Day"
// 		}
	}
}
<?php 

class IndexController extends Zend_Controller_Action
{
	public function init(){
		
		// Ajout d'un Fichier javascript uniquement pour le controller
		// $this->view->jQuery()->addJavascriptFile("/js/dataTable.js");
		
	}
	
	
	public function indexAction(){
		$this->view->text = "Yop !!!";
		
// 		$writer = new Zend_Log_Writer_Firebug();
// 		$log = new Zend_Log($writer);
// 		$log->notice($this->getRequest());
		
	}
}
<?php

class Ip_Navigation_Page_Dynamic extends Zend_Navigation_Page_Mvc {
	
	protected $service;
	
	public function setService($service){
		$this->service = new $service();
	}

	public function getService(){
		return $this->service;
	}
	
	public function setLabel($label){
		
		$requestParam = Zend_Controller_Front::getInstance()
											->getRequest()->getParam('id');
		
		if(empty($requestParam)){
			return parent::setLabel($label);
		} 
		
		$model = $this->getService()->find($requestParam);
		
		$label = 'Modification de ' . $model->getLogin();
		
		return parent::setLabel($label);
	}
	
}
<?php

class UserController extends Zend_Controller_Action
{
	public function init(){
		
	}
	
	public function indexAction(){
		$this->forward('list');
	}
	
	public function listAction(){
		$service = new Core_Service_User();
		
		$this->view->users = $service->getUserList();
	}
	
	public function createAction(){
		$form = new Core_Core_Form_User();
		$this->view->form = $form;
		
		if($this->getRequest()->isPost()){
				$data = $this->getRequest()->getPost();
				if($form->isValid($data)){
						$service = new Core_Service_User();
						
						$user = new Core_Model_User();
						$user->setLogin($form->getValue('username'));
						$user->setEmail($form->getValue('email'));
						$user->setPassword($form->getValue('password'));
						
						$service->save($user);
				
						$this->view->priorityMessenger("Inscription réussi", 'success');
				}
		}
	}
	
	public function readAction(){
		
	}
	
	public function updateAction(){
		$form = new Core_Form_User();
		$this->view->form = $form;
		
		$id = $this->getRequest()->getParam('id');
		
		/* @var $acl Zend_Acl */
		$acl = Zend_Registry::get('Zend_Acl');
		
		$userAuth = Zend_Auth::getInstance()->getIdentity();
		
		if(empty($id)){
			$id = Zend_Auth::getInstance()->getIdentity()->getId();
		}

		$service = new Core_Service_User();
		
		if($user = $service->find($id)){
			
			if($acl->isAllowed($userAuth, $user, 'update')){
				$form->populate($user);
				$form->send->setLabel('Update an account');
			} else {
				$this->view->priorityMessenger("Accès refusée", 'error');
				$this->redirect($this->view->url(array(), 'indexIndex'));
			}
		}
		
		if($this->getRequest()->isPost()){
				$data = $this->getRequest()->getPost();
				if($form->isValid($data)){
					$service = new Core_Service_User();
					
					$user = new Core_Model_User();
					$user->setLogin($form->getValue('username'));
					$user->setEmail($form->getValue('email'));
					$user->setPassword($form->getValue('password'));
					
					$service->save($user);
					$this->view->priorityMessenger("Modification réussi", 'success');
				}
		}
	}
	
	public function deleteAction(){
		
	}
}
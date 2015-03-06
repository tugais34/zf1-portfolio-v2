<?php

class ContactController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction() {
		$form = new Form_Contact();
		$this->view->form = $form;
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			if($form->isValid($data)){
				$mail = new Zend_Mail();
				$mail->setSubject("Demande de contact");
				$mail->addTo("www.project.dev@gmail.com");
				
				$view = new Zend_View();
				$view->name = $form->getValue('name');
				$view->email = $form->getValue('email');
				$view->message = $form->getValue('message');
				$view->setScriptPath(APPLICATION_PATH . '/views/mails');

				$mail->setBodyHtml($view->render('contact.phtml'));
				$mail->setBodyText($view->render('contact.txt.phtml'));
				
				
				
				$mail->send();
			}
		}
		
	}
}
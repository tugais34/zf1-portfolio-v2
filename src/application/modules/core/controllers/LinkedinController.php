<?php
class LinkedinController extends Zend_Controller_Action
{
	protected $config;
	
	public function init(){
		$this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/linkedin.ini' , APPLICATION_ENV, true);
		$this->config->requestScheme = Zend_Oauth::REQUEST_SCHEME_HEADER;
	}
	
	public function indexAction(){
		$session = new Zend_Session_Namespace('Linkedin');
		
		/* @var $token Zend_Oauth_Token_Access */
		$token = $session->accessToken;
		
		/* Utilisation de l'api REST de linkedin */
		/* @var $client Zend_Oauth_Client */
		$client = $token->getHttpClient($this->config->toArray());
		
		// URL pour configuration de la requète GET sur linkedin
		// https://developer.linkedin.com/docs/fields
		$client->setUri("https://api.linkedin.com/v1/people/~:(id,first-name,email-address)");
		
		$client->setMethod(Zend_Http_Client::GET);
		
		try{
			
			$response = $client->request();
			$body = new Zend_Config_Xml($response->getBody());
			$this->_helper->viewRenderer->setNoRender();
			
			$idLinkedin = $body->get('id');

			/* Recherche de l'id Linkedin dans la BDD */
			
			/* Si existant création de Zend_Auth */
			
			/* Sinon validation de création de compte */
			
			
		} catch (Zend_Exception $e) {
			$this->view->priorityMessenger('Erreur connexion linkedin', 'danger');
			$this->redirect($this->view->url(array(), 'indexIndex'));
		} 
	}
	
	/**
	 * Url de retour de Linkedin
	 */
	public function callbackAction(){
		$this->_helper->viewRenderer->setNoRender();
	
		if($this->getRequest()->isGet()){
				
			$data = $this->getRequest()->getQuery();
			
			/* Gestion des erreurs*/
			if(isset($data['oauth_problem'])){
	
				$this->view->priorityMessenger($data['oauth_problem'], 'danger');
				$this->redirect($this->view->url(array(), 'indexIndex'));
				
			} elseif(isset($data['oauth_token']) && isset($data['oauth_verifier'])){
				/* Création du Token Access avec les données valider par linkedin*/
				$session = new Zend_Session_Namespace('Linkedin');
	
				$consumer = new Zend_Oauth_Consumer($this->config);
				$token = $consumer->getAccessToken($data, $session->requestToken);
	
				$session->accessToken = $token;
				
				/* Renvoie sur l'index pour gestion de l'utilisateur */
				$this->redirect($this->view->url(array(), 'linkedinIndex'));
				
			}
		}
		$this->redirect($this->view->url(array(), 'indexIndex'));
	}
}
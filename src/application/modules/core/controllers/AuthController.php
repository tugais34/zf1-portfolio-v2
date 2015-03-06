<?php

class AuthController extends Zend_Controller_Action
{
	protected $config;
	
	public function init(){
		if($this->getRequest()->getActionName() == 'linkedin' ){
			$this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/linkedin.ini', APPLICATION_ENV, true);
			$this->config->requestScheme = Zend_Oauth::REQUEST_SCHEME_HEADER;
		}
		
		if($this->getRequest()->getActionName() == 'viadeo' ){
			$this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/viadeo.ini', APPLICATION_ENV, true);
		}
	}
	
	public function indexAction(){
		/* Faire suivre l'action index vers l'action login */
		$this->forward('login');
	}
	
	public function loginAction(){
		
		$form = new Core_Form_Login();
		$session = new Zend_Session_Namespace("FAILURE_CREDENTIAL");
		
		if(isset($session->count) && $session->count >= 3){
			$form->addElement('captcha', 'captcha', array(
					'label'      => 'Merci de confirmer que vous êtes humain :',
          'required'   => true,
          'captcha'    => array(
              'captcha' => 'Image',
              'wordLen' => 8,
          		'width'   => 300,
          		'height'  => 100,
          		'font'    => ROOT_PATH . '/data/fonts/PoseiAOE.ttf',
          		'fontSize'=> 75,
          		'imgDir'=> SRC_PATH . '/public/img/captcha',
          		'imgUrl'=> '/img/captcha',
          		'dotNoiseLevel' => 0,
          		'lineNoiseLevel'=> 0,
              'timeout' => 300
          )
			));
		}
		
		$this->view->form = $form;
		
		/* Condition de test présence de requète POST*/
		if($this->getRequest()->isPost()){
			/* Récupération des paramètres */
			$data = $this->getRequest()->getPost();
			
			if($form->isValid($data)){
				
				/* Récupération des valeurs filtrées */
				$login = $form->getValue('username');
				$password = $form->getValue('password');
				
				/* Configuration de l'adapteur qui va servir à la connection. De type BDD */
				$adapter = new Zend_Auth_Adapter_DbTable();
				$adapter->setTableName(Core_Model_DbTable_User::TABLE_NAME)
								->setIdentityColumn(Core_Model_DbTable_User::COL_LOGIN)
								->setCredentialColumn(Core_Model_DbTable_User::COL_PASSWORD)
								->setIdentity($login)
								->setCredential($password);
				
				$auth = Zend_Auth::getInstance();
				
				/* Authentification de l'utilisateur via l'adapter par Zend_Auth*/
				$authResult = $auth->authenticate($adapter);
				
				if($authResult->isValid()){
					/* Si l'authentification est valide on réecri le stockage au format Core_Model_User*/
					$storage = $auth->getStorage();
					
					$sdtClass = $adapter->getResultRowObject(null, Core_Model_DbTable_User::COL_PASSWORD);
					
					$user = new Core_Model_User();
					$user->setId($sdtClass->{Core_Model_DbTable_User::COL_ID})
							 ->setLogin($sdtClass->{Core_Model_DbTable_User::COL_LOGIN});
					
					/* Réecriture du stockage avec $user Core_Model_User*/
					$storage->write($user);
					Zend_Session::namespaceUnset("FAILURE_CREDENTIAL");
				}
				
				/* Gestion des codes de retour*/
				if($authResult->getCode() == Zend_Auth_Result::SUCCESS){
					$this->view->priorityMessenger("Authentification réussi", 'success');
					$this->redirect($this->view->url(array(), 'indexIndex'));
				} else {
					throw new Zend_Auth_Exception($authResult->getCode());
				}
			}
		}
	}
	
	public function logoutAction(){
		$auth = Zend_Auth::getInstance();
		
		if($auth->hasIdentity()){
			$auth->clearIdentity();
		}
		$this->view->priorityMessenger("Déconnexion réussi", 'success');
		$this->redirect($this->view->url(array(), 'indexIndex'));
	}
	
	
	/**
	 * Création de la connection via Linkedin
	 */
	public function linkedinAction(){
		$this->_helper->viewRenderer->setNoRender();
		$session = new Zend_Session_Namespace('Linkedin');
		
		/* Créatoion de l'outil Oauth*/
		$consumer = new Zend_Oauth_Consumer($this->config);
		
		// Récupération de la requestToken générer par la config
		$token = $consumer->getRequestToken(array('scope' => 'r_fullprofile'));
			
		// Stokage de la requestToken
		$session->requestToken = $token;
			
		// Redirection vers le fournisseur
		$consumer->redirect();
	}
}
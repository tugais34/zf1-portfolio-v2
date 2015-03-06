<?php
/**
 * Les fonction commençant par _init* seront lancé automatiquement
 * Si le * correspond à un nom de resource Zend ou autres library (localisé dans Zend_Application_Resource_ )
 * la resource sera surchargé automatiquement. 
 * @author apprenant
 */
class Core_Bootstrap extends Zend_Application_Module_Bootstrap {
	
	/**
	 * La fonction qui initialise la traduction
	 * @return Zend_Translate
	 */
	public function _initTranslate() {
		/*
		 * Instance de Zend_Translate et configuration
		 */
		$translate = new Zend_Translate ( 'array', ROOT_PATH . '/data/i18n', 'auto', array (
				'scan' => Zend_Translate::LOCALE_DIRECTORY 
		) );
		
		/* Définition de la locale utilisé */
		$translate->setLocale ( Zend_Locale::findLocale () );
		
		Zend_Registry::set ( "Zend_Translate", $translate );
		
		return $translate;
	}
	/**
	 * La fonction qui initialise le router
	 */
	public function initRoutes() {
		
		/* Démarreur de FrontController */
		$this->bootstrap('FrontController');
		
		/* Démarreur de Router (Lecture de la config application.ini) */
		$this->bootstrap('Router');
		
		$this->_frontController = $this->getResource('FrontController');
		
		/* @var $router Zend_Controller_Router_Rewrite  */
		$router = $this->_frontController->getRouter();
	  
		/* Création d'une route pour la partie de l'url qui gére la langue*/
		$langRoute = new Zend_Controller_Router_Route(
			':lang/',
			array(
					'lang' => 'fr'
			),
			array('lang' => '[a-zA-Z]{2}') /* Requis 2 caractères */
	  );
		
		/* Création d'une route pour la partie de l'url par défaut /controller/action */
		$defaultRoute = new Zend_Controller_Router_Route(
			':controller/:action',
			array(
					'module' => 'default',
					'controller' => 'index',
					'action' => 'index'
			)	
	  );
		
		/* @TODO A décommenter quand toutes les routes sont crées */
// 		foreach ($router->getRoutes() as $routeName => $route){
// 			$route = $langRoute->chain($route);
// 			$router->addRoute($routeName, $route);
// 		}

		$router->addRoute('langRoute', $langRoute);
		
		/* @TODO A commenter quand toutes les routes sont crées*/ 
		$defaultRoute = $langRoute->chain($defaultRoute);
		$router->addRoute('defaultRoute', $defaultRoute);
	}
	
	public function _initCustomResourceType(){
	
		$loader = $this->getResourceLoader();
		$loader->addResourceType('assert', 'asserts', 'Assert');
		
	}
	
	public function _initAcl(){
		$acl = new Zend_Acl();
		
		$acl->addRole('guest');
		$acl->addRole('other', 'guest');
		$acl->addRole('root', 'other');
		
		
		$acl->addResource('CRUD');
		$acl->addResource('skill', 'CRUD');
		$acl->addResource('progress', 'CRUD');
		$acl->addResource('user', 'CRUD');
		
		/* Resources CONTROLLER */
		$acl->addResource('auth');
		$acl->addResource('index');
		$acl->addResource('contact');
		$acl->addResource('linkedin');
		$acl->addResource('sandbox');
		$acl->addResource('api');
		
		$acl->allow('guest', 'sandbox', 'rest-test');
		
		$acl->allow('guest', 'auth', 'login');
		$acl->allow('guest', 'auth', 'linkedin');
		$acl->allow('guest', 'index', 'index');
		$acl->allow('guest', 'contact', 'index');
		$acl->allow('guest', 'api', 'index');
		
		$acl->allow('guest', 'linkedin', 'index');
		$acl->allow('guest', 'linkedin', 'callback');
		
		$acl->allow('guest', 'CRUD', array('read', 'list'));
		$acl->allow('guest', 'user', 'create');
		
		$acl->deny('other', 'auth', 'login');
		$acl->allow('other', 'auth', 'logout');
				
		$acl->allow('other', 'CRUD', 'create');
		$acl->deny('other', 'user', 'create');
		
		$acl->allow('other', 'CRUD', array('update', 'delete'), new Core_Assert_Owner());
		$acl->allow('root', 'CRUD', array('create', 'update', 'delete'));
			
		Zend_Registry::set("Zend_Acl", $acl);
	}
	
	
	/*
	protected function _initZFDebug()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('ZFDebug');
		
		$this->bootstrap('frontController');
		// @var $frontController Zend_Controller_Front 
		$frontController = $this->getResource('frontController');
		
		$this->bootstrap('multidb');
		
		// @var $db Zend_Application_Resource_Multidb 
		$db = $this->getPluginResource('multidb');
		
		$options = array(
				'plugins' => array('Variables',
                       'Html',
											 'Database' => array('adapter' => $db->getDefaultDb()),
											 'File' => array('basePath' => '/var/www/project'),
											 //'Memory',
											 //'Time',
											 //'Registry',
						//'Cache' => array('backend' => $cache->getBackend()),
				'Exception')
		);
		$debug = new ZFDebug_Controller_Plugin_Debug($options);
	
		$frontController->registerPlugin($debug);
	}
	*/
}
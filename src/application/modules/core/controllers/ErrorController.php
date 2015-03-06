<?php 

class ErrorController extends Zend_Controller_Action
{
 		public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
 
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
 
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;
            default:
            	 switch (true) {
            	 	case $errors->exception instanceof Zend_Auth_Exception:
	            	 		// Auth error
	            	 		$this->getResponse()->setHttpResponseCode(500);
	            	 		$this->view->message = 'Authentification error';
	            	 		switch ($errors->exception->getMessage())
	            	 		{
	            	 			case Zend_Auth_Result::FAILURE:
	            	 			case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
	            	 				$this->view->message = "Echec d'authentification";
	            	 				$session = new Zend_Session_Namespace("FAILURE_CREDENTIAL");
	            	 				if(!isset($session->count)){
	            	 					$session->count = 0;
	            	 				}
	            	 				$session->count++;
	            	 				break;
	            	 			case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS:
	            	 			case Zend_Auth_Result::FAILURE_UNCATEGORIZED:
	            	 				$priority = Zend_Log::CRIT;
	            	 				$this->view->message = "Echec d'authentification";
	            	 				break;
	            	 			case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
	            	 				$this->view->message = "Va t'inscrire";
	            	 				break;
	            	 		}
            	 		break;
            	 	case $errors->exception instanceof Zend_Db_Exception:
	            	 		// Db error
            	 		  $priority = Zend_Log::CRIT;
	            	 		$this->getResponse()->setHttpResponseCode(500);
	            	 		$this->view->message = 'Database error';
            	 		break;
            	 	default:
	            	 		// application error
            	 		$priority = Zend_Log::EMERG;
	            	 		$this->getResponse()->setHttpResponseCode(500);
	            	 		$this->view->message = 'Application error';
            	 		break;
            	 }
            	break;
        }
        
        if($log = $this->getLog()){
        	$log->log($this->view->message, $priority, $errors->exception);
        	$log->log('Request Parameters', $priority, $errors->request->getParams());
        }
        
        $this->view->env = APPLICATION_ENV;

        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }
    
    /**
     * Récupération de l'outil Log
     * @return Zend_Log 
     */
    public function getLog(){
    	
    	/* @var $bootstrap Bootstrap*/
    	$bootstrap = $this->getInvokeArg('bootstrap');
    	if( ! $bootstrap->hasResource('log')){
    		return false;
    	}
    	
    	$log = $bootstrap->getResource('log');
    	return $log;
    }
}
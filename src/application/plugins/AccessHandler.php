<?php

class Plugin_AccessHandler extends Zend_Controller_Plugin_Abstract
{
    protected $auth;
    
    protected $userAuth;
    
    public function init(){
        $this->auth = Zend_Auth::getInstance();
        if($this->auth->hasIdentity()){
            $this->userAuth = $this->auth->getIdentity();
        } else {
            $this->userAuth = new Core_Model_User();
        }
    }
    
    public function preDispatch($request){
        
        $errors = $request->getParam('error_handler');
        if(! $errors || ! $errors instanceof ArrayObject){
            $this->init();
            $this->_handleAccess($request);
        }
    }
    
    public function _handleAccess (Zend_Controller_Request_Abstract $request){
        
        /* @var $acl Zend_Acl */
        $acl = Zend_Registry::get('Zend_Acl');

        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
        if(! $acl->has($controller)){
            throw new Zend_Controller_Dispatcher_Exception('Resource ' . $controller . ' not found');
        }
        
        if(! $acl->isAllowed($this->userAuth, $controller, $action)){
            throw new Zend_Acl_Exception($this->userAuth->getRoleId() . ' -- ' . $controller . ' -- ' . $action);
        }
    }
}
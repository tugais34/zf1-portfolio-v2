<?php
class Core_Assert_Owner implements Zend_Acl_Assert_Interface {
	
	
	public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null) {
		
		if ("Zend_Acl_Resource" != get_class ( $resource )) {
			if ($role->getId () !== $resource->getId ()) {
				$view = new Zend_View();
				//$view->priorityMessenger("Vous n'êtes pas propriétaire", "danger");
				
				return false;
			}
		}
		
		return true;
	}
	
}
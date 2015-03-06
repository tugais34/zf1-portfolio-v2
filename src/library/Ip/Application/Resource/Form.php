<?php

/**
 * Création d'une resource Form
 * Ceci permet de configurer un élement depuis un fichier de configuration
 * et au démmarage de l'application.
 * 
 **/
class Ip_Application_Resource_Form extends Zend_Application_Resource_ResourceAbstract {
	
	public function init() {
		
		Ip_Form::$prefixPaths = $this->getOptions ()['addPrefixPath'];
	
	}
}
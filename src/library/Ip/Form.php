<?php

/**
 * Surcharge de la classe Zend_Form
 */
class Ip_Form extends Zend_Form {
	
	/*
	 * Création d'une valeur statique contenant la configuration du prefixPath
	 */
	public static $prefixPaths = array ();
	public function __construct() {
		if (! empty ( self::$prefixPaths )) {
			
			foreach ( self::$prefixPaths as $type => $options ) {
				
				foreach ( $options as $namespace => $path ) {
					$this->addPrefixPath ( $namespace, $path, $type );
				}
			}
		}
		
		parent::__construct ();
	}
}
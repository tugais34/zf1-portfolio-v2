<?php

/** Zend_Application_Resource_ResourceAbstract.php */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

class Ip_Application_Resource_Zfdebug extends Zend_Application_Resource_ResourceAbstract
{
    protected $_localOptions;
    protected $_view;

    public function init()
    {

        $this->_localOptions = $this->getOptions();

        # Returns view so bootstrap will store it in the registry
        if (null === $this->_view) {
            $this->_view = new Zend_View($this->_localOptions);
        }

        if( !$this->_localOptions['run'] ) 
            return;
        else
            unset($this->_localOptions['run']);

        foreach ($this->_localOptions['plugins'] as $name => $enabled) {
            if($enabled == TRUE)
                $this->_localOptions['plugins'][$name] = array();
            else
                unset($this->_localOptions['plugins'][$name]);
        }

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');
        $options = $this->_localOptions;

        # Instantiate the database adapter and setup the plugin.
        # Alternatively just add the plugin like above and rely on the autodiscovery feature.
        $bootstrap = $this->getBootstrap();
        if ($bootstrap->hasPluginResource('db')) {
            $bootstrap->bootstrap('db');
            $db = $bootstrap->getPluginResource('db')->getDbAdapter();
            $options['plugins']['Database']['adapter'] = $db;
        }

        # Setup the file plugin
        $options['plugins']['File']['base_path'] = APPLICATION_PATH;
        $options['plugins']['File']['library'] = $bootstrap->getOptions()['autoloaderNamespaces'];

        # Setup the cache plugin
//         if ($bootstrap->hasPluginResource('cacheManager')) {
//             $bootstrap->bootstrap('cacheManager');
//             $cache = $bootstrap->getPluginResource('cacheManager')->getOptions();
//             $options['plugins']['Cache']['backend'] = $cache['frontcore']['backend'];
//         }

        # Setup the doctrine2 plugin
        if ($bootstrap->hasPluginResource('doctrine') && isset($this->_localOptions['Doctrine2'])) {
            $bootstrap->bootstrap('doctrine');
            $doctrine = $bootstrap->getPluginResource('doctrine')->getContainer()->getEntityManager();
            $options['plugins']['Doctrine2']['entityManagers'] = array($doctrine);
        }

        $debug = new Ip_Controller_Plugin_Debug($options);

        $bootstrap->bootstrap('frontController');
        $frontController = $bootstrap->getResource('frontController');
        $frontController->registerPlugin($debug);
    }


}
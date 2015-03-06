#!/usr/bin/php
<?php
/**
 * Script pour créer et charger la base
 */
/*
 * Install de sqlite sur debian :
 * $ sudo apt-get install php5-sqlite
 */
// Initialise les chemin vers l'application et l'autoload
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('SRC_PATH', ROOT_PATH . DS . 'src');
define('APPLICATION_PATH', SRC_PATH . DS . 'application');

if (! file_exists(ROOT_PATH . '/vendor/autoload.php')) {
    die('Veuillez lancer la commande "composer install" pour initialiser cette application');
}

require_once ROOT_PATH . DS . 'vendor' . DS . 'autoload.php';
Zend_Loader_Autoloader::getInstance();

// Definit des options CLI
$getopt = new Zend_Console_Getopt(array(
    'withdata|w' => 'Load database with sample data',
    'env|e-s' => 'Application environment for which to create database (defaults to development)',
    'help|h' => 'Help -- usage message'
));

try {
    $getopt->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    // Mauvaises options passées: afficher l'aide
    echo $e->getUsageMessage();
    return false;
}

// Si l'aide est demandée, l'afficher
if ($getopt->getOption('h')) {
    echo $getopt->getUsageMessage();
    return true;
}

// Initialise des valeurs selon la présence ou absence d'options CLI
$withData = $getopt->getOption('w');
$env = $getopt->getOption('e');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (null === $env) ? 'development' : $env);

// Initialise Zend_Application
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');

// Initialise et récupère la ressoucre DB
$bootstrap = $application->getBootstrap();
$bootstrap->bootstrap('multidb');

$dbAdapter = $bootstrap->getResource('multidb')->getDb();

// Informons l'utilisateur de ce qui se passe (nous créons une base de données ici)
if ('testing' != APPLICATION_ENV) {
    echo 'Writing Database Project in (control-c to cancel): ' . PHP_EOL;
    for ($x = 5; $x > 0; $x --) {
        echo $x . "\r";
        sleep(1);
    }
}

// Vérifions si un fichier pour la base existe déja
$options = $dbAdapter->getConfig();
$dbFile = $options['dbname'];

if (file_exists($dbFile)) {
    unlink($dbFile);
}

// Chargement du fichier de la base de données.
try {
    $schemaSql = file_get_contents(dirname(__FILE__) . '/schema.sqlite.sql');
    // utilise la connexion directement pour charger le sql
    $dbAdapter->getConnection()->exec($schemaSql);
    chmod($dbFile, 0666);
    
    if ('testing' != APPLICATION_ENV) {
        echo PHP_EOL;
        echo 'Database Created';
        echo PHP_EOL;
    }
    
    if ($withData) {
        $dataSql = file_get_contents(dirname(__FILE__) . '/data.sqlite.sql');
        // utilise la connexion directement pour charger le sql
        $dbAdapter->getConnection()->exec($dataSql);
        if ('testing' != APPLICATION_ENV) {
            echo 'Data Loaded.';
            echo PHP_EOL;
        }
    }
} catch (Exception $e) {
    echo 'AN ERROR HAS OCCURED:' . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    return false;
}

// Ce script sera lancé depuis la ligne de commandes
return true;
<?php
error_reporting(E_ALL | E_STRICT);

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../vendor/zend/zendframework/library'),
    realpath(APPLICATION_PATH . '/models') ,
    get_include_path()
)));

require_once realpath(APPLICATION_PATH . '/../vendor/autoload.php');
define('USER_FILES_PUBLIC_DIR', "/users_files");
define('PUBLIC_DIR', APPLICATION_PATH.'/../public');

require_once 'Zend/Application.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$application->getBootstrap()->bootstrap('doctrine');

$cli = new Doctrine_Cli($application->getOption('doctrine'));
$cli->run($_SERVER['argv']);

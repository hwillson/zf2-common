<?php

putenv('APPLICATION_ENV=development');
putenv('COMMON_LIBRARY_PATH=/www/zf2_common');

$moduleName = 'Zf2Common';

$additionalModulePaths = null;
$moduleDependencies = null;

$rootPath = realpath(dirname(__DIR__));
$testsPath = "$rootPath/tests";

$zf2Path =
  getenv('COMMON_LIBRARY_PATH') . '/vendor/zendframework/zendframework/library';
$path = array($zf2Path, get_include_path());
set_include_path(implode(PATH_SEPARATOR, $path));

require_once  'Zend/Loader/AutoloaderFactory.php';
require_once  'Zend/Loader/StandardAutoloader.php';

use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;

// Setup autloader
AutoloaderFactory::factory(
  array(
    'Zend\Loader\StandardAutoloader' => array(
      StandardAutoloader::AUTOREGISTER_ZF => true,
      StandardAutoloader::ACT_AS_FALLBACK => false,
      StandardAutoloader::LOAD_NS => array(
        'Zend' => $zf2Path . '/Zend',
        'Zf2Common' => __DIR__ . '/../src/Zf2Common',
        'Zf2CommonUnitTest' => __DIR__ . '/unit',
        'Zf2CommonIntegrationTest' => __DIR__ . '/integration'
      ),
    )
  )
);

$modulePaths =
  array('Zf2Common' => getenv('COMMON_LIBRARY_PATH')
    . '/modules/zf2_module_common');
if (isset($additionalModulePaths)) {
  $modulePaths = array_merge($modulePaths, $additionalModulePaths);
}

// Load this module and defined dependencies
$modules = array($moduleName);
if (isset($moduleDependencies)) {
  $modules = array_merge($modules, $moduleDependencies);
}

$listenerOptions =
  new Zend\ModuleManager\Listener\ListenerOptions(array('module_paths' => $modulePaths));
$defaultListeners =
  new Zend\ModuleManager\Listener\DefaultListenerAggregate($listenerOptions);
$sharedEvents = new Zend\EventManager\SharedEventManager();
$moduleManager = new \Zend\ModuleManager\ModuleManager($modules);
$moduleManager->getEventManager()->setSharedManager($sharedEvents);
$moduleManager->getEventManager()->attachAggregate($defaultListeners);
$moduleManager->loadModules();

// A locator will be set to this class if available
$moduleTestCaseClassname =
  '\\'.$moduleName.'UnitTest\\Framework\\AbstractBasePHPUnitTestCase';

if (method_exists($moduleTestCaseClassname, 'setLocator')) {

  $config = $defaultListeners->getConfigListener()->getMergedConfig();

  $di = new \Zend\Di\Di;
  $di->instanceManager()->addTypePreference('Zend\Di\LocatorInterface', $di);

  if (isset($config['di'])) {
    $diConfig = new \Zend\Di\Config($config['di']);
    $diConfig->configure($di);
  }

  $routerDiConfig = new \Zend\Di\Config(
    array(
      'definition' => array(
        'class' => array(
          'Zend\Mvc\Router\RouteStackInterface' => array(
            'instantiator' => array(
              'Zend\Mvc\Router\Http\TreeRouteStack',
              'factory'
            ),
          ),
        ),
      ),
    )
  );
  $routerDiConfig->configure($di);

  call_user_func_array($moduleTestCaseClassname.'::setLocator', array($di));
}

// When this is in global scope, PHPUnit catches exception:
// Exception: Zend\Stdlib\PriorityQueue::serialize() must return a string or NULL
unset($moduleManager, $sharedEvents);

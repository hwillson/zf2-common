<?php

namespace Zf2Common;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Log\LoggerAwareInterface;
use Zend\EventManager\Event;

/**
 * Module loading for zf2_common.
 */
class Module implements AutoloaderProviderInterface {

  public function getAutoloaderConfig() {
    return array(
      'Zend\Loader\ClassMapAutoloader' => array(
        __DIR__ . '/autoload_classmap.php',
      ),
      'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
          __NAMESPACE__ =>
            __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__)
        ),
      ),
    );
  }

  public function getConfig() {
    return include __DIR__ . '/config/module.config.php';
  }

  public function getServiceConfig() {
    return array(
      'initializers' => array(
        'logger' => function($service, $serviceManager) {
          if ($service instanceof Zend\Log\LoggerAwareInterface) {
            $logger = $serviceManager->get('Zend\Log');
            $service->setLogger($logger);
          }
        }
      ),
      'factories' => array(
        'Zf2Common\I18n\Translator\Loader\DatabaseTranslationLoader' =>
          function($serviceManager) {
            $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
            return
              new \Zf2Common\I18n\Translator\Loader\DatabaseTranslationLoader(
                $dbAdapter);
        }
      )
    );
  }

  public function onBootstrap(Event $event) {
    $eventManager = $event->getApplication()->getEventManager();
    $moduleRouteListener = new ModuleRouteListener();
    $moduleRouteListener->attach($eventManager);
  }

}

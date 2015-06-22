<?php
namespace RubedoMongoDB;

use Rubedo\Services\Events;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use Rubedo\Collection\AbstractCollection;
use Rubedo\Collection\WorkflowAbstractCollection;
use Rubedo\Services\Manager;



class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {

        $eventManager = $e->getApplication()->getEventManager();
        Events::setEventManager($eventManager);
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $wasFiltered = AbstractCollection::disableUserFilter();
        $mappingsService = Manager::getService('MongoDBMappings');
        AbstractCollection::disableUserFilter($wasFiltered);

        $eventManager->attach(WorkflowAbstractCollection::POST_PUBLISH_COLLECTION, array(
            $mappingsService,
            'syncContentEvent'
        ));
    }
}

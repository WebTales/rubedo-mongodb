<?php
return array(

    /*
      * BO extension definition
      */
    'appExtension' => array(
        'rubedomongodb' => array(
            'basePath' => realpath(__DIR__ . '/../app-extension') . '/rubedomongodb',
            'definitionFile' => realpath(__DIR__ . '/../app-extension') . '/rubedomongodb/rubedomongodb.json'
        )
    ),

    /*
     * Service definitions
     */
    'service_manager' => array(
        'invokables' => array(
            'RubedoMongoDB\\Collection\\MongoDBMappings' => 'RubedoMongoDB\\Collection\\MongoDBMappings',
        ),
        'aliases' => array(
            'API\\Collection\\MongoDBMappings' => 'RubedoMongoDB\\Collection\\MongoDBMappings',
            'MongoDBMappings' => 'RubedoMongoDB\\Collection\\MongoDBMappings',
        ),
    ),

    /*
     * Backoffice controller definitions
     */
    'controllers' => array(
        'invokables' => array(
            'RubedoMongoDB\\Backoffice\\Controller\\MongodbMappings' => 'RubedoMongoDB\\Backoffice\\Controller\\MongodbMappingsController',
        ),
    ),

   /*
    *  Backoffice route definitions
    */
    'router' => array (
        'routes' => array(
            'mongodb-mappings' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/backoffice/mongodb-mappings',
                    'defaults' => array(
                        '__NAMESPACE__' => 'RubedoMongoDB\\Backoffice\\Controller',
                        'controller' => 'mongodb-mappings',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:action]',
                            '__NAMESPACE__' => 'RubedoMongoDB\\Backoffice\\Controller',
                            'constraints' => array(
                                'controller' => 'mongodb-mappings',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),
        ),
    ),

);
<?php
/**
 * Rubedo -- ECM solution
 * Copyright (c) 2014, WebTales (http://www.webtales.fr/).
 * All rights reserved.
 * licensing@webtales.fr
 *
 * Open Source License
 * ------------------------------------------------------------------------------------------
 * Rubedo is licensed under the terms of the Open Source GPL 3.0 license.
 *
 * @category   Rubedo
 * @package    Rubedo
 * @copyright  Copyright (c) 2012-2014 WebTales (http://www.webtales.fr)
 * @license    http://www.gnu.org/licenses/gpl.html Open Source GPL 3.0 license
 */
namespace RubedoMongoDB\Collection;
use Rubedo\Collection\AbstractCollection;
use Rubedo\Services\Manager;
use WebTales\MongoFilters\Filter;
use Zend\Debug\Debug;
use Zend\EventManager\EventInterface;



class MongoDBMappings extends AbstractCollection
{

    public function __construct()
    {
        $this->_collectionName = 'MongoDBMappings';
        parent::__construct();
    }

    protected $_indexes = array(
        array(
            'keys' => array(
                'contentTypeId' => 1,
                'active' => 1
            ),
            'options' => array(
                'unique' => true
            )
        ),

    );

    public function syncContentEvent(EventInterface $e)
    {
        $data = $e->getParam('data', array());
        $content = Manager::getService("Contents")->findById($data['id'], true, false);
        $mappingFilter = Filter::factory();
        $mappingFilter->addFilter(Filter::factory("Value")->setName("contentTypeId")->setValue($content["typeId"]));
        $mappingFilter->addFilter(Filter::factory("Value")->setName("active")->setValue(true));
        $mapping = $this->findOne($mappingFilter);
        if ($mapping) {
            $payload=array();
            foreach($mapping["fieldMappings"] as $rubedoField => $externalField){
                if($externalField&&$externalField!=""&&isset($content["fields"][$rubedoField])){
                    $payload[$externalField]=$content["fields"][$rubedoField];
                }
            }
            $connection = new \MongoClient($mapping["connexionString"]);
            $db = $connection->selectDB($mapping["databaseName"]);
            $collection = $db->selectCollection($mapping["collectionName"]);
            $collection->update(array("rubedoContentId"=>(string)$content["id"]),array('$set'=>$payload),array("upsert"=>true,"w"=>0));

        }

    }

}

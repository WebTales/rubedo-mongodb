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
use Zend\EventManager\EventInterface;



class MongoDBMappings extends AbstractCollection
{

    protected $otherLocalizableFields = array('text', 'summary');

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
            $payload = array();
            foreach ($mapping["fieldMappings"] as $rubedoField => $externalField) {
                if ($externalField && $externalField != "" && isset($content["fields"][$rubedoField])) {
                    $payload[$externalField] = $content["fields"][$rubedoField];
                }
            }
            $connection = new \MongoClient($mapping["connexionString"]);
            $db = $connection->selectDB($mapping["databaseName"]);
            $collection = $db->selectCollection($mapping["collectionName"]);
            $collection->update(array("rubedoContentId" => (string)$content["id"]), array('$set' => $payload), array("upsert" => true, "w" => 0));
        }
    }

    public function syncContentTypeUp($mappingId){
        $mapping = $this->findById($mappingId);
        $contents=Manager::getService("Contents")->getByType($mapping["contentTypeId"]);
        $connection = new \MongoClient($mapping["connexionString"]);
        $db = $connection->selectDB($mapping["databaseName"]);
        $collection = $db->selectCollection($mapping["collectionName"]);
        foreach($contents["data"] as $content){
            $payload=array();
            foreach($mapping["fieldMappings"] as $rubedoField => $externalField){
                if($externalField&&$externalField!=""&&isset($content["fields"][$rubedoField])){
                    $payload[$externalField]=$content["fields"][$rubedoField];
                }
            }
            $collection->update(array("rubedoContentId"=>(string)$content["id"]),array('$set'=>$payload),array("upsert"=>true,"w"=>0));
        }
    }

    public function syncContentTypeDown($mappingId,$lang){
        $mapping = $this->findById($mappingId);
        $type=Manager::getService("ContentTypes")->findById($mapping["contentTypeId"]);
        $connection = new \MongoClient($mapping["connexionString"]);
        $db = $connection->selectDB($mapping["databaseName"]);
        $collection = $db->selectCollection($mapping["collectionName"]);
        $unsyncedExternalContents=$collection->find(array("rubedoContentId"=>array('$exists'=>false)));
        $contentsCollection=Manager::getService("Contents");
        foreach ($unsyncedExternalContents as $unsyncedDoc){
            $newContent=array(
                "typeId"=>$mapping["contentTypeId"],
                "fields"=>array(),
                "taxonomy"=>array(),
                "status"=>"published",
                "target"=>array("global"),
                "online"=>true,
                "startPublicationDate"=>"",
                "endPublicationDate"=>"",
                "nativeLanguage"=>$lang,
                "i18n"=>array(
                    $lang=>array()
                )
            );
            foreach($mapping["fieldMappings"] as $rubedoField => $externalField){
                if($externalField&&$externalField!=""&&isset($unsyncedDoc[$externalField])){
                    $newContent["fields"][$rubedoField]=$unsyncedDoc[$externalField];
                }
            }
            if (isset($newContent["fields"]["text"])){
                $newContent["text"]=$newContent["fields"]["text"];
            }
            $newId= new \MongoId();
            $collection->update(array("_id"=>$unsyncedDoc["_id"]),array('$set'=>array("rubedoContentId"=>(string)$newId)));
            $newContent['i18n'][$lang]['fields'] = $this->localizableFields($type, $newContent['fields']);
            $newContent['_id']=$newId;
            $createdContent=$contentsCollection->create($newContent, array(), false);

        }
    }

    protected function localizableFields($type, $fields)
    {
        $existingFields = array();
        foreach ($type['fields'] as $field) {
            if ($field['config']['localizable']) {
                $existingFields[] = $field['config']['name'];
            }
        }
        foreach ($fields as $key => $value) {
            unset($value); //unused
            if (!(in_array($key, $existingFields) || in_array($key, $this->otherLocalizableFields))) {
                unset ($fields[$key]);
            }
        }
        return $fields;
    }


}

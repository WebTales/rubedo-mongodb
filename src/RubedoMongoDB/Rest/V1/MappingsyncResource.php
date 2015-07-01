<?php
/**
 * Rubedo -- ECM solution
 * Copyright (c) 2015, WebTales (http://www.webtales.fr/).
 * All rights reserved.
 * licensing@webtales.fr
 *
 * Open Source License
 * ------------------------------------------------------------------------------------------
 * Rubedo is licensed under the terms of the Open Source GPL 3.0 license.
 *
 * @category   Rubedo
 * @package    Rubedo
 * @copyright  Copyright (c) 2012-2015 WebTales (http://www.webtales.fr)
 * @license    http://www.gnu.org/licenses/gpl.html Open Source GPL 3.0 license
 */

namespace RubedoMongoDB\Rest\V1;

use Rubedo\Services\Manager;
use RubedoAPI\Entities\API\Definition\FilterDefinitionEntity;
use RubedoAPI\Entities\API\Definition\VerbDefinitionEntity;
use RubedoAPI\Rest\V1\AbstractResource;
use RubedoAPI\Exceptions\APIRequestException;
use Rubedo\Collection\AbstractLocalizableCollection;
use Rubedo\Collection\AbstractCollection;



class MappingsyncResource extends AbstractResource
{

    protected $otherLocalizableFields = array('text', 'summary');


    public function __construct()
    {
        parent::__construct();
        $this
            ->definition
            ->setName('Mapping Sync')
            ->setDescription('Sync contents using mapping')
            ->editVerb('post', function (VerbDefinitionEntity &$entity) {
                $entity
                    ->setDescription('Inform Rubedo of content change in external database')
                    ->addInputFilter(
                        (new FilterDefinitionEntity())
                            ->setDescription('Id of mapping')
                            ->setKey('mappingId')
                            ->setFilter('\MongoId')
                            ->setRequired()
                    )->addInputFilter(
                        (new FilterDefinitionEntity())
                            ->setDescription('Id of changed content in external database')
                            ->setKey('itemId')
                            ->setFilter('\MongoId')
                            ->setRequired()
                    );
            });
    }

    public function postAction($params)
    {
        $mapping = Manager::getService("MongoDBMappings")->findById($params["mappingId"]);
        if(!$mapping){
            throw new APIRequestException('Mapping not found', 404);
        }
        $connection = new \MongoClient($mapping["connexionString"]);
        $db = $connection->selectDB($mapping["databaseName"]);
        $collection = $db->selectCollection($mapping["collectionName"]);
        $unsyncedDoc=$collection->findOne(array("_id"=>$params["itemId"]));
        if(!$unsyncedDoc){
            throw new APIRequestException('External content not found', 404);
        }
        $wasFiltered = AbstractCollection::disableUserFilter();

        AbstractLocalizableCollection::setIncludeI18n(true);
        $type=Manager::getService("ContentTypes")->findById($mapping["contentTypeId"]);
        $lang=$params['lang']->getLocale();
        if (!isset($unsyncedDoc["rubedoContentId"])||!$internalContent=Manager::getService("Contents")->findById($unsyncedDoc["rubedoContentId"], false, false)){
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
            $createdContent=$this->getContentsCollection()->create($newContent, array(), false);
        } else {
            foreach($mapping["fieldMappings"] as $rubedoField => $externalField){
                if($externalField&&$externalField!=""&&isset($unsyncedDoc[$externalField])){
                    $internalContent["fields"][$rubedoField]=$unsyncedDoc[$externalField];
                }
            }
            $internalContent['i18n'][$lang]['fields'] = $this->localizableFields($type, $internalContent['fields']);
            $this->getContentsCollection()->update($internalContent, array(), false);
        }
        AbstractCollection::disableUserFilter($wasFiltered);
        return (array("success"=>true));

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

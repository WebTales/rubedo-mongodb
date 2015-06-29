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
namespace RubedoMongoDB\Backoffice\Controller;

use Rubedo\Services\Manager;
use Rubedo\Backoffice\Controller\DataAccessController;


/**
 * Controller providing CRUD API for the Orders JSON
 *
 * Receive Ajax Calls for read & write from the UI to the Mongo DB
 *
 *
 * @author adobre
 * @category Rubedo
 * @package Rubedo
 *
 */
class MongodbMappingsController extends DataAccessController
{

    public function __construct()
    {
        parent::__construct();
        // init the data access service
        $this->_dataService = Manager::getService('MongoDBMappings');
    }

    public function syncContentTypeUpAction(){
        $mappingId=$this->params()->fromPost('mappingId',null);
        if (!$mappingId){
            $this->_returnJson(array(
                "success"=>false,
                "message"=>"Missing required mappingId param"
            ));
        }
        $this->_dataService->syncContentTypeUp($mappingId);
        return $this->_returnJson(array(
            "success"=>true,
        ));
    }

    public function syncContentTypeDownAction(){
        $mappingId=$this->params()->fromPost('mappingId',null);
        if (!$mappingId){
            $this->_returnJson(array(
                "success"=>false,
                "message"=>"Missing required mappingId param"
            ));
        }
        $workingLanguage=$this->params()->fromPost('workingLanguage','en');
        $this->_dataService->syncContentTypeDown($mappingId,$workingLanguage);
        return $this->_returnJson(array(
            "success"=>true,
        ));
    }



}
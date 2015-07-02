# rubedo-mongodb
Connector extension for syncing contents with external databases

How to use 

Installing this extension adds a new application : Studio / MongoDB Mappings
Add a new mapping for an existing content type (only one mapping per content type can be active). Simply fill in the settings so that Rubedo can connect to the designated database and the field mappings (only the ones you want) with the proprty names of your external collection.
Once active the mapping will sync any publish events on contents of that type (xcreate or update) to your external collection using the mappings.
Sync up/down can also be triggered manually in order to export/import contents to/from Rubedo from/to your external collection.
You can also let Rubedo know about a change on a specific content so it can sync its local cintent accordingly : simply post to /api/v1/mappingsync with the following parameters : lang (language of the sync), itemId (id of the content in your external collection), mappingId (id of the mapping in Rubedo).
The entire sync process does not change your data beyond the mapping fields you set and a new "rubedoContentId" field linking your contents to Rubedo contents

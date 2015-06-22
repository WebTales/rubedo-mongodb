/*
 * File: app/model/MongodbMapping.js
 *
 * This file was generated by Sencha Architect version 3.2.0.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 4.2.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 4.2.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Rubedo.model.MongodbMapping', {
    extend: 'Ext.data.Model',
    alias: 'model.MongodbMapping',

    requires: [
        'Ext.data.Field'
    ],

    fields: [
        {
            name: 'id'
        },
        {
            name: 'version'
        },
        {
            mapping: 'createUser.fullName',
            name: 'createUser',
            persist: false
        },
        {
            dateFormat: 'timestamp',
            name: 'createTime',
            persist: false,
            type: 'date'
        },
        {
            dateFormat: 'timestamp',
            name: 'lastUpdateTime',
            persist: false,
            type: 'date'
        },
        {
            name: 'readOnly',
            persist: false,
            type: 'boolean'
        },
        {
            name: 'contentTypeId'
        },
        {
            name: 'connexionString'
        },
        {
            name: 'collectionName'
        },
        {
            name: 'fieldMappings'
        },
        {
            name: 'name'
        },
        {
            defaultValue: false,
            name: 'active',
            type: 'boolean'
        }
    ]
});
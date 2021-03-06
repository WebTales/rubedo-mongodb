/*
 * File: app/view/AddMongoMappingWindow.js
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

Ext.define('Rubedo.view.AddMongoMappingWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.AddMongoMappingWindow',

    requires: [
        'Ext.form.Panel',
        'Ext.form.field.ComboBox',
        'Ext.button.Button'
    ],

    id: 'AddMongoMappingWindow',
    width: 400,
    constrain: true,
    iconCls: 'media-icon',
    title: 'New MongoDB Mapping',
    modal: true,

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'form',
                    bodyPadding: 10,
                    title: '',
                    items: [
                        {
                            xtype: 'textfield',
                            anchor: '100%',
                            fieldLabel: 'Name',
                            name: 'name',
                            allowBlank: false,
                            allowOnlyWhitespace: false
                        },
                        {
                            xtype: 'combobox',
                            anchor: '100%',
                            fieldLabel: 'Content type',
                            name: 'contentTypeId',
                            allowBlank: false,
                            allowOnlyWhitespace: false,
                            editable: false,
                            displayField: 'type',
                            forceSelection: true,
                            store: 'CTForMapping',
                            valueField: 'id'
                        },
                        {
                            xtype: 'button',
                            anchor: '100%',
                            id: 'submitNewMongoMappingBtn',
                            text: 'Submit new mapping',
                            listeners: {
                                click: {
                                    fn: me.onSubmitNewMongoMappingBtnClick,
                                    scope: me
                                }
                            }
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    },

    onSubmitNewMongoMappingBtnClick: function(button, e, eOpts) {
        var form=button.up().getForm();
        if (form.isValid()){
            Ext.getStore("MongodbMappings").add(form.getValues());
            button.up().up().close();
        }
    }

});
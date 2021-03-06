/*
 * File: app/view/MongodbMappingsInterface.js
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

Ext.define('Rubedo.view.MongodbMappingsInterface', {
    extend: 'Ext.window.Window',
    alias: 'widget.MongodbMappingsInterface',

    requires: [
        'Rubedo.view.MyTool16',
        'Rubedo.view.MyTool17',
        'Ext.panel.Tool',
        'Ext.grid.Panel',
        'Ext.grid.column.Column',
        'Ext.grid.View',
        'Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.form.field.Checkbox',
        'Ext.form.FieldSet',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.toolbar.Fill'
    ],

    height: 533,
    id: 'MongodbMappingsInterface',
    ACL: 'execute.ui.technicalDashboard',
    width: 852,
    constrainHeader: true,
    iconCls: 'media-icon',
    title: 'MongoDB Mappings',

    layout: {
        type: 'hbox',
        align: 'stretch'
    },

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            tools: [
                {
                    xtype: 'mytool16'
                },
                {
                    xtype: 'mytool17'
                }
            ],
            listeners: {
                beforeclose: {
                    fn: me.onFOThemesInterfaceBeforeClose,
                    scope: me
                },
                afterrender: {
                    fn: me.onMongodbMappingsInterfaceAfterRender,
                    scope: me
                }
            },
            items: [
                {
                    xtype: 'gridpanel',
                    id: 'mongoMappingsMainGrid',
                    width: 250,
                    title: '',
                    forceFit: true,
                    store: 'MongodbMappings',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'name',
                            text: 'Name'
                        }
                    ],
                    listeners: {
                        selectionchange: {
                            fn: me.onGridpanelSelectionChange,
                            scope: me
                        }
                    }
                },
                {
                    xtype: 'panel',
                    flex: 1,
                    id:"mongoMappingsHolderPanel",
                    disabled:true,
                    autoScroll: true,
                    title: '',
                    items: [
                        {
                            xtype: 'form',
                            id: 'mongoMappingSettingsForm',
                            bodyPadding: 10,
                            title: 'Settings',
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
                                    xtype: 'checkboxfield',
                                    anchor: '100%',
                                    fieldLabel: 'Active',
                                    name: 'active',
                                    boxLabel: '',
                                    inputValue: 'true',
                                    uncheckedValue: 'false'
                                },
                                {
                                    xtype: 'fieldset',
                                    title: 'External database',
                                    items: [
                                        {
                                            xtype: 'textfield',
                                            anchor: '100%',
                                            fieldLabel: 'Connexion string',
                                            name: 'connexionString',
                                            allowBlank: false,
                                            allowOnlyWhitespace: false
                                        },
                                        {
                                            xtype: 'textfield',
                                            anchor: '100%',
                                            fieldLabel: 'Database name',
                                            name: 'databaseName',
                                            allowBlank: false,
                                            allowOnlyWhitespace: false
                                        },{
                                            xtype: 'textfield',
                                            anchor: '100%',
                                            fieldLabel: 'Collection name',
                                            name: 'collectionName',
                                            allowBlank: false,
                                            allowOnlyWhitespace: false
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'form',
                            id: 'mongoMappingFieldForm',
                            bodyPadding: 10,
                            title: 'Field mappings'
                        }
                    ]
                }
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    flex: 1,
                    dock: 'top',
                    items: [
                        {
                            xtype: 'button',
                            id: 'mongoMappingsAddBtn',
                            ACL: 'execute.ui.technicalDashboard',
                            iconAlign: 'top',
                            iconCls: 'add_big',
                            scale: 'large',
                            text: 'Ajouter',
                            listeners: {
                                click: {
                                    fn: me.onMongoMappingsAddBtnClick,
                                    scope: me
                                }
                            }
                        },
                        {
                            xtype: 'button',
                            disabled: true,
                            ACL: 'execute.ui.technicalDashboard',
                            id: 'mongoMappingsRemoveBtn',
                            iconAlign: 'top',
                            iconCls: 'remove_big',
                            scale: 'large',
                            text: 'Supprimer',
                            listeners: {
                                click: {
                                    fn: me.onMongoMappingsRemoveBtnClick,
                                    scope: me
                                }
                            }
                        },
                        {
                            xtype: 'button',
                            ACL: 'execute.ui.technicalDashboard',
                            localiserId: 'saveBtn',
                            disabled: true,
                            id: 'mongoMappingsSaveBtn',
                            iconAlign: 'top',
                            iconCls: 'floppy_disc_big',
                            scale: 'large',
                            text: 'Enregistrer',
                            listeners: {
                                click: {
                                    fn: me.onMongoMappingsSaveBtnClick,
                                    scope: me
                                }
                            }
                        },
                        {
                            xtype: 'button',
                            ACL: 'execute.ui.technicalDashboard',
                            disabled: true,
                            id: 'mongoMappingsSyncDownBtn',
                            iconAlign: 'top',
                            iconCls: 'database_down_big',
                            scale: 'large',
                            handler:function(btn){
                                btn.setLoading(true);
                                Ext.Ajax.request({
                                    url: 'mongodb-mappings/sync-content-type-down',
                                    params: {
                                        mappingId: Ext.getCmp("mongoMappingsMainGrid").getSelectionModel().getLastSelected().get("id")
                                    },
                                    success: function(response){
                                        btn.setLoading(false);
                                        Ext.Msg.alert("Success","Sync complete");
                                    },
                                    failure: function(response){
                                        btn.setLoading(false);
                                    }
                                });
                            },
                            text: 'Sync down'
                        },
                        {
                            xtype: 'button',
                            ACL: 'execute.ui.technicalDashboard',
                            disabled: true,
                            id: 'mongoMappingsSyncUpBtn',
                            iconAlign: 'top',
                            iconCls: 'database_up_big',
                            scale: 'large',
                            handler:function(btn){
                                btn.setLoading(true);
                                Ext.Ajax.request({
                                    url: 'mongodb-mappings/sync-content-type-up',
                                    params: {
                                        mappingId: Ext.getCmp("mongoMappingsMainGrid").getSelectionModel().getLastSelected().get("id")
                                    },
                                    success: function(response){
                                        btn.setLoading(false);
                                        Ext.Msg.alert("Success","Sync complete");
                                    },
                                    failure: function(response){
                                        btn.setLoading(false);
                                    }
                                });
                            },
                            text: 'Sync up'
                        },
                        {
                            xtype: 'tbfill'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    },

    onFOThemesInterfaceBeforeClose: function(panel, eOpts) {
        Ext.getStore("MongodbMappings").removeAll();
        Ext.getStore("CTForMapping").removeAll();
    },

    onMongodbMappingsInterfaceAfterRender: function(component, eOpts) {
        Ext.getStore("MongodbMappings").load();
        Ext.getStore("CTForMapping").load();
    },

    onGridpanelSelectionChange: function(model, selected, eOpts) {
        Ext.getCmp("mongoMappingSettingsForm").getForm().reset();
        Ext.getCmp("mongoMappingFieldForm").getForm().reset();
        Ext.getCmp("mongoMappingFieldForm").removeAll();
        if (Ext.isEmpty(selected)){
            Ext.getCmp("mongoMappingsRemoveBtn").disable();
            Ext.getCmp("mongoMappingsSaveBtn").disable();
            Ext.getCmp("mongoMappingsSyncUpBtn").disable();
            Ext.getCmp("mongoMappingsSyncDownBtn").disable();
            Ext.getCmp("mongoMappingsHolderPanel").disable();

        } else {
            Ext.getCmp("mongoMappingsRemoveBtn").enable();
            Ext.getCmp("mongoMappingsSaveBtn").enable();
            Ext.getCmp("mongoMappingsHolderPanel").enable();
            Ext.getCmp("mongoMappingsSyncUpBtn").enable();
            Ext.getCmp("mongoMappingsSyncDownBtn").enable();
            Ext.getCmp("mongoMappingSettingsForm").getForm().setValues(selected[0].getData());
            var fieldsToAdd=[
                {
                    name:"text",
                    fieldLabel:"Title",
                    xtype:"textfield",
                    anchor:"100%"

                },
                {
                    name:"vanityUrl",
                    fieldLabel:"Vanity URL",
                    xtype:"textfield",
                    anchor:"100%"
                },
                {
                    name:"summary",
                    fieldLabel:"Summary",
                    xtype:"textfield",
                    anchor:"100%"
                }
            ];
            var myCt=Ext.getStore("CTForMapping").findRecord("id",selected[0].get("contentTypeId"));

            Ext.Array.forEach(myCt.get("champs"),function(field){
                fieldsToAdd.push({
                    name:field.config.name,
                    fieldLabel:field.config.fieldLabel,
                    xtype:"textfield",
                    anchor:"100%"
                });
            });
            Ext.getCmp("mongoMappingFieldForm").add(fieldsToAdd);
            var recFieldMappings=Ext.clone(selected[0].get("fieldMappings"));
            if (Ext.isEmpty(recFieldMappings)){
                recFieldMappings={ };
            }
            Ext.getCmp("mongoMappingFieldForm").getForm().setValues(recFieldMappings);
            Ext.getCmp("mongoMappingFieldForm").doLayout();

        }
    },

    onMongoMappingsAddBtnClick: function(button, e, eOpts) {
        Ext.widget("AddMongoMappingWindow").show();
    },

    onMongoMappingsRemoveBtnClick: function(button, e, eOpts) {
        Ext.getCmp("mongoMappingsMainGrid").getStore().remove(Ext.getCmp("mongoMappingsMainGrid").getSelectionModel().getLastSelected());
    },

    onMongoMappingsSaveBtnClick: function(button, e, eOpts) {
        var form=Ext.getCmp("mongoMappingSettingsForm").getForm();
        if (form.isValid()){
            var rec=Ext.getCmp("mongoMappingsMainGrid").getSelectionModel().getLastSelected();
            rec.beginEdit();
            rec.set(Ext.clone(form.getValues()));
            rec.set("fieldMappings",Ext.getCmp("mongoMappingFieldForm").getForm().getValues());
            rec.endEdit();
        }
    }

});
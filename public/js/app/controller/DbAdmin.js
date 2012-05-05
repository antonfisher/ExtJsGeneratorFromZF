Ext.define('App.controller.DbAdmin', {
    extend: 'Ext.app.Controller',

    views: [
        'dbAdmin.Viewport'
    ],

    //TODO remove router for store
    stores: [
        'Bouquets',
        'Flowers',
        'Wrappers'
    ],

    models: [
        'Bouquets',
        'Flowers',
        'Wrappers'
    ],

    requires: [
        //'Extjs-generator.view.type.gridFormEdit.dbmodel.Bouquets',
        'Extjs-generator.view.type.gridRowEdit.dbmodel.Bouquets',
        //'Extjs-generator.view.type.formWindow.dbmodel.Flowers',
        //'Extjs-generator.view.type.gridRowEdit.dbmodel.Flowers'
    ],

    init: function () {
        this.callParent(arguments);
    }

});


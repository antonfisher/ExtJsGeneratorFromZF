Ext.define('App.controller.DbAdmin', {
    extend: 'Ext.app.Controller',
    
    views: [
        'dbAdmin.Viewport',
    ],
    
    stores: [
        'Bouquets',
        'Flowers'
    ],
    
    models: [
        'Bouquets',
        'Flowers'
    ],
    
    requires: [
        'Extjs-generator.view.type.grid.dbmodel.Bouquets',
        'Extjs-generator.view.type.grid.dbmodel.Flowers'
    ],
    
    init: function() {
      this.callParent(arguments);
    }
    
});
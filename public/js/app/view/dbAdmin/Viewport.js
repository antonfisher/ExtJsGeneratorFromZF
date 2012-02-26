Ext.define('App.view.dbAdmin.Viewport', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.dbAdmin-Viewport',

    title: 'Generate ExtJs Grids and Forms from Zend Framework DbModel',
    layout: 'fit',

    items: {
        xtype: 'tabpanel',
        layout: 'fit',
        items: [
            {
                xtype: 'Extjs-generator-view-type-gridFormEdit-dbmodel-Bouquets',
                title: 'Bouquets'
//            },{
//                xtype: 'Extjs-generator-view-type-gridRowEdit-dbmodel-Flowers',
//                title: 'Flowers'
            }
        ]
    }
});
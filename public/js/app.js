Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'Ext':  '/js/extjs/src',
        'App':  '/js/app',
        'ExtG': '/js/extjs-generator'
    }
});

Ext.application({
    name: 'App',
    appFolder: '/js/app',

    controllers: [
        'DbAdmin'
    ],

//    requires: [
//        'Ext.ux.form.MultiSelect'
//    ],

    launch: function () {
        Ext.create('Ext.container.Viewport', {
            layout: 'fit',
            items: {
                xtype: 'dbAdmin-Viewport'
            }
        });
    }
});


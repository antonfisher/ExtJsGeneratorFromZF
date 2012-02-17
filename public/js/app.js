Ext.Loader.setConfig({
    enabled:true,
    paths: {
        'App': '/js/app',
        'Ext': '/js/extjs/src'
    }
});
Ext.QuickTips.init();

Ext.application({
    name: 'App',
    launch: function() {
        Ext.create('Ext.container.Viewport', {
            layout: 'fit',
            items: [
                {
                    title: 'Generate ExtJs Grids and Forms from Zend Framework DbModel',
                    html : 'Good news evety one!'
                }
            ]
        });
    }
});
Ext.define('ExtG.grid.PaginatorPanel', {
    extend: 'ExtG.grid.Panel',
    alias: 'widget.ExtG-grid-PaginatorPanel',
    
    initComponent: function() {
        this.bbar = Ext.create('Ext.PagingToolbar', {
            store: this.store,
            height: true,
            displayInfo: true
        });
        
        this.callParent(arguments);
    }

});


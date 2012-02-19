Ext.define('ExtG.grid.Panel', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.ExtG-grid-Panel',
    invalidateScrollerOnRefresh: true,
    layout: 'fit',
    loadMask: true,
    columnLines: true,
    viewConfig: {
        trackOver: false
    },

    initComponent: function() {
        this.callParent(arguments);

        //FIX try to scroll bug (4.0.2a - 4.0.7(current))
        this.on('scrollershow', function(scroller) {
            if (scroller && scroller.scrollEl) {
                scroller.clearManagedListeners();
                scroller.mon(scroller.scrollEl, 'scroll', scroller.onElScroll, scroller);
            }
        });
    }

});


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

//    requires: [
//        'Ext.ux.CheckColumn'
//    ],

    initComponent: function() {
        // merge with initial config
        if (this.initialConfig.columns != undefined) {
            for (var i in this.initialConfig.columns) {
                var itemInit = this.initialConfig.columns[i];
                for (var j in this.columns) {
                    var itemGenerated = this.columns[j];
                    if (itemInit.dataIndex == itemGenerated.dataIndex) {
                        if (itemInit.disabled == true) {
                            this.columns.splice(j, 1);
                            continue;
                        }
                        Ext.merge(itemGenerated, itemInit);
                    }
                }
            }
        } else {
            console.log('Columns for grid doesnt initial config!');
        }

        this.callParent(arguments);

        //FIX try to scroll bug (4.0.2a - 4.0.7(current))
        this.on('scrollershow', function(scroller) {
            if (scroller && scroller.scrollEl) {
                scroller.clearManagedListeners();
                scroller.mon(scroller.scrollEl, 'scroll', scroller.onElScroll, scroller);
            }
        });
    },

    renderComboboxValue: function(value, metaData, record, rowIndex, colIndex) {
        var editor = this.columns[colIndex].editor || this.columns[colIndex].field;
        if (editor && 'store' in editor) {
            if (Ext.isArray(editor.store)) {
                // local store
                for (var i in editor.store) {
                    var item = editor.store[i];
                    if (item[0] == value) {
                        value = item[1];
                        break;
                    }
                }
            } else {
                var store = Ext.getStore(editor.store);
                if ('data' in store && 'items' in store.data) {
                    // remote store
                    for (var i in store.data.items) {
                        var item = store.data.items[i];
                        if (item.data[editor.valueField] == value) {
                            value = item.data[editor.displayField];
                            break;
                        }
                    }
                }
            }
        }
        return value;
    },

    showM2MWindow: function() {
        console.log('Call showM2MWindow()', arguments, this);
        var window = Ext.create('Ext.window.Window', {
           title: 'Link' ,
           width: 400,
           height: 300,
           layout: 'fit',
           items: {
               //xtype: 'Extjs-generator-view-type-gridRowEdit-dbmodel-Flowers'
               xtype: 'Extjs-generator-view-type-grid-dbmodel-Flowers'
           }
        });
        window.show();
        window.down('grid').getStore().load();
    }

});


Ext.define('ExtG.grid.FormEditPanel', {
    extend: 'ExtG.grid.PaginatorPanel',
    alias: 'widget.ExtG-grid-FormEditPanel',
    split: true,
    selType: 'rowmodel',

    // custom
//    storeAutoLoad: true,

    // confirm destroy window
    showConfirmDestroy: true,
    confirmDestroyTitle: 'Confirm',
    confirmDestroyText: 'You are sure?',

    initComponent: function() {
        var me = this;

        me.tbar = [
            {
                text: '✓ Add',
                role: 'create',
                listeners: {
                    click: function(obj){
                        //TODO mask
                        var w = Ext.create('Extjs-generator.view.type.formWindow.dbmodel.' + me.store.storeId);

                        w.down('button').addListener('click', function(obj){
                            var form = obj.up('form');
                            if (!form.url) {
                                form.submit = function() {
                                    var modelName = me.store.storeId;
                                    var model = new App.model[modelName];
                                    model.data = form.getValues();
                                    me.store.insert(0, model);
                                    w.close();
                                };
                                form.submit();
                                w.destroy();
                            }
                        });

                        w.show();
                    }
                }
            }, '-', {
                text: '✕ Delete',
                role: 'remove',
                disabled: false,
                listeners: {
                    click: function(obj) {
                        var selection = me.getView().getSelectionModel().getSelection()[0];
                        if (selection) {
                            if (me.showConfirmDestroy) {
                                Ext.Msg.confirm(me.confirmDestroyTitle, me.confirmDestroyText, function(btn){
                                    if ('yes' == btn) {
                                        me.store.remove(selection);
                                    }
                                })
                            } else {
                                me.store.remove(selection);
                            }
                        } else {
                            Ext.Msg.alert('Warning', 'Pleace, select some item to delete.');
                        }
                    }
                }
            }
        ];

        this.callParent(arguments);

        this.addListener('itemdblclick', function(view, record) {
            console.log('---', arguments);
            var w = Ext.create('Extjs-generator.view.type.formWindow.dbmodel.' + me.store.storeId);
            w.loadRecord(record);
            w.show();
        });
    }

});


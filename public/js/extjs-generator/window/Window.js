Ext.define('ExtG.window.Window', {
    extend: 'Ext.window.Window',
    alias: 'widget.ExtG-window-Window',

    buttonSaveText: 'Save',
    buttonCancelText: 'Cancel',

    initComponent: function () {
        var me = this;

//        me.items = {
//            'xtype': 'form',
//            'border': 0,
//            'bodyPadding': 5,
//            'buttons': [
//                {
//                    'text': me.buttonSaveText
//                },{
//                    'text': me.buttonCancelText
//                }
//            ]
//        }

        this.callParent(arguments);
    },

    loadRecord: function (record) {
        this.down('form').loadRecord(record);
    }

});


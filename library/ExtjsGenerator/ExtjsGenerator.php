<?php

class ExtjsGenerator_ExtjsGenerator {

    const STORE_ACTION_READ    = 'read';
    const STORE_ACTION_CREATE  = 'create';
    const STORE_ACTION_UPDATE  = 'update';
    const STORE_ACTION_DESTROY = 'destroy';

    
    public function getModelCode($jsName) {
        $dbModelName = $this->_getDbModelNameByJsName($jsName);
        $dbModel     = $this->_getDbModelByName($dbModelName);

        $jsonParams = array(
            'extend' => 'Ext.data.Model',
            'fields' => $dbModel->getExtjsModelFields(),
        );

        $jsonParams = Zend_Json_Encoder::encode($jsonParams);
        
        $jsCode = "console.log('Generate model: {$dbModelName}.js'); ";
        $jsCode .= "Ext.define('App.model.{$dbModelName}', {$jsonParams});";

        return $jsCode;
    }
    
    public function getStoreCode($jsName) {
        $dbModelName = $this->_getDbModelNameByJsName($jsName);

        $jsonParams = array(
            'extend'          => 'Ext.data.Store',
            'model'           => "App.model.{$dbModelName}",
            'autoLoad'        => true,
            'autoSync'        => true,
            'remoteSort'      => true,
            'pageSize'        => 100,
            'buffered'        => true,
            'batchUpdateMode' => 'complite',
            'proxy' => array(
                'type' => 'ajax',
                'timeout' => 120000,
                'reader' => array(
                    'type'            => 'json',
                    'root'            => 'data',
                    'totalProperty'   => 'totalCount',
                    'successProperty' => 'success',
                    'messageProperty' => 'message',
                ),
                'writer' => array(
                    'type'            => 'json',
                    'successProperty' => 'success',
                    'messageProperty' => 'message',
                    'writeAllFields'  => true,
                ),
                'api' => array(
                    'read'    => "/extjs-generator/store-read/dbmodel/{$dbModelName}/format/json",
                    'update'  => "/extjs-generator/store-update/dbmodel/{$dbModelName}/format/json",
                    'create'  => "/extjs-generator/store-create/dbmodel/{$dbModelName}/format/json",
                    'destroy' => "/extjs-generator/store-destroy/dbmodel/{$dbModelName}/format/json",
                ),
            ),
        );
                    
        $jsonParams = Zend_Json_Encoder::encode($jsonParams);

        $jsCode = "console.log('Generate store: {$dbModelName}.js'); ";
        $jsCode .= "Ext.define('App.store.{$dbModelName}', {$jsonParams});";

        return $jsCode;
    }
    
    public function getViewCode($type, $dbModel)
    {
        $jsCode = "console.log('Generate view error!');";
        switch ($type) {
            //TODO расширить до:
            case 'grid':
            case 'grid-row-edit':
            case 'grid-form-edit':
                $jsCode = $this->_getGridCode($dbModel);
                break;
            case 'form':
                $jsCode = $this->_getFormCode($dbModel);
                break;
            case 'form-window':
                $jsCode = $this->_getFormWindowCode($dbModel);
                break;
            default :
                break;
        }
        
        return $jsCode;
    }
    
    public function _getGridCode($jsName) {
        $dbModelName = $this->_getDbModelNameByJsName($jsName);
        $dbModel     = $this->_getDbModelByName($dbModelName);
        
        $jsonParams = array(
            //'extend' => 'ExtG.grid.FormEditPanel',
            'extend' => 'ExtG.grid.RowEditPanel',
            'alias'  => 'widget.Extjs-generator-view-type-grid-dbmodel-' . $dbModelName,
            'store'  => $dbModelName,
            'title'  => $dbModelName,
        );

        $referenceMap = $dbModel->info('referenceMap');
        foreach ($dbModel->info('metadata') as $field => $arrDescription) {
            $jsonParams['columns'][] = $this->_getGridColumnFromDbRow($field, $arrDescription, $referenceMap);
        }
        
        $jsonParams = Zend_Json_Encoder::encode($jsonParams);
        
        $jsCode = "console.log('Generate grid: {$dbModelName}.js'); ";
        $jsCode .= "Ext.define('Extjs-generator.view.type.grid.dbmodel.{$dbModelName}', {$jsonParams})";

        //TODO renderer for grid dependency tables
        
        return $jsCode;
    }
    
    public function _getFormWindowCode($jsName) {
        $dbModelName = $this->_getDbModelNameByJsName($jsName);
        $dbModel     = $this->_getDbModelByName($dbModelName);
        
        $jsonParams = array(
            'extend' => 'Ext.window.Window',
            'title'  => $dbModelName,
            'items'  => array(
                'xtype' => 'form',
                'border' => 0,
                'bodyPadding' => 5,
                'buttons' => array(
                    array(
                        'text' => 'Save'
                    )
                )
            )
        );

        $referenceMap = $dbModel->info('referenceMap');
        foreach ($dbModel->info('metadata') as $field => $arrDescription) {
            $jsonParams['items']['items'][] = $this->_getEditorFromDbRow($field, $arrDescription, $referenceMap);
        }
        
        $jsonParams = Zend_Json_Encoder::encode($jsonParams);
        
        $jsCode = "console.log('Generate form-window: {$dbModelName}.js'); ";
        $jsCode .= "Ext.define('Extjs-generator.view.type.form-window.dbmodel.{$dbModelName}', {$jsonParams})";

        return $jsCode;
    }
    
    public function storeAction($actionType, Zend_Controller_Request_Abstract $request) {
        $success    = true;
        $message    = '';
        $arrData    = array();
        $totalCount = null;
        
        try {
            $dbModelName = $this->_getDbModelNameByJsName($request->getParam('dbmodel'));
            $dbModel     = $this->_getDbModelByName($dbModelName);

            $arrParams = array(
                'limit'  => $request->getParam('limit', 100),
                'start'  => $request->getParam('start', 0),
                'sort'   => (
                    $request->getParam('sort') ? $this->_extjsStoreSortToArray($request->getParam('sort')) : array()
                ),
                'params' => (
                    $request->getParam('params') ? Zend_Json_Decoder::decode($request->getParam('params')) : array()
                ),
            );
            
            $arrRawBody = $this->_getArrayFromRawBody($request->getRawBody());
            
            $dbModel->getAdapter()->beginTransaction();
            
            switch ($actionType) {
                case self::STORE_ACTION_READ:
                    $arrData = $dbModel->extjsStoreRead($arrParams);
                    if (count($arrData) < $arrParams['limit'] && $arrParams['start'] == 0) {
                        $totalCount = count($arrData);
                    } else {
                        $totalCount = $dbModel->extjsStoreReadCount($arrParams);
                    }
                    break;
                case self::STORE_ACTION_CREATE:
                    foreach ($arrRawBody as &$arrItem) {
                        //возвращать arrItems обратно клиенту
                        $arrItem['id'] = $dbModel->extjsStoreCreate($arrItem, $arrParams);
                    }
                    unset($arrItem);
                    break;
                case self::STORE_ACTION_UPDATE:
                    foreach ($arrRawBody as $arrItem) {
                        $dbModel->extjsStoreUpdate($arrItem, $arrParams);
                    }
                    break;
                case self::STORE_ACTION_DESTROY:
                    foreach ($arrRawBody as $arrItem) {
                        $dbModel->extjsStoreDestroy($arrItem, $arrParams);
                    }
                    break;
                default:
                    throw new Exception('Not valid store action.');
                    break;
            }
            
            if ($success) {
                $dbModel->getAdapter()->commit();
            } else {
                $dbModel->getAdapter()->rollBack();
            }
        } catch (Zend_Exception $e) {
            $success = false;
            $message = 'Store action: ' . $e->getMessage();
        }

        return Zend_Json_Encoder::encode(
            array(
                'success'    => $success,
                'message'    => $message,
                'data'       => $arrData,
                'totalCount' => $totalCount,
            )
        );
    }
    
    protected function _getArrayFromRawBody($rawBody)
    {
        $arrRawBody = array();
        try {
            $arrRawBody = Zend_Json::decode($rawBody);
            if (!isset($arrRawBody[0]) || !is_array($arrRawBody[0])) {
                $arrRawBody = array($arrRawBody);
            }
        } catch(Zend_Json_Exception $e) {
            // --
        }
        return $arrRawBody;
    }

    protected function _extjsStoreSortToArray($sort) {
        return $sort;
    }

    protected function _getGridColumnFromDbRow($field, array $arrDescription, $referenceMap)
    {
        $jsonParams = array(
            'header'    => $field,
            'dataIndex' => $field,
            'flex'      => 1,
            'editor'    => 'textfield',
        );

        switch ($arrDescription['DATA_TYPE']) {
            case 'boolean':
            case 'bool':
                $jsonParams['editor']    = 'checkboxfield';
                $jsonParams['xtype']     = 'booleancolumn';
                $jsonParams['trueText']  = 'Yes';
                $jsonParams['falseText'] = 'No';
                break;
            default:
                break;
        }
        
        $jsonParams['editor'] = $this->_getEditorFromDbRow($field, $arrDescription, $referenceMap, false);
        
        return $jsonParams;
    }
    
    protected function _getEditorFromDbRow($field, array $arrDescription, $referenceMap, $showFieldLabel = true)
    {   
        $jsonParams = array(
            'name'       => $field,
            'xtype'      => 'textfield',
        );
        
        if ($showFieldLabel) {
            $jsonParams['fieldLabel'] = $field;
        }

        switch ($arrDescription['DATA_TYPE']) {
            case 'int4':
            case 'numeric':
                $jsonParams['xtype'] = 'numberfield';
                break;
            case 'boolean':
            case 'bool':
                $jsonParams['xtype'] = 'checkboxfield';
                break;
            case 'timestamp':
                $jsonParams['xtype'] = 'datefield';
                break;
            default:
                $jsonParams['xtype'] = 'textfield';
                break;
        }

        return $jsonParams;
    }
    
    protected function _getDbModelNameByJsName($jsName) {
        return str_replace('.js', '', $jsName);
    }
    
    /**
     * @return Zend_Db_Table
     */
    protected function _getDbModelByName($dbModelName) {
        $dbModelName = 'Application_Model_DbTable_' . $dbModelName;
        
        if (!class_exists($dbModelName)) {
            throw new Exception('ExtjsGenerator: dbmodel class does not exist: ' . $dbModelName);
        }
        
        return new $dbModelName();
    }
    
    protected function _makeJsCode(array $arrCode) {
        return Zend_Json_Encoder::encode($arrCode);
    }

}


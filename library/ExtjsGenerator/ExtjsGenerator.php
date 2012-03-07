<?php

/**
 * Abstract model for Extjs generator
 *
 * @category  ExtjsGenerator
 * @package   ExtjsGenerator_ExtjsGenerator
 * @author    Anton Fischer <a.fschr@gmail.com>
 * @copyright 2012 (c) none
 * @license   http://none BSD License
 * @link      https://github.com/antonfisher/ExtJsGeneratorFromZF
 */
class ExtjsGenerator_ExtjsGenerator
{

    const STORE_ACTION_READ    = 'read';
    const STORE_ACTION_CREATE  = 'create';
    const STORE_ACTION_UPDATE  = 'update';
    const STORE_ACTION_DESTROY = 'destroy';

    const VIEW_GRID           = 'grid';
    const VIEW_GRID_FORM_EDIT = 'gridFormEdit';
    const VIEW_GRID_ROW_EDIT  = 'gridRowEdit';

    const VIEW_FORM        = 'form';
    const VIEW_FORM_WINDOW = 'formWindow';


    /**
     * Get Extjs model java script code
     *
     * @param string $jsName model name
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return string
     */
    public function getModelCode($jsName)
    {
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

    /**
     * Get Extjs store java script code
     *
     * @param string $jsName store name
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return string
     */
    public function getStoreCode($jsName)
    {
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

    /**
     * Get Extjs view (grid, window, form ..) java script code
     *
     * @param string $type    type of view
     * @param string $dbModel db model name
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return string
     */
    public function getViewCode($type, $dbModel)
    {
        $jsCode = "console.log('Generate view error!');";
        switch ($type) {
            //TODO расширить до:
            case self::VIEW_GRID:
            case self::VIEW_GRID_FORM_EDIT:
            case self::VIEW_GRID_ROW_EDIT:
                $jsCode = $this->_getGridCode($dbModel, $type);
                break;
            case self::VIEW_FORM:
                $jsCode = $this->_getFormCode($dbModel);
                break;
            case self::VIEW_FORM_WINDOW:
                $jsCode = $this->_getFormWindowCode($dbModel);
                break;
            default :
                break;
        }

        return $jsCode;
    }

    /**
     * Get Extjs grid java script code
     *
     * @param string $jsName grid name
     * @param string $type   type of grid
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return string
     */
    public function _getGridCode($jsName, $type = self::VIEW_GRID)
    {
        $dbModelName = $this->_getDbModelNameByJsName($jsName);
        $dbModel     = $this->_getDbModelByName($dbModelName);

        $jsonParams = array(
            'extend' => 'ExtG.grid.Panel',
            'alias'  => "widget.Extjs-generator-view-type-{$type}-dbmodel-{$dbModelName}",
            'store'  => $dbModelName,
            'title'  => $dbModelName,
        );

        switch ($type) {
            case self::VIEW_GRID_FORM_EDIT:
                $jsonParams['extend'] = 'ExtG.grid.FormEditPanel';
                break;
            case self::VIEW_GRID_ROW_EDIT:
                $jsonParams['extend'] = 'ExtG.grid.RowEditPanel';
                break;
            default :
                break;
        }

        $referenceMap = $dbModel->info(Zend_Db_Table::REFERENCE_MAP);
        foreach ($dbModel->info(Zend_Db_Table::METADATA) as $field => $arrDescription) {
            $jsonParams['columns'][] = $this->_getGridColumnFromDbRow($field, $arrDescription, $referenceMap);
        }

        $jsonParams = Zend_Json_Encoder::encode($jsonParams);

        $jsCode = "console.log('Generate grid: {$dbModelName}.js'); ";
        $jsCode .= "Ext.define('Extjs-generator.view.type.{$type}.dbmodel.{$dbModelName}', {$jsonParams})";

        //TODO renderer for grid dependency tables

        return $jsCode;
    }

    /**
     * Get Extjs form window java script code
     *
     * @param string $jsName grid name
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return string
     */
    public function _getFormWindowCode($jsName)
    {
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

        $referenceMap = $dbModel->info(Zend_Db_Table::REFERENCE_MAP);
        foreach ($dbModel->info(Zend_Db_Table::METADATA) as $field => $arrDescription) {
            $jsonParams['items']['items'][] = $this->_getEditorFromDbRow($field, $arrDescription, $referenceMap);
        }

        $jsonParams = Zend_Json_Encoder::encode($jsonParams);

        $jsCode = "console.log('Generate formWindow: {$dbModelName}.js'); ";
        $jsCode .= "Ext.define('Extjs-generator.view.type." . self::VIEW_FORM_WINDOW
                . ".dbmodel.{$dbModelName}', {$jsonParams})";

        return $jsCode;
    }

    /**
     * Get Extjs store acrion (update, create, destroy, read)
     *
     * @param string                           $actionType update, create, destroy, read
     * @param Zend_Controller_Request_Abstract $request    request
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return string
     */
    public function storeAction($actionType, Zend_Controller_Request_Abstract $request)
    {
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

    /**
     * Get Extjs store acrion (update, create, destroy, read)
     *
     * @param string $rawBody raw http request body
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return string
     */
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

    /**
     * sort
     *
     * @param string $sort raw http request body
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return string
     */
    protected function _extjsStoreSortToArray($sort)
    {
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
            'name'  => $field,
            'xtype' => 'textfield',
        );

        if ($showFieldLabel) {
            $jsonParams['fieldLabel'] = $field;
        }

        switch ($arrDescription['DATA_TYPE']) {
            case 'int4':
            case 'int8':
            case 'float8':
            case 'numeric':
                $jsonParams['xtype'] = 'numberfield';
                break;
            case 'boolean':
            case 'bool':
                $jsonParams['xtype'] = 'checkboxfield';
                break;
            case 'date':
            case 'timestamp':
                $jsonParams['xtype'] = 'datefield';
                break;
            default:
                $jsonParams['xtype'] = 'textfield';
                break;
        }

        $jsonParams['allowBlank'] = $arrDescription['NULLABLE'];

        $jsonParams['disabled'] = $arrDescription['PRIMARY'];

        return $jsonParams;
    }

    protected function _getDbModelNameByJsName($jsName)
    {
        return str_replace('.js', '', $jsName);
    }

    /**
     * @return Zend_Db_Table
     */
    protected function _getDbModelByName($dbModelName)
    {
        $dbModelName = 'Application_Model_DbTable_' . $dbModelName;

        if (!class_exists($dbModelName)) {
            throw new Exception('ExtjsGenerator: dbmodel class does not exist: ' . $dbModelName);
        }

        return new $dbModelName();
    }

    protected function _makeJsCode(array $arrCode)
    {
        return Zend_Json_Encoder::encode($arrCode);
    }

}


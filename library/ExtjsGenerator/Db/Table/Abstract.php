<?php

/**
 * Abstract model for Extjs generator
 *
 * @category  Models
 * @package   ExtjsGenerator_Db_Table_Abstract
 * @author    Anton Fischer <a.fschr@gmail.com>
 * @copyright 2012 (c) none
 * @license   http://none BSD License
 * @link      https://github.com/antonfisher/ExtJsGeneratorFromZF
 */
abstract class ExtjsGenerator_Db_Table_Abstract extends Zend_Db_Table_Abstract
{

    const REFERENCE_MAP_M2M = 'referenceMapM2M';

    protected $_referenceMapM2M = null;

    /**
     * Getter for _name
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return string/null
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Getter for schema
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return string/null
     */
    public function getSchema()
    {
        return $this->_schema;
    }

    /**
     * Get fields with descriptions for Extjs model or form
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return array
     */
    public function getExtjsModelFields()
    {
        $arrModelFields = array();

        foreach ($this->info(self::METADATA) as $field => $arrDescription) {
            $arrModelFields[] = $field;
        }

        return $arrModelFields;
    }

    /**
     * Get data for Extjs store read action
     *
     * @param array $arrParams array of request params
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return array
     */
    public function extjsStoreRead(array $arrParams = array())
    {
        $select = $this->getExtjsStoreReadSelect($arrParams);
        $arrData = $this->fetchAll($select)->toArray();

        return $arrData;
    }

    /**
     * Get count of data for Extjs store read action
     *
     * @param array $arrParams array of request params
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return integer
     */
    public function extjsStoreReadCount(array $arrParams = array())
    {
        $select = $this->getExtjsStoreReadSelect($arrParams);
        $select->reset(Zend_Db_Select::COLUMNS)
               ->reset(Zend_Db_Select::ORDER)
               ->limit(null)
               ->columns(new Zend_Db_Expr('COUNT(*)'));

        return $this->getAdapter()->fetchOne($select);
    }

    /**
     * Update data for Extjs store update action
     *
     * @param array $arrItem   array for update
     * @param array $arrParams array of request params
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return mixed
     */
    public function extjsStoreUpdate(array $arrItem, array $arrParams = array())
    {
        $arrPrimaryKeys = array_flip($this->info(self::PRIMARY));
        if (0 == count(array_diff_key($arrPrimaryKeys, $arrItem))) {
            $arrPrimaryData = array_diff_key($arrItem, array_diff_key($arrItem, $arrPrimaryKeys));
            $objRow = $this->find($arrPrimaryData)->current();
            $objRow->setFromArray($this->_normalizeFieldsTypes($arrItem));

            return $objRow->save();
        }

        throw new Exception("Primary keys for table '{$this->getName()}' does not defined.");
    }

    /**
     * Create data for Extjs store create action
     *
     * @param array $arrItem   array for create
     * @param array $arrParams array of request params
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return mixed
     */
    public function extjsStoreCreate(array $arrItem, array $arrParams = array())
    {
        foreach ($this->info(self::PRIMARY) as $primaryKey) {
            if (empty($arrItem[$primaryKey])) {
                unset($arrItem[$primaryKey]);
            }
        }

        $objRow = $this->createRow($this->_normalizeFieldsTypes($arrItem));

        return $objRow->save();
    }

    /**
     * Destroy data for Extjs store destroy action
     *
     * @param array $arrItem   array for destroy
     * @param array $arrParams array of request params
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return mixed
     */
    public function extjsStoreDestroy(array $arrItem, array $arrParams = array())
    {
        $arrPrimaryKeys = array_flip($this->info(self::PRIMARY));
        if (0 == count(array_diff_key($arrPrimaryKeys, $arrItem))) {
            $arrPrimaryData = array_diff_key($arrItem, array_diff_key($arrItem, $arrPrimaryKeys));
            $objRow = $this->find($arrPrimaryData)->current();

            if ($objRow) {
                return $objRow->delete();
            }

            throw new Exception("Row does not found in '{$this->getName()}'.");
        }

        throw new Exception("Primary keys for table '{$this->getName()}' does not defined.");
    }

    /**
     * Make select object for Extjs store read action
     *
     * @param array $arrParams array of request params
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return Zend_Db_Select
     */
    public function getExtjsStoreReadSelect(array $arrParams = array())
    {
        $select = $this->select();

        if (!empty($arrParams['limit'])) {
            $start = isset($arrParams['start']) ? $arrParams['start'] : 0;
            $select->limit($arrParams['limit'], $start);
        }

        //TODO Warning: Invalid argument supplied for foreach() in /home/fisher/workspace/extjs-generator-from-zf/library/ExtjsGenerator/Db/Table/Abstract.php on line 179
        if (!empty($arrParams['sort'])) {
            foreach ($arrParams['sort'] as $sortItem) {
                $select->order($sortItem);
            }
        } else {
            //TODO проверить _primary, преобразовать в массив или взять из info()?
            if (is_array($this->_primary)) {
                foreach ($this->_primary as $item) {
                    $select->order($item);
                }
            } else if (is_string($this->_primary)) {
                $select->order($this->_primary);
            }
        }

        return $select;
    }

    /**
     * Get id-title pairs as assoc array (ex. for comboboxes)
     *
     * @param string $columnKey   key column name
     * @param string $columnValue value colum name
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return array
     */
    public function getPairs($columnKey, $columnValue)
    {
        $arrResult = array();

        $select = $this->select();
        $select->from(
            $this->getName(),
            array($columnKey, $columnValue),
            $this->getSchema()
        );

        foreach ($this->fetchAll($select)->toArray() as $item) {
            $arrResult[($item[$columnKey])] = $item[$columnValue];
        }

        return $arrResult;
    }

    /**
     * Returns table information.
     *
     * You can elect to return only a part of this information by supplying its key name,
     * otherwise all information is returned as an array.
     *
     * @param  string $key The specific info part to return OPTIONAL
     * @return mixed
     */
    public function info($key = null)
    {
        if (self::REFERENCE_MAP_M2M == $key) {
            return $this->_referenceMapM2M;
        }

        return parent::info($key);
    }

    /**
     * Pre-save normalisation db field data by required types
     *
     * @param array $arrItem array for normalise
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return array
     */
    protected function _normalizeFieldsTypes(array $arrItem)
    {
        //TODO на основе metadata привести к нужному типу
        foreach ($arrItem as &$value) {
            $value = ($value === '' ? null : $value);
            $value = (is_bool($value) ? ($value ? '1' : '0') : $value);
        }
        unset($value);

        return $arrItem;
    }

}


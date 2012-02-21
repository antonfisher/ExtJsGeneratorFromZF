<?php

abstract class ExtjsGenerator_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
    
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

    public function getExtjsModelFields()
    {
        $arrModelFields = array();

        foreach($this->info(self::METADATA) as $field => $arrDescription) {
            $arrModelFields[] = $field;
        }

        return $arrModelFields;
    }
    
    public function extjsStoreRead(array $arrParams = array())
    {
        $select = $this->getExtjsStoreReadSelect($arrParams);
        $arrData = $this->fetchAll($select)->toArray();
        
        return $arrData;
    }
    
    public function extjsStoreReadCount(array $arrParams = array())
    {
        $select = $this->getExtjsStoreReadSelect($arrParams);
        $select->reset(Zend_Db_Select::COLUMNS)
               ->reset(Zend_Db_Select::ORDER)
               ->limit(null)
               ->columns(new Zend_Db_Expr('COUNT(*)'));

        return $this->getAdapter()->fetchOne($select);
    }
    
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
    
    public function getExtjsStoreReadSelect(array $arrParams = array())
    {
        $select = $this->select();
        
        if (!empty($arrParams['limit'])) {
            $start = isset($arrParams['start']) ? $arrParams['start'] : 0;
            $select->limit($arrParams['limit'], $start);
        }
        
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


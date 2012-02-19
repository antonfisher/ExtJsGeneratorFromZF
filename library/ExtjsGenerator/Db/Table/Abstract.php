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

        foreach($this->info('metadata') as $field => $arrDescription) {
            $arrModelFields[] = $field;
        }

        return $arrModelFields;
    }
    
    public function extjsStoreRead(array $arrParams = array()) {
        $select = $this->getExtjsStoreReadSelect($arrParams);
        $arrData = $this->fetchAll($select)->toArray();
        return $arrData;
    }
    
    public function extjsStoreReadCount(array $arrParams = array()) {
        $select = $this->getExtjsStoreReadSelect($arrParams);
        $select->reset('columns')
               ->reset('order')
               ->limit(null)
               ->columns(new Zend_Db_Expr('COUNT(*)'));

        return $this->getAdapter()->fetchOne($select);
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
    
}


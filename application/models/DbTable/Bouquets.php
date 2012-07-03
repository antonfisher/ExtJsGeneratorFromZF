<?php

/**
 * Model for bouquets table
 *
 * @category  Models
 * @package   Application_Model_DbTable_Bouquets
 * @author    Anton Fischer <a.fschr@gmail.com>
 * @copyright 2012 (c) none
 * @license   http://none BSD License
 * @link      https://github.com/antonfisher/ExtJsGeneratorFromZF
 */
class Application_Model_DbTable_Bouquets extends ExtjsGenerator_Db_Table_Abstract
{

    protected $_name = 'bouquets';

    protected $_referenceMap = array(
        'Wrappers' => array(
            'columns'                       => 'id_wrapper',
            'refTableClass'                 => 'Application_Model_DbTable_Wrappers',
            'refExtjsGeneratorColumnsId'    => 'id',
            'refExtjsGeneratorColumnsTitle' => 'title',
            //'refExtjsGeneratorUseStore' => true,
        )
    );

    protected $_dependedTables = array('Application_Model_DbTable_BouquetsFlowers');

}


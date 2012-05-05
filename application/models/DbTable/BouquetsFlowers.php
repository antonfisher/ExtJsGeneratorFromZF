<?php

/**
 * Model for bouquets_flowers table
 *
 * @category  Models
 * @package   Application_Model_DbTable_BouquetsFlowers
 * @author    Anton Fischer <a.fschr@gmail.com>
 * @copyright 2012 (c) none
 * @license   http://none BSD License
 * @link      https://github.com/antonfisher/ExtJsGeneratorFromZF
 */
class Application_Model_DbTable_BouquetsFlowers extends ExtjsGenerator_Db_Table_Abstract
{

    protected $_name = 'bouquets_flowers';

    protected $_referenceMap = array(
        'Bouquets' => array(
            'columns'                       => 'id_bouquet',
            'refTableClass'                 => 'Application_Model_DbModel_Bouquets',
            'refExtjsGeneratorColumnsId'    => 'id',
            'refExtjsGeneratorColumnsTitle' => 'customer_name',
        ),
        'Flowers' => array(
            'columns'                       => 'id_flower',
            'refTableClass'                 => 'Application_Model_DbModel_Flowers',
            'refExtjsGeneratorColumnsId'    => 'id',
            'refExtjsGeneratorColumnsTitle' => 'title',
        )
    );

    protected $_dependedTables = array('Application_Model_DbModel_Bouquets', 'Application_Model_DbModel_Flowers');

}


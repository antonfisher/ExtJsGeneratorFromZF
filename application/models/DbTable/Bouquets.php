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
            'refColumns'                    => 'id',
            'refExtjsGeneratorColumnsId'    => 'id',
            'refExtjsGeneratorColumnsTitle' => 'title',
            //'refExtjsGeneratorUseStore' => true,
        )
    );

    /**
     * Many-to-many
     *
     * +----------+      +------------------+
     * | bouquets |      | bouquets_flowers |      +---------+
     * |----------|      |------------------|      | flowers |
     * | id       |<-----| id_bouquet       |      |---------|
     * | title    |      | id_flower        |----->| id      |
     * | ...      |      | ...              |      | title   |
     * +----------+      +------------------+      | ...     |
     *                                             +---------+
     *
     * @var array
     */
    protected $_referenceMapM2M = array(
        'Flowers' => array(
            'columns'                       => 'id',
            'columnsM2M'                    => 'id_bouquet',
            'refColumnsM2M'                 => 'id_flower',
            'refColumns'                    => 'id',
            'refTableClassM2M'              => 'Application_Model_DbTable_BouquetsFlowers',
            'refTableClass'                 => 'Application_Model_DbTable_Flowers',
            'refExtjsGeneratorColumnsId'    => 'id',
            'refExtjsGeneratorColumnsTitle' => 'title'
        )
    );

    protected $_dependedTables = array('Application_Model_DbModel_BouquetsFlowers');

}


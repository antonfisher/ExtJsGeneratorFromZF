<?php

/**
 * Model for flowers table
 *
 * @category  Models
 * @package   Application_Model_DbTable_Flowers
 * @author    Anton Fischer <a.fschr@gmail.com>
 * @copyright 2012 (c) none
 * @license   http://none BSD License
 * @link      https://github.com/antonfisher/ExtJsGeneratorFromZF
 */
class Application_Model_DbTable_Flowers extends ExtjsGenerator_Db_Table_Abstract
{

    protected $_name = 'flowers';

    protected $_dependedTables = array('Application_Model_DbModel_BouquetsFlowers');

}


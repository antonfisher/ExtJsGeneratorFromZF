<?php

/**
 * Test class
 *
 * @category  ExtjsGenerator
 * @package   Test
 * @author    Anton Fischer <a.fschr@gmail.com>
 * @copyright 2012 (c) none
 * @license   http://none BSD License
 * @link      https://github.com/antonfisher/ExtJsGeneratorFromZF
 */
class ExtjsGeneratorControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    /**
     * Set up
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->setupDatabase();
    }

    /**
     * Test database setup from /sql/data-example.sql
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function setupDatabase()
    {
        $config = Zend_Db_Table::getDefaultAdapter()->getConfig();
        $logFile = '/tmp/egfzf-data-example.log';
        $runCommand =
            "psql -h {$config["host"]} -U {$config["username"]} {$config["dbname"]} "
            . "< " . APPLICATION_PATH . "/../sql/data-example.sql 1>{$logFile} 2>{$logFile}";
        system($runCommand);
    }

    /**
     * Data provider - models/stores names
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return array
     */
    public function dataProviderTablesNames()
    {
        return array(
            array('Bouquets'),
            array('Flowers'),
            array('BouquetsFlowers'),
            array('Wrappers'),
        );
    }

    /**
     * Test javascript model code
     *
     * @param string $modelName model name
     *
     * @dataProvider dataProviderTablesNames()
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testModelAction($modelName)
    {
        // pass
        $this->dispatch("/js/app/model/{$modelName}.js");

        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('model');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->assertRegExp("/.*Ext\.define\(\'App\.model\.{$modelName}\'.*/", $this->getResponse()->getBody());
    }

    /**
     * Failure modelAction()
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testFailureModelAction()
    {
        // wrong
        $this->dispatch('/js/app/model/ERROR.js');

        $this->assertResponseCode(500);
        $this->assertController('error');
        $this->assertAction('error');
    }

    /**
     * Test javascript store code
     *
     * @param string $storeName store name
     *
     * @dataProvider dataProviderTablesNames()
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testStoreAction($storeName)
    {
        // pass
        $this->dispatch("/js/app/store/{$storeName}.js");
        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('store');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->assertRegExp("/.*Ext\.define\(\'App\.store\.{$storeName}\'.*/", $this->getResponse()->getBody());

        // wrong
        // no wrong response, datebase does't use :)
    }

    /**
     * Data provider - testStoreReadAction()
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return array array for test
     */
    public function dataProviderStoreReadAction()
    {
        return array(
            array(
                'Bouquets',
                array(
                    array(
                        'id'               => 1,
                        'customer_name'    => 'Bob',
                        'customer_phone'   => null,
                        'date'             => '2012-02-16',
                        'price'            => null,
                        'count_of_flowers' => '5',
                        'is_complete'      => true,
                        'id_wrapper'       => 2,
                    ),
                    array(
                        'id'               => 2,
                        'customer_name'    => 'Mike',
                        'customer_phone'   => '123-456-789',
                        'date'             => '2012-02-18',
                        'price'            => '100.50',
                        'count_of_flowers' => '10',
                        'is_complete'      => false,
                        'id_wrapper'       => 4,
                    ),
                    array(
                        'id'               => 3,
                        'customer_name'    => 'Alice',
                        'customer_phone'   => '123-456-789',
                        'date'             => '2012-02-20',
                        'price'            => '400.0',
                        'count_of_flowers' => '20',
                        'is_complete'      => false,
                        'id_wrapper'       => 1,
                    ),
                )
            ),
            array(
                'Flowers',
                array(
                    array('id' => '1', 'title' => 'Rose'),
                    array('id' => '2', 'title' => 'Tulip'),
                    array('id' => '3', 'title' => 'Narcissus'),
                    array('id' => '4', 'title' => 'Chamomile'),
                    array('id' => '5', 'title' => 'Dandelion'),
                )
            ),
            array(
                'BouquetsFlowers',
                array(
                    array('id' => '1', 'id_bouquet' => '1', 'id_flower' => '3'),
                    array('id' => '2', 'id_bouquet' => '1', 'id_flower' => '2'),
                    array('id' => '3', 'id_bouquet' => '2', 'id_flower' => '3'),
                    array('id' => '4', 'id_bouquet' => '2', 'id_flower' => '4'),
                    array('id' => '5', 'id_bouquet' => '2', 'id_flower' => '5'),
                    array('id' => '6', 'id_bouquet' => '3', 'id_flower' => '1'),
                )
            ),
            array(
                'Wrappers',
                array(
                    array('id' => '1', 'title' => 'Thin blue'),
                    array('id' => '2', 'title' => 'Thin red'),
                    array('id' => '3', 'title' => 'Thick yellow'),
                    array('id' => '4', 'title' => 'Wrinkled green'),
                )
            ),
        );
    }

    /**
     * Test store read action
     *
     * @param string $storeName store name
     * @param array  $arrResult store data
     *
     * @dataProvider dataProviderStoreReadAction()
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testStoreReadAction($storeName, $arrResult)
    {
        // pass
        $this->dispatch("/extjs-generator/store-read/dbmodel/{$storeName}/format/json");

        $arrJson = false;
        try {
            $arrJson = Zend_Json_Decoder::decode($this->getResponse()->getBody());
        } catch (Exception $e) {
            $arrJson = false;
        }

        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('store-read');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->assertInternalType('array', $arrJson);
        $this->assertArrayHasKey('success', $arrJson);
        $this->assertArrayHasKey('data', $arrJson);
        $this->assertInternalType('array', $arrJson['data']);
        $this->assertEquals($arrJson['data'], $arrResult);
    }

    /**
     * Failure storeReadAction()
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testFailureStoreReadAction()
    {
        // wrong
        $this->dispatch('/extjs-generator/store-read/dbmodel/ERROR/format/json');

        $this->assertResponseCode(500);
        $this->assertController('error');
        $this->assertAction('error');
    }

    /**
     * Data provider - testStoreCreateAction()
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return array array for test
     * @todo add another tables
     */
    public function dataProviderStoreCreateAction()
    {
        return array(
            array(
                'Bouquets',
                '{"id":4,"customer_name":"Pip","customer_phone":"123","date":"2012-07-09T00:00:00","price":500,'
                . '"count_of_flowers":3,"is_complete":true,"id_wrapper":"3"}',
                array('id' => '4', 'date' => '2012-07-09 00:00:00')
            ),
            array(
                'Bouquets',
                '[{"id":4,"customer_name":"Pip","customer_phone":"123","date":"2012-07-09T00:00:00","price":500,'
                . '"count_of_flowers":3,"is_complete":true,"id_wrapper":"3"}'
                . ',{"id":5,"customer_name":"Pop","customer_phone":"321","date":"2012-07-10T00:00:00","price":505,'
                . '"count_of_flowers":6,"is_complete":false,"id_wrapper":"2"}]',
                array(
                    '0' => array('id' => '4', 'date' => '2012-07-09 00:00:00'),
                    '1' => array('id' => '5', 'date' => '2012-07-10 00:00:00'),
                )
            ),
        );
    }

    /**
     * Test store create action
     *
     * @param string $storeName store name
     * @param string $rowBody   row body
     * @param array  $arrResult arr result
     *
     * @dataProvider dataProviderStoreCreateAction()
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testStoreCreateAction($storeName, $rowBody, $arrResult)
    {
        $this->getRequest()->setRawBody($rowBody);
        $this->getRequest()->setMethod('POST');
        $this->dispatch("/extjs-generator/store-create/dbmodel/{$storeName}/format/json");

        $arrRowBody = $this->_getArrayFromRowBody($rowBody, $arrResult);
        $arrJson = $this->_getArrayFromJson($this->getResponse()->getBody());

        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('store-create');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->assertInternalType('array', $arrJson);
        $this->assertArrayHasKey('success', $arrJson);
        $this->assertTrue($arrJson['success']);
        $this->assertArrayHasKey('data', $arrJson);
        $this->assertInternalType('array', $arrJson['data']);
        $this->assertEquals($arrJson['data'], array($arrRowBody));
    }

    /**
     * Data provider - testStoreUpdateAction()
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return array array for test
     * @todo add another tables
     */
    public function dataProviderStoreUpdateAction()
    {
        return array(
            array(
                'Bouquets',
                '{"id":1,"customer_name":"Pip","customer_phone":"123","date":"2012-07-09T00:00:00","price":500,'
                . '"count_of_flowers":3,"is_complete":true,"id_wrapper":"3"}',
                array('date' => '2012-07-09 00:00:00')
            ),
            array(
                'Bouquets',
                '[{"id":1,"customer_name":"Pip","customer_phone":"123","date":"2012-07-09T00:00:00","price":500,'
                . '"count_of_flowers":3,"is_complete":true,"id_wrapper":"3"}'
                . ',{"id":2,"customer_name":"Pop","customer_phone":"321","date":"2012-07-10T00:00:00","price":505,'
                . '"count_of_flowers":6,"is_complete":false,"id_wrapper":"2"}]',
                array(
                    '0' => array('date' => '2012-07-09 00:00:00'),
                    '1' => array('date' => '2012-07-10 00:00:00'),
                )
            ),
        );
    }

    /**
     * Test store update action
     *
     * @param string $storeName store name
     * @param string $rowBody   row body
     * @param array  $arrResult arr result (part)
     *
     * @dataProvider dataProviderStoreUpdateAction()
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testStoreUpdateAction($storeName, $rowBody, $arrResult)
    {
        $this->getRequest()->setRawBody($rowBody);
        $this->getRequest()->setMethod('POST');
        $this->dispatch("/extjs-generator/store-update/dbmodel/{$storeName}/format/json");

        $arrRowBody = $this->_getArrayFromRowBody($rowBody, $arrResult);
        $arrJson = $this->_getArrayFromJson($this->getResponse()->getBody());

        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('store-update');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->assertInternalType('array', $arrJson);
        $this->assertArrayHasKey('success', $arrJson);
        $this->assertTrue($arrJson['success']);
        $this->assertArrayHasKey('data', $arrJson);
        $this->assertInternalType('array', $arrJson['data']);
        $this->assertEquals($arrJson['data'], $arrRowBody);
    }

    /**
     * Data provider - testStoreDestroyAction()
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return array array for test
     * @todo add another tables
     */
    public function dataProviderStoreDestroyAction()
    {
        return array(
            array(
                'Bouquets',
                '{"id":3,"customer_name":"Alice","customer_phone":"123-456-789","date":"2012-02-20","price":"400",'
                . '"count_of_flowers":"20","is_complete":false,"id_wrapper":1}',
                array(
                    'success' => true,
                    'data' => array(),
                    'message' => '',
                    'totalCount' => null,
                ),
            ),
            array(
                'Bouquets',
                '[{"id":3,"customer_name":"Alice","customer_phone":"123-456-789","date":"2012-02-20","price":"400",'
                . '"count_of_flowers":"20","is_complete":false,"id_wrapper":1},'
                . '{"id":2,"customer_name":"Mike","customer_phone":"123-456-789","date":"2012-02-18","price":"100.5",'
                . '"count_of_flowers":"10","is_complete":false,"id_wrapper":4}]',
                array(
                    'success'    => true,
                    'data'       => array(),
                    'message'    => '',
                    'totalCount' => null,
                ),
            ),
            array(
                'Bouquets',
                '{"id":999,"customer_name":"Alice","customer_phone":"123-456-789","date":"2012-02-20","price":"400",'
                . '"count_of_flowers":"20","is_complete":false,"id_wrapper":1}',
                array(
                    'success'    => false,
                    'data'       => array(),
                    'message'    => "Store action: Row does not found in 'bouquets'.",
                    'totalCount' => null,
                ),
            ),
            array(
                'Bouquets',
                '{"customer_name":"Alice","customer_phone":"123-456-789","date":"2012-02-20","price":"400",'
                . '"count_of_flowers":"20","is_complete":false,"id_wrapper":1}',
                array(
                    'success'    => false,
                    'data'       => array(),
                    'message'    => "Store action: Primary keys for table 'bouquets' does not defined.",
                    'totalCount' => null,
                ),
            ),
        );
    }

    /**
     * * Test store destroy action
     *
     * @param string $storeName store name
     * @param string $rowBody   row body
     * @param array  $arrResult arr result (part)
     *
     * @dataProvider dataProviderStoreDestroyAction()
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testStoreDestroyAction($storeName, $rowBody, $arrResult)
    {
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setRawBody($rowBody);
        $this->dispatch("/extjs-generator/store-destroy/dbmodel/{$storeName}/format/json");

        $arrJson = $this->_getArrayFromJson($this->getResponse()->getBody());

        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('store-destroy');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->assertInternalType('array', $arrJson);
        $this->assertEquals($arrJson, $arrResult);

        $className = 'Application_Model_DbTable_' . $storeName;
        $model = new $className();
        foreach ($this->_getArrayFromRowBody($rowBody) as $item) {
            if (isset($item['id'])) {
                $this->assertEmpty(
                    $model->find($item['id'])->toArray(),
                    "Entry doesnt delete from database: {$item['id']}"
                );
            }
        }
    }

    /**
     * Test view action
     *
     * @param string $modelName model name
     *
     * @dataProvider dataProviderTablesNames()
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testViewAction($modelName)
    {
        // pass
        $this->dispatch("/extjs-generator/view/type/gridRowEdit/dbmodel/{$modelName}.js");

        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('view');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->assertRegExp(
            "/.*Ext\.define\(\'Extjs-generator\.view\.type\.gridRowEdit\.dbmodel\.{$modelName}\'.*/",
            $this->getResponse()->getBody()
        );
    }

    /**
     * Failure viewAction()
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testFailureViewAction()
    {
        // wrong
        $this->dispatch('/extjs-generator/view/type/gridRowEdit/dbmodel/ERROR.js');

        $this->assertResponseCode(500);
        $this->assertController('error');
        $this->assertAction('error');
    }

    /**
     * Get array from row body
     *
     * @param string $rowBody    row body string
     * @param array  $mergeArray array for merging
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return boolean|array
     */
    protected function _getArrayFromRowBody($rowBody, $mergeArray = array())
    {
        $arrRowBody = false;
        try {
            $arrRowBody = Zend_Json_Decoder::decode($rowBody);
            if (isset($arrRowBody[0])) {
                foreach ($arrRowBody as $key => $value) {
                    $arrRowBody[$key] = array_merge($value, $mergeArray[$key]);
                }
            } else {
                $arrRowBody = array_merge($arrRowBod, $mergeArray);
            }
        } catch (Exception $e) {
            $arrRowBody = false;
        }

        return $arrRowBody;
    }

    /**
     * Get array from json
     *
     * @param string $json json string
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return boolean|array
     */
    protected function _getArrayFromJson($json)
    {
        $arrJson = false;
        try {
            $arrJson = Zend_Json_Decoder::decode($json);
        } catch (Exception $e) {
            $arrJson = false;
        }

        return $arrJson;
    }

}


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
     * -
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
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
            array('Bouquets'),
            array('Flowers'),
            array('BouquetsFlowers'),
            array('Wrappers'),
        );
    }

    /**
     * Test store read action
     *
     * @param string $storeName store name
     *
     * @dataProvider dataProviderStoreReadAction()
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function testStoreReadAction($storeName)
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
//        $this->assertEquals($arrJson['data'], array(
//            array(
//                'id' => 1,
//                'customer_name' => 'Bob',
//                'customer_phone' => null,
//                'date' => '2012-02-16',
//                'price' => null,
//                'count_of_flowers' => '5',
//                'is_complete' => true,
//                'id_wrapper' => 2,
//            ),
//        );
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

//    /**
//     * @author Anton Fischer <a.fschr@gmail.com>
//     * @return null
//     */
//    public function testStoreCreateAction()
//    {
//
//    }

//    /**
//     * @author Anton Fischer <a.fschr@gmail.com>
//     * @return null
//     */
//    public function testStoreUpdateAction()
//    {
//
//    }

//    /**
//     * @author Anton Fischer <a.fschr@gmail.com>
//     * @return null
//     */
//    public function testStoreDestroyAction()
//    {
//
//    }

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

}


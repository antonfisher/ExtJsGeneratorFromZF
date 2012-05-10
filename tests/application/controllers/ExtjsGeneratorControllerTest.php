<?php

class ExtjsGeneratorControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    /**
     * /js/app/model/Bouquets.js
     */
    public function testModelAction()
    {
        // pass
        $this->dispatch('/js/app/model/Bouquets.js');
        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('model');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->assertEquals((mb_stripos($this->getResponse()->getBody(), 'App.model.Bouquets') !== false), true);

        // wrong
        $this->dispatch('/js/app/model/ERROR.js');
        $this->assertResponseCode(500);
        $this->assertController('error');
        $this->assertAction('error');
    }

    /**
     * /js/app/store/Bouquets.js
     */
    public function testStoreAction()
    {
        // pass
        $this->dispatch('/js/app/store/Bouquets.js');
        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('store');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->assertEquals((mb_stripos($this->getResponse()->getBody(), 'App.store.Bouquets') !== false), true);

        // wrong
        // no wrong response, datebase does't use :)
    }

    /**
     * /extjs-generator/store-read/dbmodel/Bouquets/format/json
     */
    public function testStoreReadAction()
    {
        // pass
        $this->dispatch('/extjs-generator/store-read/dbmodel/Bouquets/format/json');
        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('store-read');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');

        $arrJson = false;
        try {
            $arrJson = Zend_Json_Decoder::decode($this->getResponse()->getBody());
        } catch (Exception $e) {
            $arrJson = false;
        }

        $this->assertEquals(is_array($arrJson), true);
        $this->assertEquals($arrJson['success'], true);
        $this->assertEquals(is_array($arrJson['data']), true);

        // wrong
        $this->dispatch('/extjs-generator/store-read/dbmodel/ERROR/format/json');
        $this->assertResponseCode(500);
        $this->assertController('error');
        $this->assertAction('error');
    }

//    /**
//     *
//     */
//    public function testStoreCreateAction()
//    {
//
//    }

//    /**
//     *
//     */
//    public function testStoreUpdateAction()
//    {
//
//    }

//    /**
//     *
//     */
//    public function testStoreDestroyAction()
//    {
//
//    }

    /**
     *
     */
    public function testViewAction()
    {
        // pass
        $this->dispatch('/extjs-generator/view/type/gridRowEdit/dbmodel/Bouquets.js');
        $this->assertResponseCode(200);
        $this->assertController('extjs-generator');
        $this->assertAction('view');
        $this->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->assertEquals(
            (
                mb_stripos(
                    $this->getResponse()->getBody(),
                    'Extjs-generator.view.type.gridRowEdit.dbmodel.Bouquets'
                ) !== false
            ),
            true
        );

        // wrong
        $this->dispatch('/extjs-generator/view/type/gridRowEdit/dbmodel/ERROR.js');
        $this->assertResponseCode(500);
        $this->assertController('error');
        $this->assertAction('error');
    }

}


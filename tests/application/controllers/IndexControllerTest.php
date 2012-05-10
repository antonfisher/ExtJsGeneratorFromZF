<?php

class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    /**
     *
     */
    public function testIndexAction()
    {
        // pass
        $this->dispatch('/');
        $this->assertResponseCode(200);
        $this->assertController('index');
        $this->assertAction('index');
        $this->assertEquals((mb_stripos($this->getResponse()->getBody(), 'Loading...') !== false), true);
    }

}


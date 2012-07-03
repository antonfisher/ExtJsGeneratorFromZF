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
class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
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
     * Test index action
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
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


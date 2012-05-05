<?php

/**
 * Extjs code generator controller
 *
 * @category  Controllers
 * @package   ExtjsGeneratorController
 * @author    Anton Fischer <a.fschr@gmail.com>
 * @copyright 2012 (c) none
 * @license   http://none BSD License
 * @link      https://github.com/antonfisher/ExtJsGeneratorFromZF
 */
class ExtjsGeneratorController extends Zend_Controller_Action
{

    /**
     * Init
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function init()
    {
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_response->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->_helper->viewRenderer->setScriptAction('js');
    }

    /**
     * Generate Extjs model
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function modelAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->getModelCode($this->_getParam('dbmodel'));
    }

    /**
     * Generate Extjs store
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function storeAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->getStoreCode($this->_getParam('dbmodel'));
    }

    /**
     * Generate Extjs store read action
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function storeReadAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeAction(
            ExtjsGenerator_ExtjsGenerator::STORE_ACTION_READ,
            $this->getRequest()
        );
    }

    /**
     * Generate Extjs store create action
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function storeCreateAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeAction(
            ExtjsGenerator_ExtjsGenerator::STORE_ACTION_CREATE,
            $this->getRequest()
        );
    }

    /**
     * Generate Extjs store update action
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function storeUpdateAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeAction(
            ExtjsGenerator_ExtjsGenerator::STORE_ACTION_UPDATE,
            $this->getRequest()
        );
    }

    /**
     * Generate Extjs store destroy action
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function storeDestroyAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeAction(
            ExtjsGenerator_ExtjsGenerator::STORE_ACTION_DESTROY,
            $this->getRequest()
        );
    }

    /**
     * Generate Extjs view (grid, form, window ..)
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function viewAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->getViewCode($this->_getParam('type'), $this->_getParam('dbmodel'));
    }

}


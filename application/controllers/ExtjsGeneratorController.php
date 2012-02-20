<?php

class ExtjsGeneratorController extends Zend_Controller_Action
{

    public function init()
    {
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_response->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->_helper->viewRenderer->setScriptAction('js');

        //ExtJS POST/PUT data
        //$this->_jsonData = Extjs::getArrayFromRawBody($this->getRequest()->getRawBody());

    }
    
    public function modelAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->getModelCode($this->_getParam('dbmodel'));
    }
    
    public function storeAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->getStoreCode($this->_getParam('dbmodel'));
    }
    
    public function storeReadAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeAction(
            ExtjsGenerator_ExtjsGenerator::STORE_ACTION_READ,
            $this->getRequest()
        );
    }
    
    public function storeCreateAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeAction(
            ExtjsGenerator_ExtjsGenerator::STORE_ACTION_CREATE,
            $this->getRequest()
        );
    }
    
    public function storeUpdateAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeAction(
            ExtjsGenerator_ExtjsGenerator::STORE_ACTION_UPDATE,
            $this->getRequest()
        );
    }
    
    public function storeDestroyAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeAction(
            ExtjsGenerator_ExtjsGenerator::STORE_ACTION_DESTROY,
            $this->getRequest()
        );
    }
    
    public function viewAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->getViewCode($this->_getParam('type'), $this->_getParam('dbmodel'));
    }

}


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
        $this->view->jsCode = $extjsGenerator->storeRead($this->_getParam('dbmodel'), $this->_request->getParams());
    }
    
    public function storeCreateAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeCreate($this->_getParam('dbmodel'), $this->_request->getParams());
    }
    
    public function storeUpdateAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeUpdate($this->_getParam('dbmodel'), $this->_request->getParams());
    }
    
    public function storeDeleteAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->storeDelete($this->_getParam('dbmodel'), $this->_request->getParams());
    }
    
    public function viewAction()
    {
        $extjsGenerator = new ExtjsGenerator_ExtjsGenerator();
        $this->view->jsCode = $extjsGenerator->getViewCode($this->_getParam('type'), $this->_getParam('dbmodel'));
    }

}


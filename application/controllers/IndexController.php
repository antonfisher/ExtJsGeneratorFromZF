<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->headTitle('ExtJs from ZF');
        $this->view->headLink()->appendStylesheet('js/extjs/resources/css/ext-all.css');
        $this->view->headScript()->appendFile('js/extjs/bootstrap.js', 'text/javascript')
                                 ->appendFile('js/app.js', 'text/javascript');
    }

    public function indexAction()
    {
        // action body
    }

}


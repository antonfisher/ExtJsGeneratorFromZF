<?php

/**
 * Default index controller
 *
 * @category  Controllers
 * @package   IndexController
 * @author    Anton Fischer <a.fschr@gmail.com>
 * @copyright 2012 (c) none
 * @license   http://none BSD License
 * @link      https://github.com/antonfisher/ExtJsGeneratorFromZF
 */
class IndexController extends Zend_Controller_Action
{

    /**
     * Init
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function init()
    {
        $this->view->headTitle('ExtJs from ZF');
        $this->view->headLink()->appendStylesheet('js/extjs/resources/css/ext-all.css');
        $this->view->headScript()->appendFile('js/extjs/bootstrap.js', 'text/javascript')
                                 ->appendFile('js/app.js', 'text/javascript');
    }

    /**
     * Index action
     *
     * @author Anton Fischer <a.fschr@gmail.com>
     * @return null
     */
    public function indexAction()
    {
        // action body
    }

}


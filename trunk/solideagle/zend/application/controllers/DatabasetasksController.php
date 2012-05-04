<?php

use solideagle\scripts\UpdateConfig;

use solideagle\scripts\InitialAdImport;

use solideagle\scripts\initdb;

class DatabasetasksController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       $this->view->themethods = get_class_methods($this);
    }

    public function startcleanAction()
    {
        initdb::startclean();
    }

    public function initialAdImportAction()
    {
      	InitialAdImport::doImport();
    }

    public function updateconfigAction()
    {
        UpdateConfig::update();
    }


}








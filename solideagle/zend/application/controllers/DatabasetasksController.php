<?php

use solideagle\scripts\initdb;

class DatabasetasksController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function startcleanAction()
    {
        initdb::startclean();
    }


}



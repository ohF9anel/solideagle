<?php

require_once 'data_access/Type.php';
use DataAccess\Type;

class UsersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$this->view->types = Type::getAll();
    }


}


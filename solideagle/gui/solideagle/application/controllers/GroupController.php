<?php

include_once 'data_access/Group.php';

use DataAccess\Group;

class GroupController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       $this->view->grouptree = Group::getTree();
    }
    
    


}


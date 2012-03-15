<?php

require_once 'data_access/GroupTaskQueue.php';

use DataAcces\GroupTaskQueue;

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       $this->view->groupTaskQueue = GroupTaskQueue::getTasksToRun();
    }


}


<?php


require_once 'data_access/TaskQueue.php';

use DataAccess\TaskQueue;



class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       	$this->view->taskQueue = TaskQueue::getTasksToRun();
    }


}


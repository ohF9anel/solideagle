<?php



require_once 'data_access/GroupTaskQueue.php';
require_once 'data_access/PersonTaskQueue.php';

use DataAcces\GroupTaskQueue;
use DataAcces\PersonTaskQueue;

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       	$this->view->groupTaskQueue = GroupTaskQueue::getTasksToRun();
    	$this->view->personTaskQueue = PersonTaskQueue::getTasksToRun();
    }


}


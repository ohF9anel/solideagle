<?php




use solideagle\data_access\Group;
use solideagle\data_access\TaskQueue;



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

    public function groupsandusersAction()
    {
       $this->view->groups = Group::getTree();
    }


}




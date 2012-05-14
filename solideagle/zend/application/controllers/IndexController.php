<?php




use solideagle\data_access\Group;
use solideagle\data_access\platforms;
use solideagle\data_access\TaskQueue;



class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       	$this->view->taskQueue = array();
       	$this->view->taskQueue[] = 	TaskQueue::getTasksToRunForPlatform(platforms::PLATFORM_AD,0);
       	$this->view->taskQueue[] = 	TaskQueue::getTasksToRunForPlatform(platforms::PLATFORM_GAPP,0);
       	$this->view->taskQueue[] = 	TaskQueue::getTasksToRunForPlatform(platforms::PLATFORM_SMARTSCHOOL,0);
    }

    public function groupsandusersAction()
    {
       $this->view->groups = Group::getTree();
    }



}






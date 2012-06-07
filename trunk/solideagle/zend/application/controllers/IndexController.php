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
       	$this->view->taskQueue[] = 	TaskQueue::getTasksToRunForPlatformDisplayLimit(platforms::PLATFORM_AD);
       	$this->view->taskQueue[] = 	TaskQueue::getTasksToRunForPlatformDisplayLimit(platforms::PLATFORM_GAPP);
       	$this->view->taskQueue[] = 	TaskQueue::getTasksToRunForPlatformDisplayLimit(platforms::PLATFORM_SMARTSCHOOL);
       	
       	$this->view->taskQueueAmount = array();
       	$this->view->taskQueueAmount[] = 	TaskQueue::getAmountOfTasksToRunForPlatform(platforms::PLATFORM_AD);
       	$this->view->taskQueueAmount[] = 	TaskQueue::getAmountOfTasksToRunForPlatform(platforms::PLATFORM_GAPP);
       	$this->view->taskQueueAmount[] = 	TaskQueue::getAmountOfTasksToRunForPlatform(platforms::PLATFORM_SMARTSCHOOL);
       	
       
    }

    public function groupsandusersAction()
    {
       $this->view->groups = Group::getTree();
    }



}






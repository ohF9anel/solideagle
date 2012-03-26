<?php
namespace smartschoolplugin;

require_once 'data_access/BaseTask.php';
require_once 'data_access/TaskQueue.php';

use DataAccess\BaseTask;
use DataAccess\TaskQueue;

class groupmanager implements TaskInterface
{
	
	public function __construct($taskid = NULL,$groupid= NULL)
	{
		parent::__construct($taskid, $groupid, parent::TypeGroup);
	}
	
	public function getParams()
	{
		
	}
	
	public function runTask($taskqueue)
	{
		$taskqueue->setErrormessages("smartschool not implemented yet");
		return false;
	}
	
	public function createTaskFromParams($params)
	{
	
	}
	
	public function prepareAddGroup()
	{
		$this->addToQueue(array());
	}
	
	
	
}
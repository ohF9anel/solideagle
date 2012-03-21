<?php
namespace smartschoolplugin;

require_once 'data_access/BaseTask.php';
require_once 'data_access/TaskQueue.php';

use DataAccess\BaseTask;
use DataAccess\TaskQueue;

class groupmanager extends \DataAccess\BaseTask
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
		
	}
	
	public function createTaskFromParams($params)
	{
	
	}
	
	public function prepareAddGroup()
	{
		$this->addToQueue(array());
	}
	
	
	
}
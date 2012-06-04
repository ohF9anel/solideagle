<?php

use solideagle\scripts\ga;

use solideagle\data_access\helpers\UnicodeHelper;

use solideagle\data_access\Group;

use solideagle\scripts\daemon;

use solideagle\scripts\oldDeleter;


use solideagle\scripts\UpdateConfig;

use solideagle\scripts\InitialAdImport;

use solideagle\scripts\initdb;

class DatabasetasksController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		
		$newgroup = Group::getGroupByName("externen");
		
		$parents = Group::getParents($newgroup);
		

		
		ga\oumanager::prepareAddOu($parents,$newgroup);
		ga\groupmanager::prepareAddGroup($newgroup);
		
		if ($parents[0] != null)
			ga\groupmanager::prepareAddGroupToGroup($parents[0], $newgroup);
		
		
		
		$this->view->themethods = get_class_methods($this);
	}

	public function startcleanAction()
	{
		initdb::startclean();
	}

	public function initialAdImportAction()
	{
		InitialAdImport::doImport();
	}

	public function updateconfigAction()
	{
		UpdateConfig::update();
	}

	public function deleteoldgroupsAction()
	{
		oldDeleter::deleteOldGroups();
	}

	public function deleteoldusersAction()
	{
		oldDeleter::deleteOldUsers();
	}

	public function rundaemonAction()
	{
		
	 $blah = solideagle\scripts\daemon::doNothing();
	}


}












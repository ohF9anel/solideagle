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


	//advanced functions that should one day get a gui!
	private function fixGroupOuOnGapp($groupname)
	{
		$newgroup = Group::getGroupByName($groupname);

		$parents = Group::getParents($newgroup);

		if(false) //toggle ou creation
		{
			ga\oumanager::prepareAddOu($parents,$newgroup);
		}

		if(true) //toggle group creation
		{
			ga\groupmanager::prepareAddGroup($newgroup);
				
		}
		
		if(false) //toggle group membership
		{
			if ($parents[0] != null)
				ga\groupmanager::prepareAddGroupToGroup($parents[0], $newgroup);
		}


	}

	private function fixGroupMembershipOnGapp($groupname)
	{
		$newgroup = Group::getGroupByName($groupname);

		$parents = Group::getParents($newgroup);

		if ($parents[0] != null)
			ga\groupmanager::prepareAddGroupToGroup($parents[0], $newgroup);
	}

	public function indexAction()
	{
		
		
		foreach (Group::getAllChilderen(Group::getGroupByName("leerlingen")) as $groupp)
		{
			$this->fixGroupMembershipOnGapp($groupp->getName());
		}

		
		


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












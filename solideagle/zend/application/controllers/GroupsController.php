<?php

use solideagle\data_access\Person;

use solideagle\data_access\Type;
use solideagle\scripts\groupmanager;
use solideagle\data_access\Group;
use solideagle\utilities\SuperEntities;

class GroupsController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		// action body
	}

	public function editgroupAction()
	{
		$this->_helper->layout()->disableLayout();

		if(($this->view->group = Group::getGroupById($this->getRequest()->getParam("gid")))===null)
		{
			echo "Deze groep bestaat niet";
			exit();
		}

		$this->view->groups = Group::getAllGroups();
		$this->view->types = Type::getAll();
	}

	public function getgroupAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$arr = $this->groupsToJson(Group::getTree());

		echo json_encode($arr);
	}

	public function getgroupbreadcrumbsAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		if(($group = Group::getGroupById($this->getRequest()->getParam("groupid")))===null)
		{
			echo "Deze groep bestaat niet";
			exit();
		}

		$breadcrumbs= " ";

		foreach(array_reverse(Group::getParents($group)) as $parentgroup)
		{
			$breadcrumbs .= $parentgroup->getName() . " > ";
		}

		$breadcrumbs  .= $group->getName();

		echo $breadcrumbs;

	}

	public function updategrouppostAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
			
		if(($oldgroup = Group::getGroupById($this->getRequest()->getParam("groupid")))===null)
		{
			echo "Deze groep bestaat niet";
			exit();
		}
			
		$newGroup = new Group();
		$newGroup->setId($oldgroup->getId());
		$newGroup->setName($this->getRequest()->getParam("groupName"));
		$newGroup->setDescription($this->getRequest()->getParam("groupDescription"));
			
		foreach($this->getRequest()->getPost('ptype', array()) as $id)
		{
			$newGroup->addType(new Type($id));
		}
			
		Group::updateGroup($newGroup);
			
		//only should update externally when name changes
		if($newGroup->getName() !== $oldgroup->getName())
			groupmanager::Modify(Group::getParents($newGroup),$oldgroup,$newGroup);
			
		if($this->getRequest()->getParam("selectGroup") !== "ignore")
		{
			$newparentid = $this->getRequest()->getParam("selectGroup");
			$newGroup->setParentId($newparentid);

			if(!(count(Group::getChilderen($newGroup)) > 0))
			{

				$oldparents = Group::getParents($newGroup);
				$oldchildren = Group::getChilderen($newGroup);
					
				Group::moveGroup($newGroup);
					
				$newparents = Group::getParents($newGroup);
				$newchildren = Group::getChilderen($newGroup);
					
				groupmanager::Move($oldparents,$newparents,$newGroup,$oldchildren,$newchildren);
			}else{
				echo "Deze groep kan niet verplaatst worden omdat hij subgroepen bevat.";
			}
		}

	}

	public function deletegrouppostAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
			
		$data = $this->getRequest()->getParams();

		if(($groupToDelete = Group::getGroupById($this->getRequest()->getParam("groupid"))) === NULL)
		{
			echo "Geen groep geselecteerd";
			return;
		}

		if($this->getRequest()->getParam("delete") !==NULL && $this->getRequest()->getParam("deletesure") !== NULL)
		{

			if(count(Group::getChilderen($groupToDelete)) !== 0)
			{
				echo "Deze groep kan niet verwijderd worden omdat hij subgroepen bevat.";
				return;
			}

			if(count(Person::getPersonIdsByGroup($groupToDelete->getId())) !== 0)
			{
				echo "Deze groep kan niet verwijderd worden omdat hij gebruikers bevat.";
				return;
			}

			groupmanager::Delete(Group::getParents($groupToDelete), $groupToDelete);

			Group::delGroupById($groupToDelete->getId());
		}else{
			echo "U was niet zeker, groep niet verwijderd";
		}

	}

	public function addsubgroupAction()
	{
		$this->_helper->layout()->disableLayout();
			
		$data = $this->getRequest()->getParams();

		$groupid = $this->getRequest()->getParam("gid");
		if(Group::getGroupById($groupid) !== NULL)
		{
			$this->view->groupid = $groupid;
			$this->view->types = Type::getAll();
			$this->view->parentTypes = Group::getGroupById($groupid)->getTypes();
		}
	}

	public function addsubgrouppostAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
			



		$parentgroupid = $this->getRequest()->getParam("parentgroupid");
		if(Group::getGroupById($parentgroupid) === NULL)
		{
			echo "Parent groep bestaat niet.";
			return;
		}
			
		$newSubGroup = new Group();
		$newSubGroup->setParentId($parentgroupid);
		$newSubGroup->setName($this->getRequest()->getParam("groupName"));
		$newSubGroup->setDescription($this->getRequest()->getParam("groupDescription"));
			
		$newsubgroupid = Group::addGroup($newSubGroup);
			
		if($newsubgroupid === NULL)
			echo "Opslaan mislukt";

		$newSubGroup->setId($newsubgroupid);
			
		foreach($this->getRequest()->getPost('ptype', array()) as $id)
		{
			$newSubGroup->addType(new Type($id));
		}
			
			
		groupmanager::Add(Group::getParents($newSubGroup), $newSubGroup);

	}

	public function deletegroupAction()
	{
		$this->_helper->layout()->disableLayout();
		
		if(($this->view->group = Group::getGroupById($this->getRequest()->getParam("gid"))) === NULL)
		{
			echo "Deze groep bestaat niet";
			exit();
		}
	}

	private function groupsToJson($roots,$isfirst = true)
	{
		if(count($roots) === 0)
			return false;

		$thisrootarr = array();

		foreach($roots as $group)
		{
			$arr = array("data" => array("title" =>  $group->getName() . " (" . $group->getTotalAmountOfMembers() . ")" . " (" . $group->getAmountOfMembers() . ")",

					"attr" => array("href" => "javascript:void(0)")), "attr" => array("id" => "tree" . $group->getId(),"groupid" => $group->getId(),"groupname" =>  SuperEntities::encode($group->getName())));

			if($isfirst)
			{
				$arr["state"] = "open";
				$isfirst = false;
			}

			if(($children = $this->groupsToJson($group->getChildGroups(),$isfirst)) != false)
			{
				$arr["children"] = $children;
			}

			$thisrootarr[] = $arr;
		}

		return $thisrootarr;

	}

}




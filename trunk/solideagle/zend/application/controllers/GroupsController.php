<?php

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

		$data = $this->_request->getParams();

		if(isset($data["gid"]))
		{
			$groupid = $data["gid"];
			$this->view->group = Group::getGroupById($groupid);
		}else{
			exit();
		}
		
		
		$this->view->groups = Group::getAllGroups();
		$this->view->types = Type::getAll();
	

    }

    public function getgroupAction()
    {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$arr = groupsToJson(Group::getTree());

		echo json_encode($arr);
    }

    public function getgroupbreadcrumbsAction()
    {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$data = $this->_request->getParams();
		
		if(!isset($data["groupid"]))
		{
			return;
		}
			
		$groupid = $data["groupid"];
		
		$group = Group::getGroupById($groupid);
		
		
		
		if($group === NULL)
			return;
		
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
		 
		$data = $this->getRequest()->getParams();
		
	
		 
		if(!isset($data["groupid"]))
		{
			echo "ID not set\n";
			exit();
		}
		 
		$groupid = $data["groupid"];
		
			$oldgroup = Group::getGroupById($groupid);
			
			
			$newGroup = new Group();
			$newGroup->setId($oldgroup->getId());
			$newGroup->setName($data["groupName"]);
			$newGroup->setDescription($data["groupDescription"]);
			
			Group::updateGroup($newGroup);
			
			//only should update when name changes
			if($newGroup->getName() !== $oldgroup->getName())
				groupmanager::Modify(Group::getParents($newGroup),$oldgroup,$newGroup);
			
			if($data["selectGroup"] !== "ignore")
			{
				$newparentid = $data["selectGroup"];
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
		
		if(!isset($data["groupid"]))
		{
			
			return;
		}
			
		$groupid = $data["groupid"];
		
		if(isset($data["delete"]) && isset($data["deletesure"]))
		{
			$deletethisgroup = Group::getGroupById($groupid);
			if(count(Group::getChilderen($deletethisgroup)) !== 0)
			{
				echo "Deze groep kan niet verwijderd worden omdat hij subgroepen bevat.";
				return;
			}
		
			//TODO: check for users
		
			if(count(array()) !== 0)
			{
				echo "Deze groep kan niet verwijderd worden omdat hij gebruikers bevat.";
				return;
			}
		
			$grp = Group::getGroupById($groupid);
		
			groupmanager::Delete(Group::getParents($grp), $grp);
		
			Group::delGroupById($groupid);
		}else{
			echo "U was niet zeker, groep niet verwijderd";
		}
		
    }

    public function addsubgroupAction()
    {
		$this->_helper->layout()->disableLayout();
		 
		$data = $this->getRequest()->getParams();
		
		
		 
		if(isset($data["gid"]))
		{
			$groupid = $data["gid"];
			if(Group::getGroupById($groupid) != NULL)
			{
				$this->view->groupid = $groupid;
				$this->view->types = Type::getAll();
			}

		}
		 
    }

    public function addsubgrouppostAction()
    {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		 
		$data = $this->getRequest()->getParams();
		
	
		 
		if(isset($data["parentgroupid"]) && isset($data["groupDescription"]) && isset($data["groupName"]))
		{
			$parentgroupid = $data["parentgroupid"];
			if(Group::getGroupById($parentgroupid) == NULL)
			{
				echo "Parent groep bestaat niet.";
				return;
			}
			
			$newSubGroup = new Group();
			$newSubGroup->setParentId($parentgroupid);
			$newSubGroup->setName($data["groupName"]);
			$newSubGroup->setDescription($data["groupDescription"]);
			
			$newsubgroupid = Group::addGroup($newSubGroup);

			$newSubGroup->setId($newsubgroupid);
			
			
			
			groupmanager::Add(Group::getParents($newSubGroup), $newSubGroup);
			
			

		}else{
			echo "Alle velden moetten ingevuld zijn";
		}
		 
		 
    }

    public function deletegroupAction()
    {
    	$this->_helper->layout()->disableLayout();

		$data = $this->_request->getParams();

		if(isset($data["gid"]))
		{
			$groupid = $data["gid"];
			$this->view->group = Group::getGroupById($groupid);
		}else{
			exit();
		}
    }


}

function groupsToJson($roots,$isfirst = true)
{
	if(count($roots) === 0)
		return false;

	$thisrootarr = array();

	foreach($roots as $group)
	{
		$arr = array("data" => array("title" =>  $group->getName(), "attr" => array("href" => "javascript:void(0)")), "attr" => array("id" => "tree" . $group->getId(),"groupid" => $group->getId(),"groupname" =>  SuperEntities::encode($group->getName())));

		if($isfirst)
		{
			$arr["state"] = "open";
			$isfirst = false;
		}

		if(($children = groupsToJson($group->getChildGroups(),$isfirst)) != false)
		{
			$arr["children"] = $children;
		}

		$thisrootarr[] = $arr;
	}

	return $thisrootarr;

}


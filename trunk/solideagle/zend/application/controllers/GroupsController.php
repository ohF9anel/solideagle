<?php

use solideagle\scripts\OUmanager;
use solideagle\data_access\Group;
use solideagle\utilities\FastJSON;

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

	

	}
	
	public function getgroupAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		
		 
		$arr = groupsToJson(Group::getTree());
		 
		 
	

		echo json_encode($arr);
		 
		
		
	
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

			OUManager::Delete(Group::getParents($grp), $grp);

			Group::delGroupById($groupid);
		}else{
			
			$oldgroup = Group::getGroupById($groupid);
			
			
			$newGroup = new Group();
			$newGroup->setId($oldgroup->getId());
			$newGroup->setName($data["groupName"]);
			$newGroup->setDescription($data["groupDescription"]);
			
			Group::updateGroup($newGroup);
			
			//only should update when name changes
			if($newGroup->getName() !== $oldgroup->getName())
				OUManager::Modify(Group::getParents($newGroup),$oldgroup,$newGroup);
			
			if($data["selectGroup"] !== "ignore")
			{
				$newparentid = $data["selectGroup"];
				$newGroup->setParentId($newparentid);
				
				if(!(count(Group::getChilderen($newGroup)) > 0))
				{
				
					$oldparents = Group::getParents($newGroup);
					
					Group::moveGroup($newGroup);
					
					$newparents = Group::getParents($newGroup);
					
					OUManager::Move($oldparents,$newparents,$newGroup);
				}else{
					echo "Kan deze OU niet verplaatsen omdat hij subou's bevat";
				}
			}
			
			
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
			
			
			
			OUmanager::Add(Group::getParents($newSubGroup), $newSubGroup);
			
			

		}else{
			echo "Alle velden moetten ingevuld zijn";
		}
		 
		 
	}


}


function superentities( $str ){
	// get rid of existing entities else double-escape
	$str = html_entity_decode(stripslashes($str),ENT_QUOTES,'UTF-8');
	$ar = preg_split('/(?<!^)(?!$)/u', $str );  // return array of every multi-byte character
	$str2 = "";
	foreach ($ar as $c){
		$o = ord($c);
		if ( (strlen($c) > 1) || /* multi-byte [unicode] */
				($o <32 || $o > 126) || /* <- control / latin weirdos -> */
				($o >33 && $o < 40) ||/* quotes + ambersand */
				($o >59 && $o < 63) /* html */
		) {
			// convert to numeric entity
			$c = mb_encode_numericentity($c,array (0x0, 0xffff, 0, 0xffff), 'UTF-8');
		}
		$str2 .= $c;
	}
	return $str2;
}

function groupsToJson($roots,$isfirst = true)
{
	if(count($roots) === 0)
		return false;

	$thisrootarr = array();

	foreach($roots as $group)
	{
		$arr = array("data" => array("title" =>  $group->getName(), "attr" => array("href" => "javascript:void(0)")), "attr" => array("id" => "tree" . $group->getId(),"groupid" => $group->getId(),"groupname" =>  superentities($group->getName())));

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
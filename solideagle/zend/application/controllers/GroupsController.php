<?php


require_once 'data_access/Group.php';
require_once 'scripts/ad/groupmanager.php';

use DataAccess\Group;
use DataAcces\GroupTaskQueue;
use adplugin\groupmanager;


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
    	
    	
    		//TODO: ADD TO QUEUE
    		
    		
    		$grp = Group::getGroupById($groupid);
    		
    		$gm = new groupmanager("27",$groupid);
    		
    		$gm->prepareDeleteGroup(Group::getParents($grp), $grp);
    		
    		Group::delGroupById($groupid);
    	}
    	
    	//var_dump($data);
    	
    }
    
    public function addsubgroupAction()
    {
    	$this->_helper->layout()->disableLayout();
    	
    	$data = $this->_request->getParams();
    	
    	if(isset($data["gid"]))
    	{
    		$groupid = $data["gid"];
    		if(Group::getGroupById($groupid) != NULL)
    		{
    			$this->view->groupid = $groupid;
    		}
    		
    	}
    	
    }


}




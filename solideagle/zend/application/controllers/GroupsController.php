<?php

require_once 'data_access/Group.php';
require_once 'data_access/GroupTaskQueue.php';

use DataAccess\Group;
use DataAcces\GroupTaskQueue;

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




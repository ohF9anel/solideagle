<?php

use solideagle\scripts\smartschool\usermanager;

use solideagle\Config;

use solideagle\data_access\platforms;

use solideagle\data_access\Type;
use solideagle\data_access\TaskTemplate;

use solideagle\scripts\ad\homefoldermanager;

use solideagle\data_access\Person;

use solideagle\logging\Logger;





class TaskTemplateController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		 

	}
        
        public function managetemplatesAction()
	{
		 

	}


	public function managetasktemplatesAction()
	{
		$this->_helper->layout()->disableLayout();

		$this->view->defaults = new stdClass();

     //  $this->view->taskTemplates = $this->templatesToJson(TaskTemplate::getAllTemplates());
	}
	
	public function removetasktemplateAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		if(($taskname = $this->getRequest()->getPost("templatename",false)))
		{
			TaskTemplate::delTaskTemplateByName($taskname);
		}
	}
	
	public function gettemplatesAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		echo $this->templatesToJson(TaskTemplate::getAllTemplates());
		return;
	}
	
	/**
	 * 
	 * @param array(TaskTemplate) $templates
	 */
	private function templatesToJson($templates)
	{
		$finalarr = array();
		foreach($templates as $template)
		{
			$finalarr[] = $template->getTemplateName();
		}
		
		return json_encode($finalarr);
	}


}










<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype()
	{	
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');
	}
	
	protected function _initRequest()
	{
		
	
		$this->bootstrap('FrontController');
	
		$front = $this->getResource('FrontController');
		$request = new Zend_Controller_Request_Http();
	
		$front->setRequest($request);
	}
	
	
	
	protected function _initViewHelpers()
	{
		$view = $this->getResource('view');
		
		$view->SE_path = $view->serverUrl().$view->baseUrl();
		
		$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
	
		$path = $view->serverUrl().$view->baseUrl();
		
		
		$view = $this->getResource('view');
		$view->jQuery()->addStylesheet($path.'/css/ui-custom/jquery-ui-1.8.18.custom.css')
		->setLocalPath($path.'/js/jquery-1.7.1.min.js')
		->setUiLocalPath($path.'/js/jquery-ui-1.8.18.custom.min.js');
		$view->jQuery()->enable();
		$view->jQuery()->uiEnable();
	
	}
	

	
	

}


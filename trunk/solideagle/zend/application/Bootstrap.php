<?php



class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
	/**
	 * Bootstrap autoloader for application resources
	 *
	 * @return Zend_Application_Module_Autoloader
	 */
	protected function _initAutoload()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		
		set_include_path(get_include_path().PATH_SEPARATOR."../../");
		
		$autoloader->pushAutoloader(
				function($class){

			spl_autoload($class);
			
		},"solideagle");
		
		return $autoloader;
	}
	
	
	protected function _initDoctype()
	{	
		$this->bootstrap('view');
		$view = $this->getResource('view');
		
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
		
		//$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
	
		$path = $view->serverUrl().$view->baseUrl();
		
		
		/*$view = $this->getResource('view');
		$view->jQuery()->addStylesheet($path.'/css/ui-custom/jquery-ui-1.8.18.custom.csss')
		->setLocalPath($path.'/js/jquery-1.7.1.min.js')
		->setUiLocalPath($path.'/js/jquery-ui-1.8.18.custom.min.jss');
		$view->jQuery()->enable();
		$view->jQuery()->uiEnable();*/
	
	}
	
	protected function _initNavigation()
	{
		$view = $this->getResource('view');
		$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml','nav');
		
		$nav = new Zend_Navigation($config);
		$view->navigation($nav);
	}
	
	

}


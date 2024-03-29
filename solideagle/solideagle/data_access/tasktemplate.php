<?php


namespace solideagle\data_access;

use solideagle\data_access\database\DatabaseCommand;

class TaskTemplate
{

	// variables
	private $templateName;
	private $templateConfig;

	// getters & setters

	public function getArrayOfObject()
	{
		return get_object_vars($this);
	}

	public function getTemplateName()
	{
		return $this->templateName;
	}

	public function setTemplateName($templateName)
	{
		$this->templateName = $templateName;
	}

	public function getTemplateConfig()
	{
		return $this->templateConfig;
	}

	public function setTemplateConfig($templateConfig)
	{
		$this->templateConfig = $templateConfig;
	}

	// manage types

	public static function addTaskTemplate($taskTemplate)
	{
		$sql = "INSERT INTO  `task_template`
		(
		`template_name`,
		`template_config`
		)
		VALUES
		(
		:template_name,
		:template_config
		);";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":template_name", $taskTemplate->getTemplateName());
		$cmd->addParam(":template_config", serialize($taskTemplate->getTemplateConfig()));

		$cmd->BeginTransaction();

		$cmd->execute();

		$cmd->newQuery("SELECT LAST_INSERT_ID();");

		$retval = $cmd->executeScalar();

		$cmd->CommitTransaction();
		return $retval;
	}

	public static function delTaskTemplateByName($name)
	{
		$sql = "DELETE FROM  `task_template`
		WHERE `template_name` = :template_name;";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":template_name", $name);

		$cmd->execute();
	}

	public static function getTemplateByName($name)
	{
		$sql = "SELECT
		`task_template`.`template_name`,
		`task_template`.`template_config`
		FROM  `task_template`
		WHERE `task_template`.`template_name` = :name;";
			
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":name", $name);

		if(($obj = $cmd->executeReader()->read()) === false)
		{
			return NULL;
		}

		$template = new TaskTemplate();
		$template->setTemplateName($obj->template_name);
		$template->setTemplateConfig(unserialize($obj->template_config));

		return $template;
	}


	public static function getAllTemplates()
	{
		$sql = "SELECT
		`task_template`.`template_name`,
		`task_template`.`template_config`
		FROM  `task_template`;";
			
		$cmd = new DatabaseCommand($sql);
			
		$retarr = array();
			
		$cmd ->executeReader()->readAll(function($rowdata) use (&$retarr){
			$template = new TaskTemplate();
			$template->setTemplateName($rowdata->template_name);
			$template->setTemplateConfig(unserialize($rowdata->template_config));

			$retarr[] = $template;
		});

		return $retarr;
	}

	public function getJson()
	{
		return json_encode(get_object_vars($this));
	}


}



?>
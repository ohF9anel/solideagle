<?php

class csvtransformer
{
	private $fieldnames = array();
	
	public function getFromField($funcname,$fieldname)
	{
		$this->fieldnames[$fieldname] = new csvfield($funcname,$fieldname);
	}
	
	
	
}

class csvfield
{
	public $fieldname;
	public $funcname;
	public $position;
	public $cleanfieldname;
	

	public function __construct($funcname, $fieldname)
	{
		$this->fieldname = $fieldname;
		$fieldname = iconv("UTF-8", "ASCII//TRANSLIT",$fieldname);
		$this->$cleanfieldname = preg_replace("/[^a-z0-9]/", "", $fieldname);
		$this->position = -1;
		$this->funcname = $funcname;
	}
	
}
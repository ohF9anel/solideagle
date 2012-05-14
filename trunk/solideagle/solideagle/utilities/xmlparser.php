<?php

namespace solideagle\utilities;

class XMLParser
{
	private $callback;
	private $elementData = "";
	private $elementname;
	
	function startElement($parser, $name, $attrs) {
		$this->elementData = "";
		$this->elementname = $name;
	}
	
	
	function endElement($parser, $name) {
		if($this->elementname != "")
		{
			call_user_func($this->callback, $this->elementname,$this->elementData);
		}
		$this->elementname = "";
	}
	
	function handleData($parser,$data)
	{
		$this->elementData.= $data;
	}
	
	
	function parse($XMLdata,$callback)
	{
		$this->callback = $callback;
		
		$xmlparser = xml_parser_create();
		
		
		xml_set_element_handler($xmlparser, array(&$this,"startElement"), array(&$this,"endElement"));
		xml_set_character_data_handler($xmlparser,array(&$this,"handleData"));
		
		xml_parse($xmlparser,$XMLdata);
		
		
		xml_parser_free($xmlparser);
	}
	
	

}
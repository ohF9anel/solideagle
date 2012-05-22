<?php

namespace solideagle\utilities;


//machiel's crazy csv parser
class csvparser
{

	private $fieldnames;
	private $separator;
	private $fileptr;
	private $headersParsed = false;
	
	
	/**
	 * The caller is responsible for opening and closing the file
	 *
	 *
	 * @param fileptr $fileptr
	 * @param string $separator
	 */
	public function __construct($fileptr,$separator)
	{
		$this->separator = $separator;
		$this->fileptr = $fileptr;
		
	}
	
	/**
	 * first param will be the property on the stdclass, second is the name in the header of the csv
	 * 
	 * @param string $propertyname
	 * @param string $fieldname
	 */
	public function getFromField($propertyname,$fieldname)
	{
		$this->fieldnames[] = new csvfield($propertyname,$fieldname);
	}
	
	/**
	 * Parses the first line of the csv where the headers should be and remembers the position
	 */
	private function parseHeaders()
	{
		if($this->headersParsed)
		{
			return;
		}
		
		$this->headersParsed = true;
		
		$headercounter = 0;

		foreach (fgetcsv($this->fileptr ,0,$this->separator) as $linearrelem)
		{
			
			//search if document has BOM
			if(substr($linearrelem, 0,3) == pack("CCC",0xef,0xbb,0xbf))
			{
				//remove BOM
				$linearrelem = substr($linearrelem,3);
			}
			
			//find the field
			foreach($this->fieldnames as $val)
			{
				if($val->position > -1) //field already found, don't bother
				{
					continue;
				}
					
				if(strcasecmp($linearrelem, $val->fieldname) === 0)
				{
					$val->position = $headercounter;
					break; //we found it, break loop
				}
			}
			
			$headercounter += 1;
		}
	}
	
	/**
	 * Returns an array with all fields that could not be parsed
	 * @return array
	 */
	public function canParse()
	{
		$this->parseHeaders();
		
		$retarr = array();
		
		foreach($this->fieldnames as $val)
		{
			if($val->position == -1)
			{
				$retarr[] = $val->fieldname;
			}
		}
		
		return $retarr;
	}

	/**
	 * parses each line and returns an array of stdClass objects
	 */
	public function parse()
	{
		$this->parseHeaders();
		
		$retarr = array();
		
		while (($linearr = fgetcsv($this->fileptr ,0,$this->separator)) !== false)
		{
			$class = new \stdClass();
		
			foreach($this->fieldnames as $val)
			{
				//does field exist?
				if($val->position == -1)
				{
					die("Fieldname: " . $val->fieldname . " does not exist!");
					
				}else{
					$class->{$val->propertyname} = $linearr[$val->position];
				}
				
			}
			
			$retarr[] = $class;
		}
		
		return $retarr;
	}
	
	
}

class csvfield
{
	public $fieldname;
	public $propertyname;
	public $position;
	
	public function __construct($propertyname,$fieldname)
	{
		$this->fieldname = $fieldname;
		$this->position = -1;
		$this->propertyname = $propertyname;
	}
	
}
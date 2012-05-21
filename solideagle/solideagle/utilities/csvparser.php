<?php

namespace \solideagle\utilities;

class csvparser
{
	
	private $fieldnames;
	private $separator;
	
	/**
	 * 
	 * @param assoc array $fieldnames
	 * @param string $separator
	 */
	public static function __construct($fieldnames,$separator=";")
	{
		foreach($fieldnames as $fieldname)
		{
			$this->fieldnames[$fieldname] = -1;
		}
		
		$this->separator = $separator;
	}
	
	public static function parseHeaders($fileptr)
	{
		$headercounter = 0;
		
		foreach (fgetcsv($fileptr,0,$separator) as $linearrelem)
		{
			$headercounter += 1;
			
			//find the field
			foreach($fieldnames as $key => $val)
			{
				if($val != -1) //field already found, don't bother
					continue;
				
				if(strcasecmp($linearrelem, $key) === 0)
				{
					$val = $headercounter;
					break; //we found it, break loop
				}
			}
		}
	}
	
	public static function parseFile($fileptr)
	{
		
	}
	
	
}
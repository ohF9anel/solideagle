<?php

namespace solideagle\scripts\exports;


use solideagle\data_access\database\DatabaseCommand;

class exportall
{
	
	public static function getCSV()
	{
	

		
		$sql = "select p.* , t.type_name , 
		pad.enabled as ad_enabled
		,pad.homefolder_path,pga.enabled as ga_enabled,pga.aliasmail,pss.enabled as ss_enabled,x.groups
		from person p 
		left join type_person pt on p.id = pt.person_id 
		left join type t on pt.type_id = t.id
		left join platform_ad pad on p.id = pad.person_id
		left join platform_ga pga on p.id = pga.person_id
		left join platform_ss pss on p.id = pss.person_id
		join ((
		select d.child_id,group_concat(n.name order by n.id separator ' -> ') as groups
		from group_closure d
		join `group` n on (n.id = d.parent_id) group by d.child_id )  as x ) on x.child_id = p.group_id";
		
		$cmd = new DatabaseCommand($sql);
		
		
		$fristtime = true;
		$csvstring= "";
		
		$cmd->executeReader()->readAll(function($data) use (&$csvstring,&$fristtime)
		{
			if($fristtime)
			{
				$fristtime = false;
				$arrwithattr = get_object_vars($data);
				foreach($arrwithattr as $k => $v)
				{
					$csvstring.= $k . ";";
				}
				
				$csvstring.= "\n";
			}
			
			$arrwithattr = get_object_vars($data);
		
			foreach($arrwithattr as $k => $v)
			{
				$csvstring.= $v . ";";
			}
			
			$csvstring.= "\n";
			
			
		});
		
		return $csvstring;
	}
	
}
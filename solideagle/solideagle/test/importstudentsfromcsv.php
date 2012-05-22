<?php

namespace solideagle\scripts;

use solideagle\data_access\Person;
use solideagle\data_access\Group;
use solideagle\data_access\Type;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$row = 1;
$first = true;
if (($handle = fopen("/home/brunommy/leerlingendbz.csv", "r")) !== FALSE) {
    while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if(!$first)
        {
            $p = new Person();
            $p->setAccountUsername($row[13]);
            $p->setFirstName($row[1]);
            $p->setName($row[0]);
            $p->setAccountPassword(Person::generatePassword());
            $p->setGender($row[5]);
            $p->setStreet($row[9]);
            
            $housenumber = $row[10];
            if ($row[11] != "")
                $housenumber .= $row[11];

            $p->setHouseNumber($housenumber);
            $p->setPostCode($row[2]);
            $p->setCity($row[4]);
            
            $email = $row[13] . "@pilot.dbz.be";
            $p->setEmail($email);
            
            $number = "";
            if ($row[8] != "")
            {
                for($i = 0; $i < strlen($row[8]); $i++)
                {
                    if ($row[8][$i] != ' ')
                        $number .= $row[8][$i];
                    else
                        break;
                }
                if ($number[1] == '4')
                    $p->setMobile($number);
                
                $p->setPhone($number);
            }  
            
            $p->setStudentStamnr($row[6]);
            
            $groupname = str_replace(' ', '', $row[3]);
            $group = Group::getGroupByName($groupname);
            $p->setGroupId($group->getId());
            
            //$p->addType(new Type(3));
            
            var_dump(Person::addPerson($p) . " " . $groupname . " " . $row[13]);
            
            // add types
            
            
            
             
            //break;
            $row++;
        }
        $first = false;
        
    }
    fclose($handle);
}


?>

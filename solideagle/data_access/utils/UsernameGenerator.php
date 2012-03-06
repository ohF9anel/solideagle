<?php

namespace Utils;

use Database\DatabaseCommand;

class UsernameGenerator {
    
    public static function CreateUniqueUsername($firstName, $lastName)
    {
        $firstName = str_replace(" ", "", strtolower($firstName));
        $lastName = str_replace(" ", "", strtolower($lastName));
        $userName = $firstName . "." . $lastName;
        $number = 1;

        while(true)
        {
            $sql = "SELECT * FROM `CentralAccountDB`.`person`
                    WHERE `account_username` = :user_name;";
            
            $cmd = new DatabaseCommand($sql);
            $cmd->addParam(":user_name", $userName);

            $retval = $cmd->executeScalar();
            
            if ($retval != null)
            {
                $userName = $firstName . "." . $lastName . $number;
                $number++;
            }
            else
            {
                return $userName;
            }
        }
        
        
    }
    
}

?>

<?php

namespace AD;

require_once('Net/SSH2.php');
require_once 'logging/Logger.php';
require_once 'config.php';

use Logging\Logger;

class ConnectionSsh
{
    
    private static $instance;
    private $conn;
    
    public function __construct()
    {
        
    }
    
    public static function singleton()
    {
            if (!isset(self::$instance)) {
                    $className = __CLASS__;
                    self::$instance = new $className;
            }
            return self::$instance;
    }
    
    public function getConn()
    {
        return $this->conn;
    }


    
    
    
}

?>

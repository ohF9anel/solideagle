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
        $this->conn = new \Net_SSH2('10.3.7.111');
        if (!$this->conn->login(S1_ADMINISTRATOR, AD_PASSWORD))
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nLogin to SSH failed on " . AD_DC_HOST . ".", PEAR_LOG_ERR);
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

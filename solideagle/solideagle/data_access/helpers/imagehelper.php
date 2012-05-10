<?php

namespace solideagle\data_access\helpers;

use solideagle\plugins\StatusReport;

class imagehelper
{

    public static function downloadTempFile($url, $filepath)
    {
        $output = array();
        $ret;
        exec('wget ' . $url . ' -q -O ' . $filepath . " 2>&1", $output, $ret);
        
        if ($ret != 0)
            return new StatusReport(false, "Temp file cannot be downloaded");
        else
            return new StatusReport();
    }
    
    public static function encodeImage($imgPath)
    {
        $fd = fopen ($imgPath, 'rb');
        $size=filesize ($imgPath);
        $cont = fread ($fd, $size);
        fclose ($fd);
        $encimg = base64_encode($cont);

        return $encimg;
    }
    
}
?>

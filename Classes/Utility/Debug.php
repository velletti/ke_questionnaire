<?php

namespace Kennziffer\KeQuestionnaire\Utility;

use TYPO3\CMS\Core\Core\Environment;

class debug
{
    public static function store($resultId , $debug )
    {
        if (File_exists(Environment::getProjectPath() . '/_LOG_KEQ_')) {
            $file = Environment::getProjectPath() . '/var/log/debug_result_update_' . $resultId  . '.txt';
            $stream = fopen($file, 'a+');
            if ($stream) {
                fwrite($stream, " ****************** \n");
                fwrite($stream, date('H:i:s') . " \n");
                fwrite($stream, print_r($debug, true) . " \n");
                fclose($stream);
            }
        }
    }
}
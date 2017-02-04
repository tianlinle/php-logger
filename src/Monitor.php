<?php
namespace Tian\Logger;

/**
* @author wangtianlin
*/
class Monitor
{
    public static function tail($dir, $prefix, $grep = '')
    {
        $file = Logger::getLoggerFilePath($dir, $prefix);
        echo 'file: ' . $file . "\n";
        $handler = fopen($file, 'r');
        $size = filesize($file);
        fseek($handler, $size);
        while (true) {
            clearstatcache();
            $newSize = filesize($file);
            if ($size < $newSize) {
                $data = fread($handler, $newSize - $size);
                $size = $newSize;
                $lines = explode("\n", $data);
                foreach ($lines as $line) {
                    if ($line) {
                        if ($grep) {
                            if (!strstr($line, $grep)) {
                                continue;
                            }
                        }
                        print_r(unserialize($line));
                        echo "\n" . '=================================================================================' . "\n";
                    }
                }
            }
            sleep(1);
        }
    }

    public static function grep($dir, $prefix, $grep, $date = '')
    {
        $file = Logger::getLoggerFilePath($dir, $prefix, $date);
        $lines = file($file, FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strstr($line, $grep)) {
                print_r(unserialize($line));
                echo "\n" . '=================================================================================' . "\n";
            }
        }
    }
}
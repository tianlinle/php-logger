<?php
namespace Tian\Logger;

/**
 * @author wangtianlin
 */
class Logger
{
    const LEVEL_INFO = 1;
    const LEVEL_ERROR = 2;
    const LEVEL_IMPORTANCE = 3;

    protected $dir;
    protected $prefix;
    protected $level;
    protected $stack = [];

    protected static $instances = [];

    public static $levelNameMap = [
        self::LEVEL_INFO => 'LOG_LEVEL_INFO',
        self::LEVEL_ERROR => 'LOG_LEVEL_ERROR',
        self::LEVEL_IMPORTANCE => 'LEVEL_IMPORTANCE',
    ];

    public static function instance($dir, $prefix, $level = self::LEVEL_INFO)
    {
        $name = self::fixDirName($dir) . $prefix;
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new static($dir, $prefix, $level);
        }
        return self::$instances[$name];
    }

    public static function getLoggerFilePath($dir, $prefix, $date = '')
    {
        if ($date) {
            $date = date('.Ymd', strtotime($date));
        } else {
            $date = date('.Ymd');
        }
        return self::fixDirName($dir) . $prefix . $date . '.log';
    }

    public function __construct($dir, $prefix, $level = self::LEVEL_INFO)
    {
        $this->dir = $dir;
        $this->prefix = $prefix;
        $this->level = $level;
    }

    public function __destruct()
    {
        $this->writeAndClean();
    }

    public function importance($message, $e = null)
    {
        $this->log($message, self::LEVEL_IMPORTANCE, $e);
    }

    public function info($message)
    {
        $this->log($message, self::LEVEL_INFO);
    }

    public function error($message, $e = null)
    {
        $this->log($message, self::LEVEL_ERROR, $e);
    }

    public function writeAndClean()
    {
        if (!empty($this->stack) && file_exists($this->dir)) {
            file_put_contents(self::getLoggerFilePath($this->dir, $this->prefix), str_replace("\n", ' ', serialize($this->stack)) . "\n", FILE_APPEND | LOCK_EX);
        }
        $this->stack = [];
    }

    protected function log($message, $level, $e = null)
    {
        if ($this->level <= $level) {
            $this->push($message, $level, $e);
        }
    }

    protected function push($message, $level, $e = null)
    {
        $this->stack[] = [
            'stamp' => date('Y-m-d H:i:s'),
            'level' => self::$levelNameMap[$level],
            'message' => $message,
            'trace' => $e ? explode("\n", $e->getTraceAsString()) : self::trace(),
        ];
    }

    protected static function trace(){
        ob_start();
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $trace = explode("\n", ob_get_contents());
        ob_end_clean();
        return $trace;
    }

    protected static function fixDirName($dir) {
        if ($dir[strlen($dir) - 1] != '/') {
            $dir .= '/';
        }
        return $dir;
    }
}
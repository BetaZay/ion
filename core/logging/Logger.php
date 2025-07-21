<?php

namespace core\logging;

/**
 * Simple application logging system
 * 
 * Provides logging capabilities with different severity levels and file output.
 * Log level can be configured via environment variables.
 */
class Logger
{
    /**
     * Available logging levels in order of increasing severity
     * 
     * @var array<int, string>
     */
    protected static array $levels = ['debug', 'info', 'warning', 'error', 'critical'];
    
    /**
     * Path to the log file
     * 
     * @var string
     */
    protected static string $logFile;
    
    /**
     * Minimum log level that will be recorded
     * 
     * @var string
     */
    protected static string $minLevel;

    /**
     * Initialize the logging system
     * 
     * Sets up the log file path and minimum log level from environment variables.
     * Creates the log directory if it doesn't exist.
     * 
     * @return void
     */
    public static function init(): void
    {
        $envPath = $_ENV['LOG_FILE'] ?? base_path('storage/logs/app.log');
        self::$logFile = is_writable(dirname($envPath)) ? $envPath : base_path('storage/logs/fallback.log');
        self::$minLevel = $_ENV['LOG_LEVEL'] ?? 'debug';

        $dir = dirname(self::$logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    /**
     * Log a message with the specified level
     * 
     * Writes a timestamped log entry if the level is valid and meets the minimum level threshold.
     * 
     * @param string $level The log level ('debug', 'info', 'warning', 'error', 'critical')
     * @param string $message The message to log
     * @return void
     */
    public static function log(string $level, string $message): void
    {
        if (!in_array($level, self::$levels)) return;
        if (array_search($level, self::$levels) < array_search(self::$minLevel, self::$levels)) return;

        $timestamp = date('Y-m-d H:i:s');
        $entry = "[$timestamp] [$level] $message\n";

        file_put_contents(self::$logFile, $entry, FILE_APPEND);
    }

    /**
     * Log a debug message
     * 
     * @param string $msg The message to log
     * @return void
     */
    public static function debug(string $msg): void { self::log('debug', $msg); }
    
    /**
     * Log an info message
     * 
     * @param string $msg The message to log
     * @return void
     */
    public static function info(string $msg): void  { self::log('info', $msg); }
    
    /**
     * Log a warning message
     * 
     * @param string $msg The message to log
     * @return void
     */
    public static function warning(string $msg): void { self::log('warning', $msg); }
    
    /**
     * Log an error message
     * 
     * @param string $msg The message to log
     * @return void
     */
    public static function error(string $msg): void { self::log('error', $msg); }
    
    /**
     * Log a critical message
     * 
     * @param string $msg The message to log
     * @return void
     */
    public static function critical(string $msg): void { self::log('critical', $msg); }
}
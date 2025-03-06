<?php

class Logger
{
    private const LEVELS = [
        'info' => 0,
        'warning' => 1,
        'error' => 2
    ];

    private string $logFile;
    private int $logLevel;

    public function __construct(string $logFile = 'error_log.log', string $logLevel = 'error')
    {
        $this->logFile = $logFile;
        $this->logLevel = self::LEVELS[$logLevel] ?? self::LEVELS['error'];
    }

    public function error(string $message): void
    {
        $this->log('ERROR', $message);
    }

    public function info(string $message): void
    {
        $this->log('INFO', $message);
    }

    public function warning(string $message): void
    {
        $this->log('WARNING', $message);
    }

    private function log(string $level, string $message): void
    {
        if ($this->isLoggable($level)) {
            $timestamp = date('Y-m-d H:i:s');
            file_put_contents(
                $this->logFile,
                "[$timestamp] $level: $message\n",
                FILE_APPEND | LOCK_EX
            );
        }
    }

    private function isLoggable(string $level): bool
    {
        return (self::LEVELS[$level] ?? self::LEVELS['error']) >= $this->logLevel;
    }
}

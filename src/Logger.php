<?php
namespace CMSContentMigrator;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger {
    public function log($level, $message, array $context = []) {
        $logEntry = sprintf(
            "[%s] %s: %s %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            json_encode($context)
        );
        file_put_contents('var/logs/migration.log', $logEntry, FILE_APPEND);
    }
}
?>
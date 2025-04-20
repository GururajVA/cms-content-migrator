<?php
declare(strict_types=1);

namespace CMSContentMigrator\Exceptions;

use Exception;

class MigrationException extends Exception
{
    // Optional: Add custom logic for migration errors
    // Example: Track failed entity IDs
    private array $failedIds = [];

    public function __construct(
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null,
        array $failedIds = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->failedIds = $failedIds;
    }

    public function getFailedIds(): array
    {
        return $this->failedIds;
    }
}
?>
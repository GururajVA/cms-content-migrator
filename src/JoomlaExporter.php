<?php
declare(strict_types=1);

namespace CMSContentMigrator;

use CMSContentMigrator\Exceptions\MigrationException;
use CMSContentMigrator\Logger;

class JoomlaExporter {
    private \mysqli $db;
    private Logger $logger;

    public function __construct(array $config, Logger $logger) {
        $this->db = new \mysqli(
            $config['DB_HOST'],
            $config['DB_USER'],
            $config['DB_PASSWORD'],
            $config['DB_NAME']
        );
        $this->logger = $logger;
    }

    public function exportArticles(string $outputFile): int {
        try {
            $query = "SELECT a.id, a.title, a.introtext, a.fulltext, 
                             c.title AS category, u.name AS author 
                      FROM `#__content` a 
                      LEFT JOIN `#__categories` c ON a.catid = c.id 
                      LEFT JOIN `#__users` u ON a.created_by = u.id 
                      LIMIT 1000"; // Batch processing

            $result = $this->db->query($query);
            $articles = [];
            
            while ($row = $result->fetch_assoc()) {
                $articles[] = $this->transformArticle($row);
            }

            file_put_contents($outputFile, json_encode($articles, JSON_PRETTY_PRINT));
            return count($articles);
        } catch (\Exception $e) {
            $this->logger->error("Export failed: " . $e->getMessage());
            throw new MigrationException("Export failed", 0, $e);
        }
    }

    private function transformArticle(array $row): array {
        return [
            'source_id' => $row['id'],
            'title' => $row['title'],
            'content' => $row['introtext'] . $row['fulltext'],
            'category' => $row['category'],
            'author' => $row['author'],
            'metadata' => [
                'created_at' => date('c'),
                'source' => 'joomla'
            ]
        ];
    }
}
?>  
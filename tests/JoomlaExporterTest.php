<?php
declare(strict_types=1);

namespace CMSContentMigrator\Tests;

use CMSContentMigrator\JoomlaExporter;
use CMSContentMigrator\Logger;
use PHPUnit\Framework\TestCase;

class JoomlaExporterTest extends TestCase {
    private JoomlaExporter $exporter;
    private Logger $logger;

    protected function setUp(): void {
        // Use a test database (e.g., SQLite in-memory)
        $config = [
            'DB_HOST' => 'sqlite::memory:',
            'DB_NAME' => 'test',
            'DB_USER' => 'root',
            'DB_PASSWORD' => '',
        ];

        $this->logger = new Logger();
        $this->exporter = new JoomlaExporter($config, $this->logger);

        // Setup test schema/data
        $pdo = new \PDO('sqlite::memory:');
        $pdo->exec("CREATE TABLE IF NOT EXISTS `#__content` (
            id INTEGER PRIMARY KEY,
            title TEXT,
            introtext TEXT,
            fulltext TEXT,
            catid INTEGER,
            created_by INTEGER
        )");
        $pdo->exec("INSERT INTO `#__content` (title, introtext, fulltext, catid, created_by) 
                    VALUES ('Test Article', 'Intro', 'Full Text', 1, 1)");
    }

    public function testExportArticles(): void {
        $count = $this->exporter->exportArticles('test_articles.json');
        $this->assertEquals(1, $count);
        $this->assertFileExists('test_articles.json');
    }
}
?>
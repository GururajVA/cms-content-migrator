<?php
declare(strict_types=1);

namespace CMSContentMigrator\Tests;

use CMSContentMigrator\Logger;
use CMSContentMigrator\WordPressImporter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class WordPressImporterTest extends TestCase {
    private WordPressImporter $importer;
    private MockHttpClient $mockClient;
    private Logger $logger;

    protected function setUp(): void {
        $this->mockClient = new MockHttpClient();
        $this->logger = new Logger();
        $config = [
            'WP_API_URL' => 'https://fake-wordpress-site/wp-json',
            'WP_USER' => 'test',
            'WP_APP_PASSWORD' => 'test'
        ];

        $this->importer = new WordPressImporter($this->mockClient, $config, $this->logger);
    }

    public function testImportArticles(): void {
        // Mock successful POST to WordPress
        $this->mockClient->setResponseFactory([
            new MockResponse('', ['http_code' => 201]), // POST /posts
            new MockResponse(json_encode([['id' => 1]])) // GET /categories
        ]);

        file_put_contents('test_articles.json', json_encode([[
            'title' => 'Test',
            'content' => 'Content',
            'category' => 'Test Category',
            'author' => 'Admin'
        ]]));

        $imported = $this->importer->importArticles('test_articles.json');
        $this->assertEquals(1, $imported);
    }
}
?>
<?php
declare(strict_types=1);

namespace CMSContentMigrator;

use CMSContentMigrator\Exceptions\MigrationException;
use CMSContentMigrator\Logger;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WordPressImporter {
    private HttpClientInterface $client;
    private Logger $logger;
    private array $config;

    public function __construct(HttpClientInterface $client, array $config, Logger $logger) {
        $this->client = $client;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function importArticles(string $inputFile): int {
        $articles = json_decode(file_get_contents($inputFile), true);
        $imported = 0;

        foreach ($articles as $article) {
            try {
                $response = $this->client->request('POST', $this->config['WP_API_URL'] . '/posts', [
                    'auth_basic' => [$this->config['WP_USER'], $this->config['WP_APP_PASSWORD']],
                    'json' => [
                        'title' => $article['title'],
                        'content' => $article['content'],
                        'status' => 'draft',
                        'categories' => [$this->resolveCategory($article['category'])],
                        'meta' => $article['metadata']
                    ]
                ]);

                if ($response->getStatusCode() === 201) {
                    $imported++;
                }
            } catch (\Exception $e) {
                $this->logger->error("Failed to import {$article['title']}: " . $e->getMessage());
            }
        }
        return $imported;
    }

    private function resolveCategory(string $categoryName): int {
        // Check if category exists or create it
        $response = $this->client->request('GET', $this->config['WP_API_URL'] . '/categories', [
            'query' => ['search' => $categoryName]
        ]);

        $categories = json_decode($response->getContent(), true);
        if (!empty($categories)) return $categories[0]['id'];

        // Create new category
        $response = $this->client->request('POST', $this->config['WP_API_URL'] . '/categories', [
            'json' => ['name' => $categoryName]
        ]);
        return json_decode($response->getContent(), true)['id'];
    }
}
?>
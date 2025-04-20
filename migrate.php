#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use CMSContentMigrator\JoomlaExporter;
use CMSContentMigrator\WordPressImporter;
use CMSContentMigrator\Logger;
use Symfony\Component\HttpClient\HttpClient;

// Inside migrate.php
$config = new Config();
$logger = new Logger();

// Initialize exporter/importer with config
$exporter = new JoomlaExporter(
    $config->getDatabaseConfig(),
    $logger
);

$importer = new WordPressImporter(
    HttpClient::create(),
    $config->getWordPressConfig(),
    $logger
);

// Load config
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$logger = new Logger();
$exporter = new JoomlaExporter($_ENV, $logger);
$importer = new WordPressImporter(HttpClient::create(), $_ENV, $logger);

try {
    $count = $exporter->exportArticles('joomla_articles.json');
    echo "Exported $count articles.\n";
    
    $imported = $importer->importArticles('joomla_articles.json');
    echo "Successfully imported $imported articles.\n";
} catch (\Exception $e) {
    $logger->error("Fatal error: " . $e->getMessage());
    exit(1);
}
?>
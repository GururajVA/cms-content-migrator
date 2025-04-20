<?php
declare(strict_types=1);

namespace CMSContentMigrator;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class Config {
    private array $config = [];

    public function __construct(string $envPath = __DIR__ . '/../') {
        try {
            $dotenv = Dotenv::createImmutable($envPath);
            $dotenv->load();
            $this->config = $_ENV;
        } catch (InvalidPathException $e) {
            throw new \RuntimeException('.env file not found. Copy .env.example to .env');
        }

        // Validate required keys
        $requiredKeys = ['DB_HOST', 'DB_NAME', 'DB_USER', 'WP_API_URL', 'WP_USER', 'WP_APP_PASSWORD'];
        foreach ($requiredKeys as $key) {
            if (!isset($this->config[$key])) {
                throw new \RuntimeException("Missing required config: $key");
            }
        }
    }

    public function get(string $key, mixed $default = null): mixed {
        return $this->config[$key] ?? $default;
    }

    public function getDatabaseConfig(): array {
        return [
            'host' => $this->get('DB_HOST'),
            'name' => $this->get('DB_NAME'),
            'user' => $this->get('DB_USER'),
            'password' => $this->get('DB_PASSWORD', ''),
        ];
    }

    public function getWordPressConfig(): array {
        return [
            'api_url' => $this->get('WP_API_URL'),
            'user' => $this->get('WP_USER'),
            'app_password' => $this->get('WP_APP_PASSWORD'),
        ];
    }
}
?>
# CMS Content Migrator 🚀

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A lightweight CLI tool for migrating content between Joomla and WordPress with basic field mapping and error logging.

## Features ✨

- **Joomla Exporter**: Extract articles with metadata to JSON
- **WordPress Importer**: Create posts via REST API
- **Basic Field Mapping**: Categories, Authors, Content
- **Error Logging**: Detailed migration errors in `var/logs/`
- **Cross-Platform**: Works on Windows/Linux/macOS

## Installation 💻

### Requirements
- PHP 8.1+
- MySQL/MariaDB
- WordPress 5.6+ (with REST API enabled)
- Composer 2.0+

```bash
# Clone repository
git clone https://github.com/your-username/cms-content-migrator.git
cd cms-content-migrator

# Install dependencies
composer install

# Configure environment (Windows users: use copy instead of cp)
cp config/.env.example .env
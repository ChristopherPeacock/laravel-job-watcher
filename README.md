# Laravel Job Watcher

Hot reload for Laravel Jobs — automatically restart your queue workers when job files change during development.

## Problem

When working on Laravel Jobs, you often have to stop and restart the queue worker manually to see your code changes take effect. This repetitive process slows down development and wastes time.

## Solution

**Hot Reload for Laravel Jobs** — inspired by JavaScript frameworks, this tool watches the `app/Jobs` directory and automatically restarts the Laravel queue worker whenever it detects PHP file changes. No more manual restarts; just code and watch your changes take effect immediately.

## Installation

Install via composer:

```bash
composer require christopherpeacock/laravel-job-watcher
```

## Usage

Run from your Laravel project root folder:

```bash
vendor/bin/job-watcher.php
```

The watcher will:

- Monitor the `app/Jobs` directory for any PHP file changes
- Automatically restart the Laravel queue worker upon detecting changes
- Print clear status messages to keep you informed
- Keep your development workflow smooth and uninterrupted

### Example Output

```bash
$ vendor/bin/job-watcher


```

## Requirements

- PHP 8.0 or higher
- Laravel 9.0 or higher

## How It Works

The job watcher uses file system events to monitor changes in your job files. When a change is detected:

1. The current queue worker process is gracefully terminated
2. A new queue worker is started automatically
3. Your updated job code is now active without manual intervention

## Development Benefits

- **Faster Development**: No more manual queue worker restarts
- **Improved Workflow**: Focus on coding, not process management  
- **Real-time Testing**: See your job changes immediately
- **Error Prevention**: Avoid forgetting to restart workers

## Credits

- Christopher Peacock -> (https://github.com/ChristopherPeacock)

---

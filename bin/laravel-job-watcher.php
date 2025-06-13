#!/usr/bin/env php
<?php

/**
 * Laravel Job Watcher 
 * -----------------------------------------
 * Watches app/Jobs for PHP changes and restarts queue:worker
 * Author: Christopher Peacock
 */

$watchDir = getcwd() . '/app/Jobs';
$artisan  = PHP_OS_FAMILY === 'Windows' ? 'php artisan' : './artisan';
$lastModTimes = [];
$queueProcess = null;

echo "Laravel Job Watcher Booted...\n";
echo "Watching: $watchDir\n";
echo "Restarting queue worker on file change...\n";
echo "-------------------------------------------\n";


function startQueueWorker()
{
    echo "Starting queue:work...\n";
    $descriptorspec = [
        0 => ['pipe', 'r'], // stdin
        1 => ['pipe', 'w'], // stdout
        2 => ['pipe', 'w'], // stderr
    ];

    $process = proc_open('php artisan queue:work --no-interaction', $descriptorspec, $pipes);

    if (!is_resource($process)) {
        echo "Failed to start queue:work.\n";
        return null;
    }

    // Non-blocking output stream
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    // You can monitor output elsewhere or in your loop

    return $process;
}


// Kill the running queue worker
function stopQueueWorker(&$process)
{
    if (is_resource($process)) {
        proc_terminate($process);
        proc_close($process);
        echo "Queue worker terminated.\n";
    }
}

$queueProcess = startQueueWorker();

while (true) {
    $changed = false;

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($watchDir)
    );

    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = $file->getRealPath();
            $modTime = $file->getMTime();

            if (!isset($lastModTimes[$path])) {
                $lastModTimes[$path] = $modTime;
                continue;
            }

            if ($lastModTimes[$path] !== $modTime) {
                echo "Change detected in {$file->getFilename()}\n";
                $lastModTimes[$path] = $modTime;
                $changed = true;
            }
        }
    }

    if ($changed) {
        stopQueueWorker($queueProcess);
        $queueProcess = startQueueWorker();
        echo "Queue worker restarted after change.\n";
        echo "-------------------------------------------\n";
    }

    sleep(1);
}

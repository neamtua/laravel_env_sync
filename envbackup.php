<?php
/**
 * Symlink .env files and sync with Dropbox
 */

// Get action
$action = $argv[1] ?? null;
$actions = [
    'backup',
    'restore',
    'uninstall'
];
if (empty($action) || !in_array($action, $actions)) {
    exit('Invalid argument');
}

// Set up paths
$projectsPath = '/Users/neamtua/Sites/homestead';
$dropboxPath = '/Users/neamtua/Dropbox/envs';

// Check if Dropbox path exists and create if not
if (!file_exists($dropboxPath)) {
    mkdir($dropboxPath);
    echo 'Created Dropbox path'.PHP_EOL;
}

// Process files
if ($action === 'backup') {
    $files = shell_exec('find '.$projectsPath.' -mindepth 2 -maxdepth 2 -type f -name ".env*" ! -name ".env.example"');
    $files = explode(PHP_EOL, $files);
    foreach ($files as $file) {
        if (!empty($file)) {
            // Get directory and file name
            $path = explode('/', $file);
            $fileName = basename($file);
            $directory = $path[count($path)-2];
            echo 'Directory '.$directory.' | File name '.$fileName.PHP_EOL;

            // Check if it's already a symlink

            // Backup command
            if (!file_exists($dropboxPath.'/'.$directory)) {
                mkdir($dropboxPath.'/'.$directory);
            }
            if (file_exists($dropboxPath.'/'.$directory.'/'.$fileName)) {
                unlink($dropboxPath.'/'.$directory.'/'.$fileName);
            }
            shell_exec('cp '.$file.' '.$dropboxPath.'/'.$directory.'/'.$fileName);
            shell_exec('rm '.$file);
            shell_exec('ln -sf '.$dropboxPath.'/'.$directory.'/'.$fileName.' '.$file);
        }
    }
} elseif ($action === 'restore') {
    // Restore command
    $files = shell_exec('find '.$dropboxPath.' -mindepth 2 -maxdepth 2 -type f -name ".env*" ! -name ".env.example"');
    $files = explode(PHP_EOL, $files);
    foreach ($files as $file) {
        if (!empty($file)) {
            $path = explode('/', $file);
            $fileName = basename($file);
            $directory = $path[count($path)-2];
            echo 'Directory '.$directory.' | File name '.$fileName.PHP_EOL;

            shell_exec('rm '.$projectsPath.'/'.$directory.'/'.$fileName);
            shell_exec('ln -sf '.$file.' '.$projectsPath.'/'.$directory.'/'.$fileName);
        }
    }
} elseif ($action === 'uninstall') {
    // Uninstall command
    $files = shell_exec('find '.$dropboxPath.' -mindepth 2 -maxdepth 2 -type f -name ".env*" ! -name ".env.example"');
    $files = explode(PHP_EOL, $files);
    foreach ($files as $file) {
        if (!empty($file)) {
            $path = explode('/', $file);
            $fileName = basename($file);
            $directory = $path[count($path)-2];
            echo 'Directory '.$directory.' | File name '.$fileName.PHP_EOL;

            shell_exec('rm '.$projectsPath.'/'.$directory.'/'.$fileName);
            shell_exec('cp '.$file.' '.$projectsPath.'/'.$directory.'/'.$fileName);
        }
    }
}


echo 'Process terminated'.PHP_EOL;
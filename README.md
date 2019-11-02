# laravel_env_sync
Syncs .env files for Laravel when working on multiple workstations.

The way I have set up my deployment is that I have multiple .env files for each environment (.env.stage, .env.live etc.). This script searches for all the .env files in the projects folder and copies them to my Dropbox folder after which it creates a symlink to it in the original location.

If I setup a new machine, all I have to do is run the **restore** command for it to use the files in Dropbox and symlink them in the local folders.

## Setup
Change paths in variables **$projectsPath** and **$dropboxPath** with your locations. You can, of course, use other sync services than Dropbox.

## Usage

**Backup action**

`php envbackup.php backup`

**Restore action**

`php envbackup.php restore`

**Uninstall**

`php envbackup.php uninstall`

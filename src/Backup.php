<?php

namespace Nickcheek\Backup;

class Backup {

    protected $dropbox;
    protected $user;
    protected $pass;
    protected $db;
    protected $path;

    public function __construct($key)
    {
        $this->dropbox = new Dropbox\Dropbox($key);
        $this->db = "live";
        $this->user = "ncheek";
        $this->pass = "tristan5034";
        $this->path = "/var/www/html/scripts/db-backup/";
    }

    public function getBackup()
    {
        $cmd = "mysqldump --hex-blob --routines --skip-lock-tables --log-error=mysqldump_error.log -u {$this->user} -p{$this->pass} {$this->db} > " . $this->path . "{$this->db}-backup.sql";
        exec($cmd);
    }

    public function zipFile()
    {
        exec("zip " . $this->path . "{$this->db}.zip " . $this->path . "{$this->db}-backup.sql");
        unlink($this->path.$this->db."-backup.sql");
    }

    public function uploadFile()
    {
        $this->dropbox->files->upload("/DB-Backups/{$this->db}.zip" , $this->path . "{$this->db}.zip");
        unlink($this->path.$this->db.".zip");
    }
}

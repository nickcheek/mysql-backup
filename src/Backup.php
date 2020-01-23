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
    }

    public function setUsername($username)
    {
        $this->user = username;
    }

    public function setPassword($password)
    {
        $this->pass = $password;
    }

    public function setDatabase($database)
    {
        $this->db = $database;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getBackup()
    {
        $vars = array($this->user, $this->pass,$this->db,$this->path);
        if(!isset($vars)) {
            echo 'One or more required variables have not been set';
            exit;
        }
        $cmd = "mysqldump --hex-blob --routines --skip-lock-tables --log-error=mysqldump_error.log -u {$this->user} -p{$this->pass} {$this->db} > " . $this->path . "{$this->db}-backup.sql";
        exec($cmd);
    }

    public function zipFile()
    {
        $vars = array($this->db,$this->path);
        if(!isset($vars)) {
            echo 'One or more required variables have not been set';
            exit;
        }
        exec("zip " . $this->path . "{$this->db}.zip " . $this->path . "{$this->db}-backup.sql");
        unlink($this->path.$this->db."-backup.sql");
    }

    public function uploadFile()
    {
        $vars = array($this->db,$this->path);
        if(!isset($vars)) {
            echo 'One or more required variables have not been set';
            exit;
        }
        $this->dropbox->files->upload("/DB-Backups/{$this->db}.zip" , $this->path . "{$this->db}.zip");
        unlink($this->path.$this->db.".zip");
    }

    public function test()
    {
        return "Connected";
    }
}

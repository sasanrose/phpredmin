<?php

class Cron_Controller extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->infoModel  = new Info_Model();
        $this->statsModel = new Stats_Model();
    }

    public function indexAction()
    {
        $info = $this->db->info();
        $time = time();

        $this->statsModel->addKey('memory', $info['used_memory'], $time);
        $this->statsModel->addKey('connections', $info['total_connections_received'], $time);
        $this->statsModel->addKey('commands', $info['total_commands_processed'], $time);
        $this->statsModel->addKey('expired_keys', $info['expired_keys'], $time);
        $this->statsModel->addKey('hits', $info['keyspace_hits'], $time);
        $this->statsModel->addKey('misses', $info['keyspace_misses'], $time);
        $this->statsModel->addKey('clients', $info['connected_clients'], $time);
        $this->statsModel->addKey('user_cpu', $info['used_cpu_user'], $time);
        $this->statsModel->addKey('system_cpu', $info['used_cpu_sys'], $time);

        foreach ($this->infoModel->getDbs($info) as $db)
            if (preg_match('/^keys=([0-9]+),expires=([0-9]+)$/', $info["db{$db}"], $matches)) {
                $this->statsModel->addKey("db{$db}:keys", $matches[1], $time);
                $this->statsModel->addKey("db{$db}:expired_keys", $matches[2], $time);
            }
    }
}

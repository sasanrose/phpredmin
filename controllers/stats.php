<?php

class Stats_Controller extends Controller
{
    public function __construct($config)
    {
        parent::__construct($config);

        $this->statsModel = new Stats_Model($this->app->current);
        $this->template   = Template::factory('json');
    }

    public function keysAction()
    {
        $result      = Array();
        $from        = $this->router->query('from', strtotime('-12 hours'));
        $to          = $this->router->query('to', time());
        $hits        = $this->statsModel->getKeys('hits', $from, $to);
        $misses      = $this->statsModel->getKeys('misses', $from, $to);
        $expiredKeys = $this->statsModel->getKeys('expired_keys', $from, $to);

        $result[] = Array('key' => 'Keysapce Misses', 'values' => $hits);
        $result[] = Array('key' => 'Keysapce Hits', 'values' => $misses);
        $result[] = Array('key' => 'Expired Keys', 'values' => $expiredKeys);

        $this->template->render($result);
    }

    public function commandsAction()
    {
        $result      = Array();
        $from        = $this->router->query('from', strtotime('-12 hours'));
        $to          = $this->router->query('to', time());
        $connections = $this->statsModel->getKeys('connections', $from, $to);
        $commands    = $this->statsModel->getKeys('commands', $from, $to);

        $result[] = Array('key' => 'Connections Received', 'values' => $connections);
        $result[] = Array('key' => 'Commands Processed', 'values' => $commands);

        $this->template->render($result);
    }

    public function clientsAction()
    {
        $result  = Array();
        $from    = $this->router->query('from', strtotime('-12 hours'));
        $to      = $this->router->query('to', time());
        $clients = $this->statsModel->getKeys('clients', $from, $to);

        $result[] = Array('key' => 'Clients', 'values' => $clients);

        $this->template->render($result);
    }

    public function memoryAction()
    {
        $result = Array();
        $from   = $this->router->query('from', strtotime('-12 hours'));
        $to     = $this->router->query('to', time());
        $memory = $this->statsModel->getKeys('memory', $from, $to);

        $result[] = Array('key' => 'Memory Usage', 'values' => $memory);

        $this->template->render($result);
    }

    public function dbkeysAction()
    {
        $result = Array();
        $from   = $this->router->query('from', strtotime('-12 hours'));
        $to     = $this->router->query('to', time());

        foreach ($this->infoModel->getDbs($this->db->info()) as $db) {
            $keys     = $this->statsModel->getKeys("db{$db}:keys", $from, $to);
            $result[] = Array('key' => "DB{$db} Keys", 'values' => $keys);
        }

        $this->template->render($result);
    }

    public function dbexpiresAction()
    {
        $result = Array();
        $from   = $this->router->query('from', strtotime('-12 hours'));
        $to     = $this->router->query('to', time());

        foreach ($this->infoModel->getDbs($this->db->info()) as $db) {
            $keys     = $this->statsModel->getKeys("db{$db}:expired_keys", $from, $to);
            $result[] = Array('key' => "DB{$db} Expired Keys", 'values' => $keys);
        }

        $this->template->render($result);
    }

    public function cpuAction()
    {
        $result     = Array();
        $from       = $this->router->query('from', strtotime('-12 hours'));
        $to         = $this->router->query('to', time());
        $user_cpu   = $this->statsModel->getKeys('user_cpu', $from, $to);
        $system_cpu = $this->statsModel->getKeys('system_cpu', $from, $to);

        $result[] = Array('key' => 'User CPU Usage', 'values' => $user_cpu);
        $result[] = Array('key' => 'System CPU Usage', 'values' => $system_cpu);

        $this->template->render($result);
    }
}

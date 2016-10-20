<?php

/*
 * This file is part of the "PHP Redis Admin" package.
 *
 * (c) Faktiva (http://faktiva.com)
 *
 * NOTICE OF LICENSE
 * This source file is subject to the CC BY-SA 4.0 license that is
 * available at the URL https://creativecommons.org/licenses/by-sa/4.0/
 *
 * DISCLAIMER
 * This code is provided as is without any warranty.
 * No promise of being safe or secure
 *
 * @author   Sasan Rose <sasan.rose@gmail.com>
 * @author   Emiliano 'AlberT' Gabrielli <albert@faktiva.com>
 * @license  https://creativecommons.org/licenses/by-sa/4.0/  CC-BY-SA-4.0
 * @source   https://github.com/faktiva/php-redis-admin
 */

class Cron_Controller extends Controller
{
    public function indexAction()
    {
        foreach ($this->app->config['database']['redis'] as $serverId => $server) {
            if (!empty($server['stats']['enable'])) {
                $time = time();

                $db = Db::factory($server);
                $info = $db->info();

                $statsModel = new Stats_Model($server);
                $statsModel->addKey('memory', $info['used_memory'], $time);
                $statsModel->addKey('connections', $info['total_connections_received'], $time);
                $statsModel->addKey('commands', $info['total_commands_processed'], $time);
                $statsModel->addKey('expired_keys', $info['expired_keys'], $time);
                $statsModel->addKey('hits', $info['keyspace_hits'], $time);
                $statsModel->addKey('misses', $info['keyspace_misses'], $time);
                $statsModel->addKey('clients', $info['connected_clients'], $time);
                $statsModel->addKey('user_cpu', $info['used_cpu_user'], $time);
                $statsModel->addKey('system_cpu', $info['used_cpu_sys'], $time);
                if ($info['aof_enabled']) {
                    $statsModel->addKey('aof_size', $info['aof_current_size'], $time);
                    $statsModel->addKey('aof_base', $info['aof_base_size'], $time);
                }

                foreach ($this->infoModel->getDbs($info) as $i) {
                    if (preg_match('/^keys=([0-9]+),expires=([0-9]+)$/', $info["db{$i}"], $matches)) {
                        $statsModel->addKey("db{$i}:keys", $matches[1], $time);
                        $statsModel->addKey("db{$i}:expired_keys", $matches[2], $time);
                    }
                }
            }
        }
    }
}

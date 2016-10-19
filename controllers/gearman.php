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

class Gearman_Controller extends Controller
{
    public function indexAction()
    {
        $config = App::instance()->config;
        $worker = new GearmanWorker();

        $worker->addServer($config['gearman']['host'], $config['gearman']['port']);
        $worker->addFunction('delete_keys', array($this, 'deleteKeys'));
        $worker->addFunction('move_keys', array($this, 'moveKeys'));

        while ($worker->work());
    }

    public function deleteKeys($job)
    {
        $data = unserialize($job->workload());

        Log::factory()->write(Log::NOTICE, "Try to delete: {$data['key']} at {$data['server']['host']}:{$data['server']['port']}, DB: {$data['server']['database']}", 'Gearman');
        
        $db = Db::factory($data['server']);
        $db->changeDB($data['server']['database']);
        
        $keys  = $db->keys($data['key']);
        $count = count($keys);

        if ($count) {
            $db->set("phpredmin:gearman:deletecount:{$data['key']}", $count);
            $db->del("phpredmin:gearman:deleted:{$data['key']}");
            $db->del("phpredmin:gearman:requests:{$data['key']}");

            foreach ($keys as $key) {
                if ($db->delete($key) !== false) {
                    $db->incrBy("phpredmin:gearman:deleted:{$data['key']}", 1);
                    $db->expireAt("phpredmin:gearman:deleted:{$data['key']}", strtotime('+10 minutes'));
                } else {
                    Log::factory()->write(Log::INFO, "Unable to delete {$key}", 'Gearman');
                }
            }

            $db->del("phpredmin:gearman:deletecount:{$data['key']}");
        }
    }

    public function moveKeys($job)
    {
    }
}

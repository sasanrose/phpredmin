<?php

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
        $data = $job->workload();

        Log::factory()->write(Log::INFO, "Try to delete: {$data}", 'Gearman');

        $keys  = $this->db->keys($data);
        $count = count($keys);

        $this->db->set("phpredmin:deletecount:{$data}", $count);
        $this->db->del("phpredmin:deleted:{$data}");
        $this->db->del("phpredmin:requests:{$data}");

        foreach ($keys as $key) {
            if ($this->db->delete($key) !== False) {
                $this->db->incrBy("phpredmin:deleted:{$data}", 1);
                $this->db->expireAt("phpredmin:deleted:{$data}", strtotime('+10 minutes'));
            } else
                Log::factory()->write(Log::INFO, "Unable to delete {$key}", 'Gearman');
        }

        $this->db->del("phpredmin:deletecount:{$data}");
    }

    public function moveKeys($job)
    {
    }
}

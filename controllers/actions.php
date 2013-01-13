<?php

class Actions_Controller extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->infoModel  = new Info_Model();
        $this->statsModel = new Stats_Model();
        $this->template   = Template::factory('json');
    }

    public function resetAction()
    {
        $info = $this->db->info();

        $this->db->resetStats($info);

        $this->template->render(True);
    }

    public function fallAction()
    {
        $this->db->flushAll();

        $this->template->render(True);
    }

    public function fdbAction()
    {
        $this->db->flushDB();

        $this->template->render(True);
    }
}

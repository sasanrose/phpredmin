<?php
class Welcome_Controller extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->infoModel = new Info_Model();
    }

    public function indexAction($db = Null)
    {
        if (isset($db)) {
            $this->db->select($db);
            $this->session->db = $db;
        }

        $info       = $this->db->info();
        $dbs        = $this->infoModel->getDbs($info);
        $selectedDb = $this->session->has('db') ? $this->session->db : $this->app->config['database']['redis']['database'];

        Template::factory()->render('welcome/index', array('dbs'        => $dbs,
                                                           'selectedDb' => $selectedDb));
    }

    public function configAction()
    {
        $config = $this->db->config('GET', '*');

        Template::factory()->render('welcome/config', array('config' => $config));
    }

    public function statsAction()
    {
        Template::factory()->render('welcome/stats');
    }

    public function infoAction()
    {
        $info       = $this->db->info();
        $uptimeDays = floor($info['uptime_in_seconds'] / 86400);
        $dbSize     = $this->db->dbSize();
        $lastSave   = $this->db->lastSave();


        Template::factory()->render('welcome/info', array('info'       => $info,
                                                          'uptimeDays' => $uptimeDays,
                                                          'dbSize'     => $dbSize,
                                                          'lastSave'   => $lastSave));
    }
}

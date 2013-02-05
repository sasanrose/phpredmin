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

        if (!in_array($selectedDb, $dbs)) {
            $this->db->select($dbs[0]);

            $selectedDb        = $dbs[0];
            $this->session->db = $dbs[0];
        }

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

    public function saveAction($async = Null)
    {
        $saved    = isset($async) ? $this->db->save() : $this->db->bgSave();
        $filename = current($this->db->config('GET', 'dbfilename'));

        Template::factory()->render('welcome/save', array('saved' => $saved, 'filename' => $filename));
    }

    public function slowlogAction()
    {
        $support    = False;
        $slowlogs   = Array();
        $serverInfo = $this->db->info('server');
        $count      = $this->inputs->post('count', Null);
        $count      = isset($count) ? $count : 10;

        if (!preg_match('/^(0|1)/', $serverInfo['redis_version']) && !preg_match('/^2\.[0-5]/', $serverInfo['redis_version'])) {
            $slowlogs = $this->db->eval("return redis.call('slowlog', 'get', {$count})");
            $support  = True;
        }

        Template::factory()->render('welcome/slowlog', array('slowlogs' => $slowlogs,
                                                             'support'  => $support,
                                                             'version'  => $serverInfo['redis_version'],
                                                             'count'    => $count));
    }
}

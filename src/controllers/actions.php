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

class Actions_Controller extends Controller
{
    public function __construct($config)
    {
        parent::__construct($config);

        $this->statsModel = new Stats_Model($this->app->current);
        $this->template   = Template::factory('json');
    }

    public function resetAction()
    {
        $this->statsModel->resetStats();

        $this->template->render(true);
    }

    public function fallAction()
    {
        $this->db->flushAll();

        $this->template->render(true);
    }

    public function fdbAction()
    {
        $this->db->flushDB();

        $this->template->render(true);
    }
}

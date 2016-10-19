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

class Strings_Controller extends Controller
{
    public function viewAction($key)
    {
        $edited = null;

        if ($this->router->method == Router::POST) {
            $newvalue = $this->inputs->post('newvalue', null);
            $key      = $this->inputs->post('key', null);

            if (!isset($newvalue) || trim($newvalue) == '' || !isset($key) || trim($key) == '') {
                $edited = false;
            } else {
                $edited = $this->db->set($key, $newvalue);
            }
        }

        $value = $this->db->get(urldecode($key));

        Template::factory()->render('strings/view', array('edited' => $edited, 'key' => urldecode($key), 'value' => $value));
    }

    public function addAction()
    {
        $added = false;

        if ($this->router->method == Router::POST) {
            $value = $this->inputs->post('value', null);
            $key   = $this->inputs->post('key', null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '') {
                $added = $this->db->set($key, $value);
            }
        }

        Template::factory('json')->render($added);
    }
}

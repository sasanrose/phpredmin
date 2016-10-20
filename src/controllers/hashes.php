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

class Hashes_Controller extends Controller
{
    public function addAction()
    {
        $added = false;

        if ($this->router->method == Router::POST) {
            $value   = $this->inputs->post('value', null);
            $key     = $this->inputs->post('key', null);
            $hashkey = $this->inputs->post('hashkey', null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '' && isset($hashkey) && trim($hashkey) != '') {
                if ($this->db->hSet($key, $hashkey, $value) !== false) {
                    $added = true;
                }
            }
        }

        Template::factory('json')->render($added);
    }

    public function viewAction($key)
    {
        $members = $this->db->hGetAll(urldecode($key));

        Template::factory()->render('hashes/view', array('members' => $members, 'key' => urldecode($key)));
    }

    public function deleteAction($key, $member)
    {
        Template::factory('json')->render($this->db->hDel(urldecode($key), urldecode($member)));
    }

    public function delallAction()
    {
        if ($this->router->method == Router::POST) {
            $results = array();
            $values  = $this->inputs->post('values', array());
            $keyinfo = $this->inputs->post('keyinfo', null);

            foreach ($values as $key => $value) {
                $results[$value] = $this->db->hDel($keyinfo, $value);
            }

            Template::factory('json')->render($results);
        }
    }

    public function editAction($key, $member)
    {
        $edited = null;

        if ($this->router->method == Router::POST) {
            $newvalue = $this->inputs->post('newvalue', null);
            $member   = $this->inputs->post('member', null);
            $key      = $this->inputs->post('key', null);

            if (!isset($newvalue) || trim($newvalue) == '' || !isset($key) || trim($key) == '' ||
                !isset($member) || trim($member) == '') {
                $edited = false;
            } elseif ($this->db->hDel($key, $member)) {
                $edited = $this->db->hSet($key, $member, $newvalue);
            }
        }

        $value = $this->db->hGet(urldecode($key), urldecode($member));

        Template::factory()->render('hashes/edit', array('member' => urldecode($member), 'key' => urldecode($key), 'value' => $value, 'edited' => $edited));
    }
}

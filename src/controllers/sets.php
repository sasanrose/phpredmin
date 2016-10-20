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

class Sets_Controller extends Controller
{
    public function addAction()
    {
        $added = false;

        if ($this->router->method == Router::POST) {
            $value = $this->inputs->post('value', null);
            $key   = $this->inputs->post('key', null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '') {
                $added = $this->db->sAdd($key, $value);
            }
        }

        Template::factory('json')->render($added);
    }

    public function viewAction($key)
    {
        $members = $this->db->sMembers(urldecode($key));

        Template::factory()->render('sets/view', array('members' => $members, 'key' => urldecode($key)));
    }

    /**
     * Edit action ( edit members in Sets )
     *
     * @param string $key
     * @param string $member
     */

    public function editAction($key, $member)
    {
        $edited = null;

        if ($this->router->method == Router::POST) {
            $member    = $this->inputs->post('oldmember', null);
            $newmember = $this->inputs->post('newmember', null);
            $key       = $this->inputs->post('key', null);

            if (!isset($newmember) || trim($newmember) == '' || !isset($key) || trim($key) == '') {
                $edited = false;
            } elseif ($this->db->sRem($key, $member)) {
                $edited = $this->db->sAdd($key, $newmember);
            }
        }

        Template::factory()->render('sets/edit', array('member' => urldecode($member), 'key' => urldecode($key), 'edited' => $edited));
    }

    public function deleteAction($key, $value)
    {
        Template::factory('json')->render($this->db->sRem(urldecode($key), urldecode($value)));
    }

    public function delallAction()
    {
        if ($this->router->method == Router::POST) {
            $results = array();
            $values  = $this->inputs->post('values', array());
            $keyinfo = $this->inputs->post('keyinfo', null);

            foreach ($values as $key => $value) {
                $results[$value] = $this->db->sRem($keyinfo, $value);
            }

            Template::factory('json')->render($results);
        }
    }

    public function moveallAction()
    {
        if ($this->router->method == Router::POST) {
            $results     = array();
            $values      = $this->inputs->post('values', array());
            $destination = $this->inputs->post('destination');
            $keyinfo     = $this->inputs->post('keyinfo');

            foreach ($values as $key => $value) {
                $results[$value] = $this->db->sMove($value, $keyinfo, $destination);
            }

            Template::factory('json')->render($results);
        }
    }
}

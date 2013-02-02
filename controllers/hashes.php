<?php

class Hashes_Controller extends Controller
{
    public function addAction()
    {
        $added = False;

        if ($this->router->method == Router::POST) {
            $value   = $this->inputs->post('value', Null);
            $key     = $this->inputs->post('key', Null);
            $hashkey = $this->inputs->post('hashkey', Null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '' && isset($hashkey) && trim($hashkey) != '')
                if ($this->db->hSet($key, $hashkey, $value) !== False)
                    $added = True;
        }

        Template::factory('json')->render($added);
    }

    public function viewAction($key)
    {
        $members = $this->db->hGetAll(urldecode($key));

        Template::factory()->render('hashes/view', Array('members' => $members, 'key' => $key));
    }

    public function deleteAction($key, $member)
    {
        Template::factory('json')->render($this->db->hDel(urldecode($key), urldecode($member)));
    }

    public function delallAction()
    {
        if ($this->router->method == Router::POST) {
            $results = Array();
            $values  = $this->inputs->post('values', array());
            $keyinfo = $this->inputs->post('keyinfo', Null);

            foreach ($values as $key => $value)
                $results[$value] = $this->db->hDel($keyinfo, $value);

            Template::factory('json')->render($results);
        }
    }

    public function editAction($key, $member)
    {
        $edited = Null;

        if ($this->router->method == Router::POST) {
            $newvalue = $this->inputs->post('newvalue', Null);
            $member   = $this->inputs->post('member', Null);
            $key      = $this->inputs->post('key', Null);

            if (!isset($newvalue) || trim($newvalue) == '' || !isset($key) || trim($key) == '' ||
                !isset($member) || trim($member) == '')
                $edited = False;
            elseif ($this->db->hDel($key, $member))
                $edited = $this->db->hSet($key, $member, $newvalue);
        }

        $value = $this->db->hGet(urldecode($key), urldecode($member));

        Template::factory()->render('hashes/edit', Array('member' => $member, 'key' => $key, 'value' => $value, 'edited' => $edited));
    }
}

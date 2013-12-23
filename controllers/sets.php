<?php

class Sets_Controller extends Controller
{
    public function addAction()
    {
        $added = False;

        if ($this->router->method == Router::POST) {
            $value = $this->inputs->post('value', Null);
            $key   = $this->inputs->post('key', Null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '')
                $added = $this->db->sAdd($key, $value);
        }

        Template::factory('json')->render($added);
    }

    public function viewAction($key)
    {
        $members = $this->db->sMembers(urldecode($key));

        Template::factory()->render('sets/view', Array('members' => $members, 'key' => urldecode($key)));
    }

    /**
     * Edit action ( edit members in Sets )
     *
     * @param string $key
     * @param string $member
     */

    public function editAction($key, $member)
    {
        $edited = Null;

        if ($this->router->method == Router::POST) {
            $member    = $this->inputs->post('oldmember',NULL);
            $newmember = $this->inputs->post('newmember', Null);
            $key       = $this->inputs->post('key', Null);

            if (!isset($newmember) || trim($newmember) == '' || !isset($key) || trim($key) == '' ) {
               $edited = False;
            } elseif ($this->db->sRem($key, $member)) {
               $edited = $this->db->sAdd($key,$newmember);
            }

        }

        Template::factory()->render('sets/edit', Array('member' => urldecode($member), 'key' => urldecode($key),'edited' => $edited));
    }

    public function deleteAction($key, $value)
    {
        Template::factory('json')->render($this->db->sRem(urldecode($key), urldecode($value)));
    }

    public function delallAction()
    {
        if ($this->router->method == Router::POST) {
            $results = Array();
            $values  = $this->inputs->post('values', array());
            $keyinfo = $this->inputs->post('keyinfo', Null);

            foreach ($values as $key => $value)
                $results[$value] = $this->db->sRem($keyinfo, $value);

            Template::factory('json')->render($results);
        }
    }

    public function moveallAction()
    {
        if ($this->router->method == Router::POST) {
            $results     = Array();
            $values      = $this->inputs->post('values', array());
            $destination = $this->inputs->post('destination');
            $keyinfo     = $this->inputs->post('keyinfo');

            foreach ($values as $key => $value)
                $results[$value] = $this->db->sMove($value, $keyinfo, $destination);

            Template::factory('json')->render($results);
        }
    }
}

<?php

class Strings_Controller extends Controller
{
    public function viewAction($key)
    {
        $edited = Null;

        if ($this->router->method == Router::POST) {
            $newvalue = $this->inputs->post('newvalue', Null);
            $key      = $this->inputs->post('key', Null);

            if (!isset($newvalue) || trim($newvalue) == '' || !isset($key) || trim($key) == '')
                $edited = False;
            else
                $edited = $this->db->set($key, $newvalue);
        }

        $value = $this->db->get(urldecode($key));

        Template::factory()->render('strings/view', array('edited' => $edited, 'key' => urldecode($key), 'value' => $value));
    }

    public function addAction()
    {
        $added = False;

        if ($this->router->method == Router::POST) {
            $value = $this->inputs->post('value', Null);
            $key   = $this->inputs->post('key', Null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '')
                $added = $this->db->set($key, $value);
        }

        Template::factory('json')->render($added);
    }
}

<?php

class Zsets_Controller extends Controller
{
    public function addAction()
    {
        $added = False;

        if ($this->router->method == Router::POST) {
            $value = $this->inputs->post('value', Null);
            $key   = $this->inputs->post('key', Null);
            $score = $this->inputs->post('score', Null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '' && isset($score) && trim($score) != '') {
                $added = (boolean) $this->db->zAdd($key, (double) $score, $value);
            }
        }

        Template::factory('json')->render($added);
    }
}

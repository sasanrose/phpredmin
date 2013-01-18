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
}

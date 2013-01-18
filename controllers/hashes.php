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
}

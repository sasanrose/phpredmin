<?php

class Lists_Controller extends Controller
{
    public function addAction()
    {
        $added = False;

        if ($this->router->method == Router::POST) {
            $value = $this->inputs->post('value', Null);
            $key   = $this->inputs->post('key', Null);
            $type  = $this->inputs->post('type', Null);
            $pivot = $this->inputs->post('pivot', Null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '' &&
                (isset($type) && in_array($type, array('before', 'after', 'prepend', 'append'))))
                if (($type == 'before' || $type == 'after') && (!isset($pivot) || $pivot == ''))
                    $added = False;
                else
                    switch ($type) {
                        case 'prepend':
                            $added = (boolean) $this->db->lPush($key, $value);
                            break;
                        case 'append':
                            $added = (boolean) $this->db->rPush($key, $value);
                            break;
                        case 'before':
                            $added = (boolean) $this->db->lInsert($key, Redis::BEFORE, $pivot, $value);
                            break;
                        case 'after':
                            $added = (boolean) $this->db->lInsert($key, Redis::AFTER, $pivot, $value);
                            break;
                    }
        }

        Template::factory('json')->render($added);
    }
}

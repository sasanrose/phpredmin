<?php

class Keys_Controller extends Controller
{
    public function searchAction()
    {
        if ($this->router->method == Router::POST) {
            $key = $this->inputs->post('key', Null);

            if (isset($key) && trim($key) != '') {
                $keys = $this->db->keys("{$key}*");

                Template::factory()->render('keys/search', Array('keys' => $keys));
            } else
                Template::factory()->render('invalid_input');
        }
    }

    public function moveAction($key)
    {
        $moved = Null;

        if ($this->router->method == Router::POST) {
            $db  = $this->inputs->post('db', Null);
            $key = $this->inputs->post('key', Null);

            if (!isset($db) || trim($db) == '' || !isset($key) || trim($key) == '')
                $moved = False;
            else
                $moved = $this->db->move($key, $db);
        }

        Template::factory()->render('keys/move', array('moved' => $moved, 'key' => urldecode($key)));
    }

    public function renameAction($key)
    {
        $renamed = Null;

        if ($this->router->method == Router::POST) {
            $newkey = $this->inputs->post('newkey', Null);
            $key    = $this->inputs->post('key', Null);

            if (!isset($newkey) || trim($newkey) == '' || !isset($key) || trim($key) == '')
                $renamed = False;
            else
                $renamed = $this->db->rename($key, $newkey);
        }

        Template::factory()->render('keys/rename', array('renamed' => $renamed, 'key' => urldecode($key)));
    }

    public function expireAction($key)
    {
        $updated = Null;
        $oldttl  = $this->db->ttl(urldecode($key));

        if ($this->router->method == Router::POST) {
            $ttl = $this->inputs->post('ttl', Null);
            $key = $this->inputs->post('key', Null);

            if (!isset($ttl) || trim($ttl) == '' || !isset($key) || trim($key) == '')
                $updated = False;
            else
                if ((int)$ttl > 0)
                    $updated = $this->db->expire($key, $ttl);
                else
                    if ($oldttl > 0)
                        $updated = $this->db->persist($key);
                    else
                        $updated = True;
        }



        Template::factory()->render('keys/ttl', array('updated' => $updated, 'key' => urldecode($key), 'ttl' => $oldttl));
    }

    public function deleteAction($key)
    {
        Template::factory('json')->render($this->db->del(urldecode($key)));
    }

    public function viewAction($key)
    {
        switch ($this->db->type(urldecode($key))) {
            case Redis::REDIS_STRING:
                $this->router->redirect("strings/view/{$key}");
                break;
            case Redis::REDIS_SET:
                $this->router->redirect("sets/view/{$key}");
                break;
            case Redis::REDIS_LIST:
                $this->router->redirect("lists/view/{$key}");
                break;
            case Redis::REDIS_ZSET:
                $this->router->redirect("zsets/view/{$key}");
                break;
            case Redis::REDIS_HASH:
                $this->router->redirect("hashes/view/{$key}");
                break;
        }
    }
}

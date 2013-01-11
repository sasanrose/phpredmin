<?php
class JsonTemplate
{
    public function render($data)
    {
        if ($data)
            header('HTTP/1.0 200 OK');
        else
            header('HTTP/1.0 404 Not Found');

        echo json_encode($data);
        die();
    }
}

<?php
class Welcome_Controller
{
    public function indexAction() {
        Template::factory()->render('welcome/index');
    }
}

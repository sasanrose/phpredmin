<?php
class Welcome_Controller extends Controller
{
    public function indexAction() {
        Template::factory()->render('welcome/index');
    }
}

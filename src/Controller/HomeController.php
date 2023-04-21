<?php

namespace App\Controller;

use App\Controller\BaseController;

class HomeController extends BaseController
{
    public function Home(){
        $this->render("home.html.twig");
    }
}
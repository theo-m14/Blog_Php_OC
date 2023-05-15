<?php

namespace App\Controller;

use App\Controller\BaseController;

class HomeController extends BaseController
{
    public function Home() : void
    {
        $this->render("home.html.twig");
    }
}
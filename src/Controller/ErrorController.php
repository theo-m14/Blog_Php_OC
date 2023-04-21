<?php

namespace App\Controller;

use App\Controller\BaseController;


class ErrorController extends BaseController
{
    public function Show($exception){
            $this->render('error.html.twig', ['exception' => $exception]);
    }
    
}
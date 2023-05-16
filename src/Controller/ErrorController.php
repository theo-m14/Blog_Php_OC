<?php

namespace App\Controller;

use Exception;
use App\Controller\BaseController;


class ErrorController extends BaseController
{
    public function show(Exception $exception) : void
    {
            $this->render('error.html.twig', ['exception' => $exception]);
    }

}

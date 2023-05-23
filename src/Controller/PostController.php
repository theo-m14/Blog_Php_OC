<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Repository\PostRepository;

class PostController extends BaseController
{
    public function readAll(PostRepository $postRepository) : void
    {
        $posts = $postRepository->getAll();
        $this->render("readall.html.twig", ["posts" => $posts]);
    }
}

<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\Post;
use App\Repository\PostRepository;

class PostController extends BaseController
{
    public function readAll(PostRepository $postRepository) : void
    {
        $posts = $postRepository->getAll();
        $this->render("readall.html.twig", ["posts" => $posts]);
    }

    public function postForm() : void
    {
        if(!$this->getUser()){
            header('Location: /');
            return;
        }
        $this->render("add.html.twig");
    }

    public function add(PostRepository $postRepository, string $title, string $caption, string $content) : void
    {
        if(strlen($title) < 4){
            $this->render("add.html.twig", ["error" => "Votre titre doit faire plus de 4 caractères"]);
            return;
        }
        if(strlen($caption) < 4){
            $this->render("add.html.twig", ["error" => "Votre légende doit faire plus de 4 caractères"]);
            return;
        }
        if(strlen($content) < 20){
            $this->render("add.html.twig", ["error" => "Votre contenu doit faire plus de 20 caractères"]);
            return;
        }
        $post = new Post($title,$caption,$content,$this->getUser()["id"],date("Y-m-d H:i:s"));
        $param = ["title","caption","content","user_id","date"];
        $postRepository->insert($post,$param);
        header('Location: /Blog');
    }

    public function readOne(PostRepository $postRepository, int $id) : void
    {
        $post = $postRepository->getByField('id',$id);
        $this->render("readone.html.twig", ['post' => $post]);
    }
}

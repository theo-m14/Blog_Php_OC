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
        if (!$this->getUser()) {
            header('Location: /');
            return;
        }
        $this->render("add.html.twig");
    }

    public function add(PostRepository $postRepository, string $title, string $caption, string $content) : void
    {
        $error = $this->verifPost($title,$caption,$content);
        if (!empty($error)) {
            $this->render("add.html.twig", ["error" => $error]);
            return;
        }
        $post = new Post($title, $caption, $content, $this->getUser()["id"], date("Y-m-d H:i:s"));
        $param = ["title","caption","content","user_id","date"];
        $postRepository->insert($post, $param);
        header('Location: /Blog');
    }

    public function verifPost(string $title, string $caption, string $content) : array
    {
        $error = [];
        if (strlen($title) < 4) {
            $error['title'] = "Votre titre doit faire plus de 4 caractères";
        }
        if (strlen($caption) < 4) {
            $error['caption'] = "Votre légende doit faire plus de 4 caractères";
        }
        if (strlen($content) < 20) {
            $error['content'] = "Votre contenu doit faire plus de 20 caractères";
        }
        return $error;
    }

    public function readOne(PostRepository $postRepository, int $id) : void
    {
        $post = $postRepository->getByField('id', $id);
        $this->render("readone.html.twig", ['post' => $post]);
    }

    public function editForm(PostRepository $postRepository, int $id) : void
    {
        $post = $postRepository->getByField('id', $id);
        if ($this->getUser()['id'] != $post->getUserId()) {
            header('Location: /Blog');
            return;
        }
        $this->render("add.html.twig", ['post' => $post]);
    }

    public function update(PostRepository $postRepository, string $title, string $caption, string $content, string $postId) : void
    {
        //postExist
        //string verif
        $error = $this->verifPost($title,$caption,$content);
        if (!empty($error)) {
            $post = $postRepository->getByField('id', $postId);
            $this->render("add.html.twig", ["error" => $error, "post" => $post]);
            return;
        }
        $postId = intval($postId);
        $post = new Post($title, $caption, $content, $this->getUser()['id'], date("Y-m-d H:i:s"), $postId);
        $params = ['title','caption','content','date','user_id'];
        $postRepository->update($post, $params);
        header('Location: /Blog');
    }
}

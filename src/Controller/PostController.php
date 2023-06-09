<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\Post;
use App\Repository\CommentRepository;
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
            $this->redirectTo('/',403);
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
        $this->redirectTo('/blog',302);
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

    public function readOne(PostRepository $postRepository,CommentRepository $commentRepository, int $id) : void
    {
        $post = $postRepository->getByField('id', $id);
        if (empty($post)) {
            $this->redirectTo('/blog',404);
            return;
        }

        $comments = $commentRepository->getAllByField('post_id',$id);
        $this->render("readone.html.twig", ['post' => $post,'comments' => $comments]);
    }

    public function editForm(PostRepository $postRepository, int $id) : void
    {
        $post = $postRepository->getByField('id', $id);
        if (empty($post) || !$this->isGranted($post)) {
            $this->redirectTo('/blog',403);
            return;
        }
        $this->render("add.html.twig", ['post' => $post]);
    }

    public function update(PostRepository $postRepository, string $title, string $caption, string $content, string $postId) : void
    {
        $post = $postRepository->getByField('id', $postId);
        if (empty($post) || !$this->isGranted($post)) {
            $this->redirectTo('/blog',403);
            return;
        }
        //string verif
        $error = $this->verifPost($title,$caption,$content);
        if (!empty($error)) {
            $this->render("add.html.twig", ["error" => $error, "post" => $post]);
            return;
        }

        $authorId = $post->getUserId();
        $post = new Post($title, $caption, $content, $authorId, date("Y-m-d H:i:s"),intval($postId));
        $params = ['title','caption','content','date'];
        $postRepository->update($post, $params);
        $this->redirectTo('/post/' . $postId ,302);
    }

    public function delete(PostRepository $postRepository, string $csrfToken, int $postId) : void
    {
        $post = $postRepository->getByField('id',$postId);

        if(empty($post)){
            $this->redirectTo('/blog',303,"Vous devez être connecté pour ajouter un commentaire");
            return;
        }


        if(!$this->getUser() || !$this->isGranted($post) || $this->getUser()['csrfToken'] != $csrfToken){
            $this->redirectTo('/blog',303,"Vous devez être propriétaire d'un commentaire pour le modifier");
            return;
        }

        $postRepository->delete($post);
        $this->redirectTo('/blog',302);
    }
}

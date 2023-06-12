<?php

namespace App\Controller;

use Exception;
use App\Entity\Comment;
use App\Controller\BaseController;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;

class CommentController extends BaseController
{
    public function add(PostRepository $postRepository, CommentRepository $commentRepository, string $content, int $postId) : void
    {
        if(!$postRepository->postExistById($postId) || !$this->getUser()){
            $this->redirectTo('/blog',400);
            return;
        }

        if(strlen($content) < 2 ){
            $this->redirectTo('/blog',400);
            return;
        }

        $comment = new Comment($content, $this->getUser()["id"], date("Y-m-d H:i:s"),$postId);
        $param = ["content","user_id","date","post_id"];
        $commentRepository->insert($comment, $param);
        $this->redirectTo('/post/' . $postId,302);
    }

}

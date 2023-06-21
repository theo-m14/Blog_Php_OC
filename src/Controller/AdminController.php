<?php

namespace App\Controller;

use Exception;
use App\Controller\BaseController;
use App\Repository\CommentRepository;


class AdminController extends BaseController
{
    public function panel() : void
    {
        if(!$this->isAdmin())
        {
            $this->redirectTo('/blog', 303);
            return;
        }

        $this->render('panel.html.twig');
    }

    public function getCommentUnverifed(CommentRepository $commentRepository) : void
    {
        if(!$this->isAdmin())
        {
            $this->redirectTo('/blog', 303);
            return;
        }

        $unverifiedComment = $commentRepository->getAllByField('verified','false');

        $this->render('unverifiedComment.html.twig', ['comments' => $unverifiedComment]);
    }

    public function validComment(CommentRepository $commentRepository,string $commentId) : void
    {
        if(!$this->isAdmin())
        {
            $this->redirectTo('/blog', 303);
            return;
        }

        $comment = $commentRepository->getByField('id',$commentId);

        $comment->setVerif(true);
        $params = ['verified'];
        $commentRepository->update($comment, $params);
        $this->redirectTo('/blog/comment/unverified',302);
    }
}

<?php

namespace App\Controller;

use Exception;
use App\Controller\BaseController;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;

class AdminController extends BaseController
{
    public function panel() : void
    {
        if(!$this->isAdmin())
        {
           $this->redirectNonAdmin();
            return;
        }



        $this->render('panel.html.twig');
    }

    public function getCommentUnverifed(CommentRepository $commentRepository) : void
    {
        if(!$this->isAdmin())
        {
           $this->redirectNonAdmin();
            return;
        }

        $unverifiedComment = $commentRepository->getAllByField('verified','false');

        $this->render('unverifiedComment.html.twig', ['comments' => $unverifiedComment]);
    }

    public function validComment(CommentRepository $commentRepository,string $commentId) : void
    {
        if(!$this->isAdmin())
        {
           $this->redirectNonAdmin();
            return;
        }

        $comment = $commentRepository->getByField('id',$commentId);

        $comment->setVerif(true);
        $params = ['verified'];
        $commentRepository->update($comment, $params);
        $this->redirectTo('/blog/comment/unverified',302);
    }

    public function getUsers(UserRepository $userRepository) : void
    {
        if(!$this->isAdmin())
        {
           $this->redirectNonAdmin();
            return;
        }

        $users = $userRepository->getAll();

        $this->render('users.html.twig', ['users' => $users]);
    }

    public function updateRole(UserRepository $userRepository,string $role,string $userId) : void
    {
        if(!$this->isAdmin())
        {
           $this->redirectNonAdmin();
            return;
        }

        $user = $userRepository->getByField('id',$userId);

        if(!$user)
        {
            $this->redirectTo('/users',303,"Cet utilisateur n'existe pas");
            return;
        }

        $allowedRole = ['basic','admin'];


        if(!in_array($role,$allowedRole))
        {
            $this->redirectTo('/users',303,"Ce role n'existe pas");
            return;
        }

        if($role == "basic" && $this->getUser()['role'] !== 'super_admin')
        {
            $this->redirectTo('/users',303,"Vous ne posséder pas les droits pour faire ce changement");
            return;
        }

        $roleId = $userRepository->getRoleId($role);


        $user->setRole($roleId);
        $params = ['role_id'];
        $userRepository->update($user,$params);
        $this->redirectTo('/users',302);
    }

    public function redirectNonAdmin() : void
    {
        $this->redirectTo('/blog', 303);
    }
}

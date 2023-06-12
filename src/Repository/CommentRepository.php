<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use App\Repository\UserRepository;


class CommentRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct("comment", "Comment");
    }

    public function getAll() : array
    {
        $comments = parent::getAll();
        foreach ($comments as $comment) {
            //Set author username using post.user_id relation
           $comment->setAuthor($this->loadAuthor($comment->getUserId()));
        }
        return $comments;
    }

    public function getByField(string $fieldName, mixed $fieldValue): mixed
    {
        $comment = parent::getByField($fieldName, $fieldValue);
        if($comment){
            $comment->setAuthor($this->loadAuthor($comment->getUserId()));
        }
        return $comment;
    }

    public function getAllByField(string $fieldName, mixed $fieldValue): array
    {
        $comments = parent::getAllByField($fieldName,$fieldValue);
        foreach ($comments as $comment) {
            $comment->setAuthor($this->loadAuthor($comment->getUserId()));
        }
        return $comments;
    }

    public function loadAuthor(int $id) : string
    {
        $userRepository = new UserRepository();
        return $userRepository->getByField('id', $id)->getUsername();
    }

}

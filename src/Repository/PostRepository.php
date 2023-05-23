<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\BaseRepository;
use App\Repository\UserRepository;


class PostRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct("post","Post");
    }

    public function getAll() : array
    {
        $posts = parent::getAll();
        foreach ($posts as $post)
        {
           $post->setAuthor($this->loadAuthor($post->getUserId()));
        }
        return $posts;
    }

    public function getByField(string $fieldName, mixed $fieldValue): mixed
    {
        $post = parent::getByField($fieldName,$fieldValue);
        $post->setAuthor($this->loadAuthor($post->getUserId()));
        return $post;
    }

    public function loadAuthor(int $id) : string
    {
        $userRepository = new UserRepository();
        return $userRepository->getByField('id',$id)->getUsername();
    }

}

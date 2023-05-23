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
        $req = $this->bdd->prepare("SELECT * FROM post");
        $req->execute();
        $req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE,$this->object);
        $posts = $req->fetchAll();
        foreach ($posts as $post)
        {
           $post->setAuthor($this->loadAuthor($post->getUserId()));
        }
        return $posts;
    }

    public function loadAuthor(int $id) : string
    {
        $userRepository = new UserRepository();
        return $userRepository->getById($id)->getUsername();
    }

    public function getById(int $id) : mixed
    {
        $req = $this->bdd->prepare("SELECT * FROM post WHERE id=?");
		$req->execute(array($id));
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE,$this->object);
		return $req->fetch();
    }

}

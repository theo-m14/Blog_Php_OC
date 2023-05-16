<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\BaseRepository;


class UserRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct("user","User");
    }

    public function getByMail(string $mail) : User|bool
    {
        $req = $this->bdd->prepare("SELECT * FROM user WHERE mail=?");
        $req->execute(array($mail));
        $req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE,$this->getObject());
        $user = $req->fetch();
        if($user){
            return $this->retrieveRole($user);
        }else{
            return $user;
        }
    }

    public function userExistByMail(string $mail) : bool{
        $req = $this->bdd->prepare("SELECT COUNT(*) FROM user WHERE mail=? ");
        $req->execute(array($mail));
        $result = $req->fetch();
        return $result[0] > 0;
    }

    public function userExistByUsername(string $username) : bool{
        $req = $this->bdd->prepare("SELECT COUNT(*) FROM user WHERE username=? ");
        $req->execute(array($username));
        $result = $req->fetch();
        return $result[0] > 0;
    }

    public function retrieveRole(User $user) : User
    {
        $req = $this->bdd->prepare("SELECT name FROM role WHERE id=?");
        $req->execute(array($user->getRole()));
        $role = $req->fetch(\PDO::FETCH_ASSOC)['name'];
        $user->setRole($role);
        return $user;
    }
}

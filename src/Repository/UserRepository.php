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
        $user = parent::getByField('mail',$mail);
        if ($user) {
            return $this->retrieveRole($user);
        }else{
            return $user;
        }
    }

    public function userExistByField(string $fieldName, string $fieldValue) : bool
    {
        $sql = "SELECT COUNT(*) FROM user WHERE ". $fieldName ."=? ";
        $req = $this->bdd->prepare($sql);
        $req->execute(array($fieldValue));
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

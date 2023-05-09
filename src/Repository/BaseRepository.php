<?php

namespace App\Repository;

use App\Core\Bdd;
use App\Exception\PropertyNotFoundException;


class BaseRepository
{
    private $table;
    private $object;
    protected $bdd;

    public function __construct($table,$object)
    {
        $this->table = $table;
        $this->object = 'App\Entity\\' . $object;
        $this->bdd = Bdd::getInstance();
    }

    public function getById($id)
    {
        $req = $this->bdd->prepare("SELECT * FROM " . $this->table . " WHERE id=?");
		$req->execute(array($id));
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE,$this->object);
		return $req->fetch();
    }

    public function getAll()
    {
        $req = $this->bdd->prepare("SELECT * FROM " . $this->table);
        $req->execute();
        $req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE,$this->object);
        return $req->fetchAll();

    }

    public function create($object,$param)
    {
        //Count number of parameter
        $paramNumber = count($param);
        //fill array with ?
        $valueArray = array_fill(1,$paramNumber,"?");
        //Transform array in string separate with , 
        $valueString = implode(", ",$valueArray);
        $sql = "INSERT INTO " . $this->table . "(" . implode(", ",$param) . ") VALUES(" . $valueString . ")";
        $req = $this->bdd->prepare($sql);
        var_dump($sql);
        $boundParam = array();
        foreach($param as $paramName)
        {
            if(property_exists($object,$paramName))
			{
				$boundParam[] = $object->generalGetter($paramName);	
			}
			else
			{
				throw new PropertyNotFoundException($this->object,$paramName);	
			}
        }
        var_dump($boundParam);
        $req->execute($boundParam);
        var_dump('test');
    }

    public function delete($obj)
    {
        if(property_exists($obj,"id"))
			{
				$req = $this->bdd->prepare("DELETE FROM " . $this->table . " WHERE id=?");
				return $req->execute(array($obj->id));
			}
			else
			{
				throw new PropertyNotFoundException($this->object,"id");	
			}
    }

    public function getObject()
    {
        return $this->object;
    }
}
<?php

namespace App\Repository;

use PDO;
use App\Core\Bdd;
use App\Exception\PropertyNotFoundException;


class BaseRepository
{
    private string $table;
    protected mixed $object;
    protected PDO $bdd;

    public function __construct(string $table,string $object)
    {
        $this->table = $table;
        $this->object = 'App\Entity\\' . $object;
        $this->bdd = Bdd::getInstance();
    }


    public function insert(mixed $object,array $param) : void
    {
        //Count number of parameter
        $paramNumber = count($param);
        //fill array with ?
        $valueArray = array_fill(1,$paramNumber,"?");
        //Transform array in string separate with ,
        $valueString = implode(", ",$valueArray);
        $sql = "INSERT INTO " . $this->table . "(" . implode(", ",$param) . ") VALUES(" . $valueString . ")";
        $req = $this->bdd->prepare($sql);
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
        $req->execute($boundParam);
    }

    public function delete(mixed $obj) : bool
    {
        if(property_exists($obj,"id"))
			{
				$req = $this->bdd->prepare("DELETE FROM ? WHERE id=?");
				return $req->execute(array($this->table,$obj->id));
			}
			else
			{
				throw new PropertyNotFoundException($this->object,"id");
			}
    }

    public function getObject() : mixed
    {
        return $this->object;
    }
}

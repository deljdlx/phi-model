<?php

namespace Phi\Model;

use ImGrowth\Entity\Node;
use ImGrowth\Repository\NodeRecord;
use Phi\Database\Source;
use Phi\Model\Interfaces\Storage;

abstract class Repository  implements Storage
{

    /**
     * @var Source
     */
    protected $database;
    protected static $tableName = null;


    public abstract function store(Entity $entity);

    public function __construct(Source $database)
    {
        $this->database = $database;
    }

    public function getSource()
    {
        return $this->database;
    }

    public static function getTableName()
    {
        return static::$tableName;
    }

    public function queryAndFetch($query, $parameters = null)
    {
        return $this->database->queryAndFetch($query, $parameters);
    }

    public function queryAndFetchOne($query, $parameters = null)
    {
        return $this->database->queryAndFetchOne($query, $parameters);
    }

    public function queryAndFetchValue($query, $parameters = null)
    {
        return $this->database->queryAndFetchValue($query, $parameters);
    }

    public function getLastInsertId($name = null)
    {
        return $this->database->getLastInsertId($name);
    }

    public function escape($value, $type = null)
    {
        return $this->database->escape($value, $type);
    }


}
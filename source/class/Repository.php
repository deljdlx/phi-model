<?php

namespace Phi\Model;

use Phi\Database\Source;
use Phi\Model\Interfaces\Storage;

abstract class Repository  implements Storage
{

    /**
     * @var Source
     */
    protected $database;
    protected static $tableName = null;


    public abstract function store(Entity $entity, $dryRun =false);


    public static function getTableName()
    {
        return static::$tableName;
    }


    public function __construct(Source $database)
    {
        $this->database = $database;
    }

    public function getSource()
    {
        return $this->database;
    }


    public function query($query, $parameters = null)
    {
        return $this->database->query($query, $parameters);
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

    public function escapeField($value, $type = null)
    {
        return $this->database->escapeField($value, $type);
    }



    public function getAll($extraQuery = '', $indexBy = null)
    {
        $rows = $this->queryAndFetch('SELECT * FROM '.$this->getTableName().' '.$extraQuery);
        $dataset = $this->getDataset($rows);

        if($indexBy) {
            $indexedArray = array();
            foreach ($dataset as $instance) {
                $indexedArray[$instance->getValue($indexBy)] = $instance;
            }
            return $indexedArray;
        }
        else {
            return $dataset;
        }



    }




    /**
     * @return \Phi\Model\Entity
     */
    public function getEntityInstance($cast = null)
    {
        if(!$cast) {
            $entityClassName = str_replace('\Repository\\', '\Entity\\', get_class($this));
        }
        else {
            $entityClassName = $cast;
        }

        $instance = new $entityClassName($this);
        return $instance;
    }

    public function getDataset($rows, $cast = null, $valueFilter = null)
    {

        $dataset = [];
        foreach ($rows as $values) {
            $instance = $this->getEntityInstance($cast);

            if($valueFilter) {
                foreach ($values as $key => $value) {
                    if(strpos($key, $valueFilter) === 0) {
                        $field = preg_replace('`^'.$valueFilter.'`', '', $key);
                        $instance->setValue($field, $value);
                    }
                }
            }
            else {
                $instance->setValues($values);
            }


            $dataset[] = $instance;
        }


        return new \Phi\Model\Dataset($dataset, $this);
    }








    public function reset()
    {
        $this->database->query("DROP TABLE ".$this->getTableName()."");
        $this->initialize();
        return $this;
    }



    public function drop()
    {
        $this->database->query("DROP TABLE ".$this->getTableName()."");
        return $this;
    }

    public function flush()
    {
        $this->database->query("DELETE FROM TABLE ".$this->getTableName()."");
        return $this;
    }





}
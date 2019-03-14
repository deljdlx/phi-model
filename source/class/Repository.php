<?php

namespace Phi\Model;

use Phi\Database\FieldDescriptor;
use Phi\Database\Source;
use Phi\Model\Interfaces\Storage;

abstract class Repository  implements Storage
{

    /**
     * @var Source
     */
    protected $source;
    protected static $tableName = null;


    public abstract function store(Entity $entity, $dryRun =false);


    public static function getTableName()
    {
        return static::$tableName;
    }


    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource(Source $source)
    {
        $this->source = $source;
    }


    public function query($query, $parameters = null)
    {
        return $this->source->query($query, $parameters);
    }

    public function getCompiledQuery($query, $parameters = null)
    {
        return $this->source->getCompiledQuery($query, $parameters);
    }


    public function queryAndFetch($query, $parameters = null)
    {
        return $this->source->queryAndFetch($query, $parameters);
    }

    public function queryAndFetchOne($query, $parameters = null)
    {
        return $this->source->queryAndFetchOne($query, $parameters);
    }

    public function queryAndFetchValue($query, $parameters = null)
    {
        return $this->source->queryAndFetchValue($query, $parameters);
    }

    public function getLastInsertId($name = null)
    {
        return $this->source->getLastInsertId($name);
    }

    public function escape($value, $type = null)
    {
        return $this->source->escape($value, $type);
    }

    public function escapeField($value, $type = null)
    {
        return $this->source->escapeField($value, $type);
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
     * @return FieldDescriptor[]
     */
    public function getTableDescriptor()
    {
        return $this->source->getDescriptor($this->getTableName());
    }


    //public function




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
        $this->source->query("DROP TABLE ".$this->getTableName()."");
        $this->initialize();
        return $this;
    }



    public function drop()
    {
        $this->source->query("DROP TABLE ".$this->getTableName()."");
        return $this;
    }

    public function flush()
    {
        $this->source->query("DELETE FROM TABLE ".$this->getTableName()."");
        return $this;
    }





}
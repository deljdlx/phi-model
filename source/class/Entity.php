<?php
namespace Phi\Model;


use Phi\Model\Interfaces\Storage;
use Phi\Traits\Introspectable;

class Entity implements \JsonSerializable
{
    use Introspectable;

    protected $values = [];
    protected $oldValues = [];
    //protected $__values = [];

    /**
     * @var Storage
     */
    protected $repository;




    public function __construct($repository = null)
    {
        if($repository) {
            $this->setRepository($repository);
        }

        /*
        $this->values = array_merge($this->values, $this->__values);

        $parents = $this->getParentClasses();

        foreach ($parents as $parent) {
            $instance = new $parent($this->getRepository());

            if(property_exists($instance, '__values')) {
                foreach ($instance->__values as $key => $value) {
                    if(!array_key_exists($key, $this->values)) {
                        $this->values[$key] = $value;
                    }
                }
            }
        }
        */
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = $this->getValues();

        return $this->doAfterSerialize($data);
    }

    public function toJson()
    {
        return json_encode($this);
    }


    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setValue($key, $value) {
        $this->values[$key]=$value;
        return $this;
    }

    public function isFieldUpdated($fieldName)
    {


        if($value = $this->getValue($fieldName)) {
            if(array_key_exists($fieldName, $this->oldValues)) {

                if($this->oldValues[$fieldName] == $value) {
                    return false;
                }
                else {
                    return true;
                }
            }
            else {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getValue($key)
    {
        if (array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }
        else {
            return null;
        }
    }

    public function getOldValues()
    {
        return $this->oldValues;
    }

    public function getOldValue($key)
    {
        if (array_key_exists($key, $this->oldValues)) {
            return $this->oldValues[$key];
        }
        else {
            return null;
        }
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValues(array $values)
    {
        foreach ($values as $key => $value) {
            $this->setValue($key, $value);
        }
        return $this;
    }

    public function getValues()
    {
        return $this->values;
    }


    /**
     * @param Storage $storage
     * @return $this
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @return Repository
     */
    public function getRepository($className = null)
    {
        if($className === null) {
            return $this->repository;
        }
        else {
            return $this->getApplication()->getModel()->getRepository($className);
        }
    }


    /**
     * @return \Phi\Database\Source
     *
     */
    public function getSource()
    {
        return $this->repository->getSource();
    }



    /**
     * @return $this
     */

    public function store($dryRun = false)
    {
        $this->repository->store($this, $dryRun);
        return $this;
    }

    public function doBeforeInsert()
    {
        return $this;
    }

    public function doBeforeUpdate()
    {
        return $this;
    }

    public function doAfterSerialize($data)
    {
        return $data;
    }





}
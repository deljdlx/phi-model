<?php
namespace Phi\Model;


use Phi\Model\Interfaces\Storage;

class Entity implements \JsonSerializable
{

    protected $values = array();

    /**
     * @var Storage
     */
    protected $repository;


    public function __construct($repository = null)
    {
        if($repository) {
            $this->setRepository($repository);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->values;
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

    /**
     * @param array $values
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = $values;
        return $this;
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
     * @return Storage
     */
    public function getRepository()
    {
        return $this->repository;
    }


    /**
     * @return $this
     */

    public function store()
    {
        $this->repository->store($this);
        return $this;
    }

}
<?php

namespace Phi\Model;



use Phi\Traits\Iterator;

class Dataset implements \Iterator, \ArrayAccess, \JsonSerializable, \Countable
{
    use Iterator;

    /** @var  Repository */
    protected $repository;


    public function __construct(array $values = array(), Repository $repository = null)
    {
        $this->repository = $repository;
        $this->setValues($values);
    }


    /**
     * @param Dataset $dataset
     * @return $this
     */
    public function loadFromDataset(Dataset $dataset)
    {
        $this->repository = $dataset->getRepository();
        $this->setValues(
           $dataset->getValues()
        );

        return $this;
    }


    public function first()
    {
        if($this->offsetExists(0)) {
            return $this[0];
        }
        else {
            return null;
        }
    }

    public function count()
    {
        return $this->length();
    }

    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return Entity[]
     */
    public function getEntities()
    {
        return $this->getValues();
    }



    public function indexBy($fieldName)
    {
        $values = $this->getAll();

        $indexedBy = array();

        foreach ($values as $entity) {
            $indexedBy[$entity->getValue($fieldName)] = $entity;
        }

        $this->setValues($indexedBy);

        return $this;

    }

    public function jsonSerialize()
    {
        return array(
            'repository' => $this->repository,
            'entities' => $this->getEntities()
        );
    }


}
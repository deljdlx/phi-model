<?php

namespace Phi\Model;


class Segment implements \JsonSerializable
{

    /**
     * @var Entity[]
     */
    protected $entities;

    /**
     * @var Repository
     */
    protected $repository;


    protected $offset;

    protected $limit;

    protected $totalRows;

    public function __construct(Repository $repository, $offset = null, $limit = null, $totalRows = null)
    {

        $this->repository = $repository;

        $this->totalRows = $totalRows;
        $this->offset = $offset;
        $this->limit = $limit;
    }


    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
        return $this;
    }



    public function setEntities(array $entities)
    {
        $this->entities = $entities;
        return $this;
    }

    public function setTotal($total)
    {
        $this->totalRows = $total;
        return $this;
    }

    public function setFields(array $fields)
    {
        $this->selectedFields = $fields;
        return $this;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getEntities()
    {
        return $this->entities;
    }

    public function length()
    {
        return count($this->entities);
    }


    public function isEmpty()
    {
        if(!count($this->entities)) {
            return true;
        }
        return false;
    }


    public function jsonSerialize()
    {

        //=======================================================


        $segmentCount = 0;
        $currentSegment = 0;
        if($this->limit) {
            $segmentCount = ceil($this->totalRows/$this->limit);
            $currentSegment = floor($this->offset/$this->limit);
        }

        //=======================================================

        $response = array(
            'metadata' => array(
                'count' => $this->totalRows,
                'segment' => array(
                    'offset' => $this->offset,
                    'limit' => $this->limit,
                    'count' => $segmentCount,
                    'currentIndex' => $currentSegment,
                )
            ),
            'entities' => $this->entities
        );

        return $response;


    }



}

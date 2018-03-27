<?php
namespace Phi\Model\Interfaces;


use Phi\Model\Entity;

interface Storage
{
    public function store(Entity $entity);
}
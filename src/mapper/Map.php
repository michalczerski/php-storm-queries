<?php

namespace Storm\Query\mapper;

class Map
{
    private array $many = [];
    private array $ones = [];

    public static function create(string $class, string $id, array $fields): Map
    {
        return new Map($class, $id, $fields);
    }

    public function __construct(private string $className, private string $id, private array $fields)
    {
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getPk(): string
    {
        return $this->id;
    }

    public function getColumns(): array
    {
        return $this->fields;
    }

    public function hasOne(string $field, Map $map): Map
    {
        $this->ones[$field] = $map;
        return $this;
    }

    public function getOnes(): array
    {
        return $this->ones;
    }

    public function hasMany(string $field, Map $map): Map
    {
        $this->many[$field] = $map;
        return $this;
    }

    public function getMany(): array
    {
        return $this->many;
    }
}
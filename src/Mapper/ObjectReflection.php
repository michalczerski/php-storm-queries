<?php

namespace Storm\Query\Mapper;

use ReflectionClass;

class ObjectReflection
{
    private ReflectionClass $reflection;
    private object $object;

    public function __construct($object)
    {
        $this->reflection = new ReflectionClass($object);
        $this->object = $object;
    }

    public function isInitialized($field): bool
    {
        if ($this->reflection->hasProperty($field)) {
            $property = $this->reflection->getProperty($field);
            if ($property->hasType()) {
                return $property->isInitialized($this->object);
            }
            else
            {
                return $property->getValue($this->object) != null;
            }
        }
        else if (property_exists($this->object, $field)) {
            return true;
        }
        return false;
    }
}
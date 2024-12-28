<?php

namespace Storm\Query;

use DateTime;
use InvalidArgumentException;
use ReflectionClass;

class Mapper
{
    public static function map(array $records, Map $map): array
    {
        if (!count($records)) {
            return $records;
        }

        $array = [];
        foreach($records as $record) {
            self::mapRecordAsMany($array, $record, $map);
        }
        return $array;
    }

    public static function mapRecordAsMany(array &$array, $record, Map $map): void
    {
        $pkColumn = $map->getPk();
        if (!property_exists($record, $pkColumn)) {
            throw new InvalidArgumentException("Column '$pkColumn' does not exist");
        }
        $value = $record->$pkColumn;
        if ($value == null) {
            return;
        }
        if (array_key_exists($pkColumn, $map->getColumns())) {
            $pkColumn = $map->getColumns()[$pkColumn];
        }

        $object = self::getByPk($array, $pkColumn, $value);
        if ($object == null) {
            $object = self::createObject($map->getClassName());
            $array[] = $object;
        }

        self::mapObject($object, $record, $map);
    }

    private static function mapObject($object, $record, $map): void
    {
        self::copyColumnsToFields($object, $record, $map);

        $reflection = new ObjectReflection($object);
        foreach($map->getMany() as $field => $manyMap) {
            if (!$reflection->isInitialized($field)) {
                $object->$field = array();
            }
            self::mapRecordAsMany($object->$field, $record, $manyMap);
        }

        foreach($map->getOnes() as $field => $oneMap) {
            if (!$reflection->isInitialized($field)) {
                $object->$field = self::createObject($oneMap->getClassName());
            }
            self::mapObject($object->$field, $record, $oneMap);
        }
    }

    private static function copyColumnsToFields(object $object, object $record, Map $map): void
    {
        $rp = new ReflectionClass($object);
        foreach($map->getColumns() as $column => $field ) {
            $hasProperty = $rp->hasProperty($field);
            $typeProperty = "";
            if ($hasProperty and $rp->getProperty($field)->hasType()) {
                $typeProperty = $rp->getProperty($field)->getType()->getName();
            }
            if ($typeProperty == 'DateTime') {
                $rp->getProperty($field)->setValue($object, new DateTime($record->$column));
            }
            else {
                $object->$field = $record->$column;
            }
        }
    }

    private static function getByPk(array $mapped, $column, $value): ?object
    {
        foreach ($mapped as $record) {
            if ($record->$column == $value) {
                return $record;
            }
        }

        return null;
    }

    private static function createObject(string $className): object
    {
        $reflect  = new ReflectionClass($className);
        return $reflect->newInstance();
    }
}
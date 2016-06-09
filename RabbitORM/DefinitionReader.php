<?php


namespace RabbitORM;


use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;


class DefinitionReader {

    public function getPropertyDefinition(ReflectionProperty $property, ReflectionClass $class) {

        $jsonDefinition = $class->getConstant($property->getName() . "Definition");
        return json_decode($jsonDefinition);
    }

    public function getClassDefinition(ReflectionClass $class) {
        $jsonDefinition = $class->getConstant($class->getName() . "Definition");
        return json_decode($jsonDefinition);
   }


}